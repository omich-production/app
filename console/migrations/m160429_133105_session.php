<?php

use yii\db\Migration;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class m160429_133105_session extends Migration
{

    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%session}} (
                id CHAR(40) NOT NULL PRIMARY KEY,
                expire INTEGER,
                data BLOB
            )
        ");
    }

    public function down()
    {
        echo "m160429_133105_session cannot be reverted.\n";

        return false;
    }

}