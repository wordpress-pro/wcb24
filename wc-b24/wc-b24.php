<?php
/**
 * Plugin Name: WooCommerce-Bitrix24
 * Plugin URI:
 * Description: Bitrix24 WooCommerce extension
 * Version: 0.3.7
 * Author: Vadim Pshentsov <pshentsoff@yandex.ru>
 * Author URI: http://kinohouse-work.bitrix24.ru/oauth/authorize/?response_type=code&client_id=local.56f18417641fd2.08455444&redirect_uri=http://surikolq.bget.ru/index.php?wcb24=1
 * Requires at least: 4.1
 * Tested up to: 4.4
 *
 * Text Domain: wc_b25
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
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/rest.php';
require_once __DIR__ . '/includes/Class.REST.php';
require_once __DIR__ . '/includes/hooks.php';

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