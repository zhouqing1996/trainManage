<?php
namespace frontend\models\wechart\my;

use Yii;
use yii\base\Model;
use common\models\SysWebUserTb;
use common\models\User;

/**
 * Login form
 */
class AlterAccount extends Model
{
    public $USER;
    public $PASSWORD;

    private $_user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['USER', 'PASSWORD'], 'trim'],
            [['USER', 'PASSWORD'], 'string']
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
            return false;
        }

        $user=User::findIdentityByAccessToken($access_token);

        if($user==null){
            return false;
        }

        if($this->PASSWORD){
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->PASSWORD);
            return $user->update() ? $user : false;
        }

        return false;
    }
}
