<?php

namespace app\models\search;

use app\common\Constants;
use app\common\Helper;
use app\models\activerecord\Users;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use yii\db\Expression;
use yii\helpers\HtmlPurifier;

/**
 * UsersSearch represents the model behind the search form of `\app\models\Users`.
 */
class UsersSearch extends Users
{
    public $searchQuery = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'is_founder', 'country_id', 'is_two_fa', 'role_id', 'left_leg', 'right_leg', 'referred_by', 'parent_id', 'position', 'default_position', 'cycle', 'can_withdraw', 'rank_id', 'is_login_two_factor'], 'integer'],
            [['identifier', 'is_debt', 'username', 'first_name', 'last_name', 'email', 'password', 'code', 'time', 'updated_on', 'two_fa_secret', 'auth_key', 'ip', 'useragent', 'referral_code'], 'safe'],
            [['left_volume', 'right_volume', 'left_outstanding', 'right_outstanding'], 'number'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $query = null)
    {
        $identity = Yii::$app->user->identity;
        if($query==null) {
            $query = Users::find();
        }

        $defaultOrder = ['id' => SORT_DESC];


        if($this->searchQuery!=''){
            $query->andWhere(['or',
                ['like','identifier',$this->searchQuery],
                ['like','username',$this->searchQuery],
                ['like','first_name',$this->searchQuery],
                ['like','last_name',$this->searchQuery],
                ['like','email',$this->searchQuery],
                ['like','mobile_no',$this->searchQuery],
            ]);
        }

        if(Helper::isAdmin()){
            $query->andWhere(['!=', 'role_id', Constants::USER_ROLE_ADMIN]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $defaultOrder
            ]
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status_id' => $this->status_id,
            'country_id' => $this->country_id,
            'time' => $this->time,
            'updated_on' => $this->updated_on,
            'is_two_fa' => $this->is_two_fa,
            'role_id' => $this->role_id,
            'referred_by' => $this->referred_by,
        ]);

        $query->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'two_fa_secret', $this->two_fa_secret])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'useragent', $this->useragent])
            ->andFilterWhere(['like', 'referral_code', $this->referral_code]);
        return $dataProvider;
    }

}
