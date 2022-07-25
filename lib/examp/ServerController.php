<?php
namespace Manao\Servermanager;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

use \Exception;

use Manao\Servermanager\ServerReserveTable;
use Manao\Servermanager\TagsServerTable;

class ServerController 
{	

	public function __construct()
	{

	}

	public function unreserveServer($serverID){
		$arFields = [
			'DATE_UPDATE' => \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime()),
			'USER_ID' => null,
		];
		$result = ServerReserveTable::update($serverID, $arFields);
	}
	
	public function reserveServer($serverID, $userID){
		$item = $this->getServInfoByID($serverID);
		if ($item) 
		{
			if ($item['USER_ID']) {
				// Сервер уже занят
				$serverIsReserved = $serverID;
				return false;
			}
		}
		if (!$serverIsReserved) 
		{
			$arFields = [
				'DATE_UPDATE' => \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime()),
				'USER_ID' => $userID,
			];
			$result = ServerReserveTable::update($serverID, $arFields);
		}
		return true;
	}

	public function getServInfoByID($serverID)
	{
		$resItem = ServerReserveTable::getList([
			'filter' => ['ID' => $serverID],
		]);
		$result = $resItem->Fetch();
		return $result;
	
	}
	public function getGitBranch($serverID)
	{
		$serverItem = $this->getServInfoByID($serverID); 
		$dir = '';
		$branchName = 'git отсутствует';
		if ($serverItem) 
		{
			preg_match('/^t[0-9]*\./', $serverItem['NAME'], $matches);
			if (!empty($matches)) 
			{
				// Значит t2, t3 и т.д
				$dir = $serverItem['PARENT_NAME'].'/'.$serverItem['NAME'].'/htdocs';
			} 
			else 
			{
				// Основной тестовый
				$dir = $serverItem['PARENT_NAME'].'/htdocs';
			}
		}
		if ($dir) {
			$fullDir = '/home/hdd/'.$dir.'/.git/';
			if (is_dir($fullDir)) {
				$branchName = exec('git --git-dir='.$fullDir.' rev-parse --abbrev-ref HEAD');
			}
		}
		return $branchName;
	}

	public function getServersList($nav="", $filter="")
	{
		$res = ServerReserveTable::getList([
			'filter' => $filter,
			'select' => ["*"],
			"count_total" => true,
			'offset'      => $nav->getOffset(),
			'limit'       => $nav->getLimit(),
			'order'       => ['NAME' => 'ASC'],
		]);
		if($nav != ''){
			$getCount = $res->getCount();
			$nav->setRecordCount($getCount);
		}
		return $res->fetchAll();
	}

	public function getServersListByTag($nav="", $filter="", $tagID)
	{
		$connectionTableRes = TagsServerTable::getList([
			'select' => ['*'],
			'filter' => ['TAG_ID' => $tagID],
		]);
		$connectionTableArray = $connectionTableRes->fetchAll();
		$idFilter =[];
		foreach($connectionTableArray as $connect){
			$idFilter[] = $connect['SERVER_ID'];
		}
		$filter = ['ID' => $idFilter];
		$result = $this->getServersList($nav, $filter);
		return $result;
	
	}

	public function addServer($serverName, $parentName, $userID, $getID = false)
	{
		$addObject = ServerReserveTable::add(array(
			'PARENT_NAME' =>  $parentName,
			'NAME' =>  $serverName,
			'EXTERNAL_SOURCE' => 'Y',
			'CREATOR_ID' => $userID,
			'DATE_CREATE' => \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime()),
			)   
		);
		if($addObject->isSuccess())
		{
			if($getID == true){
				$id = $addObject->getId();
				return $id;
			}
			return true;
		}
		return false;
	}

	public function removeServer($serverID)
	{
		$result = ServerReserveTable::delete($serverID);
	}

}
