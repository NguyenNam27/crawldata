<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
            'name'=>$request->name,
            'url'=>$request->url,
            'values'=>[
                'class_cha'=>$request->input('values_cha'),
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
//        dd($decodeData->class_cha);

        return view('partner.edit',[
            'edit_partner'=>$edit_partner,
            'decodeData'=>$decodeData
        ]);
    }
    public function update_partner(Request $request,$id){
        $data = [
            'name'=>$request->name,
            'url'=>$request->url,
            'values'=>[
                'class_cha'=>$request->input('values_cha'),
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
}
