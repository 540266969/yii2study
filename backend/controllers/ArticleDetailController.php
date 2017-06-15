<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 23:17
 */

namespace backend\controllers;


use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticleDetailController extends Controller
{
    public function actionView($id){
        $model=ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('view',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=ArticleDetail::findOne(['article_id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->getFlash('success','修改成功');
                return $this->redirect(['article-detail/view','id'=>$model->article_id]);
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
}