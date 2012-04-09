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

class Tx_Zeitenwende_Domain_Model_ExportDocumentsCollection {
	
	/**
	 * This is the basedir in which the export documents will be sought.
	 * @var String (DIRPATH) $_basedir
	 */
	private $_basedir;  
	
	/**
	 * This will hold all the XSLT snippets' filenames collected in the basedir.
	 * @var Array of filenames
	 */
	private $_stylesheets = array();
	
	/**
	 * @access public
	 * @param String (DIRPATH) $basedir
	 * @param String (FILETYPE) $type
	 */
	public function __construct($basedir, $type = 'xml') {
		if (is_dir($basedir) && file_exists($basedir)) {
			$this->_basedir = $basedir;
		}                                  

		$this->_seekExportDocuments($type);
	}                     
	
	/**
	 * This private method simply scans for the export documents matching the given filetype within the basedir.
	 * @access private
	 * @param String (FILETYPE) $type
	 * @throws Generic Exception
	 */
	private function _seekExportDocuments($type) {
		if (isset($this->_basedir)) {              
			try {
				$this->_stylesheets = scandir($this->_basedir);
			
				foreach ($this->_stylesheets as $index => $fname) {
					if (! preg_match("/.*\.({$type})(_INACTIVE)?$/", $fname)) {
						unset($this->_stylesheets[$index]);
					}
				}
			} catch (Exception $e) {
				throw new Exception("An Error occured when seeking the exported documents: ".$e->getMessage());
			}
		}
	}    
	
	/**
	 * @access public
	 * @return String (FILEPATH) $basedir (if set)
	 */
	public function getBasedir() {
		if (isset($this->_basedir)) {
			return $this->_basedir;
		}
	}   
	
	/**
	 * @access public
	 * @return Array of filenames (if not empty)
	 */
	public function getListing() {
		if (isset($this->_stylesheets)) {
			return $this->_stylesheets;
		}
	}

}

?>