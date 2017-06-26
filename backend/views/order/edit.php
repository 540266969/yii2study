<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'status')->dropDownList(\frontend\models\Order::$stutu);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();