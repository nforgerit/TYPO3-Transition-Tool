<?php

class Tx_T3tt_Domain_Model_Data_Transformator {
    
    protected $_requestInserted = FALSE;
    
    protected $_dataTransformed = FALSE;
    
    protected $_transformationRequest = NULL;
    
    protected $_phpHookAggregation = array();
    
    protected $_xsltStylesheet = NULL;
    
    protected $_xsltProcessor = NULL;
    
    protected $_data = NULL;
    
    
    public function setRequest(Tx_T3tt_Domain_Model_Request_DataTransformationRequest $transformationRequest) {
        $this->_transformationRequest = $transformationRequest;
        $this->_requestInserted = TRUE;
        return $this;
    }
     
    public function exec() {        
        if (! $this->_requestInserted) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Missing the transformation request. You need to inject it via setRequest().");
        }
        $this->prepare();
        $this->transform();
        $this->_dataTransformed = TRUE;
        return $this;
    }
        
    public function getData() {
        if (! $this->_dataTransformed) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidParamsException("Transformation was never executed. You need to call exec() first.");
        }

        return (string) $this->_data;
    }
    
    public function writeDataToOutputFile() {
        $outputFile = t3lib_extMgm::extPath('t3tt').'Resources/Private/Data/Output.xml';
        $returnValue = file_put_contents($outputFile, $this->getData());
        
        if ($returnValue > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    private function prepare() {
        $this->fetchInitialData();
        $this->setXsltProcessor();
        $this->fetchXsltStylesheet();
        $this->substituteMarkersWithValues();
        $this->fetchPhpHooks();
    }
    
    private function transform() {
        $this->execPhpHooks('pre');    
        $this->execXsltTransformation();
        $this->execPhpHooks('post');
    }
    
    private function fetchInitialData() {
        $filedir = t3lib_extMgm::extPath('t3tt').'Resources/Private/Data/'.$this->_transformationRequest->getInputDatafile();
        if (! file_exists($filedir)) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidParamsException("Given Input data file `".$filedir."' does not exist.");
        }
        
        $this->_data = file_get_contents($filedir);
    }
    
    private function setXsltProcessor() {
        $xsltProcessorClass = "Tx_T3tt_Domain_Service_".$this->_transformationRequest->getXsltProcessor();
        
        if (! class_exists($xsltProcessorClass)) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidParamsException("Given XSLT Processor class does not exist.");
        }
        
        $this->_xsltProcessor = new $xsltProcessorClass;
    }
    
    private function fetchXsltStylesheet() {
        $compositor = new Tx_T3tt_Domain_Model_Xslt_StylesheetComposition;
        $this->_xsltStylesheet = $compositor
            ->setXsltStylesheetAggregation(new Tx_T3tt_Domain_Model_Xslt_StylesheetAggregation)
            ->setBaseXsltStylesheet(t3lib_extMgm::extPath('t3tt').'Resources/Private/XSLT/Stylesheets/master.xsl') // TODO: make this configurable
            ->getCompound();
            
        if (! $this->_xsltStylesheet) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidXsltStylesheetContentsException("Constructing XSLT stylesheet failed :(");
        }
    }
    
    private function fetchPhpHooks() {
        $hooksFileBasepath = t3lib_extMgm::extPath('t3tt').'Resources/Private/PHP/Transition/' ;// TODO: make this configurable
		$hooksFile = $hooksFileBasepath.$this->_transformationRequest->getPhpHooksFile();
    	$this->_phpHookAggregation = (array) include_once ($hooksFile); 
    }
    
    private function execPhpHooks($category) {
        if (empty($this->_phpHookAggregation[$category])) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidParamsException("Given category `".$category."' does not exist in PHP Hooks array.");
        }
        
        if (! is_array($this->_data)) {
			$lines = explode("\n", $this->_data);
		} else {
			$lines = $this->_data;
		}                               
		
		foreach ($this->_phpHookAggregation[$category] as $func_name => $func) {
			$output = call_user_func($func, &$lines);
		}                    
		
        if (! $output) return;
		                                   
		if (is_array($output)) {
		    // TODO: \n may be critical concerning system independency, see how F3 handles that (some chr() stuff)
			$output = implode("\n", $output); 
		}                                 

		$this->_data = $output;
    }
    
    private function execXsltTransformation() {
        $stylesheetDomObj = DOMDocument::loadXML($this->_xsltStylesheet);
        if (! $stylesheetDomObj) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Assembled stylesheet could not be transformed into a DOMDocument.");
        }
        
        $dataDomObj = DOMDocument::loadXML($this->_data);
        if (! $dataDomObj) {
            throw new Tx_T3tt_Domain_Model_Exception_NoParamsException("Prepared data could not be transformed into a DOMDocument.");
        }
        
        /* THIS IS THE SWEET SPOT */               
		$this->_data = $this->_xsltProcessor
		    ->setStylesheet($stylesheetDomObj)
            ->setInputData($dataDomObj)
            ->render();
    }
    
    private function substituteMarkersWithValues() {
        $markerArray = $this->_transformationRequest->getMarkerData();
        foreach ($markerArray as $marker => $value) {
            $marker = "<!-- {$marker} -->";
            $this->_xsltStylesheet = str_replace($marker, $value, $this->_xsltStylesheet);            
        }
    }
    
    

}