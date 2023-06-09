<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductPartnerController extends Controller
{

    public function listProductPartner(){

        return view('productpartner.list');
    }
    public function comparePrices(){
        $query = "
            SELECT
                product_originals.id,
                product_originals.code_product_original,
                product_originals.price_product_original AS original_price,
                product_partners.price_product_partner AS partner_price,
                product_partners.price_product_partner - product_originals.price_product_original AS price_difference
            FROM
                product_originals
            LEFT JOIN
                product_partners ON product_partners.code_product_partner = product_originals.code_product_original
        ";
        $products = DB::select($query);
        dd($products);
    }
}
