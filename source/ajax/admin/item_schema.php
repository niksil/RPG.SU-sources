<?php
include("ajax_header.inc.php");
$response = "";

function create_response($item_id)
{
	$sel_res = myquery("SELECT id,name FROM craft_resource ORDER BY BINARY name");
	$response = "
	<table>
	<tr><td>Ресурс</td><td>Количество</td><td>&nbsp;</td><td>&nbsp;<td></tr>
	<tr>
	<td><select id=\"res_id\">";
	while ($res = mysql_fetch_array($sel_res))
	{
		$response.="<option value=".$res['id'].">".$res['name']."</option>";
	}   
	$response.="</select></td>
	<td><input id=\"new_col\" size=5 value=0></td>
	<td><input type=\"button\" value=\"Сохранить\" onClick=\"save_schema('new');\"></td>
	<td>&nbsp;</tr>";    
	if ($item_id>0)
	{
		$sel = myquery("SELECT * FROM game_items_schema WHERE item_id=$item_id");
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
			$response.="</select></td>
			<td><input id=\"col_".$schema['id']."\" size=5 value=\"".$schema['col']."\"></td>
			<td><input type=\"button\" value=\"Сохранить\" onClick=\"save_schema('".$schema['id']."');\"></td>
			<td><input type=\"button\" value=\"Удалить\" onClick=\"delete_schema('".$schema['id']."');\"></td>"; 
		}
	}
	$response.="</table>";
	return $response;
}

if (isset($_GET['save']))
{
	$res_id = (int)$_GET['save'];
	$col = (int)$_GET['col'];
	$item_id = (int)$_GET['read'];
	$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$item_id.""),0,0); 
	$res_name = mysqlresult(myquery("SELECT name FROM craft_resource WHERE id=".$res_id.""),0,0); 
	myquery("INSERT INTO game_items_schema (item_id,res_id,col) VALUES ($item_id,$res_id,$col) ON DUPLICATE KEY UPDATE col=$col");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для предмета: <b>".$item_name."</b> добавил(изменил) состав схемы: ресурс <b>".$res_name."</b>, количество - ".$col."',
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
	list($res_id,$col,$item_id) = mysql_fetch_array(myquery("SELECT res_id,col,item_id FROM game_items_schema WHERE id=$id"));
	$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$item_id.""),0,0); 
	$res_name = mysqlresult(myquery("SELECT name FROM craft_resource WHERE id=".$res_id.""),0,0); 
	myquery("DELETE FROM game_items_schema WHERE id=$id");
	$da = getdate();
	$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
	 VALUES (
	 '".$char['name']."',
	 'Для предмета: <b>".$item_name."</b> удалил состав схемы: ресурс <b>".$res_name."</b>, количество - ".$col."',
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