<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
include_once $_SERVER['DOCUMENT_ROOT']."/local/components/manao/server.reserve/ServerReserve.php";

/*
	Функция clearServerReserve() сбрасывает занятость сервера ('USER_ID' => null)
	Если были заняты сервера, то возвращает массив с инфой: Название сервера и id пользователя, который занимает сервер.
*/
function clearServerReserve() {
	$arResult = [];
	$arServersID = [];
	$resDB = \Manao\ServerReserveTable::getList([
		'filter' => ["!USER_ID" => false],
		'select' => ["ID", "USER_ID", "NAME"],
	]);
	while ($item = $resDB->Fetch()) {
		$arServersID[] = $item['ID'];
		$arResult[] = $item;
	}

	if (!empty($arServersID)) {
		$data = [
			'USER_ID' => null,
			'DATE_UPDATE' => \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime()),
		];
		$resDB = \Manao\ServerReserveTable::updateMulti($arServersID, $data);
	}

	if (!empty($arResult)) {
		return $arResult;
	}
	return false;
}

/*
	Функция scanTestServers() сканирует директорию на предмет тестовых серверов, возвращает массив со списком добаленных(ADD) и удаленных(DELETE) серверов
	Если ничего не добавлено или удалено, то вернет false
	$dir 		- Поиск идет вначале по этой вроженности, затем по подпапкам найденых папок. Два уровня.
	$exceptions - Массив исключений. Папки первого уровня, которые нужно исключить из поиска.
				  Если первый символ %, то в названии ищется любое совпадение подстроки. Пример для "%trainee", найдет и "980trainee" и "trainee1000".
				  Если нет символа % вначале, то исключается по полному совпадению названия.
*/
function scanTestServers($dir, $exceptions) {
	$exceptionsMask = [];
	foreach ($exceptions as $key => $excep) {
		if (mb_substr($excep, 0, 1) == '%') {
			$exceptionsMask[] = mb_substr($excep, 1);
			unset($exceptions[$key]);
		}
	}

	$arResult = [];

	$arServers = [];
	$arServersToAdd = [];
	$arServersToDel = [];

	$arServersDB = [];
	$resDB = \Manao\ServerReserveTable::getList([
		// 'filter' => $filter,
		'select' => ["ID", "NAME"],
	]);
	while ($item = $resDB->Fetch()) {
		$arServersDB[$item['ID']] = $item['NAME'];
	}

	$arExeptionsToDel = [];

	$files = scandir($dir);
	foreach ($files as $dir1) {
		if ($dir1 == '.' || $dir1 == '..') continue;

		if (in_array($dir1, $exceptions)) {
			$arExeptionsToDel[] = $dir1.'.dev-bitrix.by';
			continue;
		}
		
		$isExept = false;
		foreach ($exceptionsMask as $mask) {
			if (strpos($dir1, $mask) !== false) {
				$arExeptionsToDel[] = $dir1.'.dev-bitrix.by';
				$isExept = true;
				break;
			}
		}

		if ($isExept) {
			continue;
		}

		$files2 = scandir($dir.'/'.$dir1);
		if ($files2) {
			foreach ($files2 as $dir2) {
				$name = false;
				if (strpos($dir2, 'dev-bitrix.by')) {
					$name = $dir2;
				} elseif ($dir2 == 'htdocs') {
					$name = $dir1.'.dev-bitrix.by';
				}
				$parentName = $dir1;
				
				if ($name) {
					$arServers[] = $name;
					if (in_array($name, $arServersDB)) continue;
					$arServersToAdd[] = [
						'NAME' => $name,
						'PARENT_NAME' => $parentName,
					];
				}
			}
		}
	}

	// Собираем сервера для удаления из БД
	foreach ($arServersDB as $id => $serverDB) {
		if (in_array($serverDB, $arExeptionsToDel)) {
			$arServersToDel[$id] = $serverDB;
			continue;
		}

		if (in_array($serverDB, $arServers)) continue;
		
		$arServersToDel[$id] = $serverDB;
	}
	
	if (!empty($arServersToAdd)) {
		$date = \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime());
		$rows = [];
		foreach ($arServersToAdd as $itemToAdd) {
			$rows[] = [
				'NAME' => $itemToAdd['NAME'],
				'PARENT_NAME' => $itemToAdd['PARENT_NAME'],
				'DATE_CREATE' => $date,
				'DATE_UPDATE' => $date,
			];
			$arResult['ADD'][] = $itemToAdd;
		}
		\Manao\ServerReserveTable::addMulti($rows);
	}

	if (!empty($arServersToDel)) {
		foreach ($arServersToDel as $id => $itemToDel) {
			\Manao\ServerReserveTable::delete($id);
			$arResult['DELETE'][] = $itemToDel;
		}
	}

	if (!empty($arResult)) {
		return $arResult;// Список добавленных и удаленных серверов
	}
	return false;
}