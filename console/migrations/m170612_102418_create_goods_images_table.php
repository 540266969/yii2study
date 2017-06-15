<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_images`.
 */
class m170612_102418_create_goods_images_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_images', [
            'id' => $this->primaryKey(),
            'img'=>$this->string()->notNull()->comment('图片地址'),
            'goods_id'=>$this->integer()->notNull()->comment('商品名称'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_images');
    }
}
