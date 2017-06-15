<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\Goodssearch;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    //显示到主页面;
    public function actionIndex()
    {
        //通过get方式接收传值
        $item=\Yii::$app->request->get();
        //$item=$items['Goodssearch'];
        //var_dump($item);exit;
        //判断是否设置了收索的名称和货号
        $name=isset($item['name'])?$item['name']:'';
        $sn=isset($item['sn'])?$item['sn']:'';
        //构造where条件
        $condition="status=1 and name like '%{$name}%' and  sn like '%{$sn}%'";
        //var_dump($condition);exit;
        $model=Goods::find()->where($condition);
        //var_dump($model->count());exit;
        //获取当前收索的条数
        $count=$model->count();
        //var_dump($count);exit;
        //创建分页模型,传递总条数信息和默认的每页条数
       $page=new Pagination([
            'defaultPageSize'=>'2',
          'totalCount'=>$count,
      ]);
        $models=$model->offset($page->offset)->limit($page->limit)->all();
       // $searchModel= new Goodssearch();
        //var_dump($searchModel->search());exit;
        //$models = $searchModel->search(\Yii::$app->request->queryParams);
        //var_dump($models);exit;
        //return $this->render('test',['models'=>$models,'searchModel'=>$searchModel]);
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    public function actionAdd(){
        //创建goods模型对象和goods_intro的模型对象
        $model=new Goods();
        $goods_intro=new GoodsIntro();
        //var_dump($model->load(\Yii::$app->request->post())->logo);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //获取当前事件
            $today=date('Ymd',time());
            //在每日新增的表中搜索当前日期的数据是否存在
            $today_count=GoodsDayCount::findOne(['day'=>$today]);
            if($today_count){
                //存在把今日数据的总数加一,保存到数据库中
                $today_count->count=$today_count->count+1;
                $today_count->save();
            }else{
                //不存在创建模型对象,传递今天的日期和设置count为1,然后保存到数据库中
                $today_count=new GoodsDayCount();
                $today_count->count=1;
                $today_count->day=$today;
                $today_count->save();
            }
            //获取货号补零的个数
            $length=13-strlen($today.$today_count->count);
            //生成货号,和生成添加时间
            $model->sn=$today.str_repeat('0',$length).$today_count->count;
            $model->create_time=time();
            //保存到模型中
            $model->save(false);
            //加载详情数据
            $goods_intro->load(\Yii::$app->request->post());
            //$goods_intro->content=$model->content;
            //获取该商品的id,保存到模型中去
            $goods_intro->goods_id=$model->id;
            //var_dump($goods_intro->goods_id);exit;
            $goods_intro->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods/index']);
        }
        //需要找到全部的品牌数据,商品分类数据
        $brand=Brand::find()->where('status<>-1')->all();
        $categories=GoodsCategory::find()->asArray()->all();
        $categories=Json::encode(ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$categories));
        return $this->render('add',['model'=>$model,'categories'=>$categories,'brand'=>$brand,'goods_intro'=>$goods_intro]);
    }
    //修改
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        if($model==null){
            \Yii::$app->session->setFlash('danger','非法访问我报警啦');
            return $this->redirect(['goods/index']);
        }
        $goods_intro=GoodsIntro::findOne(['goods_id'=>$id]);
        var_dump($model->load(\Yii::$app->request->post()));
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            $model->save(false);
            $goods_intro->load(\Yii::$app->request->post());
            //$goods_intro->content=$model->content;
            //var_dump($goods_intro->goods_id);exit;
            $goods_intro->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods/index']);
        }
        $brand=Brand::find()->where('status<>-1')->all();
        $categories=GoodsCategory::find()->asArray()->all();
        $categories=Json::encode(ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$categories));
        return $this->render('add',['model'=>$model,'categories'=>$categories,'brand'=>$brand,'goods_intro'=>$goods_intro]);
    }
    //删除
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=0;
        $model->save();
        \Yii::$app->session->setFlash('success','删除成功,如果错误删除,请联系DBA回复数据');
        return $this->redirect(['goods/index']);
    }
    //使用uploadfy插件和ueditor插件
    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
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
                    //$action->output['fileUrl'] = $action->getWebUrl();
                    $imgUrl=$action->getWebUrl();
                    //使用七牛云插件
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl']=$url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}
