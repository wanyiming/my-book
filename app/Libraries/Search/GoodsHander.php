<?php
namespace  App\Libraries\Search;
use App\Models\Goods;

/**
 * Created by PhpStorm. 生成商品文件，cvs 然后将其导入到搜索文本中
 * User: Administrator
 * Date: 2017/5/2
 * Time: 11:48
 * Author: wym
 */
class GoodsHander
{
    public static $XS = null;

    public function sendDocument () {
        self::initXS()->index->clean();
        (new SearchHander(self::initXS()->getIndex()))->insertDocument(Goods::goodsXunSearchCsv()->toArray());
        return self::initXS();
    }

    public function returnTokenizerSCWS ($str) {
        if (empty($str)) {
            return [];
        }
        try {
            self::initXS()->search;
            $tokenizer = new \XSTokenizerScws();
            $titleScws = $tokenizer->getResult($str);
            return array_pluck($titleScws, 'word');
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return -1;
    }

    // TODO 暂停
    public function returnSearchGoods ($param = array()) {
        $searchObj = self::initXS()->search;
        if ($param['keyword']) {
            $tokenizer = new \XSTokenizerScws();
            $titleScws = $tokenizer->getTops($param['keyword'], 5);
            if (!empty($titleScws)) {
                $titleArr = [];
                foreach ($titleScws as $k=>$b) {
                    $titleArr[] = 'title:'.$b['word'];
                }
                $searchObj->setQuery(implode(' OR ', $titleArr));
            }
        }
        if ($param['money']) {
            list($beginMoney, $endMoney) = explode('-', $param['money']);
            if ($beginMoney && !$endMoney) {
                $searchObj->addRange('market_price',tofloat($beginMoney), null);
            } else if (!$beginMoney && $endMoney) {
                $searchObj->addRange('market_price',null, tofloat($endMoney));
            } else if ($beginMoney && $endMoney) {
                if ($beginMoney > $endMoney) {
                    $endsMoney = $beginMoney;
                    $beginMoney = $endMoney;
                    $endMoney = $endsMoney;
                }
                dd($searchObj->setFuzzy()->addRange('member_price', 1,3)->setLimit(10,5)->search());
            }
            dd($searchObj->search());
        }
    }

    public function initXS () {
        if (self::$XS === null) {
            self::$XS = new \XS(__DIR__.'/product.ini');
       }
        return self::$XS;
    }
}