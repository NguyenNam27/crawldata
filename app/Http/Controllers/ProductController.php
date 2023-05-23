<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ProductController extends Controller
{
    private $results = array();
    public function scraper(){
        $client = new Client(HttpClient::create(['timeout' => 60]));
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
