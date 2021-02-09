<?
require_once(__DIR__ . '/events.php');

use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load("ui.hint");

$arJsConfig = array(
    'newHintPopup' => array(
        'js'=> '/local/js/newHintPopup.js?v=20210209',
    ),
    'hintElementInfo' => array(
        'js'=> '/local/js/hintElementInfo.js?v=20210209',
    ),
    'hintsListPopup' => array(
        'js'=> '/local/js/hintsListPopup.js?v=20210209',
    ),
    'clearFields' => array(
        'js'=> '/local/js/clearFields.js?v=20210209',
    ),
    'hintItems' => array(
        'js'=> '/local/js/hintItems.js?v=20210209',
    )
);

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}

CUtil::InitJSCore(array('newHintPopup'));
CUtil::InitJSCore(array('hintElementInfo'));
CUtil::InitJSCore(array('hintsListPopup'));
CUtil::InitJSCore(array('clearFields'));
CUtil::InitJSCore(array('hintItems'));

CJSCore::Init(array('ajax', 'popup', 'jquery'));

AddEventHandler('main', 'onProlog', function(){
     Asset::getInstance()->addJs("/local/js/newHint.js?v=20210209");
    Asset::getInstance()->addCss("/local/styles/style.css?v=20210209");
}, 99999999);
