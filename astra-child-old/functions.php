<?php
/**
 * gpc-astra Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package gpc-astra
 * @since 1.0.0
 */

use GpcAstra\Site\PostTypes\GpcLop;
use GpcAstra\Site\SiteInit;

/**
 * Define Constants
 */
define( 'GPC_ASTRA_VERSION', '1.0.0' );
define( 'GPC_ASTRA_DIR_URL', get_stylesheet_directory_uri() );
define( 'GPC_ASTRA_DIR', get_stylesheet_directory() );
//define('DISALLOW_FILE_EDIT', true);


require_once __DIR__ . '/vendor/autoload.php';
SiteInit::instance();