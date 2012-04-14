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
      
return array(          	
    /**
     * Because PHPs XSLTProcessor is based on libxml which currently only supports
     * XSLT v1, we need to replace values of the Input.xml containing a string
     * like "tt_content:42" (tt_content item on page with id 42) with an
     * XSLT v1-readable replacement like "index='tt_content' id='42'".
     */
	"pre"	=> array(
		"killIndexColons"	=> function($lines) {		    
			foreach ($lines as $i => $l) {
				$lines[$i] = preg_replace(
					'/index=\"(\w+):(\d+)\"/',
					'index="$1" id="$2"',
					$l
				);
			}

			return $lines;
		},
		/*"unescapeHtmlSpecialCharsOfFlexformValues" => function($lines) {
		},*/
	),
	"post"	=> array(
        "fillEmptyNodeNames" => function($lines) {
            foreach ($lines as $i => $l) {

                if (preg_match('/nodeName=\"\"/U', $l)) {
                    $lines[$i] = preg_replace(  
                        '/nodeName=\"\"/U', 
                        'nodeName="'.substr(hash('sha256', (string)mt_rand()), 0, 20).'"',
                        $l
                    );
                }
            }
            
            return $lines;
        },
	    /**
	     * Because nodeName values mustn't contain the symbols *, #, @ as well
	     * as ' ' (any whitespaces), replace any occurrences with a dash (-).
	     */
	   	"normalizeNodeNames" => function($lines) {
		    foreach ($lines as $i => $l) {    
		        
                    // just process the value within the nodeName (thus a somewhat more sophisticated approach is needed)
                    // ('U' = ungreedy matching, otherwise PCRE would match nodeName="{someVal" title="}")
                if (preg_match('/nodeName=\"(.*)\"/U', $l, $matches) > 0) {
                    $nodeNameValue = $matches[1];    		        

                        // kill whitespaces
                    $filteredSubStr = str_replace(
                        array(chr(9) /*TAB*/, chr(32) /*SPC*/, chr(10) /*LF*/, chr(11) /*VTAB*/, chr(13) /*CR*/),
                        '', 
                        $nodeNameValue
                    );
                    
                        // replace illegal symbols with a dash
                    $filteredSubStr = str_replace(array('#','@','*',',','.','!','(', ')'), '-', $filteredSubStr);
                        
                        // replace original nodeName content with filtered content
                    $l = preg_replace(
                        '/nodeName=\"(.*)\"/U',
                        'nodeName="'.$filteredSubStr.'"',
                        $l
                    );                
                }
                                                                            
                $lines[$i] = $l;
            }
            
            return $lines;
		},

		// "killRemainingMarkers" => function($lines) {
		//            
		//        },
	),
);

?>            