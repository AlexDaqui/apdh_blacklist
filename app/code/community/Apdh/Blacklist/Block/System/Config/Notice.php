<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Block_System_Config_Notice extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper('blacklist');
        $message = $helper->__('To add users you must create a record in the Apdh solution - Tracking user');
        $html = '<div id="'.$element->getHtmlId().'" style="margin-bottom:20px; padding:10px 5px 5px 5px; ">'
            .$message.'</div>';

        return $html; 
        
    }
}
