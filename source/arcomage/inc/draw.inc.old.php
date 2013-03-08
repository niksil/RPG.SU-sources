<?

if (function_exists("start_debug")) start_debug(); 

$ch = mysql_result(myquery("SELECT COUNT(*) FROM game_users WHERE arcomage='".$char['arcomage']."'"),0,0);
if ($ch<=1)
{
	myquery("DELETE FROM arcomage WHERE id='".$char['arcomage']."'");
	myquery("DELETE FROM arcomage_users WHERE arcomage_id='".$char['arcomage']."'");
	myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$char['arcomage']."'");
	myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$char['arcomage']."'");
    myquery("DELETE FROM arcomage_chat WHERE arcomage='".$char['arcomage']."'"); 
}

myquery("UPDATE game_users SET delay_reason='',arcomage=0,hod=0 WHERE user_id='$user_id'");

echo '<center>Ничья!<br>';
echo'<input type="button" value="Вернуться" onClick=top.location.replace("main.php")><br>';
echo'<img src="http://'.IMG_DOMAIN.'/combat/n.jpg">';

if (function_exists("save_debug")) save_debug(); 

?>