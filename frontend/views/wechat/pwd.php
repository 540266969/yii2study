<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>修改密码</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
</head>
<body>
<form method="post">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">原密码：</label></div>
        <div class="weui-cell__bd">
            <input name="old_password" class="weui-input" type="password" placeholder="请输入旧密码">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">新密码：</label></div>
        <div class="weui-cell__bd">
            <input name="new_password" class="weui-input" type="password" placeholder="请输入新密码">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">确认密码：</label></div>
        <div class="weui-cell__bd">
            <input name="re_password" class="weui-input" type="password" placeholder="请再次输入新密码">
        </div>
    </div>
    <div>
        <input type="submit" value="提交" class="weui-btn weui-btn_primary"/>
    </div>
</form>
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
</body>
</html>