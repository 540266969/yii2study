<?php
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'intro')->textarea();
echo $from->field($model,'sort');
echo $from->field($model,'status')->radioList(\backend\models\Brand::$statuItem);
echo $from->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($lists,'id','name'));
if(!$model->name){
    echo $from->field($model,'content')->textarea();
}
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();