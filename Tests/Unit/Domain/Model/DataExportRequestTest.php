<?php

class Tx_T3tt_Domain_Model_DataExportRequestTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

    protected $_exportRequestMock = NULL;

    public function setUp() {
        $this->_exportRequestMock = new Tx_T3tt_Domain_Model_DataExportRequest;
    }
        
    /**
     * @test
     */
    public function successfulPropertyAccessOnCorrectlyInjectedRequestData() {
        $validParams = array(
            'tables' => array(
                'tt_content', 
                'pages'
                ),
            'initNode' => 0,
            'outputFile' => 'dataSources.xml'
        );
        
        $this->_exportRequestMock->insertParams($validParams);
        $this->assertEquals(array('tt_content','pages'), $this->_exportRequestMock->getTables());       
        $this->assertEquals(0, $this->_exportRequestMock->getInitNode());       
        $this->assertEquals('dataSources.xml', $this->_exportRequestMock->getOutputFile());       
    }
    
    /**
     * @test
     * @expectedException Tx_T3tt_Domain_Model_Exception_NoParamsException
     */
    public function throwExceptionOnForgottenInjectedRequestData() {
        $this->_exportRequestMock->getTables();
        $this->_exportRequestMock->getInitNode();
        $this->_exportRequestMock->getOutputFile();
    }
    
    
    /**
     * @test
     */
    public function missingTablesInRequestLeadToFallback() {
        $this->_exportRequestMock->insertParams($this->_requestParams['missingTables']);
        $this->assertEquals(
            array('tt_content', 'pages'),
            $this->_exportRequestMock->getTables()
        );
    }
    
    /**
     * @test
     */
    public function missingInitNodeInRequestLeadsToFallback() {
        $this->_exportRequestMock->_insertParams($this->_requestParams['missingInitNode']);
        $this->assertEquals(0, $this->_exportRequestMock->getInitNode());
    }
    
    /**
     * @test
     */
    public function missingOutputFileInRequestLeadsToFallback() {
        $this->_exportRequestMock->_insertParams($this->_requestParams['missingOutputFile']);
        $this->assertEquals('Input.xml', $this->_exportRequestMock->getOutputFile());
    }
}