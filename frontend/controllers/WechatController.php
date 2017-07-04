<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/3 0003
 * Time: 19:12
 */

namespace frontend\controllers;


use backend\models\Goods;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class WechatController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout = false;

    public function actionIndex()
    {
        //echo 'success';
        //echo  $_GET['echostr'];
        //* 消息回复，回复普通文本消息，回复多图文消息
        $app = new Application(\Yii::$app->params['wechat']);
        $server = $app->server;
        //echo '111';
        $server->setMessageHandler(function ($message) {
            //return '收到你的消息';
            switch ($message->MsgType) {
                case 'text':
                    switch ($message->Content) {
                        case '解除绑定':
                            $openid = $message->FromUserName;
                            $member = Member::findOne(['openid' => $openid]);
                            //return  $openid;
                            if ($member == null) {
                                $app = new Application(\Yii::$app->params['wechat']);
                                $url = $app->url;
                                $shortUrl = $url->shorten(Url::to(['wechat/login'],true));
                                $shortUrl=Json::decode($shortUrl,true);
                                return '您还没有绑定帐号,请先绑定,绑定网址为:'.$shortUrl['short_url'];
                            } else {
                                $member->openid = null;
                                $member->save();
                                return '解除绑定成功';
                            }
                            break;
                        case '帮助':
                            return '您可以发送 优惠、解除绑定 等信息';
                            break;
                        case '优惠':
                            $models = Goods::find()->limit(5)->all();
                            $lists = [];
                            foreach ($models as $model) {
                                $news = new News([
                                    'title' => $model->name,
                                    'description' => $model->shop_price,
                                    'url' => Url::to(['index/goods', 'id' => $model->id], true),
                                    'image' => $model->logo,
                                    // ...
                                ]);
                                $lists[] = $news;
                            }
                            return $lists;
                            break;
                    }
                    return '收到你的消息' . $message->Content;
                    break;
                //处理菜单点击事件（点击菜单回复文本信息，点击菜单回复图文信息）
                case 'event':
                    if ($message->Event == 'CLICK') {
                        if ($message->EventKey == 'pgoods') {
                            $models = Goods::find()->limit(5)->all();
                            $lists = [];
                            foreach ($models as $model) {
                                $news = new News([
                                    'title' => $model->name,
                                    'description' => $model->shop_price,
                                    'url' => Url::to(['index/goods', 'id' => $model->id], true),
                                    'image' => $model->logo,
                                    // ...
                                ]);
                                $lists[] = $news;
                            }
                            return $lists;
                            break;
                        }
                    }
                    break;

            }
            // ...
        });
        $response = $server->serve();
        $response->send(); // Laravel 里请使用：return $response;
    }

    //设置菜单 （view click）
    public function actionMenu()
    {
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "促销商品",
                "key" => "pgoods"
            ],
            [
                "type" => "view",
                "name" => "在线商城",
                "url" => Url::to(['index/index'], true)
            ],
            [
                "name" => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url" => Url::to(['wechat/login'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url" => Url::to(['wechat/order'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "收货地址",
                        "url" => Url::to(['wechat/address'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "修改密码",
                        "url" => Url::to(['wechat/pwd'], true)
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        $menus = $menu->all();
        var_dump($menus);
    }

//网页授权
    public function actionMember()
    {
        if (\Yii::$app->session->get('openid') == null) {
            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }

    }

    //网页授权获取openid
    public function actionOpenid()
    {
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        \Yii::$app->session->set('openid', $user->getId());
        return $this->redirect([\Yii::$app->session->get('redirect')]);
// $user 可以用的方法:
// $user->getId();  // 对应微信的 OPENID
// $user->getNickname(); // 对应微信的 nickname
// $user->getName(); // 对应微信的 nickname
// $user->getAvatar(); // 头像网址
// $user->getOriginal(); // 原始API返回的结果
// $user->getToken(); // access_token， 比如用于地址共享时使用

    }
    //用户登录
    public function actionLogin()
    {
        $openid = \Yii::$app->session->get('openid');
        $this->actionMember();
//        if ($openid == null) {
//            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
//            $app = new Application(\Yii::$app->params['wechat']);
//            $response = $app->oauth->scopes(['snsapi_base'])
//                ->redirect();
//            $response->send();
//        }
        $request = \Yii::$app->request;
        $mem=Member::findOne(['openid'=>$openid]);
        if($mem!=null){
            return '你已经绑定过啦,无需进行该操作';
        }
        if ($request->isPost) {
            $member = Member::findOne(['username' => $request->post('username')]);
            if ($member == null) {
                return '用户名或者密码错误';
            }
            if (\Yii::$app->security->validatePassword($request->post('password'), $member->password_hash)) {
                \Yii::$app->user->login($member);
                $member->openid = $openid;
                $member->save();
                return $this->redirect(\Yii::$app->session->get('redirect'));
            }
        }
        return $this->renderPartial('login');
    }

    /*

  * 设置菜单 （view click）
     *
  * 处理菜单点击事件（点击菜单回复文本信息，点击菜单回复图文信息）
  * 网页授权获取openid
  * 绑定账户
  * 获取用户的订单
  */
    //获取订单
    public function actionOrder()
    {
        $openid = \Yii::$app->session->get('openid');
        $this->actionMember();
//        if ($openid == null) {
//            //获取用户的基本信息（openid），需要通过微信网页授权
//            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
//            //echo 'wechat-user';
//            $app = new Application(\Yii::$app->params['wechat']);
//            //发起网页授权
//            $response = $app->oauth->scopes(['snsapi_base'])
//                ->redirect();
//            $response->send();
//        }
        $member = Member::findOne(['openid' => $openid]);
        \Yii::$app->session->remove('redirect');
        if ($member == null) {
            //该openid没有绑定任何账户
            //引导用户绑定账户
            \Yii::$app->session->set('redirect', ['wechat/order']);
            return $this->redirect(['wechat/login']);
        } else {
            //已绑定账户
            $orders = Order::findAll(['member_id' => $member->id]);
            return $this->render('order', ['orders' => $orders]);
            //var_dump($orders);
        }
    }
    //获取收货地址
    public function actionAddress()
    {
        $openid = \Yii::$app->session->get('openid');
        $this->actionMember();
//        if ($openid == null) {
//            //获取用户的基本信息（openid），需要通过微信网页授权
//            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
//            //echo 'wechat-user';
//            $app = new Application(\Yii::$app->params['wechat']);
//            //发起网页授权
//            $response = $app->oauth->scopes(['snsapi_base'])
//                ->redirect();
//            $response->send();
//        }
        $member = Member::findOne(['openid' => $openid]);
        \Yii::$app->session->remove('redirect');
        if ($member == null) {
            //该openid没有绑定任何账户
            //引导用户绑定账户
            \Yii::$app->session->set('redirect', ['wechat/address']);
            return $this->redirect(['wechat/login']);
        }
        $member_id=$member->id;
        $models=Address::findAll(['member_id'=>$member_id]);
        return $this->render('address',['models'=>$models]);
    }
    //修改密码
    public function actionPwd(){
        $openid = \Yii::$app->session->get('openid');
        $this->actionMember();
//        if ($openid == null) {
//            //获取用户的基本信息（openid），需要通过微信网页授权
//            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
//            //echo 'wechat-user';
//            $app = new Application(\Yii::$app->params['wechat']);
//            //发起网页授权
//            $response = $app->oauth->scopes(['snsapi_base'])
//                ->redirect();
//            $response->send();
//        }
        $member = Member::findOne(['openid' => $openid]);
        \Yii::$app->session->remove('redirect');
        if ($member == null) {
            //该openid没有绑定任何账户
            //引导用户绑定账户
            \Yii::$app->session->set('redirect', \Yii::$app->controller->action->uniqueId);
            return $this->redirect(['wechat/login']);
        }
        $reques=\Yii::$app->request;
        if($reques->isPost){
           if(!\Yii::$app->security->validatePassword($reques->post('old_password'),$member->password_hash)){
               return '旧密码错误';
           }
           if($reques->post('new_password')!=$reques->post('re_password')){
               return '两次密码不一致';
           }
           $member->password_hash=\Yii::$app->security->generatePasswordHash($reques->post('new_password'));
           $member->save();
           return '修改成功';
        }
        return $this->render('pwd');
    }
}