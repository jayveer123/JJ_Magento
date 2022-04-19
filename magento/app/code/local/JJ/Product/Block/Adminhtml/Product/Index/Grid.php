<?php

class JJ_Product_Block_Adminhtml_Product_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        
        parent::__construct();
        $this->setId('product_index');
        $this->setDefaultSort('type');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('product/product_collection');
        foreach ($collection->getItems() as $col)
        {
            $col->path = $col->getPath();
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Configuration of grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('product_id', array(
            'header' => Mage::helper('product')->__('ID'),
            'width' => '50px',
            'align' => 'right',
            'index' => 'product_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('product')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('price', array(
            'header' => Mage::helper('product')->__('Price'),
            'index' => 'price',
        ));

        $this->addColumn('costPrice', array(
            'header' => Mage::helper('product')->__('CostPrice'),
            'index' => 'costPrice',
        ));

        $this->addColumn('quantity', array(
            'header' => Mage::helper('product')->__('Quantity'),
            'index' => 'quantity',
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('product')->__('Sku'),
            'index' => 'sku',
        ));

        $this->addColumn('created_date', array(
            'header' => Mage::helper('product')->__('Created Date'),
            'index' => 'created_date',
        ));

        $this->addColumn('updated_date', array(
            'header' => Mage::helper('product')->__('Updated Date'),
            'index' => 'updated_date',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}
