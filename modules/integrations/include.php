<?php
global $DB;
$db_type = mb_strtolower($DB->type);
CModule::AddAutoloadClasses(
  'integrations',
  [
    'RNS\Integrations\ExchangeTypeTable' => 'lib/'.$db_type.'/ExchangeTypeTable.php',
    'RNS\Integrations\ExternalSystemTable' => 'lib/'.$db_type.'/ExternalSystemTable.php',
    'RNS\Integrations\SystemExchangeTypeTable' => 'lib/'.$db_type.'/SystemExchangeTypeTable.php',
    'RNS\Integrations\Helpers\Column' => 'lib/helpers/Column.php',
    'RNS\Integrations\Helpers\TableHelper' => 'lib/'.$db_type.'/TableHelper.php',
    'RNS\Integrations\Helpers\HLBlockHelper' => 'lib/helpers/HLBlockHelper.php',
    'RNS\Integrations\Helpers\EntityFacade' => 'lib/helpers/EntityFacade.php',
    'JsonMapper' => 'lib/helpers/JsonMapper.php',
    'RNS\Integrations\Models\OptionsBase' => 'lib/models/OptionsBase.php',
    'RNS\Integrations\Models\DatabaseOptions' => 'lib/models/DatabaseOptions.php',
    'RNS\Integrations\Models\EntityTypeMapItem' => 'lib/models/EntityTypeMapItem.php',
    'RNS\Integrations\Models\EntityTypeMap' => 'lib/models/EntityTypeMap.php',
    'RNS\Integrations\Models\EntityStatusMapItem' => 'lib/models/EntityStatusMapItem.php',
    'RNS\Integrations\Models\EntityStatusMap' => 'lib/models/EntityStatusMap.php',
    'RNS\Integrations\Models\PropertyMapItem' => 'lib/models/PropertyMapItem.php',
    'RNS\Integrations\Models\PropertyMap' => 'lib/models/PropertyMap.php',
    'RNS\Integrations\Models\EntityMapItem' => 'lib/models/EntityMapItem.php',
    'RNS\Integrations\Models\EntityMap' => 'lib/models/EntityMap.php',
    'RNS\Integrations\Models\UserMapItem' => 'lib/models/UserMapItem.php',
    'RNS\Integrations\Models\UserMap' => 'lib/models/UserMap.php',
    'RNS\Integrations\Models\ResponsibleSettings' => 'lib/models/ResponsibleSettings.php',
    'RNS\Integrations\Models\Mapping' => 'lib/models/Mapping.php',
    'RNS\Integrations\Models\SystemExchangeType' => 'lib/models/SystemExchangeType.php',
    'RNS\Integrations\Processors\Database\Import' => 'lib/processors/database/Import.php',
    'RNS\Integrations\Processors\DataTransferResult' => 'lib/processors/DataTransferResult.php',
    'RNS\Integrations\Processors\DataTransferBase' => 'lib/processors/DataTransferBase.php',
    'RNS\Integrations\Processors\DataProviderBase' => 'lib/processors/DataProviderBase.php',
    'RNS\Integrations\Processors\IntegrationAgent' => 'lib/processors/IntegrationAgent.php',
    'RNS\Integrations\Controller\Entity' => 'lib/controller/Entity.php',
  ]
);
