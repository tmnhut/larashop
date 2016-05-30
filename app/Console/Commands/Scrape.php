<?php

namespace App\Console\Commands;

use App\Stores;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Scrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape description';

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
        $dom_parse_path = app_path('simple_html_dom.php');
        include($dom_parse_path);
        $store = Stores::getStore();
        if (count($store) > 0) {
            $path_folder = base_path('data');
            if(!is_dir($path_folder)){
                mkdir($path_folder , 0777, true);
                echo "\nDirectory {$path_folder} was created";
            }
            foreach ($store as $key_store => $store) {
                if (count($store) > 0) {
                    foreach ($store as $name => $link) {
                        $html = file_get_html($link);
                        $data = null;
                        switch ($key_store) {
                            case 'nhat cuong':
                                $data = $this->parseNhatCuong($html);
                                break;
                            case 'bach long':
                                $data = $this->parseBachLong($html);
                                break;
                            case 'bach khoa':
                                $data = $this->parseBachKhoa($html);
                                break;
                            case 'hnam':
                                $data = $this->parseHnam($html);
                                break;
                            default:
                                break;
                        };
                        $result = PHP_EOL.$key_store."\t".$name."\t".$data[0]."\t".$data[1];
                        $file_path = $path_folder."/result_".date('d_m_Y', time()).".txt";
                        $this->writeFile($file_path, $result);
//                        $this->info($result);
                    }
                }
                $this->info(PHP_EOL.$key_store." DONE".PHP_EOL);
            }
        }
    }

    public function parseNhatCuong($html)
    {
        $price = trim($html->find('div.detailInfo .info .price', 0)->plaintext);
        $price = substr($price, 0, strpos($price, " VN"));
        $price = trim(str_slug($price));
        if(empty($price))
            $price = 'Không tìm thấy giá';
        $promotion = null;
        $li_list = $html->find('div.detailInfo .info .saleInfo li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }
        return [intval($price), html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseBachLong($html)
    {
        $price = trim($html->find('div.product-shop .price-info .price', 0)->innertext);
        $price = trim(str_slug($price));
        if(empty($price))
            $price = 'Không tìm thấy giá';
        $promotion = null;
        $li_list = $html->find('div.product-shop .short-description .prom li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }
        if(empty($promotion))
            $promotion = 'Không tìm thấy khuyến mãi';
        return [intval($price), html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseBachKhoa($html)
    {
        $price = trim($html->find('div.price span#our_price_display', 0)->plaintext);
        $price = str_replace("đ", "", $price);
        $price = trim(str_slug($price));
        if(empty($price))
            $price = 'Không tìm thấy giá';
        $promotion = null;
        $li_list = $html->find('div.statics_free_shipping ul li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }
        if(empty($promotion))
            $promotion = 'Không tìm thấy khuyến mãi';
        return [intval($price), html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseHnam($html)
    {
        $price = trim($html->find('div#product-detail price-block span', 0)->plaintext);
        $price = str_replace("đ", "", $price);
        $price = trim(str_slug($price));
        if(empty($price))
            $price = 'Không tìm thấy giá';
        $promotion = null;
        $li_list = $html->find('div#boxKM ul li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }

        $proms = $html->find('div#product-detail-right .borderOrange .content p');
        foreach ($proms as $p) {
            $promotion = $promotion." ".trim($p->plaintext);
        }
        if(empty($promotion))
            $promotion = 'Không tìm thấy khuyến mãi';
        return [intval($price), html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function writeFile($file_path, $result){
        if(!file_exists($file_path)) {
            $handle = fopen($file_path, 'w') or die('Cannot open file:  ' . $file_path);
            $res = fwrite($handle, $result);
            fclose($handle);
        } else {
            $res = file_put_contents($file_path, $result, FILE_APPEND);
        }
        return $res;
    }
}
