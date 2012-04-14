<?php

class Tx_T3tt_Controller_IndexController extends Tx_Extbase_MVC_Controller_ActionController {
    
    protected $_args;
        
    public function initializeAction() {    
        $this->_args = $this->request->getArguments();
        $this->fetchEmConfiguration();
    }
    
    public function indexAction() {
        $this->forward('step1');
    }

public function step1Action() {
        $this->flashMessageContainer->add("Hello there and welcome to `TYPO3 Transition Tool'" , 'Welcome', t3lib_FlashMessage::INFO);

        if ($this->_args['generateData'] === 'Generate') {        
            $dataExportRequest = $this->objectManager->get('Tx_T3tt_Domain_Model_Request_DataExportRequest');
            $dataExportRequest->insertParams($this->_args);
            
            $dataExporter = $this->objectManager->get('Tx_T3tt_Domain_Model_Data_Exporter');
            $dataExporter
                ->setRequest($dataExportRequest)
                ->exec();
                
            if ($dataExporter->writeDataToOutputFile()) {
                $this->flashMessageContainer->add("File with prepared data successfully written! Now go to Step2.", ':)', t3lib_FlashMessage::INFO);
            } else {
                $this->flashMessageContainer->add("Could not write output file for some reasons...", ':(', t3lib_FlashMessage::ERROR);
            }
            $this->view->assign('data', $dataExporter->getData());
        }

        $this->view->assign('headline', 'This is where you would prepare your data');
    }
    
    public function step2Action() {            
        if ($this->_args['transformData'] === 'Transform') {
            $transformationRequest = $this->objectManager->get('Tx_T3tt_Domain_Model_Request_DataTransformationRequest');
            $transformationRequest->insertParams($this->_args);
            
            $dataTransformator = $this->objectManager->get('Tx_T3tt_Domain_Model_Data_Transformator');
            $dataTransformator
                ->setRequest($transformationRequest)
                ->exec();
                            
            if ($dataTransformator->writeDataToOutputFile()) {
                $this->flashMessageContainer->add("File `Output.xml' with transformed data successfully written! Now switch to Step3", ':)', t3lib_FlashMessage::INFO);
            } else {
                $this->flashMessageContainer->add("Could not write output file for some reasons...", ':(', t3lib_FlashMessage::ERROR);
            }            
            $this->view->assign('data', $dataTransformator->getData());
        }
            
    }
    
    public function step3Action() {
        $dstFolder = '<YOUR_FLOW3_ROOT>/Packages/Sites/TYPO3.$siteKey/Resources/Private/Content';
        $this->flashMessageContainer->add("Alright, now put the Sites.xml into ".$dstFolder, ':)', t3lib_FlashMessage::INFO);
        
        $target = t3lib_extMgm::extPath('t3tt').'Resources/Private/Data/Output.xml';
        $link = PATH_site.'/fileadmin/t3tt/Output.xml';
        symlink($target, $link);
        
        $this->view->assign('downloadFile', $link);
    }
    
    public function downloadOutputFileAction() {
        $link = PATH_site.'/fileadmin/t3tt/Output.xml';
        
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"Sites.xml\"");        
        echo file_get_contents(readlink($link));
        die();
    }
    
    private function fetchEmConfiguration() {
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['t3tt']);
        $var1 = $this->extConf['name_der_variable'];
        
        $this->_args['EMCONF']['transformationInputDirectory'] = $extConf['Transformation_Input_Directory'];
        $this->_args['EMCONF']['transformationInputFilename'] = $extConf['Transformation_Input_Filename'];
        $this->_args['EMCONF']['transformationOutputDirectory'] = $extConf['Transformation_Output_Directory'];
        $this->_args['EMCONF']['transformationOutputFilename'] = $extConf['Transformation_Output_Filename'];
    }

}