<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170620_115553_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(100)->notNull()->comment('收货人'),
            'privace'=>$this->string(30)->notNull()->comment('省份'),
            'city'=>$this->string(30)->notNull()->comment('城市'),
            'area'=>$this->string(30)->notNull()->comment('地区'),
            'detail'=>$this->string(200)->notNull()->comment('详细地址'),
            'tel'=>$this->char(11)->notNull()->comment('手机号码'),
            'is_default'=>$this->integer(1)->comment('默认地址'),
            'member_id'=>$this->integer()->notNull()->comment('用户编号'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
