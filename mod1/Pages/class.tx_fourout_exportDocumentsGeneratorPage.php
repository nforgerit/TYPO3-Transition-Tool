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

class Tx_FourOut_ExportDocumentsGeneratorPage {                
	
	/** 
	* Constant values which are currently hardcoded but may be variable in future.
	**/
	
	/**
	 * The input file's basedir
	 */
	protected $_INPUT_BASEDIR;
	
	/**
	 * The output file's basedir
	 */
	protected $_OUTPUT_BASEDIR;           
	
	/** 
	 * The basedir of the PHP-Hooks
	 */
	protected $_PHP_HOOKS_BASEDIR;  
	
	/**
	 * The basedir of the XSLT stylesheets
	 */
	protected $_XSLT_STYLESHEETS_BASEDIR;     
	
	/**
	* Dynamic POST-injected values
	**/
	
	/**
	 * The input datafile's full filepath
	 */
	protected $_inputFilepath;

	/**
	 * The output datafile's full filepath
	 */
	protected $_outputFilepath;
		
	/**
	 * Possible error messages that may occur by the data transition
	 */
	protected $_errorMessage;       
	
	/**
	 * Pseudo-constants are for now simply set by the constructor
	 * @access public
	 */
	public function __construct() {
		$this->_INPUT_BASEDIR = PATH_site."typo3conf/ext/four_out/Resources/Private/InputData/";
		$this->_OUTPUT_BASEDIR = PATH_site."typo3conf/ext/four_out/Resources/Public/";
		$this->_PHP_HOOKS_BASEDIR = PATH_site."typo3conf/ext/four_out/Resources/Private/PHP_Hooks/";
		$this->_XSLT_STYLESHEETS_BASEDIR = PATH_site."typo3conf/ext/four_out/Resources/Private/XSLT_Stylesheets/";
	} 

	/**
	 * This method sanitizes the webuser's input data
	 * @access public 
	 */
	public function sanitizeRequest() {         
		// TODO: make this more secure!!
		$inpFilename = $_POST['SET']['tx_fouroutM1']['inpFilename'];  
		$outpFilename = $_POST['SET']['tx_fouroutM1']['outpFilename'];

		$inpFilepath = $this->_INPUT_BASEDIR.$inpFilename;
		if (! file_exists($inpFilepath)) {
			$this->_errorMessage = array( 
				"body" => "{$inpFilepath} does not exist.",
				"headline" => "Error when loading file",
				);
		}     
		           
		$outpFilepath = $this->_OUTPUT_BASEDIR.$outpFilename;
		if (! is_writable($outpFilepath)) {
			$this->_errorMessage = array(
				"body" => "{$outpFilepath} is not writable.",
				"headline" => "Erroneous output filepath.",
			);
		}
		      
		$this->_inputFilepath = $inpFilepath;            
		$this->_outputFilepath = $outpFilepath;
	}   
		
	/**
	 * This method generates the XML data according to all the given information
	 * @return Integer Return signal which is given by PHP's file_put_contents function
	 */
	public function generateXMLData() {      
		require_once t3lib_extMgm::extPath('four_out').'Classes/class.tx_fourout_ExportDataProvider.php';
		try {        	
			$dataProvider = new Tx_FourOut_ExportDataProvider(
				$this->_inputFilepath, 
				$this->_XSLT_STYLESHEETS_BASEDIR, 
				$this->_PHP_HOOKS_BASEDIR
				);
					    
			
			$dataProvider->transform();
			$transformedData = $dataProvider->getExportData();
			
			echo "<pre>";
  		      echo "<span style=\"background-color:#bada55\">";
  		          var_dump($transformedData);
  		      echo "</span>";
  		  echo "</pre>";
  		  die("-- died in ".__FILE__.", line ".__LINE__);
			
		} catch (Exception $e) {
			$this->_errorMessage = array(
				"body" => $e->getMessage(),
				"headline" => "Transformation failed",
				);
		}                    
		

		
		
		return file_put_contents(
			$this->_outputFilepath."_INACTIVE", 
			$transformedData
			);
	} 
		
	/**
	 * This method renders and returns the configuration form which can be used in the backend module
	 * @access public
	 * @return String (HTML)
	 */
	public function render_ExportFileConfigurator() {       
		// TODO: use templating engine 
		$content .= '<h2>'.'Configure the Transformation Process'.'</h2>';	
		$content .= "<form action=\"/?M=tx_fouroutM1\" method=\"post\">";
		$content .= "	<label for=\"inpFilename\">Inputfile</label>"; 
		$content .= "	<input type=\"text\" name=\"SET[tx_fouroutM1][inpFilename]\" id=\"SET[tx_fouroutM1][inpFilename]\"/>";
		$content .= "	&nbsp;&nbsp;&nbsp;&gt;&gt;&gt;&nbsp;&nbsp;&nbsp;";
	  	$content .= "	<label for=\"outpFilename\">Outputfile</label>"; 
		$content .= "	<input type=\"text\" name=\"SET[tx_fouroutM1][outpFilename]\" id=\"SET[tx_fouroutM1][outpFilename]\"/>";
		$content .= "	<input type=\"hidden\" name=\"SET[tx_fouroutM1][generateNewFile]\" id=\"SET[tx_fouroutM1][generateNewFile]\"/>";
		$content .= "	<input type=\"submit\" value=\"Transform\"/>";
		$content .= "</form>"; 
		$content .= "<hr />"; 
		return $content;
	}
		
	/**
	 * This method returns the actual content
	 * @access public
	 * @return String (HTML) $content
	 */
	public function getContent() {   
		if (! isset($_REQUEST['SET']['tx_fouroutM1']['generateNewFile'])) {
			// render errors if there were any
			if (! empty($this->_errorMessage)) {
				$message = t3lib_div::makeInstance(
					't3lib_FlashMessage', 
					$this->_errorMessage["body"],
					$this->_errorMessage["headline"], 
					t3lib_FlashMessage::ERROR, 
					false // save in session 
					);      
					t3lib_FlashMessageQueue::addMessage($message);
					$content .= t3lib_FlashMessageQueue::renderFlashMessages();	 
			 }   	
		} else {
			/**
			* Persist the shit 
			**/
			$this->sanitizeRequest(); 
			
            // if (! $this->generateXMLData()) {
            //  throw new Exception("Could not generate the XML file.");
            // } else {
				$message = t3lib_div::makeInstance(
					't3lib_FlashMessage', 
					'File successfully written',
					'Success', 
					t3lib_FlashMessage::INFO, 
					false // save in session 
					);      
					t3lib_FlashMessageQueue::addMessage($message);
					$content .= t3lib_FlashMessageQueue::renderFlashMessages();			}
        // }
		
		$content .= $this->render_ExportFileConfigurator();
		return $content;
	}                             	
}