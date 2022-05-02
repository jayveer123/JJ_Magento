<?php
class JJ_Process_Block_Adminhtml_Process_Upload extends Mage_Adminhtml_Block_Widget_Grid_Container{

    public function __construct()
    {
        $this->_controller = 'adminhtml_process';
        $this->_blockGroup = 'process';
        $this->_headerText = Mage::helper('process')->__('View Data');
        $this->_addButtonLabel = Mage::helper('process')->__('Add File');
        parent::__construct();
    }
}

?>