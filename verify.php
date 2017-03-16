<?php
$access_token = 'ZHAE6XhjkeKgKkR1C/Y3Hw3n4yPxS8ByGY11+u5IhI5Z8W4Tr+ytOwT5UD+B4x4CsDMa8r1jcrbZ12sSb1ptmwRwjwvff4i82FpwIAyzYXkcMoIZVsSOmp+0FROf5wd48Bz4Ztycfk5vYJosSKjI7AdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;