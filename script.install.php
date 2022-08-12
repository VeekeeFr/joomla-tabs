<?php
/**
 * @package         Regular Labs Installer
 * @version         22.8.8209
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://regularlabs.com
 * @copyright       Copyright © 2022 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

if ( ! class_exists('RegularLabsInstaller'))
{
    require_once __DIR__ . '/script.helper.php';
}

class PlgSystemRegularLabsInstallerTabsInstallerScript extends RegularLabsInstaller
{
    var $dir           = null;
    var $installerName = 'regularlabsinstallertabs';
    var $packages_dir  = null;

    public function __construct()
    {
        $this->dir          = __DIR__;
        $this->jversion     = (int) JVERSION;
        $this->packages_dir = $this->dir . '/packages/j' . $this->jversion;
    }
}
