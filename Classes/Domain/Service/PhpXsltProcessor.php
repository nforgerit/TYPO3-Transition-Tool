<?php

class Tx_T3tt_Domain_Service_PhpXsltProcessor extends Tx_T3tt_Domain_Service_AbstractXsltProcessor {

    	/**
    	 * The input data feeded to the XSLT processor
    	 * @var String (XML) $_inputdata
    	 */
    	private $_inputData;

    	/**
    	 * The resulting output data of the XSLT transformation
    	 * @var String (XML) $_outputData
    	 */
    	private $_outputData;

    	/**
    	 * The XSLT stylesheet used by the XSLT processor
    	 * @var String (XSLT) $_styleSheet
    	 */
    	private $_styleSheet;

    	private $_profilerLogPath;

    	/**
    	 * This function simply injects the input data
    	 * @param DOMDocument $inp The input data injected into the XSLTProcessor service class
    	 * @access public
    	 */
    	public function setInputData(DOMDocument $inp) {
    		$this->_inputData = $inp;
    		return $this;
    	}                                                      

    	/**
    	 * This function simply injects the stylesheet data
    	 * @param DOMDocument $xsls The stylesheet data injected into the XSLTProcessor service class
    	 * @access public
    	 */	
    	public function setStylesheet(DOMDocument $xsls) {
    		$this->_styleSheet = $xsls;
    		return $this;
    	}

    	public function setProfiler($logPath) {
    	    $this->_profilerLogPath = $logPath;
    	    return $this;
    	}

    	/**
    	 * The main function of this object which simply renders the accumulated data using PHP's XSLTProcessor
    	 * @access public
    	 */
    	public function render() {
    		try {
    			$xsltproc = new \XSLTProcessor;
    			$xsltproc->importStylesheet($this->_styleSheet);

    			if (isset($this->_profilerLogPath)) {
    			    $xsltproc->setProfiling($this->_profilerLogPath);
    			}

    			return $xsltproc->transformToXML($this->_inputData);			
    		} catch (Exception $e) {
    			echo "Could not process the Datafile with the given Stylesheet: " . $e->getMessage();
    		}
    	}

    }