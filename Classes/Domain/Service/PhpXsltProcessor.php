<?php

class Tx_T3tt_Domain_Service_PhpXsltProcessor extends Tx_T3tt_Domain_Service_AbstractXsltProcessor {

	/**
	 * The input data feeded to the XSLT processor
	 *
	 * @var string (XML) $_inputdata
	 */
	private $_inputData;

	/**
	 * The resulting output data of the XSLT transformation
	 *
	 * @var string (XML) $_outputData
	 */
	private $_outputData;

	/**
	 * The XSLT stylesheet used by the XSLT processor
	 *
	 * @var string (XSLT) $_styleSheet
	 */
	private $_styleSheet;

	/**
	 * @var string
	 */
	private $_profilerLogPath;

	/**
	 * This function simply injects the input data
	 *
	 * @param DOMDocument $inp The input data injected into the XSLTProcessor service class
	 * @return Tx_T3tt_Domain_Service_PhpXsltProcessor
	 */
	public function setInputData(DOMDocument $inp) {
		$this->_inputData = $inp;

		return $this;
	}

	/**
	 * This function simply injects the stylesheet data
	 *
	 * @param DOMDocument $xsls The stylesheet data injected into the XSLTProcessor service class
	 * @return Tx_T3tt_Domain_Service_PhpXsltProcessor
	 */
	public function setStylesheet(DOMDocument $xsls) {
		$this->_styleSheet = $xsls;

		return $this;
	}

	/**
	 * @param $logPath
	 * @return Tx_T3tt_Domain_Service_PhpXsltProcessor
	 */
	public function setProfiler($logPath) {
		$this->_profilerLogPath = $logPath;

		return $this;
	}

	/**
	 * The main function of this object which simply renders the accumulated data using PHP's XSLTProcessor
	 *
	 * @return string
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