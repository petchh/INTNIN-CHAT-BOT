<?php
$access_token = '7dICQM/2jmDmOPH439SGgwoXzID97m1PmAPBPpwhREx5MR83nbDwhus8Y1AwIPPQrmwV2zitrCbFVjVuGjkAxtRKi4zMgZpV1JOltqV0vmJuu/TRGfP34sgDjjGf1fef6Idcd9Fx2/ChvcZqV2qR1wdB04t89/1O/w1cDnyilFU=';
$msg="";
$m_type="";
$regs="";
$msg_check="";

include("Message.php");

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
			
			$msg_decode = json_decode($s_ans, true);
				foreach ($msg_decode['msg'] as $msg) {
         				$msg_type = $msg['msg_type'];
	       			}
			
			
			if((eregi ( "วิธีลงทะเบียน", $text, $regs ))or(eregi ( "การลงทะเบียน", $text, $regs ))){
				$messages = [
					'type'=>'text',
					'text' =>'ทำตามขั้นตอนตามนี้เลยครับ http://reg.mju.ac.th/enrollguide.htm'
				];
			}else if((eregi ( "Transcript", $text, $regs ))or(eregi ( "ทรานสคริป", $text, $regs ))){
				$messages = [
					'type'=>'text',
					'text' =>'เข้า www.education.mju.ac.th แล้วเลือก เข้าสุ่ระบบนักศึกษาครับ'
				];
			}else if((eregi ( "คศ101", $text, $regs ))or(eregi ( "แคลคูลัส 1", $text, $regs ))){
				$messages = [
					'type'=>'text',
					'text' =>'คศ101	แคลคูลัส 1
	Course Description:
	ลิมิตและความต่อเนื่อง อนุพันธ์ การประยุกต์ของอนุพันธ์ ดิฟเฟอเรนเชียลและอินทริกัลป์ไม่จำกัดเขต อินทริกัลป์จำกัดเขต และการประยุกต์ อนุพันธ์ย่อย
	Limit and continuity of functions, the derivative of algebraic functions and transcendental functions, the indefinite integrals and techniques of integration, and the definite integrals with applications
'
				];
			}
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
				//'messages' => [$msg],
			];
				
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
			
		}else if ($event['type'] == 'postback' ) {
			$text = $event['postback']['data'];
			// Get replyToken
			$replyToken = $event['replyToken'];	
			$str = explode("|",$text);
			$insertMSG = $str[1]."|".$str[2];
			$updataMSG = $str[1]."|".$str[2];
			if($str[0] == "update"){
				$strMSG = urlencode($updataMSG);
				$result = $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/update_frequency.php?msg='.$strMSG);
				$messages = [
					 "type"=> "sticker",
					 "packageId"=> "2",
					 "stickerId"=> "179"
				];
			}else if($str[0] == "insert"){
				$strMSG =  urlencode($insertMSG);
				$result = $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/insert_ans.php?msg='.$strMSG);
				$messages = [
					'type'=>'text',
					'text'=>'พิมพ์ '.$result.':คำตอบ'
				];
			}else{
				$messages = [
					'type'=>'text',
					'text'=>$text
				];
			}	
			
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
				
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
		
		}
	}
}

echo "OK";

?>
