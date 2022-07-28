<?php
namespace Homedev\Process;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

use \Exception;
use Homedev\Process\IcheckTable;
use \Bitrix\Main\Type\DateTime;


class IcheckController
{	
	private $shopName;	
	private $checksNumber;
	private $couse;
	private $couseDescription;




	public function __construct($shopName, $checksNumber, $couse, $couseDescription)
	{
		$this->shopName = $shopName;
		$this->checksNumber = $checksNumber;
		$this->couse = $couse;
		$this->couseDescription = $couseDescription;
	}
	
	public function createLine(){
		$result = IcheckTable::add(array(
			'SHOPS_NAME' => $this->shopName,
			'DATE_CREATE' => DateTime::createFromPhp(new \DateTime()),
			'CHECKS_NUMBER' => $this->checksNumber,
			'COUSE' => $this->couse,
			'COUSE_DESCRIPTION' => $this->couseDescription,
			'COMPLETE' => 'N',
		));
		
		if ($result->isSuccess())
		{
			$id = $result->getId();
		}else{
			$id = 'Error, Data not found!';
		}
		return $id;
	}
	public function readLine(){}
	public function updateLine(){}
	public function deleteLine(){}

}
