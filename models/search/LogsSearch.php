<?php

namespace app\models\search;

use app\common\Constants;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\activerecord\Logs;

/**
 * LogsSearch represents the model behind the search form of `app\models\Logs`.
 */
class LogsSearch extends Logs
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['particulars', 'time', 'ip', 'useragent', 'type', 'data', 'country', 'country_code'], 'safe'],
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
        $query = Logs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>['id'=>SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);

        if(is_string($this->type) && $this->type!=''){
            $this->type = strtolower(str_replace(' ','_',trim($this->type)));
        }

        $query->andFilterWhere(['like', 'particulars', $this->particulars])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'useragent', $this->useragent])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'country_id', $this->country_id])
            ->andFilterWhere(['like', 'time', $this->time]);

        return $dataProvider;
    }
}
