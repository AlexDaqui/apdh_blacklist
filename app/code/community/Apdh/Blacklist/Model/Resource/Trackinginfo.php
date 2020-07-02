<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Model_Resource_Trackinginfo extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('blacklist/tracking_info', 'info_id');
    }
}
