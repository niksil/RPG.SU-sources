 <?php

if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	header('Location: index.php');
}
else
{
	echo '
	<form autocomplete="off" action="act.php" method="post" name="chatbox">
	<input type="hidden" name="option" value="chat">
	<img src="http://'.IMG_DOMAIN.'/nav/babble.gif">&nbsp;&nbsp;<input id="voice_id" type="text" name="voice" maxlength="100" size="30" class="input"> [<a href="act.php">обновить</a>]
	</form>
    ';
}

if (function_exists("save_debug")) save_debug(); 

?>