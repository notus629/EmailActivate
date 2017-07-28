<?php
/**
 * Created by PhpStorm.
 * User: Notus
 * Date: 17/7/27
 * Time: 下午6:39
 */

try{
    $db = new PDO("mysql:dbname=test_project;host=112.74.174.157", 'n_test', 'n_test');
} catch(PDOException $e){
    exit('数据库错误代码 '. $e->getCode() . " : " . $e->getMessage());
}