<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_login_ip
 * @property integer $last_login_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_PWD='pwd';
    public static $message=[1=>'正常',0=>'异常'];
    public $newpassword;
    public $repassword;
    public $code;
    public $roles=null;
    public static function tableName()
    {
        return 'user';
    }
    public function scenarios()
    {
        $myscenarios=[
            [
                'add'=>['username', 'password_hash', 'email','repassword','status'],
                'edit'=>['username','email'],
                'pwd'=>['newpassword','repassword'],
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
            [['username', 'password_hash', 'email','repassword'], 'required','on'=>self::SCENARIO_ADD],
            [['username','email'], 'required','on'=>self::SCENARIO_EDIT],
            [['newpassword','repassword'],'required','on'=>self::SCENARIO_PWD],
            [['status', 'created_at', 'updated_at', 'last_login_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 100],
            [['email','username'], 'unique'],
            ['repassword','compare','compareAttribute'=>'newpassword','message'=>'两次密码不一致','on'=>self::SCENARIO_PWD],
            ['repassword','compare','compareAttribute'=>'password_hash','message'=>'两次密码不一致','on'=>self::SCENARIO_ADD],
            [['email'],'email'],
            ['roles','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'newpassword'=>'新密码',
            'repassword'=>'确认密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'roles'=>'角色',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_ip' => 'Last Login Ip',
            'last_login_at' => 'Last Login At',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    //获取所有角色名称
    public static function getRole(){
        $authManager=Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','description');
    }
    //添加角色
    public function addRoles($id){
        $authManager=\Yii::$app->authManager;
        if($this->roles!=null){
            foreach ($this->roles as $roleName){
                $role=$authManager->getRole($roleName);
                $authManager->assign($role,$id);
            }
        }
        return true;
    }
    //获取所有选中角色
    public function getUserRole($id){
        $authManager=Yii::$app->authManager;
        $userroles=$authManager->getRolesByUser($id);
        foreach ($userroles as $userrole){
            $this->roles[]=$userrole->name;
        }
    }
    //修改用户角色,先清空全部,然后遍历循环获取所有勾选的角色名,添加上去
    public function editRoles($id){
        $authManager=Yii::$app->authManager;
        $authManager->revokeAll($id);
        foreach ($this->roles as $roleName){
            $role=$authManager->getRole($roleName);
            $authManager->assign($role,$id);
        }
        return true;

    }
    //获取用户能够使用的菜单
    public function GetMenus(){
        $menus=Menu::find()->where(['parent_id'=>0])->all();
        $menuItems=[];
        foreach ($menus as $menu){
            $items=[];
            foreach ($menu->children as $child){
                if(Yii::$app->user->can($child->url)){
                    $items[]=['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            $menuItems[]=['label'=>$menu->name,'items'=>$items];
        }
        return $menuItems;
    }
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
        // TODO: Implement findIdentityByAccessToken() method.
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
