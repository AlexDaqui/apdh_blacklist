<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
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
