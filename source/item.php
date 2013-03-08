<?php
require_once('inc/engine.inc.php');
require('inc/xray.inc.php');
include('inc/lib.inc.php');
require('inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

// Временно. До перевода от $char[boy] -> $char[func_id] глобально
$f_id=getFunc($user_id);

if($f_id=='1') {if (function_exists("save_debug")) save_debug(); exit;}

if (!empty($inv_option))
{
	switch ($inv_option)
	{
		case 'equip':
			if ($user_time < $char['delay'])
			{
				if (isset($_GET['house']))
				{
					setLocation("lib/inv.php?house&option=".$option."&reason=delay");
				}
				else
				{
					setLocation("act.php?func=inv&reason=delay");
				}
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			$id=(int)$_GET['id'];
			
			$Item = new Item();
			$Item->up($id,0);
		break;

		case 'unequip':
			$Item = new Item();
			$Item->down($_GET['id']);
		break;

		case 'kleymo_return':
			$Item = new Item();
			$Item->kleymo_return($_GET['id']);
		break;

		case 'use':
			$Item = new Item();
			$Item->use_item($_GET['id']);
		break;

		case 'drop':
			$Item = new Item();
			$Item->drop($_GET['id']);
		break;
	
		case 'takeres':
            if (!isset($_GET['id']) or !is_numeric($_GET['id'])) break;
            if (!isset($_GET['col']) or $_GET['col'] <= 0 or !is_numeric($_GET['col'])) break;
			$result_items = myquery("SELECT * FROM craft_resource_market WHERE id='".$_GET['id']."' AND col>0 AND col>='".$_GET['col']."' AND user_id=0 AND town=0 AND map_name='" . $char['map_name'] . "' AND map_xpos=" . $char['map_xpos'] . " AND map_ypos=" . $char['map_ypos'] . " LIMIT 1");
			if ($result_items!=false AND mysql_num_rows($result_items)>0)
			{
				$drop = mysql_fetch_array($result_items);
				$ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$drop['res_id'].""));
				$_GET['col']=(int)$_GET['col'];
				$_GET['col']=max(0,$_GET['col']);
				$weight = $_GET['col']*$ress['weight'];
				$prov = mysqlresult(myquery("select count(*) from game_wm where user_id=$user_id AND type=1"),0,0);
				if ($char['CW']+$weight<=$char['CC'] or $prov>0)
				{
					$delay = $user_time + $weight;
					$update_users = myquery("UPDATE game_users SET CW=(CW + $weight) WHERE user_id=$user_id LIMIT 1");
					set_delay_info($user_id,$delay,7);
					
					if ($drop['col']==$_GET['col'])
					{
						myquery("DELETE FROM craft_resource_market WHERE id=$id");
					}
					else
					{
						myquery("UPDATE craft_resource_market SET col=col-".$_GET['col']." WHERE id=$id");
					}
					myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ($user_id,".$drop['res_id'].",".$_GET['col'].") ON DUPLICATE KEY UPDATE col=col+".$_GET['col']."");
				}
				else
				{
					setLocation("act.php?errror=full_inv");
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
			}
		break;

		case 'take':
			list($maze) = mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=".$char['map_name'].""));
			if ($maze==1 AND !isset($_GET['id']))
			{
				$result_items = myquery("SELECT type,effekt FROM game_maze WHERE map_name='" . $char['map_name'] . "' AND xpos=" . $char['map_xpos'] . " AND ypos=" . $char['map_ypos'] . " LIMIT 1");
				$usl = mysql_num_rows($result_items);
				if ($usl>0)
				{
					list($type , $effekt) = mysql_fetch_array($result_items);
					if ($type>=3 AND $type<=10)
					{
						switch($type)
						{
							case 3:
								$update_users = myquery("UPDATE game_users SET GP=GP + $effekt WHERE user_id=$user_id LIMIT 1");
								setGP($user_id,$effekt,5);
								break;
							case 4:
								$update_users = myquery("UPDATE game_users SET GP=GP - $effekt WHERE user_id=$user_id LIMIT 1");
								setGP($user_id,-$effekt,6);
								break;
							case 5:
								$update_users = myquery("UPDATE game_users SET HP=HP - $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 6:
								$update_users = myquery("UPDATE game_users SET MP=MP - $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 7:
								$update_users = myquery("UPDATE game_users SET STM=STM - $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 8:
								$update_users = myquery("UPDATE game_users SET HP=HP + $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 9:
								$update_users = myquery("UPDATE game_users SET MP=MP + $effekt WHERE user_id=$user_id LIMIT 1");
								break;
							case 10:
								$update_users = myquery("UPDATE game_users SET STM=STM + $effekt WHERE user_id=$user_id LIMIT 1");
								break;
						}
						set_delay_reason_id($user_id,7);
						myquery("UPDATE game_maze SET type=0,effekt=0 WHERE map_name='" . $char['map_name'] . "' AND xpos=" . $char['map_xpos'] . " AND ypos=" . $char['map_ypos'] . ""); 
						setLocation("act.php?getsunduk=$effekt");
						{if (function_exists("save_debug")) save_debug(); exit;}
					}
				}
			}
			
            if (!isset($_GET['id']) or !is_numeric($_GET['id'])) break;
			$Item = new Item((int)$_GET['id']);
			$Item->take();
		break;
	}
	if (function_exists("save_debug")) save_debug(); 
	if (isset($_GET['house']))
	{
		setLocation("lib/inv.php?house&option=".$option."");
	}
	else
	{
		if (getFunc($user_id)==2)
		{
			setLocation("craft.php?inv");
		}
		else
		{ 
			setLocation("act.php?func=inv");
		}
	}
}
?>