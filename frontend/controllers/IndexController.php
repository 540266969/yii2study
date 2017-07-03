<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/21 0021
 * Time: 14:40
 */

namespace frontend\controllers;


use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class IndexController extends Controller
{
    public $layout='index';
    public function actionIndex(){
        $this->layout='index';
        return $this->render('index');
    }
    public function actionList($id){
        $this->layout='list';
        $brands=Brand::find()->where('status<>-1')->all();
        $category=GoodsCategory::findOne(['id'=>$id]);
        $parent_id=$category->parent_id;
        $borthers=GoodsCategory::findAll(['parent_id'=>$parent_id]);
        $goods=Goods::find()->andWhere('is_no_sale=1')->andWhere(['goods_category_id'=>$id])->all();
        return $this->render('list',['brands'=>$brands,'goods'=>$goods,'borthers'=>$borthers,'category'=>$category]);
    }
    public function actionGoods($id){
        $this->layout='goods';
        $good=Goods::findOne(['id'=>$id]);
        if($good==null){
            throw new NotFoundHttpException('你访问的页面不存在','404');
        }
        $goods_category_id=$good->goods_category_id;
        $brothers=Goods::find()->where(['goods_category_id'=>$goods_category_id])->orderBy('id desc')->limit(5)->all();
        $img=$good->image[0];
        //var_dump($img);exit;
        //var_dump($good->brand->name);exit;
        //var_dump($img);exit;
        return $this->render('goods',['good'=>$good,'img'=>$img,'brothers'=>$brothers]);
    }
    public function actionSearch(){

    }
}