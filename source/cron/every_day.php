<?php
require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");
include("".PATCH."/inc/lib_admin.inc.php");

mysql_query("SET wait_timeout=1800");

mysql_query("DELETE FROM game_cron_log WHERE cron='every_day' AND step='final'");
mysql_query("INSERT INTO game_cron_log (cron,step,timecron) VALUES ('every_day','������',".time().")");
$idcronlog = mysql_insert_id();
 
mysql_query("UPDATE game_cron_log SET step='1��������� ������� � game_activity', timecron=".time()." WHERE id=$idcronlog");

$sellastid = mysql_query("SELECT id FROM game_activity_mult ORDER BY id DESC LIMIT 1");
if ($sellastid!=false AND mysql_num_rows($sellastid)>0)
{
	list($last_id) = mysql_fetch_array($sellastid);
}
else
{
	$last_id = 0;
}
$sel = mysql_query("SELECT DISTINCT host,host_more FROM game_activity");
while ($ac = mysql_fetch_array($sel))
{
	$sel_name = mysql_query("SELECT DISTINCT name FROM game_activity WHERE name<>'ban' AND host='".$ac['host']."' AND host_more='".$ac['host_more']."'");
	if (mysql_num_rows($sel_name)>1)
	{
		while (list($name) = mysql_fetch_array($sel_name))
		{
			mysql_query("INSERT INTO game_activity_mult SELECT * FROM game_activity WHERE name='".$name."' AND host='".$ac['host']."' AND host_more='".$ac['host_more']."' AND id>$last_id");
		}
	}
}

mysql_query("UPDATE game_cron_log SET step='2��������� ������ � ����������', timecron=".time()." WHERE id=$idcronlog");
//�������� ���� "������������" �� ������ ������� � �� ����������� ��������� (�� ����������� ���, ��� ����������� � ��������)
mysql_query("UPDATE game_users SET STR=STR_MAX WHERE STR<>STR_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users SET NTL=NTL_MAX WHERE NTL<>NTL_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users SET PIE=PIE_MAX WHERE PIE<>PIE_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users SET VIT=VIT_MAX WHERE VIT<>VIT_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users SET DEX=DEX_MAX WHERE DEX<>DEX_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users SET SPD=SPD_MAX WHERE SPD<>SPD_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");

mysql_query("UPDATE game_users_archive SET STR=STR_MAX WHERE STR<>STR_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users_archive SET NTL=NTL_MAX WHERE NTL<>NTL_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users_archive SET PIE=PIE_MAX WHERE PIE<>PIE_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users_archive SET VIT=VIT_MAX WHERE VIT<>VIT_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users_archive SET DEX=DEX_MAX WHERE DEX<>DEX_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");
mysql_query("UPDATE game_users_archive SET SPD=SPD_MAX WHERE SPD<>SPD_MAX AND user_id NOT IN (SELECT user_id FROM game_obelisk_users)");

mysql_query("UPDATE game_cron_log SET step='3�������� ������� � �����', timecron=".time()." WHERE id=$idcronlog");

//���������� ������� �� ������������ � ���� 3 ��� � �����
$cur_time=time()-60*60*24*3;
echo '�������� ������� � �����<br>';
$sel = mysql_query("INSERT INTO game_users_archive SELECT game_users.* FROM game_users,game_users_active WHERE game_users_active.user_id=game_users.user_id AND game_users_active.last_active<'".$cur_time."'");
$del = mysql_query("DELETE FROM game_users WHERE user_id IN ( SELECT user_id FROM game_users_active WHERE last_active<'".$cur_time."' )");
/*
$sel = mysql_query("SELECT  * FROM game_users WHERE game_users.delay<'".$cur_time."'");
while ($user = mysql_fetch_array($sel))
{
	$ins = mysql_query("INSERT INTO game_users_archive SELECT * FROM game_users WHERE game_users.user_id='".$user['user_id']."'");
	if (mysql_insert_id())
	{
		$del = mysql_query("DELETE FROM game_users WHERE game_users.user_id='".$user['user_id']."'");
	}
}
*/

mysql_query("UPDATE game_cron_log SET step='4������� ������� ������� ����������', timecron=".time()." WHERE id=$idcronlog");

//������� ���������� (����� 30 ���� �����)
$cur_time=time()-60*60*24*90;
echo '������� ����������<br>';
$del = mysql_query("DELETE FROM game_activity WHERE time<'".$cur_time."'");

mysql_query("UPDATE game_cron_log SET step='5������� ��������� �����', timecron=".time()." WHERE id=$idcronlog");

$cur_time=time()-60*60*24*30;
echo '������� ��������� �����<br>';
$del = mysql_query("DELETE FROM game_pm_deleted WHERE time<'".$cur_time."'");

mysql_query("UPDATE game_cron_log SET step='6������� ������������ �����������', timecron=".time()." WHERE id=$idcronlog");

//������� ������������ ����������� ������� (����� 48 �����)
echo '������� ������������ ����������� �������<br>';
$cur_time=time()-60*60*24*2;
$del = mysql_query("DELETE FROM game_users_reg WHERE rego_time<'".$cur_time."'");

myquery("UPDATE game_cron_log SET step='7������� ����� ����', timecron=".time()." WHERE id=$idcronlog");

//������� ����� ���� (����� 30 ���� �����)
echo '������� ����� ����<br>';
$cur_time=time()-60*60*24*30;
myquery("DELETE FROM game_combats_log_data WHERE boy IN (SELECT DISTINCT boy FROM game_combats_log WHERE time<$cur_time)");
myquery("DELETE FROM game_combats_users WHERE boy IN (SELECT DISTINCT boy FROM game_combats_log WHERE time<$cur_time)");
myquery("DELETE FROM game_combats_log WHERE time<$cur_time");
//$del = myquery("DELETE FROM game_combats_log WHERE time<'".$cur_time."'");

myquery("UPDATE game_cron_log SET step='8��������� ��� � ���������', timecron=".time()." WHERE id=$idcronlog");
//��������� ��� � ���������
$sel = myquery("SELECT * FROM game_shop");
while ($shop = mysql_fetch_array($sel))
{
	$cena_pok = mt_rand($shop['cena_pok_min'],$shop['cena_pok_max']);
	$cena_prod = mt_rand($shop['cena_prod_min'],$shop['cena_prod_max']);
	myquery("UPDATE game_shop SET cena_pok=".$cena_pok.", cena_prod=".$cena_prod." WHERE id=".$shop['id']."");
}

echo'������ �� ������<br>';

myquery("UPDATE game_cron_log SET step='9������� ������� � �����', timecron=".time()." WHERE id=$idcronlog");

//������ ������������ ���������� ������ � ������ � ������� �������
$time_for_check = time()- 20*24*60*60;
$pg=myquery("SELECT * FROM game_items where priznak=1 and sell_time<'$time_for_check' and kleymo=0 ORDER BY sell_time DESC");
while($item = mysql_fetch_array($pg))
{
	$town = $item['town'];
	$is_clan_town = @mysql_result(@myquery("SELECT COUNT(*) FROM game_clans WHERE town='$town'"),0,0);
	if($is_clan_town==0)
	{
		//������� �� ��������. ������ ���������� ��� ���������
		$userid =  $item['user_id'];
		$it = $item['id'];
		$item_id = $item['item_id'];
		list($type,$ident,$weight) = mysql_fetch_array(myquery("SELECT type,name,weight FROM game_items_factsheet WHERE id=".$item['item_id'].""));
		if ($type=='12' or $type=='13' or $type=='19' or $type=='21' or $type=='22' or $type=='97')
		{
			$check=myquery("Select count_item from game_items where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
			if (mysql_num_rows($check)>0)
			{
				myquery("UPDATE game_items SET count_item=count_item+".$item['count_item']." where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
				myquery("Delete From game_items WHERE id=".$item['id']."");
			}
			else
			{
				myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
			}
		}
		else
		{
			myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
		}
		myquery("update game_users set CW=CW+'$weight' where user_id='$userid'");
		myquery("update game_users_archive set CW=CW+'$weight' where user_id='$userid'");
		$town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
		list($rustown)=mysql_fetch_array($town_select);
		
		$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', '�������������', '�����', '���� ������ ����������. ���� ������� ".$ident." ���� � ������� �� ����� � ".$rustown.", ��� ��� �� ���� ������� ��� � �� ������� ����������! �� ��������� � ���� ���������','0','".time()."')");
		echo '������� '.$ident.' ����� �� ������. �������� '.$userid.'<br>';
	}
}

//������ ������������ �������� ������ � ������ � ������� �������
$time_for_check = time()- 1*24*60*60;
$pg=myquery("SELECT * FROM game_items where priznak=1 and sell_time<'$time_for_check' and kleymo>0 ORDER BY sell_time DESC");
while($item = mysql_fetch_array($pg))
{
	$town = $item['town'];
	$is_clan_town = @mysql_result(@myquery("SELECT COUNT(*) FROM game_clans WHERE town='$town'"),0,0);
	if($is_clan_town==0)
	{
		//������� �� ��������. ������ ���������� ��� ���������
		$userid =  $item['user_id'];
		$it = $item['id'];
		$item_id = $item['item_id'];
		list($type,$ident,$weight) = mysql_fetch_array(myquery("SELECT type,name,weight FROM game_items_factsheet WHERE id=".$item['item_id'].""));
		if ($type=='12' or $type=='13' or $type=='19' or $type=='21' or $type=='22' or $type=='97')
		{
			$check=myquery("Select count_item from game_items where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
			if (mysql_num_rows($check)>0)
			{
				myquery("UPDATE game_items SET count_item=count_item+".$item['count_item']." where priznak=0 and ref_id=0 and user_id=$userid and item_id=$item_id");
				myquery("Delete From game_items WHERE id=".$item['id']."");
			}
			else
			{
				myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
			}
		}
		else
		{
			myquery("UPDATE game_items SET priznak=0,sell_time=0,ref_id=0,item_cost=0,post_to=0,post_var=0,used=0 WHERE id=".$item['id']."");
		}
		myquery("update game_users set CW=CW+'$weight' where user_id='$userid'");
		myquery("update game_users_archive set CW=CW+'$weight' where user_id='$userid'");
		$town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
		list($rustown)=mysql_fetch_array($town_select);
		
		$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', '�������������', '�����', '���� ������ ����������. ���� ������� ".$ident." ���� � ������� �� ����� � ".$rustown.", ��� ��� �� ���� ������� ��� � �� ������� ����������! �� ��������� � ���� ���������','0','".time()."')");
		echo '������� '.$ident.' ����� �� ������. �������� '.$userid.'<br>';
	}
}

myquery("UPDATE game_cron_log SET step='10������� �������� � �����', timecron=".time()." WHERE id=$idcronlog");

//������ ������������ ������� � ������ � ������� �������
$time_for_check = time()- 20*24*60*60;
$pg=myquery("SELECT * FROM craft_resource_market where priznak=0 and sell_time<'$time_for_check' ORDER BY sell_time DESC");
while($item = mysql_fetch_array($pg))
{
    $town = $item['town'];
    $is_clan_town = @mysql_result(@myquery("SELECT COUNT(*) FROM game_clans WHERE town='$town'"),0,0);
    if($is_clan_town==0)
    {
        //������ �� ��������. ������ ���������� ��� ���������
        $userid =  $item['user_id'];
        $it = $item['res_id'];

        list($ident,$weight) = mysql_fetch_array(myquery("SELECT name,weight FROM craft_resource WHERE id=".$item['res_id'].""));

        $weight = $weight*$item['col'];
        myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ('".$item['user_id']."','".$item['res_id']."','".$item['col']."') ON DUPLICATE KEY UPDATE col=col+'".$item['col']."'");
        myquery("update game_users set CW=CW+'$weight' where user_id='$userid'");
        myquery("update game_users_archive set CW=CW+'$weight' where user_id='$userid'");
        myquery("delete from craft_resource_market where id=".$item['id']."");
        $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
        list($rustown)=mysql_fetch_array($town_select);
        
        $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$userid', '�������������', '�����', '���� ������ ����������. ���� ������ ".$ident." ���� � ������� �� ����� � ".$rustown.", ��� ��� �� ���� ������ ��� � �� ������� ����������! �� ��������� � ���� ���������','0','".time()."')");
        echo '������ '.$ident.' ����� �� ������. �������� '.$userid.'<br>';
    }
}

myquery("UPDATE game_cron_log SET step='11������ �����������', timecron=".time()." WHERE id=$idcronlog");

echo'������ �� ��������<br>';
//�������� ������ ���������� �������
$sel = myquery("SELECT * FROM game_tavern");
while ($tav = mysql_fetch_array($sel))
{
	$town = $tav['town'];
	$map = mysql_fetch_array(myquery("SELECT * FROM game_map WHERE town='$town' AND to_map_name='' LIMIT 1"));
	if ($map['name']!=0)
	{
		$map_name = mysql_result(myquery("SELECT name FROM game_maps WHERE id='".$map['name']."'"),0,0);
		$rustown = mysql_result(myquery("SELECT rustown FROM game_gorod WHERE town='$town'"),0,0);
		$tavern = '������ '.$rustown.' ('.$map_name.' '.$map['xpos'].', '.$map['ypos'].')';
		$result=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$tav['vladel']."', '0', '��������� ������� � ����� �������', '�� ������� ������ � ���� �� ������� ����� ������� � ".$tavern." ��������� ������: HP = ".$tav['hp_store']." ������, MP = ".$tav['mp_store']." ������, STM = ".$tav['stm_store']." ������. ������� ����� ������� - ".$tav['dohod']." �����', '0','".time()."')");
	}
}

myquery("UPDATE game_cron_log SET step='12���������� ������������� �� ������', timecron=".time()." WHERE id=$idcronlog");

//���������� � �������� ������������� �� ������
$da = getdate();
$lastday = 28;
if (checkdate($da['mon'],31,$da['year'])) $lastday = 31;
elseif (checkdate($da['mon'],30,$da['year'])) $lastday = 30;
elseif (checkdate($da['mon'],29,$da['year'])) $lastday = 29;
if ($da['mday']==$lastday)
{
	//���������� �������������
	$sel = myquery("SELECT * FROM game_clans WHERE raz=0");
	while ($clan = mysql_fetch_array($sel))
	{
		$kol = 0;
		$summa = 0;
		$seluser = myquery("(SELECT clevel FROM game_users WHERE clan_id=".$clan['clan_id'].") UNION (SELECT clevel FROM game_users_archive WHERE clan_id=".$clan['clan_id'].")");
		while (list($level)=mysql_fetch_array($seluser))
		{
			$kol++;
			if ($level<10) $summa+=0;
			elseif ($level<20) $summa+=70;
			elseif ($level<30) $summa+=130;
			elseif ($level<40) $summa+=240;
			else $summa+=350;
		}
		if ($kol<=10) $summa=round($summa/2,2);
		elseif ($kol<=20) $summa=round($summa*0.75,2);
		//���������� ������, ���� � ����� ���� �����
		$test_gorod=myquery("SELECT * FROM game_gorod WHERE clan=".$clan['clan_id']."");
		if (mysql_num_rows($test_gorod)>0) $summa=round($summa*(1+0.5*mysql_num_rows($test_gorod)),2);
			
		myquery("INSERT INTO game_clans_taxes (clan_id,month,year,summa) VALUE (".$clan['clan_id'].",".$da['mon'].",".$da['year'].",$summa)");
	}
}
if ($da['mday']==10)
{
	//�������� ������ �������������
	$sel = myquery("SELECT * FROM game_clans WHERE raz=0");
	while ($clan = mysql_fetch_array($sel))
	{
		$rat = 0;
		$add = 0;
		$selcheck = myquery("SELECT * FROM game_clans_taxes WHERE clan_id=".$clan['clan_id']." AND flag=0");
		while ($tax = mysql_fetch_array($selcheck))
		{
			$rat+=5+$add;
			$add+=2;    
		}    
		if ($rat>0)
		{
			myquery("UPDATE game_clans SET raring=raring-$rat WHERE clan_id=".$clan['clan_id']."");
		}
	}
}

echo'���<br>';

myquery("UPDATE game_cron_log SET step='13������� ������� ��������� ������ � ����', timecron=".time()." WHERE id=$idcronlog");

//������� ������� ��������� �������
myquery("TRUNCATE TABLE game_login");

function delete_house($user_id,$build_id=0)
{
    //���� $build_id=0 - ������� ��� ��������� ��-�� ������� � ������� ���� �����
    //����� ������� ���������� $build_id ������ �� houses_users ��-�� �������
    //��� �������� ���� - ��-�� ������� - ��������� ������ �� �������. 
	//���������� �������� �� �������� � ��� ��������� (����� ���������)
    if ($build_id==0 OR ($build_id>=1 AND $build_id<=4))//������� ���
    {
	    $selitems = mysql_query("SELECT SUM(game_items_factsheet.weight) AS weight FROM game_items,game_items_factsheet WHERE game_items.priznak=4 AND game_items.item_id=game_items_factsheet.id AND game_items.user_id=$user_id AND game_items_factsheet.type<>13 GROUP BY game_items.user_id");
        mysql_query("UPDATE game_items SET priznak=0 WHERE priznak=4 AND user_id=$user_id");
        $weight = 0;
        if (mysql_num_rows($selitems)) 
        {
            $weight = mysql_result($selitems,0,0);
	        mysql_query("UPDATE game_users SET CW=CW+$weight WHERE user_id=$user_id");
	        mysql_query("UPDATE game_users_archive SET CW=CW+$weight WHERE user_id=$user_id"); 
        } 
    }
    //���������� �������� �� �������� � ��� ���������
    if ($build_id==0 OR ($build_id>=13 AND $build_id<=16))//������� ��������� ���������
    {
        $selitems = mysql_query("SELECT SUM(game_items_factsheet.weight) AS weight FROM game_items,game_items_factsheet WHERE game_items.priznak=4 AND game_items.item_id=game_items_factsheet.id AND game_items.user_id=$user_id AND game_items_factsheet.type=13 GROUP BY game_items.user_id");
        mysql_query("UPDATE game_items SET priznak=0 WHERE priznak=4 AND user_id=$user_id");
        $weight = 0;
        if (mysql_num_rows($selitems)) 
        {
            $weight = mysql_result($selitems,0,0);
            mysql_query("UPDATE game_users SET CW=CW+$weight WHERE user_id=$user_id");
            mysql_query("UPDATE game_users_archive SET CW=CW+$weight WHERE user_id=$user_id"); 
        } 
    }
	//���������� ������� �� ���������
    if ($build_id==0 OR ($build_id>=9 AND $build_id<=12))//������� ��������� ��������
    {
	    $hransel = mysql_query("SELECT craft_resource_market.col,craft_resource.img3 AS img,craft_resource.name,craft_resource.weight,craft_resource.id AS res_id FROM craft_resource_market,craft_resource WHERE craft_resource_market.user_id=$user_id AND craft_resource_market.res_id=craft_resource.id AND craft_resource_market.priznak=1");
	    $weight=0;
	    if ($hransel!=false AND mysql_num_rows($hransel)>0)
	    {
		    while ($hran = mysql_fetch_array($hransel))
            {
		        $col_res = $hran['col'];
		        $weight+=$col_res*$hran['weight'];
		        myquery("INSERT INTO craft_resource_user (user_id,col,res_id) VALUES ($user_id,$col_res,".$hran['res_id'].") ON DUPLICATE KEY UPDATE col=col+$col_res");
            }
	    }
        if ($weight>0)
        {
	        myquery("UPDATE game_users SET CW=CW+$weight WHERE user_id=$user_id");
	        myquery("UPDATE game_users_archive SET CW=CW+$weight WHERE user_id=$user_id");
        }
	    myquery("DELETE FROM craft_resource_market WHERE user_id=$user_id AND priznak=1");
    }
	//������� � ������� ����
    if ($build_id==0 OR ($build_id>=6 AND $build_id<=8))//������� ������
    {
	    $selhorse = myquery("SELECT game_users_horses.id,game_users_horses.used,game_vsadnik.ves FROM game_users_horses,game_vsadnik WHERE game_users_horses.user_id=$user_id AND game_users_horses.horse_id=game_vsadnik.id AND game_vsadnik.vsad>=5");
	    while ($h = mysql_fetch_array($selhorse))
	    {
		    if ($h['used']==1)
		    {
			    $weight = $h['ves'];
			    myquery("UPDATE game_users SET CC=CC-$weight WHERE user_id=$user_id");
			    myquery("UPDATE game_users_archive SET CC=CC-$weight WHERE user_id=$user_id");
		    }    
		    myquery("DELETE FROM game_users_horses WHERE id=".$h['id']."");
	    }
    }
	//������� ����/��������� ������ �� �������������
    $arr=array(5,17,18,19,21,22);
	if ($build_id==0)
    {
        //������� ��� ���������+���
        myquery("DELETE FROM houses_market WHERE user_id=$user_id");
	    myquery("DELETE FROM houses_nalog WHERE user_id=$user_id");
	    myquery("DELETE FROM houses_users WHERE user_id=$user_id");
    }
    elseif (in_array($build_id,$arr))
    {
        //������� ���������� ������/��������� 
        myquery("DELETE FROM houses_market WHERE user_id=$user_id AND build_id=$build_id");
        myquery("DELETE FROM houses_users WHERE user_id=$user_id AND build_id=$build_id");
    }
}

//mysql_query("UPDATE game_cron_log SET step='14���� �� ��������� ����� 1 ������ �� ���������', timecron=".time()." WHERE id=$idcronlog");
//mysql_query("UPDATE game_cron_log SET step='17�������� ���� �� ��������� ����� 1 ������', timecron=".time()." WHERE id=$idcronlog");
//�������� ���� �� ��������� ����� 1 ������
//mysql_query("UPDATE houses_nalog SET nalog=nalog+(nalog-pay)/30.4 WHERE nalog_time<(UNIX_TIMESTAMP()-31*24*60*60) AND (nalog-pay)>0.01");


//������ � �������� � ��������� ����
if ($da['mday']==1)
{
    mysql_query("UPDATE game_cron_log SET step='14������� ������������������� ������', timecron=".time()." WHERE id=$idcronlog");
	//�������� � ������� ������������������� ������
	$sel = myquery("SELECT build_id,user_id,SUM(doska) as doska,SUM(stone) as stone,SUM(doska_repair) as doska_repair,SUM(stone_repair) as stone_repair FROM houses_users WHERE type>1 GROUP BY user_id HAVING doska_repair>doska AND stone_repair>stone");
    while ($ch=mysql_fetch_array($sel))
	{
		$user_id = $ch['user_id'];
		delete_house($user_id,$ch['build_id']);
	}

	
	$test_user=myquery("Select Distinct game_users_data.user_id, game_users_data.month_visits, houses_users.square 
						From game_users_data
						Join houses_users On houses_users.user_id=game_users_data.user_id
						Where game_users_data.month_visits>=0 and houses_users.type=1");
	$nalogtime = mktime(23,59,59,$da['mon'],$da['mday'],$da['year']);
	while ($user_data=mysql_fetch_array($test_user))
	{
		mysql_query("UPDATE game_cron_log SET step='15���������� ������', timecron=".time()." WHERE id=$idcronlog");
		$nalog=600*$user_data['month_visits']*$user_data['square']/365;
		myquery("INSERT INTO houses_nalog (user_id,nalog,nalog_time) VALUES (".$user_data['user_id'].",".$nalog.",".$nalogtime.") ON DUPLICATE KEY UPDATE nalog=nalog+".$nalog."");
		mysql_query("UPDATE game_cron_log SET step='16���������� �������', timecron=".time()." WHERE id=$idcronlog");
		myquery("Update houses_users set stone_repair=stone_repair+stone*1.2*".$user_data['month_visits']."/365, doska_repair=doska_repair+doska*1.2*".$user_data['month_visits']."/365 Where user_id=".$user_data['user_id']."");
	}
	mysql_query("UPDATE game_cron_log SET step='17�������� ����� ���������', timecron=".time()." WHERE id=$idcronlog");
    //�������� ����� ���������
    $sel = myquery("SELECT SUM(houses_nalog.nalog-houses_nalog.pay) AS summa,houses_nalog.user_id,houses_users.square AS square FROM houses_nalog,houses_users WHERE houses_nalog.nalog_time<(UNIX_TIMESTAMP()-31*24*60*60) AND houses_users.user_id=houses_nalog.user_id AND houses_users.type=1 GROUP BY houses_nalog.user_id HAVING summa>=(square*700)");
    mysql_query("UPDATE game_cron_log SET step='18�������� ����� ���������', timecron=".time()." WHERE id=$idcronlog");
	while ($ch=mysql_fetch_array($sel))
	{
		$user_id = $ch['user_id'];
		delete_house($user_id);
	}
	mysql_query("UPDATE game_cron_log SET step='19�������� �������� ���������', timecron=".time()." WHERE id=$idcronlog");
	myquery("UPDATE game_users_data SET month_visits=0");    
}


myquery("UPDATE game_cron_log SET step='20', timecron=".time()." WHERE id=$idcronlog");
$sel = myquery("SELECT game_users_archive.user_id FROM game_users_archive,game_users_data WHERE game_users_archive.clevel=0 AND game_users_data.last_visit=".(time()-7*24*60*60)."");
mysql_query("UPDATE game_cron_log SET step='21', timecron=".time()." WHERE id=$idcronlog");
while (list($id) = mysql_fetch_array($sel))
{
	admin_delete_user($id);
}

myquery("UPDATE game_cron_log SET step='22', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_npc WHERE for_user_id NOT IN (SELECT user_id FROM game_users) AND for_user_id>0");

myquery("UPDATE game_cron_log SET step='23', timecron=".time()." WHERE id=$idcronlog");
$sel_templ = myquery("SELECT npc_id FROM game_npc_template WHERE to_delete>0");
while ($templ = mysql_fetch_array($sel_templ))
{
	$count = mysql_result(myquery("SELECT COUNT(*) FROM game_npc WHERE npc_id=".$templ['npc_id'].""),0,0);
	if ($count==0)
	{
		myquery("DELETE FROM game_npc_template WHERE npc_id=".$templ['npc_id']."");
	}
}
//myquery("UPDATE game_cron_log SET step='26', timecron=".time()." WHERE id=$idcronlog");
//myquery("UPDATE game_users_horses SET golod=golod+1");

myquery("UPDATE game_cron_log SET step='24', timecron=".time()." WHERE id=$idcronlog");
myquery("DELETE FROM game_combats_log_data WHERE boy NOT IN (SELECT DISTINCT boy FROM game_combats_log)");

myquery("UPDATE game_cron_log SET step='final', timecron=".time()." WHERE id=$idcronlog");

?>