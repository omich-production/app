<?php

use yii\db\Migration;

/**
 * @author Albert Garipov <bert320@gmail.com>
 */
class m160504_214212_create_rbac_tables extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%auth_rule}} (
                `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `data` text COLLATE utf8_unicode_ci,
                `created_at` int(11) DEFAULT NULL,
                `updated_at` int(11) DEFAULT NULL,
                PRIMARY KEY (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%auth_item}} (
                `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `type` int(11) NOT NULL,
                `description` text COLLATE utf8_unicode_ci,
                `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
                `data` text COLLATE utf8_unicode_ci,
                `created_at` int(11) DEFAULT NULL,
                `updated_at` int(11) DEFAULT NULL,
                PRIMARY KEY (`name`),
                KEY `rule_name` (`rule_name`),
                KEY `idx-auth_item-type` (`type`),
                CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES {{%auth_rule}} (`name`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%auth_item_child}} (
                `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                PRIMARY KEY (`parent`,`child`),
                KEY `child` (`child`),
                CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES {{%auth_item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES {{%auth_item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%auth_assignment}} (
                `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                `created_at` int(11) DEFAULT NULL,
                PRIMARY KEY (`item_name`,`user_id`),
                CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES {{%auth_item}} (`name`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS {{%auth_assignment}};");
        $this->execute("DROP TABLE IF EXISTS {{%auth_item_child}};");
        $this->execute("DROP TABLE IF EXISTS {{%auth_item}};");
        $this->execute("DROP TABLE IF EXISTS {{%auth_rule}};");
    }

}