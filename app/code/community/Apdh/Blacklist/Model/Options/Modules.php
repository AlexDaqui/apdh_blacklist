<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Model_Options_Modules
{
  public function toOptionArray()
  {
    $helper = Mage::helper('blacklist');
    $array = array(
        array('value'=>'newsletter', 'label'=> $helper->__('Newsletter')),
        array('value'=>'register', 'label'=> $helper->__('Register')),
        array('value'=>'checkout', 'label'=> $helper->__('Checkout'))
    );

    return $array;
  }

}
