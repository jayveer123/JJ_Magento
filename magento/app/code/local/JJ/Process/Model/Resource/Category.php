<?php
class JJ_Process_Model_Resource_Category extends Mage_Core_Model_Resource_Db_Abstract
{   
    protected function _construct()
    {      
        $this->_init('process/category','category_id');
    }

}

?>