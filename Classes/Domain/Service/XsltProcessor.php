<?php       
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Nicolas Forgerit <nicolas.forgerit@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This is the XSLTProcesscor's service class
 */  
class Tx_FourOut_Domain_Service_XsltProcessor {
	
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
	public function injectInputData(DOMDocument $inp) {
		$this->_inputData = $inp;
	}                                                      

	/**
	 * This function simply injects the stylesheet data
	 * @param DOMDocument $xsls The stylesheet data injected into the XSLTProcessor service class
	 * @access public
	 */	
	public function injectStylesheet(DOMDocument $xsls) {
		$this->_styleSheet = $xsls;
	}
	
	public function setProfiler($logPath) {
	    $this->_profilerLogPath = $logPath;
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
?>
