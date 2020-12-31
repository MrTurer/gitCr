<?
use Bitrix\Main\Localization\Loc;

try {
    Loc::loadMessages(__FILE__);

    return [
		[
			'parent_menu' => 'global_menu_services',
			'text' => Loc::getMessage('PMO_MENU_TITLE'),
			'section' => 'industrial_office',
			'module_id' => 'industrial_office',
			'items_id' => 'menu_pmo',
			'icon' => 'clouds_menu_icon',
			'page_icon' => 'clouds_menu_icon',
			'sort' => 1,
			'items' => [
				[
					'text'     => Loc::getMessage('PMO_ENTITIES_TITLE'),
					'url'      => 'industrial_office_entities_list.php?lang=' . LANGUAGE_ID,
					'more_url' => [
						'industrial_office_entities_edit.php?lang=' . LANGUAGE_ID
					],
					'title'    => Loc::getMessage('PMO_ENTITIES_TITLE'),
				],
				[
					'text'     => Loc::getMessage('PMO_SETTINGS_HIERARCHY_RULES_TITLE'),
					'url'      => 'industrial_office_settings_hierarchy_rules_list.php?lang=' . LANGUAGE_ID,
					'title'    => Loc::getMessage('PMO_SETTINGS_HIERARCHY_RULES_TITLE'),
				],
				[
					'text'     => Loc::getMessage('PMO_SETTINGS_ENTITY_STATUSES_TITLE'),
					'url'      => 'industrial_office_settings_entities_list.php?lang=' . LANGUAGE_ID,
					'more_url' => [
						'industrial_office_settings_entity_statuses_list.php?lang=' . LANGUAGE_ID,
						'industrial_office_settings_entity_status_edit.php?lang=' . LANGUAGE_ID
					],
					'title'    => Loc::getMessage('PMO_SETTINGS_ENTITY_STATUSES_TITLE'),
				],
			]
		]
    ];


} catch (\Bitrix\Main\LoaderException $e) {
    return [];
}
?>