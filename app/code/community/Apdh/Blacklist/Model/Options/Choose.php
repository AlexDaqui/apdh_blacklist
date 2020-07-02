<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Model_Options_Choose
{
  public function toOptionArray()
  {
    $helper = Mage::helper('blacklist');
    $array = array(
        array('value'=>'yes', 'label'=> $helper->__('Yes')),
        array('value'=>'no', 'label'=> $helper->__('No'))
    );

    return $array;
  }

}
