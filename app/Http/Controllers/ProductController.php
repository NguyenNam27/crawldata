<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ProductController extends Controller
{
//    private $results = array();
//    public function scraper(){
//        $client = new Client();
//        $url = 'https://www.thegioididong.com/';
//        $crawler = $client->request('GET',$url);
//
//        $crawler->filter('ul.listproduct li.item')->each(
//            function (Crawler $node) {
//                $name = $node->filter('h3')->text();
//
//                $price = $node->filter('.price')->text();
//                $price2 = preg_replace('/\D/', '', $price);
//
//                echo '<pre>';
//                print_r($name);
//                echo '</pre>';
//
//                echo '<pre>';
//                print_r($price);
//                echo '</pre>';
//            }
//            );
//    }
}
