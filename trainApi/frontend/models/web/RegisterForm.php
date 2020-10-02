<?php
namespace frontend\models\web;

use yii\base\Model;
use common\models\User;
use common\models\SysWebUserTb;
/**
 * Signup form
 */
class RegisterForm extends Model
{
    public $user;
    public $password;
    public $name;
    public $province;
    public $city;
    public $school;
    public $job;
    public $email;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user', 'required'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['name', 'province', 'city', 'school', 'job','email'], 'string', 'max' => 255],
        ];
    }


    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        if(User::judgeByUsername($this->user)!=null){
            return "has";
        }

        $user = new User();
        $user->username = $this->user;
        $user->setPassword($this->password);
        $user->kind = 9;
        $user->created_at = time();
        $user->status = 10;
        $user->save();


        $sysUser = new SysWebUserTb();
        $sysUser->NAME = $this->name;
        $sysUser->USER = $this->user;
        $sysUser->PROVINCE = $this->province;
        $sysUser->CITY = $this->city;
        $sysUser->SCHOOL = $this->school;
        $sysUser->RANK = $this->job;
        $sysUser->EMAIL = $this->email;
        
        if(!$sysUser->save()){
            return false;
        }
        return true;
    }
}
