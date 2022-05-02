<?php

class JJ_Category_Block_Adminhtml_Category_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        
        parent::__construct();
        $this->setId('category_index');
        $this->setDefaultSort('type');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Init category groups collection
     * @return void
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('category/category_collection');
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
        $this->addColumn('category_id', array(
            'header' => Mage::helper('category')->__('ID'),
            'width' => '50px',
            'align' => 'right',
            'index' => 'category_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('category')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('path', array(
            'header' => Mage::helper('category')->__('Path'),
            'index' => 'path',
        ));

        $this->addColumn('status', array(
          'header'    => Mage::helper('category')->__('status'),
          'index'     => 'status',
          'type'      => 'options',
          'options'    => array(
                1 => 'Active',
                2 => 'Inactive'
            ),
      ));

        $this->addColumn('created_date', array(
            'header' => Mage::helper('category')->__('Created Date'),
            'index' => 'created_date',
        ));

        $this->addColumn('updated_date', array(
            'header' => Mage::helper('category')->__('Updated Date'),
            'index' => 'updated_date',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}
