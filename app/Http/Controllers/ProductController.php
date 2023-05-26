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
    public function getList()
    {
        $product = DB::table('products')->paginate(20);
        return view('product.list',[
            'product'=>$product
        ]);
    }


    public function search(Request $request){
        $search = $request->input('search');
        $search_product=Product::Where('name', 'like', '%' . $search . '%')
//            ->orWhere(['price','link_product'], 'like', '%' . $search . '%')
            ->get();
        dd($search_product);
        return view('product.list',[
            'search_product'=>$search_product
        ]);
    }
    public function viewproduct($id){

        $product = Product::find($id);
//        dd($product);

        return view('product.list')->with('product', $product);
    }
    public function find(Request $request){
        $search = $request->input('search');
        $search_product=Product::Where('name', 'like', '%' . $search . '%')
            ->get();
        return view('resultsearch.search',[
            'search_product'=>$search_product
        ]);
    }
}
