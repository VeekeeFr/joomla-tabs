<?php
/**
 * @package         Regular Labs Library
 * @version         22.6.8549
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://regularlabs.com
 * @copyright       Copyright © 2022 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use RegularLabs\Library\DB as RL_DB;

class SimpleCategory
{
	public static function save($table, $item_id, $category, $id_column = 'id')
	{
		$db = RL_DB::get();

		$query = $db->getQuery(true)
			->select(RL_DB::quoteName($id_column))
			->from(RL_DB::quoteName('#__' . $table))
			->where(RL_DB::quoteName($id_column) . ' = ' . $item_id);

		$item_exists = $db->setQuery($query)->loadResult();

		if ($item_exists)
		{
			$query = $db->getQuery(true)
				->update(RL_DB::quoteName('#__' . $table))
				->set(RL_DB::quoteName('category') . ' = ' . RL_DB::quote($category))
				->where(RL_DB::quoteName($id_column) . ' = ' . $item_id);

			$db->setQuery($query)->execute();

			return;
		}

		$query = 'SHOW COLUMNS FROM `#__' . $table . '`';
		$db->setQuery($query);

		$columns = $db->loadColumn();

		$values             = array_fill_keys($columns, '');
		$values[$id_column] = $item_id;
		$values['category'] = $category;

		$query = $db->getQuery(true)
			->insert(RL_DB::quoteName('#__' . $table))
			->columns(RL_DB::quoteName($columns))
			->values(implode(',', RL_DB::quote($values)));

		$db->setQuery($query)->execute();
	}
}
