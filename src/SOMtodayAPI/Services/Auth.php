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

class Auth {

    public static function login($uuid, $username, $password) {
        $curl = new Curl();
        $postFields = [
            'grant_type' => 'password',
            'username' => $uuid . '\\' . $username,
            'password' => $password,
            'scope' => 'openid'
        ];
        $curl->setHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic RDUwRTBDMDYtMzJEMS00QjQxLUExMzctQTlBODUwQzg5MkMyOnZEZFdkS3dQTmFQQ3loQ0RoYUNuTmV5ZHlMeFNHTkpY'
        ]);
        $curl->post('https://production.somtoday.nl/oauth2/token', $postFields);

        if(isset($curl->response->error)) {
            throw new AuthException($curl->response->error_description);
        }
        if ($curl->error) {
            throw new AuthException('cURL Error: ' . $curl->errorCode . ': ' . $curl->errorMessage);
        }

        $auth = new Auth();
        $keys = ['access_token', 'refresh_token', 'somtoday_api_url', 'scope', 'somtoday_tenant', 'id_token', 'token_type', 'expires_in'];
        foreach($keys as $key) {
            if(isset($curl->response->$key)) {
                $auth->$key = $curl->response->$key;
            }
        }
        return $auth;
    }
}