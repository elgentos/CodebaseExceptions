<?php

class Elgentos_CodebaseExceptions_Helper_Data extends Mage_Core_Helper_Abstract {

    public function __construct() {
        if(Mage::getStoreConfig('codebaseexceptions/general/disabled')) return;

        require_once Mage::getBaseDir('lib') . '/Airbrake/Client.php';
        require_once Mage::getBaseDir('lib') . '/Airbrake/Configuration.php';

        $apiKey  = Mage::getStoreConfig('codebaseexceptions/general/apikey');
        $options = array();
        $requestUri = explode("/",$_SERVER['REQUEST_URI']);
        $options['action'] = array_pop($requestUri);
        $options['component'] = implode('/',array_slice($requestUri,-2));
        $projectRoot = explode('/',$_SERVER['PHP_SELF']);
        array_pop($projectRoot);
        $options['projectRoot'] = implode('/',$projectRoot).'/';
        $config = new Airbrake\Configuration($apiKey,$options);
        $this->client = new Airbrake\Client($config);
    }

    public function insertException($reportData) {
        if(Mage::getStoreConfig('codebaseexceptions/general/disabled')) return;
        $backtraceLines = explode("\n",$reportData[1]);
        foreach($backtraceLines as $backtrace) {
            $temp = array();
            $parts = explode(': ',$backtrace);
            $temp['function'] = $parts[1];
            $temp['file'] = substr($parts[0],0,stripos($parts[0],'('));
            $temp['line'] = substr($parts[0],stripos($parts[0],'(')+1,(stripos($parts[0],')')-1)-stripos($parts[0],'('));

            if(!empty($temp['function'])) {
                $backtraces[] = $temp;
            }
        }

        $this->client->notifyOnError($reportData[0],$backtraces);
    }
}
