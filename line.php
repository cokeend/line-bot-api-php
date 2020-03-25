<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'b2BHdwwCHjprOiqDveJkJ/10Dv5cRjVOCERzG0dRWfOdISNxl6wiWmwJum50yCYlZxrBev1Heoj9ITTvCSqw3lJBXvx5kj19JDz1WuzuI9D/M1K8gksJgp71jbQlHKKd9ec/Kwh4fl2cIRTeAkv/2gdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "อยากทราบยอด COVID-19 ครับ")||($text == "ยอด")||($text == "อุณหภูมิ")){
			$temp = 27;
			$reply_message = '"รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 (COVID-19) ในประเทศไทย" \n ผู้ป่วยสะสม    จำนวน 398,995 ราย \n ผู้เสียชีวิต    จำนวน 17,365 ราย \n รักษาหาย    จำนวน 103,753 ราย \n ผู้รายงานข้อมูล: นายพิทวัส ทัศนัย';
		}
		else if(($text== "ตอนนี้อยู่ที่ไหน")||($text== "ตอนนี้อยู่ไหน")||($text== "อยู่ที่ไหน")||($text== "อยู่ไหน")){
			$reply_message = 'ขณะนี้อยู่ที่ห้องเรียน IF-5T05 ...!!!';
		}
	  	else if(($text== "coke")||($text== "โค้ก")||($text== "อยู่ที่ไหน")||($text== "อยู่ไหน")){
			$reply_message = 'หล่อ';
		}
		else
		{
			$reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
