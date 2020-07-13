<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
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
