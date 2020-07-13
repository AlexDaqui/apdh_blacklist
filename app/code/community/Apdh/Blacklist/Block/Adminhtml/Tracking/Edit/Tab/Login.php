<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking_Edit_Tab_Login extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customLoginGrid');
        $this->setUseAjax(true);
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        // Load your custom collection
        $id= $this->getRequest()->getParam('id');
        $collection = Mage::getModel("blacklist/trackinginfo")->getCollection();
        $collection->addFieldToFilter('type', array('eq'=>'login'));
        $collection->addFieldToFilter('tracking', array('eq'=>$id));

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            "created_time", array(
            "header" => Mage::helper('blacklist')->__("Date"),
            "index" => "created_time",
            'sortable' => false,
            'filter' => false
            )
        );

        $this->addColumn(
            "origin", array(
            "header" => Mage::helper('blacklist')->__("Http Agent"),
            "index" => "origin",
            'sortable' => false,
            'filter' => false
            )
        );

        $this->addColumn(
            "ip", array(
            "header" => Mage::helper('blacklist')->__("Ip"),
            "index" => "ip",
            'sortable' => false,
            'filter' => false
            )
        );

        $this->addColumn(
            "intent", array(
            "header" => Mage::helper('blacklist')->__("Intent"),
            "index" => "intent",
            'sortable' => false,
            'filter' => false,
            'renderer' => 'blacklist/adminhtml_tracking_renderer_html',
            )
        );

        $this->addColumn(
            "result", array(
            "header" => Mage::helper('blacklist')->__("Result"),
            "index" => "result",
            'sortable' => false,
            'filter' => false,
            'renderer' => 'blacklist/adminhtml_tracking_renderer_html',
            )
        );
        return parent::_prepareColumns();
    }
}
