<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170609_103351_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree'=>$this->integer(11)->notNull()->defaultValue(0)->comment('树id'),
            'lft'=>$this->integer(11)->notNull()->comment('左值'),
            'rgt'=>$this->integer(11)->notNull()->comment('右值'),
            'depth'=>$this->integer(11)->notNull()->comment('层次'),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'parent_id'=>$this->integer()->notNull()->comment('上级分类'),
            'intro'=>$this->text()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
