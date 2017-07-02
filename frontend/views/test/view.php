<from>
    <input type="text" name="a" value=""><br/>
    <input type="text" name="b" value=""><br/>
    <input type="text" name="c" value=""><br/>
    <input type="text" name="d" value=""><br/>
    <input type="button" value="提交" id="button">
</from>
<div id="show"></div>
<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['test/cai']);
$csrf=Yii::$app->request->csrfToken;
$js=<<<JS
 $('#button').click(function() {
      var a=$('input[name=a]').val();
    var b=$('input[name=b]').val();
    var c=$('input[name=c]').val();
    var d=$('input[name=d]').val();
    $.post('$url',{a:a,b:b,c:c,d:d,'_csrf-frontend':'$csrf'},function(data) {
       $('<h3>'+data+'<h3>').appendTo($('#show'));
    })
})    
JS;
$this->registerJs($js);
