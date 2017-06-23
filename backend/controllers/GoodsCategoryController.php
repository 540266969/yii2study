<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class GoodsCategoryController extends RbacFilterController
{
    public function actionIndex()
    {
        //找到所有的按照树结构排序,需要先通过tree排序,然后通过左值排序
        $models=GoodsCategory::find()->orderBy('tree','lft')->all();
        //var_dump($modes);exit;
        return $this->render('index',['models'=>$models]);
    }
    public function actionTest(){
        $cate = new GoodsCategory();
        $cate->name='手机';
        $cate->parent_id=0;
        $cate->makeRoot();
       // var_dump($cate->getErrors());exit;
//        $cate1 = new GoodsCategory(['name' => '大家电']);
//        $cate1->parent_id=1;
//        $cate1->prependTo($cate);
//        $cate2 = new GoodsCategory(['name' => '小家电']);
//        $cate2->parent_id=1;
//        $cate2->prependTo($cate);
        echo '执行成功';
    }
    public function actionZtree(){
        $categories=GoodsCategory::find()->asArray()->all();
        $categories=Json::encode($categories);
        return $this->renderPartial('ztree',['categories'=>$categories]);
    }
    public function actionAdd(){
        $model=new GoodsCategory();
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //var_dump($model->parent_id);exit;
            //判断parent_id是不是为0
            if($model->parent_id){
                //不会0的时候需要找到他的父类,然后加入到其父类中
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{
                //为0的时候创建一个跟节点
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        //找出所有的节点,通过asArray返回一个二维数组,把顶级分类添加到数组集合中去,然后
        //转化成json对象
        $categories=GoodsCategory::find()->asArray()->all();
        $categories=Json::encode(ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$categories));
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $old_parent_id=$model->parent_id;
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            //var_dump($model->parent_id);exit;
            try{
                //首先判定父id是否为0,
                if($model->parent_id){
                    //不为0的时候找到其上级节点,调用parent方法加入
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    //$parent;exit;
                    $model->prependTo($parent);
                }else{
                    //当原来的父ID和后面接收保存的父ID相等的时候,调用save方法就可以了
                    if($old_parent_id==$model->parent_id){
                        $model->save();
                    }else{
                        //其他时候调用makeRoot创建新的跟节点
                        $model->makeRoot();
                    }

                }
            }catch (Exception $e){
                var_dump($e->getMessage());
            }

            \Yii::$app->session->setFlash('success','添加成功');
            return $this->refresh();
        }
        //找出所有的节点,通过asArray返回一个二维数组,把顶级分类添加到数组集合中去,然后
        //转化成json对象
        $categories=GoodsCategory::find()->asArray()->all();
        $categories=Json::encode(ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],$categories));
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }
}
