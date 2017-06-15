<?php $form = \yii\bootstrap\ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'id' => 'cateadd-form',
    'options' => ['class' => 'form-horizontal'],
]); ?>

<?= $form->field($searchModel, 'name',[
    'options'=>['class'=>''],
    'inputOptions' => ['placeholder' => '名称搜索','class' => 'input-sm form-control'],
])->label(false) ?>
    <span class="input-group-btn">
    <?= \yii\bootstrap\Html::submitButton('Go!', ['class' => 'btn btn-sm btn-primary']) ?>
</span>
<?php \yii\bootstrap\ActiveForm::end(); ?>

