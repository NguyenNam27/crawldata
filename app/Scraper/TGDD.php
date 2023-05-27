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

    public function scrapeMediaMart()
    {
        $client = new Client();
        $url = 'https://mediamart.vn/tag?key=hawonkoo';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);
        $listItems = $crawler->filter('.product-list .card');
        if(count($listItems) > 0){
            $listItems->each(
                function (Crawler $node) {
                    $url = 'https://mediamart.vn';
                    $name = $node->filter('p.product-name')->text();

                    $price = $node->filter('p.product-price')->text();
                    $price2 = preg_replace('/\D/', '', $price);
                    $link_product = $node->filter('a.product-item')->attr('href');
                    $link = $url . $link_product;

                    $checkproduct = Product::where('name','like','%'.$name.'%')
                        ->first();
                    $priceold = $checkproduct->price2;
//                    if ($priceold < $price2){
//                        echo 'giá thay đổi,sản phẩm ' .$link. ' giá là: '.$priceold .'giá sản pham BTP là :' .$checkproduct.PHP_EOL;
//                    } else if ( $priceold > $price2){
//                        echo 'giá thay đổi,sản phẩm';
//                    } else {
//                        echo 'giá mới bằng giá cũ';
//                    }
//                    print_r($priceold,'-');
//                    die();
                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id = $url;
                $product->link_product = $link;
                $product->save();
                });
        }
    }
    public function scrapeJunger()
    {
        $client = new Client();
        $array = array('bep', 'may-rua-bat', 'may-hut-mui', 'lo-vi-song-cao-cap-junger-tk-90-345', 'dung-cu-nha-bep');
        foreach ($array as $arr) {
            for ($page = 1; $page <= 9999; $page++) {
                $url = 'https://junger.vn/' . $arr . '?p=' . $page;
                $category = new Category;
                $category->url = $url;
                $category->save();
                $crawler = $client->request('GET', $url);
                $checkItems = $crawler->filter('.item-wrapper');
                if (count($checkItems) === 0) {
                    break;
                }
                $checkItems->each(
                    function (Crawler $node) {
                        $url = 'https://junger.vn';
                        $name = $node->filter('.item-name')->text();
                        $price = $node->filter('.price_box')->text();
                        $price2 = preg_replace('/\D/', '', $price);
                        $link_product = $node->filter('.primary-img')->attr('href');
                        $link = $url . $link_product;
                        $product = new Product;
                        $product->name = $name;
                        $product->price = $price2;
                        $product->category_id = $url;
                        $product->link_product = $link;
                        $product->save();
                    });
                print_r($arr . ', page: ' . $page . ' \n');
            }

        }
    }

    public function scrapePoongsan()
    {
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

                $link = $url . $link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id = $url;
                $product->link_product = $link;
                $product->save();
            });
    }

    public function scrapeHawonkoo()
    {
        $client = new Client();
        $totalItem = 0;
        $arrayHWK = array('bep-tu', 'noi-chien-khong-dau', 'may-ep-cham', 'quat-cay', 'noi-com-dien', 'noi-ap-suat', 'am-sieu-toc', 'may-tiet-trung', 'noi-lau-dien', 'noi-lau-nuong-da-nang', 'may-lam-sua-hat', 'may-say-toc', 'may-vat-cam', 'vot-muoi');
        foreach ($arrayHWK as $arrHWK){
            for ($page = 1; $page < 10; $page++){
                $url = 'https://hawonkoo.vn/'.$arrHWK.'?p='.$page;
                $category = new Category;
                $category->url = $url;
                $category->save();
                $crawler = $client->request('GET', $url);
                $checkpagination = $crawler->filter('.product_content');
                $totalItem += count($checkpagination);
                if (count($checkpagination) === 0){
                    break;
                }
                $checkpagination->each(
                    function (Crawler $node) {
                        $url = 'https://hawonkoo.vn';
                        $name = $node->filter('a h3.product_name')->text();
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);
                        $product = new Product;
                        $product->name = $name;
                        $product->price = $price2;
                        $product->category_id = $url;
                        $product->link_product = $link;

                        $product->save();
                    });
                print_r($arrHWK.' - page'.$page . ' item: '.count($checkpagination).', Total Item = '.$totalItem);
            }
        }
    }
    public function scrapeBoss()
    {
        $totalItem = 0;
        $client = new Client();
        $arrayBoss = array('ghe-massage-toan-than','may-chay-bo','xe-dap-tap','dung-cu-massage');
        foreach ($arrayBoss as $arrBoss){
            $url = 'https://bossmassage.vn/'.$arrBoss;
            $category = new Category;
            $category->url = $url;
            $category->save();
            $crawler = $client->request('GET', $url);
            $listItem = $crawler->filter('.single_product');
            if(count($listItem) > 0){
                $totalItem += count($listItem);
                $listItem->each(
                    function (Crawler $node) {
                        $url = 'https://bossmassage.vn';
                        $name = $node->filter('h3.product_name a')->text();
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a.primary_img')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);

                        $product = new Product;
                        $product->name = $name;
                        $product->price = $price2;
                        $product->category_id = $url;
                        $product->link_product = $link;
                        $product->save();
                    });
                print_r($arrBoss.' | item: '.count($listItem).', Total Item = '.$totalItem);

            }
        }

    }

    public function scrapeDMX()
    {
//        $client = new Client();
//        $url = 'https://www.dienmayxanh.com/search?key=junger#c=0&kw=junger&pi=0';
//        $category = new Category;
//        $category->url = $url;
//        $category->save();
//        $crawler = $client->request('GET', $url);
//
//        $crawler->filter('.product_content')->each(
//            function (Crawler $node) {
//                $url = 'https://bossmassage.vn';
//
//                $name = $node->filter('a h3.product_name')->text();
//
//                $price = $node->filter('.current_price')->text();
//
//                $link_product = $node->filter('a')->attr('href');
//
//                $link = $url . $link_product;
//                $price2 = preg_replace('/\D/', '', $price);
//
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price2;
//                $product->category_id = $url;
//                $product->link_product = $link;
//                $product->save();
//            });
    }
}
