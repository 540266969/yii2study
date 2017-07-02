<?php
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'url');
echo $from->field($model,'sort');
echo $from->field($model,'description')->textarea();
echo $from->field($model,'parent_id')->dropDownList($messages);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();