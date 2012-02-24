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
   
/**
 * This is the main entry file for the extension's backend module. It processes 
 * every given content and calls the according functionality/rendering objects for 
 * the matching subpage.
 */

define('EXTDIR', dirname(__FILE__)."/../");

$GLOBALS['LANG']->includeLLFile('EXT:four_out/mod1/locallang.xml');
require_once(PATH_t3lib . 'class.t3lib_scbase.php');              
require_once(t3lib_extMgm::extPath($_EXTKEY)."Classes/class.tx_fourout_exportDocument.php");


/**
 * Module 'Tx_FourOut_Module1' for the 'four_out' extension.
 *
 * @author	Nicolas Forgerit <nicolas.forgerit@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_four_out
 */
class Tx_FourOut_Module1 extends t3lib_SCbase {     
	
	const MODULE_Name = 'tools_txfouroutM1';
			
	public function __construct() {
		$GLOBALS['BE_USER']->modAccess($GLOBALS['MCONF'],1);
		
		$this->doc = t3lib_div::makeInstance('noDoc');
		$this->init();	  
	}                
	
	/**
	 * The extension's main entry point which is then called below
	 * @access public
	 */
	public function main() {                    
		$chosenFunction = intval($_REQUEST['SET']['function']); 
		$this->doc->form='<form action="" method="post">';   
		$this->doc->docType = 'xhtml_trans';

			// JavaScript
		$this->doc->JScode = $this->doc->wrapScriptTags('
				script_ended = 0;
				function jumpToUrl(URL)	{	//
					if (!URL.match(/[?&]M=/)) {
						URL = URL + "&M=' . self::MODULE_Name . '";
					}
					document.location = URL;
				}
		');         
		
		$this->content.=$this->doc->startPage('Four out! Main Preferences');
		$this->content.=$this->doc->header('Four out! Main Preferences');
		$this->content.=$this->doc->spacer(5);     
		$this->content.=$this->doc->section('',
			$this->doc->funcMenu('',
				t3lib_BEfunc::getFuncMenu(
					$this->id,
					'SET[function]',
					$chosenFunction,
					$this->MOD_MENU['function'])
				));                  
		$this->content.=$this->doc->spacer(30);
		
		/* include and render the needed page scripts for the subpages */		
		switch ($chosenFunction) {
			default: 
			case 0:   
				break;
			case 1: 
				// Overview
				require_once (t3lib_extMgm::extPath("four_out")."mod1/Pages/class.tx_fourout_exportDocumentsOverviewPage.php");
				$page = new Tx_FourOut_ExportDocumentsOverviewPage;
				break;
			case 2:   
			 	// Step 1: Prepare your Data
                require_once (t3lib_extMgm::extPath("four_out")."mod1/Pages/class.tx_fourout_exportDocumentsGeneratorPage.php");
				$page = new Tx_FourOut_ExportDocumentsGeneratorPage;
				break;   
			case 3: 
				// Step 2: Export your Data     
				require_once (t3lib_extMgm::extPath("four_out")."mod1/Pages/class.tx_fourout_sitePackageGeneratorPage.php");
				$page = new Tx_FourOut_SitePackageGeneratorPage;
				break;
			case 4:  
			 	// Step 3: Import your Data
				require_once (t3lib_extMgm::extPath("four_out")."mod1/Pages/class.tx_fourout_exportDocumentsPublisherPage.php");
				$page = new Tx_FourOut_ExportDocumentsPublisherPage;
				break;
		}         
		
		if (isset($page)) {
			//$page->sanitizeRequest();  
			$this->content .= $page->getContent();  
		}
    }
	
	/**
	 * This simply prints the content's of the requested subpage to PHP's output buffer
	 * @access public
	 */
	public function printContent() {    
		if (isset($this->content)) {
			$this->content .= $this->doc->endPage();  
			echo $this->content;
		}
	}       
	
	/**
	 * Some proxy object returning the menu configuration.
	 * @access public
	 * @return Array of TYPO3 menu configuration entries
	 */
	public function menuConfig() {
		$this->MOD_MENU = array (
			'function' => array (
				'0' => '[Select step]',
				'1' => 'Step 1: Prepare your Data',  
				'2'	=> 'Step 2: Export your Data',
				'3'	=> 'Step 3: Import your Data',
			)
		);
	}        
}           

/* Afterwards, instantiate the previously defined class */
// Make instance:
$SOBE = t3lib_div::makeInstance('tx_FourOut_Module1');
$SOBE->main();         
$SOBE->printContent();