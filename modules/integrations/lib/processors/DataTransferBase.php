<?php

namespace RNS\Integrations\Processors;

use CEventLog;
use RNS\Integrations\Models\SystemExchangeType;

/**
 * Реализация общего функционала и для импорта и для экспорта данных.
 * @package RNS\Integrations\Processors
 */
abstract class DataTransferBase
{
    protected $exchangeTypeCode;

    protected $options;

    protected $mapping;

    protected $errors = [];

    public function run(int $systemExchangeTypeId)
    {
        $obj = SystemExchangeType::getById($systemExchangeTypeId);

        $this->exchangeTypeCode = $obj->getExchangeTypeCode();
        $this->options = $obj->getOptions();
        $this->mapping = $obj->getMapping();

        $this->execute();
    }

    public function getCapabilities()
    {
        return [];
    }

    protected function addError(string $errorText)
    {
        $this->errors[] = $errorText;
    }

    protected function log(string $message, string $eventType, string $severity = 'ERROR')
    {
        CEventLog::Add([
          'SEVERITY' => $severity,
          'AUDIT_TYPE_ID' => $eventType,
          'MODULE_ID' => 'integrations',
          'DESCRIPTION' => $message
        ]);
    }

    protected abstract function execute();
}
