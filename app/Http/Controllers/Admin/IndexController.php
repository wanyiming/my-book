<?php

namespace App\Http\Controllers\Admin;

use App\Models\FirmAndDealer;
use App\Models\Goods;
use App\Models\GoodsSale;
use App\Models\InformationContent;
use App\Models\SrvGoodsCategory;
class IndexController extends CommonController
{
    function __construct()
    {
        parent::__construct();
    }

    const  SITE_URL = '';

    public function index(){
        return view('admin.index.index');
    }

    public function sendSitemapXml () {
        $sitemap[] = self::SITE_URL. to_route('register');
        $sitemap[] = self::SITE_URL. to_route('search');
        $sitemap[] = self::SITE_URL. to_route('home.jiancai');
        $sitemap[] = self::SITE_URL. to_route('home.jiaju');
        $sitemap[] = self::SITE_URL. to_route('home.shebei');
        $sitemap[] = self::SITE_URL. to_route('home.manufacturer.index');
        $sitemap[] = self::SITE_URL. to_route('home.dealer.index');
        $sitemap[] = self::SITE_URL. to_route('home.project.manager.index');;
        $sitemap[] = self::SITE_URL. to_route('home.protocol_information.link');

        $sitemap[] = to_route('home.login');
        $sitemap[] = self::SITE_URL. to_route('home.register');
        $sitemap[] = self::SITE_URL. to_route('home.firm_login');
        $sitemap[] = self::SITE_URL. to_route('home.firm_logout');
        $sitemap[] = self::SITE_URL. to_route('home.dealer_logout');
        $sitemap[] = self::SITE_URL. to_route('home.logout');
        $sitemap[] = self::SITE_URL. to_route('home.dealer_get_back_password');
        $sitemap[] = self::SITE_URL. to_route('home.firm_get_step_two');
        $sitemap[] = self::SITE_URL. to_route('home.dealer_get_step_two');
        $sitemap[] = self::SITE_URL. to_route('home.dealer_get_step_three');
        $sitemap[] = self::SITE_URL. to_route('home.firm_get_step_three');

        // 商品详情
        //product/{id}.html
        $goodsSaleData = GoodsSale::leftJoin('goods', 'goods.id', '=', 'goods_sale.goods_id')->where('goods_sale.status',GoodsSale::GOODS_STATUS_ON)
            ->where('goods.status',Goods::GOODS_STATUS_ON)->pluck('goods_sale.id as saleid')->toArray();
        foreach ($goodsSaleData as $k=>$v) {
            $sitemap[] = self::SITE_URL. to_route('home.goods.details',['id'=>$v]);
        }

        // 搜索列表
        //product/{category_url?}/list_1/
        $creategoryData = SrvGoodsCategory::all();
        foreach ($creategoryData as $value) {
            $sitemap[] = self::SITE_URL. to_route('home.goods.lists',['category_url'=>$value->short_name]);
        }

        // 协议
        //protocol_information/information/{id?}
        $articleData = InformationContent::all();
        foreach ($articleData as $informValue) {
            $sitemap[] = self::SITE_URL. to_route('home.protocol_information.information',['id'=>$informValue->information_id]);
        }

        // 店铺
        $firmShopData = FirmAndDealer::where('seller_type', 3)->whereIn('status',[1,3])->pluck('id')->toArray();


        foreach ($firmShopData as $shopValue) {
            $sitemap[] = to_route('home.shop.detail',['id'=>$shopValue]);
            $sitemap[] = to_route('home.shop.contact',['id'=>$shopValue]);
            $sitemap[] = to_route('home.shop.introduction',['id'=>$shopValue]);
            $sitemap[] = to_route('home.shop.comment',['id'=>$shopValue]);
        }

        $siteData = array_chunk($sitemap, 4000);



        $siteParents = [];

        foreach ($siteData as $qwe=>$asd) {
            $siteParents[] =  self::sendSiteFile($asd, $qwe);
        }

        $siteParentsStr = "<?xml version=\"1.0\" encoding=\"utf-8\"?>".PHP_EOL;
        $siteParentsStr .= "<sitemap>".PHP_EOL;
        foreach ($siteParents as $qwers) {
            $siteParentsStr .= "<loc>".$qwers."</loc>".PHP_EOL;
            $siteParentsStr .= "<lastmod>".date('Y-m-d',time())."</lastmod>".PHP_EOL;
        }
        $siteParentsStr .= "</sitemap>".PHP_EOL;
        file_put_contents(app_path(). '/../public/sitemap.xml', $siteParentsStr);


    }


    public function sendSiteFile ($siteArray, $i) {
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>".PHP_EOL;
        $xml .= "<?xml-stylesheet type=\"text/xsl\" href=\"sitemap.xsl\"?>".PHP_EOL;
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".PHP_EOL;
        foreach ($siteArray as $data) {
            $xml .= "<url>".PHP_EOL;
            $xml .= "<loc>".$data."</loc>".PHP_EOL;
            $xml .= "<priority>0.4</priority>".PHP_EOL;
            $xml .= "<lastmod>".date('Y-m-d',time())."</lastmod>".PHP_EOL;
            $xml .= "<changefreq>Daily</changefreq>".PHP_EOL;
            $xml .= "</url>".PHP_EOL;
        }
        $fileName =  'sitemap'.($i+1).'.xml';
        file_put_contents(app_path(). '/../public/'.$fileName, $xml);
        echo "OK".PHP_EOL;
        return "http://www.wwwjcsc.com/". $fileName;
    }
}
