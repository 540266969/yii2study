<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170619_034601_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->notNull()->comment('用户名'),
            'auth_key'=>$this->string(30),
            'password_hash'=>$this->string(100)->comment('密码'),
            'email'=>$this->string(100)->notNull()->comment('邮箱'),
            'tel'=>$this->char(11)->notNull()->comment('电话号码'),
            'last_login_time'=>$this->integer()->comment('最后登录事件'),
            'last_login_ip'=>$this->integer()->comment('最后登录IP'),
            'status'=>$this->integer(1)->notNull()->defaultValue(1)->comment('状态'),
            'created_at'=>$this->integer()->comment('创建时间'),
            'updated_at'=>$this->integer()->comment('最后修改时间'),
            'password_reset_token'=>$this->string(255)->comment('重置密码'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
