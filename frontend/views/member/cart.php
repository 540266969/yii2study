
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>
                    <?php
                    if (Yii::$app->user->isGuest){
                        echo '您好，欢迎来到京西！';
                        echo  \yii\helpers\Html::a('登录',\yii\helpers\Url::to(['member/login']));
                        echo   \yii\helpers\Html::a('注册',\yii\helpers\Url::to(['member/register']));
                    }else{
                        echo '尊敬的用户:<span style="color: red">'.Yii::$app->user->identity->username.'</span>，欢迎来到京西！';
                        echo \yii\helpers\Html::a('注销',\yii\helpers\Url::to(['member/logout']));
                    }
                    ?>
                </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lists as $list):?>
        <tr data-good_id="<?=$list['id']?>">
            <td class="col1"><a href=""><?=\yii\helpers\Html::img($list['logo'])?></a>  <strong><?=\yii\helpers\Html::a($list['name'],\yii\helpers\Url::to(['index/goods','id'=>$list['id']]))?></strong></td>
            <td class="col3">￥<span><?=$list['shop_price']?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$list['amount']?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span><?=$list['shop_price']*$list['amount']?></span></td>
            <td class="col6"><a href="javascript:;" class="goods_del">删除</a></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total">1870.00</span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <?=\yii\helpers\Html::a('结 算',\yii\helpers\Url::to(['member/order-list']),['class'=>'checkout'])?>
    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['member/update-cart']);
$csrf=Yii::$app->request->csrfToken;
$js=<<<JS
    var total=0;
    $('.col5 span').each(function(i,v) {
      total+=parseInt($(this).text());
    })
    $('#total').text(total);
     $('.reduce_num,.add_num').click(function() {
      var amount=$(this).closest('td').find('input').val();
      var goods_id=$(this).closest('tr').attr('data-good_id');
      $.post('$url',{'goods_id':goods_id,'amount':amount,'_csrf-frontend':'$csrf'});
       //console.log(goods_id);
     })
     $('.goods_del').click(function() {
       var goods_id=$(this).closest('tr').attr('data-good_id');
       var amount=0;
       $.post('$url',{'goods_id':goods_id,'amount':amount,'_csrf-frontend':'$csrf'});
        $(this).closest('tr').remove(); 
     })
      
JS;
$this->registerJs($js);

