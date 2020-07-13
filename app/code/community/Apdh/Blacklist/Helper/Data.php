<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_items;

    public function checkEmail($email)
    {
        $emails = explode(",", Mage::getStoreConfig('blacklist_options/data/emails'));
        $domains = explode(",", Mage::getStoreConfig('blacklist_options/data/domains'));

        if (in_array($email, $emails, true)) {
            Mage::helper('blacklist/report')->saveReport($email);
            return true;
        }

        $domain = end(explode("@", $email));
        foreach ($domains as $blacklist) {
            if ($this->wildcard($blacklist, $domain)) {
                Mage::helper('blacklist/report')->saveReport($email);
                return true;
            }
        }

        return false;
    }

    public function wildcard($pattern, $subject)
    {
        $pattern = strtr($pattern, array('*' => '.*?'));
        return preg_match("/$pattern/", $subject);
    }

    public function getEmailQuote($id)
    {
        $quote = Mage::getModel('sales/quote')->load($id);
        $email = $quote->getCustomerEmail();
        unset($quote);
        return $email;
    }

    public function getInfoQuote($id)
    {
        $quote = Mage::getModel('sales/quote')->load($id);
        $items = $quote->getItemsCollection();

        $array['email'] = $quote->getCustomerEmail();

        foreach ($items as $item) {
            $name = $item->getName();
            $qty = (int)$item->getQty();
            $price = $item->getPrice() * $qty;
            $total = Mage::helper('core')->currency($price);
            $sku = $item->getSku();
            $array['items_html'] .= "<li>$name (sku: $sku) x $qty : $total (total)</li>";
        }

        return $array;
    }

    public function isCheckUser($username, $type)
    {
        if (is_array($username)) {
            $this->_items = $username['items_html'];
            $username = $username['email'];
        }

        $websiteId = Mage::app()->getWebsite()->getId();
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($websiteId)
            ->loadByEmail($username);
        if ($customer->getEntityId()) {
            $tracking = Mage::getModel('blacklist/tracking')->load($customer->getEntityId(), 'user_id');

            if ($tracking->getTrackingId()) {
                if (in_array($type, explode(",", $tracking->getBlocksType()))) {
                    $this->saveLog($tracking->getTrackingId(), $type, true);
                    return "block";
                } else {
                    $this->saveLog($tracking->getTrackingId(), $type);
                }

                return "pass";
            }
        }

        return false;
    }

    public function saveLog($trackingId, $type, $block = null)
    {
        $result = ($block != null)?
            Mage::helper('blacklist')->__("Error %s - blocked user", $type) :
            Mage::helper('blacklist')->__("Successful %s - user is not blocked", $type);

        $forward = Mage::app()->getRequest()->getServer('HTTP_X_FORWARDED_FOR');
        $ip = Mage::helper('core/http')->getRemoteAddr();
        $ip .= ($forward)?" ($forward)":
            Mage::helper('blacklist')->__(" (HTTP_X_FORWARDED_FOR unknown)");

        $intent = Mage::helper('blacklist')->__("Start process %s", $type);

        $array = array(
            "tracking" => $trackingId,
            "type" => $type,
            "origin" => Mage::helper('core/http')->getHttpUserAgent(),
            "ip" => $ip,
            "intent" => "<p>$intent</p><ul>".$this->_items."</ul>",
            "result" => $result,
            "created_time" => now()
        );

        $trackinginfo = Mage::getModel('blacklist/trackinginfo');
        $trackinginfo->setData($array);
        try{
            $trackinginfo->save();
        } catch (Exception $e) {
            Mage::log("Exception:: ".$e->getMessage(), Zend_Log::ALERT, 'apdh_blacklist.log', true);
        }

    }
}
