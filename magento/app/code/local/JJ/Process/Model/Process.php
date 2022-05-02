<?php
    class JJ_Process_Model_Process extends Mage_Core_Model_Abstract
    {
        CONST TYPE_IMPORT = 1;
        CONST TYPE_EXPORT = 2;
        CONST TYPE_CORN = 3;
        protected $headers = [];
        protected $filedData = [];
        protected $invalidDatas = [];
        protected $requiredFiled = [];
        protected $processColumn = [];

        public function getRequiredFiled()
        {
            if($this->requiredFiled){
                return $this->requiredFiled;
            }
            $model = Mage::getModel('process/column');
            $columnModel = Mage::getModel('process/column');
            $select = $columnModel->getCollection()
                    ->getSelect()
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->columns(['name','required','castingType'])
                    ->where('process_id = ?', $this->getProcessId())
                    ->where('required = ?', 1);
            $data = $columnModel->getResource()->getReadConnection()->fetchAll($select);
            $this->requiredFiled = $data;
            return $this->requiredFiled;
        }

        public function getProcessColumn()
        {
            if($this->processColumn){
                return $this->processColumn;
            }
            $model = Mage::getModel('process/column');
            $columnModel = Mage::getModel('process/column');
            $select = $columnModel->getCollection()
                    ->getSelect()
                    ->where('process_id = ?', $this->getProcessId());
            $data = $columnModel->getResource()->getReadConnection()->fetchAll($select);
            $this->processColumn = array_combine($this->getHeaders(),$data);
            return $this->processColumn;
        }

        public function setHeaders($headers)
        {
            $this->headers = $headers;
            return $this;
        }

        public function getHeaders()
        {
            if($this->headers){
                return $this->headers;
            }
            return null;
        }

        public function setFiledDatas($filedData)
        {
            $this->filedData = $filedData;
            return $this;
        }

        public function getFiledDatas()
        {
            if($this->filedData){
                return $this->filedData;
            }
            return null;
        }

        public function addFiledData($filedData,$key)
        {
            $this->filedData[$key] = $filedData;
            return $this;
        }

        public function getFiledData($key)
        {
            if($this->filedData[$key]){
                return $this->filedData[$key];
            }
            return null;
        }

        public function removeFiledData($key)
        {
            if($this->filedData[$key]){
               unset($this->filedData[$key]);
            }
            return $this;
        }

        public function setInvalidDatas($invalidDatas)
        {
            $this->invalidDatas = $invalidDatas;
            return $this;
        }

        public function getInvalidDatas()
        {
            if($this->invalidDatas){
                return $this->invalidDatas;
            }
            return null;
        }

        public function addInvalidData($invalidData)
        {
            $this->invalidDatas[] = $invalidData;
            return $this;
        }

        protected function _construct()
        {
            $this->_init('process/process');
        }

        public function uploadFile()
        {
            $fileName = $this->getData()['fileName'];
            $uploder = new Varien_File_Uploader('fileName');
            $uploder->setAllowCreateFolders(true)
            ->setAllowRenameFiles(true)
            ->setAllowedExtensions(['csv'])
            ->save($this->getFilePath(),$fileName);

            return $this;
        }

        public function getFilePath()
        {
            return Mage::getBaseDir('media'). DS . 'process'. DS .'import';
        }

        protected function validateHeaders()
        {
            try {
                $processColumn = $this->getProcessColumn();
                $requiredFiled = array_column($this->getRequiredFiled(),'name');
                foreach ($requiredFiled as $key => $header) {
                    if(!array_key_exists($header,$processColumn)){
                        throw new Exception($header." Not in header.", 1);
                    }
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($e->getMessage()));
            }
        }

        protected function validateData()
        {
            try {
                if(!$this->getFiledDatas()){
                    throw new Exception("No Data availabel in file", 1);
                }
                foreach ($this->getFiledDatas() as $key => $row) {
                    $valid = $this->validateRow($row);
                    if(in_array('Invalid',$valid)){
                        $this->removeFiledData($key);
                        $this->addInvalidData($valid);
                    }
                    else{
                        $this->addFiledData($valid,$key);
                    }
                }
                //print_r($this->addInvalidData($valid));
                
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($e->getMessage()));
            }
        }

        protected function validateRow($row)
        {
            foreach ($row as $key => $value) {
                try {
                    if($key == 'index'){
                        continue;
                    }
                    $row[$key] = $this->validateRowData($value,$this->getProcessColumn()[$key]['castingType'],$this->getProcessColumn()[$key]['required']);
                } catch (Exception $e) {

                    $row[$key] = $e->getMessage();
                }
            }
            return $row;
        }

        protected function validateRowData($value,$castingType,$required)
        {
            if($required == 1){
                if($value == ""){
                    throw new Exception("Invalid", 1);
                }
                if($castingType == 1){
                    if(!$value = (int)$value){
                        throw new Exception("Invalid", 1);
                    }
                    return $value;
                }
                elseif($castingType == 3){
                    if(!$value = (string)$value){
                        throw new Exception("Invalid", 1);
                    }
                    return $value;
                }           
            }
            else{
                if($value == ""){
                    return $value;
                }
                if($castingType == 1){
                    if(!$value = (int)$value){
                        return '';
                    }
                    return $value;
                }
                elseif($castingType == 3){
                    if(!$value = (string)$value){
                        return '';
                    }
                    return $value;
                }           
            }
        }

        protected function readFile()
        {
            $filePathName = $this->getFilePath(). DS . $this->getData()['fileName'];
            $handler = fopen($filePathName, "r");
            $headerFlag = false;
            $data = [];
            while ($row = fgetcsv($handler)) 
            {        
                if($headerFlag == false){
                    $this->setHeaders($row);
                    $this->validateHeaders();
                    $headerFlag = true;
                }
                else{
                    $data[] = array_combine($this->getHeaders(), $row); 
                }
            }
            $this->setFiledDatas($data);
        }

        public function verify()
        {
            $this->readFile();
            $this->validateData();
            $this->processEntry();
            $this->genrateInvalidDataReport();//->saveData()Varien_File_Csv
            $this->genrateEntries();//->saveData()Varien_File_Csv 
            return true;          
        }

        protected function processEntry()
        {
            foreach ($this->getFiledDatas() as $key => $row) {
                $identifier = $this->getIdentifier($row);
                $row = $this->addFiledData($this->getJsonData($row),$key);
                $entryModel = Mage::getModel('process/entry');
                $select = $entryModel->getCollection()
                    ->getSelect()
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->columns(['process_id'])
                    ->where('process_id = ?', $this->getProcessId())
                    ->where('identifier = ?', $identifier);
                $data = $entryModel->getResource()->getReadConnection()->fetchAll($select);
                if(!$data){
                    $entryModel->setData('process_id',$this->getProcessId());
                    $entryModel->setData('identifier',$identifier);
                    $entryModel->setData('startTime',date('h:s:i'));
                    $entryModel->setData('data',$this->getFiledData($key));
                    $entryModel->setData('createdDate',date('Y-m-d h:s:i'));
                    $entryModel->save();
                }
            }
            return true;
            //data loop
            //call method prepareJsonData
            //getIdentifier method call
        }

        protected function getIdentifier($row)
        {
            return $row['name'];
        }

        protected function getJsonData($row)
        {
            return json_encode($row);
        }

        protected function genrateInvalidDataReport()
        {
            $invalid = [];
            $invalid[] = $this->getHeaders();
            $data = $this->getInvalidDatas();

            foreach ($data as $key => $row) {
                $invalid[] = $row;
            }
            $csv = new Varien_File_Csv();
            $csv->saveData($this->getFilePath(). DS . 'invalid.csv',$invalid);

        }

        protected function genrateEntries()
        {
            $entries = [];
            $entries[] = $this->getHeaders();
            $entryModel = Mage::getModel('process/entry');
            $select = $entryModel->getCollection()
            ->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns(['data'])
            ->where('process_id = ?', $this->getProcessId());
            $data = $entryModel->getResource()->getReadConnection()->fetchAll($select);
            foreach ($data as $key => $row) {
                $entries[] = json_decode($row['data']);
            }
            $csv = new Varien_File_Csv();
            $csv->saveData($this->getFilePath(). DS . 'entries.csv',$entries);
            return $entries;
        }
        
        public function getDefaultFile()
        {
            $columnModel = Mage::getModel('process/column');
            $select = $columnModel->getCollection()
                ->getSelect()
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns(['name','sampleData','required'])
                ->where('process_id = ?', $this->getProcessId());
            $data = $columnModel->getResource()->getReadConnection()->fetchAll($select);
            $finalData = [array_column($data,'name'),array_column($data,'sampleData'),$this->getRequired(array_column($data,'required'))];
            $csv = new Varien_File_Csv();
            $csv->saveData($this->getFilePath(). DS . 'sample.csv',$finalData);
            return true;
        }

        public function getRequired($values)
        {
            foreach ($values as $key => $value) {
                if($value == 1){
                    $values[$key] = 'Required';
                }
                else{
                    $values[$key] = 'Not Required';
                }
            }
            return $values;
        }
    }
?>