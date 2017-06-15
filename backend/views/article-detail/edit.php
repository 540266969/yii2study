<?php
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();