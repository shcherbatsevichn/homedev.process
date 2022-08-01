<?php
namespace Homedev\Process;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

use \Exception;
use Homedev\Process\GModuleTable;
use \Bitrix\Main\Type\DateTime;


class GModuleController
{	
	private $shopName;	
	private $device;
	private $bkNumber;
	private $serialNumber;
	private $scratchCode;
	private $malfunction;



	public function __construct($shopName, $device, $bkNumber, $serialNumber, $scratchCode, $malfunction)
	{
		$this->shopName = $shopName;
		$this->device = $device;
		$this->bkNumber = $bkNumber;
		$this->serialNumber = $serialNumber;
		$this->scratchCode = $scratchCode;
		$this->malfunction = $malfunction;
	}
	
	public function createLine(){
		$result = GModuleTable::add(array(
			'SHOPS_NAME' => $this->shopName,
			'DATE_CREATE' => DateTime::createFromPhp(new \DateTime()),
			'DEVICE_NAME' => $this->device,
			'BK_NUMBER' => $this->bkNumber,
			'SERIAL_NUMBER' => $this->serialNumber,
			'SCRATCH_CODE' => $this->scratchCode,
			'MALFUNCTION_NAME' => $this->malfunction,
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
	public static function getAllRows($filter){
		$result = GModuleTable::GetList([
			'filter' => $filter
			])->fetchall();
		return $result;
	}
	public function readLine(){}
	public function updateLine(){}
	public function deleteLine(){}

}
