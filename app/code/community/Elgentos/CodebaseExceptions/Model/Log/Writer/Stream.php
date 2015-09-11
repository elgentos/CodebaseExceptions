<?php

class Elgentos_CodebaseExceptions_Model_Log_Writer_Stream extends Zend_Log_Writer_Stream
{
    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     * @throws Zend_Log_Exception
     */
    protected function _write($event)
    {
        $line = $this->_formatter->format($event);

        if ($event['priority'] <= Zend_Log::WARN) {
            Mage::helper('codebaseexceptions')->sendToAirbrake($event['message'], 4);
        }
        if (false === @fwrite($this->_stream, $line)) {
            throw new Zend_Log_Exception("Unable to write to stream");
        }
    }
}
