<?php
namespace Manao\Servermanager;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

class TagsUserTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'manao_tags_user';
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
				(new IntegerField('ID_TAG'))
					->configurePrimary(false),
	
				(new IntegerField('USER_ID'))
					->configurePrimary(false),
	
			];

	}
}