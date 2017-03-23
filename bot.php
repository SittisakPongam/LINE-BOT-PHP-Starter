<?php

require("webapi.php");
require("verify.php");


$line_access_token = 'ZHAE6XhjkeKgKkR1C/Y3Hw3n4yPxS8ByGY11+u5IhI5Z8W4Tr+ytOwT5UD+B4x4CsDMa8r1jcrbZ12sSb1ptmwRwjwvff4i82FpwIAyzYXkcMoIZVsSOmp+0FROf5wd48Bz4Ztycfk5vYJosSKjI7AdB04t89/1O/w1cDnyilFU=';

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

			// Build message to reply back

			$accessToken = get_oauth2_token();

            $result_webapi = getVehicleStatus($accessToken);
			
			 $messages = [
				'type' => 'text',
				'text' => $text.$result_webapi
			];

			
			

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
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
echo "OK";

$accessToken = get_oauth2_token();

$result_webapi = getVehicleStatus($accessToken);

echo $result_webapi;

?>