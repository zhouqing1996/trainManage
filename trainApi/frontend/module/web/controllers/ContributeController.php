<?php
namespace frontend\module\web\controllers;
require_once(dirname(__FILE__).'/../../phpmailer/src/PHPMailer.php');
require_once(dirname(__FILE__).'/../../phpmailer/src/SMTP.php');
use PHPMailer\PHPMailer;
use Yii;
use yii\web\Controller;
use common\models\MeetAttenderList;
use common\models\MeetContributeList;
use common\models\VwModuleContribute;
/**
 * Default controller for the `finance` module
 */
class ContributeController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    
    //获取投稿信息
    public function actionGetContributeInfo()
    {
        $_now=date("y-m-d",time());
        $_data=VwModuleContribute::find()->all();
     // ->andWhere(['>','endDate',$_now])->andWhere(['<','publishDate',$_now])
        return array("msg"=>"获取投稿信息","data"=>$_data);
    }

    //投稿
    public function actionContribute()
    {

      $_data=Yii::$app->request->post(); 
      $userId = $_data['userId'];
      $meetId = $_data['meetId']; 
      $kind = $_data['kind'];  
      $_meet_data=VwModuleContribute::find()->where(["id"=>$meetId])->one();
      //参会判断时间
      //
      //
      if($kind==1){
        $attender_data=MeetAttenderList::find()->where(["meetId"=>$meetId,"userId"=>$userId])->one();
        if($attender_data!=null){
          return "请勿重复报名！";
        }
        $data=new MeetAttenderList();
        $data->meetId=$meetId;
        $data->userId=$userId;
        $data->time=date('Y-m-d H:i:s',time());
        $data->status=9;
        if($data->save())
          return "报名成功！";
        else
          return "报名失败！";
      }

      //投稿判断时间和是否已经投稿
      //
      //
      $from = $_data['email']; 
      $subject = $_data['subject'];  
      $content = $_data['content'];
      $name = $_data['name'];  


      $_receive=$_meet_data->receive;
      $_email=$_meet_data->email;

      
      $to = $_email;  
      
      if($_FILES){
        $file['fileUrl'] = $_FILES['file']['tmp_name'];    
        $file['fileName'] = $_FILES["file"]["name"]; 
        $return = $this->sendMail($to,'投稿',$content,$name,$file);  
      }else{
        $return = $this->sendMail($to,'投稿',$content,$name,1);
      }  
      $this->sendMail($from,'投稿回复',$_receive,"会议主办方",1);  

      if($return[0]){
        $data_contribute = MeetContributeList::find()->where(["meetId"=>$meetId,"userId"=>$userId])->one();
        if($data_contribute==null)
          $data_contribute=new MeetContributeList();

        $data_contribute->meetId=$meetId;
        $data_contribute->userId=$userId;
        $data_contribute->from=$from;
        $data_contribute->to=$to;
        $data_contribute->title='投稿';
        $data_contribute->subject=$subject;
        $data_contribute->content=$content;
        $data_contribute->file=$return[1];
        $data_contribute->time=date('Y-m-d H:i:s',time());
        $data_contribute->status=1;
        $data_contribute->save();

        $data = MeetAttenderList::find()->where(["meetId"=>$meetId,"userId"=>$userId])->one();
        if($data==null)
          $data_contribute=new MeetAttenderList();
        
        $data=new MeetAttenderList();
        $data->meetId=$meetId;
        $data->userId=$userId;
        $data->time=date('Y-m-d H:i:s',time());
        $data->status=8;
        $data->save();

        return "发送成功！";
      }else{
        return "发送失败！";
      }
    }

    function sendMail($to,$title,$content,$name,$file){
      $mail = new PHPMailer\PHPMailer();
      $mail->SMTPDebug = 1;
      $mail->isSMTP();
      $mail->SMTPAuth=true;
      $mail->Host = 'smtp.qq.com';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;
      $mail->CharSet = 'UTF-8';
      $mail->FromName = $name;
      $mail->Username ='909377773@qq.com';
      $mail->Password = 'fuvllsnbelyabeji';
      $mail->From = '909377773@qq.com';
      $mail->isHTML(true); 
      $mail->addAddress($to,'标题');
      $mail->Subject = $title;
      $mail->Body = $content;
      if ($file != 1) {
        $menu = str_replace('\\','/',dirname(Yii::$app->BasePath));
        $upload_path =$menu.'/'.@backend."/Uploads/contribute/";
        move_uploaded_file($file['fileUrl'], iconv("utf-8","gb2312",$upload_path.$file['fileName']));

        $mail->addAttachment(iconv("utf-8","gb2312",$upload_path.$file['fileName']),iconv("utf-8","gb2312",$file['fileName']));
      }
      $status = $mail->send();
      if($status) {
        $return = [];
        if ($file != 1) {    
          array_push($return,true);
          array_push($return,'/backend/Uploads/contribute/'.$file['fileName']);
          return $return;
        } else {
          array_push($return,true);
          array_push($return,null);
          return $return;
        }
      }else{
        array_push($return,false);
        return $return;
      }
    }

}