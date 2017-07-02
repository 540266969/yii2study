<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/18 0018
 * Time: 13:20
 */

namespace backend\components;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilters extends ActionFilter
{
    public function beforeAction($action)
    {
        $user=\Yii::$app->user;
        if ($user->isGuest){
            $action->controller->redirect(['user/login']);
        }
        if(!$user->can($action->uniqueId)){
            throw new HttpException('403','你没有权限访问');
            return false;
        }
        return parent::beforeAction($action);
    }
}