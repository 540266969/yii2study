<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 18:39
 */

namespace backend\controllers;


use frontend\models\Member;
use yii\web\Controller;

class MemberController extends Controller
{
    public function actionIndex(){
        $models=Member::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    public function actionDel($id){
        $model=Member::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','用户删除成功');
        return $this->redirect(['member/index']);
    }
}