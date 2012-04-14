<?php

class Tx_T3tt_Domain_Model_Request_DataExportRequest {
    
    protected $_paramsInserted = FALSE;
    
    protected $_params = array();
    
    public function insertParams(Array $params) {
        $this->_params['dataFileName'] = isset($params['dataFileName']) ? $params['dataFileName'] : 'Input.xml';                        
        $this->_params['initNode'] = (! isset($params['initNode']) || $params['initNode'] < 0) ? 0 : intval($params['initNode']);
        $this->_params['involvedTables'] = isset($params['involvedTables']) ? (array)$params['involvedTables'] : array('tt_content','pages');        
        $this->_params['EMCONF'] = isset($params['EMCONF']) ? (array)$params['EMCONF'] : array();        
        $this->_paramsInserted = TRUE;

        return self;
    }
    
    public function getEmConf(string $key) {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the tables.");
        }
        
        if (! isset($this->_params['EMCONF'][$key]) || strlen($this->_params['EMCONF']) === 0 ) { 
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Expected value of key `".$key."' does not exist.");
        }
        return (string) $this->_params['EMCONF'][$key];
    }
    
    public function getTables() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the tables.");
        }
        return (array) $this->_params['involvedTables'];
    }
    
    public function getOutputFile() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the outputFile.");
        }
        
        return (string) $this->_params['dataFileName'];
    }
    
    public function getInitNode() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the initNode.");
        }

        return (int) $this->_params['initNode'];
    }
}