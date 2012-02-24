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

class Tx_FourOut_ExportDocumentsPublisherPage {  

	/**
	 * @var String (HTML) The Pages Content.
	 */
	protected $_pageContent = '';   
	
	/**
	 * @var String (User-given) filename of the to be exported datafile.
	 */
	protected $_filename;
	
	/**
	 * @var String (User-given) URI of the exported data. Currently, it will just be appended to "/rest/4out".
	 */ 
	protected $_exportUri;     
	
	/**
	 * @var Array ["head", "body"] Containing possible error messages.
	 */
	protected $_errorMessage;
	
	/**
	 * This method sanitizes the webuser's input.
	 * @access public
	 */
	public function sanitizeRequest() {                                     
		if (isset($_REQUEST['SET']['tx_fouroutM1']['exportUri']) && isset($_REQUEST['SET']['tx_fouroutM1']['filename'])) {
		   $_REQUEST['newExportDocument'] = true;

			// forbid "dotted" export uris
			if (preg_match('/\.+/', $_REQUEST['SET']['tx_fouroutM1']['exportUri'])) {
				$_REQUEST['newExportDocument'] = false;
				$this->_errorMessage = array(
					"headline" => 'Export Document not saved',
					"body"	=> 'Dotted export uris are forbidden.'	
				);
			} else {               			
				$this->_exportUri = (preg_match('/^\/rest\//.*', $_REQUEST['SET']['tx_fouroutM1']['exportUri']) ? $_REQUEST['SET']['tx_fouroutM1']['exportUri'] : "/rest".$_REQUEST['SET']['tx_fouroutM1']['exportUri']); 				
			}                      

			if (file_exists(t3lib_extMgm::extPath("four_out")."Resources/Private/InputData/".$_REQUEST['SET']['tx_fouroutM1']['filename'])) {   				
				// TODO: make this more flexible
				$this->_filename = t3lib_extMgm::extPath("four_out")."Resources/Private/InputData/".$_REQUEST['SET']['tx_fouroutM1']['filename'];
			} else {    
				$_REQUEST['newExportDocument'] = false; 
				$this->_errorMessage = array(
					"headline" => 'Export Document not saved',
					"body"	=> "Given file {$_REQUEST['SET']['tx_fouroutM1']['filename']} does not exist."
				);
			}
		} else {
			$_REQUEST['newExportDocument'] = false;
		}
	}    
	
	/**
	 * @access public
	 * @return String (HTML) The configuration page's form rendering.                                           
	 */
	// TODO: use some templating mechanism
	public function render_MappingMode() {
		$content .= "<h2>"."Define a new Export Path"."</h2>";
		$content .= "<form action=\"/?M=tx_fouroutM1\" method=\"post\">";
		$content .= "	<label for=\"exportUri\">Export URI</label>"; 
		$content .= "	<input type=\"text\" name=\"SET[tx_fouroutM1][exportUri]\" id=\"SET[tx_fouroutM1][exportUri]\"/>";
		$content .= "	<label for=\"filename\">Filename</label>"; 	
		$content .= "	<input type=\"text\" name=\"SET[tx_fouroutM1][filename]\" id=\"SET[tx_fouroutM1][filename]\"/>";	
		$content .= "	<input type=\"submit\"/>";
		$content .= "</form>"; 
		$content .= "<hr />";       
		
		return $content;	
	}            
	
	/**
	 * @access public
	 * @return String (HTML) The configuration page's success message for binding a datafile to an URI.
	 */
	public function getContent() {
		if (! $_REQUEST['newExportDocument']) {  
			// render errors if there were any
			if (! empty($this->_errorMessage)) {
				$message = t3lib_div::makeInstance(
					't3lib_FlashMessage', 
					$this->_errorMessage["body"],
					$this->_errorMessage["headline"], 
					t3lib_FlashMessage::ERROR, 
					TRUE // save in session 
					);      
					t3lib_FlashMessageQueue::addMessage($message);
					$content .= t3lib_FlashMessageQueue::renderFlashMessages();
			}              

			$content .= $this->render_MappingMode();
			return $content;
		} else {
			$content = "";
			return $content;
		}
	}            
	

	
	   

}