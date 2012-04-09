<?php

class Tx_Zeitenwende_Controller_TransformationStepsController extends Tx_Extbase_MVC_Controller_ActionController {


public function step1Action() {
    
    
}
public function prepareDataAction() {
    $this->forward('step1');
}
    
public function step2Action() {}
public function transformDataAction() {
    $this->forward('step2');
}    

public function step3Action() {}
public function importDataAction() {
    $this->forward('step3');
}

}