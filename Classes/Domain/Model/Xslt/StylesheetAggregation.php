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

class Tx_Zeitenwende_Domain_Model_Xslt_StylesheetAggregation {

	/** 
	 * The XSLT snippets aggregation
	 * @var Array of Tx_Zeitenwende_XSLTSnippet objects $_snippets
	 */
	protected $_snippets = array();
	
	protected $_basedir = '';
	
	public function __construct(string $basedir, $snippets = array()) {
		$this->_basedir = $basedir;
		
		if (! empty($snippets)) {
		    foreach ($snippets as $snippetObject) {
		        if ($snippetObject typeof Tx_Zeitenwende_Domain_Model_XsltSnippet) {
		            $this->_snippets.push($snippetObject);
		        } else {
        		    throw new Tx_Zeitenwende_Domain_Model_Exception_InvalidParamsException("Given snippet is not a valid XsltSnippet object.");
		        }
		    }
		}
	}                                

	public function addSnippet(Tx_Zeitenwende_Domain_Model_XsltSnippet $snippetFile) {
        if (strlen($snippetFile->getContent()) > 0) {
    		$this->_snippets.push($snippetFile); 
        } else {
		    throw new Tx_Zeitenwende_Domain_Model_Exception_InvalidParamsException("Snippet `".$snippetFile."' is empty.");
		}
	}                                               
	
	public function getSnippets() {
	    if (! empty($this->_snippets)) {
	        return $this->_snippets;
	    }
	}
}

?>