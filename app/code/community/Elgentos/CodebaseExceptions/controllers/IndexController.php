<?php
class Elgentos_CodebaseExceptions_IndexController extends Mage_Core_Controller_Front_Action {

    public function testAction() {
        throw new Exception('This is just a test exception to check whether the extension is working.');
    }

}