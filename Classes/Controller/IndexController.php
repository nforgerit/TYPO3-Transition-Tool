<?php

class Tx_Zeitenwende_Controller_IndexController extends Tx_Extbase_MVC_Controller_ActionController {

    protected $_dataExporter;
        
    public function initializeAction() {
        // fetch $_GP values
        
        $this->_dataExporter = $this->objectManager->get('Tx_Zeitenwende_Domain_Model_Data_Exporter');
    }
    
    /**
     */
    public function indexAction() {

            echo "<pre>";
                echo "<span style=\"background-color:#bada55\">";
                    var_dump($this->arguments);
                echo "</span>";
            echo "</pre>";
            //die("-- died in ".__FILE__.", line ".__LINE__);
        
        
        $this->view->assignMultiple(array(
            'message' => "Hey I'm in IndexController->indexAction() !!!",
            'step' => intval($step),
        ));        

    }

public function step1Action() {
        /*
            $_GET[] may contain: (&key [= std. value])
            - initNode = 0
            - dataFileName = Input.xml
            - tables = array()
            - ...
        */  
        
        $this->flashMessageContainer->add("Hello there and welcome to `TYPO3 Transition Tool'" , 'Welcome', t3lib_FlashMessage::INFO);

        $args = $this->request->getArguments();
        if ($args['generateData'] === 'Generate') {        
            $dataExportRequest = $this->objectManager->get('Tx_Zeitenwende_Domain_Model_Request_DataExportRequest');
            $dataExportRequest->insertParams($args);
            
            $this->_dataExporter->setRequest($dataExportRequest);
            $this->_dataExporter->exec();
            $exportData = $this->_dataExporter->getData();
            
            if (!$exportData) {
                $this->flashMessageContainer->add("Could not write output file for some reasons...", ':(', t3lib_FlashMessage::ERROR);
            } else {
                $this->flashMessageContainer->add("File with prepared data successfully written!", ':)', t3lib_FlashMessage::INFO);
                $this->view->assign('data', $exportData);
            }
        }

        $this->view->assign('step1', 'true');
        $this->view->assign('headline', 'This is where you would prepare your data');
        $this->view->assign('message', $this->flashMessageContainer->getAllMessagesAndFlush());
    }
    
    public function step2Action() {
        /*
            $_GET[] may contain: (&key [= std. value])
            - inputDataFile = Input.xml
            - usedPhpHooks[] = array() (i.e. all hooks)
            - used
        */
        
        $args = $this->request->getArguments();
        
        $transformationRequest = $this->objectManager->get('Tx_Zeitenwende_Domain_Model_Request_DataTransformationRequest');
        $transformationRequest->insertParams($args);
        
        $dataTransformator = $this->objectManager->get('Tx_Zeitenwende_Domain_Model_Data_Transformator');
        $dataTransformator->setRequest($transformationRequest);
        
        if (isset($_POST['transformData'])) {
            
                echo "<pre>";
                    echo "<span style=\"background-color:#bada55\">";
                        var_dump($args);
                    echo "</span>";
                echo "</pre>";
                die("-- died in ".__FILE__.", line ".__LINE__);
            
            
            $ret = $this->transformData();
            
            if (!$ret) {
                $this->view->assign('color', 'red');
                $this->view->assign('message', "Could not write output file for some reasons...");
            } else {
                $this->view->assign('color', "green");
                $this->view->assign('message', "File with prepared data successfully written!");
                $this->view->assign('data', $ret);
            }            
        }
            
        $this->view->assign('step2', 'true');
        $this->view->assign('headline', 'This is where you would export your data');        
    }
    
    public function step3Action() {
        $this->view->assign('step3', 'true');
        $this->view->assign('message', 'This describes how you would import your data');        
    }
    
    
    private function transformData() {
        $exportDataProviderObj = t3lib_div::makeInstance(
            'Tx_Zeitenwende_Domain_Service_ExportDataProvider'
        );

        $exportDataProviderObj->transform();
        $outputFile = dirname(__FILE__).'/../../Resources/Private/Data/Output.xml';
        file_put_contents($outputFile, $exportDataProviderObj->getExportData());
        return $exportDataProviderObj->getExportData();
    }
    
    private function prepareExport() {
        
        /* --> ExportDataProvider.php->getData(
            $tables = array('tt_content','pages'),
            $outputFile = 'Input.xml',
            $initNode = 0,
            
        );
        */
        require_once (t3lib_extMgm::extPath('impexp').'class.tx_impexp.php');
        $this->dataGenerator = t3lib_div::makeInstance('tx_impexp');
        $this->dataGenerator->init();

        
        $tree = t3lib_div::makeInstance('t3lib_pageTree');
        $tree->init();
        $tree->getTree(0);
        $idH = $tree->buffer_idH;
        $this->dataGenerator->setPageTree($idH);
        
        
        $this->prepareRecordsFrom('tt_content');
        $this->prepareRecordsFrom('pages');
                
        $outputFile = dirname(__FILE__).'/../../Resources/Private/Data/Input.xml';
        $outputContent = $this->dataGenerator->createXML();
        
        if (strlen($outputFile) > 0 && strlen($outputContent) > 0) {
            if (! file_put_contents($outputFile, $outputContent) > 0) {
                throw new Exception("Could not write XML file.");
            }
            return $outputContent;
        }
        return false;
    }
    
    private function prepareRecordsFrom($table) {
        /* --> ExportDataProvider.php->prepareRecordsFrom(
            $table
        );
        */
        $tableRows = $this->getExportableRowUids($table);
        foreach ($tableRows as $uid => $a) {
            $this->dataGenerator->export_addRecord($table, t3lib_BEfunc::getRecord($table, $uid));
        }    
    }
    
    private function getExportableRowUids($table, $returnDeleted = TRUE) {
        /* --> ExportDataProvider.php->getExportableRowUids(
            $table,
            $returnDeleted = TRUE
        );
        */
        $select = 'uid';
        $from = $table;
        $where = '1';
        if (!$returnDeleted) {
            $where .= ' AND deleted=0';
        }
        
        $rows = array();
        $rows = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $from, $where, '', '', '', 'uid');
        
        return $rows;
    }
}