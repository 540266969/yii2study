<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>订单编号</th>
        <th>商品名称</th>
        <th>商品图片</th>
        <th>商品价格</th>
        <th>商品数量</th>
        <th>小计</th>
        <th>回到订单页面</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->order_id?></td>
            <td><?=$model->goods_name?></td>
            <td><?=\yii\bootstrap\Html::img($model->logo)?> </td>
            <td><?=$model->price?></td>
            <td><?=$model->amount?></td>
            <td><?=$model->total?></td>
            <td><?=Yii::$app->user->can('order/index')? \yii\bootstrap\Html::a('回到订单页面',['order/index'],['class'=>'btn btn-danger btn-xs']):''?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$page,
//    'nextPageLabel'=>'下一页',
//    'prevPageLabel'=>'上一页',
//]);
?>
