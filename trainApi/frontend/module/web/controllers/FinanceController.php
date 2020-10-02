<?php

namespace frontend\module\web\controllers;
use Yii;
use yii\web\Controller;
use common\models\ModulePay;
use common\models\MeetAttenderList;
use common\models\FinanceCertificate;
use common\models\LogisticsAccommodationInfo;

/**
 * Default controller for the `finance` module
 */
class FinanceController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return "财务模块接口";
    }


	public function actionGetMoney()
    {
    	$meetId=Yii::$app->request->post("meetId");
        
    	if(!$meetId){
    		return "查找失败";
    	}

    	$_data = ModulePay::find()->where(['meetId'=>$this->$meetId])->one();

        return array("msg"=>"获取成功","data"=>$_data);
    }

    public function actionPayMoney()
    {
    	//执行缴费
    	//记录到缴费相关的表
        $data=Yii::$app->request->post();
    	$meetId=$data["meetId"];
        $userId=$data["userId"];
        $isAttend=$data["isAttend"];//是否参会，交了参会费为参会
        $isRegister=$data["isRegister"];//是否注册，交了注册费为参会

    	$isAccommodateBySystem=$data["isAccommodateBySystem"];
        $accommodateKind=$data["accommodateKind"];

        $payType=$data["payType"];
        $payItem=$data["payItem"];
        $money=$data["money"];

    	if(!$meetId||!$userId){
    		return "查找失败";
    	}

        $finance=FinanceCertificate::find()->andWhere(['CONFERENCEID'=>$meetId])->andWhere(['USERID'=>$userId])->one();

        if ($finance==null) {
            $finance = new FinanceCertificate();
            $finance->USERID=$userId;
            $finance->CONFERENCEID=$meetId;
        }else{
            if($finance->STATUS==2||$finance->STATUS==3||$finance->STATUS==9)
            return "已经提交交费，请勿重新提交";
        }

        $finance->TYPE=$payType;
        $finance->FEE_ITEM=$payItem;
        $finance->MONEY=$money;
        $finance->CREATE_TIME=date('Y-m-d H:i:s',time());
        $finance->FINISH_TIME=date('Y-m-d H:i:s',time());
        if($payType==1){
            $finance->ORDER_NUMBER=time().mt_rand(1000,9999);
            $finance->STATUS=2;
        }
        if($payType==2){
            $finance->STATUS=9;
        }
        if($payType==3){
            $finance->STATUS=8;
        }
        
        $finance->save();

        //记录是否注册、参会
        $attender=MeetAttenderList::find()->andWhere(['meetId'=>$meetId])->andWhere(['userId'=>$userId])->one();
        if ($attender==null) {
            $attender = new MeetAttenderList();
            $attender->userId=$userId;
            $attender->meetId=$meetId;
        }

        $attender->time=date('Y-m-d H:i:s',time());
        if($isAttend=="true" && $isRegister=="false"){
            $attender->status=1;//只参会
            $attender->save();
        } else if ($isAttend=="false" && $isRegister=="true") {
            $attender->status=2;//只出版
            $attender->save();
        } else if ($isAttend=="true" && $isRegister=="true") {
            $attender->status=3;//参会+出版
            $attender->save();
        }


        if ($isAccommodateBySystem=="true") {
            $accommodate=new LogisticsAccommodationInfo();
            $accommodate->guestId=$userId;
            $accommodate->conferenceId=$meetId;
            $accommodate->type=$accommodateKind;
            $accommodate->status=1;
            $accommodate->save();
        }

        return array("msg"=>"操作成功","data"=>$finance->errors);
    }
}
