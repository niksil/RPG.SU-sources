<?php
include("ajax_header.inc.php");
require_once('../../class/class_item.php');

$response = "";

function create_response($item_id)
{
    $sel_res = myquery("SELECT id,name,type FROM game_items_factsheet WHERE can_up=1 AND type <=18 AND type NOT IN (12,13) ORDER BY type, BINARY name");
    $response = "
    <table>
    <tr><td>Предмет, входящий в комплект</td><td>&nbsp;</td><td>&nbsp;<td></tr>
    <tr>
    <td><select style=\"font-size:12px;\" id=\"item_id\">";
    $cur_type = 0;
    while ($res = mysql_fetch_array($sel_res))
    {
        if ($cur_type!=$res['type'])
        {
            $cur_type = $res['type'];
            $response.="<option value=0 disabled=true style=\"background-color:white;\">".type_str($cur_type)."</option>";
        }
        $response.="<option value=".$res['id'].">".$res['name']."</option>";
    }   
    $response.="</select></td>
    <td><input type=\"button\" value=\"Сохранить\" onClick=\"save_schema('new');\"></td>
    <td>&nbsp;</tr>";    
    if ($item_id>0)
    {
        $sel = myquery("SELECT * FROM game_items_complect WHERE complect_id=$item_id");
        while ($schema = mysql_fetch_array($sel))
        {
            $response.="<tr>
            <td><select style=\"font-size:12px;\" id=\"item_id_".$schema['id']."\">";
            $sel_res = myquery("SELECT id,name,type FROM game_items_factsheet WHERE can_up=1 AND type <=18 AND type NOT IN (12,13) ORDER BY type, BINARY name");
            $cur_type = 0;
            while ($res = mysql_fetch_array($sel_res))
            {
                if ($cur_type!=$res['type'])
                {
                    $cur_type = $res['type'];
                    $response.="<option value=0 disabled=true style=\"background-color:white;\">".type_str($cur_type)."</option>";
                }
                $response.="<option value=".$res['id']."";
                if ($res['id']==$schema['item_id'])
                {
                    $response.=" selected";
                }
                $response.=">".$res['name']."</option>";
            }
            $response.="</select></td>
            <td><input type=\"button\" value=\"Сохранить\" onClick=\"save_schema('".$schema['id']."');\"></td>
            <td><input type=\"button\" value=\"Удалить\" onClick=\"delete_schema('".$schema['id']."');\"></td>"; 
        }
    }
    $response.="</table>";
    return $response;
}

if (isset($_GET['save']))
{
	$item_id = (int)$_GET['save'];
	$complect_id = (int)$_GET['read'];
	$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$item_id.""),0,0); 
	$complect_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$complect_id.""),0,0); 
	myquery("INSERT INTO game_items_complect (item_id,complect_id) VALUES ($item_id,$complect_id) ON DUPLICATE KEY UPDATE item_id=$item_id");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для предмета: <b>".$item_name."</b> добавил(изменил) состав комплекта ".$complect_name.": предмет <b>".$item_name."</b>',
	 '".time()."',
	 '".$da['mday']."',
	 '".$da['mon']."',
	 '".$da['year']."')")
		 or die(mysql_error());
	$response = 'ok';
}

if (isset($_GET['delete']))
{
	$id = (int)$_GET['delete'];
	list($complect_id,$item_id) = mysql_fetch_array(myquery("SELECT complect_id,item_id FROM game_items_complect WHERE id=$id"));
	$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$item_id.""),0,0); 
	$complect_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$complect_id.""),0,0); 
	myquery("DELETE FROM game_items_complect WHERE id=$id");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для предмета: <b>".$item_name."</b> удалил из комплекта ".$complect_name.": предмет <b>".$item_name."</b>',
	 '".time()."',
	 '".$da['mday']."',
	 '".$da['mon']."',
	 '".$da['year']."')")
		 or die(mysql_error());
	$response = 'ok';
}

// При изменении состава комплекта пересчитать всех игроков.
if (isset($_GET['save']) OR isset($_GET['delete']))
{
	$complect_id = (int)$_GET['read'];

	// Снимаем комплект со всех, у кого он был одет.
	$res = myquery("SELECT `id`,`user_id` FROM `game_items` WHERE `priznak`=0 AND `used`=22 AND `item_id` = $complect_id");
	while ($list = mysql_fetch_array($res))
	{
		$item = new Item($list['id']);
		$item->setChar($list['user_id']);
		$item->down();
	}

	$complect_fact = mysql_fetch_assoc(myquery("SELECT * FROM game_items_factsheet WHERE id=$complect_id"));
	$complect_fact['weight'] = (double)$complect_fact['weight'];
	$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");

	// Игроки у которых можеь быть одета новая версия комплекта
	$res = myquery("SELECT DISTINCT `user_id` FROM `game_items` WHERE `priznak`=0 AND `used`!=0 AND `item_id` IN (SELECT DISTINCT `item_id` FROM `game_items_complect` WHERE `complect_id` = $complect_id);");
	while ($list = mysql_fetch_array($res))
	{
		//Требования к этому комплекту выполненны?
		$check_user_id = $list['user_id'];
		$est_complect = 0;
		$kol_item_in_complect = 0;
		$kol_item_complect_used = 0;
		mysql_data_seek($sel_check, 0);
		while (list($item_ch_id) = mysql_fetch_array($sel_check))
		{
			$kol_item_in_complect++;
			if ($item_ch_id != $complect_fact['id'])
			{
				$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=$check_user_id AND item_id=$item_ch_id");
				if (!mysql_num_rows($check_used))
				{
					$est_complect = 2;
					break;
				}
				else
					$kol_item_complect_used++;
			}
			else
			{
				$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=$check_user_id AND item_id=$item_ch_id");
				if (mysql_num_rows($check_used))
						$kol_item_complect_used++;
			}
		}
		if (($kol_item_in_complect-$kol_item_complect_used)==1 AND $est_complect!=2)
			$est_complect = 1;

		if ($est_complect == 0)
		{
			myquery("INSERT INTO `game_items` (`user_id`, `item_id`, `ref_id`, `item_uselife`, `item_cost`, `map_name`, `map_xpos`, `map_ypos`, `item_for_quest`, `town`, `sell_time`, `priznak`, `post_to`, `post_var`, `used`, `item_uselife_max`, `for_town`, `shop_from`, `kleymo`, `kleymo_nomer`, `kleymo_id`, `count_item`) 
			VALUES ($check_user_id, '$complect_id', '0', '100', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '22', '100', '0', '0', '0', '0', '0', '1');");

			myquery("UPDATE game_users SET 
			STR=STR+".$complect_fact['dstr'].",
			PIE=PIE+".$complect_fact['dpie'].",
			NTL=NTL+".$complect_fact['dntl'].",
			VIT=VIT+".$complect_fact['dvit'].",
			DEX=DEX+".$complect_fact['ddex'].",
			SPD=SPD+".$complect_fact['dspd'].",
			STR_MAX=STR_MAX+".$complect_fact['dstr'].",
			PIE_MAX=PIE_MAX+".$complect_fact['dpie'].",
			NTL_MAX=NTL_MAX+".$complect_fact['dntl'].",
			VIT_MAX=VIT_MAX+".$complect_fact['dvit'].",
			DEX_MAX=DEX_MAX+".$complect_fact['ddex'].",
			SPD_MAX=SPD_MAX+".$complect_fact['dspd'].",
			lucky=lucky+".$complect_fact['dlucky'].",
			lucky_max=lucky_max+".$complect_fact['dlucky'].",
			CC=CC+".$complect_fact['cc_p'].",
			HP_MAX=HP_MAX+".$complect_fact['hp_p'].",
			HP_MAXX=HP_MAXX+".$complect_fact['hp_p'].",
			MP_MAX=MP_MAX+".$complect_fact['mp_p'].",
			STM_MAX=STM_MAX+".$complect_fact['stm_p'].",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=$check_user_id");

			myquery("UPDATE combat_users SET 
			STR=STR+".$complect_fact['dstr'].",
			PIE=PIE+".$complect_fact['dpie'].",
			NTL=NTL+".$complect_fact['dntl'].",
			VIT=VIT+".$complect_fact['dvit'].",
			DEX=DEX+".$complect_fact['ddex'].",
			SPD=SPD+".$complect_fact['dspd'].",
			lucky=lucky+".$complect_fact['dlucky'].",
			HP_MAX=HP_MAX+".$complect_fact['hp_p'].",
			MP_MAX=MP_MAX+".$complect_fact['mp_p'].",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=$check_user_id");

			myquery("UPDATE game_users_archive SET 
			STR=STR+".$complect_fact['dstr'].",
			PIE=PIE+".$complect_fact['dpie'].",
			NTL=NTL+".$complect_fact['dntl'].",
			VIT=VIT+".$complect_fact['dvit'].",
			DEX=DEX+".$complect_fact['ddex'].",
			SPD=SPD+".$complect_fact['dspd'].",
			STR_MAX=STR_MAX+".$complect_fact['dstr'].",
			PIE_MAX=PIE_MAX+".$complect_fact['dpie'].",
			NTL_MAX=NTL_MAX+".$complect_fact['dntl'].",
			VIT_MAX=VIT_MAX+".$complect_fact['dvit'].",
			DEX_MAX=DEX_MAX+".$complect_fact['ddex'].",
			SPD_MAX=SPD_MAX+".$complect_fact['dspd'].",
			lucky=lucky+".$complect_fact['dlucky'].",
			lucky_max=lucky_max+".$complect_fact['dlucky'].",
			CC=CC+".$complect_fact['cc_p'].",
			HP_MAX=HP_MAX+".$complect_fact['hp_p'].",
			HP_MAXX=HP_MAXX+".$complect_fact['hp_p'].",
			MP_MAX=MP_MAX+".$complect_fact['mp_p'].",
			STM_MAX=STM_MAX+".$complect_fact['stm_p'].",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=$check_user_id");
		}
	}
}

if (isset($_GET['read']))
{
	$item_id = (int)$_GET['read']; 
	$response = create_response($item_id);
}

if(ob_get_length()) ob_clean();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/html;charset=windows-1251');

echo $response;
?>