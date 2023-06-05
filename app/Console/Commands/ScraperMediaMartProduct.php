<?php

namespace App\Console\Commands;

use App\Scraper\TGDD;
use Illuminate\Console\Command;

class ScraperMediaMartProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:mediamart';

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
        $bot = new TGDD();
        $bot->scrapeMediaMart();
    }
}
