<?php

namespace common\models\queries;

use common\models\Account;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Account]].
 * @see Account
 */
class AccountQuery extends ActiveQuery
{

    /**
     * @return static
     */
    public function active()
    {
        return $this->andWhere(['status' => Account::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     * @return Account[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Account
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}