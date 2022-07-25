<?php
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
include_once $_SERVER['DOCUMENT_ROOT']."/local/components/manao/server.reserve/ServerReserve.php";

global $DB;

$tableName = \Manao\ServerReserveTable::getTableName();

$res = $DB->Query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName' AND COLUMN_NAME = 'PARENT_NAME'");
if (!$res->Fetch()) {
	$DB->Query("ALTER TABLE $tableName ADD PARENT_NAME VARCHAR(255) NULL");
}


$resDB = \Manao\ServerReserveTable::getList([
	'filter' => ["PARENT_NAME" => false],
	'select' => ["ID", "USER_ID", "NAME", "PARENT_NAME"],
]);
while ($item = $resDB->Fetch()) {
	$patterns = ['/.dev-bitrix.by/', '/^t[0-9]*\./'];
	$replacements = ['', ''];
	$clearName = preg_replace($patterns, $replacements, $item['NAME']);

	\Manao\ServerReserveTable::update($item['ID'], ['PARENT_NAME' => $clearName]);
}