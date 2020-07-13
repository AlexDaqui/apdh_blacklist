<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Adminhtml_ReportController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('blacklist');
    }
}