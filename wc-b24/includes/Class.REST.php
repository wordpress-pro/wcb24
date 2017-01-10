<?php
namespace WCB24;
/**
 * @file        Class.REST.php
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

class REST
{

	/**
	 * Производит перенаправление пользователя на заданный адрес
	 *
	 * @param string $url адрес
	 */
	public function redirect($url)
	{
		Header("HTTP 302 Found");
		Header("Location: ".$url);
		die();
	}

	/**
	 * Совершает запрос с заданными данными по заданному адресу. В ответ ожидается JSON
	 *
	 * @param string $method GET|POST
	 * @param string $url адрес
	 * @param array|null $data POST-данные
	 *
	 * @return array
	 */
	public function query($method, $url, $data = null)
	{
		$query_data = "";

		$curlOptions = array(
			CURLOPT_RETURNTRANSFER => true
		);

		if($method == "POST")
		{
			$curlOptions[CURLOPT_POST] = true;
			$curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
//			$curlOptions[CURLOPT_POSTFIELDS] = json_encode($data);

//			error_log('REST->query() data = '.print_r($curlOptions[CURLOPT_POSTFIELDS], true));
		}
		elseif(!empty($data))
		{
			$url .= strpos($url, "?") > 0 ? "&" : "?";
			$url .= http_build_query($data);

//			error_log("REST->query() url=[$url]");
		}

		$curl = curl_init($url);
		curl_setopt_array($curl, $curlOptions);
		$result = curl_exec($curl);

//		error_log("REST->query() result=[$result]");

		return json_decode($result, 1);
	}

	/**
	 * Вызов метода REST.
	 *
	 * @param string $method вызываемый метод
	 * @param array $params параметры вызова метода
	 *
	 * @return array
	 */
	public function call($method, array $params = array())
	{
		$tokens = $this->readTokensData();

		if(!isset($params['auth'])) {
			$params['auth'] = $tokens['access_token'];
		}

		return $this->query('POST', WCB24_PROTOCOL."://".$tokens['domain']."/rest/".$method, $params);
	}

	/**
	 * Первичная авторизация (на 23.03.2016 еще не работает)
	 */
	public function authenticate()
	{
		$domain = \get_option('wcb24_crm_host', WCB24_CRM_HOST);

		$params = array(
			"response_type" => "code",
			"client_id" => WCB24_CLIENT_ID,
			"redirect_uri" => WCB24_REDIRECT_URI,
		);
		$path = "/oauth/authorize/";

//		$this->redirect(WCB24_PROTOCOL."://".$domain.$path."?".http_build_query($params));
		$response = $this->query('GET', WCB24_PROTOCOL."://".$domain.$path, $params);

//		error_log('REST->authenticate(): response'.print_r($response, true));
	}

	/**
	 * Метод обновления данных токенов
	 *
	 * @param $tokens_data
	 */
	protected function updateTokensData($tokens_data)
	{
		update_option('wcb24_access_token', $tokens_data['access_token']);
		update_option('wcb24_access_token_ts', time());
		update_option('wcb24_expires_in', $tokens_data['expires_in']);
		update_option('wcb24_refresh_token', $tokens_data['refresh_token']);
		update_option('wcb24_refresh_token_ts', time());
		update_option('wcb24_domain', $tokens_data['domain']);
	}

	/**
	 * Чтение данных токенов
	 *
	 * @return array
	 */
	protected function readTokensData()
	{
		$tokens_data = array();

		$tokens_data['access_token'] = get_option('wcb24_access_token', false);
		$tokens_data['access_token_ts'] = get_option('wcb24_access_token_ts', 0);
		$tokens_data['expires_in'] = get_option('wcb24_expires_in', 0);
		$tokens_data['refresh_token'] = get_option('wcb24_refresh_token', false);
		$tokens_data['refresh_token_ts'] = get_option('wcb24_refresh_token_ts', 0);
		$tokens_data['domain'] = get_option('wcb24_domain', false);

		return $tokens_data;
	}

	/**
	 * Метод получения access_token
	 *
	 * @param $code
	 * @param $domain
	 */
	public function getAccessCode($code, $domain)
	{
		$params = array(
			"grant_type" => "authorization_code",
			"client_id" => get_option('wcb24_client_id', false),
			"client_secret" => get_option('wcb24_client_secret', false),
			"redirect_uri" => get_option('siteurl').WCB24_PATH,
			"scope" => WCB24_SCOPE,
			"code" => $code,
		);
		$path = "/oauth/token/";

		$query_data = $this->query("GET", WCB24_PROTOCOL."://".$domain.$path, $params);

		if(isset($query_data["access_token"])) {

//			error_log('REST->getAccessCode() access token gained : '.print_r($query_data, true));

			$this->updateTokensData($query_data);

		} else {

//			error_log('REST->getAccessCode() Произошла ошибка авторизации! '.print_r($query_data, true));

		}
	}

	/**
	 * Метод для обновления токенов
	 */
	public function refreshAccessToken()
	{
		$tokens_data = $this->readTokensData();

		$params = array(
			"grant_type" => "refresh_token",
			"client_id" => get_option('wcb24_client_id', false),
			"client_secret" => get_option('wcb24_client_secret', false),
			"redirect_uri" => get_option('siteurl').WCB24_PATH,
			"scope" => WCB24_SCOPE,
			"refresh_token" => $tokens_data["refresh_token"],
		);

		$path = "/oauth/token/";

		$query_data = $this->query("GET", WCB24_PROTOCOL."://".$tokens_data["domain"].$path, $params);

		if(isset($query_data["access_token"])) {

//			error_log('REST->getAccessCode() access token refreshed : '.print_r($query_data, true));

			$this->updateTokensData($query_data);

			return true;

		} else {

//			error_log('REST->getAccessCode() Произошла ошибка авторизации! '.print_r($query_data, true));

			return false;

		}
	}

	/**
	 * Метод проверки валидности токена по времени жизни
	 *
	 * @return bool
	 */
	public function checkAccessTokens()
	{
		$tokens = $this->readTokensData();

		$expires = $tokens['access_token_ts'] + $tokens['expires_in'] - time();

		if($expires < WCB24_TOKEN_TTL_MIN) {

			if(!$this->refreshAccessToken()) {
//				error_log('REST->checkAccessTokens() Не удалось обновить токен в течении времени жизни refresh_token.'
//					.' Необходимо получить новый токен вручную.');
				return false;
			}
		}

		return true;
	}
}