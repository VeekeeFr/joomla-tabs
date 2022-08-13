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

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Layout\FileLayout as JFileLayout;

class DownloadKey
{
	/**
	 * @param string $extension
	 */
	public static function get($update = true)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('extra_query')
			->from('#__update_sites')
			->where($db->quoteName('extra_query') . ' LIKE ' . $db->quote('k=%'))
			->where($db->quoteName('location') . ' LIKE ' . $db->quote('%download.regularlabs.com%'));

		$db->setQuery($query);

		$key = $db->loadResult();

		if ( ! $key)
		{
			return '';
		}

		RegEx::match('#k=([a-zA-Z0-9]{8}[A-Z0-9]{8})#', $key, $match);

		if ( ! $match[1])
		{
			return '';
		}

		$key = $match[1];

		if ($update)
		{
			self::store($key);
		}

		return $key;
	}

	/**
	 * @param string $extension
	 */
	public static function store($key)
	{
		if ( ! self::isValidFormat($key))
		{
			return false;
		}

		$extra_query = $key ? 'k=' . $key : '';

		$query = DB::getQuery()
			->update('#__update_sites')
			->set(DB::is('extra_query', $extra_query))
			->where(DB::like('location', '%download.regularlabs.com%'))
			->where(DB::combine([
				DB::like('location', '%&pro=%'),
				DB::like('location', '%e=extensionmanager%'),
			], 'OR'));

		$result = DB::get()->setQuery($query)->execute();

		JFactory::getCache()->clean('_system');

		return $result;
	}

	/**
	 * @param string $extension
	 */
	public static function getOutputForComponent($extension = 'all', $use_modal = true, $hidden = true, $callback = '')
	{
		$id = 'downloadkey_' . strtolower($extension);

		Document::script('regularlabs.script');
		Document::script('regularlabs.downloadkey');

		return (new JFileLayout(
			'regularlabs.form.field.downloadkey',
			JPATH_SITE . '/libraries/regularlabs/layouts'
		))->render(
			[
				'id'         => $id,
				'extension'  => strtolower($extension),
				'use_modal'  => $use_modal,
				'hidden'     => $hidden,
				'callback'   => $callback,
				'show_label' => true,
			]
		);
	}

	/**
	 * @param string $key
	 */
	public static function isValid($key, $extension = 'all')
	{
		$key = trim($key);

		if ( ! self::isValidFormat($key))
		{
			return json_encode([
				'valid'  => false,
				'active' => false,
			]);
		}

		$cache = new Cache;
		$cache->useFiles(1);

		if ($cache->exists())
		{
			return $cache->get();
		}

		$result = Http::getFromUrl('https://download.regularlabs.com/check_key.php?k=' . $key . '&e=' . $extension);

		return $cache->set($result);
	}

	/**
	 * @param string $key
	 */
	public static function isValidFormat($key)
	{
		$key = trim($key);

		if ($key === '')
		{
			return true;
		}

		if (strlen($key) != 16)
		{
			return false;
		}

		return RegEx::match('^[a-zA-Z0-9]{8}[A-Z0-9]{8}$', $key, $match, 's');
	}
}
