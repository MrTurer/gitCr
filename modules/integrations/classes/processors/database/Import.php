<?php

namespace RNS\Integrations\Processors\Database;

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

    }
}