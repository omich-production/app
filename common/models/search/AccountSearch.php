<?php

namespace common\models\search;

use common\models\Account;
use common\models\queries\AccountQuery;
use yii\data\ActiveDataProvider;

/**
 * @author Albert Garipov <bert320@gmail.com>
 * @see Account
 * @property string $cityId
 * @property string $cinemaId
 */
class AccountSearch extends Account
{

    public $pageSize = 1000;

    /**
     * @var AccountQuery
     */
    public $query;

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'cityId',
            'cinemaId',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['id', 'username', 'email', 'cityId', 'cinemaId', 'position', 'role'],
                'safe',
            ],
        ];
    }

    /**
     * @param array $params
     * @param string $formName
     * @return ActiveDataProvider
     */
    public function search($params = [], $formName = null)
    {
        /* @var $query AccountQuery */
        $query = $this->query ? $this->query : Account::find();

        $query->joinWith('cinemas');

        $provider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $provider->pagination->defaultPageSize = $this->pageSize;

        $provider->sort->defaultOrder = [
            'id' => SORT_DESC,
        ];

        // load the seach form data and validate
        if (!($this->load($params, $formName) && $this->validate())) {
            return $provider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['role' => $this->role]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'position', $this->position]);
        $query->andFilterWhere(['cinema.cityId' => $this->cityId]);
        $query->andFilterWhere(['cinema.id' => $this->cinemaId]);

        return $provider;
    }

}