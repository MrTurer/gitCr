<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader;

global $APPLICATION, $USER;

$module_id = 'rns.notification';

if (!$USER->IsAdmin()) {
    return;
}

if (!Loader::includeModule($module_id)) {
    return;
}

/**
 *
 * Описание логики табов и настроек в табах
 */
$tabs = [
    [
        'DIV' => 'chatbot',
        'TAB' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_NAME'),
        'TITLE' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_TITLE')
    ],
    [
        'DIV' => 'days_deadline',
        'TAB' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_DAYS_DEADLINE_NAME'),
        'TITLE' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_TITLE')
    ],
    [
        'DIV' => 'today_deadline',
        'TAB' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_TODAY_DEADLINE_NAME'),
        'TITLE' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_TITLE')
    ],
    [
        'DIV' => 'change_deadline',
        'TAB' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHANGE_DEADLINE_NAME'),
        'TITLE' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_TITLE')
    ]
];

$isChatbotActiveSet = Option::get($module_id, 'RNSNOTIFICATION_OPT_CHATBOT_ACTIVE');
$isChatbotActive = \Rns\Notification\RnsBot::getBotId() ? 'Y' : 'N';
if ($isChatbotActiveSet != $isChatbotActive) {
    Option::set($module_id, 'RNSNOTIFICATION_OPT_CHATBOT_ACTIVE', $isChatbotActive);
}

$options['chatbot'] = [
    [
        'RNSNOTIFICATION_OPT_CHATBOT_ACTIVE',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_ACTIVE'),
        $isChatbotActive,
        ['checkbox']
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_NAME',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_NAME'),
        '',
        ['text', 70]
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_WELCOME_MESSAGE',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_WELCOME_MESSAGE'),
        '',
        ['text', 70]
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_ICON',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_ICON'),
        '',
        ['file']
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_COLOR',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_COLOR'),
        '',
        ['text', 10]
    ],
    Loc::getMessage('RNSNOTIFICATION_OPT_TAB_DEFAULT_NOTICE_TITLE'),
    [
        'RNSNOTIFICATION_OPT_CHATBOT_NOTICE',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_NOTICE'),
        '',
        ['checkbox']
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_DAYS',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_DAYS'),
        '',
        ['text', 10]
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_TODAY_DEADLINE',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_TODAY_DEADLINE'),
        '',
        ['checkbox']
    ],
    [
        'RNSNOTIFICATION_OPT_CHATBOT_CHANGE',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_CHANGE'),
        '',
        ['checkbox']
    ]
];
$options['days_deadline'] = [
    [
        'RNSNOTIFICATION_OPT_DAYS_DEADLINE_TEMPLATE_TITLE',
        Loc::getMessage('RNSNOTIFICATION_OPT_TEMPLATE_TITLE'),
        '',
        ['textarea', 5, 50]
    ],
    [
        'RNSNOTIFICATION_OPT_DAYS_DEADLINE_TEMPLATE_CONTENT',
        Loc::getMessage('RNSNOTIFICATION_OPT_ENTITY_TEMPLATE'),
        '',
        ['textarea', 5, 50]
    ]
];
$options['today_deadline'] = [
    [
        'RNSNOTIFICATION_OPT_TODAY_DEADLINE_TEMPLATE_TITLE',
        Loc::getMessage('RNSNOTIFICATION_OPT_TEMPLATE_TITLE'),
        '',
        ['textarea', 5, 50]
    ],
    [
        'RNSNOTIFICATION_OPT_TODAY_DEADLINE_TEMPLATE_CONTENT',
        Loc::getMessage('RNSNOTIFICATION_OPT_ENTITY_TEMPLATE'),
        '',
        ['textarea', 5, 50]
    ]
];
$options['change_deadline'] = [
    [
        'RNSNOTIFICATION_OPT_CHANGE_DEADLINE_TEMPLATE_TITLE',
        Loc::getMessage('RNSNOTIFICATION_OPT_TEMPLATE_TITLE'),
        '',
        ['textarea', 5, 50]
    ],
    [
        'RNSNOTIFICATION_OPT_CHANGE_DEADLINE_TEMPLATE_CONTENT',
        Loc::getMessage('RNSNOTIFICATION_OPT_ENTITY_TEMPLATE'),
        '',
        ['textarea', 5, 50]
    ]
];

if (check_bitrix_sessid() && (strlen($_POST['save']) > 0 || strlen($_POST['apply']) > 0)) {
    if (!(int)$_REQUEST['RNSNOTIFICATION_OPT_CHATBOT_DAYS']) {
        $_REQUEST['RNSNOTIFICATION_OPT_CHATBOT_DAYS'] = 3;
    }
    if ($_REQUEST['RNSNOTIFICATION_OPT_CHATBOT_ACTIVE'] == 'Y') {
        \Rns\Notification\RnsBot::register();
    } else {
        \Rns\Notification\RnsBot::unregister();
    }
    if (!empty($_FILES) && isset($_FILES['RNSNOTIFICATION_OPT_CHATBOT_ICON']) && $_FILES['RNSNOTIFICATION_OPT_CHATBOT_ICON']['error'] == 0) {
        $file = $_FILES['RNSNOTIFICATION_OPT_CHATBOT_ICON'];
        $oldFile = Option::get($module_id, 'RNSNOTIFICATION_OPT_CHATBOT_ICON');
        $arFile = [
            "name" => $file['name'],
            "size" => $file['size'],
            "tmp_name" => $file['tmp_name'],
            "type" => $file['type'],
            "old_file" => $oldFile,
            "del" => "Y",
            "MODULE_ID" => $module_id
        ];
        CFile::ResizeImage(
            $arFile,
            [
                "width" => 200,
                "height" => 200
            ]
        );
        $fid = CFile::SaveFile($arFile, $module_id);
        Option::set($module_id, 'RNSNOTIFICATION_OPT_CHATBOT_ICON', $fid);
    }
    foreach ($options as $option) {
        __AdmSettingsSaveOptions($module_id, $option);
    }

    \Rns\Notification\RnsBot::update();

    if (strlen($_POST['save']) > 0) {
        LocalRedirect($APPLICATION->GetCurPageParam());
    }
}
?>

<form method="POST"
      action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
      ENCTYPE="multipart/form-data">
    <?
    $tabControl = new CAdminTabControl('tabControl', $tabs);
    $tabControl->Begin();

    if (!empty($options)) {
        foreach ($options as $optionArr) {
            $tabControl->BeginNextTab();
            foreach ($optionArr as $option) {
                if ($option[3][0] == 'file') {
                    $val = Option::get($module_id, $option[0]);
                    ?>
                    <tr>
                        <td><?= $option[1] ?></td>
                        <td>
                            <?= InputType('file', $option[0], $val, false); ?>
                            <?= CFile::ShowImage($val, 30, 30, "border=0", "", true) ?>
                        </td>
                    </tr>
                    <?
                } else {
                    __AdmSettingsDrawRow($module_id, $option);
                }
            }
        }
    }
    $tabControl->Buttons(['btnApply' => true, 'btnCancel' => false, 'btnSaveAndAdd' => false]);
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>