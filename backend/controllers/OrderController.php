<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/26 0026
 * Time: 09:17
 */

namespace backend\controllers;


use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends RbacFilterController
{
    public function actionIndex(){
        $models=Order::find();
        $page=new Pagination([
            'totalCount'=>$models->count(),
            'defaultPageSize'=>10,
        ]);
        $models=$models->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models]);
    }
    public function actionList($id){
        $models=OrderGoods::findAll(['order_id'=>$id]);
        if($models==null){
            throw new NotFoundHttpException('页面不存在','404');
        }
        return $this->render('list',['models'=>$models]);
    }
    public function actionUpdateStatus($id){
        $model=Order::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('页面不存在','404');
        }
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            return $this->redirect(['order/index']);
        }
        return $this->render('edit',['model'=>$model]);
    }
}