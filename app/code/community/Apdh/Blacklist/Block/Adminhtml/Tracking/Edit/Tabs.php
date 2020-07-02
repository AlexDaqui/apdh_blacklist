<?php
/*
* Author: Alex Daqui
* Web page: http://apdhsolution.com
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
    parent::__construct();
    $this->setId('tracking_tabs');
    $this->setDestElementId('edit_form');
    $this->setTitle(Mage::helper('blacklist')->__('Tracking Information'));
  }

  protected function _beforeToHtml()
  {
    $this->addTab(
        'form_section', array(
        'label'     => Mage::helper('blacklist')->__('Tracking Information'),
        'title'     => Mage::helper('blacklist')->__('Tracking Information'),
        'content'   => $this->getLayout()->createBlock('blacklist/adminhtml_tracking_edit_tab_form')->toHtml()
        )
    );

    $this->addTab(
        'login_section', array(
        'label'     => Mage::helper('blacklist')->__('Record login'),
        'title'     => Mage::helper('blacklist')->__('Record login'),
        'url'       => $this->getUrl('*/*/getLogin', array('_current' => true)),
        'class'     => 'ajax'
        )
    );

    $this->addTab(
        'checkout_section', array(
        'label'     => Mage::helper('blacklist')->__('Record checkout'),
        'title'     => Mage::helper('blacklist')->__('Record checkout'),
        'url'       => $this->getUrl('*/*/getCheckout', array('_current' => true)),
        'class'     => 'ajax'
        )
    );
    return parent::_beforeToHtml();
  }
}
