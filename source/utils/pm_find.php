<?php
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();
?>

Search: <form action="pm_find.php" method="post"><input type="text" name="find"/><br/>
<input type="checkbox" name="del"/>Удаленые?
<input type="checkbox" name="info"/>Спец. почта?
<input type="submit" name="Find"></form>

<?php
  if (!isset($_POST['Find']))
    die("stop!");

  if (!isset($_POST['info']))
  {
    if (!isset($_POST['del']))
      $res = myquery("SELECT * FROM `game_pm` WHERE 1 ORDER BY `time` DESC");
    else
      $res = myquery("SELECT * FROM `game_pm_deleted` WHERE 1 ORDER BY `time` DESC");
  }
  else
  {
    if (!isset($_POST['del']))
      $res = myquery("SELECT * FROM `game_pm` WHERE `otkogo` = 0 ORDER BY `time` DESC");
    else
      $res = myquery("SELECT * FROM `game_pm_deleted` WHERE `otkogo` = 0 ORDER BY `time` DESC");
  }

//  $find = iconv("Windows-1251","UTF-8//IGNORE", $_POST['find']);

//  $find = "Ты продал ресурс .* -";
  $find = $_POST['find'];
//  $find = "";
  echo("Ищем: ".$find);

?>

<table>
  <tr><td>id</td><td>komu</td><td>otkogo</td><td>theme</td><td>post</td><td>view</td><td>clan</td><td>time</td><td>folder</td></tr>

<?php

  while ($row = mysql_fetch_array($res))
  {
    if (preg_match("/".$find."/i", $row['post']))
      echo("<tr><td>".$row['id']."</td><td>".$row['komu']."</td><td>".$row['otkogo']."</td><td>".$row['theme']."</td><td>".$row['post']."</td><td>".$row['view']."</td><td>".$row['clan']."</td><td>".date("j.m.Y, H:i:s",$row['time'])."</td><td>".$row['folder']."</td></tr>");
  }
?>
</table>