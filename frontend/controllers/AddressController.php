<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;
use frontend\models\Member;
use yii\helpers\Json;

class AddressController extends \yii\web\Controller
{
    public $layout='address';
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAddress(){
        $model=new Address();
        $member_id=\Yii::$app->user->id;
        $messages=Address::findAll(['member_id'=>$member_id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->member_id=$member_id;
            $model->save(false);
            \Yii::$app->session->setFlash('success','添加成功');
        }
        return $this->render('address',['model'=>$model,'messages'=>$messages]);
    }
    public function actionLocations(){
        $pid=\Yii::$app->request->post('pid');
        $models=Locations::findAll(['parent_id'=>$pid]);
        echo Json::encode($models);
    }
    public function actionSetDefault($id){
        $member_id=\Yii::$app->user->id;
        $models=Address::findAll(['member_id'=>$member_id]);
        foreach ($models as $model){
            if ($model->id==$id){
                $model->is_default=1;
            }else{
                $model->is_default=0;
            }
            $model->save(false);
        }
        \Yii::$app->session->setFlash('success','设置成功');
        return $this->redirect(['address/address']);
    }
    public function actionDel($id){
        $model=Address::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['address/address']);
    }
    public function actionEdit($id){
        $model=Address::findOne(['id'=>$id]);
        $member_id=\Yii::$app->user->id;
        $messages=Address::findAll(['member_id'=>$member_id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //var_dump(\Yii::$app->request->post());exit;
            //$model->member_id=\Yii::$app->$member_id;
            $model->save(false);
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['address/address']);
        }
        return $this->render('address',['model'=>$model,'messages'=>$messages]);
    }
}
