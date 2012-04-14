<?php

class Tx_T3tt_Domain_Model_Xslt_CtypeConfiguration {

    protected $CtypeConfiguration = array();

    public function setCtypeConfigurationRequest(array $CtypeConfiguration) {
        
    }
}


/* This is how the content type configuration array would look like 

array(
    'ctype_text' => array(
        'mapToCtype' => 'text', // "fieldlist/field[@index='CType']/text() = 'text'"
        'nodeNameFromField' => 'header', // "fieldlist/field[@index='header']/text()"
        'locale' => 'en_EN',
        'properties' => array(
            'headline' => 'header', // "fieldlist/field[@index='header']/text()"
            'text' => 'bodytext', // "fieldlist/field[@index='bodytext']/text()"
            'markerValues' => array(
                'HEADER' => 'headerValue1',
            )
    ),
    'ctype_html' => array(
        'mapToCtype' => 'html', // "fieldlist/field[@index='CType']/text() = 'text'"
        'nodeNameFromField' => 'header', // "fieldlist/field[@index='header']/text()"
        'locale' => 'en_EN',
        'properties' => array(
            'headline' => 'header', // "fieldlist/field[@index='header']/text()"
            'text' => 'bodytext', // "fieldlist/field[@index='bodytext']/text()"
    ),    
)

*/