<?php

class Tx_T3tt_Domain_Model_Request_DataExportRequest {
    
    protected $_paramsInserted = FALSE;
    
    protected $_params = array();
    
    public function insertParams(Array $params) {
        $this->_params['dataFileName'] = isset($params['dataFileName']) ? $params['dataFileName'] : 'Input.xml';                        
        $this->_params['initNode'] = (! isset($params['initNode']) || $params['initNode'] < 0) ? 0 : intval($params['initNode']);
        $this->_params['involvedTables'] = isset($params['involvedTables']) ? (array)$params['involvedTables'] : array('tt_content','pages');        
        $this->_paramsInserted = TRUE;

        return self;
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