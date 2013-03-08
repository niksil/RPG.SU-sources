<?
include("ajax_header.inc.php");
$response = "";

function create_response($item_id)
{
	$sel_res = myquery("SELECT id,name FROM craft_resource WHERE name LIKE ('%морий%') ORDER BY BINARY name");
	$response = "
	<table>
	<tr><td>Ресурс</td><td>Количество</td><td>&nbsp;</td><td>&nbsp;<td></tr>
	<tr>
	<td><select id=\"res_id\">";
	while ($res = mysql_fetch_array($sel_res))
	{
		$response.="<option value=".$res['id'].">(".$res['id'].") ".$res['name']."</option>";
	}
	$response.="</td>
	<td><input id=\"new_col\" size=5 value=0></td>
	<td><input type=\"button\" value=\"Сохранить\" onClick=\"save_res('new');\"></td>
	<td>&nbsp;</tr>";
	if ($item_id>0)
	{
		$sel = myquery("SELECT * FROM dungeon_quests_res WHERE quest_id=$item_id ORDER BY id");
		while ($schema = mysql_fetch_array($sel))
		{
			$response.="<tr>
			<td><select id=\"res_id_".$schema['id']."\">";
			$sel_res = myquery("SELECT id,name FROM craft_resource ORDER BY BINARY name");
			while ($res = mysql_fetch_array($sel_res))
			{
				$response.="<option value=".$res['id']."";
				if ($res['id']==$schema['res_id'])
				{
					$response.=" selected";
				}
				$response.=">".$res['name']."</option>";
			}
			$response.="</td>
			<td><input id=\"col_".$schema['id']."\" size=5 value=\"".$schema['col']."\"></td>
			<td><input type=\"button\" value=\"Сохранить\" onClick=\"save_res('".$schema['id']."');\"></td>
			<td><input type=\"button\" value=\"Удалить\" onClick=\"delete_res('".$schema['id']."');\"></td>";
		}
	}
	$response.="</table>";
	return $response;
}

if (isset($_GET['save']))
{
	$res_id = (int)$_GET['save'];
	$col = (int)$_GET['col'];
	$item_id = (int)$_GET['quest'];
	if ($col>0)
	{
		$item_name = mysql_result(myquery("SELECT name FROM dungeon_quests WHERE id=".$item_id.""),0,0); 
		$res_name = mysql_result(myquery("SELECT name FROM craft_resource WHERE id=".$res_id.""),0,0); 
		myquery("INSERT INTO dungeon_quests_res (quest_id,res_id,col) VALUES ($item_id,$res_id,$col) ON DUPLICATE KEY UPDATE col=$col");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Для квеста Подземелий Мории: <b>".$item_name."</b> добавил(изменил) состав ресурсов: ресурс <b>".$res_name."</b>, количество - ".$col."',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	}
	$response = 'ok';
}
if (isset($_GET['delete']))
{
	$id = (int)$_GET['delete'];
	list($res_id,$col,$item_id) = mysql_fetch_array(myquery("SELECT res_id,col,quest_id FROM dungeon_quests_res WHERE id=$id"));
	$item_name = mysql_result(myquery("SELECT name FROM dungeon_quests WHERE id=".$item_id.""),0,0); 
	$res_name = mysql_result(myquery("SELECT name FROM craft_resource WHERE id=".$res_id.""),0,0); 
	myquery("DELETE FROM dungeon_quests_res WHERE id=$id");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для квеста Подземелий Мории: <b>".$item_name."</b> удалил из состав ресурсов: ресурс <b>".$res_name."</b>, количество - ".$col."',
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