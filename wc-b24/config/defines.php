<?php
/**
 * @file        defines.php
 * @description
 *
 * @version
 * PHP Version  7
 *
 * @package     wordpress
 *
 * @copyright   2015, Vadim Pshentsov. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3
 * @author      Vadim Pshentsov <pshentsoff@gmail.com>
 * @link        http://pshentsoff.ru Author's homepage
 * @link        http://blog.pshentsoff.ru Author's blog
 *
 * @created     22.03.16
 *
 * @since
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// CRM server conection data
defined('WCB24_CRM_PORT') or define('WCB24_CRM_PORT', '443'); // CRM server port
defined('WCB24_CRM_LEAD_PATH') or define('WCB24_CRM_LEAD_PATH', '/crm/configs/import/lead.php'); // CRM server REST service path

// CRM server authorization data
defined('WCB24_CRM_LOGIN') or define('WCB24_CRM_LOGIN', 'YOUR BITRIX24 LOGIN'); // login of a CRM user able to manage leads
defined('WCB24_CRM_PASSWORD') or define('WCB24_CRM_PASSWORD', 'YOUR BITRIX24 LOGIN'); // password of a CRM user

/**
 * client_id приложения
 */
define('WCB24_CLIENT_ID', 'APPLICATION CLIENT ID');
/**
 * client_secret приложения
 */
define('WCB24_CLIENT_SECRET', 'APPLICATION CLIENT SECRET');
/**
 * относительный путь приложения на сервере
 */
define('WCB24_PATH', '/?wcb24=1');
/**
 * scope приложения
 */
define('WCB24_SCOPE', 'crm');

/**
 * протокол, по которому работаем. должен быть https
 */
define('WCB24_PROTOCOL', 'https');

/**
 * Использовать REST API
 */
define('WCB24_USE_REST_AS_DEFAULT', true);
/**
 * Минимальное оставшееся время жизни токена перед отправкой запроса на обновление (сек)
 */
define('WCB24_TOKEN_TTL_MIN', 30);
/**
 * Передача SKU (артикула) в качестве PRODUCT_ID
 */
define('WCB24_SKU_AS_PRODUCT_ID', true);