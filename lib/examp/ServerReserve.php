<?php
namespace Manao\Servermanager;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\DatetimeField,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
Loc::loadMessages(__FILE__);

/**
 * Class ServerReserve
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> PARENT_ID int optional
 * <li> PARENT_NAME string(255) optional
 * <li> NAME string(255) optional
 * <li> EXTERNAL_SOURCE string(1) optional - new
 * <li> USER_ID int optional
 * <li> DATE_CREATE datetime optional
 * <li> DATE_UPDATE datetime optional
 * <li> BUTTON string(255) optional
 * </ul>
 *
 * @package Bitrix\Server
 **/

class ServerReserveTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'manao_server_reserve';
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
					'title' => Loc::getMessage('RESERVE_ENTITY_ID_FIELD')
				]
			),
			(new Reference('TAGS_INFO', ServerReserveTable::class,
					Join::on('this.ID', 'ref.SERVER_ID')))
					->configureJoinType('inner'),
			new IntegerField(
				'PARENT_ID',
				[
					'title' => Loc::getMessage('RESERVE_ENTITY_PARENT_ID_FIELD')
				]
			),
			new StringField(
				'NAME',
				[
					'validation' => [__CLASS__, 'validateName'],
					'title' => Loc::getMessage('RESERVE_ENTITY_NAME_FIELD')
				]
			),
			new StringField(
				'BUTTON',
				[
					'validation' => [__CLASS__, 'validateButton'],
					'title' => Loc::getMessage('RESERVE_ENTITY_BUTTON_FIELD')
				]
			),
			new StringField(
				'PARENT_NAME',
				[
					'validation' => [__CLASS__, 'validateParentName'],
					'title' => Loc::getMessage('RESERVE_ENTITY_PARENT_NAME_FIELD')
				]
			),
			new StringField(
				'EXTERNAL_SOURCE',
				[
					'validation' => [__CLASS__, 'validateExternalSorce'],
					'title' => 'EXTERNAL_SOURCE'
				]
			),
			new IntegerField(
				'USER_ID',
				[
					'title' => Loc::getMessage('RESERVE_ENTITY_USER_ID_FIELD')
				]
			),
			new IntegerField(
				'CREATOR_ID',
				[
					'title' => "CREATOR_ID"
				]
			),
			new DatetimeField(
				'DATE_CREATE',
				[
					'title' => Loc::getMessage('RESERVE_ENTITY_DATE_CREATE_FIELD')
				]
			),
			new DatetimeField(
				'DATE_UPDATE',
				[
					'title' => Loc::getMessage('RESERVE_ENTITY_DATE_UPDATE_FIELD')
				]
			),
			
		];
	}

	/**
	 * Returns validators for NAME field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

    /**
	 * Returns validators for BUTTON field.
	 *
	 * @return array
	 */
	public static function validateButton()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	/**
	 * Returns validators for PARENT_NAME field.
	 *
	 * @return array
	 */
	public static function validateParentName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
	public static function validateExternalSorce()
	{
		return [
			new LengthValidator(null, 1),
		];
	}
}