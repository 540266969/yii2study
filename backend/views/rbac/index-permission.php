<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>权限名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?=Yii::$app->user->can('rbac/edit-permission')? \yii\bootstrap\Html::a('编辑',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-warning btn-xs']):''?> <?=Yii::$app->user->can('rbac/del-permission')? \yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$model->name],['class'=>'btn btn-danger btn-xs']):''?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});');