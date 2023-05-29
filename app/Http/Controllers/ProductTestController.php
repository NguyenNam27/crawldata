<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ProductTestController extends Controller
{
    public function scraper()
    {
        $client = new Client();
        $url = 'https://www.dienmayxanh.com/search?key=junger';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);
        $listItems = $crawler->filter('ul.listproduct li.item');
        if (count($listItems) > 0) {
            $listItems->each(
                function (Crawler $node) {
                    $url = 'https://www.dienmayxanh.com';
                    $name = $node->filter('a h3')->text();
                    echo '<pre>';
                    print_r($name);
                    echo '<pre>';
                    $price = $node->filter('.price')->text();
//                    preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
//                    $price2 = $m[0];
                    $price3 = preg_replace('/\D/', '', $price);
                    echo '<pre>';
                    print_r($price3);
                    echo '<pre>';
                    $link_product = $node->filter('a.main-contain')->attr('href');
                    echo '<pre>';
                    print_r($link_product);
                    echo '<pre>';
                });
        }

    }
}
