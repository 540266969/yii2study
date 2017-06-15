<?php
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'intro')->textarea();
echo $from->field($model,'sort');
echo $from->field($model,'status')->radioList(\backend\models\Brand::$statuItem);
echo $from->field($model,'is_help')->radio(['value'=>1]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();