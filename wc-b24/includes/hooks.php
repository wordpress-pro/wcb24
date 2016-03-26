<?php
/**
 * @file        hooks.php
 * @description
 *
 * @version
 * PHP Version  7
 *
 * @package     pushaplatok
 *
 * @copyright   2015, Vadim Pshentsov. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @author      Vadim Pshentsov <pshentsoff@gmail.com>
 * @link        http://pshentsoff.ru Author's homepage
 * @link        http://blog.pshentsoff.ru Author's blog
 *
 * @created     25.03.16
 *
 * @since
 *
 */

add_filter('woocommerce_checkout_order_processed', 'wcb24_order_processed', 10, 2);
function wcb24_order_processed($order_id, $posted)
{
	global $wp;

	$sku_as_product_id = WCB24_SKU_AS_PRODUCT_ID;

	$order = new WC_Order($order_id);

	$order_items = $order->get_items();

//	error_log('wcb24_order_processed: Order items = '.print_r($order_items, true));

	$items = array();
	foreach ($order_items as $key => $item) {

		$product = new \WC_Product($item['product_id']);

		$items[] = array(
			"PRODUCT_ID" => ($sku_as_product_id ? $product->get_sku() : $item['product_id']),
			"QUANTITY" => $item['qty'],
			'PRODUCT_NAME' => $item['name'],
			"PRICE" => $product->get_price(),
		);

		unset($product);

	}
	$total = $order->calculate_totals();
	$posted['payment_method'] = $order->payment_method_title;
	$posted['shipping_method'] = $order->get_shipping_method();

//	error_log('wcb24_order_processed: New order: '.$order_id);
//	error_log('wcb24_order_processed: Order items = '.print_r($items, true));
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

add_filter('wpcf7_mail_components', 'wcb24_wpcf7_mail_components', 10, 2);
function wcb24_wpcf7_mail_components($mail_params, $form = null)
{
	error_log('wcb24_wpcf7_mail_components: $mail_params = '.print_r($mail_params, true));

	wcb24_rest_send_cf7_to_lead($mail_params['sender'], $mail_params['body']);

	return $mail_params;
}