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

class Tx_FourOut_Domain_Model_PackageGenerator {
	
	/**
	 * The to be exported Phoenix SitePackage' name
	 */
	private $_packagename;
	
	/**
	 * The compression to be used
	 */
	private $_compression = "zip";
	
	/**
	 * Some special package configuration
	 */
	private $_packageConfiguration;     
	
	/**
	 * @param String $packagename
	 * @param String $packageconfiguration
	 * @param String $compression
	 */
	public function __construct($packagename, $packageConfiguration, $compression = 'zip') {
		$this->_packagename = $packagename;
		$this->_packageConfiguration = $packageConfiguration;
		$this->_compression = $compression;
	}
	
	
}   

?>