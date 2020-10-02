<?php
namespace common\util;

define("APPID","wxbf9ff53835b6de15");//填写自己的APPID
define("APPSECRET","c3464b067029c309d4105383e9789b82");//填写自己的APPSECRET
define("TOKEN", "token");//token随便填，只要一致就行。

class WeChatMessage{
    private $_appid;
    private $_appsecret;
    private $_token;
    public function __construct(){
        $this->_appid =APPID;
        $this->_appsecret =APPSECRET;
        $this->_token =TOKEN;
    }
    /**
      *接入微信平台时验证
    **/
    public function valid()//检查安全性
    {
        $echoStr = $_GET["echostr"];
  
        //valid signature , option
        if($this->checkSignature()){//检查签名是否一致
            echo $echoStr;//验证成功后，输出
            exit;
        }
    }
    /**
      *验证签名
    **/
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
          
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function action(){
    /*获得请求时POST:XML字符串不能用$_POST获取，因为没有key*/
        $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];

        if(empty($xml_str)){
            die('');
        }
        if(!empty($xml_str)){
            // 解析该xml字符串，利用simpleXML
            libxml_disable_entity_loader(true);
            //禁止xml实体解析，防止xml注入
              $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            //判断该消息的类型，通过元素MsgType
            switch ($request_xml->MsgType){
                case 'event':
                    //判断具体的时间类型（关注、取消、点击）
                    $event = $request_xml->Event;
                    $Ticket = $request_xml->Ticket;
                      if ($event=='subscribe'&&$Ticket=='') { // 普通关注事件
                          $this->_doSubscribe($request_xml);
                      }
                      if ($event=='subscribe'&&$Ticket!='') {//扫描渠道二维码关注
                          $this->_doQRSubscribe($request_xml);
                      }
                      if ($event=='SCAN'&&$Ticket!='') {//已关注公众号扫描渠道二维码事件
                          $this->_doSCAN($request_xml);
                      }
                      break;
                case 'text'://文本消息
                    $this->_doText($request_xml);
                    break;
                case 'image'://图片消息
                    $this->_doImage($request_xml);
                    break;
                case 'voice'://语音消息
                    $this->_doVoice($request_xml);
                    break;
                case 'video'://视频消息
                    $this->_doVideo($request_xml);
                    break;
                case 'shortvideo'://短视频消息
                    $this->_doShortvideo($request_xml);
                    break;
                case 'location'://位置消息
                    $this->_doLocation($request_xml);
                    break;
                case 'link'://链接消息
                    $this->_doLink($request_xml);
                    break;
            }        
        }  
    }

/*平台业务函数*******************************************************************/
  /**
   * 普通关注事件
   * @Author   ZeroC
   * @DateTime 2018-02-02T10:10:20+0800
   * @Function
   * @param    [type]                   $request_xml [description]
   * @return   [type]                                [description]
   */
  function _doSubscribe($request_xml){        
      $userOpenId=(string)$request_xml->FromUserName;
      $touser=$request_xml->FromUserName;
      // $userInfo=SysWebUserTb::where(['WECHART'=>$userOpenId])->find();
      // if(!$userInfo['ID']){
      //     $WeChatUserInfo=$this->getWeChatUserInfo($touser);
      //     $openId = $request_xml->FromUserName;
      //     // $data['openId'] = $request_xml->FromUserName;
      //     // $data['nickName'] = $WeChatUserInfo['nickname'];
      //     // $data['img'] = $WeChatUserInfo['headimgurl'];
      //     // $data['createTime'] = date('Y-m-d H:i:s',time());
      //     // $data['updateTime'] = date('Y-m-d H:i:s',time());
      //     // $res=SysWebUserTb::create($data);
      // }
      $item_list = array(
              array('title'=>'                 欢迎使用会务管理系统','desc'=>'用年轻快乐的生活态度去传播轻松愉悦的文化，引领时代的生活方式。','picurl'=>'http://weixin.gorant.cn/uploads/WeChatImg/WeChatWelcome_zibi.png','url'=>'http://phpitem.grouptong.top/clubWechart/index.html')
              );
      $this->_msgNews($request_xml->FromUserName,$request_xml->ToUserName,$item_list);
  }

  /**
   * 发送图文
   * @Author   ZeroC
   * @DateTime 2018-02-02T10:54:42+0800
   * @Function
   * @param    [type]                   $to        [description]
   * @param    [type]                   $from      [description]
   * @param    array                    $item_list [description]
   * @return   [type]                              [description]
   */
    private function _msgNews($to,$from,$item_list=array()){
        //拼凑文章部分
        $item_str = '';
        foreach ($item_list as $item) {
            $item_str .= sprintf(self::$_msg_template['news_item'],$item['title'],$item['desc'],$item['picurl'],$item['url']);
        }
        //拼凑主体部分
        $response = sprintf(self::$_msg_template['news'], $to, $from, time(), count($item_list), $item_str);
        die($response);
    }

  /**
   * 扫描渠道二维码关注
   * @Author   ZeroC
   * @DateTime 2018-02-02T10:11:12+0800
   * @Function
   * @param    [type]                   $request_xml [description]
   * @return   [type]                                [description]
   */
  function _doQRSubscribe($request_xml){
      $fromId = str_replace("qrscene_","",(string)$request_xml->EventKey);
      $this->_msgNotice($touser,$title,$fromId,$gourl,$picurl);
      $touser=$request_xml->FromUserName;
      $toName=$userInfo['nickName'];
      $title="                 去租欢迎您！";
      $desc="                      去生活 租轻松";
      $gourl='http://weixin.gorant.cn/index.php/mobile/index/index';
      $picurl='http://weixin.gorant.cn/uploads/WeChatImg/WeChatWelcome.png';
      
      $userOpenId=(string)$request_xml->FromUserName;
      $userInfo=UsersModel::where(['openId'=>$userOpenId])->find();
      if(!$userInfo['id']){
        $WeChatUserInfo=$this->getWeChatUserInfo($touser);
        $data['openId'] = $request_xml->FromUserName;
        $data['nickName'] = $WeChatUserInfo['nickname'];
        $data['img'] = $WeChatUserInfo['headimgurl'];
        $data['createTime'] = date('Y-m-d H:i:s',time());
        $data['updateTime'] = date('Y-m-d H:i:s',time());
        $data['fromId']=$fromId;
        if(strstr($fromId,'QC_')==$fromId){
          $qrInfo=QrCouponModel::where(['qrCode'=>$fromId])->find();
          $data['couponList']=$qrInfo['couponList'];
          $fromNickName=$qrInfo['name'];
        }else{
          $fromUserInfo=UsersModel::where(['id'=>$fromId])->find();
          $data['couponList']=$fromUserInfo['channelCoupon'];
          $fromNickName=$fromUserInfo['name'];
          $fromTitle="推荐结果提醒";
          $fromDesc="您的好友 ".$data['nickName']." 通过扫描您的推荐码，关注了本公众号，十分感谢您的支持！";
          $this->_msgNotice($fromUserInfo['openId'],$fromTitle,$fromDesc,null,null);
        }
        $res=UsersModel::create($data);
        $desc="您的推荐人是:  ".$fromNickName."。
          如果您想拥有自己的推广码，可以[点击]本条消息，申请您的专属推荐码";
        $gourl='http://weixin.gorant.cn/index.php/mobile/join/myqrcode.html';
      }
      if($userInfo['fromId']){
        $fromId=$userInfo['fromId'];
        if(strstr($fromId,'QC_')==$fromId){
          $qrInfo=QrCouponModel::where(['qrCode'=>$fromId])->find();
          $fromNickName=$qrInfo['name'];
        }else{
          $fromUserInfo=UsersModel::where(['id'=>$fromId])->find();
          $fromNickName=$fromUserInfo['name'];
        }
        $desc="您是通过：".$fromNickName." 关注我们的！";
      }
      $this->_msgNotice($touser,$title,$desc,$gourl,$picurl);
  }

  /**
   * 已关注公众号扫描渠道二维码事件
   * @Author   ZeroC
   * @DateTime 2018-02-02T10:11:53+0800
   * @Function
   * @param    [type]                   $request_xml [description]
   * @return   [type]                                [description]
   */
  function _doSCAN($request_xml){
    $scene_id = $request_xml->EventKey;
    $touser=$request_xml->ToUserName;
    $userOpenId=(string)$request_xml->FromUserName;
    
    $userInfo=UsersModel::where(['openId'=>$userOpenId])->find();
    $title="扫码结果";
    $gourl="http://weixin.gorant.cn/index.php/mobile/join/myqrcode.html";
    $picurl='http://weixin.gorant.cn/uploads/WeChatImg/WeChatWelcome.png';
    // if($userInfo['fromId']){
    //     $fromInfo=UsersModel::where(['id'=>$userInfo['fromId']])->find();
    //     $fromNickName=$fromInfo['nickName'];
    //     $desc="您的推荐人是:  ".$fromNickName."。
    //     如果您还没有拥有自己的推广码，可以[点击]本条消息，申请您的专属推荐码";
    // }else{
    //     $desc="您是自行关注本平台的，没有推荐人。
    //     如果您还没有拥有自己的推广码，可以[点击]本条消息，申请您的专属推荐码";
    // }
    $item_list = array(
              array('title'=>$title,'desc'=>$desc,'picurl'=>$picurl,'url'=>$gourl)
              );
    $this->_msgNews($request_xml->FromUserName,$request_xml->ToUserName,$scene_id);
  }

    private function _doText($request_xml){
      $item_list = array();
      $item_list[0]=array('title'=>'欢迎使用会务管理系统','desc'=>'请先绑定您的手机号码！','picurl'=>'','url'=>'');
      // $this->_msgNews($request_xml->FromUserName,$request_xml->ToUserName,$item_list);
      $this->_msgNews($request_xml->FromUserName,$request_xml->ToUserName,$item_list);
        // $content = $request_xml->FromUserName;
        // $openId=$request_xml->FromUserName;
        // $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $content);
    }

    /**
     * 发送文本信息
     * @param  [type] $to      目标用户ID
     * @param  [type] $from    来源用户ID
     * @param  [type] $content 内容
     * @return [type]          [description]
     */
    private function _msgText($to, $from, $content) {
        $response = sprintf(self::$_msg_template['text'], $to, $from, time(), $content);
        die($response);
    }

    /**
   * 回复用户消息格式
   * [$_msg_template description]
   * @var array
   */
  private static $_msg_template = array(
        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>',//文本回复XML模板
        'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>',//图片回复XML模板
        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>',//音乐模板
        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>',// 新闻主体
        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>',//某个新闻模板
        'transfer_customer_service' => 
            '<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[transfer_customer_service]]></MsgType>
            </xml>',
        'template' =>'{
            "touser":"osvwx5ikH_5vkhSL4zi6W38fw3XU",
            "template_id":"LmffGShSojA-JcU2jE8qcU2iGnRCtRAo0yYC2M5cX0Y",
            "url":"http://weixin.qq.com/download",
            "topcolor":"#FF0000",
            "data":{
                "User": {
                    "value":"李桥小姐123",
                    "color":"#173177"
                },
                "Date":{
                    "value":"06月07日 19时24分",
                    "color":"#173177"
                    },
                "CardNumber": {
                    "value":"0426",
                    "color":"#173177"
                    },
                "Type":{
                    "value":"消费",
                    "color":"#173177"
                    },
                "Money":{
                    "value":"人民币260.00元",
                    "color":"#173177"
                },
                "DeadTime":{
                    "value":"06月07日19时24分",
                    "color":"#173177"
                },
                "Left":{
                    "value":"6504.09",
                    "color":"#173177"
                }
            }
        }'
);

    /**
     * name 通知 -new型
     * introduction 
     *
     * @Author   ZeroC
     * @DateTime 2018-05-16
     *
     * @param    [type]     $to     [description]
     * @param    [type]     $title  [description]
     * @param    [type]     $desc   [description]
     * @param    [type]     $gourl  [description]
     * @param    [type]     $picurl [description]
     * @return   [type]             [description]
     */
    private function _msgNotice($to,$title,$desc,$gourl,$picurl){
        $accessToken =self::getWxAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;
        $data=self::$_msg_template['template'];
        // $data='{
        //         "touser":"'.$to.'",
        //         "msgtype":"news",
        //         "news":{
        //             "articles": [
        //              {
        //                  "title":"'.$title.'",
        //                  "description":"'.$desc.'",
        //                  "url":"'.$gourl.'",
        //                  "picurl":"'.$picurl.'"
        //              }
        //              ]
        //         }
        //     }';
        $res=self::api_notice_increment($url, $data);
        return $res;
    }

    /**向微信接口发送post请求
   * @Author   ZeroC
   * @DateTime 2018-01-30T14:19:21+0800
   * @Function
   * @param    [type]                   $url  [description]
   * @param    [type]                   $data [description]
   * @return   [type]                         [description]
   */
    function api_notice_increment($url, $data){
        $ch = curl_init();
        $header[] = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
            
        if (curl_errno($ch)) {
            curl_close( $ch );
            return $ch;
        }else{
            curl_close( $ch );
            return $tmpInfo;
        }
    }

    function api_get($url){
      $oCurl = curl_init();
      if (stripos($url, "https://") !== FALSE) {
          curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
          curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
      }
      curl_setopt($oCurl, CURLOPT_TIMEOUT, 1);

      curl_setopt($oCurl, CURLOPT_URL, $url);
      curl_setopt($oCurl, CURLOPT_HEADER, 0);
      curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
      $json = curl_exec($oCurl);
      $arr = json_decode($json,true);
      curl_close($oCurl);
      return $arr;
    }


    public function showAccessToken(){
      return $this->getWxAccessToken();
    }

    /**获取微信公众号access_token
     * @Author   ZeroC
     * @DateTime 2018-01-30T13:50:26+0800
     * @Function
     * @return   [type]                   [description]
     */
    private function getWxAccessToken(){
        $appid = APPID;
        $secret = APPSECRET;
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 1);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $json = curl_exec($oCurl);
        $arr = json_decode($json,true);
        curl_close($oCurl);
        return $arr['access_token'];
    }

  /**
   * name 推送消息接口
   * introduction 
   *
   * @Author   ZeroC
   * @DateTime 2018-03-07
   *
   * @return   [type]     [description]
   */
    public static function userNotice($touser,$title,$desc,$url=null,$picurl=null){
        $res=self::_msgNotice($touser,$title,$desc,$url,$picurl);
        return $res;
    }

  /**
   * name 推送消息接口
   * introduction 
   *
   * @Author   ZeroC
   * @DateTime 2018-03-07
   *
   * @return   [type]     [description]
   */
    public static function userNotice1($touser,$title,$desc,$url=null,$picurl=null){
        $res=self::_msgNotice($touser,$title,$desc,$url,$picurl);
        return $res;
    }

    public function getOauthCode($backUrl){
      $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=$backUrl&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
      Header("HTTP/1.1 303 See Other");
      Header('Location:'.$url);
    }
    
    public function getOpenid($code){
      $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".APPID."&secret=".APPSECRET."&code=$code&grant_type=authorization_code";
      return $this->api_get($url);
    }

/**渠道二维码**************************************************************************/
    /**获取二维码ticket
     * @Author   ZeroC
     * @DateTime 2018-01-30T13:53:30+0800
     * @Function
     * @return   [type]                   [description]
     */
    function getQRTicket($qrCode){//
        $accessToken =$this->showAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$accessToken;
        $qrcode = '{"expire_seconds": 604800,"action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$qrCode.'"}}}';
        $result = $this->api_notice_increment($url, $qrcode);
        $result = json_decode($result, true);
        return $result;
    }
}