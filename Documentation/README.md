# TYPO3 Transition Toolchain (Pt1): Four Out!

## Foreword

### History

This extension started based on discussions that took place during the TYPO3
Transition Days in Berlin as a GSoC project in 2011:

http://www.google-melange.com/gsoc/project/google/gsoc2011/q8/15001

It has it's "other home" over at the TYPO3 Forge:

http://forge.typo3.org/projects/package-t3tt

And here is a presentation done during the TYPO3 Developer Days 2012:

http://www.slideshare.net/crus0e/t3-dd12-contenttransitiontool

### Current Status

This extension is still highly experimental since it needs a lot of
refurbishment concerning its GUI, its source structuring as well as its core:
The data transition via XSLT. However, it proves the possibility of the initial
idea of transitioning from TYPO3 CMS instances to TYPO3 Neos instances via XSLT.

After a lot of refactoring and maturing, this extension could be a kickstarter
for a whole transition toolchain (maybe covering TS, (Extbase-)PHP-Code, Users,
etc.). Alas, by now the future content definitions of Neos are not ready such
that the extension needs a lot of future development effort. By now, it will be
mainly hosted on github (//github.com/crusoe/TYPO3-Transition-Tool) and will at
last be hosted at TER when it's at least some certain (to be defined) state of
feature-complete.

## Abstract

This TYPO3 CMS Extension is called "Four Out!" and is part of the transition
toolchain which will ease the transition for users of TYPO3 to the brand new and
technically incompatible TYPO3 Neos. It will provide either a simple Webservice
API for exporting data from your TYPO3 instance to Neos (which then itself
needs the toolchain's Pt2 called "Five In!" for import functionalities) or it
may alternatively generate Neos compatible packages that then can simply be
thrown into your Neos directory.

The data translation uses TYPO3's XML export and translates it into Neos
compatible XML data via XSLT. It can be highly customized by simply throwing
appropriate XSLT snippets into the EXT:T3tt/Resources/Private/XSLT/Stylesheets
folder which are automatically read, assembled and executed. Furthermore, the
whole translation process can be customized via several PHP-hooks that can gain
control at almost any stage.

## Installation

- enable .htaccess for mod_rewrite (this is urgent!)
- install the TYPO3 extension "T3tt" from TYPO3 Extension Repository (TER)
  [you've probably already done that]

If installing from git, make sure to name the extension folder t3tt to make it
work.

## Quick Guide

- log into your TYPO3 backend
- seek and click on the "Four Out!" module in your TYPO3 backend
- ...
- currently all .xml files in EXT:T3tt/Resources/Public are automatically bound
  to http://<your_t3_host>/rest/<filename_wo_ending>
- newly generated export paths can then simply be saved as files holding the
  content in the public folder from above

## Contents
### Backend (mod1)
### Frontend (pi1)

## Further Documentation

- extending "Four Out!"
	- provide xslt-snippets for EXT:T3tt/Resources/Private/XSLT/Stylesheets
	- customize Tx_T3tt_ExportDataProvider to fulfill your needs
	- hook into the translation process via your own PHP Script
	- see "Crunching `Four Out!' - Advanced techniques for Customization"
- refer to "T3TT in-depth study"
- generating stages:
	- collecting phase
		- master.xsl [HOOK: manipulate via PHP]
		- concatenate all the snippets in Resources directory [HOOK: for each
		  snippet: manipulate via PHP]
		(-- now we have whole stylesheet as string --)
	- php-hook: manipulate all the collected stuff via PHP
	- php-hook: register custom php functions given to the XSLTprocessor
	- *** processing XSLTProcessor ***
	- php-hook: after-processing: last chance to change the output!

## Misc. Points

- asynchronous export (huge data amounts will result in a dying request!)
- seek fileadmin content, too

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