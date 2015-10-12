<?php
/*
Open this file in your browser for instructions how to get your vk.com access token and access secret.
*/
?>

<!doctype html>
<html>
<head><meta charset='utf-8'></head>
<body>



<?php if($_GET["client_id"]!="" && $_GET["client_secret"]!=""){ ?>

Five steps for getting vk.com access token:<br />
<ol>
	<li><a href='http://api.vkontakte.ru/oauth/authorize?client_id=<?=$_GET["client_id"]?>&scope=offline,wall,groups,pages,photos,docs,audio,video,notes,stats,messages,notify,notifications,nohttps&amp;redirect_uri=http://oauth.vk.com/blank.html&amp;response_type=code' target='_blank'>Click this link</a>
	
	<li>Grant access for your app
	<li>You will be redirected to page <b>https://oauth.vk.com/blank.html#code=CODE</b>
	<li>Copy your CODE and paste it into this link:<br />
	<b><textarea cols='100' rows='3'>https://api.vkontakte.ru/oauth/access_token?client_id=<?=$_GET["client_id"]?>&amp;client_secret=<?=$_GET["client_secret"]?>&amp;redirect_uri=http://oauth.vk.com/blank.html&amp;code=CODE</textarea></b>
	<li>Great job! Now fill in your access token and access secret at <b>config.php</b>
</ol>

<?php }else{ ?>

Welcome to a wizard for getting your access token.
<ol>
	<li>Create a new Vk standalone app <a href="https://vk.com/editapp?act=create">here</a>
	<li>Specify you app's client ID and client secret
</ol>
<form method="get" action="getstarted.php">
	<input type="text" name="client_id" placeholder="Client ID" /><br />
	<input type="text" name="client_secret" placeholder="Client secret" /><br />
	<input type="submit" /><br />
</form>

<?php } ?>

</body></html>