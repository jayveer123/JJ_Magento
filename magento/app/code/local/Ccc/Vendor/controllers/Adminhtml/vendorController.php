<?php 
class Ccc_Vendor_Adminhtml_vendorController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->_title($this->__('Vendors'))->_title($this->__('Vendor Groups'));

        $this->loadLayout();
        $this->_setActiveMenu('vendor/group');
        $this->_addBreadcrumb(Mage::helper('vendor')->__('Vendors'), Mage::helper('vendor')->__('Vendors'));
        $this->_addBreadcrumb(Mage::helper('vendor')->__('Vendor Groups'), Mage::helper('vendor')->__('Vendor Groups'));
        $this->renderLayout();
	}
	public function editAction()
    {
        $vendorId = $this->getRequest()->getParam('id');

        $vendorModel = Mage::getModel('vendor/vendor')->load($vendorId);

        if ($vendorModel->getId() || $vendorId == 0) {

           Mage::register('vendor_data', $vendorModel);

           $this->loadLayout();
            $this->_setActiveMenu('vendor/vendor');

           $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Vendor Information'), Mage::helper('adminhtml')->__('Vendor Information'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Data News'), Mage::helper('adminhtml')->__('Data News'));

           $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

           $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendor_edit'))
            ->_addLeft($this->getLayout()->createBlock('vendor/adminhtml_vendor_edit_tabs'));

           $this->renderLayout();
        } 
     	else{
          	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vendor')->__('Item does not exist'));
           	$this->_redirect('*/*/');
     	}
    }

    public function newAction()
    {
    	$this->_forward('edit');
    }

    public function saveAction()
    {
    	if ( $this->getRequest()->getPost() ) {
	      	try {
	      		$postData = $this->getRequest()->getPost();
	      		$vendorModel = Mage::getModel('vendor/vendor');


				$vendorModel->setId($this->getRequest()->getParam('id'))
				->setName($postData['name'])
				->setEmail($postData['email'])
				->setMobile($postData['mobile'])
				->save();

		    	Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Data was successfully saved'));
		     	Mage::getSingleton('adminhtml/session')->setVendorData(false);

		    	$this->_redirect('*/*/');
		      	return;
	      	}catch (Exception $e){
	      		Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	      		Mage::getSingleton('adminhtml/session')->setVendorData($this->getRequest()->getPost());
	      		$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
	      		return;
	      	}
    	}
      	$this->_redirect('*/*/');
    }

    public function deleteAction()
    {
    	if( $this->getRequest()->getParam('id') > 0 ) {
      		try {
      			$vendorModel = Mage::getModel('vendor/vendor');

     			$vendorModel->setId($this->getRequest()->getParam('id'))->delete();

     			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Data was successfully deleted'));
      			$this->_redirect('*/*/');
      			} catch (Exception $e) {
      				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      		}
      	}
      	$this->_redirect('*/*/');
    }

    public function gridAction()
    {
    	$this->loadLayout();
      	$this->getResponse()->setBody(
      	$this->getLayout()->createBlock('vendor/adminhtml_pro_grid')->toHtml());
    }

}