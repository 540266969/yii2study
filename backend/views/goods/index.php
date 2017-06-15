<form class="form-inline" action="index.php?r=goods/index" method="get" style="float:left">
    <input type="hidden" value="goods/index" name="r">
    <div class="form-group">
        <label for="exampleInputName2">名称</label>
        <input type="text" class="form-control" id="exampleInputName2" placeholder="请输入名称" name="name">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail2">商品编号</label>
        <input type="text" class="form-control" id="exampleInputEmail2" placeholder="请输入商品编号" name="sn">
    </div>
    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
</form>
<div style="float:right"><?=\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-primary'])?></div>
<div style="clear: both"></div>
<table class="table table-bordered table-striped" style="margin-top: 10px">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>商品编号</th>
        <th>LOGO</th>
        <th>商品分类名称</th>
        <th>品牌名称</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序号</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><?=\yii\bootstrap\Html::img($model->logo,['width'=>50]);?></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=$model->is_no_sale?'在售':'下架'?></td>
            <td><?=$model->status?'正常':'回收站'?></td>
            <td><?=$model->sort?></td>
            <td><?=\yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-primary btn-xs'])?> <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?> <?=\yii\bootstrap\Html::a('上传商品图',['goods-images/add','id'=>$model->id],['class'=>'btn btn-success btn-xs'])?> <?=\yii\bootstrap\Html::a('修改商品图',['goods-images/index','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
   'pagination'=>$page,
    'nextPageLabel'=>'下一页',
   'prevPageLabel'=>'上一页',
]);
?>
