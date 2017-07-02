<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $username
 * @property string $privace
 * @property string $city
 * @property string $area
 * @property string $detail
 * @property string $tel
 * @property integer $is_default
 * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function getPrivaces(){
        return $this->hasOne(Locations::className(),['id'=>'privace']);
    }
    public function getCites(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }
    public function getAreas(){
        return $this->hasOne(Locations::className(),['id'=>'area']);
    }
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'privace', 'city', 'area', 'detail', 'tel'], 'required'],
            [['is_default'], 'integer'],
            [['username'], 'string', 'max' => 100],
            //[['privace', 'city', 'area'], 'string', 'max' => 20],
            [['detail'], 'string', 'max' => 200],
            [['tel'], 'string', 'max' => 11,'min'=>11],
            [['area'],'mycheck'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '收货人',
            'privace' => '省份',
            'city' => '城市',
            'area' => '地区',
            'detail' => '详细地址',
            'tel' => '手机号码',
            'is_default' =>'',
        ];
    }
    public function mycheck(){
        if($this->area==-1){
            $this->addError('area','请选择地区');
            return false;
        }
    }
}
