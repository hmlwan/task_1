<?php
/**
 * Created by PhpStorm.
 * User: v_huizzeng
 * Date: 2018/7/23
 * Time: 21:36
 */
function dd($data){
    if($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    die();
}