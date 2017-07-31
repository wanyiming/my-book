<?php

namespace App\Libraries\Extend;

/**
 * Tree 树型类(无限分类)
 *
 * $tree= new Tree($result);
 * $arr=$tree->leaf(0);
 * $nav=$tree->navi(15);
 */
class Tree
{
    private        $result    = null;
    private        $tmp       = null;
    private        $arr       = null;
    private        $already   = array();
    private static $_instance = null;

    private function __construct()
    {

    }

    public function __clone()
    {
    }

    /**
     * @param array $result 树型数据表结果集
     * @param array $fields 树型数据表字段，array(分类id,父id)
     * @param integer $root 顶级分类的父id
     * @param bool $type true:返回数据保持索引关系；false:返回数据重新排序
     * @return Tree|null
     */
    public static function getInstance($result, $fields = array('id', 'pid'), $root = 0, $type = false)
    {
        self::$_instance = null;
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        self::$_instance->result = null;
        self::$_instance->result = $result;
        self::$_instance->fields = $fields;
        self::$_instance->root = $root;
        self::$_instance->handler($type);

        return self::$_instance;
    }

    /**
     * 构造函数
     *
     * @param array $result 树型数据表结果集
     * @param array $fields 树型数据表字段，array(分类id,父id)
     * @param integer $root 顶级分类的父id
     */
    //public function __construct($result, $fields = array('id', 'pid'), $root = 0) {
    //    $this->result = $result;
    //    $this->fields = $fields;
    //    $this->root = $root;
    //    $this->handler();
    //}

    /**
     * 树型数据表结果集处理
     * @param $type
     */
    private function handler($type)
    {
        $tmp = [];
        foreach ($this->result as $node) {
            if (isset($node['greed']) && $node['greed'] == 6) {
                $node['islast'] = 2;
            } else {
                $node['islast'] = 1;
            }
            if ($type === true) {
                $tmp[$node[$this->fields[1]]][$node['id']] = $node;
            } else {
                $tmp[$node[$this->fields[1]]][] = $node;
            }
        }
        krsort($tmp);
        for ($i = count($tmp); $i > 0; $i--) {
            foreach ($tmp as $k => $v) {
                if (!in_array($k, $this->already)) {
                    if (!$this->tmp) {
                        $this->tmp = array($k, $v);
                        $this->already[] = $k;
                        continue;
                    } else {
                        foreach ($v as $key => $value) {
                            if ($value[$this->fields[0]] == $this->tmp[0]) {
                                $result_a = $this->tmp[1];
                                $result_ss = end($result_a);
                                if (isset($result_ss['greed']) && $result_ss['greed'] != 6) {
                                    $tmp[$k][$key]['islast'] = 0;
                                }
                                $tmp[$k][$key]['child'] = $this->tmp[1];
                                $this->tmp = array($k, $tmp[$k]);
                            }
                        }
                    }
                }
            }
            $this->tmp = null;
        }
        $this->tmp = $tmp;
    }

    /**
     * 反向递归
     * @param $arr
     * @param $id
     */
    private function recur_n($arr, $id)
    {
        foreach ($arr as $v) {
            if ($v[$this->fields[0]] == $id) {
                $this->arr[] = $v;
                if ($v[$this->fields[1]] != $this->root) $this->recur_n($arr, $v[$this->fields[1]]);
            }
        }
    }

    /**
     * 正向递归
     * @param $arr
     */
    private function recur_p($arr)
    {
        foreach ($arr as $v) {
            $this->arr[] = $v[$this->fields[0]];
            if ($v['child']) $this->recur_p($v['child']);
        }
    }

    /**
     * 菜单 多维数组
     *
     * @param integer $id 分类id
     * @return array 返回分支，默认返回整个树
     */
    public function leaf($id = null)
    {
        $id = ($id === null) ? $this->root : $id;
        return $this->tmp[$id] ?? [];
    }

    /**
     *
     * 列表 两维数组
     *
     * @param integer $id 分类id
     * @param str|string $title 原文字field
     * @param str|string $prefix 文字前缀
     * @param str|string $greed 层级field
     * @param integer $level 前缀层级
     * @return array 返回，默认返回整个树
     *
     */
    public function gather($id = null, $title = "", $prefix = "", $greed = "greed", $level = 0)
    {
        $return = array();
        $list = $this->leaf($id);
        $this->recursion($return, $list, $title, $prefix, $greed, $level);

        return $return;
    }

    /**
     *递归处理数组
     * @param array $return 数组指针
     * @param array $list 待处理的数组数据
     * @param str|string $title 原文字
     * @param str|string $prefix 文字前缀
     * @param string $greed
     * @param integer $level 前缀层级
     */
    private function recursion(&$return, $list, $title = "", $prefix = "", $greed = "greed", $level = 0)
    {
        foreach ($list as $key => $val) {
            $new = array();
            foreach ($val as $k => $v) {
                if ($k == "child") {
                    continue;
                }
                if ($title && $k == $title) {
                    $str = "";
                    for ($i = 0; $i < $val[$greed] - $level; $i++) {
                        $str .= $prefix;
                    }
                    $new[$k] = $str . $v;
                } else {
                    $new[$k] = $v;
                }
            }
            $return[] = $new;
            if (isset($val['child'])) {
                $this->recursion($return, $val['child'], $title, $prefix, $greed, $level);
            }
        }
    }

    /**
     * 导航 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回单线分类直到顶级分类
     */
    public function navi($id)
    {
        $this->arr = null;
        $this->recur_n($this->result, $id);
        if($this->arr){
            krsort($this->arr);
            return $this->arr;
        }
    }

    /**
     * 散落 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回leaf下所有分类id
     */
    public function leafid($id)
    {
        $this->arr = null;
        $this->arr[] = $id;
        $this->recur_p($this->leaf($id));

        return $this->arr;
    }
}