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

class Tx_FourOut_Domain_Model_XsltStylesheet {
	
	/**
	 * The XSLT master stylesheet
	 * @var String (XML) $_master
	 */
	private $_master;
	
	/** 
	 * The XSLT snippets aggregation
	 * @var Array of Tx_FourOut_XSLTSnippet objects $_snippets
	 */
	private $_snippets;
	
	public function __construct($masterStylesheet, $snippets = array()) {
		$this->master = $masterStylesheet;
		$this->_snippets = $snippets;
	}                                
	
	/** 
	 * Manually add new Tx_FourOut_XSLTSnippet objects to the snippet aggregation
	 * @param Tx_FourOut_XSLTSnippet $snippetFilename 
	 */
	public function addSnippet(Tx_FourOut_XSLTSnippet $snippetFilename) {
		if (file_exists($snippetFilename)) {
			$this->_snippets.push($snippetFilename); 
		}
	}                                               
	
	/**
	 * Return the concatenated stylesheets (incl. the master stylesheet)
	 * @access public
	 * @throws Generic Exception
	 */
	public function provideFinalStylesheet() {
		try {
			$stylesheet = file_get_contents($this->_master);
			
			foreach ($this->_snippets as $filename) {
				$stylesheetObj = new Tx_FourOut_XSLTSnippet($filename);
				$stylesheet .= $styleSheetObj->getContent();
			}        
			return $stylesheet;                                       
		} catch (Exception $e) {
			throw new Exception ("Could not render final XSLT stylesheet: ".$e->getMessage());
		}
	}
}

?>