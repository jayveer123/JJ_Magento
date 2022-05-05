<?php
class JJ_Process_Model_Resource_Page extends Mage_Core_Model_Resource_Db_Abstract
{   
    protected function _construct()
    {      
        $this->_init('process/page','page_id');
    }

}

?>