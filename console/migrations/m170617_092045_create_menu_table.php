<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170617_092045_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(255)->notNull()->comment('菜单名称'),
            'url'=>$this->string(100)->comment('路由'),
            'description'=>$this->text()->comment('描述'),
            'sort'=>$this->integer()->notNull()->defaultValue(0)->comment('排序号'),
            'parent_id'=>$this->integer()->notNull()->defaultValue(0)->comment('上级分类'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
