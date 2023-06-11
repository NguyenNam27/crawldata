<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPartner;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlProductPartnerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:productpartner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        $requestArr = [];
        $requestUrl = $request->get('url', 'https://mediamart.vn/tag?key=hawonkoo');
        $request['category_id'] = $request->get('category_id', 'https://mediamart.vn');
        $requestArr['parent'] = $request->get('parent_element', '.product-list .card');
        $requestArr['product_name'] = $request->get('product_name', 'p.product-name');
        $requestArr['product_price'] = $request->get('product_price', 'p.product-price');
        $requestArr['product_link'] = $request->get('product_link','a.product-item');
        $requestArr['regex_product_name'] = $request->get('regex_product_name','/([\w\d]+)-.*/');

        try {
            $client = new Client();
            $category = new Category();
            $url = $requestUrl;
            $category->url = $url;
            $category->save();
            $crawler = $client->request('GET', $url);
            $listItems = $crawler->filter($requestArr['parent']);
            $categoryId = $request['category_id'];

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
                            preg_match_all($requestArr['regex_product_name'],$name , $code);;
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

//        $client = new Client();
//        $url = 'https://www.dienmayxanh.com/search?key=junger';
//        $category = new Category();
//        $category->url = $url;
//        $category->save();
//        $crawler = $client->request('GET', $url);
//        $dmxUrl = "https://www.dienmayxanh.com";
//
//        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
//            ->where('category_id', $dmxUrl)
//            ->get()
//            ->pluck('unique_product_by_date')
//            ->toArray();
//
//        $crawler->filter('ul.listproduct li.item')->each(
//            function (Crawler $node) use ($existData) {
//                $url = 'https://www.dienmayxanh.com';
//
//                $name = $node->filter('a h3')->text();
//                preg_match_all('/([\w\d]+)-.*/',$name , $code);;
//                $code_product1 = $code[0];
//                $code_product = implode(" ",$code_product1);
//                $price = $node->filter('.price')->text();
//
//                $link_product = $node->filter('a.main-contain')->attr('href');
//
//                $link = $url . $link_product;
//
//                $price_partner = preg_replace('/\D/', '', $price);
//
//                $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
//                if (!in_array($productByNameAndDate, $existData)) {
//                    $product = new ProductPartner;
//                    $product->code_product_partner = $code_product;
//                    $product->name_product_partner = $name;
//                    $product->price_product_partner = empty($price_partner) ? 0 : $price_partner;
//                    $product->category_id = $url;
//                    $product->url_product_partner = $link;
//                    $product->save();
//                }
//            });
    }

}
