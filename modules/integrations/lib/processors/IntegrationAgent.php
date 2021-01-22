<?php

namespace RNS\Integrations\Processors;

use RNS\Integrations\Models\SystemExchangeType;
use RNS\Integrations\SystemExchangeTypeTable;

class IntegrationAgent
{
    public static function run(int $id)
    {
        $systemExchangeType = SystemExchangeType::getById($id);
        $exchangeTypeCode = $systemExchangeType->getExchangeTypeCode();
        $options = $systemExchangeType->getOptions();
        $mapping = $systemExchangeType->getMapping();
        $className = $systemExchangeType->getDirection() == SystemExchangeTypeTable::DIRECTION_IMPORT ? 'Import' : 'Export';

        $processorClassPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/integrations/lib/processors/' .
            $exchangeTypeCode . '/' . $className . '.php';

        include_once($processorClassPath);

        $processorClass = "RNS\\Integrations\\Processors\\{$exchangeTypeCode}\\{$className}";

        /** @var DataTransferBase $processor */
        $processor = new $processorClass($options, $mapping);

        $processor->run($id);

        return "\\RNS\\Integrations\\Processors\\IntegrationAgent::run({$id});";
    }
}
