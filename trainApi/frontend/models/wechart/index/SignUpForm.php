<?php
namespace frontend\models\wechart\index;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    // public $email;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 1, 'max' => 255],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        if(User::judgeByUsername($this->username)!=null){
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->kind = 9;
        $user->created_at = time();
        $user->status = 0;
        
        return $user->save() ? $user : null;
    }
}
