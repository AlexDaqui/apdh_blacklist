<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'blacklist';
        $this->_controller = 'adminhtml_tracking';
        
        $this->_updateButton('save', 'label', Mage::helper('blacklist')->__('Save Tracking'));
        $this->_updateButton('delete', 'label', Mage::helper('blacklist')->__('Delete Tracking'));

        $array = array (
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save'
        );
        $this->_addButton('saveandcontinue', $array, -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('tracking_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'tracking_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'tracking_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('tracking_data') && Mage::registry('tracking_data')->getId()) {
            return Mage::helper('blacklist')->__("Edit User '%s'", Mage::registry('tracking_data')->getUserId());
        } else {
            return Mage::helper('blacklist')->__('Add Tracking');
        }
    }
}
