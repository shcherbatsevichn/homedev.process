<?php
namespace Manao\Servermanager;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

class TagsServerTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'manao_tags_server';
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
	
				(new IntegerField('TAG_ID'))
					->configurePrimary(false),
	
				(new Reference('INFO_TAG', TagsTable::class,
					Join::on('this.TAG_ID', 'ref.ID')))
					->configureJoinType('inner'),

				(new IntegerField('SERVER_ID'))
					->configurePrimary(false),
	
				(new Reference('SERVER_INFO', ServerReserveTable::class,
					Join::on('this.SERVER_ID', 'ref.ID')))
					->configureJoinType('inner'),
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