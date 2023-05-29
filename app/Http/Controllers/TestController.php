<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
                $name = $node->filter('a h3')->text();
                echo '<pre>';
                print_r($name);
                echo '</pre>';
                $link_product = $node->filter('a.main-contain')->attr('href');
                $link = $url . $link_product;
                echo '<pre>';
                print_r($link);
                echo '</pre>';
                $price = $node->filter('.price')->text();
                $price3 = preg_replace('/\D/', '', $price);
               echo '<pre>';
               print_r($price3);
               echo '</pre>';

                $product = Product::Where('name', 'like', '%' . $name . '%')->first();
//                dd($product);
                if ($product) {
                    if ($price3 != $product->price) {
                        $details = [
                            'title' => 'Sản phẩm đổi giá rồi',
                            'body' => $data . 'Sản phẩm' . $product->name . ' bên ' . $product->link_product . ' có giá: ' . $product->price . ' trong khi bên' . $link . ' có giá: ' . $price3 . PHP_EOL
                        ];
                        Mail::to('nguyenngocnam00770@gmail.com')->send(new \App\Mail\Sendmail($details));
                    }
                } else {
                   echo 'ano';
                }
            });
    }
}
