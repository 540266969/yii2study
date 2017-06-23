<div><?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-primary'])?></div>
<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>上级分类名称</th>
        <th>商品分类名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tbody class="category">
        <tr date_lft="<?=$model->lft?>" date_rgt="<?=$model->rgt?>" tree="<?=$model->tree?>" class="trs">
            <td><?=$model->id?></td>
            <td><?=$model->goodscat?$model->goodscat->name:''?></td>
            <td><?=str_repeat('- ',$model->depth)?><?=$model->name?>  <span  class="glyphicon glyphicon-minus-sign expends" style="float:right"></span></td>
            <td><?=Yii::$app->user->can('goods-category/edit')? \yii\bootstrap\Html::a('编辑',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-primary btn-xs']):''?> <?=Yii::$app->user->can('goods-category/edit')? \yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']):''?></td>
        </tr>
        </tbody>
    <?php endforeach;?>
</table>
<?php
//只要左值大于他本生,且又值小于他本生,并且具有相同的树值的就是其子分类
$js=<<<JS
    $('.expends').click(function() {
      $(this).toggleClass('glyphicon glyphicon-plus-sign');
      $(this).toggleClass('glyphicon glyphicon-minus-sign');
      var now_tr=$(this).closest('tr');
      var now_lft=now_tr.attr('date_lft');
      var now_rgt=now_tr.attr('date_rgt');
      var now_tree=now_tr.attr('tree');
      $('.category tr').each(function() {
        var lft=$(this).attr('date_lft');
        var rgt=$(this).attr('date_rgt');
        var tree=$(this).attr('tree');
        if(now_lft<lft&&now_rgt>rgt&&now_tree==tree){
           $(this).fadeToggle();
       }
      })
    })
JS;
$this->registerJs($js);

