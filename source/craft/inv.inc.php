<?

if (function_exists("start_debug")) start_debug(); 

$select=myquery("select * from craft_resource_user where user_id='$user_id' ");
if (mysql_num_rows($select)) 
{
	QuoteTable('open');
	?>
	<a name="anchor500" href="#anchor500" onClick=expand('d500','d500','d500','funct.php?item=500')><li><b>Ресурсы</b></li></a>
	<div id="d500" style="display:none;"><i>Загрузка</i></div>
	<?
	QuoteTable('close');
	echo'<br />';
}

if (function_exists("save_debug")) save_debug(); 

?>