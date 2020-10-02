<?php
namespace frontend\models\wechart\index;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class Login extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;
    private $_members;
    private $_webUsers;

    const GET_ACCESS_TOKEN = 'generate_access_token';

    public function init ()
    {
        parent::init();
        $this->on(self::GET_ACCESS_TOKEN, [$this, 'onGenerateAccessToken']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $this->trigger(self::GET_ACCESS_TOKEN);
            if($this->_user->kind!=9){
               return false;
            }else{
                $this->_webUsers=User::findByUsername($this->username)->webUsers;
                return array(
                    'user' => $this->_user->username, 
                    'name'=>$this->_webUsers->NAME,
                    'cardId'=>$this->_webUsers->CARDID,
                    'site'=>$this->_webUsers->SCHOOL,
                    'sex'=>$this->_webUsers->SEX,
                    'age'=>$this->_webUsers->AGE,
                    'province'=>$this->_webUsers->PROVINCE,
                    'city'=>$this->_webUsers->CITY,
                    'access_token'=>$this->_user->access_token,
                    "kind"=>$this->_user->kind
                );
            }
        } else {
            return false;
        }

    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    /**
     * 登录校验成功后，为用户生成新的token
     * 如果token失效，则重新生成token
     */
    public function onGenerateAccessToken ()
    {
        if($this->_user->access_token!=null){
            if (!User::accessTokenIsValid($this->_user->access_token)) {
                $this->_user->generateAccessToken();
                $this->_user->save(false);
            }           
        }else{
            $this->_user->generateAccessToken();
            $this->_user->save(false);
        }

    }
}
