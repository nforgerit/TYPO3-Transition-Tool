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
class Tx_Zeitenwende_Domain_Model_Xslt_Snippet {
	
	/**
	 * The XSLT snippet's filename
	 */
	protected $_filename = '';
	
	/**
	 * The XSLT snippet's content
	 */
	protected $_content = ''; 
	
	/**
	 * @param String (FILENAME) $filename
	 */
	public function __construct(string $filename) {
        if (! file_exists($filename)) {
            throw new Tx_Zeitenwende_Domain_Model_Exception_InvalidParamsException("Given Snippetfile ".$filename." does not exist.");
        }
        $this->_filename = $filename;
	}                             
	
	/**
	 * @return String (XSLT) Returns the snippet's contents
	 */
	public function getContent() {
	    if (file_exists($filename)) {
			$this->_content = file_get_contents($filename);
			return $this->_content;
		}
	}
}

?>