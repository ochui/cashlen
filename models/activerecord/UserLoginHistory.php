<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;

/**
 * This is the model class for table "user_login_history".
 *
 * @property int $id
 * @property int $user_id
 * @property string $time
 * @property string $ip
 * @property string $useragent
 * @property int|null $country_id
 *
 * @property Countries $country
 * @property Users $user
 */
class UserLoginHistory extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_login_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'ip', 'useragent'], 'required'],
            [['user_id', 'country_id'], 'integer'],
            [['time'], 'safe'],
            [['ip'], 'string', 'max' => 64],
            [['useragent'], 'string', 'max' => 500],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'time' => 'Time',
            'ip' => 'Ip',
            'useragent' => 'Useragent',
            'country_id' => 'Country',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
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
}
