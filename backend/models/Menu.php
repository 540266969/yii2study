<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $description
 * @property integer $parent_id
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }
    //获取父亲分了名称
    public function getParent(){
        return $this->hasOne(Menu::className(),['id'=>'parent_id']);
    }
    //获取子孙分类名称
    public function getChildren(){
        return $this->hasMany(Menu::className(),['parent_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['parent_id','sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'url' => '路由',
            'sort'=>'排序号',
            'description' => '描述',
            'parent_id' => '上级分类',
        ];
    }
    public function getTop(){
        $messages=Menu::find()->where('parent_id=0')->all();
        $top=['0'=>'顶级菜单'];
        $messages=ArrayHelper::map($messages,'id','name');
        $messages=ArrayHelper::merge($top,$messages);
        return $messages;
        //var_dump($messages);exit;
    }
}
