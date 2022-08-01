<?php
namespace Homedev\Process;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField;
Loc::loadMessages(__FILE__);

/**
 * Class ServerReserve
 * 
 * Fields:
 * <ul>
 * <li> ID = numberGM int mandatory
 * <li> SHOPS_NAME string(255) required
 * <li> SHOPS_USER string(255) required
 * </ul>
 *
 * @package Bitrix\Server
 **/

class ShopListTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'homedev_shoplist';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
				]
			),
			new StringField(
				'SHOPS_NAME',
				[
					'required' => true
				]
			),
			new StringField(
				'SHOPS_USER_ID',
			),
		];
	}

}