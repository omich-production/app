<?php

namespace common\components\arBehaviors;

use common\components\arBehaviors\ExtraAttributeBehavior;
use yii\db\ActiveRecord;

/**
 * The active record behavior.
 * @author Albert Garipov <bert320@gmail.com>
 */
class LinkBehavior extends ExtraAttributeBehavior
{

    /**
     * Array of IDs.
     */
    const FORMAT_ARRAY = 'ARRAY';

    /**
     * Comma-separated list of IDs.
     */
    const FORMAT_STRING = 'STRING';

    /**
     * @var string
     */
    public $format = self::FORMAT_ARRAY;

    /**
     * @var boolean
     */
    public $deleteViaTableRow = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'link',
            ActiveRecord::EVENT_AFTER_INSERT => 'link',
        ];
    }

    public function __set($name, $value)
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }
        parent::__set($name, $value);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            if (!array_key_exists($name, $this->data)) {
                $this->data[$name] = $this->owner->getRelation($this->attributes[$name])
                ->asArray()->select('id')->column();
            }

            if ($this->format === self::FORMAT_STRING) {
                return join(',', $this->data[$name]);
            } else {
                return $this->data[$name];
            }
        } else {
            return parent::__get($name);
        }
    }

    public function link($event)
    {
        foreach ($this->attributes as $attribute => $relationName) {
            if (!array_key_exists($attribute, $this->data)) {
                continue;
            }

            $relation = $this->owner->getRelation($relationName);
            $relatedClass = $relation->modelClass;

            if (is_array($this->data[$attribute])) {
                $ids = $this->data[$attribute];
            } else {
                $ids = array_map('trim', explode(',', $this->data[$attribute]));
            }

            $currentlyRelated = $relation->indexBy('id')->all();
            foreach ($currentlyRelated as $key => $model) {
                if (($key = array_search($model->id, $ids)) !== false) {
                    unset($ids[$key]);
                } else {
                    $this->owner->unlink($relationName, $model, (bool) $relation->via);
                }
            }

            if (count($ids)) {
                $relatedModels = $relatedClass::findAll(['id' => $ids]);
                foreach ($relatedModels as $model) {
                    $this->owner->link($relationName, $model);
                }
            }
        }
    }

}