<?php
class Elgentos_CodebaseExceptions_IndexController extends Mage_Core_Controller_Front_Action {

    public function testAction() {
        Mage::logException( new Exception('This is just a test exception to check whether the extension can handle logged exception'));
        Mage::log('This is just a test to check whether the extension can handle single line warning logged to Magento log', Zend_Log::WARN);
        Mage::log("This is just a test\nto check whether the extension can handle multiline warning \nlogged to Magento log", Zend_Log::WARN);
        throw new Exception('This is just a test exception to check whether the extension is working.');
    }

}