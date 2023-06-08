<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductOriginController extends Controller
{
    public function listProductOriginal(){
        return view('productoriginal.list');
    }
    public function addProductOriginal(){
        return view('productoriginal.create');
    }
    public function saveProductOriginal(){

    }
}
