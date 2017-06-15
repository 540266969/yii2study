<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170611_141128_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->comment('商品名称'),
            'sn'=>$this->string(20)->notNull()->comment('货号'),
            'logo'=>$this->string(255)->notNull()->comment('商品突破'),
            'goods_category_id'=>$this->integer(11)->comment('商品分类'),
            'brand_id'=>$this->integer(11)->comment('品牌分类'),
            'market_price'=>$this->decimal(10,2)->notNull()->comment('市场价格'),
            'shop_price'=>$this->decimal(10,2)->notNull()->comment('商品价格'),
            'stock'=>$this->integer()->notNull()->defaultValue(0)->comment('库存'),
            'is_no_sale'=>$this->integer(1)->notNull()->comment('是否出售'),
            'status'=>$this->integer(1)->notNull()->comment('状态'),
            'sort'=>$this->integer()->notNull()->comment('排序号'),
            'create_time'=>$this->integer(11)->notNull()->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
