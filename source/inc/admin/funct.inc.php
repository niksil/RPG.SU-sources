<?

if (function_exists("start_debug")) start_debug(); 

if($char['name']=='mrHawk')
{
	
	if (isset($npc))
	{
		$npc_check=myquery("Select Distinct npc_id as id from game_npc where map_name=691");
		while ($npc=mysql_fetch_array($npc_check))
		{
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
			$new=mysql_insert_id();
			myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 2), ($new, 2, 100), ($new, 3, 0)");
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
		}
		$npc_check=myquery("Select Distinct npc_id as id from game_npc where map_name=692");
		while ($npc=mysql_fetch_array($npc_check))
		{
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
			$new=mysql_insert_id();
			myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 2), ($new, 2, 120), ($new, 3, 10)");
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
		}
		$npc_check=myquery("Select Distinct npc_id as id from game_npc where map_name=804");
		while ($npc=mysql_fetch_array($npc_check))
		{
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
			$new=mysql_insert_id();
			myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 2), ($new, 2, 140), ($new, 3, 10)");
			myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
		}
			/*switch ($npc['f'])
			{
			case 1: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 1), ($new, 2, 70), ($new, 3, 15)");
			break;
			case 2: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 9)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 100)");
			break;
			case 3: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 10)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 100)");
			break;
			case 4: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
			break;
			case 5: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 1), ($new, 2, 100), ($new, 3, 15)");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
			break;
			case 6: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 2), ($new, 2, 100), ($new, 3, 0)");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
			break;
			case 7: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 2), ($new, 2, 120), ($new, 3, 10)");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
			break;
			case 8: 
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 12)");
				$new=mysql_insert_id();
				myquery("Insert Into game_npc_set_option_value (id, number, value) Values ($new, 1, 2), ($new, 2, 140), ($new, 3, 10)");
				myquery("Insert Into game_npc_set_option (npc_id, opt_id) Values (".$npc['id'].", 8)");
			break;
			}
		}*/
	}
	if (isset($newnpc))
	{
		$da = getdate();
		if ($da['mday']==31 and $da['hours']>=0 and $da['hours']<24)
		{	
			$npc_id=1058835;
			$templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=$npc_id"));
			myquery("INSERT INTO game_npc SET stay=4,npc_id=$npc_id,map_name=18,xpos=28,ypos=21,view=0,dropable=1,HP=".$templ['npc_max_hp'].",MP=".$templ['npc_max_mp'].", EXP=".$templ['npc_exp_max']."");
			$say = 'Зло прорвалось в Средиземье!';
			$say = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say."</b></span>");
			myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1)");
		}
	}
	echo '<center><h2>Процедуры</h2></center>';
	echo '1. <a href=admin.php?opt=main&option=funct&npc>Перенастройка ботов</a><br>';
	echo '2. <a href=admin.php?opt=main&option=funct&newnpc>Создание Призрака Хранителя</a>';
	echo '</center>';	
}

if (function_exists("save_debug")) save_debug(); 

?>