<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $password
 * @property string $repassword
 * @property string $code
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $password_reset_token
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit-pwd';
    const SCENARIO_API='user-register';
    public $password;//明文密码
    public $repassword;//确认密码
    public $code;//验证码
    public $msgcode;
    public $agree;
    public static function tableName()
    {
        return 'member';
    }
    public function scenarios()
    {
        $myscenarios=[
            [
                'add'=>['username', 'password','repassword','email','tel','msgcode','agree'],
                'edit-pwd'=>['password','repassword'],
            ]
        ];
        $parent= parent::scenarios();
        return ArrayHelper::merge($myscenarios,$parent);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password','repassword','email','tel','msgcode','agree'], 'required','on'=>self::SCENARIO_ADD],
            [['password','repassword'],'required','on'=>self::SCENARIO_EDIT],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 20,'min'=>3],
            [['auth_key'], 'string', 'max' => 100],
            [['password','repassword'],'string','max'=>100,'min'=>6],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'match', 'pattern'=>'/^1[3578]\d{9}$/','message'=>'请输入正确的手机号码,当前支持13 15 17 18号段'],
            [['email'],'email','message'=>'请输入正确的邮箱地址'],
            ['code','captcha','on'=>self::SCENARIO_ADD],
            ['code','captcha','on'=>self::SCENARIO_API,'captchaAction'=>'api/captcha'],
            ['repassword','compare','compareAttribute'=>'password','message'=>'两次密码不一致'],
            ['msgcode','mycheck'],
            [['agree'],'agree'],
            ['password_reset_token','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名 :',
            'auth_key' => 'Auth Key',
            'password' => '密码 :',
            'repassword' => '确认密码 :',
            'email' => '邮箱: ',
            'tel' => '手机号码 :',
            'agree'=>'',
            'msgcode'=>'验证码',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'status' => '状态',
            'code'=>'验证码 :',
            'created_at' => '创建时间',
            'updated_at' => '最后修改时间',
        ];
    }
    public function mycheck(){
        $msgcode=Yii::$app->cache->get('tel'.$this->tel);
        if(!$msgcode||$msgcode!=$this->msgcode){
            $this->addError('msgcode','验证码错误');
        }
    }
    public function agree(){
        if($this->agree==0){
            $this->addError('agree','请同意用户协议然后在注册');
        }
    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key==$authKey;
    }
}
