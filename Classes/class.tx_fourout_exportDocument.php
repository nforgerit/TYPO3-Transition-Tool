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
  
class Tx_FourOut_ExportDocument {       
	
	/**
	 * The ExportDocument's later basedir
	 * @var String (FILEPATH)
	 */
	private $_basedir;
	
	/**
	 * The input datafile's filename
	 * @var String (FILEPATH)
	 */
	private $_filename;
	
	/**
	 * The input datafile's datatype (possibly XML)
	 * @var String (FILETYPE)
	 */
	private $_type;    
	
	/**
	 * The input datafile's actual content
	 * @var String (FILECONTENT)
	 */
	private $_filecontent;
	
	/**
	 * @access public
	 * @param $filename The input file's filename
	 */
	public function __construct($filename) {
		if (isset($filename) && file_exists($filename)) {  
			$this->_processFilename($filename); 
			$this->_filecontent = file_get_contents($filename);
		}
	}                   
	
	/**
	 * The object's basedir it is working on.
	 * @access public
	 * @return String (FILEPATH)
	 */
	public function getBasedir() {
		return $this->_basedir;
	}
	
	/**
	 * The object's filename of which it holds the content
	 * @access public
	 * @return String (FILENAME)
	 */
	public function getFilename() {
		return $this->_filename;
	}                           
	
	/**
	 * The type of the input datafile
	 * @access public
	 * @return String (FILETYPE)
	 */
	public function getType() {
		return $this->_type;
	}                       
	
	/**
	 * Whether the input file's content is of type XML, this method returns a SimpleXMLElement object containing the file's data
	 * @access public
	 * @return SimpleXMLElement
	 */
	public function getXML() {
		if ($this->_type === 'xml') {
			return new SimpleXMLElement($this->_filecontent);
		}
	}   
	
	/**
	 * Set the input file's filename
	 * @access public 
	 * @param $filename String (FILENAME)
	 */
	public function setFilename($filename) {
		$this->_filename = $filename;
	}                                
	
	/**
	 * Set the input file's filetype
	 * @access public
	 * @param $type String (FILETYPE)
	 */
	public function setType($type) {
		$this->_type = $type;
	}                        
	
	/**
	 * Set the file's content from outside (possibly a bad idea, which may getting deprecated in future)
	 * @access public
	 * @param $content String (FILECONTENT)
	 */
	public function setFilecontent($content) {
		$this->_filecontent = $content;
	}                 
	
	/** 
	 * Method to save the object's data to the according filepath/-name combination
	 * @access public
	 */
	public function saveFile() {
		try {
			$flag = file_put_contents($this->_filename, $this->_filecontent);
			return $flag;
		} catch (Exception $e) {
			die("An error occured when writing the file: ".$e->getMessage());
		}
	}          
	
	/**
	 * @access private
	 */
	private function _processFilename($filename) { 
		$rpos = strrpos($filename, "/");		
		$parts = explode(".", trim($filename, "/."));           		
		list($this->_filename, $this->_basedir, $this->_type) = each(substr($filename, $rpos), substr($filename, 0, $rpos), $parts[1]);
	}
	
	
}

?>