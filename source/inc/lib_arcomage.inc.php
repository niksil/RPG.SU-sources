<?php
function get_new_card($charboy, $hod)
{
	unset($cards1);
	unset($cards2);
	unset($cards3);
	$kol1=0;
	$kol2=0;
	$kol3=0;

	$arcomage_win = mysqlresult(myquery("SELECT arcomage_win FROM game_users WHERE user_id='".$charboy['user_id']."'"),0,0);
	/*
	if ($arcomage_win<15) $end = 15;
	elseif ($arcomage_win<35) $end = 18;
	elseif ($arcomage_win<75) $end = 21;
	elseif ($arcomage_win<145) $end = 24;
	elseif ($arcomage_win<265) $end = 27;
	else */ $end = 35;
	for ($i=1;$i<=$end;$i++)
	{
		$check = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users_cards WHERE card_id='$i' AND arcomage_id='".$charboy['arcomage_id']."'"),0,0) OR
				 mysql_result(myquery("SELECT COUNT(*) FROM arcomage_history WHERE card_id='$i' AND arcomage_id='".$charboy['arcomage_id']."' AND hod='$hod'"),0,0);
		if ($check==0)
		{
			$cards1[]=$i;
			$kol1++;
		}
	}
	/*
	if ($arcomage_win<15) $end = 64;
	elseif ($arcomage_win<35) $end = 67;
	elseif ($arcomage_win<75) $end = 70;
	elseif ($arcomage_win<145) $end = 73;
	elseif ($arcomage_win<265) $end = 76;
	else */ $end = 83;
	for ($i=50;$i<=$end;$i++)
	{
		$check = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users_cards WHERE card_id='$i' AND arcomage_id='".$charboy['arcomage_id']."'"),0,0) OR
				 mysql_result(myquery("SELECT COUNT(*) FROM arcomage_history WHERE card_id='$i' AND arcomage_id='".$charboy['arcomage_id']."' AND hod='$hod'"),0,0);
		if ($check==0 AND $i!=54)
		{
			$cards2[]=$i;
			$kol2++;
		}
	}
	/*
	if ($arcomage_win<15) $end = 114;
	elseif ($arcomage_win<35) $end = 117;
	elseif ($arcomage_win<75) $end = 120;
	elseif ($arcomage_win<145) $end = 123;
	elseif ($arcomage_win<265) $end = 126;
	else */ $end = 134;
	for ($i=100;$i<=$end;$i++)
	{
		$check = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users_cards WHERE card_id='$i' AND arcomage_id='".$charboy['arcomage_id']."'"),0,0) OR
				 mysql_result(myquery("SELECT COUNT(*) FROM arcomage_history WHERE card_id='$i' AND arcomage_id='".$charboy['arcomage_id']."' AND hod='$hod'"),0,0);
		if ($check==0 AND $i!=104)
		{
			$cards3[]=$i;
			$kol3++;
		}
	}
	$r = mt_rand(1,3);
	if ($r==1)
	{
		$t = mt_rand(0,$kol1-1);
		$new_card = $cards1[$t];
	}
	elseif ($r==2)
	{
		$t = mt_rand(0,$kol2-1);
		$new_card = $cards2[$t];
	}
	elseif ($r==3)
	{
		$t = mt_rand(0,$kol3-1);
		$new_card = $cards3[$t];
	}
	return $new_card;
}

function arcomage_user($char,$player,$money)
{
	global $user_id;
	$id = $player['user_id'];

	list($map_id) = mysql_fetch_array(myquery("SELECT map_name FROM game_users_map WHERE user_id=$id"));
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=$map_id"));
	if ($map['name']=='Арена Хаоса') return '';
	if ($map['dolina']==1) return '';
	
	list($map_id) = mysql_fetch_array(myquery("SELECT map_name FROM game_users_map WHERE user_id=$user_id"));
	$map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=$map_id"));
	if ($map['name']=='Арена Хаоса') return '';
	if ($map['dolina']==1) return '';
	
	if (played_arco($player['user_id'])!=0)
	{
		//нельзя нападать на игроков в две башни
		return 'Игрок играет в Две Башни'; 
	}
	
	$r = mt_rand(1,5);
	if ($r==1)
	{
		$tower_win = 50;
		$resource_win = 150;
		$tower = 10;
		$wall=5;
		$bricks=10;
		$gems=10;
		$monsters=10;
		$bricks_add=2;
		$gems_add=2;
		$monsters_add=2;
	}
	elseif ($r==2)
	{
		$tower_win = 100;
		$resource_win = 100;
		$tower = 20;
		$wall=15;
		$bricks=15;
		$gems=15;
		$monsters=15;
		$bricks_add=1;
		$gems_add=1;
		$monsters_add=1;
	}
	elseif ($r==3)
	{
		$tower_win = 150;
		$resource_win = 250;
		$tower = 30;
		$wall=20;
		$bricks=20;
		$gems=20;
		$monsters=20;
		$bricks_add=5;
		$gems_add=5;
		$monsters_add=5;
	}
	elseif ($r==4)
	{
		$tower_win = 100;
		$resource_win = 300;
		$tower = 20;
		$wall=10;
		$bricks=20;
		$gems=20;
		$monsters=20;
		$bricks_add=3;
		$gems_add=3;
		$monsters_add=3;
	}
	else
	{
		$tower_win = 200;
		$resource_win = 150;
		$tower = 20;
		$wall=20;
		$bricks=20;
		$gems=20;
		$monsters=20;
		$bricks_add=2;
		$gems_add=2;
		$monsters_add=2;
	}
	
	$nachalo = time();

	myquery("DELETE FROM arcomage_call WHERE user_id='$user_id'");
	myquery("DELETE FROM arcomage_call WHERE user_id='$id'");
	
	$ins = myquery("insert into arcomage (hod,tower_win,resource_win,money,timehod,user1,user2) values (1,'$tower_win','$resource_win','$money',$nachalo,$user_id,$id)");
	$uid = mysql_insert_id();

	myquery("DELETE FROM arcomage_users WHERE user_id='$user_id'");
	myquery("DELETE FROM arcomage_users_cards WHERE user_id='$user_id'");
	$ins = myquery("insert into arcomage_users (arcomage_id,user_id,tower,wall,bricks,gems,monsters,bricks_add,gems_add,monsters_add,func,hod) values ('$uid','$user_id','$tower','$wall','$bricks','$gems','$monsters','$bricks_add','$gems_add','$monsters_add','6',$nachalo)");
	$is = mysql_insert_id();
	$charboy = mysql_fetch_array(myquery("SELECT * FROM arcomage_users WHERE id='$is'"));
	for ($n=1;$n<=5;$n++)
	{
		$new_card = get_new_card($charboy, 0);
		$ins = myquery("insert into arcomage_users_cards (arcomage_id,user_id,card_id) values ('$uid','$user_id','$new_card')");
	}

	myquery("DELETE FROM arcomage_users WHERE user_id='$id'");
	myquery("DELETE FROM arcomage_users_cards WHERE user_id='$id'");
	$ins = myquery("insert into arcomage_users (arcomage_id,user_id,tower,wall,bricks,gems,monsters,bricks_add,gems_add,monsters_add,func,hod) values ('$uid','$id','$tower','$wall','$bricks','$gems','$monsters','$bricks_add','$gems_add','$monsters_add','7',$nachalo)");
	$is = mysql_insert_id();
	$charboy = mysql_fetch_array(myquery("SELECT * FROM arcomage_users WHERE id='$is'"));
	for ($n=1;$n<=5;$n++)
	{
		$new_card = get_new_card($charboy, 0);
		$ins = myquery("insert into arcomage_users_cards (arcomage_id,user_id,card_id) values ('$uid','$id','$new_card')");
	}
	set_delay_reason_id($user_id,10);
	set_delay_reason_id($id,10);
	
	// Пробный
	setLocation("arcomage.php");
	// Попробуем так забросить противника в бой. Тут есть потенциальные проблемы. 
	// Т.к. точно ли мы уверены, что игрок находится в тех режимах, из которых можно выйти в бой. А мы его форсируем.
	ForceFunc($id,4);


	return '';
}

function played_arco($id)
{
	$play = mysql_result(myquery("SELECT COUNT(*) FROM arcomage_users WHERE user_id=$id AND hod>=".(time()-160).""),0,0);
	return $play;
}
?>
