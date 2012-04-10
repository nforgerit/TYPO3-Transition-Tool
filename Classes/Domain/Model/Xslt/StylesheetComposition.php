<?php

class Tx_T3tt_Domain_Model_Xslt_StylesheetComposition {
    
    protected $_SNIPPET_MARKER = '###SNIPPET_MARKER###';
        
    protected $_sheets = NULL;
    
    protected $_baseXsltStylesheet = NULL;
    
    protected $_ctypeConfiguration = NULL;
    
    public function setBasedir(string $dir) {
        $this->_basedir = $dir;
        return $this;
    }
    
    public function setXsltStylesheetAggregation(Tx_T3tt_Domain_Model_Xslt_StylesheetAggregation $sheets) {
        $this->_sheets = $sheets;
        return $this;
    }
    
    public function setBaseXsltStylesheet($baseXsltStylesheet) {
        if (! file_exists($baseXsltStylesheet)) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidParamsException("Given XSLT base stylesheet ".$baseXsltStylesheet." does not exist.");
        }
        $this->_baseXsltStylesheet = $baseXsltStylesheet;
        return $this;
    }
    
    public function setCtypeConfiguration(Tx_T3tt_Domain_Model_CtypeConfiguration $configuration) {
        $this->_ctypeConfiguration = $configuration;
        return $this;
    }
    
    public function getCompound() {
        $compound = file_get_contents($this->_baseXsltStylesheet);

        // check whether base stylesheet contains a snippet marker
        if (! strpos($compound, $this->_SNIPPET_MARKER)) {
            throw new Tx_T3tt_Domain_Model_Exception_InvalidXsltStylesheetContentsException("Base XSLT stylesheet ".$this->_baseXsltStylesheet." does not include any snippet marker, but should contain the marker `".$this->_SNIPPET_MARKER."'.");
        }
        
        // replace snippet marker with snippets appending the snippet marker again
        $snippets = $this->_sheets->getSnippets();
        if (! empty($snippets)) {
            foreach ($snippets as $snippet) {
                $replaceString = $snippet->getContent()."\n".$this->_SNIPPET_MARKER;
                $compound = str_replace($this->_SNIPPET_MARKER, $replaceString, $compound);
            }
        }

        // lastly, kill snippet marker
        $compound = str_replace($this->_SNIPPET_MARKER, '', $compound);
        
        return $compound;
    }
    
}