<?php

namespace App\Scraper;

use App\Models\Product;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

class TGDD
{
    public function scrape()
    {
        $url = 'https://www.thegioididong.com/';

        $client = new Client();
        $crawler = $client->request('GET', $url);
//        $str = '10.990.000VND -19%';
//        preg_match_all('/([0-9\.,]+)\s?\w+/', $str, $m);
//        dd($m);

        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) {

                $name = $node->filter('h3')->text();
                $price = $node->filter('.price')->text();
                $price2 = preg_replace('/\D/', '', $price);
//                $join = DB::table('products')
//                    ->join('categories', 'categories.id', '=', 'products.category_id')
//                    ->orderBy('products.category_id', 'desc')->get();
                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->save();
            });

    }

    public function scrapeMediaMart()
    {
        $client = new Client();
        $url = 'https://mediamart.vn/';
        $crawler = $client->request('GET', $url);

        $crawler->filter('.card-body')->each(
            function (Crawler $node) {
                $name = $node->filter('p.product-name')->text();
                $price = $node->filter('p.product-price')->text();
                $price2 = preg_replace('/\D/', '', $price);
                $product = new Product;
                $product->name = $name;
                $product->price = $price;
                $product->save();
            });
    }
    public function scrapeJunger(){
        $client = new Client();
        $url = 'https://junger.vn/bep';
        $crawler = $client->request('GET', $url);
        print_r(parse_url($url)); //lấy url
        $crawler->filter('.item-wrapper')->each(
            function (Crawler $node) {
                $name = $node->filter('.item-name')->text();
                $price = $node->filter('.price_box')->text();
                $price2 = preg_replace('/\D/', '', $price);
                $link_product = $node->filter('.primary-img')->attr('href');

                $product = new Product;
                $product->name = $name;
                $product->price = $price;
                $product->link_product = $link_product;
                $product->save();
            });
    }
    public function scrapePoongsan(){

    }
    public function scrapeHawonkoo(){

    }
    public function scrapeBoss(){

    }
}
