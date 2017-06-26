<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>用户名称</th>
        <th>收货地址</th>
        <th>支付方式</th>
        <th>配送方式</th>
        <th>应付金额</th>
        <th>状态</th>
        <th>下单时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->province?> <?=$model->city?> <?=$model->area?> <?=$model->address?> <?=$model->tel?></td>
            <td><?=$model->payment_name?> </td>
            <td><?=$model->delivery_name?> <?=$model->delivery_price?></td>
            <td><?=$model->total?></td>
            <td><?=\frontend\models\Order::$stutu[$model->status]?></td>
            <td><?=$model->create_time?date('Y-m-d H:i:s',$model->create_time):''?></td>
            <td><?=Yii::$app->user->can('order/update-status')? \yii\bootstrap\Html::a('修改状态',['order/update-status','id'=>$model->id],['class'=>'btn btn-warning btn-xs']):''?> <?=Yii::$app->user->can('order/list')? \yii\bootstrap\Html::a('订单详情',['order/list','id'=>$model->id],['class'=>'btn btn-info btn-xs']):''?></td>
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
