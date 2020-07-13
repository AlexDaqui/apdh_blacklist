<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_tracking';
    $this->_blockGroup = 'blacklist';
    $this->_headerText = Mage::helper('blacklist')->__('Tracking User');
    $this->_addButtonLabel = Mage::helper('blacklist')->__('Add Tracking');
    parent::__construct();
  }
}
