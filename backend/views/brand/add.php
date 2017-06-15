<?php
use yii\web\JsExpression;

$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'intro')->textarea();
//echo $from->field($model,'imgFile')->fileInput();
//添加隐藏框,提交图片的保存地址
echo $from->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
if($model->logo){
    echo \yii\bootstrap\Html::img('@web'.$model->logo,['id'=>'logos','height'=>50]);
}else{
   echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'logos','height'=>50]);
}
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //使得上传的图片显示
        $('#logos').attr('src',data.fileUrl).show();
        //保存图片路径到隐藏域
        $('#brand-logo').val(data.fileUrl);
        
        
        
    }
}
EOF
        ),
    ]
]);
echo $from->field($model,'sort');
echo $from->field($model,'status')->radioList(\backend\models\Brand::$statuItem);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();