<?php

namespace common\components;

use yii\web\UrlManager;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class BackendUrlManager extends UrlManager
{

    public function init()
    {
        $this->showScriptName = false;
        $this->enablePrettyUrl = true;

        $this->rules = [
            '/' => 'site/index',
            '/' => 'site',
        ];

        parent::init();
    }

}