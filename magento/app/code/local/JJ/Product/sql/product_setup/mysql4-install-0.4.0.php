<?php

    $installer = $this;
    $installer->startSetup();
    $installer->run("
    -- DROP TABLE IF EXISTS {$this->getTable('product')};
    CREATE TABLE {$this->getTable('product')} (
      `product_id` int(11) unsigned NOT NULL auto_increment,
      `name` varchar(255) NOT NULL,
      `price` int(11) NOT NULL,
      `costPrice` float NOT NULL,
      `quantity` int(11) NOT NULL,
      `sku` varchar(255) NOT NULL,
      `created_date` datetime NOT NULL,
      `updated_date` datetime NOT NULL,
      PRIMARY KEY (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    $installer->endSetup();
