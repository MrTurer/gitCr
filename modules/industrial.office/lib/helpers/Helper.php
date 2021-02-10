<?php
namespace RNS\Industrial\Office\Helpers;

use \Bitrix\Main\Diag\Debug;
use \Bitrix\Main\Localization\Loc;
use	\Bitrix\Main\Loader;
use \Bitrix\Highloadblock\HighloadBlockTable;

class Helper {
	public static function getStatuses($intEntityID = 0, $intCurrStatus = 0) {
		$arReturn = [];
		
		if (!Loader::includeModule('highloadblock') && $intEntityID <= 0 && $intCurrStatus <= 0) {
			return $arReturn;
		}

		$arEntityCode = '';
		$rsData = HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
		$hldata = $rsData->fetch();
		if ($hldata) {
			$hlentity = HighloadBlockTable::compileEntity($hldata);
			$strEntityDataClass = $hlentity->getDataClass();
			
			$res = $strEntityDataClass::getList([
				'filter' => [
					'ID' => $intEntityID,
				],
				'select' => ['UF_CODE'],
			]);
			if ($ar_res = $res->fetch()) {
				$arEntityCode = $ar_res['UF_CODE'];
			}
			unset($ar_res, $res);
		}

		if ($arEntityCode != '') {
			$rsData = HighloadBlockTable::getList(['filter' => ['NAME' => 'StatusEntity']]);
			$hldata = $rsData->fetch();
			if ($hldata) {
				$hlentity = HighloadBlockTable::compileEntity($hldata);
				$strEntityDataClass = $hlentity->getDataClass();
				
				$res = $strEntityDataClass::getList([
					'filter' => [
						'UF_ENTITY_TYPE_BIND' => $arEntityCode,
					],
					'select' => ["*"],
				]);
				while ($ar_res = $res->fetch()) {
					$ar_res['UF_NEXT_STATUS'] = unserialize($ar_res['UF_NEXT_STATUS']);
					$ar_res['UF_NEXT_STATUS_BUTTON_NAME'] = unserialize($ar_res['UF_NEXT_STATUS_BUTTON_NAME']);
					$ar_res['FINAL_STATUS'] = false;
					
					if ($ar_res['ID'] == $intCurrStatus) {
						$arReturn['CUSTOM_STATUS'] = $ar_res;
					}
					
					$arReturn['ALL_CUSTOM_STATUSES'][$ar_res['UF_CODE']]['ID'] = $ar_res['ID'];
					$arReturn['ALL_CUSTOM_STATUSES'][$ar_res['UF_CODE']]['NAME'] = $ar_res['UF_RUS_NAME'];
					$arReturn['ALL_CUSTOM_STATUSES'][$ar_res['UF_CODE']]['FINAL_STATUS'] = false;
					$arReturn['ALL_CUSTOM_STATUSES'][$ar_res['UF_CODE']]['UF_NEXT_STATUS'] = $ar_res['UF_NEXT_STATUS'];
					$arReturn['ALL_CUSTOM_STATUSES'][$ar_res['UF_CODE']]['UF_NEXT_STATUS_BUTTON_NAME'] = $ar_res['UF_NEXT_STATUS_BUTTON_NAME'];
					
					if ($ar_res['UF_NEXT_STATUS'] == '') {
						$arReturn['ALL_CUSTOM_STATUSES'][$ar_res['UF_CODE']]['FINAL_STATUS'] = true;
					}
				}
			}
		}
		
		return $arReturn;
	}
	
	public static function getBoundFields($intEntityID = 0) {
		$arReturn = [];
		if (!Loader::includeModule('highloadblock') && $intEntityID <= 0) {
			return $arReturn;
		}

		$rsData = HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
		$hldata = $rsData->fetch();
		if ($hldata) {
			$hlentity = HighloadBlockTable::compileEntity($hldata);
			$strEntityDataClass = $hlentity->getDataClass();
			
			$res = $strEntityDataClass::getList([
				'filter' => [
					'ID' => $intEntityID,
				],
				'select' => ['UF_BOUND_FIELDS'],
			]);
			if ($ar_res = $res->fetch()) {
				$arReturn = unserialize($ar_res['UF_BOUND_FIELDS']);
			}
		}

		return $arReturn;
	}
	
	public static function checkUsingTime($intEntityID = 0) {
		$arReturn = false;
		if (!Loader::includeModule('highloadblock') && $intEntityID <= 0) {
			return $arReturn;
		}
		
		$rsData = HighloadBlockTable::getList(['filter' => ['NAME' => 'Entities']]);
		$hldata = $rsData->fetch();
		if ($hldata) {
			$hlentity = HighloadBlockTable::compileEntity($hldata);
			$strEntityDataClass = $hlentity->getDataClass();
			
			$res = $strEntityDataClass::getList([
				'filter' => [
					'ID' => $intEntityID,
				],
				'select' => ['UF_CONSIDER_TIME'],
			]);
			if ($ar_res = $res->fetch()) {
				$arReturn = $ar_res['UF_CONSIDER_TIME'];
			}
		}

		return $arReturn;
	}
}