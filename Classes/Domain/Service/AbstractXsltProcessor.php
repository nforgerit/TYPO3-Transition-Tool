<?php

abstract class Tx_T3tt_Domain_Service_AbstractXsltProcessor {

	/**
	 * @return Tx_T3tt_Domain_Service_AbstractXsltProcessor
	 */
	public function setInputData() {}

	/**
	 * @return Tx_T3tt_Domain_Service_AbstractXsltProcessor
	 */
	public function setStylesheet() {}

	/**
	 * @return string
	 */
    public function render() {}
}