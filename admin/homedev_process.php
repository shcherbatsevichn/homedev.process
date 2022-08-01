<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

/** @global $APPLICATION CMain */
global $APPLICATION;

Loc::loadMessages(__FILE__);
$APPLICATION->SetTitle(Loc::getMessage('TITLE'));

try {
	if (!Loader::includeModule('homedev.process')) {
		throw new Exception('need to install module homedev.process');
	}
	if ($APPLICATION->GetGroupRight('homedev.process') == 'D') {
		throw new Exception(Loc::getMessage('ACCESS_DENIED'));
	}

	require("interface.php");

	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
} catch (Exception $e) {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

	echo $e->getMessage();

	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
} catch (Throwable $e) {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

	echo $e->getMessage();

	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}