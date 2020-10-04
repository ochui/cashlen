<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;
use app\common\Constants;
use app\common\Helper;
use PHPUnit\TextUI\Help;
use Yii;

/**
 * This is the model class for table "user_status_history".
 *
 * @property int $id
 * @property int $user_id
 * @property int $last_status_id
 * @property int $user_status_id
 * @property string $time
 * @property string $ip
 * @property string $useragent
 *
 * @property UserBillingCycles[] $userBillingCycles
 * @property Users $user
 * @property UserStatus $userStatus
 * @property UserStatus $lastStatus
 */
class UserStatusHistory extends BaseActiveRecord
{

    public function beforeValidate()
    {
        if($this->isNewRecord){
            $this->time = date(Constants::PHP_DATE_FORMAT);
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_status_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'last_status_id', 'user_status_id', 'ip', 'useragent'], 'required'],
            [['user_id', 'last_status_id', 'user_status_id'], 'integer'],
            [['time'], 'safe'],
            [['ip', 'useragent'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['last_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserStatus::className(), 'targetAttribute' => ['last_status_id' => 'id']],
            [['user_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserStatus::className(), 'targetAttribute' => ['user_status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'last_status_id' => Yii::t('app', 'Last Status'),
            'user_status_id' => Yii::t('app', 'User Status'),
            'time' => Yii::t('app', 'Time'),
            'ip' => Yii::t('app', 'Ip'),
            'useragent' => Yii::t('app', 'Useragent'),
        ];
    }

    /**
     * Gets query for [[UserBillingCycles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserBillingCycles()
    {
        return $this->hasMany(UserBillingCycles::className(), ['close_history_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[LastStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLastStatus()
    {
        return $this->hasOne(UserStatus::className(), ['id' => 'last_status_id']);
    }

    /**
     * Gets query for [[UserStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserStatus()
    {
        return $this->hasOne(UserStatus::className(), ['id' => 'user_status_id']);
    }
}
