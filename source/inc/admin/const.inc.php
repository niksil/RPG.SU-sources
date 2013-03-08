<?

if (function_exists("start_debug")) start_debug(); 

if($char['clan_id']==1 or $char['name']=='mrHawk')
{
	
	if (isset($_POST['save']))
	{
		foreach ($_POST as $key => $value)
		{
			if (substr($key,0,6)=='const_')
			{
				$field_name = substr($key,6);
				myquery("UPDATE game_constants SET `value` = '".$value."' WHERE `name` = '".$field_name."'");
			}    
		}			
	}
	
	$sel = myquery("SELECT * FROM game_constants");
	echo '<h2><center>Установка значений системных констант</center></h2>
	<form action="" method="post">';
	echo '<table cellpadding=2 cellspacing=2>
	<tr><td> Название константы </td><td> Значение </td></tr>';
	
	while ($t = mysql_fetch_array($sel))
	{
		echo '<tr><td> '.$t['name'].'</td><td> <input type="text" name="const_'.$t['name'].'" value="'.$t['value'].'" size="20"> </td></tr>';
	}
	
	echo '</table>
	<input type="submit" name="save" value="Установить константы">
	</form>';
	?>
	<br /><br /><br /><br />
//для проведения Битвы Хаоса - clans_war=1 и chaos_war=1<br />
//для проведения Битвы Кланов - clans_war=1 и chaos_war=0, clans_war_type=4<br />
//для проведения Битвы Склонностей - clans_war=1 и chaos_war=0, clans_war_type=6<br />
//для разрешения битв в "Долинах Смерти" - clans_war=1<br />
//ВНИМАНИЕ!!! После окончания Битвы обязательно выставить clans_war=0<br />
//Склонности: -1 - никто. 0 - без склонности. 1 - нейтрал. 2 - светлые. 3 - тёмные.<br/>
	<?
}

if (function_exists("save_debug")) save_debug(); 

?>