<?php

class Tx_Zeitenwende_Domain_Model_Data_Transformator {
    
    protected $_transformationRequest = NULL;
    
    protected $_phpHookAggregation = array();
    
    protected $_xsltStylesheet = NULL;
    
    protected $_xsltProcessor = NULL;
    
    protected $_data = NULL;
    
    /**
     * @param $phpHooks Array
     * @return Tx_Zeitenwende_Domain_Model_Data_Transformator
     */
    public function setRequest(Tx_Zeitenwende_Domain_Model_Request_DataTransformationRequest $transformationRequest) {
        $this->_transformationRequest = $transformationRequest;
        return $this;
    }
    
    /**
     * @param $phpHooks Array
     * @return Tx_Zeitenwende_Domain_Model_Data_Transformator
     */
    public function setPhpHooks(Array $phpHooks) {
        $this->_phpHooks = $phpHooks;
        return $this;
    }
    
    /**
     * @param Tx_Zeitenwende_Domain_Model_XsltStylesheet $xsltStylesheet
     * @return Tx_Zeitenwende_Domain_Model_Data_Transformator
     */
    public function setXsltStylesheet(Tx_Zeitenwende_Domain_Model_XsltStylesheet $xsltStylesheet) {
        $this->_xsltStylesheet = $xsltStylesheet;
        return $this;
    }

    /**
     * @param Tx_Zeitenwende_Domain_Model_Service_AbstractXsltProcessor $xsltProcessor
     * @return Tx_Zeitenwende_Domain_Model_Data_Transformator
     */    
    public function setXsltProcessor(Tx_Zeitenwende_Domain_Model_Service_AbstractXsltProcessor $xsltProcessor) {
        $this->_xsltProcessor = $xsltProcessor;
        return $this;
    }

    /**
     * @param DOMDocument $data
     * @return Tx_Zeitenwende_Domain_Model_Data_Transformator
     */
    public function setData(DOMDocument $data) {
        $this->_data = $data;
        return $this;
    }
     
    public function exec() {}
        
    public function getData() {
        return $this->_data;
    }
    

}