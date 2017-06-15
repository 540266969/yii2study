<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\UserForm;
use yii\web\Cookie;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models=User::find()->where('status<>-1')->all();
        return $this->render('index',['models'=>$models]);
    }

    public function actionAdd(){
        $model=new User();
        $model->setScenario('add');
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
            //为什么生成随机字符串出现乱码呢?
            $model->auth_key=\Yii::$app->security->generateRandomString();
            //var_dump($model->auth_key);exit;
            $model->created_at=time();
            $model->save(false);
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->refresh();
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionLogin(){
        $model=new UserForm();
//      if (\Yii::$app->request->cookies->get('user')) {
//            $cookie = json_decode(\Yii::$app->request->cookies->get('userinfo'));
//            \Yii::$app->user->identity=unserialize($cookie);
//         return $this->redirect(['brand/index']);
//       }
        if ($model->load(\Yii::$app->request->post())&&$model->validate()) {
            //var_dump($model->username);
//                 $cookie=\Yii::$app->response->cookies;
//                $cookie->add(new Cookie(['user'=>serialize($model)]));
            return $this->redirect(['brand/index']);
            //exit;
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //修改用户信息的方法
    public function actionEdit(){
        $id=\Yii::$app->user->id;
        if($id==null){
            \Yii::$app->session->setFlash('warning','请先登录在操作');
            return $this->redirect(['user/login']);
        }
        $model=User::findOne(['id'=>$id]);
        $model->setScenario('edit');
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->updated_at=time();
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('add',['model'=>$model]);
        //var_dump($id);
    }
    //修改密码的方法
    public function actionPwd(){
        $id=\Yii::$app->user->id;
        if($id==null){
            \Yii::$app->session->setFlash('warning','请先登录在操作');
            return $this->redirect(['user/login']);
        }
       $model=User::findOne(['id'=>$id]);
        $model->setScenario('pwd');
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->newpassword);
            $model->updated_at=time();
            $model->save(false);
            \Yii::$app->session->setFlash('success','密码修改成功,请重新登录');
            return $this->redirect(['user/login']);
        }
        return $this->render('pwd',['model'=>$model]);
    }
    public function actionTest(){
        var_dump(\Yii::$app->user->isGuest);
    }
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
}
