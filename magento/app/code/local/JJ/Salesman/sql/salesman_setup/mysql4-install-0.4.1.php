<?php

    $installer = $this;
    $installer->startSetup();
    $installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('salesman')};
    CREATE TABLE {$this->getTable('salesman')} (
      `salesman_id` int(11) unsigned NOT NULL auto_increment,
      `firstName` varchar(255) NOT NULL,
      `lastName` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `mobile` int(11) NOT NULL,
      `created_date` datetime NOT NULL,
      `updated_date` datetime NOT NULL,
      PRIMARY KEY (`salesman_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    $installer->endSetup();
