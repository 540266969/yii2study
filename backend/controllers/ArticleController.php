<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8 0008
 * Time: 19:23
 */

namespace backend\controllers;


use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use backend\models\Article;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends RbacFilterController
{
    public function actionAdd(){
        $model=new Article();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
           // var_dump($model);exit;
            if($model->validate()){
                $model->create_time=time();
                $model->save();
                $detail=new ArticleDetail();
                $detail->article_id=$model->id;
                $detail->content=$model->content;
                $detail->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
        }
        $lists=ArticleCategory::find()->where('status<>-1')->all();
        return $this->render('add',['model'=>$model,'lists'=>$lists]);
    }
    public function actionEdit($id){
            $model=Article::findOne(['id'=>$id]);
            $request=\Yii::$app->request;
            if($request->isPost){
                    $model->load($request->post());
                    //var_dump($model->validate());exit;
                    if($model->validate()){
                        $model->save();
                        \Yii::$app->session->setFlash('success','修改成功');
                        return $this->redirect(['article/index']);
                    }
            }
            $lists=ArticleCategory::find()->where('status<>-1')->all();
            return $this->render('add',['model'=>$model,'lists'=>$lists]);
    }
    public function actionIndex(){
        //$models=Article::find()->where('status<>-1')->all();
        $model=Article::find();
        $count=$model->where('status<>-1')->count();
        $page=new Pagination([
            'defaultPageSize'=>'2',
            'totalCount'=>$count,
        ]);
        $models=$model->where('status<>-1')->offset($page->offset)->limit($page->limit)->all();
        //渲染模版,传递数据
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionDel($id){
        $model=Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }
}