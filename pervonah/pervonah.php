<?php
chdir(dirname(__FILE__));
header('Content-Type: text/html; charset=utf-8');
include("tools.php");
include("antigate.php");
include("config.php");



// Call vk.com method
function callMethod($method, $parameters){
	global $vkAccessToken,$vkAccessSecret;
	if (!$vkAccessToken) return false;
	if (is_array($parameters)) $parameters = http_build_query($parameters);
	$queryString = "/method/$method?$parameters&access_token={$vkAccessToken}";
	$querySig = md5($queryString . $vkAccessSecret);
	return json_decode(file_get_contents(
		"http://api.vk.com{$queryString}&sig=$querySig"
	));
}

// Main function
function pervonah($groupId,$photo){
	// Get 20 latest group posts. You can change count and offset.
	$answer = callMethod('wall.get', array(
		'owner_id' => -1 * $groupId,
		'domain' => "",
		'offset' => "0",
		'count' => "20",
		'filter' => "owner",
		'extended' => "",
	));
	da($answer); //for debugging purposes
	
	// Get the ID of the post you've last commented on to prevent duplicate comments.
	$n=fir("post-ids/".$groupId.".txt");
	$i=$n;
	
	// Cycle through 20 group posts. If you've changed the 'count' parameter change the number here as well.
	for($i=1;$i<=20;$i++){
		if($answer->response[$i]->id > $n){
			// Publish a comment
			$result = callMethod('wall.addComment', array(
				'owner_id' => -1 * $groupId,
				'post_id' => $answer->response[$i]->id,
				'from_group' => $fromGroup ? 1 : 0,
				'text' => "",
				'reply_to_comment' => "",
				'attachment' => $photo,
			));
			print_r($result); //for debugging purposes
			
			// If we've got captcha recognize it and try commenting again.
			if($result->error->captcha_img!=""){
				$resultC = callMethod('wall.addComment', array(
					'owner_id' => -1 * $groupId,
					'post_id' => $answer->response[$i]->id,
					'from_group' => $fromGroup ? 1 : 0,
					'text' => "",
					'reply_to_comment' => "",
					'attachment' => $photo,
					'captcha_key' => recognize($result->error->captcha_img,$antigateApikey),
					'captcha_sid' => $result->error->captcha_sid,
				));
				print_r($resultC); //for debugging purposes
			}
		
		}
	}
	
	// Write the ID of the last post you've commented on to file
	if($answer->response[1]->id!="") fiw("post-ids/".$groupId.".txt",$answer->response[1]->id);
}
?>