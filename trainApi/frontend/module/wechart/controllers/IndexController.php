<?php

namespace frontend\module\wechart\controllers;

use Yii;
use yii\web\Controller;
use common\util\WeChatMessage;
use common\models\User;
use common\models\ExpertUserInfo;
use yii\filters\VerbFilter;
use yii\filters\auth\QueryParamAuth;
/**
 * Default controller for the `finance` module
 */
class IndexController extends Controller
{
    private $_webUsers;
    private $_expertUsers;
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
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers'=>['x-requested-with','content-type']
                ],
            ], 
        ];
    }

    /**
     * 对接接口
     * @return string
     */
    public function actionIndex(){
        $WeChatMessage=new WeChatMessage();
        // $WeChatMessage->valid();
        $WeChatMessage->action();
    }

    /**
     * @introduce 获取accesstoken
     * @author ZeroC
     * @date      2018-11-02
     * @return    [type]     [description]
     */
    public function actionGetaccesstoken(){
        $WeChatMessage=new WeChatMessage();
        return $WeChatMessage->showAccessToken();
    }

    /**
     * @introduce openid与用户信息绑定
     * @author ZeroC
     * @date      2018-11-02
     * @return    [type]     [description]
     */
    public function actionBinduser(){
        $post=Yii::$app->request->post();
        $params=array('openid','username','password');
        foreach ($params as $value) {
            if(!isset($post[$value])){
                return array("msg"=>"400","data"=>"请退出后重试");
            }
        }
        
        $userInfo=User::findOne(['username'=>$post['username']]);
        if(empty($userInfo)){
            return array("msg"=>"402","data"=>'用户不存在');
        }
        if($userInfo['openid']==$post['openid']){
            return array("msg"=>"406","data"=>'无需重复绑定');
        }
        if(!Yii::$app->security->validatePassword($post['password'], $userInfo['password_hash'])){
            return array("msg"=>"405","data"=>'密码错误');
        }
        $userInfo->openid=$post['openid'];
        
        if(!$userInfo->save()){
            array("msg"=>"403","data"=>"绑定失败");
        }
        $webUsersInfo=User::findByUsername($post['username'])->webUsers;
        if(!$webUsersInfo){
            array("msg"=>"404","data"=>"请退出后重试");
        }
        $returnInfo=array(
            'user' => $userInfo->username, 
            'name'=>$webUsersInfo->NAME,
            'cardId'=>$webUsersInfo->CARDID,
            'site'=>$webUsersInfo->SCHOOL,
            'sex'=>$webUsersInfo->SEX,
            'age'=>$webUsersInfo->AGE,
            'province'=>$webUsersInfo->PROVINCE,
            'city'=>$webUsersInfo->CITY,
            'access_token'=>$userInfo->access_token,
            "kind"=>$userInfo->kind
        );
        return array("msg"=>"200","data"=>$returnInfo);
    }

    public function actionUnbinduser(){
        $post=Yii::$app->request->post();
        $params=array('username');
        foreach ($params as $value) {
            if(!isset($post[$value])){
                return array("msg"=>"400","data"=>"请退出后重试");
            }
        }
        $userInfo=User::findOne(['username'=>$post['username']]);
        $userInfo->access_token="";
        $userInfo->openid="";
        if(!$userInfo->save())
            return array("msg"=>"401","data"=>'操作失败');
        return array("msg"=>"200","data"=>'操作成功');
    }
    /**
     * @introduce 获取accesstoken
     * @author ZeroC
     * @date      2018-11-02
     * @return    [type]     [description]
     */
    public function actionGetappaccesstoken(){
        $code=Yii::$app->request->post('code');
        if(empty($code)){
            return array("msg"=>"400","data"=>"参数错误");
        }
        $WeChatMessage=new WeChatMessage();
        $openidInfo=$WeChatMessage->getOpenid($code);
        $openid=isset($openidInfo['openid'])?$openidInfo['openid']:null;
        if($openid==null){
            return  array("msg"=>$openid,"data"=>"获取用户信息失败");
        }
        $userInfo=User::findOne(['openid'=>$openid]);
        if(empty($userInfo)){
            return  array("msg"=>"402","data"=>$openid);
        }

        $userInfo->access_token=Yii::$app->security->generateRandomString() . '_' . time();
        $userInfo->save();

        // $webUsersInfo=User::findByUsername($userInfo['username'])->webUsers;
        // if(!$webUsersInfo){
        //     array("msg"=>"404","data"=>"请退出后重试");
        // }

        if($userInfo->kind==8){
            $this->_expertUsers=ExpertUserInfo::find()->where(["USER"=>$userInfo['username']])->one();
            if(!$this->_expertUsers){
                array("msg"=>"404","data"=>"请退出后重试");
            }
            $returnInfo=array(
                'user' => $userInfo->username, 
                'name'=>$this->_expertUsers->NAME,
                'sex'=>$this->_expertUsers->SEX,
                'title'=>$this->_expertUsers->TITLE,
                'field'=>$this->_expertUsers->FIELD,
                'introduction'=>$this->_expertUsers->INTRODUCTION,
                'department'=>$this->_expertUsers->DEPARTMENT,
                'job'=>$this->_expertUsers->JOB,
                'phone'=>$this->_expertUsers->PHONE,
                'email'=>$this->_expertUsers->EMAIL,
                'access_token'=>$userInfo->access_token,
                "kind"=>$userInfo->kind
            );
        }

        if($userInfo->kind==9){
            $this->_webUsers=User::findByUsername($userInfo['username'])->webUsers;
            if(!$this->_webUsers){
                array("msg"=>"404","data"=>"请退出后重试");
            }
            $returnInfo=array(
                'user' => $userInfo->username, 
                'name'=>$this->_webUsers->NAME,
                'cardId'=>$this->_webUsers->CARDID,
                'site'=>$this->_webUsers->SCHOOL,
                'sex'=>$this->_webUsers->SEX,
                'age'=>$this->_webUsers->AGE,
                'province'=>$this->_webUsers->PROVINCE,
                'city'=>$this->_webUsers->CITY,
                'email'=>$this->_webUsers->EMAIL,
                'rank'=>$this->_webUsers->RANK,
                'access_token'=>$userInfo->access_token,
                "kind"=>$userInfo->kind
            );
        }
        
        return array("msg"=>"200","data"=>$returnInfo);
    }
}
