<?php

	function get_oauth2_token() {

    $oauth2token_url = "https://webapi.forthtrack.com/authorizationserver11/token";
    $clienttoken_post = array(
    "username" => "user@forth",
    "password" => "forth#1234",
    "grant_type" => "password"
    );

    $curl = curl_init($oauth2token_url);

    $headers = array('Content-Type: application/json', 'Authorization: Basic Zm9ydGhUb29sOlptOXlkR2gwYjI5c01qQXhOdz09');

    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $clienttoken_post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $json_response = curl_exec($curl);

    echo $json_response;

    curl_close($curl);

    $authObj = json_decode($json_response);

    if (isset($authObj->refresh_token)){
        //refresh token only granted on first authorization for offline access
        //save to db for future use (db saving not included in example)
        global $refreshToken;
        $refreshToken = $authObj->refresh_token;
    }

    $accessToken = $authObj->access_token;
    return $accessToken;
}

echo get_oauth2_token();

?>