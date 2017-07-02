<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/16 0016
 * Time: 13:43
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleFrom extends Model
{
    public $name;
    public $description;
    public $permissions=[];
    public function rules()
    {
        return [
          [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }
    public function attributeLabels()
    {
        return ['name'=>'角色名称','description'=>'描述','permissions'=>'权限列表'];
    }
    //获取所有的权限名称,和描述的对应关系
    public static function getPermissions(){
        $permissions=\Yii::$app->authManager->getPermissions();
        return ArrayHelper::map($permissions,'name','description');
    }
    //保存角色
    public function saveRole(){
        $authManager=\Yii::$app->authManager;
        //判断角色名称是否存在,存在就不保存,提示错误消息
        if ($authManager->getRole($this->name)){
            $this->addError('name','角色已经存在');
            return false;
        }
        //创建角色,添加数据,保存角色
        $role=$authManager->createRole($this->name);
        $role->description=$this->description;
        $authManager->add($role);
        if($this->permissions!=null){
            //遍历权限名称,获取权限对象,给角色添加相应的权限
            foreach ($this->permissions as $permissionName){
                $permission=$authManager->getPermission($permissionName);
                $authManager->addChild($role,$permission);
            }
        }
        return true;
    }
    public function getMessage(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //通过角色获取全部的权限名称,循环遍历,保存到数组中去;
       $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
       foreach ($permissions as $permission){
           $this->permissions[]=$permission->name;
       }
    }
    public function editRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        //判定角色名称是否已经修改,修改了宾且修改后的值与数据库中其他的相同,提示角色已经存在
        if($this->name!=$name&&$authManager->getRole($this->name)){
            $this->addError('角色名称已经存在');
            return false;
        }
        $authManager->removeChildren($role);
        $role->name=$this->name;
        $role->description=$this->description;
        $authManager->update($name,$role);
        if($this->permissions!=null){
            foreach ($this->permissions as $permissionName){
                $permission=$authManager->getPermission($permissionName);
                $authManager->addChild($role,$permission);
            }
        }
        return true;
    }
}