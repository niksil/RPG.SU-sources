<?

if (function_exists("start_debug")) start_debug(); 

echo'<meta http-equiv="refresh" content="20">';

if ($char['arcomage']>0)
{
	if (isset($textg) and $textg!='')
	{
		$select=myquery("select * from game_chat_option where user_id='$user_id'");
		$chato=mysql_fetch_array($select);
		$text=htmlspecialchars($textg);
		$text=$char['name'].':><font color='.$chato['color'].'> '.$text.'</font>';
		$update=myquery("insert into arcomage_chat (arcomage,chat) values (".$char['arcomage'].",'$text')");
		echo '<script>top.window.frames.chat.document.form1.textg.value="";top.window.frames.chat.document.form1.textg.focus();</script>';
	}

	$sel = myquery("SELECT user_id FROM arcomage_users WHERE arcomage_id='".$char['arcomage']."' AND user_id<>'$user_id'");
	while (list($boy_user_id) = mysql_fetch_array($sel))
	{
	   list($name)=mysql_fetch_array(myquery("select name from game_users where user_id='".$boy_user_id."'"));
	   echo '<div align="left"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick=\'top.chat.document.form1.textg.value="'.$name.', "+top.chat.document.form1.textg.value;top.chat.document.form1.textg.focus();\'>'.$name.'</span><br>';
	}

	$select=myquery("select * from arcomage_chat where arcomage='".$char['arcomage']."' AND id>".$_SESSION['arcomage_chat_id']." ORDER BY id ASC");
	while ($combat=mysql_fetch_array($select))
	{
		$combat_chat = $combat['chat'].'<br>';
		?>
		<script language="JavaScript" type="text/javascript">
		//try
		//{
			chat = top.window.frames.chat.chat_f.document.getElementById("chat_boy");
			chat.innerHTML='<?=$combat_chat;?>'+chat.innerHTML;
		//}
		//catch(e)
		//{}
		</script>
		<?
		$_SESSION['arcomage_chat_id'] = $combat['id'];  
	}
}

if (function_exists("save_debug")) save_debug(); 

?>