<?php

namespace App\Libraries\Extend;

class NewTree
{
//一维数组
    public static function toLevel($cate, $delimiter = '———', $parent_id = 0, $level = 0) {

        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $parent_id) {
                $v['level'] = $level + 1;
                $v['delimiter'] = str_repeat($delimiter, $level);
                $arr[] = $v;
                $arr = array_merge($arr, self::toLevel($cate, $delimiter, $v['id'], $v['level']));
            }
            //print_r($v);
        }
//exit;
        return $arr;

    }

    //将重组一维数组
    public static function Recombination($arr){
        $new_arr = [];
        foreach ($arr as $value){
            $new_arr[$value['id']] = $value;
        }
        return $new_arr;
    }


    //组成多维数组
    public static function toLayer($cate, $name = 'child', $pid = 0){

        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $v[$name] = self::toLayer($cate, $name, $v['id']);
                $arr[] = $v;
            }
        }

        return $arr;
    }


    //一维数组(同模型)(model = tablename相同)，删除其他模型的分类
    public static function getLevelOfModel($cate, $tablename = 'article') {

        $arr = array();
        foreach ($cate as $v) {
            if ($v['tablename'] == $tablename) {
                $arr[] = $v;
            }
        }

        return $arr;

    }

    //一维数组(同模型)(modelid)，删除其他模型的分类
    public static function getLevelOfModelId($cate, $modelid = 0) {

        $arr = array();
        foreach ($cate as $v) {
            if ($v['modelid'] == $modelid) {
                $arr[] = $v;
            }
        }

        return $arr;

    }

    //传递一个子分类ID返回他的所有父级分类
    public static function getParents($cate, $id) {
        $arr = array();
        $parent_id = 0;
        $pid = 0;
        foreach ($cate as $v) {
            if ($v['id'] == $id) {
                //先获取父级id
                $parent_id = $v['pid'];
                break;
            }
        }
        //获取父级的pid
        foreach ($cate as $v) {
            if ($v['id'] == $parent_id) {
                //先获取父级id
                $pid = $v['pid'];
                break;
            }
        }
        //获取所有pid相同的分类
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
            }
        }

        return $arr;
    }

    //传递一个子分类ID返回他的同级分类
    public static function getSameCate($cate, $id) {
        $arr = array();
        $self = self::getSelf($cate, $id);
        if (empty($self)) {
            return $arr;
        }

        foreach ($cate as $v) {
            if ($v['id'] == $self['pid']) {
                $arr[] = $v;
            }
        }
        return $arr;
    }



    //判断分类是否有子分类,返回false,true
    public static function hasChild($cate, $id) {
        $arr = false;
        foreach ($cate as $v) {
            if ($v['pid'] == $id) {
                $arr = true;
                return $arr;
            }
        }

        return $arr;
    }

    //传递一个父级分类ID返回所有子分类ID
    /*
     *@param $cate 全部分类数组
     *@param $pid 父级ID
     *@param $flag 是否包括父级自己的ID，默认不包括
     **/
    public static function getChildsId($cate, $pid, $flag = 0) {
        $arr = array();
        if ($flag) {
            $arr[] = $pid;
        }
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v['id'];
                $arr = array_merge($arr , self::getChildsId($cate, $v['id']));
            }
        }

        return $arr;
    }


    //传递一个父级分类ID返回所有子级分类
    public static function getChilds($cate, $pid) {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $arr[] = $v;
//                $arr = array_merge($arr, self::getChilds($cate, $v['id']));
            }
        }
        return $arr;
    }

    //传递一个分类ID返回该分类相当信息
    public static function getSelf($cate, $id) {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['id'] == $id) {
                $arr = $v;
                return $arr;
            }
        }
        return $arr;
    }

    //传递一个分类ID返回该分类相当信息
    public static function getSelfByEName($cate, $ename) {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['ename'] == $ename) {
                $arr = $v;
                return $arr;
            }
        }
        return $arr;
    }

    //返回单线分类直到顶级分类
    public static function returnNavArr($cate,$id){
        $arr = array();
        foreach ($cate as $v) {
            if ($v['id'] == $id) {
                $arr[] = $v;
                $arr = array_merge(self::returnNavArr($cate, $v['pid']), $arr);
            }
        }
        return $arr;
    }

}