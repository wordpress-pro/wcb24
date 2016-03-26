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
	?>
	<div class="wrap">
		<h2>WooCommerce Bitrix24 options.</h2>
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Access Token</th>
					<td><input type="text" name="new_option_name" value="<?php echo get_option('wcb24_access_token', false); ?>" disabled readonly /></td>
				</tr>
			</table>
		</form>
	</div>
	<?php
}