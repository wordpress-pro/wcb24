<?php
/**
 * Plugin Name: WooCommerce-Bitrix24
 * Plugin URI:
 * Description: Bitrix24 WooCommerce extension
 * Version: 0.3.5
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

add_filter('woocommerce_checkout_order_processed', 'wcb24_order_processed', 10, 2);
function wcb24_order_processed($order_id, $posted)
{
	global $wp;

	$order = new WC_Order($order_id);

	$order_items = $order->get_items();

//	error_log('wcb24_order_processed: Order items = '.print_r($order_items, true));

	$items = array();
	foreach ($order_items as $key => $item) {
		$items[$key] = array(
			'PRODUCT_ID' => $item['product_id'],
			'QUANTITY' => $item['qty'],
//			'name' => $item['name'],
//			'total' => $item['line_total'],
		);
	}
	$total = $order->calculate_totals();
	$posted['payment_method'] = $order->payment_method_title;
	$posted['shipping_method'] = $order->get_shipping_method();

//	error_log('wcb24_order_processed: New order: '.$order_id);
	error_log('wcb24_order_processed: Order items = '.print_r($items, true));
//	error_log('wcb24_order_processed: Total = '.print_r($total, true));
//	error_log('wcb24_order_processed: Checkout = '.print_r($posted, true));

	$use_REST = get_option('wcb24_use_rest', WCB24_USE_REST_AS_DEFAULT);

	if($use_REST) {
		$lead_id = wcb24_rest_send_lead($order_id, $total, $posted, $items);

		// Ошибка добавления лида
		if($lead_id === false) {
			return;
		}
	} else {
		// прямой запрос, не REST
		$lead_id = wcb24_send_lead($order_id, $total, $posted);

		// Ошибка добавления лида
		if($lead_id === false) {
			return;
		}
	}

}

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