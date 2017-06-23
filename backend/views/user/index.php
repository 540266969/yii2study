<div><?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-primary'])?></div>
<table class="table table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>管理员名称</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>最后修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=$model->email?></td>
            <td><?=\backend\models\User::$message[$model->status]?></td>
            <td><?=$model->created_at?date('Y-m-d H:i:s',$model->created_at):''?></td>
            <td><?=$model->updated_at?date('Y-m-d H:i:s',$model->updated_at):''?></td>
            <td><?=$model->last_login_at?date('Y-m-d H:i:s',$model->last_login_at):''?></td>
            <td><?=$model->last_login_ip?></td>
            <td><?=Yii::$app->user->can('user/del')? \yii\bootstrap\Html::a('删除',['user/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs']):''?> <?=Yii::$app->user->can('user/edit-user-role')? \yii\bootstrap\Html::a('修改用户角色',['user/edit-user-role','id'=>$model->id],['class'=>'btn btn-info btn-xs']):''?> <?=Yii::$app->user->can('user/reset-password')? \yii\bootstrap\Html::a('重置密码',['user/reset-password','id'=>$model->id],['class'=>'btn btn-danger btn-xs']):''?></td>
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
