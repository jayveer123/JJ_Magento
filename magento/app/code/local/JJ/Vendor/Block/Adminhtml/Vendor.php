<?php
class JJ_Vendor_Block_Adminhtml_Vendor Extends Mage_Adminhtml_Block_Widget_Grid_Container {

	public function __construct() {
		$this->_controller = 'adminhtml_vendor';
		$this->_blockGroup = 'vendor';
		$this->_headerText = 'Manage vendor';
		parent::__construct();
	}

}