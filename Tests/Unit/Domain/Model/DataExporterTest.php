<?php

class Tx_Zeitenwende_Domain_Model_DataExporterTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
    
    protected $_dataExporterMock = NULL;
    
    protected $_exportRequestMock = NULL;
    
    protected $_requestParams = array();
    
    public function setUp() {
        $this->_dataExporterMock = new Tx_Zeitenwende_Domain_Model_DataExporter;
        $this->_exportRequestMock = new Tx_Zeitenwende_Domain_Model_DataExportRequest;
        
        $this->_requestParams['valid'] = array('tables' => array('tt_content', 'pages'), 'initNode' => 0, 'outputFile' => 'dataSources.xml');
        $this->_requestParams['missingTables'] = array('initNode' => 0, 'outputFile' => 'dataSources.xml');
        $this->_requestParams['missingInitNode'] = array('tables' => array('tt_content', 'pages'), 'outputFile' => 'dataSources.xml');
        $this->_requestParams['missingOutputFile'] = array('tables' => array('tt_content', 'pages'), 'initNode' => 0);
    }
    
    public function tearDown() {
        unset($this->_dataExporterMock);
        unset($this->_exportRequestMock);
        unset($this->_requestParams);
    }
    
    /**
     * @test
     */
    public function injectingRequestObjectSupportsFluentInterface() {    
        $this->_exportRequestMock->insertParams($this->_requestParams['valid']);        
        $this->assertEquals(
            $this->_dataExporterMock, 
            $this->_dataExporterMock->injectRequest($this->_exportRequestMock)
        );
    }
    
    /**
     * @test
     */
    public function execMethodSupportsFluentInterface() {
        $this->_exportRequestMock->insertParams($this->_requestParams['valid']);
        $this->assertEquals(
            $this->_dataExporterMock,
            $this->_dataExporterMock->exec()
        );
    }

    
}
