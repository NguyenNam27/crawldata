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

        for ($p=1; $p<=2; $p++){
            $purl = 'https://junger.vn/bep?p='.$p;
        }
        $url = $purl;
        $crawler = $client->request('GET', $url);
        $crawler->filter('.item-wrapper')->each(
            function (Crawler $node) {
                $url = 'https://junger.vn/bep';
                $name = $node->filter('.item-name')->text();
                $price = $node->filter('.price_box')->text();
                $price2 = preg_replace('/\D/', '', $price);
                $link_product = $node->filter('.primary-img')->attr('href');
                $link = $url.$link_product;

            });
    }
}
