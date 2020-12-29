<?php

use Bitrix\Main\Localization\Loc;

try {
    Loc::loadMessages(__FILE__);

    return [
      [
        'parent_menu' => 'global_menu_services',
        'text' => Loc::getMessage('INTEGRATIONS_MENU_TITLE'),
        'section' => 'integrations',
        'module_id' => 'integrations',
        'items_id' => 'menu_integrations',
        'icon' => 'clouds_menu_icon',
        'page_icon' => 'clouds_menu_icon',
        'sort' => 1,
        'items' => [
          [
            'text'     => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_LIST_TITLE'),
            'url'      => 'integrations_system_exchange_type_list.php?lang=' . LANGUAGE_ID,
            'more_url' => [
              'integrations_system_exchange_type_edit.php?lang=' . LANGUAGE_ID
            ],
            'title'    => Loc::getMessage('INTEGRATIONS_SYS_EXCH_TYPE_LIST_TITLE'),
          ],
          [
            'text'     => Loc::getMessage('INTEGRATIONS_SYSTEM_LIST_TITLE'),
            'url'      => 'integrations_system_list.php?lang=' . LANGUAGE_ID,
            'more_url' => [
              'integrations_system_edit.php?lang=' . LANGUAGE_ID
            ],
            'title'    => Loc::getMessage('INTEGRATIONS_SYSTEM_LIST_TITLE'),
          ],
        ]
      ]
    ];


} catch (\Bitrix\Main\LoaderException $e) {
    return [];
}
