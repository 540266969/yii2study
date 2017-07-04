<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>WeUI</title>
    <!-- 引入 WeUI -->
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>
</head>
<body>

<?php foreach ($models as $model):?>
    <div class="weui-form-preview">
        <div class="weui-form-preview__hd">
            <label class="weui-form-preview__label">姓名</label>
            <em class="weui-form-preview__value"><?=$model->username?></em>
        </div>
        <div class="weui-form-preview__bd">
            <p>
                <label class="weui-form-preview__label">电话</label>
                <span class="weui-form-preview__value"><?=$model->tel?></span>
            </p>
            <p>
                <label class="weui-form-preview__label">省市县</label>
                <span class="weui-form-preview__value"><?=$model->privaces->name?> <?=$model->cites->name?> <?=$model->areas->name?></span>
            </p>
            <p>
                <label class="weui-form-preview__label">详细地址</label>
                <span class="weui-form-preview__value"><?=$model->detail?></span>
            </p>
        </div>
        <div class="weui-form-preview__ft">
            <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</a>
        </div>
    </div>
<?php endforeach;?>
<div class="weui-cells__tips">底部说明文字底部说明文字</div>
</body>
</html>
