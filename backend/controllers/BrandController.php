<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //$models=Brand::find()->where('status<>-1')->all();
        $model=Brand::find();
        $count=$model->where('status<>-1')->count();
        //初始化分页工具条,传入数据总条数
        $page=new Pagination([
            'totalCount'=>$count,
            'defaultPageSize'=>2
        ]);
        $models=$model->where('status<>-1')->offset($page->offset)->limit($page->limit)->all();
        //var_dump($model);exit;
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
//                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//                if($model->imgFile){
//                    $filename='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename);
//                    $model->logo=$filename;
//                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
//                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//                if($model->imgFile){
//                    $filename='/images/brand/'.uniqid().'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename);
//                    $model->logo=$filename;
//                }
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
    }
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl=$action->getWebUrl();
                    //$action->output['fileUrl'] = $action->getWebUrl();
                    //七牛云使用步骤
                    //1重新crazyfd,把它添加到组件中,在配置文件中配置相应的初始化信息
                    //2使用\Yii::$app->qiniu 创建一个新的对象
                    //3调用uploadFile方法保存文件到七牛云中,传递一个key值,接收返回的路径信息
                    //4调用getlink方法接收返回的路径信息
                    //5 把路径信息放入到uploadfy的output方法中,返回到输出页面
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile($action->getSavePath(),$imgUrl);
                    //$qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl']=$url;
                    //var_dump($url);
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
        $qiniu=\Yii::$app->qiniu;
        $key = time();
        $fileName=\Yii::getAlias('@webroot').'/images/brand/59390012e854d.jpg';
        $qiniu->uploadFile($fileName,$key);
        $url = $qiniu->getLink($key);
    }
}
