<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>菜单名称</th>
        <th>路由</th>
        <th>描述</th>
        <th>上级菜单名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->url?></td>
            <td><?=$model->description?></td>
            <td><?=$model->parent?$model->parent->name:'顶级菜单'?></td>
            <td><?=Yii::$app->user->can('menu/edit')? \yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$model->id],['class'=>'btn btn-primary btn-xs']):''?> <?=Yii::$app->user->can('menu/del')? \yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']):''?></td>
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

