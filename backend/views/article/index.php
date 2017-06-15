<div><?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-primary'])?></div>
<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>排序号</th>
        <th>状态</th>
        <th>文章分类名称</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Article::$statuItem[$model->status]?></td>
            <td><?=$model->articleCategory->name?></td>
            <td><?=\yii\bootstrap\Html::a('编辑',['article/edit','id'=>$model->id],['class'=>'btn btn-primary btn-xs'])?> <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?> <?=\yii\bootstrap\Html::a('详情',['article-detail/view','id'=>$model->id],['class'=>'btn btn-success btn-xs'])?></td>
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