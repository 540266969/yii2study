<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $stutu=[0=>'已取消',1=>'待付款',2=>'待发货',3=>'待收货',4=>'完成'];
    public function getMember(){
        return $this->hasOne(Member::className(),['member_id'=>'id']);
    }
    public static $delivery=[
        1=>['delivery_id'=>1,'delivery_name'=>'京西配送','delivery_price'=>0,'detail'=>'预计明天到达,限城区使用'],
        2=>['delivery_id'=>2,'delivery_name'=>'顺丰超快','delivery_price'=>20,'detail'=>'上午下单,下午到达'],
        3=>['delivery_id'=>3,'delivery_name'=>'邮政送货','delivery_price'=>10,'detail'=>'全国各地都送,速度你就不要期望了'],
    ];
    public static $payment=[
       1=>['payment_id'=>1,'payment_name'=>'货到付款','detail'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
       2=>['payment_id'=>2,'payment_name'=>'在线付款','detail'=>'	即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
       3=>['payment_id'=>3,'payment_name'=>'微信支付','detail'=>'扫一扫即可付款'],
       4=>['payment_id'=>4,'payment_name'=>'支付宝支付','detail'=>'支付送双色球一注,500w不是梦'],
    ];
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'name', 'province', 'city', 'area', 'address', 'tel', 'delivery_id', 'delivery_name', 'payment_id', 'payment_name', 'total', 'status'], 'required'],
            [['member_id', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name', 'province', 'city', 'area'], 'string', 'max' => 30],
            [['address', 'payment_name'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
            [['delivery_name'], 'string', 'max' => 50],
            [['trade_no'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户Id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '手机号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方订单号',
            'create_time' => '创建时间',
        ];
    }
}
