<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 11:29
 */

namespace frontend\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/login.css',
        'style/footer.css',
//    <link rel="stylesheet" href="style/base.css" type="text/css">
//	<link rel="stylesheet" href="style/global.css" type="text/css">
//	<link rel="stylesheet" href="style/header.css" type="text/css">
//	<link rel="stylesheet" href="style/login.css" type="text/css">
//	<link rel="stylesheet" href="style/footer.css" type="text/css">
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}