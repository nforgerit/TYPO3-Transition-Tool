<?php

class Tx_T3tt_Domain_Model_Data_Exporter {

    protected $_exportRequest = NULL;
    
    protected $_txImpexpInstance = NULL;
    
    protected $_exportData = NULL;

    public function setRequest(Tx_T3tt_Domain_Model_Request_DataExportRequest $exportRequest) {
        $this->_exportRequest = $exportRequest;
        return $this;
    }
    
    public function exec() {
        $this->instantiateTxImpexp();
        $this->instantiatePageTreeObj();
        
        foreach ($this->_exportRequest->getTables() as $tableName) {
            $this->prepareRecordsFrom($tableName);
        }
        
        $this->_exportData = $this->_txImpexpInstance->createXML();
        
        return $this;
    }
    
    public function getData() {
        return $this->_exportData;
    }
    
    public function writeDataToOutputFile() {
        $outputFile = t3lib_extMgm::extPath('t3tt').'Resources/Private/Data/'.$this->_exportRequest->getOutputFile();
        $returnValue = file_put_contents($outputFile, $this->getData());
        
        if ($returnValue > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    private function instantiateTxImpexp() {
        require_once (t3lib_extMgm::extPath('impexp').'class.tx_impexp.php');
        $this->_txImpexpInstance = t3lib_div::makeInstance('tx_impexp');
        $this->_txImpexpInstance->init();
    }
    
    private function instantiatePageTreeObj() {
        $pageTree = t3lib_div::makeInstance('t3lib_pageTree');
        $pageTree->init();
        $pageTree->getTree($this->_exportRequest->getInitNode());
        $idH = $pageTree->buffer_idH;
        $this->_txImpexpInstance->setPageTree($idH);
    }
    
    private function prepareRecordsFrom($table) {
        $tableRows = $this->getExportableRowUids($table);
        foreach ($tableRows as $uid => $a) {
            $this->_txImpexpInstance->export_addRecord($table, t3lib_BEfunc::getRecord($table, $uid));
        }    
    }
    
    private function getExportableRowUids($table, $returnDeleted = TRUE) {
        $select = 'uid';
        $from = $table;
        $where = '1';
        if (!$returnDeleted) {
            $where .= ' AND deleted=0';
        }
        
        $rows = array();
        $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $from, $where, '', '', '', 'uid');
        
        return $rows;
    }
}

