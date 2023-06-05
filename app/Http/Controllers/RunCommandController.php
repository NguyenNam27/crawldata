<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\DomCrawler\Crawler;

class RunCommandController extends Controller
{
    public function scraper()
    {

    }
    public function runCommand()
    {
        Artisan::call('crawl:data');
        return redirect()->route('list')->with('success', 'Câu lệnh đã được chạy thành công.');

    }
}
