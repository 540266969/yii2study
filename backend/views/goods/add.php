<?php
use yii\web\JsExpression;
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
echo $from->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo,['id'=>'logos','height'=>50]);
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
        $('#goods-logo').val(data.fileUrl);
        
        
        
    }
}
EOF
        ),
    ]
]);
echo $from->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $from->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brand,'id','name'));
echo $from->field($model,'market_price');
echo $from->field($model,'shop_price');
echo $from->field($model,'stock');
echo $from->field($model,'is_no_sale')->radioList([0=>'下架',1=>'在售']);
echo $from->field($model,'status')->radioList([0=>'回收站',1=>'正常']);
echo $from->field($model,'sort');
echo $from->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);
//echo $from->field($goods_intro,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$js=<<<JS
    var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        //使用回调函数的形式,找到其选择的条目的Id,作为其parent_id,放入到隐藏域中
        callback: {
		    onClick:function(event, treeId, treeNode){
		        //console.log(treeNode.id);
		         $('#goods-goods_category_id').val(treeNode.id);
		         }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes ={$categories};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展示全部的子叶节点
        zTreeObj.expandAll(true);
        //注意getNodeByParam和getNodesByParam的区别,他们返回的一个是数组对象,一个是单一的对象
    var node=zTreeObj.getNodeByParam("id",$('#goods-goods_category_id').val(), null);
        //console.log(node);
        zTreeObj.selectNode(node);
JS;
$this->registerJs($js);