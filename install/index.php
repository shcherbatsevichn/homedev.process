<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

if (class_exists('homedev_process')) {
	return;
}

class homedev_process extends CModule
{
	/** @var string */
	public $MODULE_ID;

	/** @var string */
	public $MODULE_VERSION;

	/** @var string */
	public $MODULE_VERSION_DATE;

	/** @var string */
	public $MODULE_NAME;

	/** @var string */
	public $MODULE_DESCRIPTION;

	/** @var string */
	public $MODULE_GROUP_RIGHTS;

	/** @var string */
	public $PARTNER_NAME;

	/** @var string */
	public $PARTNER_URI;


	public $path = "";

	function setPath()
	{
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		$this->path = $path;
	}
	public function __construct()
	{
		$this->setPath();

		$arModuleVersion = array();

		include($this->path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		$this->MODULE_ID = 'homedev.process';
		$this->MODULE_NAME = Loc::getMessage('HOMEDEV_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('HOMEDEV_MODULE_DESCRIPTION');
		$this->MODULE_GROUP_RIGHTS = 'Y';
		$this->PARTNER_NAME = "HomeDev";
		$this->PARTNER_URI = "shcherbatsevich.n.dvl@yandex.by";
	}

	public function doInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);

		CopyDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
	}

	public function doUninstall()
	{
		DeleteDirFiles(__DIR__ . "/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

		ModuleManager::unregisterModule($this->MODULE_ID);
	}
}
