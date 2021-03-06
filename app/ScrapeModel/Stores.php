<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 5/30/2016 5:51 PM
 */

namespace App\ScrapeModel;


class Stores
{
    public static function getStore(){
        $stores = [
            'nhatcuong' => [
                'iphone 6s 64Gb' => 'https://www.dienthoaididong.com/dien-thoai/apple-iphone-6s-64gb-silver',
                'iphone 6s Plus 64Gb' => 'https://www.dienthoaididong.com/dien-thoai/apple-iphone-6s-plus-64gb-silver',
            ],
            'bachlong' => [
                'iphone 6s 64Gb' => 'https://bachlongmobile.com/iphone-6s-64gb-cong-ty.html',
                'iphone 6s Plus 64Gb' => 'https://bachlongmobile.com/iphone-6s-plus-64gb-cong-ty.html',
            ],
            'bachkhoa' => [
                'iphone 6s 64Gb' => 'https://mrbachkhoa.com/dien-thoai/iphone-6s-64g-world-vn-13408.html',
                'iphone 6s Plus 64Gb' => 'https://mrbachkhoa.com/dien-thoai/iphone-6s-plus-64g-world-vn-13411.html',
            ],
            'hnam' => [
                'iphone 6s 64Gb' => 'http://www.hnammobile.com/dien-thoai/apple-iphone-6s-64gb-silver.7051.html',
                'iphone 6s Plus 64Gb' => 'http://www.hnammobile.com/dien-thoai/apple-iphone-6s-plus-64gb-silver-.7061.html',
            ],
            'cellphones' => [
                'iphone 6s 64Gb' => 'http://cellphones.com.vn/iphone-6s-64-gb-cty.html',
                'iphone 6s Plus 64Gb' => 'http://cellphones.com.vn/iphone-6s-plus-64-gb.html',
            ],
            'techone' => [
                'iphone 6s 64Gb' => 'http://www.techone.vn/iphone-6s-64gb-chi-nh-ha-ng-fpt-4894.html',
                'iphone 6s Plus 64Gb' => 'http://www.techone.vn/iphone-6s-plus-64gb-chi-nh-ha-ng-fpt-4969.htmll',
            ],
            'pico' => [
                'iphone 6s Plus 64Gb' => 'https://pico.vn/30458/dien-thoai-apple-iphone-6s-plus-rose-gold-64gb-a1687mku92vna.html',
                'iphone 6s 64Gb' => 'https://pico.vn/30449/dien-thoai-apple-iphone-6s-rose-gold-64gb-a1688mkqr2vna.html',
            ],
            'mediamart' => [
                'iphone 6s 64Gb' => 'http://mediamart.vn/smartphones/apple/apple-iphone-6s-64gb-rose-gold.htm',
                'iphone 6s Plus 64Gb' => 'http://mediamart.vn/smartphones/apple/apple-iphone-6s-plus-64gb-rose-gold.htm',
            ]
        ];
        return $stores;
    }
}