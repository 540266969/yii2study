<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
if(!$model->username){
    echo $form->field($model,'password_hash')->passwordInput();
    echo $form->field($model,'repassword')->passwordInput();
}
echo $form->field($model,'email');
if(!$model->username){
    echo $form->field($model,'roles')->checkboxList(\backend\models\User::getRole());
}
echo $form->field($model,'status')->radioList(\backend\models\User::$message);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();