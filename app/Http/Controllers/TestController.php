<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{
    public function scraper(){
        $client = new Client();
        $url = 'https://www.dienmayxanh.com/search?key=junger';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);
        $data = '';
        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) use ($data) {
                $url = 'https://www.dienmayxanh.com';
                $name = $node->filter('h3')->text();
                $price = $node->filter('.price')->text();
                $link_product = $node->filter('a.main-contain')->attr('href');
                $link = $url . $link_product;

                $price3 = preg_replace('/\D/', '', $price);
                echo '<pre>';
                print_r($name .'-'. $price3 .'-'. $link);
                echo '</pre>';
            });
    }
}
