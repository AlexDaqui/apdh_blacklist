<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Model_Observer_Newsletter extends Mage_Core_Model_Observer
{
    public function saveSubscriber(Varien_Event_Observer $observer)
    {
        $url = Mage::helper('core/http')->getHttpReferer()?Mage::helper('core/http')->getHttpReferer():Mage::getUrl();
        $request = Mage::app()->getRequest()->getParams();
        $helper = Mage::helper('blacklist');

        if (strpos(Mage::getStoreConfig('blacklist_options/data/allow'), 'newsletter') !== false) {
            $message = Mage::getStoreConfig('blacklist_options/data/message');
            $message = ($message)?$message:$helper->__("Sorry, your email in not allow at this store.");
            if ($request['email'] != "") {
                if ($helper->checkEmail($request['email'])) {
                    Mage::getSingleton('core/session')->addError($message);
                    $controller = $observer->getControllerAction();
                    $controller->setFlag('', 'no-dispatch', true);
                    Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                }
            }
        }
    }
}
