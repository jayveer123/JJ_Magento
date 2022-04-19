
<?php
 class Ccc_Vendor_Block_Adminhtml_Vendor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('vendor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('vendor')->__('Vendor Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
         'label' => Mage::helper('vendor')->__('Vendor Data'),
         'name' => Mage::helper('vendor')->__('Name'),
         'email' => Mage::helper('vendor')->__('Email'),
         'mobile' => Mage::helper('vendor')->__('Mobile'),
         'content' => $this->getLayout()->createBlock('vendor/adminhtml_vendor_edit_tabs_form')->toHtml(),
         ));

        return parent::_beforeToHtml();
    }
 }