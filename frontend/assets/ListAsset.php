<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20 0020
 * Time: 20:30
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class ListAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/list.css',
        'style/bottomnav.css',
        'style/common.css',
        'style/footer.css',
    ];
    public $js = [
        'js/header.js',
        'js/home.js',
        'js/list.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}