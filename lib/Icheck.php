<?php
namespace Homedev\Process;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\DatetimeField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\BooleanField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
Loc::loadMessages(__FILE__);

/**
 * Class ServerReserve
 * 
 * Fields:
 * <ul>
 * <li> ID = numberGM int mandatory
 * <li> SHOPS_NAME string(255) required
 * <li> DATE_CREATE datetime required
 * <li> CHECKS_NUMBER string(255) required
 * <li> COUSE string(255) required
 * <li> COUSE_DESCRIPTION string(255) required
 * <li> COMPLETE bool(N, Y) required
 * </ul>
 *
 * @package Bitrix\Server
 **/

class IcheckTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'homedev_ich';
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
			new DatetimeField(
				'DATE_CREATE',
				[
					'required' => true
				]
			),
			new StringField(
				'CHECKS_NUMBER',
				[
					'required' => true
				]
			),
			new StringField(
				'COUSE',
				[
					'required' => true
				]
			),
			new StringField(
				'COUSE_DESCRIPTION',
				[
					'required' => true
				]
			),
			new BooleanField(
				'COMPLETE', 
				[
					'required' => true,
					'values' => array('N', 'Y')
				]
			
			),

		];
	}

}