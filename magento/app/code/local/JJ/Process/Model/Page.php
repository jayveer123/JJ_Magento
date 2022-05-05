<?php
class JJ_Process_Model_Page extends JJ_Process_Model_Process_Abstract
{

    protected function _construct()
    {
        $this->_init('process/page');
    }

    public function getIdentifier($row)
    {
        return $row['name'];
    }

    public function prepareRow($row)
    {
        return [
            'name' => $row['name'],
            'email' => $row['email'],
            'mobile' => $row['mobile'],
        ];
    }

    

    public function validateRow($row)
    {
        return $row;
    }


}

