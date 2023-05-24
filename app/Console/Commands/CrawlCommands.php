<?php

namespace App\Console\Commands;

use App\Models\Product;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

class CrawlCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl-data';

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
        $client = new Client();

        $url = 'https://mediamart.vn/smartphones';
        $crawler = $client->request('GET', $url);
        $crawler->filter('a.product-item')->each(
            function (Crawler $node) {
                $name = $node->filter('p.product-name')->text();
                $price = $node->filter('p.product-price')->text();
                preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                $price2 = $m[0];
                $price3 = preg_replace('/\D/', '', $price2);
                $product = new Product;
                $product->name = $name;
                $product->price = $price3;
                $product->link_product ='mediamart';
                $product->save();
            });

        $client = new Client();

        $url = 'https://www.thegioididong.com/dtdd#c=42&o=17&pi=5';
        $crawler = $client->request('GET', $url);
        $data ='';
        $crawler->filter('li.__cate_42')->each(
            function (Crawler $node) use($data) {
                $price=$node->filter('.price')->text();
                $name = $node->filter('h3')->text();
                preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                $price2 = $m[0];
                $price3 = preg_replace('/\D/', '', $price2);
                $product=Product::Where('name', 'like', '%' . $name . '%')->first();
                if ($product){
                    if($price3 != $product->price){
                        $details = [
                            'title' => 'Nó đổi giá r',
                            'body' => $data .'Sản phẩm'. $product->name. ' bên '. $product->link_product. ' có giá: '. $product->price .' trong khi bên tgdd có giá: '. $price3 .PHP_EOL
                        ];

                        Mail::to('huynq.150115@gmail.com')->send(new \App\Mail\Sendmail($details));
                    }
                }else{
                    $product = new Product();
                    $product->name = $name;
                    $product->price = $price3;
                    $product->link_product ='tgdd';
                    $product->save();
                }
            });
    }
}
