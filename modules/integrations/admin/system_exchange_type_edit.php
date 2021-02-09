<?php

use Bitrix\Main\Localization\Loc;
use RNS\Integrations\ExchangeTypeTable;
use RNS\Integrations\ExternalSystemTable;
use RNS\Integrations\Helpers\EntityFacade;
use RNS\Integrations\Models\SystemExchangeType;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/admin_tools.php');
if (!CModule::IncludeModule("integrations")) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

Loc::loadMessages(__FILE__);

$backUrl = 'integrations_system_exchange_type_list.php?lang=' . LANGUAGE_ID;

$obj = SystemExchangeType::getById($ID);

if ((!empty($save) || !empty($apply)) && is_array($_POST)) {
    $fields = $_POST;

    $obj->save($fields);

    if (!empty($save)) {
        LocalRedirect($backUrl );
    } else {
        LocalRedirect($_SERVER['PHP_SELF'] . '?ID=' . $obj->getId() . '&lang=' . LANGUAGE_ID);
    }
}

$options = $obj->getOptions();
$mapping = $obj->getMapping();

$rows = ExternalSystemTable::getList([
  'select' => ['*'],
  'order' => ['ID' => 'ASC']
])->fetchAll();
$externalSystems = [['REFERENCE_ID' => [], 'REFERENCE' => []]];
foreach ($rows as $item) {
    $externalSystems['REFERENCE_ID'][] = $item['ID'];
    $externalSystems['REFERENCE'][] = $item['NAME'];
}

$rows = ExchangeTypeTable::getList([
  'select' => ['*'],
  'order' => ['ID' => 'ASC']
])->fetchAll();
$exchangeTypes = ['REFERENCE_ID' => [], 'REFERENCE' => []];
foreach ($rows as $item) {
    $exchangeTypes['REFERENCE_ID'][] = $item['ID'];
    $exchangeTypes['REFERENCE'][] = $item['NAME'];
}

$directions = [
  'REFERENCE_ID' => [0, 1],
  'REFERENCE' => ['Импорт', 'Экспорт']
];

$tabs = [
  ['DIV' => 'tab-1', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL')]
];

$noneSelected = Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_NONE_SELECTED');
$emptyDict = [
  'REFERENCE_ID' => [null],
  'REFERENCE' => [$noneSelected]
];

if ($ID > 0) {
    \Bitrix\Main\UI\Extension::load("ui.vue");

    $systemCode = $obj->getSystemCode();

    $localProjects = EntityFacade::getProjects();
    $localUsers = EntityFacade::getUsers();

    $entityTypes = EntityFacade::getEntityTypes();
    $entityProps = EntityFacade::getEntityProperties();
    $externalEntityTypes = EntityFacade::getExternalEntityTypes($systemCode);
    $externalEntityStatuses = EntityFacade::getExternalEntityStatuses($systemCode);
    $externalEntityProps = EntityFacade::getExternalEntityProperties($systemCode);

    $entityTypeOptions = [];
    foreach ($entityTypes['REFERENCE_ID'] as $i => $id) {
        $entityTypeOptions[] = '<option value="'. $id . '">' . $entityTypes['REFERENCE'][$i] . '</option>';
    }
    $externalEntityTypeOptions = [];
    $propertyMapping = $mapping->getEntityPropertyMap();
    foreach ($externalEntityTypes['REFERENCE_ID'] as $i => $id) {
        $externalEntityTypeOptions[] = '<option value="'. $id . '">' . $externalEntityTypes['REFERENCE'][$i] . '</option>';

        foreach ($externalEntityProps['REFERENCE_ID'] as $propId) {
            if (!$propertyMapping->getItemByExternalPropertyId($id, $propId)) {
                $propertyMapping->addItem($id, $propId);
            }
        }
    }

    $externalEntityStatusOptions = [];
    foreach ($externalEntityStatuses['REFERENCE_ID'] as $i => $id) {
        $externalEntityStatusOptions[] = '<option value="'. $id . '">' . $externalEntityStatuses['REFERENCE'][$i] . '</option>';
    }

    $entityPropertyOptions = [];
    foreach ($entityProps['REFERENCE_ID'] as $i => $id) {
        $entityPropertyOptions[] = '<option value="'. $id . '">' . $entityProps['REFERENCE'][$i] . '</option>';
    }
    $externalEntityPropertyOptions = [];

    foreach ($externalEntityProps['REFERENCE_ID'] as $i => $id) {
        $externalEntityPropertyOptions[] = '<option value="'. $id . '">' . $externalEntityProps['REFERENCE'][$i] . '</option>';
    }

    $exchType = $obj->getExchangeTypeCode();

    $externalProjects = [
      'REFERENCE_ID' => [],
      'REFERENCE' => []
    ];
    $externalProjectList = EntityFacade::getExternalProjects($exchType, $systemCode, $obj->getOptions(), $obj->getMapping());
    $externalProjectOptions = [];
    $projectMapping = $mapping->getProjectMap();
    $entityTypeMapping = $mapping->getEntityTypeMap();
    $entityStatusMapping = $mapping->getEntityStatusMap();
    foreach ($externalProjectList as $id => $name) {
        $externalProjectOptions[] = '<option value="'. $id . '">' . $name . '</option>';

        $externalProjects['REFERENCE_ID'][] = $id;
        $externalProjects['REFERENCE'][] = $name;
        if (empty($projectMapping->getItemsByExternalId($id))) {
            $mapping->getProjectMap()->addItem($id);
        }

        foreach ($externalEntityTypes['REFERENCE_ID'] as $typeId) {
            if (!$entityTypeMapping->getItemByExternalTypeId($typeId, $id)) {
                $entityTypeMapping->addItem($id, $typeId);
            }
            foreach ($externalEntityStatuses['REFERENCE_ID'] as $statusId) {
                if (!$entityStatusMapping->getItemByExternalStatusId($statusId, $typeId, $id)) {
                    $entityStatusMapping->addItem($id, $typeId, $statusId);
                }
            }
        }
    }

    $localProjectOptions = [];
    foreach ($localProjects['REFERENCE_ID'] as $i => $id) {
        $localProjectOptions[] = '<option value="'. $id . '">' . $localProjects['REFERENCE'][$i] . '</option>';
    }

    $localUserOptions = [];
    foreach ($localUsers['REFERENCE_ID'] as $i => $id) {
        $localUserOptions[] = '<option value="'. $id . '">' . $localUsers['REFERENCE'][$i] . '</option>';
    }

    $externalUsers = [
      'REFERENCE_ID' => [],
      'REFERENCE' => []
    ];
    $users = EntityFacade::getExternalUsers($exchType, $systemCode, $obj->getOptions(), $obj->getMapping());
    foreach ($users as $id => $name) {
        $externalUsers['REFERENCE_ID'][] = $id;
        $externalUsers['REFERENCE'][] = $name;
    }
    $externalUserOptions = [];
    foreach ($externalUsers['REFERENCE_ID'] as $i => $id) {
        $externalUserOptions[] = '<option value="'. $id . '">' . $externalUsers['REFERENCE'][$i] . '</option>';
    }

    foreach ($localUsers['REFERENCE_ID'] as $id) {
        if (!$mapping->getUserMap()->getItemByInternalId($id)) {
            $mapping->getUserMap()->addItem($id);
        }
    }

    $tabs = array_merge($tabs, [
      ['DIV' => 'tab-2', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_' . strtoupper($exchType))],
      ['DIV' => 'tab-3', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_PROJECTS')],
      ['DIV' => 'tab-4', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENTITY_TYPES')],
      ['DIV' => 'tab-5', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENTITY_STATUSES')],
      ['DIV' => 'tab-6', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENTITY_PROPS')],
      ['DIV' => 'tab-7', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_USERS')],
      ['DIV' => 'tab-8', 'TAB' => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_RESPONSIBLE')]
    ]);

    switch ($exchType) {
        case ExchangeTypeTable::TYPE_DATABASE:
            $imp = new \RNS\Integrations\Processors\Database\Import();
            $dbms = $imp->getCapabilities()['supportedDBMS'];
            break;
    }
}

$APPLICATION->SetTitle(Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_TITLE'));

$tabControl = new CAdminTabControl('tabControl', $tabs);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
$tabControl->Begin();
?>
<div id="settingsform">
  <form method="POST" name="<?= basename(__FILE__, '.php') ?>" action="<?= $APPLICATION->GetCurUri() ?>">
    <?= bitrix_sessid_post() ?>
    <?php $tabControl->BeginNextTab() ?>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_SYSTEN') ?></td>
        <td>
            <?= SelectBoxFromArray('externalSystem', $externalSystems, $obj->getSystemId()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_EXCH_TYPE') ?></td>
        <td>
            <?= SelectBoxFromArray('exchangeType', $exchangeTypes, $obj->getExchangeTypeId()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_EXCH_DIR') ?></td>
        <td>
            <?= SelectBoxFromArray('exchangeDirection', $directions, $obj->getDirection()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_SCHEDULE') ?></td>
        <td>
            <?= InputType('text', 'schedule', $obj->getSchedule(), false) ?>
        </td>
    </tr
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_TASK_LEVEL') ?>
        </td>
        <td>
            <?= InputType('number', 'options[taskLevel]', $options->getTaskLevel(), false, false, false, 'step="1"') ?>
        </td>
    </tr>
    <tr>
        <td>
            <label for="active"><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_GENERAL_ACTIVE') ?></label>
        </td>
        <td>
            <?= InputType('checkbox', 'active', true, htmlspecialcharsbx($obj->isActive())) ?>
        </td>
    </tr>
    <?php if ($ID > 0): ?>
    <?php $tabControl->BeginNextTab() ?>
    <?php if($exchType == ExchangeTypeTable::TYPE_DATABASE) : ?>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_TYPE') ?></td>
        <td>
            <?= SelectBoxFromArray('options[database][type]', $dbms, $options->getType()) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_HOSTNAME') ?></td>
        <td>
            <?= InputType('text', 'options[database][hostName]', $options->getHostName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_PORT') ?></td>
        <td>
            <?= InputType('text', 'options[database][port]', $options->getPort(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_DATABSENAME') ?></td>
        <td>
            <?= InputType('text', 'options[database][databaseName]', $options->getDatabaseName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_USERNAME') ?></td>
        <td>
            <?= InputType('text', 'options[database][userName]', $options->getUserName(), false) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_DATABASE_PASSWORD') ?></td>
        <td>
            <?= InputType('password', 'options[database][password]', $options->getPassword(), false) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= InputType('button', 'test_connection', Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_TEST_CONNECTION'), false); ?>
        </td>
    </tr>
    <?php endif; ?>
    <?php if ($exchType == ExchangeTypeTable::TYPE_API) : ?>
    <!-- Настройки API -->
    <tr><td></td></tr>
    <?php endif; ?>
    <?php if ($exchType == ExchangeTypeTable::TYPE_EMAIL) : ?>
    <!-- Настройки E-mail -->
    <tr><td></td></tr>
    <?php endif; ?>
    <?php if ($exchType == ExchangeTypeTable::TYPE_FILES) : ?>
    <!-- Настройки импорта/экспорта файлов -->
    <tr><td></td></tr>
    <?php endif; ?>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Экземпляры проектов -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEF_PRJ') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray('mapping[projectMap][defaultEntityId]', $localProjects, $mapping->getProjectMap()->getDefaultEntityId()) ?>
        </td>
    </tr>
        <tr>
            <td class="adm-detail-content-cell-r" colspan="2">
                <input type="button" @click="projectMapAddItem" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
            </td>
        </tr>
    <tr>
        <td colspan="2">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_PRJ') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEST_PRJ') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DELETE_ITEM') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getProjectMap()->getItems() as $i => $item):?>
                <tr>
                    <td class="adm-list-table-cell">
                        <?= SelectBoxFromArray("mapping[projectMap][items][{$i}][externalEntityId]", $externalProjects, $item->getExternalEntityId()) ?>
                    </td>
                    <td class="adm-list-table-cell">
                        <?= SelectBoxFromArray("mapping[projectMap][items][{$i}][internalEntityId]", $localProjects, $item->getInternalEntityId(), $noneSelected,  'onchange="refillSelects(\'projectMap\', \'internalEntityId\', ' . $i . ')"') ?>
                    </td>
                    <td class="adm-list-table-cell">
                        <?= InputType('checkbox', "mapping[projectMap][items][{$i}][deleted]", false, false)?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr v-for="item in projectMap.items">
                    <td class="adm-list-table-cell" v-html="projectMapGetExtEntitySelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="projectMapGetIntEntitySelect(item)"></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <?php $tabControl->BeginNextTab() ?>
    <!-- Типы сущности -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEF_PRJ') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityTypeMap][defaultProjectId]", $localProjects, $mapping->getEntityTypeMap()->getDefaultProjectId()) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_TYPE') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityTypeMap][defaultTypeId]", $entityTypes, $mapping->getEntityTypeMap()->getDefaultTypeId()) ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-r" colspan="4">
            <input type="button" @click="entityTypeMapAddItem" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_PRJ') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_EXT_ENT_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_ENT_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DELETE_ITEM') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getEntityTypeMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityTypeMap][items][{$i}][externalProjectId]", $externalProjects, $mapItem->getExternalProjectId(), $noneSelected, 'onchange="refillSelects(\'entityTypeMap\', \'externalProjectId\', ' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityTypeMap][items][{$i}][externalTypeId]", $externalEntityTypes, $mapItem->getExternalTypeId(), $noneSelected, 'onchange="refillSelects(\'entityTypeMap\', \'externalTypeId\', ' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityTypeMap][items][{$i}][internalTypeId]", $entityTypes, $mapItem->getInternalTypeId(), $noneSelected, 'onchange="refillSelects(\'entityTypeMap\', \'internalTypeId\', ' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= InputType('checkbox', "mapping[entityTypeMap][items][{$i}][deleted]", false, false)?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in entityTypeMap.items">
                    <td class="adm-list-table-cell" v-html="entityTypeMapGetExtProjectSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityTypeMapGetExtEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityTypeMapGetIntEntityTypeSelect(item)"></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Статусы сущности -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEF_PRJ') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityStatusMap][defaultProjectId]", $localProjects, $mapping->getEntityStatusMap()->getDefaultProjectId()) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_TYPE') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityStatusMap][defaultTypeId]", $entityTypes, $mapping->getEntityStatusMap()->getDefaultTypeId(), '', 'id="mapping_entityStatusMap_defaultTypeId" onchange="entityStatusMapDefEntityTypeChange()"') ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_STATUS') ?>
        </td>
        <td class="adm-detail-content-cell-r" colspan="3">
            <?= SelectBoxFromArray("mapping[entityStatusMap][defaultStatusId]", [], '', '', 'id="mapping_entityStatusMap_defaultStatusId" data-value="' . $mapping->getEntityStatusMap()->getDefaultStatusId() . '"') ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-r" colspan="4">
            <input type="button" @click="entityStatusMapAddItem" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_SRC_PRJ') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_EXT_ENT_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_EXT_ENT_STATUS') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_ENT_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_ENT_STATUS') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DELETE_ITEM') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getEntityStatusMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][externalProjectId]", $externalProjects, $mapItem->getExternalProjectId(), $noneSelected, 'onchange="refillSelects(\'entityStatusMap\', \'externalProjectId\', ' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][externalTypeId]", $externalEntityTypes, $mapItem->getExternalTypeId(), $noneSelected, 'onchange="refillSelects(\'entityStatusMap\', \'externalTypeId\', ' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][externalStatusId]", $externalEntityStatuses, $mapItem->getExternalStatusId(), $noneSelected) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][internalTypeId]", $entityTypes, $mapItem->getInternalTypeId(), $noneSelected, 'onchange="entityStatusMapEntityTypeChange(' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][internalStatusId]", $emptyDict, null, $noneSelected, 'id="mapping_entityStatusMap_items_' . $i . '_internalStatusId" data-value="' . $mapItem->getInternalStatusId() . '"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= InputType('checkbox', "mapping[entityStatusMap][items][{$i}][deleted]", false, false)?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in entityStatusMap.items">
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetExtProjectSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetExtEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetExtEntityStatusSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetIntEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetIntEntityStatusSelect(item)"></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Свойства сущности -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_TYPE') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityPropertyMap][defaultTypeId]", $entityTypes, $mapping->getEntityPropertyMap()->getDefaultTypeId()) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_PROP') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityPropertyMap][defaultPropertyId]", $entityProps, $mapping->getEntityPropertyMap()->getDefaultPropertyId()) ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-r" colspan="4">
            <input type="button" @click="entityPropertyMapAddItem" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_EXT_ENT_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_EXT_PROP') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_ENT_TYPE') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_PROP') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DELETE_ITEM') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getEntityPropertyMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][externalTypeId]", $externalEntityTypes, $mapItem->getExternalTypeId(), $noneSelected) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][externalPropertyId]", $externalEntityProps, $mapItem->getExternalPropertyId(), $noneSelected) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][internalTypeId]", $entityTypes, $mapItem->getInternalTypeId(), $noneSelected) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][internalPropertyId]", $entityProps, $mapItem->getInternalPropertyId(), $noneSelected, 'onchange="refillSelects(\'entityPropertyMap\', \'internalPropertyId\', ' . $i . ')"') ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= InputType('checkbox', "mapping[entityPropertyMap][items][{$i}][deleted]", false, false)?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in entityPropertyMap.items">
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetExtEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetExtEntityPropSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetIntEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetIntEntityPropSelect(item)"></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Пользователи -->
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_USER_DEF_EXT_EMAIL') ?>
        </td>
        <td>
            <?= InputType('text', 'mapping[userMap][defaultExternalEmail]', $mapping->getUserMap()->getDefaultExternalEmail(), false) ?>
        </td>
        <td colspan="2">
            <label for="mapping[userMap][ignoreAliens]"><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_USER_IGNORE_ALIENS') ?></label>
            <?= InputType('checkbox', 'mapping[userMap][ignoreAliens]', true, htmlspecialcharsbx($mapping->getUserMap()->isIgnoreAliens())) ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-r" colspan="4">
            <input type="button" @click="userMapAddItem" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table class="adm-list-table">
                <thead>
                <tr class="adm-list-table-header">
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_USER') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_EXT_USER') ?>
                        </div>
                    </td>
                    <td class="adm-list-table-cell">
                        <div class="adm-list-table-cell-inner">
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DELETE_ITEM') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getUserMap()->getItems() as $i => $item):?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[userMap][items][{$i}][internalId]", $localUsers, $item->getInternalId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[userMap][items][{$i}][externalId]", $externalUsers, $item->getExternalId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= InputType('checkbox', "mapping[userMap][items][{$i}][deleted]", false, false)?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in userMap.items">
                    <td class="adm-list-table-cell" v-html="userMapGetIntUserSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="userMapGetExtUserSelect(item)"></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Ответственные -->
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_RESP_DEF_RESP') ?></td>
        <td>
            <?= SelectBoxFromArray('mapping[responsibleSettings][defaultResponsibleId]', $localUsers, $mapping->getResponsibleSettings()->getDefaultResponsibleId()) ?>
        </td>
    </tr>
    <tr>
        <td>
            <label for="active"><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_RESP_EXEC_LOAD') ?></label>
        </td>
        <td>
            <?= InputType('checkbox', 'mapping[responsibleSettings][executorLoading]', true, htmlspecialcharsbx($mapping->getResponsibleSettings()->isExecutorLoading())) ?>
        </td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_RESP_DEF_AUTHOR') ?></td>
        <td>
            <?= SelectBoxFromArray('mapping[responsibleSettings][defaultAuthorId]', $localUsers, $mapping->getResponsibleSettings()->getDefaultAuthorId()) ?>
        </td>
    </tr>
    <tr>
        <td>
            <label for="active"><?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_RESP_AUTHOR_LOAD') ?></label>
        </td>
        <td>
            <?= InputType('checkbox', 'mapping[responsibleSettings][authorLoading]', true, htmlspecialcharsbx($mapping->getResponsibleSettings()->isAuthorLoading())) ?>
        </td>
    </tr>
    <tr>
        <td>
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_RESP_DEADLINE') ?>
        </td>
        <td>
            <?= InputType('number', 'mapping[responsibleSettings][defaultDeadlineDays]', $mapping->getResponsibleSettings()->getDefaultDeadlineDays(), false, false, false, 'step="1"') ?>
        </td>
    </tr>
    <?php endif; ?>
  </form>
</div>
<?php
$tabControl->Buttons([
    'back_url' => 'integrations_system_exchange_type_list.php?lang=' . LANGUAGE_ID
]);
$tabControl->End();
?>
<?php if ($ID > 0): ?>
<script type="text/javascript">

    function updateStatusListByIds(typesSelId, statusesSelId, selectVal) {
      var typesSelect = BX(typesSelId);
      var statusesSelect = BX(statusesSelId);
      BX.ajax.runAction('integrations.api.entity.statuses', {data: {entityType: typesSelect.value}})
        .then(function(response) {
          if (response.status === 'success') {
            var list = response.data.list;
            BX.selectUtils.deleteAllOptions(statusesSelect);
            BX.selectUtils.addNewOption(statusesSelect, null, '<?=$noneSelected?>');
            list.forEach(function (item) {
              BX.selectUtils.addNewOption(statusesSelect, item.UF_CODE, item.UF_RUS_NAME);
            });
            if (selectVal) {
              BX.selectUtils.selectOption(statusesSelect, statusesSelect.getAttribute('data-value'));
            }
          }
        }, function(result) {
          console.log(result);
        });
    }

    function updateStatusList(idx, selectVal) {
      var typesSelId = `mapping[entityStatusMap][items][${idx}][internalTypeId]`;
      var statusesSelId = 'mapping_entityStatusMap_items_' + idx + '_internalStatusId';
      updateStatusListByIds(typesSelId, statusesSelId, selectVal);
    }

    function entityStatusMapEntityTypeChange(idx) {
      updateStatusList(idx);
      refillSelects('entityStatusMap', 'internalTypeId', idx);
    }

    function updateAllStatusLists() {
      var idx = 0;
      for (idx = 0;;idx++) {
        var typesSelect = BX(`mapping[entityStatusMap][items][${idx}][internalTypeId]`);
        if (!typesSelect) break;
        updateStatusList(idx, true);
      }
      updateStatusListByIds('mapping_entityStatusMap_defaultTypeId', 'mapping_entityStatusMap_defaultStatusId', true);
    }

    function entityStatusMapDefEntityTypeChange() {
      updateStatusListByIds('mapping_entityStatusMap_defaultTypeId', 'mapping_entityStatusMap_defaultStatusId');
    }

    function optionUsed(value, mapping, property, excludeIndex, scopeProperty) {
      var currVal = null;
      if (scopeProperty) {
        var elScopeCurrent = BX('mapping[' + mapping + '][items][' + excludeIndex + '][' + scopeProperty + ']');
        if (elScopeCurrent) currVal = elScopeCurrent.value;
      }
      for (var i = 0;; i++) {
        var el = BX('mapping[' + mapping + '][items][' + i + '][' + property + ']');
        if (!el) break;
        if (scopeProperty) {
          var elScope = BX('mapping[' + mapping + '][items][' + i + '][' + scopeProperty + ']');
          if (elScope.value !== currVal) continue;
        }
        if (i !== excludeIndex && el.value === value) return true;
      }
      return false;
    }

    function filterOptions(options, mapping, property, index, scopeProperty) {
      var result = [];
      result.push({id: '', name: '<?=$noneSelected?>'});
      options.REFERENCE_ID.forEach(function(item, idx) {
        if (!optionUsed(item, mapping, property, index, scopeProperty)) {
          result.push({id: item, name: options.REFERENCE[idx]});
        }
      });
      return result;
    }

    function getOptions(mapping, property, index) {
      var options;
      var scopeProperty = null;
      switch (mapping) {
        case 'projectMap':
          options = <?= json_encode($localProjects, JSON_UNESCAPED_UNICODE)?>;
          break;
        case 'entityTypeMap':
          switch (property) {
            case 'externalProjectId':
              options = <?= json_encode($externalProjects, JSON_UNESCAPED_UNICODE)?>;
              break;
            case 'externalTypeId':
              options = <?= json_encode($externalEntityTypes, JSON_UNESCAPED_UNICODE)?>;
              scopeProperty = 'externalProjectId';
              break;
            default:
              options = <?= json_encode($entityTypes, JSON_UNESCAPED_UNICODE)?>;
              scopeProperty = 'externalProjectId';
              break;
          }
          break;
        case 'entityStatusMap':
          switch (property) {
            case 'externalProjectId':
              options = <?= json_encode($externalProjects, JSON_UNESCAPED_UNICODE)?>;
              break;
            case 'externalTypeId':
              options = <?= json_encode($externalEntityTypes, JSON_UNESCAPED_UNICODE)?>;
              scopeProperty = 'externalProjectId';
              break;
            case 'internalTypeId':
              options = <?= json_encode($entityTypes, JSON_UNESCAPED_UNICODE)?>;
              scopeProperty = 'externalProjectId';
          }
          break;
        case 'entityPropertyMap':
          options = <?= json_encode($entityProps, JSON_UNESCAPED_UNICODE)?>;
          scopeProperty = 'externalTypeId';
          break;

      }
      return filterOptions(options, mapping, property, index, scopeProperty);
    }

    function getOptionsHtml(mapping, property, index) {
      var options = getOptions(mapping, property, index);
      var html = [];
      options.forEach(function(item) {
        html.push(`<option value="${item.id}">${item.name}</option>`);
      });
      return html.join('');
    }

    function refillSelects(mapping, property, index) {
      for (var i = 0;; i++) {
        var el = BX(`mapping[${mapping}][items][${i}][${property}]`);
        if (!el) break;
        if (i === index) continue;
        var options = getOptions(mapping, property, i);
        var saveVal = el.value;
        BX.selectUtils.deleteAllOptions(el);
        options.forEach(function(item) {
          BX.selectUtils.addNewOption(el, item.id, item.name);
        });
        if (saveVal) BX.selectUtils.selectOption(el, saveVal);
      }
    }

    BX.ready(function() {
      BX.Vue.create({
        el: '#settingsform',
        data: {
          projectMap: {
            lastIndex: <?= count($mapping->getProjectMap()->getItems())?>,
            items: []
          },
          entityTypeMap: {
            lastIndex: <?= count($mapping->getEntityTypeMap()->getItems())?>,
            items: []
          },
          entityStatusMap: {
            lastIndex: <?= count($mapping->getEntityStatusMap()->getItems())?>,
            items: []
          },
          entityPropertyMap: {
            lastIndex: <?= count($mapping->getEntityPropertyMap()->getItems())?>,
            items: []
          },
          userMap: {
            lastIndex: <?= count($mapping->getUserMap()->getItems())?>,
            items: []
          }
        },
        methods: {
          projectMapAddItem() {
            this.projectMap.items.push({
              idx: this.projectMap.lastIndex
            });
            this.projectMap.lastIndex++;
          },
          projectMapGetExtEntitySelect(item) {
            return `<select name="mapping[projectMap][items][${item.idx}][externalEntityId]" class="typeselect"><?= implode('', $externalProjectOptions)?></select>`;
          },
          projectMapGetIntEntitySelect(item) {
            var options = getOptionsHtml('projectMap', 'internalEntityId', item.idx);
            return `<select name="mapping[projectMap][items][${item.idx}][internalEntityId]" id="mapping[projectMap][items][${item.idx}][internalEntityId]" class="typeselect" onchange="refillSelects('projectMap', 'internalEntityId', ${item.idx}})">${options}</select>`;
          },

          entityTypeMapAddItem() {
            this.entityTypeMap.items.push({
              idx: this.entityTypeMap.lastIndex
            });
            this.entityTypeMap.lastIndex++;
          },
          entityTypeMapGetExtProjectSelect(item) {
            var options = getOptionsHtml('entityTypeMap', 'externalProjectId', item.idx);
            return `<select name="mapping[entityTypeMap][items][${item.idx}][externalProjectId]" id="mapping[entityTypeMap][items][${item.idx}][externalProjectId]" class="typeselect">${options}</select>`;
          },
          entityTypeMapGetExtEntityTypeSelect(item) {
            var options = getOptionsHtml('entityTypeMap', 'externalTypeId', item.idx);
            return `<select name="mapping[entityTypeMap][items][${item.idx}][externalTypeId]" id="mapping[entityTypeMap][items][${item.idx}][externalTypeId]" class="typeselect">${options}</select>`;
          },
          entityTypeMapGetIntEntityTypeSelect(item) {
            var options = getOptionsHtml('entityTypeMap', 'internalTypeId', item.idx);
            return `<select name="mapping[entityTypeMap][items][${item.idx}][internalTypeId]" id="mapping[entityTypeMap][items][${item.idx}][internalTypeId]" class="typeselect">${options}</select>`;
          },

          entityStatusMapAddItem() {
            var item = {idx: this.entityStatusMap.lastIndex};
            this.entityStatusMap.items.push(item);
            this.entityStatusMap.lastIndex++;
            setTimeout(function() {
              updateStatusList(item.idx);
            }, 100);
          },
          entityStatusMapGetExtProjectSelect(item) {
            var options = getOptionsHtml('entityStatusMap', 'externalProjectId', item.idx);
            return `<select name="mapping[entityStatusMap][items][${item.idx}][externalProjectId]" id="mapping[entityStatusMap][items][${item.idx}][externalProjectId]" class="typeselect">${options}</select>`;
          },
          entityStatusMapGetExtEntityTypeSelect(item) {
            var options = getOptionsHtml('entityStatusMap', 'externalTypeId', item.idx);
            return `<select name="mapping[entityStatusMap][items][${item.idx}][externalTypeId]" id="mapping[entityStatusMap][items][${item.idx}][externalTypeId]" class="typeselect">${options}</select>`;
          },
          entityStatusMapGetExtEntityStatusSelect(item) {
            return `<select name="mapping[entityStatusMap][items][${item.idx}][externalStatusId]" class="typeselect"><?= implode('', $externalEntityStatusOptions)?></select>`;
          },
          entityStatusMapGetIntEntityTypeSelect(item) {
            var options = getOptionsHtml('entityStatusMap', 'internalTypeId', item.idx);
            return `<select id="mapping[entityStatusMap][items][${item.idx}][internalTypeId]" name="mapping[entityStatusMap][items][${item.idx}][internalTypeId]" class="typeselect" onchange="entityStatusMapEntityTypeChange(${item.idx})">${options}</select>`;
          },
          entityStatusMapGetIntEntityStatusSelect(item) {
            return `<select id="mapping_entityStatusMap_items_${item.idx}_internalStatusId" name="mapping[entityStatusMap][items][${item.idx}][internalStatusId]" class="typeselect"></select>`;
          },

          entityPropertyMapAddItem() {
            this.entityPropertyMap.items.push({
              idx: this.entityPropertyMap.lastIndex
            });
            this.entityPropertyMap.lastIndex++;
          },
          entityPropertyMapGetExtEntityTypeSelect(item) {
            return `<select name="mapping[entityPropertyMap][items][${item.idx}][externalTypeId]" class="typeselect"><?= implode('', $externalEntityTypeOptions)?></select>`;
          },
          entityPropertyMapGetExtEntityPropSelect(item) {
            return `<select name="mapping[entityPropertyMap][items][${item.idx}][externalPropertyId]" class="typeselect"><?= implode('', $externalEntityPropertyOptions)?></select>`;
          },
          entityPropertyMapGetIntEntityTypeSelect(item) {
            return `<select name="mapping[entityPropertyMap][items][${item.idx}][internalTypeId]" class="typeselect"><?= implode('', $entityTypeOptions)?></select>`;
          },
          entityPropertyMapGetIntEntityPropSelect(item) {
            var options = getOptionsHtml('entityPropertyMap', 'internalPropertyId', item.idx);
            return `<select name="mapping[entityPropertyMap][items][${item.idx}][internalPropertyId]" id="mapping[entityPropertyMap][items][${item.idx}][internalPropertyId]" class="typeselect">${options}</select>`;
          },

          userMapAddItem() {
            this.userMap.items.push({
              idx: this.userMap.lastIndex
            });
            this.userMap.lastIndex++;
          },
          userMapGetIntUserSelect(item) {
            return `<select name="mapping[userMap][items][${item.idx}][internalId]" class="typeselect"><?= implode('', $localUserOptions)?></select>`;
          },
          userMapGetExtUserSelect(item) {
            return `<select name="mapping[userMap][items][${item.idx}][externalId]" class="typeselect"><?= implode('', $externalUserOptions)?></select>`;
          },
        }
      });

      updateAllStatusLists();
    });
</script>
<?php endif;?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
