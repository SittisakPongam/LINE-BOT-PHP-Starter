<?php

//require("webapi.php");
//require("verify.php");

function gettoken($userId) {

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

    return  $accessToken;// 
}


function getWepAPI($link,$userId,$boxId)
{

            $access_token = gettoken('');

            $url ="";

            if($url == 'status' ) 
            {
                 $url = 'https://webapi.forthtrack.com/trackingresource/api/line/'.$userId.'/'.$boxId;		
            }
            	

            $curl = curl_init($url);
            $headers = array('Authorization: Bearer '.$access_token);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);   
            $result = curl_exec($curl);
            curl_close($curl);

			return $result;
}




//$line_access_token = 'ZHAE6XhjkeKgKkR1C/Y3Hw3n4yPxS8ByGY11+u5IhI5Z8W4Tr+ytOwT5UD+B4x4CsDMa8r1jcrbZ12sSb1ptmwRwjwvff4i82FpwIAyzYXkcMoIZVsSOmp+0FROf5wd48Bz4Ztycfk5vYJosSKjI7AdB04t89/1O/w1cDnyilFU=';

$line_access_token ='71iCqzubKdOiHIt9HYT6CjGpp7qfgNQh9aatLgO0C/FLf+kClgYtHuLcsvN6o8s30yNS5yGphl05b3LzCuawJZjgWHpQW/yXc/HHqse24CqvF7TGbQXeNj+FU2QaYv59Q7ihGKedxzFL3CFyZtbkUAdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];

			$userId = $event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];

            $userId ='l'.$userId;


            $myText = getWepAPI('status',$userId,$text);

			
			 $messages = [
				'type' => 'text',
				'text' => $myText
			];



			// $messages = [
			//  'type' => 'image',
   //           'originalContentUrl' => 'https://example.com/original.jpg',
   //            'previewImageUrl' => 'https://example.com/preview.jpg'
			// ];
		
								

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
                    //https://api.line.me/v2/bot/message/reply
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];

			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $line_access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}

echo "Hello LINE BOT";

?>