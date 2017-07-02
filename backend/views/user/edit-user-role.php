<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'roles')->checkboxList(\backend\models\User::getRole());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
echo '&nbsp;&nbsp;';
echo \yii\bootstrap\Html::a('回到列表页面',['user/index'],['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();