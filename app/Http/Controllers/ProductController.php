<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ProductController extends Controller
{
//    private $results = array();
    public function scraper(){
        $client = new Client();
        $url = 'https://poongsankorea.vn/';

        $crawler = $client->request('GET', $url);
        print_r(parse_url($url)); //lấy url

        $crawler->filter('.product_content')->each(
            function (Crawler $node) {
                $name = $node->filter('p.product_name')->text();
                echo '<pre>';
                print_r($name);
                echo '</pre>';
                $price = $node->filter('.current_price ')->text();
                echo '<pre>';
                print_r($price);
                echo '</pre>';
                $link_product = $node->filter('.position-relative ')->attr('href');

                echo '<pre>';
                print_r($link_product);
                echo '</pre>';
//                $price2 = preg_replace('/\D/', '', $price);
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price;
//                $product->save();
            });
    }


}
