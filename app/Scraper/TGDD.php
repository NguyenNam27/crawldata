<?php

namespace App\Scraper;

use App\Models\Product;
use Goutte\Client;
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

    }
}
