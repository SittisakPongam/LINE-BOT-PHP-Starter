<?php

	function get_oauth2_token() {

    $oauth2token_url = "https://webapi.forthtrack.com/authorizationserver/token";

    $curl = curl_init($oauth2token_url);

    $data = 'username=user@forth&password=forth#1234&grant_type=password';
    $headers = array('Content-Type: application/x-www-form-urlencoded','Authorization: Basic Zm9ydGhUb29sOlptOXlkR2gwYjI5c01qQXhOdz09');   

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

    $json_response = curl_exec($curl);

    curl_close($curl);

    $authObj = json_decode($json_response);

    if (isset($authObj->refresh_token)){
        //refresh token only granted on first authorization for offline access
        //save to db for future use (db saving not included in example)
        global $refreshToken;
        $refreshToken = $authObj->refresh_token;
    }

    $accessToken = $authObj->access_token;

    setcookie("accessToken", $accessToken, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie("refreshToken", $refreshToken, time() + (86400 * 30), "/"); // 86400 = 1 day

    return $accessToken;
}


function getVehicleStatus($access_token,$userId)
{
     // Make a POST Request to Messaging API to reply to sender
			$url = 'https://webapi.forthtrack.com/trackingresource/api/line/.'$userId;			

            $curl = curl_init($url);
            $headers = array('Authorization: Bearer RDQmWHPItY0M6D-G5care1yTmsI2IW_jLAoK0VMLensRzcx1xtX1lGhKnw5dPFlnizg9EXCSl-WNLLGUS2qx0jSMIPr5Q1vy0W947NX4xiGiLssc_LyryNG4_9O67Dsc83gKeeQi4CMeJCAsA7qRJzxQNwjiNpU5sGX9NqrnKC6FFvbuk64F4uw3ZH755qY1YZJdzD9ZT-_Z6j8N7Fipc7z_gGjw-x4hzF2JoYTOqe_f1XI_0Wxt7M3ib5hUPIC5-nymLw');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);    
            $result = curl_exec($curl);
            curl_close($curl);

            //$obj = json_decode($result);			

			echo $result . "\r\n";
}

?>