<?php

class SOMtoday {

  public function __construct($username, $password, $guid) {
    $this->username = $username;
    $this->password = $password;
    $this->guid = $guid;
    $this->auth();
  }

  private function auth() {
    $res = $this->curlLogin([
      'username'    => $this->guid . '\\' . $this->username,
      'password'    => $this->password,
      'scope'       => 'openid',
      'grant_type'  => 'password'
    ]);

    $this->access_token     = $res['access_token'];
    $this->refresh_token    = $res['refresh_token'];
    $this->base_url         = $res['somtoday_api_url'];
    $this->scope            = $res['scope'];
    $this->somtoday_tenant  = $res['somtoday_tenant'];
    $this->id_token         = $res['id_token'];
    $this->token_type       = $res['token_type'];
    $this->expires_in       = $res['expires_in'];

  }

  private function curlLogin($postFields) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://productie.somtoday.nl/oauth2/token?" . http_build_query($postFields),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: Basic RDUwRTBDMDYtMzJEMS00QjQxLUExMzctQTlBODUwQzg5MkMyOnZEZFdkS3dQTmFQQ3loQ0RoYUNuTmV5ZHlMeFNHTkpY",
        "content-type: application/x-www-form-urlencoded"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return ['error' => $err, 'status' => 'error'];
    } else {
      $result = json_decode($response, true);
      $result['status'] = 'success';
      return $result;
    }
  }

}
