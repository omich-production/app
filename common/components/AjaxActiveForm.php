<?php

namespace common\components;

use yii\bootstrap\ActiveForm;

/**
 * A form without beginning and ending tags. Used to render form fields to be added
 * to page via ajax.
 * @author Albert Garipov <bert320@gmail.com>
 */
class AjaxActiveForm extends ActiveForm
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        // supress output
        ob_start();
        parent::init();
        ob_end_clean();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        // empty
    }

}