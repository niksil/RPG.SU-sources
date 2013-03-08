<?
include("ajax_header.inc.php");
$response = "";

function create_response($npc_id)
{
	$response = '<br />Для данного монстра добавлены выпадающие предметы:
	<table border=1 cellspacing=2 cellpadding=2>';
	//Форма добавлени лута
	$response.='<tr><td>
	<div id="content" onclick="hideSuggestions();">
	<table bgcolor="#003E00" border=0 cellspacing=2 cellpadding=2>
	<tr><td>Вариант выпадения: </td><td>
	<select id="variant">
		<option value="0" selected>Выпадает постоянно</option>
		<option value="1">Выпадает один раз</option>
	</select>
	</td></tr>
	<tr><td>Тип дроповой вещи: </td><td>
	<select id="drop_type" onChange="switch_type(this)">
		<option value="1" selected>Предмет</option>
		<option value="2">Ресурс</option>
	</select>
	<input id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value=""><div style="display:none;" id="scroll"><div id="suggest"></div></div>
	</td></tr>
	<tr><td>
	Шанс выпадения:</td><td>
	<input id="chance" size="10" type="text" value="0"> из <input type="text" id="chance_max" size="10" value="100">
	</td></tr>
	<tr><td> Куда выпадает предмет: </td><td>
	<select id="kuda">
		<option value="0" selected>Падает на землю</option>
		<option value="1">Перемещается в инвентарь</option>
	</select>
	</td></tr>
	<tr><td>Количество одновременно "падающих" предметов</td><td>От <input type="text" maxsize="5" size="5" id="mincount" value="1"> до <input type="text" maxsize="5" size="5" id="maxcount" value="1"></td></tr>
	<tr><td colspan="2"><!--Если из монстра выпадает более 1 предмета, то для каждого предмета можно задать шанс выпадания среди всего списка выпадающих предметов<br><input type="text" maxsize="3" size="5" id="random_all" value="0"> - % выпадания этого элемента среди всего списка--></td></tr>
	</table>
	</div></td>
	<td><input style="width:100%" type="button" value="Добавить" onclick="save_res(\'new\')"></td></tr>';

	$sel = myquery("SELECT * FROM game_npc_drop WHERE npc_id=".$npc_id."");
	$i = 0;
	$lcm = array();
	$all = 0;

	while ($drop=mysql_fetch_array($sel))
	{
		$i++;
		$response.='<tr><td>
		<table border=0 cellspacing=2 cellpadding=2';
		if ($i%2==0)
		{
			$response.=' bgcolor="#00004F"';    
		}
		else
		{
			$response.=' bgcolor="#420000"';    
		}
		$response.='>
		<tr><td>Вариант выпадения: </td><td>
		<select id="variant'.$drop['id'].'">
			<option value="0" ';if ($drop['variant']==0) $response.='selected'; $response.='>Выпадает постоянно</option>
			<option value="1" ';if ($drop['variant']==1) $response.='selected'; $response.='>Выпадает один раз</option>
		</select>
		</td></tr>
		<tr><td>Тип дроповой вещи: </td><td>
		<select id="drop_type'.$drop['id'].'" onChange="switch_type(this)">
			<option value="1" ';if ($drop['drop_type']==1) $response.='selected'; $response.='>Предмет</option>
			<option value="2" ';if ($drop['drop_type']==2) $response.='selected'; $response.='>Ресурс</option>
		</select>';
		$name_item = "";
		if($drop['drop_type']==1)
		{
			$selname = myquery("SELECT name FROM game_items_factsheet WHERE id=".$drop['items_id']."");
			if (mysql_num_rows($selname)>0)
			{
				list($name_item) = mysql_fetch_array($selname);    
			}
		}
		elseif($drop['drop_type']==2)
		{
			$selname = myquery("SELECT name FROM craft_resource WHERE id=".$drop['items_id']."");
			if (mysql_num_rows($selname)>0)
			{
				list($name_item) = mysql_fetch_array($selname);    
			}
		}
		$response.='&nbsp;<input id="name_items'.$drop['id'].'" type="text" size="50" value="'.$name_item.'">';
		$response.='</td></tr>
		<tr><td>
		Шанс выпадения:</td><td>
		<input id="chance'.$drop['id'].'" size="10" type="text" value="'.$drop['random'].'"> из <input type="text" id="chance_max'.$drop['id'].'" size="10" value="'.$drop['random_max'].'">
		</td></tr>
		<tr><td> Куда выпадает предмет: </td><td>
		<select id="kuda'.$drop['id'].'">
			<option value="0" ';if ($drop['kuda']==0) $response.='selected'; $response.='>Падает на землю</option>
			<option value="1" ';if ($drop['kuda']==1) $response.='selected'; $response.='>Перемещается в инвентарь</option>
		</select>
		</td></tr>
		<tr><td>Количество одновременно "падающих" предметов</td><td>От <input type="text" maxsize="5" size="5" id="mincount'.$drop['id'].'" value="'.$drop['mincount'].'"> до <input type="text" maxsize="5" size="5" id="maxcount'.$drop['id'].'" value="'.$drop['maxcount'].'"></td></tr>
		<tr><td colspan="2"><!--Если из монстра выпадает более 1 предмета, то для каждого предмета можно задать шанс выпадания среди всего списка выпадающих предметов<br><input type="text" maxsize="3" size="5" id="random_all'.$drop['id'].'" value="0"> - % выпадания этого элемента среди всего списка--></td></tr>
		</table></td>
		<td><input style="width:100%" type="button" value="Сохранить" onclick="save_res(\''.$drop['id'].'\')"><br /><br /><br /><input style="width:100%" type="button" value="Удалить" onclick="delete_res(\''.$drop['id'].'\')"></td></tr>';

		$lcm[] = $drop['random_max'];
	}

	$lcm = lcm_arr($lcm);

	if ($lcm != 0)
	{
		mysql_data_seek($sel, 0);
	        while ($chance = mysql_fetch_array($sel))
		{
			$all += ($chance['random']) * $lcm / gcd($lcm, $chance['random_max']);
		}

		if ($all <= $lcm)
			$response.="</table><br /><br />Общий шанс лута: <font color=green><b><u>".$all."/".$lcm."</u></b></font> (".round(100*$all/$lcm,2)."%)";
		else
			$response.="</table><br /><br />Общий шанс лута: <font color=red><b><u>".$all."/".$lcm."</u></b></font> (".round(100*$all/$lcm,2)."%)";
	}
	else
		$response.="</table><br /><br />Лута нет.";

	return $response;
}

if (isset($_GET['save']))
{
	$id = (int)$_GET['save'];
	list($npc_name) = mysql_fetch_array(myquery("SELECT npc_name FROM game_npc_template WHERE npc_id=".$id.""));
	$item_name =  iconv("UTF-8//IGNORE","Windows-1251",$_POST['name_items']);
	$items_id = "";
	if($_POST['drop_type']==1)
	{
		$selname = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
		if (mysql_num_rows($selname)>0)
		{
			list($items_id) = mysql_fetch_array($selname);    
		}
	}
	elseif($_POST['drop_type']==2)
	{
		$selname = myquery("SELECT id FROM craft_resource WHERE name='".$item_name."'");
		if (mysql_num_rows($selname)>0)
		{
			list($items_id) = mysql_fetch_array($selname);    
		}
	}
	if (isset($_POST['drop_id']))
	{
		//update
		$loot = "изменил состав лута: ";
		myquery("UPDATE game_npc_drop SET items_id=".$items_id.",variant=".$_POST['variant'].",random=".$_POST['chance'].",random_max=".$_POST['chance_max'].",kuda=".$_POST['kuda'].",drop_type=".$_POST['drop_type'].",mincount=".$_POST['mincount'].",maxcount=".$_POST['maxcount']." WHERE id=".$_POST['drop_id']."");
	}
	else
	{
		//insert
		$loot = "добавил в состав лута: ";
		myquery("INSERT INTO game_npc_drop SET npc_id=$id,items_id=".$items_id.",variant=".$_POST['variant'].",random=".$_POST['chance'].",random_max=".$_POST['chance_max'].",kuda=".$_POST['kuda'].",drop_type=".$_POST['drop_type'].",mincount=".$_POST['mincount'].",maxcount=".$_POST['maxcount']."");
	} 
	if ($_POST['drop_type']==1)
	{
		$loot.="предмет ";
	}
	else
	{
		$loot.="ресурс ";
	}
	$loot.="<b>".$item_name."</b> шанс: ".$_POST['chance']." из ".$_POST['chance_max']." , кол-во: от ".$_POST['mincount']." до ".$_POST['maxcount']."";
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для шаблона бота: <b>$npc_name</b> $loot',
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
	$drop = mysql_fetch_array(myquery("SELECT * FROM game_npc_drop WHERE id=$id"));
	list($npc_name) = mysql_fetch_array(myquery("SELECT npc_name FROM game_npc_template WHERE npc_id=".$drop['npc_id'].""));
	$name_item = "";
	if($drop['drop_type']==1)
	{
		$selname = myquery("SELECT name FROM game_items_factsheet WHERE id=".$drop['items_id']."");
		if (mysql_num_rows($selname)>0)
		{
			list($name_item) = mysql_fetch_array($selname);    
		}
	}
	elseif($drop['drop_type']==2)
	{
		$selname = myquery("SELECT name FROM craft_resource WHERE id=".$drop['items_id']."");
		if (mysql_num_rows($selname)>0)
		{
			list($name_item) = mysql_fetch_array($selname);    
		}
	}
	if ($drop['drop_type']==1)
	{
		$item_name = 'удалил предмет '.$name_item; 
	}
	else
	{
		$item_name = 'удалил ресурс '.$name_item; 
	}
	myquery("DELETE FROM game_npc_drop WHERE id=$id");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для бота: <b>".$npc_name."</b> из состава лута ".$item_name."',
	 '".time()."',
	 '".$da['mday']."',
	 '".$da['mon']."',
	 '".$da['year']."')")
		 or die(mysql_error());
	$response = 'ok';
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