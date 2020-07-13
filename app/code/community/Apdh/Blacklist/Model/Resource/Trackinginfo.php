<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Model_Resource_Trackinginfo extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('blacklist/tracking_info', 'info_id');
    }
}
