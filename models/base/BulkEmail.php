<?php
/**
 * Created by PhpStorm.
 * User: prabhjyot
 * Date: 2019-07-27
 * Time: 16:00
 */

namespace app\models\base;


use app\common\Constants;
use app\models\activerecord\Users;
use yii\base\Model;
use app\common\Helper;
use Yii;

class BulkEmail extends Model
{
    public $users;
    public $message;
    public $subject;

    public $country_id;
    public $status_id;

    const BULK_EMAIL_SCENARIO_PRIMARY = 'primary';


    public function beforeValidate()
    {
        return parent::beforeValidate();
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['users','subject','message'], 'required'],
            [['country_id','status_id'], 'safe'],
        ];
    }


    public function formName()
    {
        if($this->scenario == self::BULK_EMAIL_SCENARIO_PRIMARY)
            return parent::formName();
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(),[
            self::BULK_EMAIL_SCENARIO_PRIMARY=>['users','message','subject'],
        ]);
    }

    public function parseAndSend(){
        if($this->country_id!=""){
            //parse and send as per country
            $users = Users::find()->where(['country_id'=>$this->country_id])->all();
            $this->sendEmails($users);
        }
        else
            return false;
        return true;
    }
    public function send()
    {
        if ($this->validate()) {
            $users= Users::find()->where(['in','id',$this->users])->all();
            $this->sendEmails($users);
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'users' => 'Users',
        ];
    }

    /**
     * @param Users[] $users
     */

    protected function sendEmails($users){

        $this->message = nl2br($this->message);
        $message = $this->message;
        foreach($users as $user){
            $msg = 'Hello '.$user->username.',<span id="nameSeparator"></span><br />';

            $msg.= $message;
            Helper::sendEmail($user->email, $this->subject, $msg);
        }
    }
}

