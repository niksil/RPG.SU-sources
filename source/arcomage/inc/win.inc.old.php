<?

if (function_exists("start_debug")) start_debug(); 

list($money) = mysql_fetch_array(myquery("SELECT money FROM arcomage WHERE id='".$char['arcomage']."'"));
$ch = mysql_result(myquery("SELECT COUNT(*) FROM game_users WHERE arcomage='".$char['arcomage']."'"),0,0);
if ($ch<=1)
{
	myquery("DELETE FROM arcomage WHERE id='".$char['arcomage']."'");
	myquery("DELETE FROM arcomage_users WHERE arcomage_id='".$char['arcomage']."'");
	myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$char['arcomage']."'");
	myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$char['arcomage']."'");
    myquery("DELETE FROM arcomage_chat WHERE arcomage='".$char['arcomage']."'"); 
}
   
myquery("UPDATE game_users SET delay_reason='',arcomage=0,hod=0,arcomage_win=arcomage_win+1,GP=GP+'$money',CW=CW+'".($money*money_weight)."' WHERE user_id='$user_id'");
setGP($user_id,$money,24);

echo '<center>Ты выиграл эту игру<br>';
echo'<input type="button" value="Вернуться" onClick=top.location.replace("main.php")><br>';
echo'<img src="http://'.IMG_DOMAIN.'/combat/1.jpg">';

if (function_exists("save_debug")) save_debug(); 

?>