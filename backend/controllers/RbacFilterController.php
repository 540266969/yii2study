<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18 0018
 * Time: 13:37
 */

namespace backend\controllers;


use backend\components\RbacFilters;
use yii\web\Controller;

class RbacFilterController extends Controller
{
    public function behaviors(){
        return [
          'rbac'=>[
              'class'=>RbacFilters::className(),
          ]
        ];
    }
}