<?php

namespace RNS\Integrations\Processors;

use CEventLog;
use RNS\Integrations\Models\IntegrationOptions;
use RNS\Integrations\Models\Mapping;
use RNS\Integrations\Models\SystemExchangeType;

/**
 * Реализация общего функционала и для импорта и для экспорта данных.
 * @package RNS\Integrations\Processors
 */
abstract class DataTransferBase
{
    protected $systemCode;

    protected $exchangeTypeCode;

    /** @var IntegrationOptions */
    protected $integrationOptions;

    protected $options;

    /** @var Mapping */
    protected $mapping;

    /** @var DataTransferResult */
    protected $result;

    /**
     * @param int $systemExchangeTypeId
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function run(int $systemExchangeTypeId)
    {
        $obj = SystemExchangeType::getById($systemExchangeTypeId);

        $this->systemCode = $obj->getSystemCode();
        $this->exchangeTypeCode = $obj->getExchangeTypeCode();
        $this->options = $obj->getOptions();
        $this->mapping = $obj->getMapping();
        $this->integrationOptions = new IntegrationOptions($this->systemCode);

        $this->execute();

        if (!$this->result->success) {
            throw new \Exception(implode("\n", $this->result->errors));
        }
    }

    public function getCapabilities()
    {
        return [];
    }

    public function getResult()
    {
        return $this->result;
    }

    protected function addError(string $errorText)
    {
        $this->result->errors[] = $errorText;
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
