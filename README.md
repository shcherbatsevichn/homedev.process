# Модуль  Homedev.process #
***
## Структура модуля: ## 

/admin - административная часть модуля  
/instal - инсталятор модуля
---- /components 
-------- /homedev - расположение компонентов 
------------ /homedev.shopprocess - компонент рабочего стола магазина. 
---- /db 
-------- /mysql - расположение компонентов 
------------ *instal.sql* - файл sql запросов на создание таблиц бд
/langs - языковые файлы
/lib
---- *GModule.php* - ORM-обертак таблицы Гарантийного модуля (ГМ)
---- *GModuleController.php* - Интерфейс таблицы ГМ
---- *Icheck.php* - ORM-обертак таблицы исправления чеков (ИЧ)
---- *IcheckController.php* - Интерфейс таблицы ИЧ
---- *TasksController.php* - Контроллер постановщика задач
/updates
*include.php*

***
## Концепция модуля: ## 
Данный модуль предназначен для полного переноса процесов, с которыми на ежедневной основе сталкиваются продавцы-консультанты. 



***
## О модуле: ##




