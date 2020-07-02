<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form
        ->addFieldset('tracking_form', array('legend'=>Mage::helper('blacklist')->__('User information')));

    $fieldset->addField(
        'user_id', 'select', array(
        'label'     => Mage::helper('blacklist')->__('User'),
        'class'     => 'required-entry',
        'required'  => true,
        'name'      => 'user_id',
        'values'    => Mage::getModel('blacklist/options_users')->getUsersOptions(),
        'after_element_html' => '<small>Select user.</small>',
        )
    );

    $fieldset->addField(
        'blocks_type', 'multiselect', array(
        'name'      => 'blocks_type',
        'class'     => '',
        'label'     => Mage::helper('blacklist')->__('Deny'),
        'required'  => false,
        'values'    => Mage::getModel('blacklist/options_blocks')->getBlocksOptions(),
        'after_element_html' => '<small>Choose block type user.</small>',
        )
    );

    $fieldset->addField(
        'created_time', 'label', array(
        'label'     => Mage::helper('blacklist')->__('Date creation')
        )
    );

    if (Mage::getSingleton('adminhtml/session')->getTrackingData()) {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getTrackingData());
      Mage::getSingleton('adminhtml/session')->setTrackingData(null);
    } elseif (Mage::registry('tracking_data')) {
      $form->setValues(Mage::registry('tracking_data')->getData());
    }

    return parent::_prepareForm();
  }
}
