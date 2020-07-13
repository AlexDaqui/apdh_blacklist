<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Block_Adminhtml_Tracking_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('trackingGrid');
        $this->setDefaultSort('tracking_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('blacklist/tracking')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'tracking_id', array(
            'header'=> Mage::helper('blacklist')->__('Id tracking'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'tracking_id'
            )
        );

        $this->addColumn(
            'user_id', array(
            'header'=> Mage::helper('blacklist')->__('User'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'user_id',
            'renderer' => 'blacklist/adminhtml_tracking_renderer_email',
            'filter_condition_callback' => array($this, '_customEmailFilterCallBack')
            )
        );

        $this->addColumn(
            'created_time', array(
            'header'=> Mage::helper('blacklist')->__('Date creation'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'created_time',
            'type'      => 'datetime'
            )
        );

        $this->addColumn(
            'action', array(
            'header'    =>  Mage::helper('blacklist')->__('Action'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('blacklist')->__('View Details'),
                    'url'       => array('base'=> '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true
            )
        );

        return parent::_prepareColumns();
    }

    protected function _customEmailFilterCallBack($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if (!empty($value)) {
            $customerId = $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(1)
                ->loadByEmail($value)->getEntityId();
            if (!empty($customerId)) {
                $collection->addFieldToFilter('user_id', array('eq'=>$customerId));
            }
        }

        return $this;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tracking_id');
        $this->getMassactionBlock()->setFormFieldName('blacklist');
        $array = array(
            'label'    => Mage::helper('blacklist')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('blacklist')->__('Are you sure?')
        );

        $this->getMassactionBlock()->addItem('delete', $array);

        return $this;
    }

    public function getRowUrl($row)
    {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
