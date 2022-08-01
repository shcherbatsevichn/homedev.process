<?php
namespace Homedev\Process;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

use \Exception;
use Homedev\Process\ShopListTable;
use \Bitrix\Main\Type\DateTime;


class ShopListController
{	
	private $shopName;	
	private $userId;
	private $shopId;

	public function __construct($shopName, $userId)
	{
		$this->shopName = $shopName;
		$this->userId = $userId;
	}
	
	public function createNewShop($shopName){
		if(!$this->searchShop($shopName)){
			$this->shopName = $shopName;
			$result = ShopListTable::add(array(
				'SHOPS_NAME' => $this->shopName,
			));
			if ($result->isSuccess())
			{
				$id = $result->getId();
				$this->shopId = $id;
			}else{
				$id = 'Error, Data not found!';
			}
			return true;
		}else{
			return false;
		}
	}

	public function searchShop($shopName){
		$filter = ['SHOPS_NAME' => $shopName];
		$result = ShopListTable::GetList(['filter' => $filter])->fetch();
		if($result['ID']){
			$this->shopId = $result['ID'];
		}else{
			return false;
		}
		return true;
	
	}

	public function addSalesman(){
		$this->searchShop($this->shopName);
		$result = ShopListTable::update($this->shopId, array(
			'SHOPS_USER_ID' => $this->userId
		));

	}
	public function deleteSalesman(){
		$this->searchShop($this->shopName);
		$result = ShopListTable::update($this->shopId, array(
			'SHOPS_USER_ID' => '-'
		));

	}

	public static function getShopUserList($filter = false){
		if($filter){
			$tableList = ShopListTable::getList([
				'filter' => $filter
				])->fetchAll();
		}else{
			$tableList = ShopListTable::getList()->fetchAll();
		}
		
		return $tableList;
	}

	public static function getShopList(){
		$allList = self::getShopUserList();
		foreach($allList as $row){
			$result[] = $row['SHOPS_NAME'];
		}
		return $result;
	}

	public static function getUsersShop($usersId){
		$list = ShopListTable::getList([
			'filter' => array(
				'SHOPS_USER_ID' => $usersId,
			)
			])->fetch();
		if($list['SHOPS_NAME']){
			return $list['SHOPS_NAME'];
		}else{
			return false;
		}
		
	}

	public function readLine(){}
	public function updateLine(){}
	public function deleteLine(){}

}
