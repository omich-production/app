<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\web\Application;
use yii\web\View;
use yii\helpers\Json;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class Debug extends Behavior
{

    public static $varExport = [];
    public static $varDump = [];

    public function events()
    {
        return [
            View::EVENT_END_PAGE => 'run',
        ];
    }

    public function run($event)
    {
        /* @var $view View */
        $view = $this->owner;

        if (count(self::$varDump) || count(self::$varExport)) {
            echo "<script>\n";
            echo "\n(function(){\n\tif(window['console']){";
            foreach (self::$varExport as $object) {
                $log = Json::encode(var_export($object, true));
                echo "\t\tconsole.log({$log});\n";
            }
            foreach (self::$varDump as $object) {
                ob_start();
                var_dump($object);
                $log = Json::encode(ob_get_clean());
                echo "\t\tconsole.log({$log});\n";
            }
            echo "\t}\n}());\n";
            echo "</script>\n";
        }
    }

}