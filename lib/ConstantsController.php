<?php
namespace Homedev\Process;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

use \Exception;
use Homedev\Process\IcheckTable;
use \Bitrix\Main\Type\DateTime;


class ConstantsController
{	
	public function __construct()
	{
	}
	
	public static function getConstantsValue($constantsName)
	{
		require_once($_SERVER['DOCUMENT_ROOT']."/local/modules/homedev.process/constants.php");
		foreach($consts as $constant => $value)
		{
			if($constant == $constantsName)
			{
				return $value;
			}
		}
		return false;

	}

}
