<?php

namespace backend\controllers;

use backend\models\PermissionFrom;
use backend\models\RoleFrom;
use yii\web\NotFoundHttpException;


class RbacController extends RbacFilterController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAddPermission(){
        $model=new PermissionFrom();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->savePermission()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect(['index-permission']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public  function actionIndexPermission(){
        $models =\Yii::$app->authManager->getPermissions();
        return $this->render('index-permission',['models'=>$models]);
    }
    public function actionEditPermission($name){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model=new PermissionFrom();
        $model->getMessage($permission);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->editPermission($name)){
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['index-permission']);
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    public function actionDelPermission($name){
        $authManager=\Yii::$app->authManager;
        if(!$authManager->getPermission($name)){
            throw new NotFoundHttpException('权限不存在');
        }
        $authManager->remove($authManager->getPermission($name));
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['index-permission']);

    }
    public function actionAddRole(){
        $model=new RoleFrom();
        if ($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->saveRole()){
                \Yii::$app->session->setFlash('success','角色添加成功');
                return $this->redirect(['index-role']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionIndexRole(){
        $models=\Yii::$app->authManager->getRoles();
        return $this->render('index-role',['models'=>$models]);
    }
    public function actionEditRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if (!$role){
            throw new NotFoundHttpException('角色不存在');
        }
        $model= new RoleFrom();
        $model->getMessage($role);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->editRole($name)){
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['index-role']);
            }
        }
        return $this->render('add-role',['model'=>$model]);
    }
    public function actionDelRole($name){
        $authManager=\Yii::$app->authManager;
        $authManager->removeChildren($authManager->getRole($name));
        $authManager->remove($authManager->getRole($name));
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect(['index-role']);
    }
}
