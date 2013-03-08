<?

if (function_exists("start_debug")) start_debug(); 

$ch = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."'"),0,0);
if ($ch<=1)
{
	myquery("DELETE FROM arcomage WHERE id='".$charboy['arcomage_id']."'");
	myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$charboy['arcomage_id']."'");
	myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$charboy['arcomage_id']."'");
}
myquery("DELETE FROM arcomage_users WHERE user_id=$user_id");
myquery("UPDATE game_users SET arcomage=0 WHERE user_id='$user_id'");
set_delay_reason_id($user_id,1);
echo '<center>Ничья!<br>';
echo'<input type="button" value="Вернуться" onClick=location.replace("act.php")><br>';
echo'<img src="http://'.IMG_DOMAIN.'/combat/n.jpg">';

if (function_exists("save_debug")) save_debug(); 

?>