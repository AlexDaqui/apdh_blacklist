<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Model_Observer_Tracking extends Mage_Core_Model_Observer
{
    public function checkUserLogin($observer)
    {
        $url = Mage::helper('core/http')->getHttpReferer()?Mage::helper('core/http')->getHttpReferer():Mage::getUrl();
        $controller = $observer->getControllerAction();
        $loginParams = $controller->getRequest()->getPost('login');
        $login = isset($loginParams['username']) ? $loginParams['username'] : null;
        $helper = Mage::helper('blacklist');
        $active =  Mage::getStoreConfig('blacklist_options/tracking/enable');

        if ($active == "yes") {
            if ($helper->isCheckUser($login, "login") == "block") {
                $message = Mage::getStoreConfig('blacklist_options/tracking/message');
                $message = ($message)?$message:$helper->__("Sorry, your account is block.");
                Mage::getSingleton('core/session')->addError($message);
                $controller->setFlag('', 'no-dispatch', true);
                Mage::app()->getFrontController()->getResponse()->setRedirect($url);
            }
        }
    }

    public function checkUserCheckout($observer)
    {
        $url = Mage::helper('core/http')->getHttpReferer()?Mage::helper('core/http')->getHttpReferer():Mage::getUrl();
        $id = Mage::getSingleton('checkout/session')->getData('quote_id_1');
        $controller = $observer->getControllerAction();
        $helper = Mage::helper('blacklist');
        $checkout = $helper->getInfoQuote($id);
        $active =  Mage::getStoreConfig('blacklist_options/tracking/enable');

        if ($active == "yes") {
            if ($helper->isCheckUser($checkout, "checkout") == "block") {
                $message = Mage::getStoreConfig('blacklist_options/tracking/message');
                $message = ($message)?$message:$helper->__("Sorry, your account is block.");
                Mage::getSingleton('core/session')->addError($message);
                $controller->setFlag('', 'no-dispatch', true);
                $result['success']  = false;
                $result['error']    = true;
                $result['redirect'] = $url;
                Mage::app()->getFrontController()->getResponse()
                    ->setHeader('Content-type', 'application/json', true);
                Mage::app()->getFrontController()->getResponse()
                    ->setBody(Mage::helper('core')->jsonEncode($result));
            }
        }
    }
}
