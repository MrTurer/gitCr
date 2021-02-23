<?php

namespace Rns\Analytics;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

class Heatmap
{
    const MODULE_ID = "rns.analytics";

    public static function onEpilogAction()
    {
        global $USER;

        if (defined('ADMIN_SECTION') && ADMIN_SECTION) {
            return;
        }


        $arJsConfig = [
            'analytics_heatmap_ext' => [
                'js' => '/bitrix/js/rns/analytics/heatmapext.js'
            ],
            'analytics_heatmap' => [
                'js' => '/bitrix/js/rns/analytics/heatmap.min.js'
            ]
        ];
        foreach ($arJsConfig as $ext => $arExt) {
            \CJSCore::RegisterExt($ext, $arExt);
        }
        \CUtil::InitJSCore([
            'analytics_heatmap_ext',
            'analytics_heatmap'
        ]);

        $apiUrl = Option::get(self::MODULE_ID, 'RNSANALYTICS_OPT_API_URL');
        $userId = $USER->GetID();
        if ($apiUrl && $userId) {
            Asset::getInstance()->addString("<script>BX.ready(function () {HeatMapExt.apiUrl = '{$apiUrl}'; HeatMapExt.userId = '{$userId}'; HeatMapExt.init();})</script>");
        }
    }
}