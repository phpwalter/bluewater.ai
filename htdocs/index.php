<?php


declare(strict_types=1); // strict mode

/**
 * Bluewater.ai index page.
 *
 * This file is part of Bluewater.ai.<br />
 * <i>Copyright (c) 2024 Walter Torres <walter@torres.ws></i>
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
 * Do not modify to this file if you wish to upgrade Bluewater.ai
 * in the future. If you wish to customize Bluewater.ai for your needs
 * please refer to {@link http://web.bluewatermvc.org} for more information.
 *
 * PHP version 8+
 *
 * @package     Bluewater8_Core
 * @subpackage  Support
 * @link        http://web.bluewatermvc.org
 *
 * @author      Walter Torres <walter@torres.ws>
 * @version     v.8.0 (06/01/2024)
 *
 * @copyright   Copyright (c) 2024 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filesource
 *
 */

namespace Bluewater;


use Bluewater\Config;
use Bluewater\Security\Session;

use const DIRECTORY_SEPARATOR;

/**
 * Right off the bat, make sure we have the correct version of PHP
 */
if (PHP_VERSION_ID < 80000) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Bluewater.ai requires version PHP 8.0 or greater.';
    exit(1); // EXIT_ERROR
}

/**
 * Redefine DIRECTORY_SEPARATOR for easier handling.
 *
 * @name     DS
 * @constant string
 *
 * @since    1.0
 *
 */
define('DS', DIRECTORY_SEPARATOR);


/**
 * This is the OS Path location that contains the root directory.
 * All other path constants are derived from this one.
 *
 * Due to file locations, and procedures within Bluewater.ai,
 * this path must be hand coded here. If the overall structure
 * changes, this value has to change as well.
 *
 * Do not place a slash at the end of this path.
 *
 * @name     SITE_ROOT
 * @constant string
 *
 * @since    1.0
 *
 */
define('SITE_ROOT', DS . 'var' . DS . 'www' . DS . 'Bluewater');

require_once SITE_ROOT . DS . 'vendor/autoload.php';

/**
 * Setup the environment
 */
new Bootstrap();


// ******************************************************************
/**
 * @TODO for setup, remove once classes are defined
 */
define('BW_ENV', 'development');
define('SESSION', true);

// ******************************************************************

// Load Bluewater.ai CONFIG data
Config::getInstance();

/*
 * Different environments require different levels of error reporting.
 * By default, development will show errors, but Testing and PROD will hide them.
 */
switch (BW_ENV) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        echo '1';
        break;

    case 'testing':
    case 'production':
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        ini_set('display_errors', 0);
        echo '2';
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'The application environment is not set correctly.';
        echo '3';
        exit(1); // EXIT_ERROR
}

//    // Activate Sessions, maybe
if (SESSION) {
    Session::init();
}

// Dispatch Controller
Dispatcher::Dispatch();

// eof
