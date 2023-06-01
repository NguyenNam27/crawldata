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
            ->get();
//        dd($products);
        $result = [];
        $originalSite = 'https://hawonkoo.vn';

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
        $limit = $request->get('limit', 10000);
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
}
