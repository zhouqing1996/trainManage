<?php

namespace backend\module\system\controllers;

use yii\web\Controller;
use backend\models\system\FuncModel;
use yii\helpers\ArrayHelper;

class SystemController extends Controller
{
     function header()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
    }
    

    public $modelClass = 'common\models\SysWebUserTb';
    
    // public  function actions()
    // {
    //     $actions = parent::actions();
    //     unset($actions['index']);
    //     return $actions;
    // }
   
    /**
     * @introduction
     * @apiUrl
     * @access_token yes
     * @method       post
     * @return       json       {'msg':null,'data':null}
     * @author Lxin
     * @DateTime     2018-10-09
     * @return       返回一个data对象     [description]
     */
      
     function data()
    {
        // $this->header();
        $modelClass = $this->modelClass;
        $request = \Yii::$app->request;
        $user = $request->post('USER');
        $name = $request->post('NAME');
        $sex = $request->post('SEX');
        $age = $request->post('AGE');
        $cardId = $request->post('CARDID');
        $province = $request->post('PROVINCE');
        $city = $request->post('CITY');
        $school = $request->post('SCHOOL');
        $rank = $request->post('RANK');
        $email = $request->post('EMAIL');


        $type = $request->post('type');
        if(!isset($type))
            $type='SORT_DESC';

        $kind = $request->post('kind');
        if(!isset($kind))
            $kind='id';


        $query = $modelClass::find()->andWhere(['STATUS'=>1])->orderBy([$kind => $type]);
        if(isset($user))
            $query=$query->andWhere(['like','USER',$user]);
        if(isset($name))
            $query=$query->andWhere(['like','NAME',$name]);
        if(isset($sex))
            $query=$query->andWhere(['like','SEX',$sex]);
        if(isset($age))
            $query=$query->andWhere(['like','AGE',$age]);
        if(isset($cardId))
            $query=$query->andWhere(['like','CARDID',$cardId]);
        if(isset($province))
            $query=$query->andWhere(['like','PROVINCE',$province]);
        if(isset($city))
            $query=$query->andWhere(['like','CITY',$city]);
        if(isset($school))
            $query=$query->andWhere(['like','SCHOOL',$school]);
        if(isset($rank))
            $query=$query->andWhere(['like','RANK',$rank]);
        if(isset($email))
            $query=$query->andWhere(['like','EMAIL',$email]);

        return $query;
        
    }
   //查询数据 
     public function actionQuerydata()
    {
        $this->header();
        $_size=15;

        $currentPage=\Yii::$app->request->post('page');
        $currentPage = isset($currentPage)?$currentPage:1;

        $data = $this->data();

        $total = $data->count();     

        $_data = $data->offset($_size*($currentPage-1))->limit($_size)->all(); 

        if ($total%$_size == 0) {
            $totalPage = floor($total/$_size);
        } else {
            $totalPage = floor($total/$_size)+1;
        }     
        $page['total'] = $total;     
        $page['totlepage'] = $totalPage;                  
        $page['pageall'] = $_data;  
        $page['page'] = $currentPage;  
        
        return array("data"=>$page,"msg"=>"success");    
    }

//显示全部数据
    public function actionGetuserdata(){

        $this->header();
        $_size=15;

        $currentPage=\Yii::$app->request->post('page');
        $currentPage = isset($currentPage)?$currentPage:1;

        $data = $this->data();

        $total = $data->count();     

        $_data = $data->offset($_size*($currentPage-1))->limit($_size)->all(); 

        if ($total%$_size == 0) {
            $totalPage = floor($total/$_size);
        } else {
            $totalPage = floor($total/$_size)+1;
        }     
        $page['total'] = $total;     
        $page['totlepage'] = $totalPage;                  
        $page['pageall'] = $_data;  
        $page['page'] = $currentPage;  
        
        return array("data"=>$page,"msg"=>"success");    
    }
   
//删除数据，修改状态为0
     public function actionDeleteuserdata()
    {
        $this->header();
        $request = \Yii::$app->request;
        $user = $request->get('USER');
        $user = explode("?",$user);
        return \Yii::$app->db->createCommand()->update('sys_web_user_tb',array('STATUS' => 0),"USER=:USER",array(':USER'=>$user[0]))->execute();
    }
 //导出excel数据（不分页）
     public function actionExportuserdata(){
        
        $this->header();
        $_size=15;

        $currentPage=\Yii::$app->request->post('page');
        $currentPage = isset($currentPage)?$currentPage:1;

        $data = $this->data();

        $total = $data->count();     

        $_data = $data->all(); 
                 
        $page['pageall'] = $_data;  
        
        return array("data"=>$page,"msg"=>"success");    
    }
}