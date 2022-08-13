<?php
/**
 * @package         Tabs
 * @version         8.2.2
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://regularlabs.com
 * @copyright       Copyright © 2022 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\EditorButtonPlugin as RL_EditorButtonPlugin;
use RegularLabs\Library\Extension as RL_Extension;

defined('_JEXEC') or die;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/regularlabs.xml')
	|| ! is_file(JPATH_LIBRARIES . '/regularlabs/src/EditorButtonPlugin.php')
	|| ! is_file(JPATH_LIBRARIES . '/regularlabs/src/DownloadKey.php')
)
{
	return;
}

if ( ! RL_Document::isJoomlaVersion(4))
{
	RL_Extension::disable('tabs', 'plugin', 'editors-xtd');

	return;
}

if (true)
{
	class PlgButtonTabs extends RL_EditorButtonPlugin
	{
		protected         var $require_core_auth = false;
	}
}
