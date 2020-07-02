<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Model_Observer_Newsletter extends Mage_Core_Model_Observer
{
    public function saveSubscriber(Varien_Event_Observer $observer)
    {
        $url = Mage::helper('core/http')->getHttpReferer()?Mage::helper('core/http')->getHttpReferer():Mage::getUrl();
        $request = Mage::app()->getRequest()->getParams();
        $helper = Mage::helper('blacklist');

        if (strpos(Mage::getStoreConfig('blacklist_options/data/allow'), 'newsletter') !== false) {
            $domains = explode(",", Mage::getStoreConfig('blacklist_options/data/domains'));
            $message = Mage::getStoreConfig('blacklist_options/data/message');
            $message = ($message)?$message:$helper->__("Sorry, your email in not allow at this store.");
            if ($request['email'] != "") {
                if ($helper->checkEmail($request['email'], $domains)) {
                    Mage::getSingleton('core/session')->addError($message);
                    $controller = $observer->getControllerAction();
                    $controller->setFlag('', 'no-dispatch', true);
                    Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                }
            }
        }
    }
}
