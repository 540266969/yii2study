<?php
echo \yii\bootstrap\Html::tag('h2',$model->article->name);
echo \yii\bootstrap\Html::a('修改内容',['article-detail/edit','id'=>$model->article_id],['class'=>'btn btn-primary btn-xs']);
echo \yii\bootstrap\Html::a('返回列表页',['article/index'],['class'=>'btn btn-primary btn-xs']);
?>
<div class="container-fluid"><?=$model->content?></div>
