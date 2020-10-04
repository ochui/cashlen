<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\activerecord\Submissions;

/**
 * SubmissionsSearch represents the model behind the search form of `app\models\activerecord\Submissions`.
 */
class SubmissionsSearch extends Submissions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'birth_year', 'active_military', 'home_owner', 'bank_routing_number', 'unsecured_debt'], 'integer'],
            [['reason_for_loan', 'loan_amount_required', 'first_name', 'last_name', 'last_four_ssn', 'zip_code', 'email', 'phone_number', 'dob', 'street_address', 'years_living_from', 'employment_status', 'years_with_employer', 'how_often_paid', 'monthly_income', 'next_pay_date', 'employer_name', 'occupation', 'employer_phone_number', 'drivers_license', 'state', 'ssn', 'account_number', 'bank_name', 'how_get_paid', 'time_with_account', 'account_type', 'useragent', 'time', 'ip'], 'safe'],
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
    public function search($params)
    {
        $query = Submissions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'birth_year' => $this->birth_year,
            'active_military' => $this->active_military,
            'home_owner' => $this->home_owner,
            'bank_routing_number' => $this->bank_routing_number,
            'unsecured_debt' => $this->unsecured_debt,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'reason_for_loan', $this->reason_for_loan])
            ->andFilterWhere(['like', 'loan_amount_required', $this->loan_amount_required])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'last_four_ssn', $this->last_four_ssn])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'dob', $this->dob])
            ->andFilterWhere(['like', 'street_address', $this->street_address])
            ->andFilterWhere(['like', 'years_living_from', $this->years_living_from])
            ->andFilterWhere(['like', 'employment_status', $this->employment_status])
            ->andFilterWhere(['like', 'years_with_employer', $this->years_with_employer])
            ->andFilterWhere(['like', 'how_often_paid', $this->how_often_paid])
            ->andFilterWhere(['like', 'monthly_income', $this->monthly_income])
            ->andFilterWhere(['like', 'next_pay_date', $this->next_pay_date])
            ->andFilterWhere(['like', 'employer_name', $this->employer_name])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'employer_phone_number', $this->employer_phone_number])
            ->andFilterWhere(['like', 'drivers_license', $this->drivers_license])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'ssn', $this->ssn])
            ->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'how_get_paid', $this->how_get_paid])
            ->andFilterWhere(['like', 'time_with_account', $this->time_with_account])
            ->andFilterWhere(['like', 'account_type', $this->account_type])
            ->andFilterWhere(['like', 'useragent', $this->useragent])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
