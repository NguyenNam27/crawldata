<?php

namespace App\Console\Commands;

use App\Models\Category;
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
        $totalItem = 0;
        $arrayHWK = array('bep-tu', 'noi-chien-khong-dau', 'may-ep-cham', 'quat-cay', 'noi-com-dien', 'noi-ap-suat', 'am-sieu-toc', 'may-tiet-trung', 'noi-lau-dien', 'noi-lau-nuong-da-nang', 'may-lam-sua-hat', 'may-say-toc', 'may-vat-cam', 'vot-muoi');
        foreach ($arrayHWK as $arrHWK){
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
                    function (Crawler $node) {
                        $url = 'https://hawonkoo.vn';
                        $name = $node->filter('a h3.product_name')->text();
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);
                        $product = new Product;
                        $product->name = $name;
                        $product->price = $price2;
                        $product->category_id = $url;
                        $product->link_product = $link;

                        $product->save();
                    });
                print_r($arrHWK.' - page'.$page . ' item: '.count($checkpagination).', Total Item = '.$totalItem);
            }
        }



        $client = new Client();
        $url = 'https://mediamart.vn/tag?key=hawonkoo';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);
        $data ='';
        $crawler->filter('.product-list .card')->each(
            function (Crawler $node) use($data) {
                $url = 'https://mediamart.vn';
                $price=$node->filter('p.product-price')->text();
                $name = $node->filter('p.product-name')->text();
                $link_product = $node->filter('a.product-item')->attr('href');
                $link = $url.$link_product;
                preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                $price2 = $m[0];
                $price3 = preg_replace('/\D/', '', $price2);
                $product= Product::Where('name', 'like', '%' . $name . '%')->first();

                if ($product){
                    if($price3 != $product->price){
                        $details = [
                            'title' => 'Sản phẩm bên mediamart và hawonkoo đổi giá rồi',
                            'body' => $data .'Sản phẩm'. $product->name. ' bên ' .$product->link_product.' có giá: '. $product->price .' trong khi bên' .$link. ' có giá: '. $price3 .PHP_EOL
                        ];
                        Mail::to('nguyenngocnam00770@gmail.com')->send(new \App\Mail\Sendmail($details));
                    }
                }else{
                    $product = new Product();
                    $product->name = $name;
                    $product->price = $price3;
                    $product->category_id = $url;
                    $product->link_product =$link;
                    $product->save();
                }
            });



    }


}
