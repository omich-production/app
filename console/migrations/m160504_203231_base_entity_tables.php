<?php

use yii\db\Migration;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class m160504_203231_base_entity_tables extends Migration
{

    public function up()
    {
        /* Entity tables */
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%account}} (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `role` char(31) NOT NULL,
                `status` enum('ACTIVE','BLOCKED') NOT NULL DEFAULT 'ACTIVE',
                `authKey` char(32) NOT NULL,
                `email` varchar(255) DEFAULT NULL,
                `passwordHash` varchar(255) NOT NULL,
                `username` varchar(32) NOT NULL,
                `createdBy` int(10) unsigned DEFAULT NULL,
                `updatedBy` int(10) unsigned DEFAULT NULL,
                `createdAt` int(10) unsigned NOT NULL,
                `updatedAt` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS {{%account}}');
    }

}