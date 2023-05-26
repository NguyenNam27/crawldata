<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ProductTestController extends Controller
{
    public function scraper(){
        $client = new Client();
        $url = 'https://www.dienmayxanh.com/search?key=junger#c=0&kw=junger&pi=0';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $crawler->filter('li.item a.item-img')->each(
            function (Crawler $node) {
                $url = 'https://www.dienmayxanh.com';

                $name = $node->filter('h3')->text();
                echo '<pre>';
                print_r($name);
                echo '<pre>';

                    $price = $node->filter('.price')->text();
                echo '<pre>';
                print_r($price);
                echo '<pre>';

                $link_product = $node->filter('a .main-contain')->attr('href');

                $link = $url.$link_product;
                $price2 = preg_replace('/\D/', '', $price);
//        $category = new Category;
//        $category->url = $url;
//        $category->save();
//        $crawler = $client->request('GET', $url);
//        $crawler->filter('.item-wrapper')->each(
//            function (Crawler $node) {
//                $url = 'https://junger.vn/bep';
//                $name = $node->filter('.item-name')->text();
//                $price = $node->filter('.price_box')->text();
//                $price2 = preg_replace('/\D/', '', $price);
//                $link_product = $node->filter('.primary-img')->attr('href');
//                $link = $url.$link_product;
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price2;
//                $product->category_id=$url;
//                $product->link_product = $link;
//                $product->save();
            });


    }
}
