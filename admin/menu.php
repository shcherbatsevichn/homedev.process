<?php
global $APPLICATION;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight('homedev.process') == 'D') {
	return false;
}

if (!Loader::includeModule('homedev.process')) {
	return false;
}

$aMenu = [
	'parent_menu' => 'global_menu_settings',
	'section' => 'menu_system',
	'sort' => 50,
	'text' => Loc::getMessage('MENU_MANAO'),
	'icon' => 'sys_menu_icon',
	'page_icon' => 'sys_page_icon',
	'items_id' => 'homedev.process',
	'url' => 'homedev_process.php?' . http_build_query([
			'lang' => LANGUAGE_ID
		]),
];

return $aMenu;