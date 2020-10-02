<?php
namespace common\util;

class util
{
	//https://github.com/saviorlv/aliyun-dysms
	public function Sendmsg($signatureArr,$templateCode,$postTelArr,$postContentArr)
    // public function Sendmsg()
    {            
        $response = \Yii::$app->aliyun->sendBatchSms(
            $signatureArr, // 短信签名
            $templateCode, // 短信模板编号
            $postTelArr, // 短信接收者
            $postContentArr,
            "123"
        );

        return $response;
    }

    public function Emails($users,$subject,$contentArr,$fileUrl){
        //发送一封邮件
        // $mailer = \Yii::$app->mailer->compose();
        // $mailer->setFrom($from);
        // $mailer->setTo($to);
        // $mailer->setSubject($subject);
        // $mailer->setHtmlBody($body);
        // $response = $mailer->send();

        //发送多条邮件
        $messages = []; 
        if ($fileUrl) {
            $i=0;
            foreach ($users as $user) {
                $messages[$i]= \Yii::$app->mailer->compose(); 
                $messages[$i]->setTo($user);
                    // ->attach('C:\Users\61449\Desktop\2.pdf')
                $messages[$i]->setSubject($subject) ;
                $messages[$i]->setHtmlBody($contentArr[$i]); 
                foreach ($fileUrl as $key => $value) {
                    $messages[$i]->attach($value);
                }
                $i++;
            } 
        }else{
            $i=0;
            foreach ($users as $user) {
                $messages[] = \Yii::$app->mailer->compose() 
                    ->setTo($user) 
                    ->setSubject($subject) 
                    ->setHtmlBody($contentArr[$i]); 
                $i++;
            } 
        }
        $response=\Yii::$app->mailer->sendMultiple($messages);
        return $response;
    }
}
