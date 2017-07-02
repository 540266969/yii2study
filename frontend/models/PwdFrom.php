<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23 0023
 * Time: 10:18
 */

namespace frontend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class PwdFrom extends Model
{
    const SCENARIO_FINDPWD = 'find-pwd';
    const SCENARIO_EDITPWD = 'edit-pwd';
    public $username;
    public $email;
    public $password;
    public $repassword;
    public $code;
    public function scenarios()
    {
        $myscenarios=[
            [
                self::SCENARIO_FINDPWD=>['username','email'],
                self::SCENARIO_EDITPWD=>['password','repassword'],
            ]
        ];
        $parent= parent::scenarios();
        return ArrayHelper::merge($myscenarios,$parent);
    }

    public function rules()
    {
        return [
            [['username','email','code'],'required','on'=>self::SCENARIO_FINDPWD],
            [['email'],'email'],
            [['password','repassword'],'required','on'=>self::SCENARIO_EDITPWD],
            ['password','string','min'=>6,'max'=>20],
            ['repassword','compare','compareAttribute'=>'password','message'=>'两次密码不一致','on'=>self::SCENARIO_EDITPWD],
            ['code','captcha'],

        ];
    }
    public function attributeLabels()
    {
        return ['username'=>'用户名','password'=>'输入密码','repassword'=>'确认密码','email'=>'邮箱','code'=>'验证码'];
    }
    public function match(){
        $member=Member::findOne(['username'=>$this->username]);
//        var_dump($member->password_reset_token);
//        exit;
        if($member&&$member->email==$this->email){
            $member->password_reset_token=\Yii::$app->security->generateRandomString();
            $member->save(false);
            $url='http://www.yiishop.com/index.php?r=member/edit-pwd&username='.$this->username.'&password_reset_token='.$member->password_reset_token;
            return $url;
        }
        return false;
    }
}