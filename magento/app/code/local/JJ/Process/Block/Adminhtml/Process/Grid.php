<?php

class JJ_Process_Block_Adminhtml_Process_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('processGrid');
        $this->setDefaultSort('type');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('process/process')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function getGroupOption()
    {
        $model = Mage::getModel('process/group');
        $select = $model->getCollection()
                ->getSelect()
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns(['group_id']);
        $option = $model->getResource()->getReadConnection()->fetchAll($select);
        $index = 0;
        $groupOption = [];
        foreach($option as $key => $value){
            $option = [$index => $value['group_id']];
            $index++;
            $groupOption = array_merge($option,$groupOption);
        }
        return $groupOption;
    }

    /**
     * Configuration of grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('process_id', array(
            'header' => Mage::helper('process')->__('ID'),
            'width' => '50px',
            'align' => 'right',
            'index' => 'process_id',
        ));

        $this->addColumn('group_id', array(
            'header' => Mage::helper('process')->__('Group Id'),
            'index' => 'group_id',
            'type' => 'options',
            'options' => $this->getGroupOption(),
        ));

        $this->addColumn('type_id', array(
            'header' => Mage::helper('process')->__('Type Id'),
            'index' => 'type_id',
            'type' => 'options',
            'options' => array(
                JJ_Process_Model_Process::TYPE_IMPORT => Mage::helper('process')->__('Import'),
                JJ_Process_Model_Process::TYPE_EXPORT => Mage::helper('process')->__('Export'),
                JJ_Process_Model_Process::TYPE_CORN => Mage::helper('process')->__('Corn'),
            ),
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('process')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('perRequestCount', array(
            'header' => Mage::helper('process')->__('Per Request Count'),
            'index' => 'perRequestCount',
        ));

        $this->addColumn('requestInterval', array(
            'header' => Mage::helper('process')->__('Request Interval'),
            'index' => 'requestInterval',
        ));

        $this->addColumn('requestModel', array(
            'header' => Mage::helper('process')->__('Request model'),
            'index' => 'requestModel',
        ));

        $this->addColumn('fileName', array(
            'header' => Mage::helper('process')->__('File Name'),
            'index' => 'fileName',
        ));

        $this->addColumn('createdDate', array(
            'header' => Mage::helper('process')->__('Created Date'),
            'index' => 'createdDate',
            'type' => 'date',
        ));

        $this->addColumn('upload', array(
            'header'    =>  Mage::helper('process')->__('Upload'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('process')->__('Upload'),
                    'url'       => array('base'=> '*/adminhtml_process_upload/uploadfile'),
                    'field'     => 'id'
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
    ));

    $this->addColumn('verify', array(
            'header'    =>  Mage::helper('process')->__('Verify'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('process')->__('Verify'),
                    'url'       => array('base'=> '*/adminhtml_process_upload/verify'),
                    'field'     => 'id'
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
    ));

    $this->addColumn('execution', array(
            'header'    =>  Mage::helper('process')->__('Execute'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('process')->__('Execute'),
                    'url'       => array('base'=> '*/adminhtml_process_upload/execute'),
                    'field'     => 'id'
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
    ));

    $this->addColumn('downloads', array(
            'header'    =>  Mage::helper('process')->__('Download'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('process')->__('Download'),
                    'url'       => array('base'=> '*/*/exportCsv'),
                    'field'     => 'id'
                ),
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
    ));

    //$this->addExportType('*/*/exportCsv', Mage::helper('process')->__('CSV'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}