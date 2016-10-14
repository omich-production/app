<?php

namespace common\components;

use common\models\Account;
use Yii;
use yii\rbac\DbManager;

/**
 * RBAC Manager that allows to store a role in the account table.
 * @author Albert Garipov <bert320@gmail.com>
 */
class RbacManager extends DbManager
{

    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        $assignments = parent::getAssignments($userId);
        
        /* @var $identity Account */
        $identity = Yii::$app->getUser()->getIdentity();
        if ($identity !== null && $identity->id === $userId) {
            $assignments[$identity->role] = true;
        } else {
            $role = Account::find()
            ->select('role')
            ->andWhere(['id' => (string) $userId])
            ->scalar();
            $assignments[$role] = true;
        }
        
        return $assignments;
    }

}