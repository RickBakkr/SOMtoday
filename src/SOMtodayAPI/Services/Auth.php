<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 16-1-19
 * Time: 18:33
 */

namespace SOMtodayAPI\SOMtodayAPI\Services;

use Curl\Curl;
use SOMtodayAPI\SOMtodayAPI\Exceptions\AuthException;
use SOMtodayAPI\SOMtodayAPI\Exceptions\RequestException;

class Auth {
	public static function login($uuid, $username, $password) {
		$curl = new Curl();
		$postFields = ['grant_type' => 'password', 'username' => $uuid . '\\' . $username, 'password' => $password, 'scope' => 'openid', 'client_id', 'D50E0C06-32D1-4B41-A137-A9A850C892C2'];
		$curl->setHeaders([
			'Accept' => 'application/json',
			'Content-Type' => 'application/x-www-form-urlencoded',
		]);
		$curl->setPost($postFields);
		$curl->createCurl('https://somtoday.nl/oauth2/token');

		if(isset($curl->response->error)) {
			throw new AuthException($curl->response->error_description);
		}

		if ($curl->error) {
			throw new AuthException('cURL Error: ' . $curl->getHttpStatus() . ': ' . $curl->errorMessage);
		}
		echo $curl->__tostring() . "\n";
		$response = json_decode($curl->__tostring());

		if (isset($response->error)){
			echo "{$response->error_description}";
		}

		$auth = new Auth();
		$keys = ['access_token', 'refresh_token', 'somtoday_api_url', 'scope', 'somtoday_tenant', 'id_token', 'token_type', 'expires_in'];
		foreach($keys as $key) {
			$auth->$key = '';
			if(isset($response->$key)) {
				$auth->$key = $response->$key;
			}
		}
		return $auth;
	}
}
