<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 16:12
 */

namespace frontend\models;


use yii\base\Model;

class MemberFrom extends Model
{
    public $username;
    public $password;
    public $code;
    public $remember;
    public function rules()
    {
        return [
          [['username','password'],'required'],
          ['code','captcha'],
          ['remember','boolean'],
          ['username','mycheck'],
        ];
    }
    public function attributeLabels()
    {
        return ['username'=>'用户名','password'=>'密码','code'=>'验证码','remember'=>''];
    }
    public function mycheck(){
        $login=Member::findOne(['username'=>$this->username]);
        if($login->status==0){
            $this->addError('username','该用户名已经被注销,如需激活请联系我们或者重新注册帐号');
            return false;
        }
        if($login){
            if(\Yii::$app->security->validatePassword($this->password,$login->password_hash)){
                //echo '登录成功';exit;
                //var_dump($this->remember);exit;
                if($this->remember){
                    \Yii::$app->user->login($login,3600*24*7);
                }else{
                    \Yii::$app->user->login($login);
                }
                $login->last_login_time=time();
                $login->last_login_ip=ip2long(\Yii::$app->request->getUserIP());
                $login->save(false);
                //登录之后把所有的cookie中的数据保存到数据库中,然后清空cookie
                $cookies=\Yii::$app->request->cookies;
                $member_id=\Yii::$app->user->id;
                $data=unserialize($cookies->get('cart'));
                if($data){
                    foreach ($data as $k=>$v){
                        $model=new Cart();
                        $model->member_id=$member_id;
                        $model->goods_id=$k;
                        $model->amount=$v;
                        $model->save();
                    }
                    $cookies=\Yii::$app->response->cookies;
                    $cookies->remove('cart');
                }
            }else{
                $this->addError('username','用户名或者密码不正确');
            }
        }else{
            $this->addError('username','用户名或者密码不正确');
        }
    }
}