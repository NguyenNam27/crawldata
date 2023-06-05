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

        // Chạy câu lệnh "php artisan" tại đây
        Artisan::call('crawl:data');
        // Hoặc nếu câu lệnh có tham số
        // Artisan::call('tên_câu_lệnh', ['tham_số_1' => 'giá_trị_1', 'tham_số_2' => 'giá_trị_2']);
        // Thực hiện bất kỳ xử lý nào khác sau khi chạy câu lệnh

        return redirect()->route('list')->with('success', 'Câu lệnh đã được chạy thành công.');

    }
}
