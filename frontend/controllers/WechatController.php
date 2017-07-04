<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/3 0003
 * Time: 19:12
 */

namespace frontend\controllers;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;

class WechatController extends Controller
{
    public $enableCsrfValidation=false;
    public $layout=false;
    public function actionIndex(){
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
                    switch ($message->Content){
                        case '成都':
                            $weather=simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                            foreach ($weather as $item){
                                if($item['cityname']=='成都'){
                                    $weather=$item['stateDetailed'];
                                    break;
                                }
                            }
                            return '成都的天气是:'.$weather;
                            break;
                        case '活动':
                            $news = new News([
                                'title'       => '夏日好交友',
                                'description' => '各种美女在线等',
                                'url'         => 'http://www.mm131.com',
                                'image'       => 'http://img2.imgtn.bdimg.com/it/u=2030965026,1272976928&fm=26&gp=0.jpg',
                                // ...
                            ]);
                            $news1 = new News([
                                'title'       => '极品美女',
                                'description' => '美女在线等',
                                'url'         => 'http://www.4399.com',
                                'image'       => 'http://image.tianjimedia.com/uploadImages/2015/285/24/586K2UOWHG9D.jpg',
                                // ...
                            ]);
                            $news2 = new News([
                                'title'       => '超级美女',
                                'description' => '美女在线等',
                                'url'         => 'http://www.5137.com',
                                'image'       => 'http://image.fvideo.cn/uploadfile/2015/05/25/img37533071189339.jpg',
                                // ...
                            ]);
                            return [$news,$news1,$news2];
                            break;
                    }
                     return '收到你的消息'.$message->Content;
                     break;
                     //处理菜单点击事件（点击菜单回复文本信息，点击菜单回复图文信息）
                case 'event':
                    if($message->Event=='CLICK'){
                        if($message->EventKey == 'btgirl'){
                            $news = new News([
                                'title'       => '夏日好交友',
                                'description' => '各种美女在线等',
                                'url'         => 'http://www.mm131.com',
                                'image'       => 'http://img2.imgtn.bdimg.com/it/u=2030965026,1272976928&fm=26&gp=0.jpg',
                                // ...
                            ]);
                            $news1 = new News([
                                'title'       => '极品美女',
                                'description' => '美女在线等',
                                'url'         => 'http://www.4399.com',
                                'image'       => 'http://image.tianjimedia.com/uploadImages/2015/285/24/586K2UOWHG9D.jpg',
                                // ...
                            ]);
                            $news2 = new News([
                                'title'       => '超级美女',
                                'description' => '美女在线等',
                                'url'         => 'http://www.5137.com',
                                'image'       => 'http://image.fvideo.cn/uploadfile/2015/05/25/img37533071189339.jpg',
                                // ...
                            ]);
                            return [$news,$news1,$news2];
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
    public function actionMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "今日美女",
                "key"  => "btgirl"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "登录",
                        "url"  => Url::to(['wechat/login'],true)
                    ],
                    [
                        "type" => "click",
                        "name" => "订单",
                        "url"  => Url::to(['wechat/view'],true)
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        $menus = $menu->all();
        var_dump($menus);
    }
//网页授权
    public function actionMember(){
        if(\Yii::$app->session->get('openid')==null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }

    }
    //网页授权获取openid
    public function actionOpenid(){
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        \Yii::$app->session->set('openid',$user->getId());
        return $this->redirect([\Yii::$app->session->get('redirect')]);
// $user 可以用的方法:
// $user->getId();  // 对应微信的 OPENID
// $user->getNickname(); // 对应微信的 nickname
// $user->getName(); // 对应微信的 nickname
// $user->getAvatar(); // 头像网址
// $user->getOriginal(); // 原始API返回的结果
// $user->getToken(); // access_token， 比如用于地址共享时使用

    }
    public function actionLogin(){
        $openid=\Yii::$app->session->get('openid');
        if ($openid==null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        $request=\Yii::$app->request;
        if ($request->isPost){
            $member=Member::findOne(['username'=>$request->post('username')]);
            if($member==null){
                return '用户名或者密码错误';
            }
            if(\Yii::$app->security->validatePassword($request->post('password'),$member->password_hash)){
                \Yii::$app->user->login($member);
                $member->openid=$openid;
                $member->save();
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
    public function actionOrder(){
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        $member = Member::findOne(['openid'=>$openid]);
        if($member == null){
            //该openid没有绑定任何账户
            //引导用户绑定账户
            return $this->redirect(['wechat/login']);
        }else{
            //已绑定账户
            $orders = Order::findAll(['member_id'=>$member->id]);
            var_dump($orders);
        }
    }
}