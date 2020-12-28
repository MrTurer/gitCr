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

if ($ID > 0) {
    \Bitrix\Main\UI\Extension::load("ui.vue");

    $systemCode = $obj->getSystemCode();

    $localProjects = EntityFacade::getProjects();
    $localUsers = EntityFacade::getUsers();

    $entityTypes = EntityFacade::getEntityTypes();
    $entityStatuses = EntityFacade::getEntityStatuses();
    $entityProps = EntityFacade::getEntityProperties();
    $externalEntityTypes = EntityFacade::getExternalEntityTypes($systemCode);
    $externalEntityStatuses = EntityFacade::getExternalEntityStatues($systemCode);
    $externalEntityProps = EntityFacade::getExternalEntityProperties($systemCode);

    $entityTypeOptions = [];
    foreach ($entityTypes['REFERENCE_ID'] as $i => $id) {
        $entityTypeOptions[] = '<option value="'. $id . '">' . $entityTypes['REFERENCE'][$i] . '</option>';
    }
    $externalEntityTypeOptions = [];
    foreach ($externalEntityTypes['REFERENCE_ID'] as $i => $id) {
        $externalEntityTypeOptions[] = '<option value="'. $id . '">' . $externalEntityTypes['REFERENCE'][$i] . '</option>';
    }

    $entityStatusOptions = [];
    foreach ($entityStatuses['REFERENCE_ID'] as $i => $id) {
        $entityStatusOptions[] = '<option value="'. $id . '">' . $entityStatuses['REFERENCE'][$i] . '</option>';
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

    $externalProjects = EntityFacade::getExternalProjects($exchType, $obj->getOptions(), $obj->getMapping());
    foreach ($externalProjects as $id => $name) {
        if (!$mapping->getProjectMap()->getExternalItem($id)) {
            $mapping->getProjectMap()->addItem($id);
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
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_PROJ_SOURCE_ELEM') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[projectMap][srcElementName]', $mapping->getProjectMap()->getSrcElementName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[projectMap][keyAttrName]', $mapping->getProjectMap()->getKeyAttrName(), false) ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DISPLAY_ATTR') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[projectMap][displayAttrName]', $mapping->getProjectMap()->getDisplayAttrName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEF_PRJ') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray('mapping[projectMap][defaultEntityId]', $localProjects, $mapping->getProjectMap()->getDefaultEntityId()) ?>
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
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DEST_PRJ') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getProjectMap()->getItems() as $i => $item):?>
                <tr>
                    <td class="adm-list-table-cell">
                        <?= $externalProjects[$item->getExternalEntityId()] ?>
                        <?= InputType('hidden', "mapping[projectMap][items][{$i}][externalEntityId]", $item->getExternalEntityId(), false)?>
                    </td>
                    <td class="adm-list-table-cell">
                        <?= SelectBoxFromArray("mapping[projectMap][items][{$i}][internalEntityId]", $localProjects, $item->getInternalEntityId()) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>

    <?php $tabControl->BeginNextTab() ?>
    <!-- Типы сущности -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_TYPE') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityTypeMap][defaultTypeId]", $entityTypes, $mapping->getEntityPropertyMap()->getDefaultTypeId()) ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-r" colspan="4">
            <input type="button" @click="entityTypeMapAddItem" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
        </td>
    </tr>
    <tr>
        <td colspan="2">
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
                            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_INT_ENT_TYPE') ?>
                        </div>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getEntityTypeMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityTypeMap][items][{$i}][externalTypeId]", $externalEntityTypes, $mapItem->getExternalTypeId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityTypeMap][items][{$i}][internalTypeId]", $entityTypes, $mapItem->getInternalTypeId()) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in entityTypeMap.items">
                    <td class="adm-list-table-cell" v-html="entityTypeMapGetExtEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityTypeMapGetIntEntityTypeSelect(item)"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Статусы сущности -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_TYPE') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityStatusMap][defaultTypeId]", $entityTypes, $mapping->getEntityStatusMap()->getDefaultTypeId()) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ENT_DEF_STATUS') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= SelectBoxFromArray("mapping[entityStatusMap][defaultStatusId]", $entityStatuses, $mapping->getEntityStatusMap()->getDefaultStatusId()) ?>
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
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getEntityStatusMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][externalTypeId]", $externalEntityTypes, $mapItem->getExternalTypeId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][externalStatusId]", $externalEntityStatuses, $mapItem->getExternalStatusId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][internalTypeId]", $entityTypes, $mapItem->getInternalTypeId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityStatusMap][items][{$i}][internalStatusId]", $entityStatuses, $mapItem->getInternalStatusId()) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in entityStatusMap.items">
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetExtEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetExtEntityStatusSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetIntEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityStatusMapGetIntEntityStatusSelect(item)"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Свойства сущности -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_TASK_SOURCE_ELEM') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[entityPropertyMap][sourceElementName]', $mapping->getEntityPropertyMap()->getSourceElementName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[entityPropertyMap][keyPropertyName]', $mapping->getEntityPropertyMap()->getKeyPropertyName(), false) ?>
        </td>
    </tr>
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
                </tr>
                </thead>
                <tbody>
                <?php foreach ($mapping->getEntityPropertyMap()->getItems() as $i => $mapItem): ?>
                    <tr>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][externalTypeId]", $externalEntityTypes, $mapItem->getExternalTypeId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][externalPropertyId]", $externalEntityProps, $mapItem->getExternalPropertyId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][internalTypeId]", $entityTypes, $mapItem->getInternalTypeId()) ?>
                        </td>
                        <td class="adm-list-table-cell">
                            <?= SelectBoxFromArray("mapping[entityPropertyMap][items][{$i}][internalPropertyId]", $entityProps, $mapItem->getInternalPropertyId()) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr v-for="item in entityPropertyMap.items">
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetExtEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetExtEntityPropSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetIntEntityTypeSelect(item)"></td>
                    <td class="adm-list-table-cell" v-html="entityPropertyMapGetIntEntityPropSelect(item)"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Пользователи -->
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_USER_SOURCE_ELEM') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[userMap][srcElementName]', $mapping->getUserMap()->getSrcElementName(), false) ?>
        </td>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_KEY_ATTR') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[userMap][keyAttrName]', $mapping->getUserMap()->getKeyAttrName(), false) ?>
        </td>
    </tr>
    <tr>
        <td class="adm-detail-content-cell-l">
            <?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_DISPLAY_ATTR') ?>
        </td>
        <td class="adm-detail-content-cell-r">
            <?= InputType('text', 'mapping[userMap][displayAttrName]', $mapping->getUserMap()->getDisplayAttrName(), false) ?>
        </td>
    </tr>
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
            <input type="button" value="<?= Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_EDIT_MAP_ADD_MAP') ?>">
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
                </tr>
                </thead>
                <tbody>
                <tr>

                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <?php $tabControl->BeginNextTab() ?>
    <!-- Ответственные -->
    <tr>
        <td></td>
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
    BX.ready(function() {
      BX.Vue.create({
        el: '#settingsform',
        data: {
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
          }
        },
        methods: {
          entityTypeMapAddItem() {
            this.entityTypeMap.items.push({
              idx: this.entityTypeMap.lastIndex
            });
            this.entityTypeMap.lastIndex++;
          },
          entityTypeMapGetExtEntityTypeSelect(item) {
            return `<select name="mapping[entityTypeMap][items][${item.idx}][externalTypeId]" class="typeselect"><?= implode('', $externalEntityTypeOptions)?></select>`;
          },
          entityTypeMapGetIntEntityTypeSelect(item) {
            return `<select name="mapping[entityTypeMap][items][${item.idx}][internalTypeId]" class="typeselect"><?= implode('', $entityTypeOptions)?></select>`;
          },

          entityStatusMapAddItem() {
            this.entityStatusMap.items.push({
              idx: this.entityStatusMap.lastIndex
            });
            this.entityStatusMap.lastIndex++;
          },
          entityStatusMapGetExtEntityTypeSelect(item) {
            return `<select name="mapping[entityStatusMap][items][${item.idx}][externalTypeId]" class="typeselect"><?= implode('', $externalEntityTypeOptions)?></select>`;
          },
          entityStatusMapGetExtEntityStatusSelect(item) {
            return `<select name="mapping[entityStatusMap][items][${item.idx}][externalStatusId]" class="typeselect"><?= implode('', $externalEntityStatusOptions)?></select>`;
          },
          entityStatusMapGetIntEntityTypeSelect(item) {
            return `<select name="mapping[entityStatusMap][items][${item.idx}][internalTypeId]" class="typeselect"><?= implode('', $entityTypeOptions)?></select>`;
          },
          entityStatusMapGetIntEntityStatusSelect(item) {
            return `<select name="mapping[entityStatusMap][items][${item.idx}][internalStatusId]" class="typeselect"><?= implode('', $entityStatusOptions)?></select>`;
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
            return `<select name="mapping[entityPropertyMap][items][${item.idx}][internalPropertyId]" class="typeselect"><?= implode('', $entityPropertyOptions)?></select>`;
          }
        }
      });
    });
</script>
<?php endif;?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
