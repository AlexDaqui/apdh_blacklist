<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking_Renderer_Email
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        $customer = Mage::getModel('customer/customer')->load($value);
        return $customer->getEmail();
    }
}
