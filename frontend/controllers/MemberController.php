<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 11:34
 */

namespace frontend\controllers;


use backend\models\Goods;
use Faker\Provider\da_DK\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\MemberFrom;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\PwdFrom;
use yii\db\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class MemberController extends Controller
{
    public $layout = 'login';
    //用户注册页面
    public function actionRegister()
    {
        $model = new Member();
        $model->setScenario('add');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->auth_key = \Yii::$app->security->generateRandomString();
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
            $model->created_at = time();
            $model->save(false);
            \Yii::$app->session->setFlash('success', '保存成功');
            return $this->refresh();
        }
        //if($model->load())
        return $this->render('register', ['model' => $model]);
    }
    //测试模块,没啥子用 可以注释掉
    public function actionTest()
    {
        $user = \Yii::$app->user->identity;
        var_dump($user);
        //return $this->render('test');
    }
    //用户登录
    public function actionLogin()
    {
        $model = new MemberFrom();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            \Yii::$app->session->setFlash('success', '设置成功');
            //var_dump(\Yii::$app->request->getReferrer());exit;
            //return $this->redirect(\Yii::$app->request->getReferrer());
            return $this->redirect(['index/index']);
        }
        return $this->render('login', ['model' => $model]);
    }
    //注销登录
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
    //发送手机短信验证码的功能
    public function actionMsg()
    {
        $tel = \Yii::$app->request->post('tel');
        if (!preg_match('/^1[3578]\d{9}$/', $tel)) {
            echo '请输入有效的手机号码';
            exit;
        }
        $code = mt_rand(100000, 999999);
        $msg = \Yii::$app->msg->setNum($tel)->setParam(['content' => $code])->send();
        if ($msg) {
            \Yii::$app->cache->set('tel' . $tel, $code);
            echo 'success';
        } else {
            echo '短信发送失败';
        }
    }
    //用过邮箱发起找回密码页面
    public function actionFindPwd()
    {
        $model = new PwdFrom();
        $model->setScenario('find-pwd');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->match()) {
                $url = $model->match();
                $mail = \Yii::$app->mailer->compose()
                    ->setFrom('1042851215@qq.com')
                    ->setTo($model->email)
                    ->setSubject('修改密码')
                    ->setHtmlBody("<b style='color:red'>请点击下方连接修改您的密码:$url</b>")
                    ->send();
                if ($mail) {
                    echo '请登录邮箱修改您的密码';
                    exit;
                } else {
                    echo '邮件发送失败';
                    exit;
                }

            }
        }
        return $this->render('find', ['model' => $model]);
    }
    //修改用户密码页面;
    public function actionEditPwd()
    {
        $username = \Yii::$app->request->get('username');
        $password_reset_token = \Yii::$app->request->get('password_reset_token');
        $model = Member::findOne(['username' => $username]);
        $model->setScenario('edit-pwd');
        if (!$model || $model->password_reset_token != $password_reset_token) {
            throw new NotFoundHttpException('访问的页面不存在', '404');
        }
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
            $model->updated_at = time();
            $model->password_reset_token = null;
            $model->save(false);
            \Yii::$app->session->setFlash('success', '密码修改成功,请登录');
            return $this->redirect(['member/login']);
        }

        return $this->render('edit-pwd', ['model' => $model]);
    }
    //添加商品到购物车
    public function actionAddGoods()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('你浏览的商品页面不存在');
        }
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie) {
                $data = unserialize($cookie);
            } else {
                $data = [];
            }
            if (array_key_exists($goods_id, $data)) {
                $data[$goods_id] += $amount;
            } else {
                $data[$goods_id] = $amount;
            }
            $cookie = new Cookie(['name' => 'cart', 'value' => serialize($data)]);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        } else {
            $items = Cart::findOne(['goods_id' => $goods_id]);
            if ($items) {
                $items->amount = $items->amount + $amount;
                $items->save();
            } else {
                $model = new Cart();
                $model->member_id = \Yii::$app->user->id;
                $model->amount = $amount;
                $model->goods_id = $goods_id;
                $model->save();
            }
        }
        return $this->redirect(['member/cart']);
    }
    //显示购物车列表
    public function actionCart()
    {
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $data = unserialize($cookies->get('cart'));
            //var_dump($data);exit();
            $lists = [];
            foreach ($data as $k => $v) {
                $list = Goods::find()->where(['id' => $k])->asArray()->one();
                $list['amount'] = $v;
                $lists[] = $list;
            }
        } else {
            $member_id = \Yii::$app->user->id;
            $models = Cart::find()->where(['member_id' => $member_id])->all();
            $lists = [];
            foreach ($models as $v) {
                $list = Goods::find()->where(['id' => $v->goods_id])->asArray()->one();
                $list['amount'] = $v->amount;
                $lists[] = $list;
            }
        }
        //var_dump($lists);exit;
        $this->layout = 'cart';
        return $this->render('cart', ['lists' => $lists]);
    }
    //修改购物车数据
    public function actionUpdateCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('你浏览的商品页面不存在');
        }
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie) {
                $data = unserialize($cookie);
            } else {
                $data = [];
            }
            if ($amount) {
                $data[$goods_id] = $amount;
            } else {
                unset($data[$goods_id]);
            }
            $cookie = new Cookie(['name' => 'cart', 'value' => serialize($data)]);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        } else {
            $model = Cart::findOne(['goods_id' => $goods_id]);
            if ($amount) {
                $model->amount = $amount;
                $model->save();
            } else {
                $model->delete();
            }
        }
        return $this->redirect(['member/cart']);
    }
    //订单列表页面,首先判断用户是否登录,没登陆跳转到登录页面
    public function actionOrderList()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['member/login']);
        } else {
            $id = \Yii::$app->user->id;
        }
        $this->layout = 'cart';
        //获取到用户的全部收货地址
        $addresses = \frontend\models\Address::findAll(['member_id' => $id]);
        //获取用户选择的商品信息并且保存每种商品的数量 返回一个二维数组进行处理
        $models = Cart::find()->where(['member_id' => $id])->all();
        $lists = [];
        foreach ($models as $v) {
            $list = Goods::find()->where(['id' => $v->goods_id])->asArray()->one();
            $list['amount'] = $v->amount;
            $lists[] = $list;
        }
        return $this->render('order', ['addresses' => $addresses, 'lists' => $lists]);
    }
    //用户订单提交后的处理 需要接收 收货地址id,支付方式id,送货方式id;
    public function actionOrder(){
        //var_dump(\Yii::$app->request->post());exit();
        $delivery_id=\Yii::$app->request->post('delivery_id',0)-0;
        $payment_id=\Yii::$app->request->post('payment_id',0)-0;
        $address_id=\Yii::$app->request->post('address_id',0)-0;
        if(!$payment_id||!$delivery_id||!$address_id){
            \Yii::$app->session->setFlash('请确认选项');
            return $this->redirect(['member/order-list']);
        }
        $trasaction=\Yii::$app->db->beginTransaction();
        try{
            $member_id=\Yii::$app->user->id;
            $address=\frontend\models\Address::findOne(['id'=>$address_id]);
            $carts = Cart::find()->where(['member_id' => $member_id])->all();
            $total=0;
            foreach ($carts as $cart){
                $price=Goods::find()->where(['id'=>$cart->goods_id])->one()->shop_price;
                $total+=($cart->amount*$price);
            }
            $order =new Order();
            $order->member_id=$member_id;
            $order->name=$address->username;
            $order->province=$address->privaces->name;
            $order->city=$address->cites->name;
            $order->area=$address->areas->name;
            $order->address=$address->detail;
            $order->tel=$address->tel;
            $order->delivery_id=$delivery_id;
            $order->delivery_name=Order::$delivery[$delivery_id]['delivery_name'];
            $order->delivery_price=Order::$delivery[$delivery_id]['delivery_price'];
            $order->payment_id=$payment_id;
            $order->payment_name=Order::$payment[$payment_id]['payment_name'];
            $order->total=$total+Order::$delivery[$delivery_id]['delivery_price'];
            $order->status=1;
            $order->trade_no='';
            $order->create_time=time();
            $order->save(false);
//            var_dump($order->save());
//            var_dump($order->getErrors());
//            exit;
            foreach ($carts as $cart){
                $goods=Goods::findOne(['id'=>$cart->goods_id]);
                $order_goods=new OrderGoods();
                $order_goods->order_id=$order->id;
                $order_goods->goods_id=$goods->id;
                $order_goods->goods_name=$goods->name;
                $order_goods->logo=$goods->logo;
                $order_goods->price=$goods->shop_price;
                $order_goods->amount=$cart->amount;
                $order_goods->total=($goods->shop_price)*($cart->amount);
                $order_goods->save(false);
                $goods->stock=$goods->stock-$cart->amount;
                if($goods->stock<0){
                    throw new Exception('库存不足');
                }
                $goods->save(false);
                $cart->delete();
            }
            $trasaction->commit();
            return $this->redirect(['member/success']);
        }catch (Exception $e){
            var_dump($e->getMessage());
            $trasaction->rollBack();
            return $this->redirect(['member/order-list']);
        }

    }
    public function actionSuccess(){
        $this->layout = 'cart';
        return $this->render('success');
    }
}