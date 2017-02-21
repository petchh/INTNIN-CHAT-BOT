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
			
			include("test_mqtt.php");
			echo "<script type='text/javascript'>pub();</script>";
			
			$s_ans = find_ans($text,$userId);
		
			//$strMSG = urlencode($text);
			//$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/LineAPI/check_MSG.php?msg='.$strMSG);
			$msg_decode = json_decode($s_ans, true);
				foreach ($msg_decode['msg'] as $msg) {
         				$msg_type = $msg['msg_type'];
	       			}
			
			$m_stat = $msg_decode['status'];
			$msg_type = $msg_decode['msg_type'];
			$id_userMSG = $msg_decode['id_userMSG'];
			
			if($msg_type=='Message'){
				$messages = [
						'type'=>'text',
						'text'=>$msg_decode['msg']
					];	
				//$messages = $msg_decode['msg'];		
			}else if($msg_type=='Template'){
				$msg_c = $msg_decode['msg'];
				$arrlength=count($msg_c);
				$msg_check=$msg_decode['msg_check']." ต้องตอบว่าไงดี ???";	
				
				switch($arrlength){
					case '1':
						$messages = [
							  "type"=>"template",
							  "altText"=>"this is a buttons template",
							  "template"=>[
							      "type"=>"buttons",
							      "text"=>$msg_check,
							      "actions"=>[
								  [
								    "type"=>"postback",
								    "label"=>$msg_c[0],
								    "data"=>"update|".$msg_c[0]."|".$id_userMSG
								  ],
								  [
								    "type"=>"postback",
								    "label"=>"อื่นๆ...",
								    "data"=>"insert|add|".$text
								  ]
							      ]
							  ]
						];
						break;
					case '2':
						$messages = [
							  "type"=> "template",
							  "altText"=> "this is a buttons template",
							  "template"=> [
							      "type"=> "buttons",
							      "text"=> $msg_check,
							      "actions"=> [
								  [
								    "type"=> "postback",
								    "label"=> $msg_c[0],
								    "data"=> "update|".$msg_c[0]."|".$id_userMSG
								  ],
								  [
								    "type"=> "postback",
								    "label"=> $msg_c[1],
								    "data"=> "update|".$msg_c[1]."|".$id_userMSG
								  ],
								  [
								    "type"=> "postback",
								    "label"=> "อื่นๆ...",
								    "data"=>"insert|add|".$text
								  ]
							      ]
							  ]
						];
						break;
					case '3':
						$messages = [
							  "type"=> "template",
							  "altText"=> "this is a buttons template",
							  "template"=> [
							      "type"=> "buttons",
							      "text"=> $msg_check,
							      "actions"=> [
								  [
								    "type"=> "postback",
								    "label"=>$msg_c[0],
								    "data"=>"update|".$msg_c[0]."|".$id_userMSG
								  ],
								  [
								    "type"=> "postback",
								    "label"=>$msg_c[1],
								    "data"=>"update|".$msg_c[1]."|".$id_userMSG
								  ],
								  [
								    "type"=> "postback",
								    "label"=>$msg_c[2],
								    "data"=>"update|".$msg_c[2]."|".$id_userMSG
								  ],
								  [
								    "type"=> "postback",
								    "label"=>"อื่นๆ...",
								    "data"=>"insert|add|".$text
								  ]
							      ]
							  ]
						];
						break;
					default:
						$messages = ['type'=>'text','text'=>$s_ans];
						
				}
			}else if($msg_type=='insert'){
				$messages = [
					 "type"=> "sticker",
					 "packageId"=> "2",
					 "stickerId"=> "179"
				];
			}else{
//				$messages = ['type'=>'text','text'=>$s_ans];
				$messages = [
					  "type"=> "template",
					  "altText"=> "this is a buttons template",
					  "template"=> [
					      "type"=> "buttons",
					      "text"=> $text." ต้องตอบว่าไงดี ???",
					      "actions"=> [
						  [
						    "type"=> "postback",
						    "label"=> "เพิ่มคำตอบ",
						    "data"=>"insert|new|".$text
						  ]
					      ]
					  ]
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
