<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Model_Options_Users
{
  public function getUsersOptions()
  {
    $users = Mage::getModel('customer/customer')->getCollection();
    $array = array();

    foreach ($users as $user) {
        $array[] = array(
            'value' => $user->getEntityId(),
            'label' => $user->getEmail()
        );
    }

    return $array;
  }

}
