<?php
/**
 * @var $this \yii\web\View
 */
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name');
//echo $from->field($model,'parent_id');
echo $from->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $from->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
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
		         $('#goodscategory-parent_id').val(treeNode.id);
		         }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes ={$categories};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展示全部的子叶节点
        zTreeObj.expandAll(true);
        //注意getNodeByParam和getNodesByParam的区别,他们返回的一个是数组对象,一个是单一的对象
    var node=zTreeObj.getNodeByParam("id",$('#goodscategory-parent_id').val(), null);
        //console.log(node);
        zTreeObj.selectNode(node);
JS;
$this->registerJs($js);
?>
