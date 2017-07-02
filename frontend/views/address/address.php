<div class="content fl ml10">
    <div class="address_hd">
        <h3>收货地址薄</h3>
        <?php foreach($messages as $message):?>
        <dl>
            <dt><?=$message->username?> <?=$message->privaces->name?> <?=$message->cites->name?> <?=$message->areas->name?> <?=$message->detail?> <?=$message->tel?></dt>
            <dd>
                <?=\yii\helpers\Html::a('修改',['address/edit','id'=>$message->id])?>
                <?=\yii\helpers\Html::a('删除',['address/del','id'=>$message->id])?>
                <?=$message->is_default?'':\yii\helpers\Html::a('设为默认地址',['address/set-default','id'=>$message->id])?>
            </dd>
        </dl>
        <?php endforeach;?>

    </div>

    <div class="address_bd mt10">
        <h4>新增收货地址</h4>
        <?php
        $from=\yii\widgets\ActiveForm::begin(['fieldConfig'=>[
            'options'=>['tag'=>'li'],'errorOptions'=>['tag'=>'p']
        ]]);
        echo '<ul>';
        echo $from->field($model,'username')->textInput(['class'=>'txt']);
        echo '<li>';
        echo $from->field($model,'privace',['options'=>['tag'=>false],'errorOptions'=>['tag'=>false]])->dropDownList([-1=>'请选择省份'])->label('所在地区');
        echo $from->field($model,'city',['options'=>['tag'=>false],'errorOptions'=>['tag'=>false]])->dropDownList([-1=>'请选择城市'])->label(false);
        echo $from->field($model,'area',['options'=>['tag'=>false],'errorOptions'=>['tag'=>'p']])->dropDownList([-1=>'请选择地区'])->label(false);
        echo '</li>';
        echo $from->field($model,'detail')->textInput(['class'=>'txt address']);
        echo $from->field($model,'tel')->textInput(['class'=>'txt']);
        echo '<li>';
        echo $from->field($model,'is_default',['options'=>['tag'=>false],'errorOptions'=>['tag'=>false]])->checkbox(['class'=>'check'],false)->label(\yii\helpers\Html::decode("&nbsp"));
        echo'设为默认地址';
        echo '</li>';
        echo '<li><label for="">&nbsp;</label><input type="submit" name="" class="btn" value="保存" /></li>';
        echo '</ul>';
        \yii\widgets\ActiveForm::end();
        ?>
    </div>

</div>
<!-- 右侧内容区域 end -->
</div>
<?php
$url=\yii\helpers\Url::to(['address/locations']);
$csrf=Yii::$app->request->csrfToken;

$js=<<<JS
    $('dl:last').addClass('last');
    $(function(){
				//使用ajax请求php获取省份信息
				var data = {pid:0,'_csrf-frontend':'$csrf'};
				$.post('{$url}',data,function(response){
					$(response).each(function(i,v){
					    //console.log(v);
					    //return false;
						var html = '<option value="'+v.id+'">'+v.name+'</option>';
						$(html).appendTo('#address-privace');
					});
				},'json');	
			});
			
			//使用ajax请求php获取城市信息,
			$('#address-privace').on('change',function(){
				//在获取城市信息之前先删除原来保留的城市和地区信息
				$('#address-city').find('option:not(:first)').remove();
				$('#address-area').find(' option:not(:first)').remove();
				//获取传递的省份的id
				var dat=$('#address-privace').find('option:selected').val();
				//构造成JSON对象来执行ajax与后端的交互
				var data={pid:dat,'_csrf-frontend':'$csrf'};
				//console.debug(data);return;
				$.post('{$url}',data,function(rows){
					//把获取到的对应的城市信息存入到city选项中
					$(rows).each(function(i,v){
						var html = '<option value="'+v.id+'">'+v.name+'</option>';
						$(html).appendTo('#address-city');
					})
				},'json')
			})
			//使用ajax请求php获取地区信息,
			$('#address-city').on('change',function(){
				//$('select[name=province] :not(:first)').remove();
				//在获取城市信息之前先删除原来的地区信息
				$('#address-area :not(:first)').remove();
				//获取传递的城市的id
				var dat=$('#address-city :selected').val();
				var data={pid:dat,'_csrf-frontend':'$csrf'};
				//构造成JSON对象来执行ajax与后端的交互,从而得到地区的详细信息
				$.post('{$url}',data,function(rows){
					$(rows).each(function(i,v){
						var html = '<option value="'+v.id+'">'+v.name+'</option>';
						$(html).appendTo('#address-area');
					})
				},'json')
			})
JS;
$this->registerjs($js);
