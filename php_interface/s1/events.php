<?
use Bitrix\Main\Loader;

/* Наряд ЗНИ:Старт */ 
/* Нельзя создавать группы и проекты с одинаковым названием */
AddEventHandler('socialnetwork', 'OnBeforeSocNetGroupAdd', Array("CstmSocNetGroupAdd", "addGroup"));
class CstmSocNetGroupAdd
{
	function addGroup(&$arFields)
	{
		if (CModule::IncludeModule('socialnetwork'))   {
			$res = CSocNetGroup::GetList([], ['NAME' => $arFields['NAME']]); 

			while ($group = $res->GetNext())       {
				if (!empty($group)) {
					$mess = '';
					if ($arFields['PROJECT'] == 'Y') {
						$mess = 'Проект с таким названием уже создан.';
					} else {
						$mess = 'Группа с таким названием уже создана.';
					}

					$GLOBALS['APPLICATION']->throwException($mess . ' Пожалуйста, выберите другое название.');
					return false;
				}
			}
		}
   	}
}
/* Наряд ЗНИ:Завершение */
/* Назначение последней даты просмотра у зкрытых задач */
function setLastViewDatetimeTask()
{
	require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

	if (CModule::IncludeModule("tasks"))
	{
		global $DB;
	
		$tasksSql 	= "SELECT * FROM b_tasks WHERE STATUS=5";
		$results 	= $DB->Query($tasksSql);

		while ($row = $results->Fetch())
		{
			$taskId = $row['ID'];
	
			$rsTask = CTasks::GetByID($taskId, false);

			if ($arTask = $rsTask->GetNext())
			{
				$users = [
					$arTask["RESPONSIBLE_ID"],
					$arTask["CREATED_BY"],
				];
	
				$allUser = array_merge($users, $arTask['AUDITORS'], $arTask["ACCOMPLICES"]);

				foreach($allUser as $key => $userID) 
				{
					$sqlDel = "DELETE FROM b_tasks_viewed WHERE TASK_ID=". $taskId ." AND USER_ID=". $userID .";";
					$DB->Query($sqlDel);

					$sql = "INSERT IGNORE INTO b_tasks_viewed VALUES(" . $taskId . ", " . $userID . ", NOW());";
	
					try {
						$rs = $DB->Query($sql);
	
						$sqlQuery = "INSERT IGNORE INTO b_sonet_user_content_view VALUES(" . $userID . ", 'TASK', " . $taskId . ", 'TASK-" . $taskId ."', NOW());";
						$rs = $DB->Query($sqlQuery);
		
					} catch (\Exception $e) {
						//	var_dump($e->getMessage());
						//echo '<br>';
					}
				}
			}
		}
	}

	return "setLastViewDatetimeTask();";
}