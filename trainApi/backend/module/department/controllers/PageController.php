<?php
namespace backend\module\finance\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\FinanceCertificate;

class PageController extends Controller
{
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers'=>['x-requested-with','content-type']
                ],
            ]
        ];
    }

    //分页取数据
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $currentPage = $request->get('page');
        $currentType = $request->get('type');
        //根据传值进行日期、金额升降序
        if ($currentType == 1) {
            $type = 'FINISH_TIME DESC';
        } else if ($currentType == 2) {
            $type = 'FINISH_TIME ASC';
        } else if ($currentType == 3) {
            $type = 'MONEY DESC';
        } else if ($currentType == 4) {
            $type = 'MONEY ASC';
        }    
              
        $pages = FinanceCertificate::find()->joinWith('cONFERENCE')->joinWith('uSER')->where(['finance_certificate.STATUS'=>2])->orderBy($type)->offset(5*($currentPage-1))->limit(5)->asArray()->all();    
        $total = FinanceCertificate::find()->count();
        if ($total%5 == 0) {
            $totalPage = floor($total/5);
        } else {
            $totalPage = floor($total/5)+1;
        } 
        $page['pageall'] = $pages;
        $page['total'] = $total;
        $page['totlepage'] = $totalPage;
        $page['currentPage'] = $currentPage;
        return array("data"=>$page,"msg"=>"success");
    }

    //分页取订单数据
    public function actionQuerycertif()
    {
        $request = \Yii::$app->request;
        $currentPage = $request->post('page');
        $total = FinanceCertificate::find()->where(['<>','finance_certificate.STATUS',-2])->count();
        $page['total'] = $total;
        $pages = FinanceCertificate::find()->joinWith('cONFERENCE')->joinWith('uSER')->where(['<>','finance_certificate.STATUS',-2])->orderBy('CREATE_TIME DESC')->offset(5*($currentPage-1))->limit(5)->asArray()->all();
        $page['pageall'] = $pages;
        if ($total%5 == 0) {
            $totalPage = floor($total/5);
        } else {
            $totalPage = floor($total/5)+1;
        }                            
        $page['pageall'] = $pages;
        $page['totlepage'] = $totalPage;
        return array("data"=>$page,"msg"=>"success");
    }

}