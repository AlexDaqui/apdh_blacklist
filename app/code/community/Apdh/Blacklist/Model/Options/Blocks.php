<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Model_Options_Blocks
{
  public function getBlocksOptions()
  {
    $helper = Mage::helper('blacklist');
    $array = array(
        array('value'=>'login', 'label'=> $helper->__('Login')),
        array('value'=>'checkout', 'label'=> $helper->__('Checkout'))
    );

    return $array;
  }

}
