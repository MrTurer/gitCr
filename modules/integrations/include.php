<?php
global $DB;
$db_type = mb_strtolower($DB->type);
CModule::AddAutoloadClasses(
  'integrations',
  [
    'RNS\Integrations\ExchangeTypeTable' => 'classes/'.$db_type.'/ExchangeTypeTable.php',
    'RNS\Integrations\ExternalSystemTable' => 'classes/'.$db_type.'/ExternalSystemTable.php',
    'RNS\Integrations\MapTypeTable' => 'classes/'.$db_type.'/MapTypeTable.php',
    'RNS\Integrations\SystemExchangeTypeTable' => 'classes/'.$db_type.'/SystemExchangeTypeTable.php',
    'RNS\Integrations\Helpers\Column' => 'classes/helpers/Column.php',
    'RNS\Integrations\Helpers\TableHelper' => 'classes/helpers/TableHelper.php',
    'JsonMapper' => 'classes/helpers/JsonMapper.php',
    'RNS\Integrations\Models\OptionsBase' => 'classes/models/OptionsBase.php',
    'RNS\Integrations\Models\DatabaseOptions' => 'classes/models/DatabaseOptions.php',
    'RNS\Integrations\Models\AttributeMapItem' => 'classes/models/AttributeMapItem.php',
    'RNS\Integrations\Models\AttributeMap' => 'classes/models/AttributeMap.php',
    'RNS\Integrations\Models\EntityMapItem' => 'classes/models/EntityMapItem.php',
    'RNS\Integrations\Models\EntityMap' => 'classes/models/EntityMap.php',
    'RNS\Integrations\Models\UserMapItem' => 'classes/models/UserMapItem.php',
    'RNS\Integrations\Models\UserMap' => 'classes/models/UserMap.php',
    'RNS\Integrations\Models\MApping' => 'classes/models/MApping.php',
    'RNS\Integrations\Models\SystemExchangeType' => 'classes/models/SystemExchangeType.php',
  ]
);
