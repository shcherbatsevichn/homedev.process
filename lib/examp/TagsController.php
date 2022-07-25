<?php
namespace Manao\Servermanager;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

use \Exception;

use Manao\Servermanager\TagsTable;
use Manao\Servermanager\TagsServerTable;
use Manao\Servermanager\TagsUserTable;

class TagsController 
{	
	private $serverID;



	public function __construct($setverID)
	{
		$this->serverID = $setverID;
	}
	public function addTag($tagsName, $userID)
	{
		
		$resDB = TagsTable::getList([
			'filter' => ["TAGS_NAME" => $tagsName],
		]);
		$result = $resDB->fetchAll();
		$result = $result[0];
		if (empty($result)){
			$newTag = [
				'TAGS_NAME' => $tagsName,
			];
			$addAction = TagsTable::add($newTag);
			if ($addAction->isSuccess())
				{
					$tagID = $addAction->getId();
				}
			if(!isset($tagID)){
				throw new Exception("Error: tag $tagsName was not added!");
			}
		}else{
			$tagID = $result['ID'];
		}
		$this->addConnectServer($tagID);
		$this->addConnectUser($tagID, $userID);

	}

	public function addConnectServer($tagID)
	{
		$resDB = TagsServerTable::getList([
			'filter' => ["TAG_ID" => $tagID, "SERVER_ID" => $this->serverID],
		]);
		$result = $resDB->fetch();
		if (empty($result))
		{
			$connectionRow =[
				'TAG_ID' => $tagID,
				'SERVER_ID' => $this->serverID,
			];
			$addAction = TagsServerTable::add($connectionRow);
		}
	}
	public function addConnectUser($tagID, $userID)
	{
		$resDB = TagsUserTable::getList([
			'filter' => ["ID_TAG" => $tagID, "USER_ID" => $userID],
		]);
		$result = $resDB->fetch();
		if (empty($result))
		{
			$connectionRow =[
				'ID_TAG' => $tagID,
				'USER_ID' => $userID,
			];
			$addAction = TagsUserTable::add($connectionRow);
		}
	}
	public static function getAllTags()
	{
		$resDB = TagsTable::getList();
		$result = $resDB->fetchAll();
		return $result;
	}
	public function getTagsByID()
	{
		$resDB = TagsServerTable::getList([
			'filter' => ["SERVER_ID" => $this->serverID], 
			'select' => ['*', 'TAGS_' => 'INFO_TAG']
		]);
		$result = $resDB->fetchAll();
		return $result;
	}

	public function removeTag($tagID){
		$resDB = TagsServerTable::getList([
			'filter' => ["SERVER_ID" => $this->serverID, "TAG_ID" => $tagID], 
			'select' => ['*', 'TAGS_' => 'INFO_TAG']
		]);
		$result = $resDB->fetch();
		$deleteResult = TagsServerTable::delete($result['ID']);
		$resDB = TagsServerTable::getList([
			'filter' => ["TAG_ID" => $tagID], 
			'select' => ['*', 'TAGS_' => 'INFO_TAG']
		]);
		$resultArray = $resDB->fetchAll();
		if(empty($resultArray)){
			$resdelDB = TagsTable::delete($tagID);
		}

	}

	public static function getUsersTags($userID)
	{
		$resDB = TagsUserTable::getList([
			'filter' => ["USER_ID" => $userID],
		]);
		$resultDB = $resDB->fetchAll();

		return $result;
	}
}
