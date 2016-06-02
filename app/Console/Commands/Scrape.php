<?php

namespace App\Console\Commands;

use App\ScrapeModel\Stores;
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
        $dom_parse_path = app_path('ScrapeModel/simple_html_dom.php');
        include($dom_parse_path);
        $stores = Stores::getStore();
        if (count($stores) > 0) {
            $path_folder = base_path('data');
            if(!is_dir($path_folder)){
                mkdir($path_folder , 0777, true);
                echo "\nDirectory {$path_folder} was created";
            }
            foreach ($stores as $key_store => $store) {
                if (count($store) > 0) {
                    foreach ($store as $name => $link) {
                        $html = file_get_html($link);
//                        sleep(10);
                        $data = null;
                        switch ($key_store) {
                            case 'nhatcuong':
                                $data = $this->parseNhatCuong($html);
                                break;
                            case 'bachlong':
                                $data = $this->parseBachLong($html);
                                break;
                            case 'bachkhoa':
                                $data = $this->parseBachKhoa($html);
                                break;
                            case 'hnam':
                                $data = $this->parseHnam($html);
                                break;
                            case 'cellphones':
                                $data = $this->parseCellphones($html);
                                break;
                            case 'techone':
                                $data = $this->parseTechone($html);
                                break;
                            case 'pico':
                                $data = $this->parsePico($html);
                                break;
                            case 'mediamart':
                                $data = $this->parseMediamart($html);
                                break;
                            case 'thienhoa':
                                $data = $this->parseThienhoa($html);
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
                $this->info(PHP_EOL.$key_store." DONE");
            }
        }
    }

    public function parseNhatCuong($html)
    {
        $price_tag = $html->find('.detailInfo .info .price', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->plaintext);
            $price = substr($price, 0, strpos($price, " VN"));
            $price = trim(str_slug($price));
        }
        $promotion = null;
        $li_list = $html->find('.detailInfo .info .saleInfo li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseBachLong($html)
    {
        $price_tag = $html->find('.product-shop .price-info .price', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->innertext);
            $price = trim(str_slug($price));
        }
        $promotion = null;
        $li_list = $html->find('.product-shop .short-description .prom li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }
        if(empty($promotion))
            $promotion = 'Không có thông tin';
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseBachKhoa($html)
    {
        $price_tag = $html->find('.price span#our_price_display', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->plaintext);
            $price = str_replace("đ", "", $price);
            $price = trim(str_slug($price));
        }
        $promotion = null;
        $li_list = $html->find('.statics_free_shipping ul li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim($li->plaintext);
        }
        if(empty($promotion))
            $promotion = 'Không có thông tin';
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseHnam($html)
    {
        $price_tag = $html->find('#product-detail .price-block span', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->plaintext);
            $price = str_replace("đ", "", $price);
            $price = trim(str_slug($price));
        }
        $promotion = null;
        $li_list = $html->find('#boxKM ul li');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim(preg_replace('/\t+/', '', $li->plaintext));
        }

        $proms = $html->find('.borderOrange .content p');
        foreach ($proms as $p) {
            $promotion = $promotion." ".trim(preg_replace('/\t+/', '', $p->plaintext));
        }
        if(empty($promotion))
            $promotion = 'Không có thông tin';
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseCellphones($html)
    {
        $price_tag = $html->find('.price-box .price-box-content #price', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->plaintext);
            $price = str_replace("đ", "", $price);
            $price = trim(str_slug($price));
        }

        $promotion = trim($html->find('.khuyenmai .khuyenmai-info', 0)->plaintext);
        if(empty($promotion))
            $promotion = 'Không có thông tin';
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseTechone($html)
    {
        $price_tag = $html->find('.pinfo .pi_content .pprice span.price', 0);

        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->plaintext);
            $price = str_replace("đ", "", $price);
            $price = trim(str_slug($price));
        }

        $promotion = null;
        $li_list = $html->find('.piPromotion .piProm p');
        foreach ($li_list as $li) {
            $promotion = $promotion." ".trim(preg_replace('/\t+/', '', $li->plaintext));
        }

        $li_list2 = $html->find('.khuyenmaihotro .kmht_bottom p');
        foreach ($li_list2 as $li2) {
            $promotion = $promotion." ".trim(preg_replace('/\t+/', '', $li2->plaintext));
        }

        if(empty($promotion))
            $promotion = 'Không có thông tin';
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parsePico($html)
    {
        $price_tag = $html->find('.sidebar .product-single-info .price', 0);
        if (empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->plaintext);
            $price = str_replace("đ", "", $price);
            $price = trim(str_slug($price));
        }

        $promotion = null;
        $li_list = $html->find('#part_promotion ul li');
        foreach ($li_list as $li) {
            $li_text = str_replace("[ Xem thêm &rarr; ]", "", $li->plaintext);
            $promotion = $promotion." ".trim($li_text);
        }
        if(empty($promotion))
            $promotion = 'Không có thông tin';
        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseMediamart($html)
    {
        $price_tag = $html->find('.pdr-right .pdrr-price .pdrr-pbuy', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->content);
            $price = trim(str_slug($price));
        }
        $promotion_tag = $html->find('.pdr-right .pdrr-specialoffer .pdrr-so-info p', 0);
        if(empty($promotion_tag))
            $promotion = 'Không có thông tin';
        else {
            $promotion = trim(preg_replace('/\r\n+|Chi tiết tại đây./', '', $promotion_tag->plaintext));
        }

        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
    }

    public function parseThienhoa($html)
    {
        $price_tag = $html->find('.pdr-right .pdrr-price .pdrr-pbuy', 0);
        if(empty($price_tag))
            $price = 'Không giá';
        else {
            $price = trim($price_tag->content);
            $price = trim(str_slug($price));
        }
        $promotion_tag = $html->find('.pdr-right .pdrr-specialoffer .pdrr-so-info p', 0);
        if(empty($promotion_tag))
            $promotion = 'Không có thông tin';
        else {
            $promotion = trim(preg_replace('/\r\n+|Chi tiết tại đây./', '', $promotion_tag->plaintext));
        }

        return [$price, html_entity_decode($promotion, ENT_HTML5, 'utf-8')];
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
