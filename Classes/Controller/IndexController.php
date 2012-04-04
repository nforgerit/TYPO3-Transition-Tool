<?php

class Tx_FourOut_Controller_IndexController extends Tx_Extbase_MVC_Controller_ActionController {

    protected $_dataGenerator;
    
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
    
        if (isset($_POST['generateData'])) {
            $ret = $this->prepareExport();
            
            if (!$ret) {
                $this->view->assign('color', "red");
                $this->view->assign('message', "Could not write output file for some reasons...");
            } else {
                $this->view->assign('color', "green");
                $this->view->assign('message', "File with prepared data successfully written!");
                $this->view->assign('data', $ret);
            }
        }

        $this->view->assign('step1', 'true');
        $this->view->assign('headline', 'This is where you would prepare your data');
    }
    
    public function step2Action() {
        
        if (isset($_POST['transformData'])) {
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
            'Tx_FourOut_Domain_Service_ExportDataProvider',
            dirname(__FILE__).'/../../Resources/Private/Data/Input.xml',
            dirname(__FILE__).'/../../Resources/Private/XSLT/Stylesheets/',
            dirname(__FILE__).'/../../Resources/Private/PHP/Transition/',
            dirname(__FILE__).'/../../Resources/Private/XSLT/Log/XsltProcessor.txt'
        );

        $exportDataProviderObj->transform();
        $outputFile = dirname(__FILE__).'/../../Resources/Private/Data/Output.xml';
        file_put_contents($outputFile, $exportDataProviderObj->getExportData());
        return $exportDataProviderObj->getExportData();
    }
    
    private function prepareExport() {
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
        if (file_put_contents($outputFile, $this->dataGenerator->createXML())) {
            return $this->dataGenerator->createXML();
        } else {
            return false;
        }
    }
    
    private function prepareRecordsFrom($table) {
        $tableRows = $this->getExportableRowUids($table);
        foreach ($tableRows as $uid => $a) {
            $this->dataGenerator->export_addRecord($table, t3lib_BEfunc::getRecord($table, $uid));
        }    
    }
    
    private function getExportableRowUids($table, $returnDeleted = TRUE) {
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