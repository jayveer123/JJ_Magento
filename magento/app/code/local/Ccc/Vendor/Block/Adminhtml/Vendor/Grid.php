<?php

class Ccc_Vendor_Block_Adminhtml_Vendor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
 	{
		 parent::__construct();
		 $this->setId('vendorGrid');
		 $this->setDefaultSort('vendor_id');
		 $this->setDefaultDir('ASC');
		 $this->setSaveParametersInSession(true);
		 $this->setUseAjax(true);
 	}

	protected function _prepareCollection()
 	{
 		$collection = Mage::getModel('vendor/vendor')->getCollection();
		 $this->setCollection($collection);
		 return parent::_prepareCollection();
 	}

	protected function _prepareColumns()
 	{
		$this->addColumn('vendor_id', array(
		'header' => Mage::helper('vendor')->__('ID'),
		'align' =>'right',
		'width' => '50px',
		'index' => 'vendor_id',
		));

		$this->addColumn('name', array(
		 'header' => Mage::helper('vendor')->__('Vendor Name'),
		 'align' =>'left',
		 'index' => 'name',
		 ));

		$this->addColumn('email', array(
		 'header' => Mage::helper('vendor')->__('Email Id'),
		 'align' =>'left',
		 'index' => 'email',
		 ));

		$this->addColumn('mobile', array(
		 'header' => Mage::helper('vendor')->__('Mobile'),
		 'align' =>'left',
		 'index' => 'mobile',
		 ));

		return parent::_prepareColumns();
 	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	public function getGridUrl()
 	{
 		return $this->getUrl('*/*/grid', array('_current'=>true));
 	}
}