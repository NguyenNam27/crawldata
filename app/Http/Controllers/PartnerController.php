<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Partner;
use App\Models\Product;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

class PartnerController extends Controller
{
    public function listPartner(){
        $partnerList = DB::table('partners')
//            ->where('status',1)
            ->orderBy('id','desc')
            ->paginate(10);
        return view('partner.list',[
            'partnerList'=>$partnerList
        ]);
    }
    public function addPartner(){
        return view('partner.create');
    }
    public function savePartner(Request $request){
        $data = [
            'name'=> $request->name,
            'url'=> $request->url,
            'category_id'=> $request->category_id,
            'values'=>[
                'class_parent'=>$request->input('values_cha'),
                'class_name'=>$request->input('values_name'),
                'class_price'=>$request->input('values_price'),
                'class_link'=>$request->input('values_link'),
            ],
        ];

        Partner::create($data);
        Session::put('message','Thêm đối tác thành công');
        return Redirect::to('list-partner');
    }
    public function edit_partner($id){
        $edit_partner = DB::table('partners')->where('id',$id)->first();
        $jsonData = $edit_partner->values;
        $decodeData=json_decode($jsonData);

        return view('partner.edit',[
            'edit_partner'=>$edit_partner,
            'decodeData'=>$decodeData
        ]);
    }
    public function update_partner(Request $request,$id){
        $data = [
            'name'=>$request->name,
            'url'=>$request->url,
            'category_id' => $request->input('category_id'),
            'values'=>[
                'class_parent'=>$request->input('values_cha'),
                'class_name'=>$request->input('values_name'),
                'class_price'=>$request->input('values_price'),
                'class_link'=>$request->input('values_link'),
            ],
            'status'=>$request->status
        ];
//        $data = DB::table('partners')
//            ->select('values->class_cha as value')
//            ->where('id', $id)
//            ->first();

//        $partner = Partner::find($id);
//        $jsonData = $partner->values;
//        $value = $jsonData['class_cha'];
        DB::table('partners')->where('id',$id)->update($data);
        Session::put('message','Cập nhập đối tác thành công');
        return Redirect::to('list-partner');
    }
    public function delete_partner($id){
        DB::table('partners')->where('id',$id)->delete();
        Session::put('message','Xóa đối tác thành công');
        return Redirect::to('list-partner');
    }

    public function crawl(Request $request)
    {
        $partner = Partner::find($request->id);
        if (!$partner) {
            return response('Partner is not exist', 400);
        }

        $values = json_decode($partner->values);

        $requestArr = [];
        $requestUrl = $partner->url;
        $categoryId = $partner->category_id;
        $requestArr['parent'] = $values->parent_element;
        $requestArr['product_name'] = $values->product_name;
        $requestArr['product_price'] = $values->product_price;
        $requestArr['product_link'] = $values->product_link;

        try {
            $client = new Client();
            $category = new Category();
            $url = $requestUrl;
            $category->url = $url;
            $category->save();
            $crawler = $client->request('GET', $url);
            $listItems = $crawler->filter($requestArr['parent']);

            $newProducts = [];
            if (count($listItems) > 0) {
                $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
                    ->where('category_id', $categoryId)
                    ->get()
                    ->pluck('unique_product_by_date')
                    ->toArray();
                try {
                    DB::beginTransaction();
                    $listItems->each(
                        function (Crawler $node) use ($categoryId, $existData, &$newProducts, $requestArr) {
                            $name = $node->filter($requestArr['product_name'])->text();
                            preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                            $code_product1 = $code[0];
                            $code_product = implode(" ",$code_product1);
                            $price = $node->filter($requestArr['product_price'])->text();
                            preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                            $price2 = $m[0];
                            $price3 = preg_replace('/\D/', '', $price2);
                            $link_product = $node->filter($requestArr['product_link'])->attr('href');
                            $link = $categoryId . $link_product;

                            $now = Carbon::now()->format('Y-m-d');
                            $productNameByDate = $name . '@@' . $now;
                            if (!in_array($productNameByDate, $existData)) {
                                $newProducts[] = [
                                    'code_product'=>$code_product,
                                    'name' => $name,
                                    'price_cost' => $price3,
                                    'category_id' => $categoryId,
                                    'link_product' => $link,
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                ];
                            }
                        });

                    Product::insert($newProducts);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    throw $exception;
                }
            }
        } catch (\Exception $exception) {
            Log::error("crawl data from metamart: {$exception->getMessage()}");
            dd($exception);
        }

        return response('CRAWL DATA SUCCESSFULLY', 200);
    }
}
