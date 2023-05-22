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
//        $str = '10.990.000₫ -19%';
//        preg_match_all('/([0-9\.,]+)\s?\w+/', $str, $m);
//        dd($m);
        $cate_pro = DB::table('products')
                    ->join('categories',);
        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) {
                $name = $node->filter('h3')->text();
                $price = $node->filter('.price')->text();
                $price2 = preg_replace('/\D/', '', $price);

                $product = new Product;
                $product->name = $name;
                $product->price = $price;
                $product->save();
            });
    }

    public function scrapeMediaMart()
    {
        $client = new Client();
        $url = 'https://mediamart.vn/';
        $crawler = $client->request('GET',$url);

        $crawler->filter('.card-body')->each(
            function (Crawler $node) {
                $name = $node->filter('p.product-name')->text();
                $price = $node->filter('p.product-price')->text();

                $price2 = preg_replace('/\D/', '', $price);
                echo '<pre>';
                print_r($name);
                echo '</pre>';

                echo '<pre>';
                print_r($price);
                echo '</pre>';
            });
    }
}
