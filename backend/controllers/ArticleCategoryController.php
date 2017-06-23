<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticleCategoryController extends RbacFilterController
{
    public function actionIndex()
    {
        //找到全部的状态值不为-1的结果记录
        //$models=ArticleCategory::find()->where('status<>-1')->all();
        $model=ArticleCategory::find();
        $count=$model->where('status<>-1')->count();
        $page=new Pagination([
            'defaultPageSize'=>'2',
            'totalCount'=>$count,
        ]);
        $models=$model->where('status<>-1')->offset($page->offset)->limit($page->limit)->all();
        //渲染模版,传递数据
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionAdd(){
        //创建模型对象
        $model=new ArticleCategory();
        //调用request组件
        $request=\Yii::$app->request;
        //判断是否是post提交数据
        if($request->isPost){
            //接收post请求的数据
            $model->load($request->post());
            //验证数据的有效性
            if($model->validate()){
                //保存数据
                $model->save();
                //在页面输出添加成功的消息
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }
        }
        //不是post提交的时候调用add视图,传递模型
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        //找到对应的一条数据
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        //找到对应的id的模型对象
        $model=ArticleCategory::findOne(['id'=>$id]);
        //修改其状态值为-1
        $model->status=-1;
        //保存到数据库中
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article-category/index']);
    }
}
