<?php

namespace app\models\base;


use app\common\Constants;
use app\models\activerecord\Users;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property \app\models\activerecord\\Users|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
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
     * @return bool
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                Yii::$app->session->setFlash('error','Incorrect username or password.');
                $this->addError('username', '');
                $this->addError('password', '');
                return false;
            }else{
                if($user->status_id==Constants::USER_STATUS_INACTIVE){
                    Yii::$app->session->setFlash('error','Your account is not active, please contact admin.');
                    $this->addError('username', '');
                    $this->addError('password', '');
                    return false;
                }elseif($user->status_id==Constants::USER_STATUS_BANNED){
                    Yii::$app->session->setFlash('error','Your account is stopped, please contact admin.');
                    $this->addError('username', '');
                    $this->addError('password', '');
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            Yii::$app->session->set('rememberMe',$this->rememberMe);
            $user = $this->getUser();
            if( $user->isSessionUnique()) {
                //check if 2fa enabled
                if ($user->is_two_fa == Constants::YES_FLAG) {
                    Yii::$app->session->set('login_two_fa',1);
                }

                $user->postLogin();
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);

            }
            else{
                Yii::$app->session->setFlash('error','You are already logged in on another device. Please logout first.');
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Users|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findByUsername($this->username);
            if($this->_user==null){
                $this->_user = Users::find()->where(['mobile_no'=>$this->username])->one();
            }
        }

        return $this->_user;
    }

    public function failedLogin(){
        if($this->_user){
            $this->_user->failedLogin();
        }
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username/Mobile no.',
        ];
    }

}
