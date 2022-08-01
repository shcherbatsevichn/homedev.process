<?php

if (is_file($_SERVER["DOCUMENT_ROOT"] . "/local/modules/homedev.process/admin/homedev_process.php")) {
	require($_SERVER["DOCUMENT_ROOT"] . "/local/modules/homedev.process/admin/homedev_process.php");
} else {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/homedev.process/admin/homedev_process.php");
}