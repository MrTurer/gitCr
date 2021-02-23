<?php
namespace Rns\Analytics\Controller;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Web\HttpClient;

class Heatmap extends Controller
{
    const MODULE_ID = "rns.analytics";

    public function configureActions()
    {
        return [
            'example' => [
                'prefilters' => []
            ]
        ];
    }

    public static function getClicksAction($url, $users, $fromDate, $toDate)
    {
        $apiUrl = Option::get(self::MODULE_ID, 'RNSANALYTICS_OPT_API_URL');
        $apiUrl .= '/getClicks';
        $data = [
            'url' => $url,
            'users' => $users,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ];
        $httpClient = new HttpClient();
        $httpClient->setHeader('Content-Type', 'application/json', true);
        return $httpClient->post($apiUrl, json_encode($data));
    }
}