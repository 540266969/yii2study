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
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>
<form action="<?=\yii\helpers\Url::to(['member/order'])?>" method="post">
<!-- 主体部分 start -->
    <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>">
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addresses as $address):?>
                <p>
                    <input type="radio" value="<?=$address->id?>" name="address_id"/><?=$address->username?> <?=$address->privaces->name?> <?=$address->cites->name?> <?=$address->areas->name?> <?=$address->detail?> <?=$address->tel?></p>
                <?php endforeach;?>
            </div>

        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $deliveries=\frontend\models\Order::$delivery;
                    foreach ($deliveries as $delivery):
                    ?>
                    <tr>
                        <td>
                            <input type="radio" name="delivery_id" checked="checked" value="<?=$delivery['delivery_id']?>" /><?=$delivery['delivery_name']?>
                        </td>
                        <td>￥<?=$delivery['delivery_price']?></td>
                        <td><?=$delivery['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php $payments=\frontend\models\Order::$payment;
                        foreach ($payments as $payment):
                    ?>
                    <tr>
                        <td class="col1"><input type="radio" name="payment_id" value="<?=$payment['payment_id']?>"/><?=$payment['payment_name']?></td>
                        <td class="col2"><?=$payment['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($lists as $list):?>
                <tr>
                    <td class="col1"><?=\yii\helpers\Html::img($list['logo'])?></a>  <strong><a href="">&nbsp;&nbsp;&nbsp;<?=$list['name']?></a></strong></td>
                    <td class="col3">￥<?=$list['shop_price']?></td>
                    <td class="col4"><?=$list['amount']?></td>
                    <td class="col5"><span><?=$list['amount']*$list['shop_price']?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span id="total_goods">4 件商品，总商品金额：</span>
                                <em id="total_money">￥5316.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="delevery_price">￥10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="pay_money">￥5076.00</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <p>应付总额：<strong id="pay_needed">￥5076.00元</strong></p>
        <input type="submit"  value=""  style="float: right; display: inline;width:150px; height:30px;background-image:url(<?=\Yii::getAlias('@web/images/order_btn.jpg')?>);vertical-align: middle; margin: 7px 10px 0"/>
    </div>
</div>
</form>
<!-- 主体部分 end -->
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/style/fillin.css');
$js=<<<JS
    var total=0;
    $('.col4:not(:first)').each(function(i,v) {
      total+=parseInt($(this).text());
    })
    $('#total_goods').text(total+'件商品，总商品金额:');
    var money=0;
    $('.col5:not(:first)').find('span').each(function() {
      money+=parseFloat($(this).text(),2);
    })
    $('#total_money').text('￥'+money);
    $('.delivery_select input').click(function() {
       var delivery=$(this).closest('tr').find('td:eq(1)').text();
        $('#delevery_price').text(delivery);
            var delivery_money=parseFloat($('#delevery_price').text().substr(1),2);
    var pay_money=delivery_money+money;
    $('#pay_money,#pay_needed').text('￥'+pay_money);
    $('#total_money').val(pay_money);
    })
JS;
$this->registerJs($js);