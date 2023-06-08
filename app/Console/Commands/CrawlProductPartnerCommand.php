<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlProductPartnerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:productpartner';

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
        try {
            $client = new Client();
            $url = 'https://mediamart.vn/tag?key=hawonkoo';
            $category = new Category;
            $category->url = $url;
            $category->save();
            $crawler = $client->request('GET', $url);
            $listItems = $crawler->filter('.product-list .card');
            $mediaMartUrl = 'https://mediamart.vn';

            $newProducts = [];
            if (count($listItems) > 0) {
                $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
                    ->where('category_id', $mediaMartUrl)
                    ->get()
                    ->pluck('unique_product_by_date')
                    ->toArray();
                try {
                    DB::enableQueryLog();
                    $listItems->each(
                        function (Crawler $node) use ($mediaMartUrl, $existData, &$newProducts) {
                            $mediaMartUrl = 'https://mediamart.vn';
                            $name = $node->filter('p.product-name')->text();
                            preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                            $code_product1 = $code[0];
                            $code_product = implode(" ",$code_product1);
                            $price = $node->filter('p.product-price')->text();
                            preg_match('/([0-9\.,]+)\s?\w+/', $price, $m);
                            $price2 = $m[0];
                            $price3 = preg_replace('/\D/', '', $price2);
                            $link_product = $node->filter('a.product-item')->attr('href');
                            $link = $mediaMartUrl . $link_product;

                            $now = Carbon::now()->format('Y-m-d');
                            $productNameByDate = $name . '@@' . $now;
                            if (!in_array($productNameByDate, $existData)) {
                                $newProducts[] = [
                                    'code_product'=>$code_product,
                                    'name' => $name,
                                    'price_cost' => $price3,
                                    'category_id' => $mediaMartUrl,
                                    'link_product' => $link,
                                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                ];
                            }
                        });

                    Product::insert($newProducts);
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    throw $exception;
                }
            }
        } catch (\Exception $exception) {
            Log::error("crawl data from metamart: {$exception->getMessage()}");
            dd($exception);
        }

        $client = new Client();
        $url = 'https://www.dienmayxanh.com/search?key=junger';
        $category = new Category;
        $category->url = $url;
        $category->save();
        $crawler = $client->request('GET', $url);
        $dmxUrl = "https://www.dienmayxanh.com";

        $existData = Product::selectRaw("CONCAT(name, '@@', DATE_FORMAT(created_at, '%Y-%m-%d')) as unique_product_by_date")
            ->where('category_id', $dmxUrl)
            ->get()
            ->pluck('unique_product_by_date')
            ->toArray();

        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) use ($existData) {
                $url = 'https://www.dienmayxanh.com';

                $name = $node->filter('a h3')->text();
                preg_match_all('/([\w\d]+)-.*/',$name , $code);;
                $code_product1 = $code[0];
                $code_product = implode(" ",$code_product1);
                $price = $node->filter('.price')->text();

                $link_product = $node->filter('a.main-contain')->attr('href');

                $link = $url . $link_product;

                $price_partner = preg_replace('/\D/', '', $price);

                $productByNameAndDate = $name . '@@' . Carbon::now()->format('Y-m-d');
                if (!in_array($productByNameAndDate, $existData)) {
                    $product = new Product;
                    $product->code_product = $code_product;
                    $product->name = $name;
                    $product->price_cost = empty($price_partner) ? 0 : $price_partner;
                    $product->category_id = $url;
                    $product->link_product = $link;
                    $product->save();
                }
            });
    }
}
