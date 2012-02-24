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

class Tx_FourOut_ExportDataProvider {  
	  
	/**
	 * @var String (XML) This variable contains the XML data being transformed by this class.
	 */
	protected $_exportData;
	
	/**
	 * @var Array $hooks = array(
	* 						"functionname" => function($lines) { ...hook-functionality... }   
	* 					);
	* 
	**/
	protected $_transformHooks;  
	
	/**
	 * @var String (XSLT) The main XML data transformation XSLT stylesheet.
	 */
	protected $_xsltMasterStylesheet;
	
	/**
	 * @access public
	 * @param String $inpFilepath Sets the data inputfile
	 * @param String $xsltStylesheets Sets the path of the XSLT stylesheets which are used for the transformation
	 * @param String $phpHooksPath Sets the path of the file containing the PHP-Hooks which may alter the transformation process
	 * @throws Generic Exception
	 */
	public function __construct($inpFilepath = '', $xsltStylesheetsPath = '', $phpHooksPath = '') {    
       		       
		if ($inpFilepath == '') {     	
			throw new Exception("No Inputfile given.");
		}         
		
		if (! file_exists($inpFilepath)) {
			throw new Exception("Given file does not exist.");
		}    
		
		if (! isset($xsltStylesheetsPath)) {
			throw new Exception("No Stylesheet path given.");
		}               

		if (! isset($phpHooksPath)) {
			throw new Exception("No path containing PHP-Hooks given.");
		}
		
		
		
		$this->_exportData = file_get_contents($inpFilepath);   
		$this->_loadXSLTStylesheets($xsltStylesheetsPath);
		$this->_loadPHPHooks($phpHooksPath);      
		
		    echo "<pre>";
		      echo "<span style=\"background-color:#bada55\">";
		          var_dump($this);
		      echo "</span>";
		  echo "</pre>";
		  die("-- died in ".__FILE__.", line ".__LINE__);
		 
	}       
	
     
	/** 
	 * This classes method of "main purpose"
	 * @access public
	 */
	public function transform() {
		$this->_processPreTransformation();
		$this->_processMidTransformation();
		$this->_processPostTransformation();
	}          
	
	/**
	 * Register possibly given PHP-hooks to customize the transformation process.
	 * @access public
	 * @param Array ["pre", "mid", "post"] $hooks Array containing the registered lambda functions.
	 */ 
	public function registerTransformationHooks($hooks) {
		$this->_transformHooks = $hooks;
	}                                       
	
	/**
	 * This method calls the registered pre-transformation PHP-Hooks
	 * @access protected
	 */
	protected function _processPreTransformation() { 
		if (! is_array($this->_exportData)) {
			$lines = explode("\n", $this->_exportData);
		} else {
			$lines = $this->_exportData;
		}                               
		
		foreach ($this->_transformHooks["pre"] as $func_name => $func) {
			$outp = call_user_func($func, $lines);
		}                    
		
		if (! $outp)
			return;
		                                   
		if (is_array($outp)) {
			$outp = implode("\n", $outp);
		}                                 

		$this->_exportData = $outp;
	}                                                
	
	/**
	 * Calling the mid-transformation PHP-Hooks
	 * @access protected
	 */
	protected function _processMidTransformation() {
		/* THIS IS THE SWEET SPOT */               
		require_once t3lib_extMgm::extPath('four_out').'Classes/XSLTProcessor.php';
		$xsltProcessor = t3lib_div::makeInstance('Tx_FourOut_XSLTProcessor');    

		$stylesheetDOM = new DOMDocument;   
		$stylesheetDOM->loadXML($this->_xsltMasterStylesheet); 
		$xsltProcessor->injectStylesheet($stylesheetDOM);
 
   		$xmlDataDOM = new DOMDocument;
		$xmlDataDOM->loadXML($this->_exportData);
		$xsltProcessor->injectInputData($xmlDataDOM);
		
		$this->_exportData = $xsltProcessor->render();
	}                               
	
	/**
	 * Calling registered post-transformation PHP-Hooks
	 * @access protected
	 */
	protected function _processPostTransformation() {
		if (! is_array($this->_exportData)) {
			$lines = explode("\n", $this->_exportData);
		} else {
			$lines = $this->_exportData;
		}                               
		
		foreach ($this->_transformHooks["post"] as $func_name => $func) {
			$outp = call_user_func($func, $lines);
		} 

		if (! $outp)
			return;     
		                                   
		if (is_array($outp)) {
			$outp = implode("\n", $outp);
		}          
		    
		$this->_exportData = $outp;	
   	}
	
	/**
	 * This method provides access to the object's current export data.
	 * @access public
	 * @return String (XML) 
	 */
	public function getExportData() {   
		if (isset($this->_exportData)) {
			return $this->_exportData;
		}
	}            
	
	/**
	 * This is a proxy method encapsulating the loading of the PHP-Hooks. It's called by the constructor.
	 * @access protected
	 * @param String (FILEPATH) Path to the PHP-Hooks
	 */
	protected function _loadPHPHooks($phpHooksPath) {
		// currently simply hardcoded
		$realFile = $phpHooksPath.'master.php';
		$masterFile = include_once ($realFile);     
		$this->registerTransformationHooks($masterFile);
	}   
	
	/**
	 * This method is called by the constructor and encapsulates the loading of the XSLT stylesheets.
	 * @access protected
	 * @param String (FILEPATH) Path to the XSLT stylesheets
	 */
	protected function _loadXSLTStylesheets($xsltStylesheetsPath) {
		$dirListing = scandir($xsltStylesheetsPath);
		// we don't need the "dotted dirs"
		unset($dirListing[0]);
		unset($dirListing[1]);     
				    
		$contents = '';
		foreach ($dirListing as $filename) {
			$realFile = $xsltStylesheetsPath.$filename;
			$contents .= file_get_contents($realFile);
		}                    
		
        if ($contents !== '') {
			$this->_xsltMasterStylesheet = $contents;
		}                      
	}
	
}

?>