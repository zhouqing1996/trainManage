<?php
namespace frontend\models\wechart\index;

use Yii;
use yii\base\Model;
use common\models\User;
/**
 * Login form
 */
class Logout extends Model
{
    public $access_token;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token'], 'required'],
            ['access_token', 'string'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function logout()
    {
        if ($this->getAccessToken()) {
            $this->_user->access_token=null;
            if($this->_user->save(false)){
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[accessToken]]
     *
     * @return User|null
     */
    protected function getAccessToken()
    {
       if ($this->_user === null) {
            $this->_user = User::findIdentityByAccessToken($this->access_token);
        }

        return $this->_user;
    }
}
