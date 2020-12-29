<?php
global $DB;
$db_type = mb_strtolower($DB->type);
CModule::AddAutoloadClasses(
  'integrations',
  [
    'RNS\Integrations\ExchangeTypeTable' => 'classes/'.$db_type.'/ExchangeTypeTable.php',
    'RNS\Integrations\ExternalSystemTable' => 'classes/'.$db_type.'/ExternalSystemTable.php',
    'RNS\Integrations\SystemExchangeTypeTable' => 'classes/'.$db_type.'/SystemExchangeTypeTable.php',
    'RNS\Integrations\Helpers\Column' => 'classes/helpers/Column.php',
    'RNS\Integrations\Helpers\TableHelper' => 'classes/'.$db_type.'/TableHelper.php',
    'RNS\Integrations\Helpers\EntityFacade' => 'classes/helpers/EntityFacade.php',
    'JsonMapper' => 'classes/helpers/JsonMapper.php',
    'RNS\Integrations\Models\OptionsBase' => 'classes/models/OptionsBase.php',
    'RNS\Integrations\Models\DatabaseOptions' => 'classes/models/DatabaseOptions.php',
    'RNS\Integrations\Models\EntityTypeMapItem' => 'classes/models/EntityTypeMapItem.php',
    'RNS\Integrations\Models\EntityTypeMap' => 'classes/models/EntityTypeMap.php',
    'RNS\Integrations\Models\EntityStatusMapItem' => 'classes/models/EntityStatusMapItem.php',
    'RNS\Integrations\Models\EntityStatusMap' => 'classes/models/EntityStatusMap.php',
    'RNS\Integrations\Models\PropertyMapItem' => 'classes/models/PropertyMapItem.php',
    'RNS\Integrations\Models\PropertyMap' => 'classes/models/PropertyMap.php',
    'RNS\Integrations\Models\EntityMapItem' => 'classes/models/EntityMapItem.php',
    'RNS\Integrations\Models\EntityMap' => 'classes/models/EntityMap.php',
    'RNS\Integrations\Models\UserMapItem' => 'classes/models/UserMapItem.php',
    'RNS\Integrations\Models\UserMap' => 'classes/models/UserMap.php',
    'RNS\Integrations\Models\ResponsibleSettings' => 'classes/models/ResponsibleSettings.php',
    'RNS\Integrations\Models\Mapping' => 'classes/models/Mapping.php',
    'RNS\Integrations\Models\SystemExchangeType' => 'classes/models/SystemExchangeType.php',
    'RNS\Integrations\Processors\Database\Import' => 'classes/processors/database/Import.php',
    'RNS\Integrations\Processors\DataTransferBase' => 'classes/processors/DataTransferBase.php',
    'RNS\Integrations\Processors\DataProviderBase' => 'classes/processors/DataProviderBase.php',
  ]
);
