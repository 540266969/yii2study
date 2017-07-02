<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 17:24
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/cart.css',
        'style/footer.css',
//        <link rel="stylesheet" href="style/base.css" type="text/css">
//	<link rel="stylesheet" href="style/global.css" type="text/css">
//	<link rel="stylesheet" href="style/header.css" type="text/css">
//	<link rel="stylesheet" href="style/cart.css" type="text/css">
//	<link rel="stylesheet" href="style/footer.css" type="text/css">
//
//	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
//	<script type="text/javascript" src="js/cart1.js"></script>
    ];
    public $js = [
        'js/cart1.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}