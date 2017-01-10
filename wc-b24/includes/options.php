<?php
/**
 * @file        options.php
 * @description
 *
 * @version
 * PHP Version  7
 *
 * @package     1. wcb24
 *
 * @copyright   2015, Vadim Pshentsov. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @author      Vadim Pshentsov <pshentsoff@gmail.com>
 * @link        http://pshentsoff.ru Author's homepage
 * @link        http://blog.pshentsoff.ru Author's blog
 *
 * @created     26.03.16
 *
 * @since
 *
 */

add_action('admin_menu', 'wcb24_options_menu');
function wcb24_options_menu()
{
	add_options_page('WC-B24 Options', 'WC-B24 Options', 10, 'options', 'wcb24_options_page');
}

function wcb24_options_page()
{
	$crm_host = get_option('wcb24_crm_host', false);
	$client_id = get_option('wcb24_client_id', false);
	$redirect_uri = get_option('siteurl').WCB24_PATH;
	$authorization_link = "http://$crm_host/oauth/authorize/?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri";
	?>
	<div class="wrap">
		<h2>WooCommerce Bitrix24 options.</h2>
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Портал Битрикс24</th>
					<td>
						<input type="text" name="wcb24_crm_host" value="<?php echo $crm_host; ?>" placeholder="BITRIX24 DOMAIN"/>
						<p class="description">
							Укажите имя Вашего портала Битрикс24 (Пример: <b>my-portal.bitrix24.ru</b>)
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Адрес обратной ссылки</th>
					<td>
						<p>
							<b><?php echo $redirect_uri; ?></b>
						</p>
						<p class="description">
							Адрес обратной ссылки для указания при регистрации плагина на Вашем портале Битрикс24
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Код приложения</th>
					<td>
						<input type="text" name="wcb24_client_id" value="<?php echo $client_id; ?>" placeholder="APP CLIENT ID"/>
						<p class="description">
							Для получения Кода приложения необходимо зарегистрировать плагин на Вашем портале Битрикс24<b></b> и скопировать сюда полученное значение.
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Ключ приложения</th>
					<td>
						<input type="text" name="wcb24_client_secret" value="<?php echo get_option('wcb24_client_secret', false); ?>" placeholder="APP CLIENT SECRET" />
						<p class="description">
							Скопируйте сюда значение Ключа приложения, полученного при регистрации приложения на Вашем портале Битрикс24.
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Access Token</th>
					<td>
						<input type="text" name="wcb24_access_token" value="<?php echo get_option('wcb24_access_token', false); ?>" disabled readonly placeholder="ACCESS TOKEN" />
						<?php if(!empty($crm_host) && !empty($client_id)) { ?>
						<p class="description">Для получения токена или обновления существующего авторизуйтесть на указанном портале (<?php echo $crm_host; ?>) и затем пререйдите по
							<a href="<?php echo $authorization_link; ?>" target="_blank">ссылке</a> (откроется в новом окне). При условии правильности указанных параметров остальное плагин сделает автоматичекски. Обновите страницу настроек чтобы убедиться, что новый токен успешно получен.</p>
						<?php } else { ?>
						<p class="description">
							Для получения токена необходимо указать портал Битрикс24, зарегистрировать в нем данный плагин, указав в качестве обратной ссылки <b><?php echo $redirect_uri; ?></b> и скопировать в соответствующее поле полученный Код приложения.
						</p>
						<?php } ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Refresh Token</th>
					<td><input type="text" name="wcb24_refresh_token" value="<?php echo get_option('wcb24_refresh_token', false); ?>" disabled readonly placeholder="REFRESH TOKEN" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Use SKU as Product ID</th>
					<td>
						<input type="checkbox"
						       name="wcb24_use_sku_as_product_id" <?php echo get_option( 'wcb24_use_sku_as_product_id', true ) ? 'checked' : ''; ?> />
						<p class="description">
							Использовать SKU (артикул) товара в качестве Product ID
						</p>
					</td>
				</tr>
			</table>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="wcb24_crm_host,wcb24_client_id,wcb24_client_secret" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php
}