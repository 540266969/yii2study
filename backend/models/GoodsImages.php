<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_images".
 *
 * @property integer $id
 * @property string $img
 * @property integer $goods_id
 */
class GoodsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //定义文件上传的字段
    public $imgfiles;
    public function getGoods(){
        return $this->hasOne(Goods::className(),['goods_id'=>'id']);
    }
    public static function tableName()
    {
        return 'goods_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['imgfiles'], 'file', 'extensions' =>['jpg','png','bmp'],'maxFiles'=>10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'img' => '图片地址',
            'goods_id' => '商品名称',
        ];
    }
}
