<?php
class JJ_Process_Model_Resource_Column extends Mage_Core_Model_Resource_Db_Abstract
{   
    protected function _construct()
    {      
        $this->_init('process/column','column_id');
    }

}

?>