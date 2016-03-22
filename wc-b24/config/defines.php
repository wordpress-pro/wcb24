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

// CRM server conection data
defined('WCB24_CRM_HOST') or define('WCB24_CRM_HOST', 'kinohouse-work.bitrix24.ru'); // your CRM domain name
// http://kinohouse-work.bitrix24.ru/oauth/authorize/?response_type=code&client_id=local.56f18417641fd2.08455444&redirect_uri=http://surikolq.bget.ru/index.php?wcb24=1
defined('WCB24_CRM_PORT') or define('WCB24_CRM_PORT', '443'); // CRM server port
defined('WCB24_CRM_LEAD_PATH') or define('WCB24_CRM_LEAD_PATH', '/crm/configs/import/lead.php'); // CRM server REST service path

// CRM server authorization data
defined('WCB24_CRM_LOGIN') or define('WCB24_CRM_LOGIN', 'vladimr.surkov@ya.ru'); // login of a CRM user able to manage leads
defined('WCB24_CRM_PASSWORD') or define('WCB24_CRM_PASSWORD', 'wK1R9t'); // password of a CRM user

/**
 * client_id приложения
 */
define('WCB24_CLIENT_ID', 'local.56f18417641fd2.08455444');
/**
 * client_secret приложения
 */
define('WCB24_CLIENT_SECRET', '87df9bf518a341a9282122919761b8f6');
/**
 * относительный путь приложения на сервере
 */
define('WCB24_PATH', '/index.php?wcb24=1');
/**
 * полный адрес к приложения
 */
define('WCB24_REDIRECT_URI', 'http://surikolq.bget.ru'.WCB24_PATH);
/**
 * scope приложения
 */
define('WCB24_SCOPE', 'crm');

/**
 * протокол, по которому работаем. должен быть https
 */
define('WCB24_PROTOCOL', "https");

/**
 * Использовать REST API
 */
define('WCB24_USE_REST_AS_DEFAULT', true);
/**
 * Минимальное оставшееся время жизни токена перед отправкой запроса на обновление (сек)
 */
define('WCB24_TOKEN_TTL_MIN', 30);