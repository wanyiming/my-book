<?php
namespace App\Libraries\Search;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/2
 * Time: 12:04
 */
interface SearchInterface {


    public function insertDocument (array $data);

    public function save(array $data);

    public function del(array $primaryIds, $filed);

    public function clear ();

}