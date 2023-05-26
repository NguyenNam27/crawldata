<?php

namespace App\Scraper;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;

class TGDD
{
//    public function scrape()
//    {
//        $url = 'https://www.thegioididong.com/';
//        $category = new Category;
//        $category->url = $url;
//        $category->save();
//
//        $client = new Client();
//        $crawler = $client->request('GET', $url);
//        $crawler->filter('ul.listproduct li.item')->each(
//            function (Crawler $node) {
//                $url = 'https://www.thegioididong.com';
//                $name = $node->filter('h3')->text();
//                $price = $node->filter('.price')->text();
//                preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
//                $price2 = $m[0];
//                $price3 = preg_replace('/\D/', '', $price2);
//                $link_product = $node->filter('.main-contain')->attr('href');
//                $link = $url.$link_product;
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price3;
//                $product->category_id= $url;
//                $product->link_product = $link;
//                $product->save();
//            });
//
//    }

//    public function scrapeMediaMart()
//    {
//        $client = new Client();
//        $url = 'https://mediamart.vn/';
//        $category = new Category;
//        $category->url = $url;
//        $category->save();
//
//        $crawler = $client->request('GET', $url);
//
//        $crawler->filter('.card')->each(
//            function (Crawler $node) {
//                $url = 'https://mediamart.vn';
//                $name = $node->filter('p.product-name')->text();
//
//                $price = $node->filter('.product-price-regular')->text();
//
//                $price2 = preg_replace('/\D/', '', $price);
//
//                $link_product = $node->filter('.product-item')->attr('href');
//
//                $link = $url.$link_product;
//
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price2;
//                $product->category_id=$url;
//                $product->link_product = $link;
//                $product->save();
//            });
//    }
    public function scrapeJunger(){
        $client = new Client();
        $array = array( 'bep','may-rua-bat','may-hut-mui','lo-vi-song-cao-cap-junger-tk-90-345','dung-cu-nha-bep');
        foreach ($array as $arr) {
            $url = 'https://junger.vn/'.$arr;
        }
        echo '<pre>';
        print_r($arr);
        echo '<pre>';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);
        $crawler->filter('.item-wrapper')->each(
            function (Crawler $node) {
                $url = 'https://junger.vn/bep';
                $name = $node->filter('.item-name')->text();
                $price = $node->filter('.price_box')->text();
                $price2 = preg_replace('/\D/', '', $price);
                $link_product = $node->filter('.primary-img')->attr('href');
                $link = $url.$link_product;
                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id=$url;
                $product->link_product = $link;
                $product->save();
            });
    }
    public function scrapePoongsan(){
        $client = new Client();
        $url = 'https://poongsankorea.vn';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $crawler->filter('.product_content')->each(
            function (Crawler $node) {
                $url = 'https://poongsankorea.vn';

                $name = $node->filter('p.product_name')->text();

                $price = $node->filter('.current_price')->text();

                $link_product = $node->filter('p a')->attr('href');

                $link = $url.$link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id=$url;
                $product->link_product = $link;
                $product->save();
            });
    }
    public function scrapeHawonkoo(){
        $client = new Client();
        $url = 'https://hawonkoo.vn';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $crawler->filter('.product_content')->each(
            function (Crawler $node) {
                $url = 'https://hawonkoo.vn';

                $name = $node->filter('a h3.product_name')->text();

                $price = $node->filter('.current_price')->text();

                $link_product = $node->filter('a')->attr('href');

                $link = $url.$link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id=$url;
                $product->link_product = $link;

                $product->save();
            });
    }
    public function scrapeBoss(){
        $client = new Client();
        $url = 'https://bossmassage.vn';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $crawler->filter('.product_content')->each(
            function (Crawler $node) {
                $url = 'https://bossmassage.vn';

                $name = $node->filter('a h3.product_name')->text();

                $price = $node->filter('.current_price')->text();

                $link_product = $node->filter('a')->attr('href');

                $link = $url.$link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id=$url;
                $product->link_product = $link;

                $product->save();
            });
    }
        public function scrapeDMX(){
        $client = new Client();
        $url = 'https://www.dienmayxanh.com/search?key=junger#c=0&kw=junger&pi=0';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $crawler->filter('.product_content')->each(
            function (Crawler $node) {
                $url = 'https://bossmassage.vn';

                $name = $node->filter('a h3.product_name')->text();

                $price = $node->filter('.current_price')->text();

                $link_product = $node->filter('a')->attr('href');

                $link = $url.$link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id=$url;
                $product->link_product = $link;
                $product->save();
            });
    }
}
