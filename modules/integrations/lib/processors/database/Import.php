<?php

namespace RNS\Integrations\Processors\Database;

use RNS\Integrations\Helpers\EntityFacade;
use RNS\Integrations\Processors\DataTransferBase;

/**
 * Реализация импорта из базы данных.
 * @package RNS\Integrations\Processors\Database
 */
class Import extends DataTransferBase
{
    public function getCapabilities()
    {
        return [
          'supportedDBMS' => [
            'REFERENCE_ID' => ['pgsql'],
            'REFERENCE' => ['PostgreSQL']
          ]
        ];
    }

    protected function execute()
    {
        $provider = EntityFacade::getDataProvider($this->exchangeTypeCode, $this->options, $this->mapping);

        $entities = $provider->getEntities($this->systemCode);


    }
}