<?php

namespace App\Console\Commands;

use App\Models\CatalogProductOriginal;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOriginal;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class CrawlProductOriginalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:productoriginal';

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
        //site Junger
        try {
            $client = new Client();
            $array = array('bep', 'may-rua-bat', 'may-hut-mui', 'lo-vi-song-cao-cap-junger-tk-90-345', 'dung-cu-nha-bep');
            $jungerUrl = 'https://junger.vn';
            $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
                ->where('category_id', $jungerUrl)
                ->get()
                ->pluck('unique_product_by_date')
                ->toArray();

            foreach ($array as $arr) {
                for ($page = 1; $page <= 9999; $page++) {
                    $url = 'https://junger.vn/' . $arr . '?p=' . $page;
                    $category = new CatalogProductOriginal();
                    $category->name = $url;
                    $category->save();
                    $crawler = $client->request('GET', $url);
                    $checkItems = $crawler->filter('.item-wrapper');
                    if (count($checkItems) === 0) {
                        break;
                    }
                    $checkItems->each(
                        function (Crawler $node) use ($jungerUrl, &$existData) {
                            $jungerUrl = 'https://junger.vn';
                            $name = $node->filter('.item-name')->text();
                            preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                            $code_product1 = $code[0];
                            $code_product = implode(" ",$code_product1);
                            $price = $node->filter('.price_box')->text();
                            $price2 = preg_replace('/\D/', '', $price);
                            $link_product = $node->filter('.primary-img')->attr('href');
                            $link = $jungerUrl . $link_product;

                            $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                            if (!in_array($productByNameAndDate, $existData)) {
                                $product = new ProductOriginal;
                                $product->code_product_original = $code_product;
                                $product->name_product_original = $name;
                                $product->price_product_original = empty($price2) ? 0 : $price2;
                                $product->catalog_product_original_id = $jungerUrl;
                                $product->url_product_original = $link;
                                $product->save();
                                $existData[] = $productByNameAndDate;
                            }
                        });
                    print_r($arr . ', page: ' . $page . ' \n');
                }

            }
        } catch (\Exception $exception) {
            dd($exception);
        }
        //site Poongsan
        $client = new Client();
        $poongSanUrl ='https://poongsankorea.vn';
        $category = new CatalogProductOriginal;
        $category->name = $url;
        $category->save();
        $crawler = $client->request('GET', $url);

        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $poongSanUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

        $crawler->filter('.product_content')->each(
            function (Crawler $node) use ($poongSanUrl, $existData) {
                $name = $node->filter('p.product_name')->text();
                preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                $code_product1 = $code[0];
                $code_product = implode(" ",$code_product1);
                $price = $node->filter('.current_price')->text();
                $link_product = $node->filter('p a')->attr('href');
                $link = $poongSanUrl . $link_product;
                $price2 = preg_replace('/\D/', '', $price);

                $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                if (!in_array($productByNameAndDate, $existData)) {
                    $product = new ProductOriginal;
                    $product->code_product_original = $code_product;
                    $product->name_product_original = $name;
                    $product->price_product_original = empty($price2) ? 0 : $price2;
                    $product->catalog_product_original_id = $poongSanUrl;
                    $product->url_product_original = $link;
                    $product->save();
                }
            });
        //site Hawonkoo
        $client = new Client();
        $totalItem = 0;
        $arrayHWK = array('bep-tu', 'noi-chien-khong-dau', 'may-ep-cham', 'quat-cay', 'noi-com-dien', 'noi-ap-suat', 'am-sieu-toc', 'may-tiet-trung', 'noi-lau-dien', 'noi-lau-nuong-da-nang', 'may-lam-sua-hat', 'may-say-toc', 'may-vat-cam', 'vot-muoi');

        $hawonkooUrl = 'https://hawonkoo.vn';
        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $hawonkooUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

        foreach ($arrayHWK as $arrHWK) {
            for ($page = 1; $page < 10; $page++){
                $url = 'https://hawonkoo.vn/'.$arrHWK.'?p='.$page;
                $category = new CatalogProductOriginal();
                $category->name = $url;
                $category->save();
                $crawler = $client->request('GET', $url);
                $checkpagination = $crawler->filter('.product_content');
                $totalItem += count($checkpagination);
                if (count($checkpagination) === 0){
                    break;
                }
                $checkpagination->each(
                    function (Crawler $node) use (&$existData) {
                        $url = 'https://hawonkoo.vn';
                        $name = $node->filter('a h3.product_name')->text();
                        preg_match_all('/([\w\d]+)-.*/',$name , $code);
                        $code_product1 = $code[0];
                        $code_product = implode(" ",$code_product1);
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);

                        $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                        if (!in_array($productByNameAndDate, $existData)) {
                            $product = new ProductOriginal;
                            $product->code_product_original = $code_product;
                            $product->name_product_original = $name;
                            $product->price_product_original = empty($price2) ? 0 : $price2;
                            $product->catalog_product_original_id = $url;
                            $product->url_product_original = $link;
                            $product->save();
                            $existData[] = $productByNameAndDate;
                        }

                    });
//                print_r($arrHWK.' - page'.$page . ' item: '.count($checkpagination).', Total Item = '.$totalItem);
            }
        }

        //site Boss
        $totalItem = 0;
        $client = new Client();
        $arrayBoss = array('ghe-massage-toan-than','may-chay-bo','xe-dap-tap','dung-cu-massage');

        $bossmassageUrl = 'https://bossmassage.vn';
        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $bossmassageUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

        foreach ($arrayBoss as $arrBoss){
            $url = 'https://bossmassage.vn/'.$arrBoss;
            $category = new CatalogProductOriginal;
            $category->name = $url;
            $category->save();
            $crawler = $client->request('GET', $url);
            $listItem = $crawler->filter('.single_product');
            if(count($listItem) > 0){
//                $totalItem += count($listItem);
                $listItem->each(
                    function (Crawler $node) use ($existData) {
                        $url = 'https://bossmassage.vn';
                        $name = $node->filter('h3.product_name a')->text();
                        preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                        $code_product1 = $code[0];
                        $code_product = implode(" ",$code_product1);
                        $price = $node->filter('.current_price')->text();
                        $link_product = $node->filter('a.primary_img')->attr('href');
                        $link = $url . $link_product;
                        $price2 = preg_replace('/\D/', '', $price);

                        $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                        if (!in_array($productByNameAndDate, $existData)) {
                            $product = new ProductOriginal;
                            $product->code_product_original = $code_product;
                            $product->name_product_original = $name;
                            $product->price_product_original = empty($price2) ? 0 : $price2;
                            $product->catalog_product_original_id = $url;
                            $product->url_product_original = $link;
                            $product->save();
                        }
                    });
//                print_r($arrBoss.' | item: '.count($listItem).', Total Item = '.$totalItem);

            }
        }
    }
}
