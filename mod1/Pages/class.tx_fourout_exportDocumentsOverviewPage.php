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
require_once(EXTDIR."Classes/class.tx_fourout_exportDocumentsListing.php");
	
class Tx_FourOut_ExportDocumentsOverviewPage {      
	
	/**
	 * This method returns the standard rendering
	 * @access public
	 * @return String (HTML) $content
	 */	
	public function getContent() {
		$content .= $this->render_Overview();
		return $content;
	}
	
	/**
	 * This renders the standard overwiew 
	 * @access protected
	 * @return String (HMTL) $content
	 */
	protected function render_Overview() {
		$basedir = t3lib_extMgm::extPath('four_out')."Resources/Public";
		$exportDocumentsListing = new Tx_FourOut_ExportDocumentsListing($basedir);  
		$exportDocumentsArray = $exportDocumentsListing->getListing();
		
		$content .= "<h2>"."List of Public Export Documents"."</h2>";    
		
		$i = 1;
		$content .= "<ul>";
		foreach ($exportDocumentsArray as $index => $exportDocumentFilename) {  
			$exportDocument = new Tx_FourOut_ExportDocument($filepath); 
			$filepath = $exportDocumentsListing->getBasedir()."/".$exportDocumentFilename; 
            $content .= "<li>".$i++."\n: ".$filepath."</li>";
		}                                                           
		$content .= "</ul>";
		
		$content .= "<hr /><br /><br />";
		
		return $content;
  	}
}

?>