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

    setcookie("accessToken", $accessToken, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie("refreshToken", $refreshToken, time() + (86400 * 30), "/"); // 86400 = 1 day

    return $accessToken;
}


function getVehicleStatus($replyToken)
{
     // Make a POST Request to Messaging API to reply to sender
			$url = 'https://webapi.forthtrack.com/trackingresource/api/line/1111111';

			$access_token = $_COOKIE["accessToken"];

			$authorization = "Authorization: Bearer U7FewLN_sW0xRUM_w1aaQDx-A7f-knft4uvMAEj0HPBTDzdUwch3Ure0OITLwBbna2rCmEvk8lzhOYScnCAVGsPdfwgtdW8R3S8BQ9DKhX036gBCnBe_k3FBhUFF_ubt8j6WBDyh-9pwpDLiEANpQxgQ95HbvKqdOFI0Wxl2ap2JqLm2xrpOjF6LSQSdsRHDljWhm3owN_VIjHdj4sy_V1EGXEOKLAuSJIvmbS3wU1d2kHKxwVtF48OAwhpEG02ZrNkocA";

            $curl = curl_init($url);
            $headers = array('Authorization: Bearer ' . $access_token);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);    
            $result = curl_exec($curl);
            curl_close($curl);

            //$obj = json_decode($result);			

			echo $result . "\r\n";
}



if(!isset($_COOKIE["accessToken"])) {
    echo get_oauth2_token();
} 

if($_COOKIE["accessToken"] != "")
{
	getVehicleStatus(1111);
}


?>