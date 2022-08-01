### Пример вызова компонента

```php
<?$APPLICATION->IncludeComponent(
	"manao:server.reserve", 
	"", 
	array(
		// "PAGE_SIZE" => 20,
		"FILTER_NAME" => "arrFilter",
		"SEF_MODE" => "Y",
        "COMPONENT_TEMPLATE" => "",
        "PAGER_TEMPLATE" => "",
		"SEF_FOLDER" => "/server_reserve/",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000",
		"SEF_URL_TEMPLATES" => array(
			"list" => "index.php",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
); ?>
```

### Функции

В файле [functions.php](/functions.php) находится ряд функций:

1. clearServerReserve() - сбрасывает занятость серверов.
2. scanTestServers() - сканирует директорию на предмет тестовых серверов.

