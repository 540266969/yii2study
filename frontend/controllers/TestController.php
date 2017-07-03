<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19 0019
 * Time: 19:45
 */

namespace frontend\controllers;


use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use frontend\models\Address;
use yii\web\Controller;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class TestController extends Controller
{
    public $layout='index';
    public function actionIndex(){
            $models=GoodsCategory::find()->where('parent_id=0')->all();
            //var_dump($models);exit();
            return $this->render('index',['models'=>$models]);
        }

    public function actionTest(){
// 配置信息
$config = [
    'app_key'    => '24479335',
    'app_secret' => '83281d6db2b0e620765a63555117ee7e',
    // 'sandbox'    => true,  // 是否为沙箱环境，默认false
];


// 使用方法一
$client = new Client(new App($config));
$req    = new AlibabaAliqinFcSmsNumSend;

$req->setRecNum('13540345669')
    ->setSmsParam([
        'content' => rand(100000, 999999)
    ])
    ->setSmsFreeSignName('郭欣')
    ->setSmsTemplateCode('SMS_71535102');

$resp = $client->execute($req);
var_dump($resp);
    }
    public function actionTest1(){
        $code = rand(1000,9999);
        $result = \Yii::$app->msg->setNum(13540345669)->setParam(['content' => $code])->send();
        var_dump($result);
    }
    public function actionTst(){
        $mail=\Yii::$app->mailer->compose()
           ->setFrom('1042851215@qq.com')
           ->setTo('1042851215@qq.com')
            ->setSubject('我的来信')
            ->setHtmlBody('<b style="color:yellow">在我心中,曾经有一个梦,不要让我忘了初衷</b>')
           ->send();
        var_dump($mail);
    }
    public function actionCai(){
        $a=\Yii::$app->request->post('a');
        $b=\Yii::$app->request->post('b');
        $c=\Yii::$app->request->post('c');
        $d=\Yii::$app->request->post('d');
        if(\Yii::$app->cache->get('num')){
            $num=\Yii::$app->cache->get('num');
        }else{
            $item='0123456789';
            $item=str_shuffle($item);
            $num=substr($item,0,4);
            \Yii::$app->cache->set('num',$num);
        }
        $i=0;
        $j=0;
        if($a==$num[0]){
            $i+=1;
        }elseif (strstr($num,$a)){
            $j+=1;
        }
        if($b==$num[1]){
            $i+=1;
        }elseif (strstr($num,$b)){
            $j+=1;
        }
        if($c==$num[2]){
            $i+=1;
        }elseif (strstr($num,$c)){
            $j+=1;
        }
        if($d==$num[3]){
            $i+=1;
        }elseif (strstr($num,$d)){
            $j+=1;
        }
        if($i==4){
            \Yii::$app->cache->delete('num');
            echo '恭喜你猜中';
        }else{
            echo $a.$b.$c.$d.'['.$i.'A'.$j.'B]';
        }
    }
    public function actionView(){
        $this->layout='test';
        return $this->render('view');
    }
    public function actionSphinx(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '小姐姐';//需要搜索的词
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        var_dump($res);
    }
}