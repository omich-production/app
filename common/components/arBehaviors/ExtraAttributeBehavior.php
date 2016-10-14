<?php

namespace common\components\arBehaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class ExtraAttributeBehavior extends Behavior
{

    /**
     * @var ActiveRecord
     */
    public $owner;
    protected $data = [];
    public $attributes = [];

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return array_key_exists($name, $this->data) ? $this->data[$name] : null;
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->attributes)) {
            $this->data[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function canSetProperty($name, $checkVars = true)
    {
        if (array_key_exists($name, $this->attributes)) {
            return true;
        } else {
            parent::canGetProperty($name, $checkVars);
        }
    }

    public function canGetProperty($name, $checkVars = true)
    {
        if (array_key_exists($name, $this->attributes)) {
            return true;
        } else {
            parent::canGetProperty($name, $checkVars);
        }
    }

}