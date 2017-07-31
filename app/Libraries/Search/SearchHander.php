<?php
namespace  App\Libraries\Search;
use App\Libraries\SSO\Exception;

/**
 * Created by PhpStorm. 搜索
 * User: Administrator
 * Date: 2017/5/2
 * Time: 11:48
 * Author: wym
 */
class SearchHander implements SearchInterface
{

    public static $indexOf = null;

    public function __construct($searchIndex)
    {
        if (empty($searchIndex)) {
            throw new Exception('请选择索引初始化');
        }
        self::$indexOf = $searchIndex;
    }

    public function insertDocument(array $data)
    {
        if (empty($data)) {
            throw new Exception('添加搜索数据不能为空');
        }
        $XSDoc = self::xsDocument();
        if (count($data) == count($data, 1)) {
            $XSDoc->setFields($data);
            self::$indexOf->add($XSDoc);
        } else {
            foreach ($data as $value) {
                $XSDoc->setFields($value);
                self::$indexOf->add($XSDoc);
            }
        }
        //self::$indexOf->add($XSDoc);
    }

    public function save(array $data)
    {
        if (empty($data)) {
            throw new Exception('修改索引数据不能为空');
        }
        $XSDoc = self::xsDocument();
        $XSDoc->setFields($data);
        self::$indexOf->update($XSDoc);
    }

    public function del(array $primaryIds, $filed = null)
    {
        if (empty($primaryIds)) {
            throw new Exception('主键数据为空');
        }
        self::$indexOf->del($primaryIds, $filed);
    }

    public function clear()
    {
        self::$indexOf->clean();
    }

    public function xsDocument ($code = null) {
        return new \XSDocument($code);
    }
}