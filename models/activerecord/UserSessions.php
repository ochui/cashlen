<?php

namespace app\models\activerecord;

use app\common\BaseActiveRecord;

/**
 * This is the model class for table "user_sessions".
 *
 * @property int $id
 * @property int $user_id
 * @property string $time
 * @property string|null $ip
 * @property string|null $useragent
 * @property string|null $expires
 * @property string $hash
 *
 * @property Users $user
 */
class UserSessions extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_sessions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'hash'], 'required'],
            [['user_id'], 'integer'],
            [['time', 'expires'], 'safe'],
            [['ip'], 'string', 'max' => 50],
            [['useragent'], 'string', 'max' => 500],
            [['hash'], 'string', 'max' => 128],
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
            'expires' => 'Expires',
            'hash' => 'Hash',
        ];
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
