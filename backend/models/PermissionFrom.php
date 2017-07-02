<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16 0016
 * Time: 12:41
 */

namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;

class PermissionFrom extends Model
{
    public $name;
    public $description;
    public function rules()
    {
        return [
            [['name','description'],'required'],
        ];
    }
    public function attributeLabels()
    {
        return ['name'=>'权限名','description'=>'描述'];
    }
    public function savePermission(){
        $permission=\Yii::$app->authManager;
        if($permission->getPermission($this->name)){
            $this->addError('name','权限名称已经存在');
            return false;
        }
        $addPermission=$permission->createPermission($this->name);
        $addPermission->description=$this->description;
        $permission->add($addPermission);
        return true;
    }
    //修改的时候回显数据的方法,使用数据类型限制,限制为权限对象
    public function getMessage(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }
    //修改权限调用update方法进行
    public function editPermission($name){
        $authManager=\Yii::$app->authManager;
        if($this->name!=$name){
            if($authManager->getPermission($this->name)){
                $this->addError('name','权限名称已经存在');
                return false;
            }
        }
        $permission=$authManager->getPermission($name);
        $permission->name=$this->name;
        $permission->description=$this->description;
        $authManager->update($name,$permission);
        return true;
    }
}