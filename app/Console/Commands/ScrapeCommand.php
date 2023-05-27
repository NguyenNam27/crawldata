<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl-jungerDMX';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $array = array('bep', 'may-rua-bat', 'may-hut-mui', 'lo-vi-song-cao-cap-junger-tk-90-345', 'dung-cu-nha-bep');
        foreach ($array as $arr) {
            for ($page = 1; $page <= 9999; $page++) {
                $url = 'https://junger.vn/' . $arr . '?p=' . $page;
                $category = new Category;
                $category->url = $url;
                $category->save();
                $crawler = $client->request('GET', $url);
                $checkItems = $crawler->filter('.item-wrapper');
                if (count($checkItems) === 0) {
                    break;
                }
                $checkItems->each(
                    function (Crawler $node) {
                        $url = 'https://junger.vn';
                        $name = $node->filter('.item-name')->text();
                        $price = $node->filter('.price_box')->text();
                        $price2 = preg_replace('/\D/', '', $price);
                        $link_product = $node->filter('.primary-img')->attr('href');
                        $link = $url . $link_product;
                        $product = new Product;
                        $product->name = $name;
                        $product->price = $price2;
                        $product->category_id = $url;
                        $product->link_product = $link;
                        $product->save();
                    });
                print_r($arr . ', page: ' . $page . ' \n');
            }

        }

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
                $product = Product::Where('name', 'like', '%' . $name . '%')->first();
                if ($product) {
                    if ($price3 != $product->price) {
                        $details = [
                            'title' => 'Sản phẩm đổi giá rồi',
                            'body' => $data . 'Sản phẩm' . $product->name . ' bên ' . $product->link_product . ' có giá: ' . $product->price . ' trong khi bên' . $link . ' có giá: ' . $price3 . PHP_EOL
                        ];
                        Mail::to('nguyenngocnam00770@gmail.com')->send(new \App\Mail\Sendmail($details));
                    }
                } else {
                    $product = new Product();
                    $product->name = $name;
                    $product->price = $price3;
                    $product->category_id = $url;
                    $product->link_product = $link;
                    $product->save();
                }
            });
    }
}
