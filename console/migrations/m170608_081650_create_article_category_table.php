<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_081650_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->integer(11)->notNull()->comment('排序号'),
            'status'=>$this->smallInteger(2)->notNull()->defaultValue(1)->comment('状态'),
            'is_help'=>$this->smallInteger(2)->notNull()->defaultValue(0)->comment('是否是帮助文档'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
