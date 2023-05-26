<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class CrawlCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Craw data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://www.thegioididong.com/';
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) {
                $url = 'https://www.thegioididong.com';
                $name = $node->filter('h3')->text();
                $price = $node->filter('.price')->text();
                preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                $price2 = $m[0];
                $price3 = preg_replace('/\D/', '', $price2);
                $link_product = $node->filter('.main-contain')->attr('href');
                $link = $url.$link_product;
                $product = new Product;
                $product->name = $name;
                $product->price = $price3;
                $product->category_id= $url;
                $product->link_product = $link;
                $product->save();
            });

        $client = new Client();
        $url = 'https://mediamart.vn/';

        $crawler = $client->request('GET', $url);

        $crawler->filter('.card')->each(
            function (Crawler $node) {
                $url = 'https://mediamart.vn';
                $name = $node->filter('p.product-name')->text();
                //$checkproduct = find database by $name crawl
                // true
                //$priceOld = $checkproduct->price;
                $price = $node->filter('.product-price-regular')->text();
                // if ($priceOld < $price) => giá thay đổi. giá mới lớn hơn giá cũ là $checkproduct giá mới là $priceOld
                // else if ($priceOld > $price) => gía thay đổi, giá mới nhỏ hơn  giá cũ là $checkproduct giá mới là $priceOld
                // else => //

                //false

                $price2 = preg_replace('/\D/', '', $price);

                $link_product = $node->filter('.product-item')->attr('href');

                $link = $url.$link_product;
                $product = new Product;
                $product->name = $name;
                $product->price = $price2;
                $product->category_id=$url;
                $product->link_product = $link;
                $product->save();
            });

        $client = new Client();
        $url = 'https://junger.vn/bep';
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

//        $client = new Client();
//        $url = 'https://poongsankorea.vn';
//
//        $crawler = $client->request('GET', $url);
//
//        $crawler->filter('.product_content')->each(
//            function (Crawler $node) {
//                $url = 'https://poongsankorea.vn';
//
//                $name = $node->filter('p.product_name')->text();
//
//                $price = $node->filter('.current_price')->text();
//
//                $link_product = $node->filter('p a')->attr('href');
//
//                $link = $url.$link_product;
//                $price2 = preg_replace('/\D/', '', $price);
//
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price2;
//                $product->category_id=$url;
//                $product->link_product = $link;
//                $product->save();
//            });
//        $client = new Client();
//        $url = 'https://hawonkoo.vn';
//
//        $crawler = $client->request('GET', $url);
//
//        $crawler->filter('.product_content')->each(
//            function (Crawler $node) {
//                $url = 'https://hawonkoo.vn';
//
//                $name = $node->filter('a h3.product_name')->text();
//
//                $price = $node->filter('.current_price')->text();
//
//                $link_product = $node->filter('a')->attr('href');
//
//                $link = $url.$link_product;
//                $price2 = preg_replace('/\D/', '', $price);
//
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price2;
//                $product->category_id=$url;
//                $product->link_product = $link;
//
//                $product->save();
//            });
//        $client = new Client();
//        $url = 'https://bossmassage.vn';
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
//                $link = $url.$link_product;
//                $price2 = preg_replace('/\D/', '', $price);
//
//                $product = new Product;
//                $product->name = $name;
//                $product->price = $price2;
//                $product->category_id=$url;
//                $product->link_product = $link;
//
//                $product->save();
//            });
    }
}
