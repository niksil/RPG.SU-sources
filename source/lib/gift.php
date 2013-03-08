<?
echo '<div style="margin-top:15px;margin-bottom:15px;width:100%;text-align:center;font-size:13px;color:gold;font-family:Georgia,Helvetica,Arial;">Открытки</div>';
echo '<center>';
echo'<SCRIPT language=javascript src="js/info.js"></SCRIPT>
<DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
if (isset($_POST['delcard']) AND isset($_POST['cardid']))
{
	$checkcard = mysql_result(myquery("SELECT COUNT(*) FROM game_gift WHERE user_to=$user_id AND id=".$_POST['cardid'].""),0,0);
	if ($checkcard==1) myquery("DELETE FROM game_gift WHERE user_to=$user_id AND id=".$_POST['cardid']."");
}
$sel_gift = myquery("SELECT * FROM game_gift WHERE user_to=$user_id");
if (mysql_num_rows($sel_gift))
{
	OpenTable('title', "80%");
	while ($card = mysql_fetch_array($sel_gift))
	{
		echo '<center>';
		list($user_from) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=".$card['user_from'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$card['user_from'].")"));
		?><span align="center"><a  onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?echo ' От '.$user_from.''?></b></font>','<font color=000000><?
		echo ''.replace_enter($card['gift_text']).'<br><br><hr><font size=1>Отправлена: '.date("d.m.Y H:i",$card['time_send']).'</font>'; 
		if ($card['private']!='') 
		{
			echo '<br><hr><font size=1>Личное сообщение: '.replace_enter($card['private']).'</font>';
		}
		?></font>',0,1,event)" onmouseout="showhint('','',0,0,event)"><img src="<?echo 'http://'.IMG_DOMAIN.'/gift/gallery/'.$card['gift_img'].''?>"></a><br><form action="" method="post"><input type="hidden" name="cardid" value="<?=$card['id'];?>"><input type="submit" name="delcard" value="Удалить эту открытку"></form></span>&nbsp;
		<?
		echo '</center>';
	}
	OpenTable('close');
}
else
{
	echo '<b>К сожалению, у Вас нет открыток!</b><br>';
}
echo '</center>';
?>