<?
require_once(__DIR__ . '/events.php');

use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load("ui.hint");

/* $arJsConfig = array( 
    'customJs' => array( 
        'js'=> '/bitrix/js/Interval/Interval.js', 
    ) 
); 

foreach ($arJsConfig as $ext => $arExt) { 
    \CJSCore::RegisterExt($ext, $arExt); 
}
 */
CUtil::InitJSCore(array('customJs'));

CJSCore::Init(array('ajax', 'popup', 'jquery'));

AddEventHandler('main', 'onProlog', function(){     
     Asset::getInstance()->addJs("/local/js/newHint.js"); 
    Asset::getInstance()->addCss("/local/styles/style.css");
}, 5000);
