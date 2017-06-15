<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170608_094436_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('文章名称'),
            'intro'=>$this->text()->comment('文章简介'),
            'article_category_id'=>$this->integer()->notNull()->comment('文章分类ID'),
            'sort'=>$this->smallInteger(2)->comment('排序号'),
            'status'=>$this->smallInteger(2)->notNull()->defaultValue(0)->comment('状态'),
            'create_time'=>$this->integer(11)->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
