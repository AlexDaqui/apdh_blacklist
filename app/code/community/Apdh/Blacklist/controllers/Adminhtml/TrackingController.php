<?php
/*
* Author: Alex Patricio Daqui Hernandez
* Web page: https://www.apdh.es
*/
class Apdh_Blacklist_Adminhtml_TrackingController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('tracking/items')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager')
            );

        return $this;
    }
 
    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function editAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('blacklist/tracking')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('tracking_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('tracking/items');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('blacklist/adminhtml_tracking_edit'))
                ->_addLeft($this->getLayout()->createBlock('blacklist/adminhtml_tracking_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blacklist')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
 
    public function newAction()
    {
        $this->_forward('edit');
    }
 
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('blacklist/tracking');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime() == NULL) {
                    $model->setCreatedTime(now());
                }

                if (!empty($data['blocks_type'])) {
                    $types = implode(",", $data['blocks_type']);
                    $model->setBlocksType($types);
                }

                $this->saveItem($model);
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('blacklist')->__('Item was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }

                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blacklist')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('blacklist/tracking');

                $model->setId($this->getRequest()->getParam('id'));
                $this->deleteItem($model);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Item was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }

        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $trackingIds = $this->getRequest()->getParam('blacklist');
        if (!is_array($trackingIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($trackingIds as $trackingId) {
                    $tracking = $this->getItem($trackingId);
                    $this->deleteItem($tracking);
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($trackingIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');

    }

    public function massStatusAction()
    {
        $trackingIds = $this->getRequest()->getParam('tracking');
        if (!is_array($trackingIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($trackingIds as $trackingId) {
                    $track = $this->getItem($trackingId);
                    $track->setStatus($this->getRequest()->getParam('status'));
                    $track->setIsMassupdate(true);
                    $this->saveItem($track);
                }

                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($trackingIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function getLoginAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('tracking.edit.tab.login');
        $this->renderLayout();
    }

    public function getCheckoutAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('tracking.edit.tab.checkout');
        $this->renderLayout();
    }

    public function deleteItem($item)
    {
        $item->delete();
    }

    public function saveItem($item)
    {
        $item->save();
    }

    public function getItem($id)
    {
        return Mage::getSingleton('blacklist/tracking')->load($id);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('blacklist');
    }     
    
}
