<?

if (function_exists("start_debug")) start_debug(); 

if ($arcomage['money']!=0)
{
    $money = $arcomage['money'];
    $prot_id=$arcomage['user1'];
    if ($arcomage['user1']==$user_id) $prot_id=$arcomage['user2'];
    myquery("UPDATE game_users SET GP=GP+'$money',CW=CW+'".($money*money_weight)."' WHERE user_id='$user_id'");
    myquery("UPDATE game_users SET GP=GP-'$money',CW=CW-'".($money*money_weight)."' WHERE user_id='$prot_id'");
    myquery("UPDATE arcomage SET money=0 WHERE id=".$arcomage['id']."");
    setGP($prot_id,-$money,23);
    setGP($user_id,$money,24);
}
$ch = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."'"),0,0);
if ($ch<=1)
{
	myquery("DELETE FROM arcomage WHERE id='".$charboy['arcomage_id']."'");
	myquery("DELETE FROM arcomage_users_cards WHERE arcomage_id='".$charboy['arcomage_id']."'");
	myquery("DELETE FROM arcomage_history WHERE arcomage_id='".$charboy['arcomage_id']."'");
}
myquery("DELETE FROM arcomage_users WHERE user_id=$user_id");
   
myquery("UPDATE game_users SET arcomage=0,arcomage_win=arcomage_win+1 WHERE user_id='$user_id'");
set_delay_reason_id($user_id,1);
echo '<center>Ты '.echo_sex('выиграл','выиграла').' эту игру<br>';
echo'<input type="button" value="Вернуться" onClick=location.replace("act.php")><br>';
echo'<img src="http://'.IMG_DOMAIN.'/combat/1.jpg">';

if (function_exists("save_debug")) save_debug(); 

?>