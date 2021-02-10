 <?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load("popup");
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.buttons.icons");

Loc::loadMessages(__FILE__);

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var CBitrixComponent $component */

$helper = $arResult['HELPER'];

$taskId = $arParams["TASK_ID"];
$can = $arParams["TASK"]["ACTION"];
$taskData = $arParams["TASK"];
$statusesList = $arParams["STATUSES"];

if (\Bitrix\Main\ModuleManager::isModuleInstalled('rest')) {
	\Bitrix\Main\Loader::includeModule('rest');
	
	$APPLICATION->IncludeComponent(
		'bitrix:app.placement',
		'menu',
		array(
			'PLACEMENT'         => "TASK_LIST_CONTEXT_MENU",
			"PLACEMENT_OPTIONS" => array(),
			//			'INTERFACE_EVENT' => 'onCrmLeadListInterfaceInit',
			'MENU_EVENT_MODULE' => 'tasks',
			'MENU_EVENT'        => 'onTasksBuildContextMenu',
		),
		null,
		array('HIDE_ICONS' => 'Y')
	);
}
?>

<div id="<?=$helper->getScopeId()?>" class="task-view-buttonset <?=implode(' ', $arResult['CLASSES'])?>">
	<span data-bx-id="task-view-b-timer" class="task-timeman-link">
		<span class="task-timeman-icon"></span>
		
		<span id="task_details_buttons_timer_<?=$taskId?>_text" class="task-timeman-text">
			<span data-bx-id="task-view-b-time-elapsed"><?=\Bitrix\Tasks\UI::formatTimeAmount($taskData['TIME_ELAPSED']);?></span>
			<?if ($taskData["TIME_ESTIMATE"] > 0):?>
				/ <?=\Bitrix\Tasks\UI::formatTimeAmount($taskData["TIME_ESTIMATE"]);?>
			<?endif?>
		</span>
		
		<span class="task-timeman-arrow"></span>
	</span>

	<span data-bx-id="task-view-b-buttonset">
		<?if (!empty($taskData['STATUSES']['CUSTOM_STATUS']['UF_NEXT_STATUS'])) {?>
			<span id="task-view-statuses" data-bx-id="task-view-b-open-change-statuses" class="task-more-button ui-btn ui-btn-light-border ui-btn-dropdown">
				<?=Loc::getMessage("RNS_CHANGE_STATUS")?>
			</span><?
		}
		
		?><span data-bx-id="task-view-b-open-menu" class="task-more-button ui-btn ui-btn-light-border ui-btn-dropdown">
			<?=Loc::getMessage("TASKS_MORE")?>
		</span><?

		?><a href="<?=$arResult['EDIT_URL']?>" class="task-view-button edit ui-btn ui-btn-link" data-slider-ignore-autobinding="true">
			<?=GetMessage("TASKS_EDIT_TASK")?>
		</a>
	</span>
</div>

<script>
	BX.message({
		TASKS_REST_BUTTON_TITLE: '<?=Loc::getMessage('TASKS_REST_BUTTON_TITLE')?>',
		TASKS_DELETE_SUCCESS: '<?=GetMessage('TASKS_DELETE_SUCCESS')?>'
	});
	
	var obStatuses = new BX.Tasks.Component.Statuses(<?= json_encode($taskData['STATUSES'])?>);
</script>
<?$helper->initializeExtension();?>