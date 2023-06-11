<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class ProductOriginController extends Controller
{
    public function listProductOriginal(){
        $listProductOriginal = DB::table('product_originals')
//            ->where('status',1)
            ->orderBy('id','desc')
            ->paginate(1000);
        return view('productoriginal.list',[
            'listProductOriginal'=>$listProductOriginal
        ]);
    }
    public function addProductOriginal(){
        return view('productoriginal.create');
    }
    public function saveProductOriginal(Request $request){
        $data = array();
        $data['code_product_original'] = $request->code_product_original;
        $data['name_product_original'] = $request->name_product_original;
        $data['price_product_original'] = $request->price_product_original;
        $data['url_product_original'] = $request->url_product_original;
        $data['status'] = $request->status;
        $now = Carbon::now()->format('Y-m-d');
        DB::table('product_originals')->insert($data);
        Session::put('message','Thêm sản phẩm gốc thành công');
        return Redirect::to('list-product-original');
    }
    public function editProductOriginal($id){
        $edit_ProductOriginal = DB::table('product_originals')->where('id',$id)->first();

        return view('productoriginal.edit',[
            'edit_ProductOriginal'=>$edit_ProductOriginal
        ]);
    }
    public function updateProductOriginal(Request $request,$id){
        $data = array();
        $data['code_product_original'] = $request->code_product_original;
        $data['name_product_original'] = $request->name_product_original;
        $data['price_product_original'] = $request->price_product_original;
        $data['url_product_original'] = $request->url_product_original;
        $data['status'] = $request->status;
        DB::table('product_originals')->where('id',$id)->update($data);
        Session::put('message','Cập nhập sản phẩm gốc thành công');
        return Redirect::to('list-product-original');
    }
    public function delete_ProductOriginal($id){
        DB::table('product_originals')->where('id',$id)->delete();
        Session::put('message','Xóa sản phẩm thành công');
        return Redirect::to('list-product-original');
    }
}
