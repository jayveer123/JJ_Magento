<?php
class JJ_Process_Adminhtml_Process_UploadController extends Mage_Adminhtml_Controller_Action{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('process/group');
        $this->renderLayout();
    }

    public function uploadfileAction()
    {
        $processId = $this->getRequest()->getParam('id');
		Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));

		$process = Mage::getModel('process/process')
			->setStoreId($this->getRequest()->getParam('store', 0))
			->load($processId);

		Mage::register('current_process_media', $process);

		if (!$processId) {
			$this->_getSession()->addError(Mage::helper('process')->__('This process no longer exists'));
			$this->_redirect('*/*/');
			return;
		}
		$this->loadLayout();
		$this->renderLayout();
    }
    public function uploadAction()
    {
        $processId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('process/process');
        if($model->load($processId)){
            $fileName = $model->uploadFile($processId);
        }
        $this->_redirect('process/adminhtml_process/index');
    }

    public function verifyAction()
    {

        $processId = $this->getRequest()->getParam('id');
        $model = Mage::getModel('process/process')->load($processId);
        $model->verify();

        $this->_redirect('process/adminhtml_process/index');
    }

}

?>