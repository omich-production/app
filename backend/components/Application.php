<?php

namespace backend\components;

use common\Rbac;
use Yii;
use yii\web\Application as baseApplication;
use yii\web\ForbiddenHttpException;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class Application extends baseApplication
{

    /**
     * @var array
     */
    public $publicRoutes = [];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        // Global access checking for 'showBackend' permission.
        if (parent::beforeAction($action)) {
            if (!in_array(Yii::$app->controller->route, $this->publicRoutes)) {
                if (!Yii::$app->user->can(Rbac::TASK_SHOW_BACKEND)) {
                    $this->denyAccess();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private function denyAccess()
    {
        if (Yii::$app->user->getIsGuest()) {
            Yii::$app->user->loginRequired();
            Yii::$app->end();
        } else {
            Yii::$app->controller->layout = 'blank';
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

}