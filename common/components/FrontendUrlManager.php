<?php

namespace common\components;

use yii\web\UrlManager;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class FrontendUrlManager extends UrlManager
{

    public function init()
    {
        $this->showScriptName = false;
        $this->enablePrettyUrl = true;

        $this->rules = [
            '/' => 'site/index',
            'we_offer' => 'site/we-offer',
            'confirm' => 'site/confirm',
            'job' => 'site/job',
        ];

        parent::init();
    }

}