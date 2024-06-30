<?php

/**
 * Bluewater 8 MVC Core Bootstrap file.
 * This is where all the pathing and relative information
 * is defined and referenced from.
 *
 * Your application index.php must INCLUDE this file first off to
 * establish relative pathing properly
 *
 * This file should live outside of web accessible directories
 *
 * This file is part of Bluewater 8 MVC.<br />
 * <i>Copyright (c) 2006 - 2021 Walter Torres <walter@torres.ws></i>
 *
 * <b>NOTICE OF LICENSE</b><br />
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at:
 * {@link http://opensource.org/licenses/osl-3.0.php}.<br />
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@bluewatermvc.org so one can be sent to you immediately.
 *
 * <b>DISCLAIMER</b><br />
 * Do not modify to this file if you wish to upgrade Bluewater 8 MVC
 * in the future. If you wish to customize Bluewater 8 MVC for your needs
 * please refer to {@link http://web.bluewatermvc.org} for more information.
 *
 * PHP version 7+
 *
 * @package     Bluewater8_Core
 * @subpackage  Support
 * @link        http://web.bluewatermvc.org
 *
 * @author      Walter Torres <walter@torres.ws>
 * @version     v.8.0 (11/02/2021)
 *
 * @copyright   Copyright (c) 2006-2021 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filesource
 *
 */

declare(strict_types=1); // strict mode

namespace App;

// ******************************************************************
// ******************************************************************

// No need to change anything below this point.
// Just make sure you know what this is doing if you decide to
// change anything!

// ******************************************************************
// ******************************************************************

// NOTE: The CONSTANTS "SITE_ROOT", "APP_ROOT", "CACHE_ROOT"
//       "BLUEWATER" and "LIBRARY" must be defined in the
//       "index.php" located in your application web root.


class AppBootstrap
{


    /**
     * APP_CONFIG defines where all the Application specific CONFIG files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */

    public const APP_CONFIG = APP_ROOT . DS . 'Config';

    /**
     * APP_HELPER defines where all the Application specific HELPER files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_HELPER = APP_ROOT . DS . 'Helper';

    /**
     * APP_PLUGIN defines where all the Application specific Plugin files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_PLUGIN = APP_ROOT . DS . 'Plugin';

    /**
     * APP_MODULE defines where all the Application specific Module files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_MODULE = APP_ROOT . DS . 'Modules';

    /**
     * APP_LOGS defines where all the Application specific Log files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_LOGS = APP_ROOT . DS . 'Logs';

    /**
     * APP_COLLECTIONS defines where all the Application specific Collections files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_COLLECTIONS = APP_ROOT . DS . 'Collections';

    /**
     * APP_MVC defines where all the Application specific MVC files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_MVC = APP_ROOT . DS . 'MVC';

    /**
     * APP_MODEL defines where all the Application specific Model files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_MODEL = APP_ROOT . DS . 'MVC' . DS . 'Model';

    /**
     * APP_CONTROL defines where all the Application specific Control files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_CONTROL = APP_ROOT . DS . 'MVC' . DS . 'Controller';

    /**
     * APP_VIEW defines where all the Application specific View files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_VIEW = APP_ROOT . DS . 'MVC' . DS . 'View';

    /**
     * APP_TEMPLATE defines where all the Application specific Template files reside.
     *
     * This should be outside your web-accessible directories.
     * Do not place a slash at the end of this path.
     *
     * @constant string
     *
     * @since    1.0
     *
     */
    public const APP_TEMPLATE = APP_ROOT . DS . 'MVC' . DS . 'View' . DS . 'Templates';

    public function __construct()
    {
    }
}

// eof
