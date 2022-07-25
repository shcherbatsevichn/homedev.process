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
 * <li> DEVICE_NAME string(255) required
 * <li> BK_NUMBER string(255) required
 * <li> SERIAL_NUMBER string(255) required
 * <li> SCRATCH_CODE string(255) required
 * <li> MALFUNCTION_NAME string(255) required
 * <li> VIDEO bool(N, Y) required
 * <li> COMPLETE bool(N, Y) required
 * </ul>
 *
 * @package Bitrix\Server
 **/

class GModuleTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'homedev_gm';
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
				'DEVICE_NAME',
				[
					'required' => true
				]
			),
			new StringField(
				'BK_NUMBER',
				[
					'required' => true
				]
			),
			new StringField(
				'SERIAL_NUMBER',
				[
					'required' => true
				]
			),
			new StringField(
				'SCRATCH_CODE',
				[
					'required' => true
				]
			),
			new StringField(
				'MALFUNCTION_NAME',
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