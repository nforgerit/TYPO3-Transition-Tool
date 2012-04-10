<?php

class Tx_T3tt_Domain_Model_Request_DataTransformationRequest {

   protected $_paramsInserted = FALSE;
   
   protected $_params = array();
   
   public function insertParams(Array $params) {
       $this->_params['inputDataFile'] = isset($params['inputDataFile']) ? $params['inputDataFile'] : 'Input.xml';                        
       $this->_params['phpHooksFile'] = isset($params['phpHooksFile']) ? $params['phpHooksFile'] : 'master.php';                        
       $this->_params['usedPhpHooks'] = isset($params['usedPhpHooks']) ? $params['usedPhpHooks'] : array();
       $this->_params['ctypeConfiguration'] = isset($params['ctypeConfiguration']) ? $params['ctypeConfiguration'] : array();        
       $this->_params['xsltProcessor'] = isset($params['xsltProcessor']) ? $params['xsltProcessor'] : 'PhpXsltProcessor';        
       $this->_params['xsltBaseStylesheet'] = isset($params['xsltBaseStylesheet']) ? $params['xsltBaseStylesheet'] : 'master.xsl';        
       $this->_paramsInserted = TRUE;

       return self;
   }
    
    public function getInputDatafile() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the Input Datafile.");
        }
        return (string) $this->_params['inputDataFile'];
    }
    
    public function getPhpHooksFile() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the PHP Hooksfile.");
        }
        return (string) $this->_params['phpHooksFile'];
    }    
    
    public function getUsedPhpHooks() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the information about the used PhpHooks.");
        }
        
        return (array) $this->_params['usedPhpHooks'];
    }
    
    public function getCTypeConfiguration() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the Content Type Configuration.");
        }

        return (array) $this->_params['ctypeConfiguration'];
    }
    
    public function getXsltProcessor() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the XSLT Processor Configuration.");
        }
        
        return (string) $this->_params['xsltProcessor'];
    }
    
    public function getXsltBaseStylesheet() {
        if (! $this->_paramsInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Use insertParams() before fetching the XSLT Base Stylesheet.");
        }
        
        return (string) $this->_params['xsltBaseStylesheet'];
    }
}