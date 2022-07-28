<?php
namespace Homedev\Process;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
\Bitrix\Main\Loader::includeModule("tasks");
\Bitrix\Main\Loader::includeModule("forum");
\Bitrix\Main\Loader::includeModule("intranet");
\Bitrix\Main\Loader::includeModule("iblock");

use \Exception;
use Homedev\Process\GModuleController;
use Homedev\Process\IcheckController;

class TasksController
{	
	private $userID; //id текущего пользователя
	private $managerID; //id менеджера
	private $gmResponsible = 10; //id ответственного за ГМ
	private $ichResponsible = 11; // id ответственного за ИЧ

	public function __construct($userID)
	{
		$this->userID = $userID;
		$this->managerID = $this->getBitrixUserManager();
	}
	public function createOpenCloseTask($shopName, $taskType)
	{
		$taskTitle = ""; 
		$taskDesc = "";
		if($taskType == 'open'){
			$taskTitle = "Чек лист открытия магазина ".$shopName; 
			$taskDesc = "Чек-лист открытия магазина позволит вам не забть выполнить важные процедуры при открытии магазина.";
		} else if($taskType == 'close'){
			$taskTitle = "Чек лист закрытия магазина ".$shopName; 
			$taskDesc = "Чек-лист закрытия магазина позволит вам не забть выполнить важные процедуры при закрытии магазина.";
		}

		$arrAdd = [
			"DD"	=> 0,
			"MM"	=> 0,
			"YYYY"	=> 0,
			"HH"	=> 0,
			"MI"	=> 30,
			"SS"	=> 0,
		];
		$stmp = AddToTimeStamp($arrAdd);

		$arTaskField = [
			'TITLE' => $taskTitle,
			'DESCRIPTION' => $taskDesc, 
			'GROUP_ID' => 1,
			'DEADLINE' => date("d.m.Y H:i:s", $stmp),
			'RESPONSIBLE_ID' => $this->userID, 
			'CREATED_BY' => $this->managerID, 
		];
	
		$result = \CTaskItem::add($arTaskField, $this->managerID);
		$taskId = $result->getId();
		$taskCheck = \CTaskItem::getInstance($taskId, $this->userID);
		if($taskType == 'open'){
			\CTaskCheckListItem::add($taskCheck, ['TITLE'=>'Проснуться', 'SORT_INDEX'=>10, 'IS_COMPLETE'=>'N']);
			\CTaskCheckListItem::add($taskCheck, ['TITLE'=>'Улыбнуться', 'SORT_INDEX'=>20, 'IS_COMPLETE'=>'N']);
		} else if($taskType == 'close'){
			\CTaskCheckListItem::add($taskCheck, ['TITLE'=>'Закрыть магазин', 'SORT_INDEX'=>10, 'IS_COMPLETE'=>'N']);
			\CTaskCheckListItem::add($taskCheck, ['TITLE'=>'Убежать домой', 'SORT_INDEX'=>20, 'IS_COMPLETE'=>'N']);
		}
		
	}

	public function createGmTask($shopName, $device, $bkNumber, $serialNumber, $scratchCode, $malfunction, $description){
		$tableLine = new GModuleController($shopName, $device, $bkNumber, $serialNumber, $scratchCode, $malfunction);
		$gmId = $tableLine->createLine();
		$taskTitle = "ГМ №".$gmId; 
		$taskDesc = "ГМ №".$gmId.': БК №'.$bkNumber." - ".$device." - ".$malfunction.". ".$description."\n"."Серийный номер:".$serialNumber.";\n"."Скретч-код:".$scratchCode.";\n"."НЕ ЗАБУДЬ ДОБАВИТЬ ВИДЕО В КОММЕНТАРИИ!";
		$arrAdd = [
			"DD"	=> 5,
			"MM"	=> 0,
			"YYYY"	=> 0,
			"HH"	=> 0,
			"MI"	=> 0,
			"SS"	=> 0,
		];
		$stmp = AddToTimeStamp($arrAdd);
		$arTaskField = [
			'TITLE' => $taskTitle,
			'DESCRIPTION' => $taskDesc, 
			'GROUP_ID' => 2,
			'DEADLINE' => date("d.m.Y H:i:s", $stmp),
			'RESPONSIBLE_ID' => $this->userID, 
			'CREATED_BY' => $this->managerID, 
			'ACCOMPLICES' => [$this->gmResponsible],
		];
		$result = \CTaskItem::add($arTaskField, $this->managerID);
	}

	
	public function createIchTask($shopName, $checksNumber, $couse, $couseDescription){
		$tableLine = new IcheckController($shopName, $checksNumber, $couse, $couseDescription);
		$ichId = $tableLine->createLine();
		$taskTitle = "ИЧ №".$ichId; 
		$taskDesc = "ИЧ №".$ichId.': Исправить чек №'.$checksNumber." по причине '".$couse."'. <br> Описание ситуации: ".$couseDescription;
		$arrAdd = [
			"DD"	=> 3,
			"MM"	=> 0,
			"YYYY"	=> 0,
			"HH"	=> 0,
			"MI"	=> 0,
			"SS"	=> 0,
		];
		$stmp = AddToTimeStamp($arrAdd);
		$arTaskField = [
			'TITLE' => $taskTitle,
			'DESCRIPTION' => $taskDesc, 
			'GROUP_ID' => 3,
			'DEADLINE' => date("d.m.Y H:i:s", $stmp),
			'RESPONSIBLE_ID' => $this->userID, 
			'CREATED_BY' => $this->managerID, 
			'ACCOMPLICES' => [$this->ichResponsible],
		];
		$result = \CTaskItem::add($arTaskField, $this->managerID);
	}


	private function getBitrixUserManager() {
		
		$managers = array();
		$sections = \CIntranetUtils::GetUserDepartments($this->userID);
		foreach ($sections as $section) {
		$manager = \CIntranetUtils::GetDepartmentManagerID($section);
		while (empty($manager)) {
			$res = \CIBlockSection::GetByID($section);
			if ($sectionInfo = $res->GetNext()) {
				$manager = \CIntranetUtils::GetDepartmentManagerID($section);
				$section = $sectionInfo['IBLOCK_SECTION_ID'];
				if ($section < 1) break;
			} else break;
		}
		If ($manager > 0) $managers[] = $manager;
		}
		return $managers[0];
	}

}
