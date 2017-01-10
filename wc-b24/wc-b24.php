<?php
/**
 * Plugin Name: WooCommerce-Bitrix24
 * Plugin URI:
 * Description: Bitrix24 WooCommerce extension
 * Version: 0.4.2
 * Author: Vadim Pshentsov <pshentsoff@yandex.ru>
 * Author URI: http://pshentsoff.ru
 * Requires at least: 4.1
 * Tested up to: 4.4
 *
 * Text Domain: wc_b24
 * Domain Path: /languages/
 *
 * @package WooCommerce
 * @category Extension
 * @author Vadim Pshentsov <pshentsoff@yandex.ru>
 *
 * @created     16.03.16
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once __DIR__ . '/config/defines.php';
require_once __DIR__ . '/includes/rest.php';
require_once __DIR__ . '/includes/Class.REST.php';
require_once __DIR__ . '/includes/hooks.php';
require_once __DIR__ . '/includes/options.php';

if(isset($_REQUEST['wcb24'])) {

//	error_log('wcb24: _REQUEST = '.print_r($_REQUEST, true));

	$rest = new \WCB24\REST();

	if(isset($_REQUEST['code'])) {

		$code = $_REQUEST["code"];
		$domain = $_REQUEST["domain"];
		$member_id = $_REQUEST["member_id"];

		$rest->getAccessCode($code, $domain);

	}
}