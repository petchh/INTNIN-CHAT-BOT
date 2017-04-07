<?php

function find_ans($text,$userId) {
    $ans_arr =array();
    $ans_data =array();
    $str = $userId.":".$text ;
    $strMSG = urlencode($str);
    $s_ans = file_get_contents('http://202.28.37.32/smartcsmju/Line_INTNINBOT/check_MSG.php?msg='.$strMSG);
    $msg_decode = json_decode($s_ans, true);
    $m_stat = $msg_decode['status'];
    //$msg = $msg_decode['msg'];
    if($m_stat == 1){
        $msg_ans = $s_ans;
    }else if($m_stat == 0){
	    	//$messages = [
		//	'type'=>'text',
		//	'text'=>$m_stat
		//];
	    	//array_push($ans_arr,$messages);
	    	$data= array("msg"=>$s_ans,"msg_type"=>""); 
    
        $msg_ans =json_encode($data);
	    //$msg_ans = $s_ans;
    }else if($m_stat == 3){
	    $data= array("msg"=>$m_stat,"msg_type"=>"insert");
	    $msg_ans =json_encode($data);
    }else{

	    	$messages = [
			'type'=>'text',
			'text'=>'Error'
		];        
	    
        $msg_ans =json_encode($messages);
    }
    
    //$msg_stat = $msg_decode['msg'];
    //$events = json_decode($msg_text, true);
        //foreach ($events['events'] as $event) {
            //$text = $event['message']['text'];
        //}           
    

    //if($msg_stat=='S0'){
    //    return '019';
    //}else if($msg_stat=='S1'){
    //    return $s_ans;
    //}else{
    //    return 'error';
    //}
    
    
    
    return $msg_ans; 
}

function save_log($replyToken,$userId) {
	
	$str = $replyToken."|".$userId ;
	$strMSG = urlencode($str);
	$s_ans = file_get_contents('http://202.28.37.32/smartcsmju/Line_INTNINBOT/save_log.php?msg='.$strMSG);

return $s_ans; 
}


?>
