<?php
namespace common\models;
require_once(dirname(__FILE__).'/../../frontend/module/phpmailer/src/PHPMailer.php');
require_once(dirname(__FILE__).'/../../frontend/module/phpmailer/src/SMTP.php');
use PHPMailer\PHPMailer;
use Yii;

class SendEmail extends \yii\db\ActiveRecord
{
    public function sendMail($to,$title,$content,$name){
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
      foreach ($to as $key => $value) {
        $mail->addAddress($value,'标题');
      }
      $mail->Subject = $title;
      $mail->Body = $content;
      // if ($file != 1) {
      //   $mail->addAttachment(iconv("utf-8","gb2312",$file['fileUrl']),$file['fileName']);
      // }
      $status = $mail->send();
      if($status) {
        return 1;
      }else{
        return 0;
      }
    }
}
