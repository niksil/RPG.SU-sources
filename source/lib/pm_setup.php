<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (isset($_POST['new']))
{
	$sel = myquery("SELECT folder_id FROM game_pm_folder WHERE user_id=$user_id ORDER BY folder_id DESC LIMIT 1");
	if (mysql_num_rows($sel))
		list($id)=mysql_fetch_array($sel);
	else
		$id=5;
	$id++;
	$newname = htmlspecialchars($_POST['newname']);
	myquery("INSERT INTO game_pm_folder (user_id, folder_id, folder_name) VALUES ($user_id,$id,'$newname') ");
	echo '<center>Новая папка успешно создана!<br><br>';
}

if (isset($_POST['rename']))
{
	$id = $_POST['folder_id'];
	//var_dump($_POST);
	$check = myquery("SELECT * FROM game_pm_folder WHERE user_id=$user_id AND folder_id=$id");
	if (mysql_num_rows($check))
	{
		myquery("UPDATE game_pm_folder SET folder_name='".htmlspecialchars($_POST['re_name'])."' WHERE user_id=$user_id AND folder_id=$id");
		echo '<center>Папка успешно переименована!<br><br>';
	}
}

if (isset($_POST['delete']))
{
	$id = $_POST['folder_id'];
	//var_dump($_POST);
	$check = myquery("SELECT * FROM game_pm_folder WHERE user_id=$user_id AND folder_id=$id");
	if (mysql_num_rows($check))
	{
		myquery("UPDATE game_pm SET folder=1 WHERE folder=$id AND komu=$user_id");
		myquery("DELETE FROM game_pm_folder WHERE user_id=$user_id AND folder_id=$id");
		echo '<center>Папка успешно удалена. Все письма из нее перемещены в папку ПРОЧИТАННЫЕ!<br><br>';
	}
}

echo '';
echo '<center>Управление папками почтового ящика<table border=0>';

$sel = myquery("SELECT * FROM game_pm_folder WHERE user_id=$user_id");
while ($row = mysql_fetch_array($sel))
{
	echo '<tr><td rowspan=2>
	<form name="pm_setup'.$row['folder_id'].'" action="act.php?func=pm&pm=setup" method="post"><input type="hidden" name="folder_id" value='.$row['folder_id'].'></td><td rowspan=2><b>'.$row['folder_name'].'</b></td><td align="right"><br><input type="text" name="re_name" size="35" maxsize="40"  value="'.$row['folder_name'].'">
	<input type="submit" name="rename" value="Переименовать папку"></td></tr>
	<tr><td align="right"><input type="submit" name="delete" value="    Удалить эту папку   "></form><br></td></tr>';
}
echo '<tr><td colspan=3 align="center"><br><br><br><form name="pm_setup_new" action="act.php?func=pm&pm=setup" method="post"><input	type="text" name="newname" size="40" maxsize="40"><input type="submit" name="new" value="Создать новую папку"></td></tr><table>';

if (function_exists("save_debug")) save_debug(); 

?>