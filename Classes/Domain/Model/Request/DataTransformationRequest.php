<?php

class Tx_Zeitenwende_Domain_Model_Request_DataTransformationRequest {

   protected $_paramsInserted = FALSE;
   
   protected $_params = array();
   
   public function insertParams(Array $params) {
       $this->_params['inputDataFile'] = isset($params['inputDataFile']) ? $params['inputDataFile'] : 'Output.xml';                        
       $this->_params['usedPhpHooks'] = isset($params['usedPhpHooks']) ? $params['usedPhpHooks'] : array();
       $this->_params['ctypeConfiguration'] = isset($params['ctypeConfiguration']) ? $params['ctypeConfiguration'] : array();        
       $this->_paramsInserted = TRUE;

       /* TODO:
        params: - 
       */
       return self;
   }
    
    public function getInputDatafile() {
        if (! $this->_paramsInserted) {
            throw new Tx_Zeitenwende_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the Input Datafile.");
        }
        return (string) $this->_params['inputDataFile'];
    }
    
    public function getUsedPhpHooks() {
        if (! $this->_paramsInserted) {
            throw new Tx_Zeitenwende_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the information about the used PhpHooks.");
        }
        
        return (array) $this->_params['usedPhpHooks'];
    }
    
    public function getCTypeConfiguration() {
        if (! $this->_paramsInserted) {
            throw new Tx_Zeitenwende_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the Content Type Configuration.");
        }

        return (array) $this->_params['ctypeConfiguration'];
    }
}