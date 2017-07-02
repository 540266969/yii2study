<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
                <?php
                $form=\yii\widgets\ActiveForm::begin(['fieldConfig'=>[
                    'options'=>['tag'=>'li'],'errorOptions'=>['tag'=>'p']
                ]]);
                echo '<ul>';
                echo $form->field($model,'username')->textInput(['class'=>'txt']);
                echo $form->field($model,'password')->passwordInput(['class'=>'txt']);
                echo $form->field($model,'repassword')->passwordInput(['class'=>'txt']);
                echo $form->field($model,'email')->textInput(['class'=>'txt']);
                echo $form->field($model,'tel')->textInput(['class'=>'txt']);
                $btn=\yii\helpers\Html::button('发送短信验证码',['id'=>'send_msg_button']);
                echo $form->field($model,'msgcode',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$btn\n{hint}\n{error}"])->textInput(['class'=>'txt']);
                echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
                echo '<li>';
                echo $form->field($model,'agree',[
                    'options'=>['tag'=>false],'errorOptions'=>['tag'=>false]
                ])->checkbox(['class'=>'chb'],false)->label(\yii\helpers\Html::decode('&nbsp;'));
                echo '我已阅读并同意《用户注册协议》';
                echo '</li>';
               // echo '<li><label for="">&nbsp;</label><input type="checkbox" class="chb" checked="checked" name="agree" value="agree"/> 我已阅读并同意《用户注册协议》</li>';
                echo '<li><label for="">&nbsp;</label><input type="submit" value="" class="login_btn" /></li>';
                echo '</ul>';
                \yii\widgets\ActiveForm::end();
                ?>
        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>想请我吃饭</strong>”发送到：</p>
            <p><strong>1069070056</strong></p>
            <p>让你体验和欣哥面对面的亲密接触,<strong>限妹子</strong></p>
        </div>

    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['member/msg']);
$js=<<<JS
    $('#send_msg_button').click(function(){  
       time=30; 
      var tel=$('#member-tel').val();
      //console.log(tel);
      $.post('$url',{tel:tel},function(message) {
          //console.log(message);return;
         if(message=='success'){
            interval = setInterval(function(){
            time--;
           if(time<=0){
           clearInterval(interval);
          var html = '发送短信验证码';
          $('#send_msg_button').prop('disabled',false);
          } else{
         var html = time + ' 秒后再次获取';
         $('#send_msg_button').prop('disabled',true);
         }
         $('#send_msg_button').text(html);
         },1000);
             alert('发送短信成功');
         }else{
             alert(message);
         }
      }) 
    });
JS;

$this->registerJs($js);
?>
