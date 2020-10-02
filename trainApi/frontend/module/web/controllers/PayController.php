<?php
namespace frontend\module\web\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Url;
use common\udacp\action\PayData;
use common\udacp\action\UdacpAction;
use common\udacp\action\SingleQueryData;
use common\udacp\sdk\AcpService;
/**
 * Site controller
 */
class PayController extends Controller
{                                                                                                     
    /**
     * {@inheritdoc}
     * 若要开启access_token，则打开下authenticator
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                // 'actions' => [
                //     'logout' => ['get'],
                // ],
            ],
            //设置了access_token验证的，需要在请求url上带上access_token
            // 'authenticator' => [ 
            //     'class' => QueryParamAuth::className() ,
            //     'tokenParam' => 'access_token',  //例如改为‘access_token’,get方式请求
            // ],
            // 'corsFilter' => [
            //     'class' => \yii\filters\Cors::className(),
            //     'cors' => [
            //         'Origin' => ['http://phpitem.grouptong.top'],
            //         'Access-Control-Request-Method' => ['*'],
            //         'Access-Control-Allow-Credentials' => true,
            //         'Access-Control-Max-Age' => 3600,
            //         'Access-Control-Request-Headers'=>['x-requested-with','content-type']
            //     ],
            // ], 
        ];
    }
    /**
     * @introduce 1.支付示例
     *            2.本方法需
     *              use common\Udacp\Action\PayData;
                    use common\Udacp\Action\UdacpAction;
     *            3.PayData为支付请求参数封装的类，必须实例化使用，且本函数中包含的set方法在支付时必须都设置
     * @author ZeroC
     * @date      2018-10-29
     * @return    [type]     [description]
     */
    public function actionPay(){
        ini_set('date.timezone','Asia/Shanghai');
        $payData=new PayData();
        $payData->setBackUrl(Url::toRoute('paybackfunction',true));//后端通知url,此url需外网可访问，本地无法测试
        $payData->setOrderId(date('YmdHis'));//订单号
        $payData->setTxnAmt(100);//金额 以分为单位
        $payData->setTxnTime(date('YmdHis'));//订单发送时间
        $payData->setChannelType('07');//渠道选择，07-pc；08-phone
        // $payData->setChannelType('08');//渠道选择，07-pc；08-phone
        // $payData->setOrderDesc('测试');//订单描述,若渠道为08，则必填
        $payData->setPayTimeout(15);//订单有效时长（分钟）
        $payData->setFrontUrl(Url::toRoute('payfontfunction',true));//前端通知url
        /*将订单写入数据库，支付状态为：未支付，要记录订单有效时长！*/
        /**数据库写入操作**********************************************/
        $udacpAction=new UdacpAction();
        $udacpAction->Udacppay($payData);
    }

        /**
     * @introduce 1.此为支付操作后（无论成功与否）响应的url
     *            2.对应$payData->setBackUrl();函数所设值
     *            3.本函数并不在前端显示处理结果，测试可以用写入文本的形式
     *            4.本方法需
     *              use common\Udacp\Action\SingleQueryData;
                    use common\Udacp\Action\UdacpAction;
                    use common\udacp\sdk\AcpService;
     * @author ZeroC
     * @date      2018-10-30
     * @return    [type]     [description]
     */
    /*接收数据样例
        accNo:6216261000000000018
        accessType:0
        bizType:000201
        currencyCode:156
        encoding:UTF-8
        merId:777290058163984
        orderId:20181029155228
        queryId:591810291552281987518
        respCode:00
        respMsg:成功[0000000]
        settleAmt:100
        settleCurrencyCode:156
        settleDate:1029
        signMethod:01
        signPubKeyCert:-----BEGIN CERTIFICATE-----
        MIIEQzCCAyugAwIBAgIFEBJJZVgwDQYJKoZIhvcNAQEFBQAwWDELMAkGA1UEBhMC
        Q04xMDAuBgNVBAoTJ0NoaW5hIEZpbmFuY2lhbCBDZXJ0aWZpY2F0aW9uIEF1dGhv
        cml0eTEXMBUGA1UEAxMOQ0ZDQSBURVNUIE9DQTEwHhcNMTcxMTAxMDcyNDA4WhcN
        MjAxMTAxMDcyNDA4WjB3MQswCQYDVQQGEwJjbjESMBAGA1UEChMJQ0ZDQSBPQ0Ex
        MQ4wDAYDVQQLEwVDVVBSQTEUMBIGA1UECxMLRW50ZXJwcmlzZXMxLjAsBgNVBAMU
        JTA0MUBaMjAxNy0xMS0xQDAwMDQwMDAwOlNJR05AMDAwMDAwMDEwggEiMA0GCSqG
        SIb3DQEBAQUAA4IBDwAwggEKAoIBAQDDIWO6AESrg+34HgbU9mSpgef0sl6avr1d
        bD/IjjZYM63SoQi3CZHZUyoyzBKodRzowJrwXmd+hCmdcIfavdvfwi6x+ptJNp9d
        EtpfEAnJk+4quriQFj1dNiv6uP8ARgn07UMhgdYB7D8aA1j77Yk1ROx7+LFeo7rZ
        Ddde2U1opPxjIqOPqiPno78JMXpFn7LiGPXu75bwY2rYIGEEImnypgiYuW1vo9UO
        G47NMWTnsIdy68FquPSw5FKp5foL825GNX3oJSZui8d2UDkMLBasf06Jz0JKz5AV
        blaI+s24/iCfo8r+6WaCs8e6BDkaijJkR/bvRCQeQpbX3V8WoTLVAgMBAAGjgfQw
        gfEwHwYDVR0jBBgwFoAUz3CdYeudfC6498sCQPcJnf4zdIAwSAYDVR0gBEEwPzA9
        BghggRyG7yoBATAxMC8GCCsGAQUFBwIBFiNodHRwOi8vd3d3LmNmY2EuY29tLmNu
        L3VzL3VzLTE0Lmh0bTA5BgNVHR8EMjAwMC6gLKAqhihodHRwOi8vdWNybC5jZmNh
        LmNvbS5jbi9SU0EvY3JsMjQ4NzIuY3JsMAsGA1UdDwQEAwID6DAdBgNVHQ4EFgQU
        mQQLyuqYjES7qKO+zOkzEbvdFwgwHQYDVR0lBBYwFAYIKwYBBQUHAwIGCCsGAQUF
        BwMEMA0GCSqGSIb3DQEBBQUAA4IBAQAujhBuOcuxA+VzoUH84uoFt5aaBM3vGlpW
        KVMz6BUsLbIpp1ho5h+LaMnxMs6jdXXDh/du8X5SKMaIddiLw7ujZy1LibKy2jYi
        YYfs3tbZ0ffCKQtv78vCgC+IxUUurALY4w58fRLLdu8u8p9jyRFHsQEwSq+W5+bP
        MTh2w7cDd9h+6KoCN6AMI1Ly7MxRIhCbNBL9bzaxF9B5GK86ARY7ixkuDCEl4XCF
        JGxeoye9R46NqZ6AA/k97mJun//gmUjStmb9PUXA59fR5suAB5o/5lBySZ8UXkrI
        pp/iLT8vIl1hNgLh0Ghs7DBSx99I+S3VuUzjHNxL6fGRhlix7Rb8
        -----END CERTIFICATE-----
        traceNo:198751
        traceTime:1029155228
        txnAmt:100
        txnSubType:01
        txnTime:20181029155228
        txnType:01
        version:5.1.0
        signature:pAh07zhDovL2ykMxPhzxVO/BCJxLiWHGbSvKttFANTUT01TEo8vAXI7p55+ibN3+h3dmsaX4EnYJndEpbkJ6b0KeEtJ3xNmMFvFHF0jDMP3hYRlYOKyPaqMHjrxk6jlWuD+R92ram5Fc3/hmF0HnbmO343VsKH85XaVuWF1MFclAoNalO6ArJPFRfmsxzeQwJOdjLr8lPnMEW1eaHdZ0XLrmdVzkhMGK/ccwllHnlMT5dnhi00j7uu1SZ376dNX1SeuibIf4BXgWcQFkHPKyihVMWS7iOC3KS40wX5vCr61LYaFxjuOGKq/Yxz9OKYw53PuJEtJLonCUE0X0xHyfEQ==
        */
    public function actionPaybackfunction(){
        /*此函数需要注意事件处理的一致性，需要的话开启事务操作*/
        /*删除数据库中超时且未支付订单*/
        /**数据库删除操作*********************/
        if(!isset($_POST['signature'])){
            echo '签名丢失';
            exit;
        }
        $validateRes=AcpService::validate($_POST);

        if(!$validateRes){
            echo '签验失败';
            exit;
        }
        //判断respCode=00、A6后，对涉及资金类的交易，请再发起查询接口查询，确定交易成功后更新数据库。
        if(!UdacpAction::checkRespCode($_POST['respCode'])){
            echo '订单未交易成功';
            /*数据库更改订单状态为失败*/
            exit;
        }
        $singleQueryData=new SingleQueryData();
        $singleQueryData->setOrderId($_POST['orderId']);
        $singleQueryData->setTxnTime($_POST['txnTime']);
        $udacpAction=new UdacpAction();
        $queryRes=$udacpAction->singleQueryForResult($singleQueryData);
        $validateQueryRes=AcpService::validate($queryRes);
        if(!$validateQueryRes){
            echo '查询订单签验失败';
            exit;
        }
        if(!UdacpAction::checkRespCode($queryRes['respCode'])){
            echo '订单未交易成功';
            /*数据库更改订单状态为失败*/
            exit;
        }
        /*数据库处理函数*/
        /**数据库修改操作*********************/
    }

}
