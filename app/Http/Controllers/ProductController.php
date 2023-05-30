<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $search = $request->input('search');
        $sites = Product::select('category_id')
            ->groupBy('category_id')
            ->get()
            ->pluck('category_id');

        $products = Product::selectRaw("name, DATE_FORMAT(created_at, '%Y-%d-%m') as unique_product_by_date, price_cost, category_id")
            ->where('name', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            //->where('name', 'Máy ép chậm Hawonkoo SJH-001-PK')
            ->get();

        $result = [];

        $originalSite = 'https://junger.vn';

        //Unique: (name, created_at)
        foreach($products->groupBy('name') as $name => $productByName) {
            foreach($productByName->groupBy('unique_product_by_date') as $date => $productsByDate) {
                $originalPrice = 0;
                foreach ($productsByDate as $productByDate) {
                    if ($productByDate['category_id'] === $originalSite) {
                        $originalPrice = (float) $productByDate['price_cost'];
                    }
                }

                $items = [];
                foreach ($productsByDate as $productByDate) {
                    $items[] = [
                        'category_id' => $productByDate['category_id'],
                        'price' => (float) $productByDate['price_cost'],
                        'price_diff' => $originalPrice - (float) $productByDate['price_cost'],
                    ];
                }

                $result[] = [
                    'name' => $name,
                    'created_at' => $date,
                    'original_price' => $originalPrice,
                    'items' => $items,
                ];

            }
        }
        $total = count($result);
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);
        // xuất excel thì tăng limit lên 9999999
        $offset = ($page - 1) * $limit;
        $resultPaginate = array_slice($result, $offset, $limit);

        return view('product.list', [
            'products' => $resultPaginate,
            'total' => $total,
            'count_site' => $sites->count(),
            'sites' => $sites
        ]);
    }


    public function search(Request $request){
        $search = $request->input('search');
        $search_product=Product::Where('name', 'like', '%' . $search . '%')
            ->get();
        return view('product.list',[
            'search_product'=>$search_product
        ]);
    }
    public function viewproduct($id){

        $product = Product::find($id);

        return view('product.list')->with('product', $product);
    }
    public function find(Request $request){
        $search = $request->input('search');
        //SELECT * FROM `products` WHERE `name` LIKE '%MUP-104%' AND `category_id` = 'https://poongsankorea.vn' ORDER BY `products`.`created_at` DESC;
        $search_product = Product::Where('name', 'like', '%' . $search . '%' )
            ->get();

        return view('resultsearch.search',[
            'search_product'=>$search_product,
//            'seclect_product'=>$select_product
        ]);
    }
}
