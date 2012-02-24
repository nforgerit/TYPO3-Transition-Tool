# TYPO3 Transition Toolchain (Pt1): Four Out!         

## Foreword: Current Status
This extension is still highly experimental since it needs a lot of refurbishment concerning its GUI, its source structurization as well as its core: The data transition via XSLT. However, it proves the possibility of the initial idea of transitioning from TYPO3v4 instances to TYPO3v5 (Phoenix) instances via XSLT. After a lot of refactorization and maturization, this extension could be a cold-kickstarter for a whole transition toolchain (maybe covering TS, (Extbase-)PHP-Code, Users, etc.). Alas, by now the future content definitions of Phoenix are not ready such that the extension needs a lot of future development effort. By now, it will be mainly hosted on github (//github.com/crusoe/T3TT/four_out.git) and will at last be hosted at TER when it's at least some certain (to be defined) state of feature-complete.

## Abstract
This TYPO3 v4 (T3v4) Extension is called "Four Out!" and is part of the transition toolchain which will ease the transition for users of T3v4 to the brandnew and technically incompatible TYPO3 v5 (T3v5, Codename: Phoenix). It will provide either a simple Webservice API for exporting data from your v4 instance to v5 (which then itself needs the toolchain's Pt2 called "Five In!" for import functionalities) or it may alternatively generate v5 compatible packages that then can simply be thrown into your v5 directory.                           

The data translation uses T3v4's XML export and translates it into v5 compatible XML data via XSLT. It can be highly customized by simply throwing appropriate XSLT snippets into the EXT:four_out/Resources/Private/XSLT/Stylesheets folder which are automatically read, assembled and executed. Furthermore, the whole translation process can be customized via several PHP-hooks that can gain control at almost any stage.

## Installation                  
- enable .htaccess for mod_rewrite (this is urgent!)
- install the v4 extension "four_out" from TYPO3 Extension Repository (TER) [you've probably already done that]

## Quick Guide      
- log into your T3v4 backend
- seek and click on the "Four Out!" module in your T3v4 backend
- ...     
- currently all .xml files in EXT:four_out/Resources/Public are automatically bound to http://<your_t3_host>/rest/<filename_wo_ending>      
- newly generated export paths can then simply be saved as files holding the content in the public folder from above  
          
## Contents
### Backend (mod1)
### Frontend (pi1)

## Further Documentation                                      
- extending "Four Out!"
	- provide xslt-snippets for EXT:four_out/Resources/Private/XSLT/Stylesheets
	- customize Tx_FourOut_ExportDataProvider to fulfill your needs
	- hook into the translation process via your own PHP Script
	- see "Crunching `Four Out!' - Advanced techniques for Customization"
- refer to "T3TT in-depth study"   
- generating stages:
	- collecting phase
		- master.xsl [HOOK: manipulate via PHP]
		- concatenate all the snippets in Resources directory [HOOK: for each snippet: manipulate via PHP]
		(-- now we have whole stylesheet as string --)
	- php-hook: manipulate all the collected stuff via PHP
	- php-hook: register custom php functions given to the XSLTprocessor
	- *** processing XSLTProcessor *** 
	- php-hook: after-processing: last chance to change the output!

## Legal Notice
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