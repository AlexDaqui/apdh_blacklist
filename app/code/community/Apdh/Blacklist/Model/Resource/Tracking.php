<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Model_Resource_Tracking extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('blacklist/tracking', 'tracking_id');
    }
}
