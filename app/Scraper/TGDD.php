<?php

namespace App\Scraper;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class TGDD
{


    public function scrapeMediaMart()
    {
        try {
            $client = new Client();
            $url = 'https://mediamart.vn/tag?key=hawonkoo';
            $category = new Category;
            $category->url = $url;
            $category->save();
            $crawler = $client->request('GET', $url);
            $listItems = $crawler->filter('.product-list .card');
            $mediaMartUrl = 'https://mediamart.vn';

            $newProducts = [];
            if (count($listItems) > 0) {
                $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
                    ->where('category_id', $mediaMartUrl)
                    ->get()
                    ->pluck('unique_product_by_date')
                    ->toArray();

                try {
                    DB::enableQueryLog();
                    $listItems->each(
                        function (Crawler $node) use ($mediaMartUrl, $existData, &$newProducts) {
                            $mediaMartUrl = 'https://mediamart.vn';
                            $name = $node->filter('p.product-name')->text();
                            preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                            $code_product1 = $code[0];
                            $code_product = implode(" ",$code_product1);
                            $price = $node->filter('p.product-price')->text();
                            preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                            $price2 = $m[0];
                            $price3 = preg_replace('/\D/', '', $price2);
                            $link_product = $node->filter('a.product-item')->attr('href');
                            $link = $mediaMartUrl . $link_product;

                            $now = Carbon::now()->format('Y-m-d');
                            $productNameByDate = $name . '@@' . $now;
                            if (!in_array($productNameByDate, $existData)) {
                                $newProducts[] = [
                                    'code_product'=>$code_product,
                                    'name' => $name,
                                    'price_cost' => $price3,
                                    'category_id' => $mediaMartUrl,
                                    'link_product' => $link,
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                ];
                            }
                        });

                    Product::insert($newProducts);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    throw $exception;
                }
            }
        } catch (\Exception $exception) {
            Log::error("crawl data from metamart: {$exception->getMessage()}");
            dd($exception);
        }
    }
    public function scrapeJunger()
    {
        try {
            $client = new Client();
            $array = array('bep', 'may-rua-bat', 'may-hut-mui', 'lo-vi-song-cao-cap-junger-tk-90-345', 'dung-cu-nha-bep');
            $jungerUrl = 'https://junger.vn';
            $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
                ->where('category_id', $jungerUrl)
                ->get()
                ->pluck('unique_product_by_date')
                ->toArray();

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
                        function (Crawler $node) use ($jungerUrl, &$existData) {
                            $jungerUrl = 'https://junger.vn';
                            $name = $node->filter('.item-name')->text();
                            preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                            $code_product1 = $code[0];
                            $code_product = implode(" ",$code_product1);
                            $price = $node->filter('.price_box')->text();
                            $price2 = preg_replace('/\D/', '', $price);
                            $link_product = $node->filter('.primary-img')->attr('href');
                            $link = $jungerUrl . $link_product;

                            $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                            if (!in_array($productByNameAndDate, $existData)) {
                                $product = new Product;
                                $product->code_product = $code_product;
                                $product->name = $name;
                                $product->price_cost = empty($price2) ? 0 : $price2;
                                $product->category_id = $jungerUrl;
                                $product->link_product = $link;
                                $product->save();
                                $existData[] = $productByNameAndDate;
                            }
                        });
                    print_r($arr . ', page: ' . $page . ' \n');
                }

            }
        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function scrapePoongsan()
    {
        $client = new Client();
        $poongSanUrl = $url = 'https://poongsankorea.vn';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $poongSanUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

        $crawler->filter('.product_content')->each(
            function (Crawler $node) use ($poongSanUrl, $existData) {
                $name = $node->filter('p.product_name')->text();
                preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                $code_product1 = $code[0];
                $code_product = implode(" ",$code_product1);
                $price = $node->filter('.current_price')->text();

                $link_product = $node->filter('p a')->attr('href');

                $link = $poongSanUrl . $link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                if (!in_array($productByNameAndDate, $existData)) {
                    $product = new Product;
                    $product->code_product = $code_product;
                    $product->name = $name;
                    $product->price_cost = empty($price2) ? 0 : $price2;
                    $product->category_id = $poongSanUrl;
                    $product->link_product = $link;
                    $product->save();
                }
            });
    }

    public function scrapeHawonkoo()
    {
        $client = new Client();
        $totalItem = 0;
        $arrayHWK = array('bep-tu', 'noi-chien-khong-dau', 'may-ep-cham', 'quat-cay', 'noi-com-dien', 'noi-ap-suat', 'am-sieu-toc', 'may-tiet-trung', 'noi-lau-dien', 'noi-lau-nuong-da-nang', 'may-lam-sua-hat', 'may-say-toc', 'may-vat-cam', 'vot-muoi');

        $hawonkooUrl = 'https://hawonkoo.vn';
        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $hawonkooUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

        foreach ($arrayHWK as $arrHWK) {
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
                    function (Crawler $node) use (&$existData) {
                        $url = 'https://hawonkoo.vn';

                        $name = $node->filter('a h3.product_name')->text();
                        preg_match_all('/([\w\d]+)-.*/',$name , $code);
                        $code_product1 = $code[0];
                        $code_product = implode(" ",$code_product1);
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);

                        $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                        if (!in_array($productByNameAndDate, $existData)) {
                            $product = new Product;
                            $product->code_product = $code_product;
                            $product->name = $name;
                            $product->price_cost = empty($price2) ? 0 : $price2;
                            $product->category_id = $url;
                            $product->link_product = $link;
                            $product->save();
                            $existData[] = $productByNameAndDate;
                        }

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

        $bossmassageUrl = 'https://bossmassage.vn';
        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $bossmassageUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

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
                    function (Crawler $node) use ($existData) {
                        $url = 'https://bossmassage.vn';
                        $name = $node->filter('h3.product_name a')->text();
                        preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                        $code_product1 = $code[0];
                        $code_product = implode(" ",$code_product1);
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a.primary_img')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);

                        $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                        if (!in_array($productByNameAndDate, $existData)) {
                            $product = new Product;
                            $product->code_product = $code_product;
                            $product->name = $name;
                            $product->price_cost = empty($price2) ? 0 : $price2;
                            $product->category_id = $url;
                            $product->link_product = $link;
                            $product->save();
                        }
                    });
                print_r($arrBoss.' | item: '.count($listItem).', Total Item = '.$totalItem);

            }
        }

    }

    public function scrapeDMX()
    {

    }
}
