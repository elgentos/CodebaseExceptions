<?php

class Elgentos_CodebaseExceptions_Helper_Data extends Mage_Core_Helper_Abstract {
    protected function isDisabled() {
        $apiKey  = Mage::getStoreConfig('codebaseexceptions/general/apikey');
        $disabled = Mage::getStoreConfigFlag('codebaseexceptions/general/disabled');
        if ($disabled || strlen(trim($apiKey)) == 0) {
            return true;
        }
        return false;
    }

    public function __construct() {
        $apiKey  = Mage::getStoreConfig('codebaseexceptions/general/apikey');

        if($this->isDisabled()) return;

        require_once Mage::getBaseDir('lib') . '/Airbrake/Client.php';
        require_once Mage::getBaseDir('lib') . '/Airbrake/Configuration.php';

        $options = array();
        // REQUEST_URI is not available in the CLI context
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = explode("/", $_SERVER['REQUEST_URI']);
            $options['action'] = array_pop($requestUri);
            $options['component'] = implode('/',array_slice($requestUri,-2));
        } else {
            $options['action'] = $_SERVER['PHP_SELF'];
            $options['component'] = $_SERVER['PHP_SELF'];
        }

        $projectRoot = explode('/',$_SERVER['PHP_SELF']);
        array_pop($projectRoot);
        $options['projectRoot'] = implode('/',$projectRoot).'/';
        $options['host'] = Mage::getStoreConfig('codebaseexceptions/general/host');
        $options['secure'] = Mage::getStoreConfig('codebaseexceptions/general/secure');
        $options['environmentName'] = Mage::getStoreConfig('codebaseexceptions/general/environment');
        $config = new Airbrake\Configuration($apiKey,$options);
        $this->client = new Airbrake\Client($config);
    }

    public function insertException($reportData) {
        if ($this->isDisabled()) return;
        $backtraceLines = explode("\n", $reportData[1]);
        $backtraces = $this->formatStackTraceArray($backtraceLines);

        $this->client->notifyOnError($reportData[0], $backtraces);
    }

    /**
     * @param string $message
     * @param int $backtraceLinesToSkip Number of backtrace lines/frames to skip
     */
    public function sendToAirbrake($message, $backtraceLinesToSkip=1) {
        if ($this->isDisabled()) return;

        $message = trim($message);
        $messageArray = explode("\n", $message);
        if (empty($messageArray)) {
            return;
        }
        $errorClass = 'PHP Error';
        $errorMessage = array_shift($messageArray);
        $backTrace = array_slice(debug_backtrace(), $backtraceLinesToSkip);

        $matches = array();
        if (preg_match('/exception \'(.*)\' with message \'(.*)\' in .*/', $errorMessage, $matches)) {
            $errorMessage = $matches[2];
            $errorClass = $matches[1];
        }
        if (count($messageArray) > 0) {
            $errorMessage .= '... [truncated]';
        }

        $notice = new \Airbrake\Notice;
        $notice->load(array(
            'errorClass'   => $errorClass,
            'backtrace'    => $backTrace,
            'errorMessage' => $errorMessage,
        ));

        $this->client->notify($notice);
    }

    /**
     * @param array $backtraceLines
     * @return array
     */
    protected function formatStackTraceArray($backtraceLines) {
        $backtraces = array();

        foreach($backtraceLines as $backtrace) {
            $temp = array();
            $parts = explode(': ',$backtrace);

            if (isset($parts[1])) {
                $temp['function'] = $parts[1];
            }

            $temp['file'] = substr($parts[0],0,stripos($parts[0],'('));
            $temp['line'] = substr($parts[0],stripos($parts[0],'(')+1,(stripos($parts[0],')')-1)-stripos($parts[0],'('));

            if(!empty($temp['function'])) {
                $backtraces[] = $temp;
            }
        }
        return $backtraces;
    }
}
