<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
$installer = $this;
$installer->run(
    "    
CREATE TABLE IF NOT EXISTS `{$installer->getTable('blacklist/tracking')}` (
    `tracking_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(10) unsigned NOT NULL,
    `blocks_type` text default '',
    `created_time` timestamp NOT NULL,        
    PRIMARY KEY (`tracking_id`),
    UNIQUE KEY `user_id` (`user_id`),
    CONSTRAINT `FK_USER_ID` FOREIGN KEY (`user_id`) 
    REFERENCES `{$installer->getTable('customer/entity')}` (`entity_id`) 
    ON DELETE CASCADE            
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `{$installer->getTable('blacklist/tracking_info')}` (
    `info_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `tracking`  int(11) NOT NULL,
    `type` varchar(10) NOT NULL,
    `origin` text default '',
    `ip` varchar(255) default '',
    `intent` text default '',
    `result` text default '',
    `created_time` timestamp NOT NULL,        
    PRIMARY KEY (`info_id`),
    KEY `tracking` (`tracking`),
    CONSTRAINT `FK_TRACKING` FOREIGN KEY (`tracking`) 
    REFERENCES `{$installer->getTable('blacklist/tracking')}` (`tracking_id`) 
    ON DELETE CASCADE	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	  
"
);
