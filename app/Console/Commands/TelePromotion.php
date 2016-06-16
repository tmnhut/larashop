<?php

namespace App\Console\Commands;

use App\ScrapeModel\Stores;
use Illuminate\Console\Command;

class TelePromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tele:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gui tin km cac nha mang';

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
     * @return mixed
     */
    public function handle()
    {
        $links = Stores::getPromotionLinks();
        if(count($links) > 0){
            $dom_parse_path = app_path('ScrapeModel/simple_html_dom.php');
            include($dom_parse_path);
            foreach ($links as $key => $link) {
                if (!empty($link)) {
                    $html = file_get_html($link);
                    if(!empty($html)){
                        $promotion = null;
                        $promotion = $html->find('.detailInfo .info .saleInfo li');
                    }
                }
            }
        }
    }
}
