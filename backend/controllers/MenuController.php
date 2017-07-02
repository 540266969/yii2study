<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


class MenuController extends RbacFilterController
{
    public function actionIndex()
    {

        $page=new Pagination(['defaultPageSize'=>10,'totalCount'=>Menu::find()->count()]);
        $models=Menu::find()->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionAdd(){
        $model=new Menu();
        $messages=$model->getTop();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }

        return $this->render('add',['model'=>$model,'messages'=>$messages]);
    }
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('访问的页面不存在');
        }
        $messages=$model->getTop();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);
        }

        return $this->render('add',['model'=>$model,'messages'=>$messages]);
    }
    public function actionDel($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('访问的页面不存在');
        }
        //var_dump($model->parent_id);
        $children=Menu::find()->where(['=','parent_id',$model->id])->asArray()->all();
        //var_dump($children);exit;
        if($children){
            \Yii::$app->session->setFlash('danger','删除的分类存在子分类,请先删除子分类,在操作');
            return $this->redirect(['menu/index']);
        }
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['menu/index']);
    }
}
