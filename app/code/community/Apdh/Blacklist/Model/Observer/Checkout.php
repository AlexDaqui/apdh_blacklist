<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Model_Observer_Checkout extends Mage_Core_Model_Observer
{
    public function saveCheckout(Varien_Event_Observer $observer)
    {
        $url =  Mage::helper('core/http')->getHttpReferer()?Mage::helper('core/http')->getHttpReferer():Mage::getUrl();
        $id = Mage::getSingleton('checkout/session')->getData('quote_id_1');
        $helper = Mage::helper('blacklist');
        $email = $helper->getEmailQuote($id);

        if (strpos(Mage::getStoreConfig('blacklist_options/data/allow'), 'checkout') !== false) {
            $message = Mage::getStoreConfig('blacklist_options/data/message');
            $message = ($message)?$message:$helper->__("Sorry, your email in not allow at this store.");
            if ($email != "") {
                if ($helper->checkEmail($email)) {
                    Mage::getSingleton('core/session')->addError($message);
                    $controller = $observer->getControllerAction();
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
}
