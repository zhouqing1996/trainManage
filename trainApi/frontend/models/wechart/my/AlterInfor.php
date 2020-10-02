<?php
namespace frontend\models\wechart\my;

use Yii;
use yii\base\Model;
use common\models\SysWebUserTb;
use common\models\User;

/**
 * Login form
 */
class AlterInfor extends Model
{
    public $USER;
    public $NAME;
    public $SITE;
    public $CARDID;
    public $RANK;
    public $SEX;
    public $AGE;
    public $PROVINCE;
    public $CITY;
    public $EMAIL;

    private $_user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['USER', 'NAME','SITE','CARDID'], 'trim'],
            [['USER', 'NAME','SITE','CARDID','RANK','SEX','AGE','PROVINCE','CITY','EMAIL'], 'string'],
            ['CARDID', 'string', 'length' => 18]
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function alter($access_token)
    {
        if (!$this->validate()) {
            return $this->errors;
        }

        $user=User::findIdentityByAccessToken($access_token);

        if($user==null){
            return false;
        }

        $webUser =SysWebUserTb::find()->where(['USER'=>$user->username])->one();

        if($this->NAME)
            $webUser->NAME = $this->NAME;
        if($this->SITE)
            $webUser->SCHOOL = $this->SITE;
        if($this->CARDID)
            $webUser->CARDID = $this->CARDID;
        if($this->RANK)
            $webUser->RANK = $this->RANK;
        if($this->SEX)
            $webUser->SEX = $this->SEX;
        if($this->AGE)
            $webUser->AGE = $this->AGE;
        if($this->PROVINCE)
            $webUser->PROVINCE = $this->PROVINCE;
        if($this->CITY)
            $webUser->CITY = $this->CITY;
        if($this->EMAIL)
            $webUser->EMAIL = $this->EMAIL;

        return $webUser->save() ? $webUser : false;
    }
}
