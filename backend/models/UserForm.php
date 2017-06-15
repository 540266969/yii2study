<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14 0014
 * Time: 11:12
 */

namespace backend\models;


use yii\base\Model;


class UserForm extends Model
{
    public $username;
    public $password_hash;
    public $code;
    public $remember=true;
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['remmeber','boolean'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['username','mycheck'],
        ];
    }
    public function attributeLabels()
    {
        return ['username'=>'用户名','password_hash'=>'密码','code'=>'验证码','remember'=>'自动登录'];
    }
    public function mycheck(){
        $login=User::findOne(['username'=>$this->username]);
        if($login){
            if(\Yii::$app->security->validatePassword($this->password_hash,$login->password_hash)){
                //echo '登录成功';exit;
                //var_dump($this->remember);exit;
                if($this->remember){
                    \Yii::$app->user->login($login,3600*24*7);
                }else{
                    \Yii::$app->user->login($login);
                }

//                $cookie=\Yii::$app->response->cookies;
//                $cookie->add(new Cookie(['user'=>json_encode($login)]));
                $login->last_login_at=time();
                $login->last_login_ip=\Yii::$app->request->getUserIP();
                $login->save(false);
            }else{
                $this->addError('username','用户名或者密码不正确');
            }
        }else{
            $this->addError('username','用户名或者密码不正确');
        }
    }
}