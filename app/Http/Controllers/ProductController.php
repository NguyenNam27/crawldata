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

        $products = Product::selectRaw("name, DATE_FORMAT(created_at, '%Y-%d-%m') as unique_product_by_date, price_cost, category_id, code_product, link_product")
            ->where('name', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->get();
//        dd($products);
        $result = [];

        // sites gốc
        $originalSites = [
            'https://junger.vn',
            'https://hawonkoo.vn',
            'https://poongsankorea.vn',
            'https://bossmassage.vn'
        ];

        $originalSite = $request->get('original_site', 'https://hawonkoo.vn');

        // Site gốc nên giá cần so sánh với nhau, nên k hiển thị
        foreach ($sites as $key => $site) {
            if (in_array($site, $originalSites)) {
                $sites->forget($key);
            }
        }

        // Cấu trúc dữ liệu quyết định thuật toán

        //Unique: (name, created_at)
        foreach($products->groupBy('name') as $name => $productByName) {

            foreach($productByName->groupBy('unique_product_by_date') as $date => $productsByDate) {
                $originalPrice = 0;
                $originalProductLink = '';

                // tìm giá gốc và link gốc
                foreach ($productsByDate as $key => $productByDate) {
                    if ($productByDate['category_id'] === $originalSite) {
                        $originalPrice = (float) $productByDate['price_cost'];
                        $originalProductLink = $productByDate['link_product'];
                        unset($productsByDate[$key]);
                    }
                    // do original sites chung 1 giá thì k cần so sánh, nên loại bỏ
                    if (in_array($productByDate['category_id'], $originalSites)) {
                        unset($productsByDate[$key]);
                    }
                }

                // so sánh giá với các site khác
                $items = [];
                foreach ($productsByDate as $productByDate) {
                    $items[] = [
                        'category_id' => $productByDate['category_id'],
                        'price' => (float) $productByDate['price_cost'],
                        'price_diff' => $originalPrice - (float) $productByDate['price_cost'],
                        'link_product' => $productByDate['link_product'],
                    ];
                }

                $result[] = [
                    'name' => $name,
                    'created_at' => $date,
                    'original_price' => $originalPrice,
                    'items' => $items,
                    'code_product' => isset($productByDate['code_product']) ? $productByDate['code_product'] : 'EMPTY',
                    'link_product' => $originalProductLink,
                ];
            }
        }

        $total = count($result);
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 1000);
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
