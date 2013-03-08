<?php
//операции с хранилищем вещей
$free = $templ['min_value'] - mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=4 AND item_id NOT IN (SELECT id FROM game_items_factsheet WHERE type=13)"),0,0);
if (isset($_GET['sel_hran']))
{
	if ($free>0)
	{
		$selec=myquery("select game_items.id,game_items_factsheet.img,game_items_factsheet.name from game_items,game_items_factsheet where game_items.user_id='$user_id' and (game_items.ref_id='0' OR game_items_factsheet.type IN (12,14)) and game_items.used=0 and game_items_factsheet.type<90 and game_items.item_for_quest=0 and game_items.priznak=0 and game_items.item_id=game_items_factsheet.id and game_items_factsheet.type!=13");
		while ($row=mysql_fetch_array($selec))
		{
			echo'<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><tr><td width=70 align=center><a href=town.php?option='.$option.'&hran='.$row["id"].'&part4&dom><img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" border="0"></a></td><td><a href=town.php?option='.$option.'&hran='.$row["id"].'&part4&dom>'.$row["name"].'</a></td></tr></table>';
		}
		echo'Нажми на рисунок, чтобы положить предмет в хранилище';
	}
	else
	{
		echo '<br />В хранилище больше нет свободных мест!<br />';
	}
	
}
elseif (isset($_GET['get']))
{
	$Item = new Item($_GET['get']);                                                  
	if ($Item->getItem('user_id')==$user_id AND $Item->getItem('priznak')==4 AND ($Item->getFact('weight')<=($char['CC']-$char['CW'])))
	{
		$Item->move_item_to_user(0,$user_id);
		$free++;
	}
}
elseif (isset($_GET['hran']))
{
	if ($free>0)
	{
		$Item = new Item($_GET['hran']);
		if ($Item->getItem('user_id')==$user_id AND $Item->getFact('type')<90 AND $Item->getFact('type')!=13 AND $Item->getItem('item_for_quest')==0 AND ($Item->getItem('ref_id')==0 OR $Item->getFact('type')==12 OR $Item->getFact('type')==14) AND $Item->getItem('priznak')==0 AND $Item->getItem('used')==0)
		{
			$Item->move_item_to_market(0,0,0,4);
			$free--;
		}
	}
}

//хранилище
echo '<br /><br /><strong>Хранилище предметов (максимум '.$templ['min_value'].' предметов)</strong><br /><br />';
echo 'Сейчас свободно <b><font color=red>'.$free.'</font></b> мест для хранения предметов.<br /><br />';
echo '<a href="?option='.$option.'&town_id='.$town.'&part4&dom&sel_hran">Положить предметы в хранилище</a><br /><br />';
echo'<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';
QuoteTable('open','100%');
$sel = myquery("SELECT * FROM game_items WHERE priznak=4 AND user_id=$user_id AND item_id NOT IN (SELECT id FROM game_items_factsheet WHERE type=13)");
if ($sel!=false AND mysql_num_rows($sel)>0)
{
	while ($hran = mysql_fetch_array($sel))
	{
		echo'
		<table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344"><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>';
		echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width=100 align="center">';

		$Item = new Item($hran['id']);
		$Item->hint(0,0,'<a ');
		echo '<img src="http://'.IMG_DOMAIN.'/item/'.$Item->getFact('img').'.gif" border="0" alt=""></a>';

		echo'<br><font color="#ffff00">'.$Item->getFact('name').'</font>';
		echo'</td><td valign="top">
		<div align="left">';
		$Item->info(0,1,1,'100%');

		echo '</td><td style="text-align:right;padding-right:10px;width:130px;"><input type="button" value="Взять из хранилища" onClick=location.replace("town.php?option='.$option.'&town_id='.$town.'&get='.$hran['id'].'&part4&dom")>';
		echo'</td></tr></table></td></tr></table>';
	}
}
QuoteTable('close');

?>