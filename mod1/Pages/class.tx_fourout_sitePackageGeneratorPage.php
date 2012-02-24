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

/*
TODO: 
- abstract general PAGE functionalities (e.g. $_errorMessage) to a higher level such that this class can just inherit those functionalities
*/

class Tx_FourOut_SitePackageGeneratorPage {
	
	/**
	 * @var Array Expecting the format ["head", "body"]	for persisting error messages.
	 */
	protected $_errorMessage;        
	
	/**
	 * @var String (HTML) Containing the SiteGenerator configuration page 
	 */
	protected $_pageContent;  
	
	/**
	 * @var String Chosen input data which will then be used to generate the SitePackage
	 */
	protected $_dataFilename;   
	
	/**
	 * @var String Contains the to be exported data
	 */
	protected $_exportData;  
	
	/**
	 * @var String User-defined name of the to be generated package
	 */	
	protected $_sitePackageName;
	
	/**
	 * This method sanitizes the webuser's given input data
	 * @access public 
	 * @throws Generic Exception
	 */
	public function sanitizeRequest() {    
		if (isset($_REQUEST['SET']['tx_fouroutM1']['downloadPkg'])) {
			$DATAFILE_BASEDIR = PATH_site."typo3conf/ext/four_out/Resources/Public/";
		
			// TODO: make this more secure!                 
			$this->_sitePackageName = $_POST['SET']['tx_fouroutM1']['pkgName']; 
			if (empty($this->_sitePackageName)) {
				throw new Exception("The Name of the new Package needs to be set.");
			}                                                      
		
			$this->_dataFilename = $_POST['SET']['tx_fouroutM1']['dataFilename'];  

			$dataFilePath = $DATAFILE_BASEDIR.$this->_dataFilename;
			if (! file_exists($dataFilePath)) {
				throw new Exception("Given Datafile doesn't exist.");
			} 
			$this->_exportData = file_get_contents($dataFilePath);
		}      
	}       
	
	/**
	 * This method puts up the generic ZipArchive data
	 * @access private
	 * @throws Generic Exception
	 */
	// TODO: outsource the filecontents to extern templates
	private function _generateZipArchive() {      
		$TEMPFILE = PATH_site."typo3conf/ext/four_out/Resources/Public/tempzip.zip";
		$zip = new ZipArchive();
		$resource = $zip->open($TEMPFILE, ZipArchive::CREATE);
		if ($resource === true) {
			$zip->addEmptyDir('Classes');
			$zip->addEmptyDir('Meta');
			$zip->addEmptyDir('Resources'); 
			$zip->addEmptyDir('Resources/Private'); 
			$zip->addEmptyDir('Resources/Private/Content'); 
			    
			// TODO: outsource this into a separate template
			$zip->addFromString(
				'Classes/Package.php',
				'<?php
				namespace TYPO3\\'.$this->_sitePackageName.';

				/*                                                                        *
				 * This script belongs to the FLOW3 framework.                            *
				 *                                                                        *
				 * It is free software; you can redistribute it and/or modify it under    *
				 * the terms of the GNU Lesser General Public License as published by the *
				 * Free Software Foundation, either version 3 of the License, or (at your *
				 * option) any later version.                                             *
				 *                                                                        *
				 * This script is distributed in the hope that it will be useful, but     *
				 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
				 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
				 * General Public License for more details.                               *
				 *                                                                        *
				 * You should have received a copy of the GNU Lesser General Public       *
				 * License along with the script.                                         *
				 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
				 *                                                                        *
				 * The TYPO3 project - inspiring people to share!                         *
				 *                                                                        */

				use \TYPO3\FLOW3\Package\Package as BasePackage;

				/**
				 * The '.$this->_sitePackageName.' Package
				 */
				class Package extends BasePackage {

				}

				?>'
				);
			$zip->addFromString(
				'Meta/Package.xml',
				'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
				<package xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://typo3.org/ns/2008/flow3/package" version="1.0">
					<key>TYPO3.'.$this->_sitePackageName.'</key>
					<title>'.$this->_sitePackageName.'</title>
					<description>Site package for TYPO3 Phoenix. Generated by the "Four Out!" extension from TER.</description>
					<version>0.0.1</version>
				</package>
				'
				);          

			$zip->addFromString(
				'Resources/Private/Content/Sites.xml',
				$this->_exportData
				);
			$zip->close();              			

			$this->_exportData = file_get_contents($TEMPFILE);    
			unlink($TEMPFILE);
		} else {
			throw new Exception("Could not create temporary zip file. Did you switch off PHP's 'Safe Mode'? No? Switch it off, it's a bad joke!");
		}
		

	}
		
	/**
	 * This method produces the content shown on the according generator page.
	 * @access public
	 * @return String (HTML)
	 */
	public function getContent() {    
		if (! $_REQUEST['SET']['tx_fouroutM1']['downloadPkg']) {
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
 			$this->_generateZipArchive();
			if (isset($this->_exportData)) {  
				header("HTTP/1.1 200 OK");
		 		header("Content-Type: application/force-download");
		 		header("Content-Disposition: attachment; filename=".$this->_sitePackageName.".zip");
		 		header("Content-length: ".strlen($this->_exportData));
		 		header("Content-Transfer-Encoding: binary");
				echo $this->_exportData;
				exit();
			}
		}               
		
		$content .= $this->render_SitePackageGeneratorForm();
		return $content;
	}        
	
	/**
	 * This method renders the form that receives the user's input data.
	 * @return String (HTML)
	 * @access public
	 */	
	public function render_SitePackageGeneratorForm() {    
		// TODO: better use a templating engine

		$possibleExportFiles = $this->_seekExportDatafiles();
		
		$content .= '<h2>'.'Generate your very own Sitepackage'.'</h2>';
		$content .= '<form method="POST" action>';            
		$content .= '	<label for="sitePackageName">Name of the Sitepackage</label><br/>';
		$content .= '	<input type="text" name="SET[tx_fouroutM1][pkgName]"><br/><br/>';
		
		$content .= '	<label for="dataFilename">Select Datafile</label><br/>';

		$content .= '	<select name="SET[tx_fouroutM1][dataFilename]" size="6">';
		foreach ($possibleExportFiles as $exportFile) {
			$content .= '		<option>'.$exportFile.'</option>';
		}
		$content .= '	</select><br/>';
		
		$content .= '	<input type="submit" value="Download Sitepackage"/>';
		$content .= '	<input type="hidden" name="SET[tx_fouroutM1][downloadPkg]" value="true"/>';
		$content .= '</form>';
		$content .= "<hr />"; 

		return $content;
	}      
	
	/**
	 * This method simply encapsulates the seeking of the input data files.
	 * @return Array
	 */
	private function _seekExportDatafiles() {
		$BASEDIR = PATH_site."typo3conf/ext/four_out/Resources/Public/";

		$files = scandir($BASEDIR);
		// remove "dotted" dirs
		unset($files[0]);
		unset($files[1]);
		
		return $files;
	}
	
}

?>