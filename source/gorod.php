<?php
//ob_start('ob_gzhandler',9);
require_once('inc/engine.inc.php');
require('inc/xray.inc.php');
include('inc/lib.inc.php');
require('inc/lib_session.inc.php');
include('inc/template_header.inc.php');

if (function_exists("start_debug")) start_debug(); 

$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."' AND to_map_name=0 AND town>0");
if(mysql_num_rows($select))
{
list($town)=mysql_fetch_array($select);


$sel = myquery("SELECT * FROM game_gorod WHERE town='$town'");
$gorod = mysql_fetch_array($sel);
//list($rustown, $opis, $clan, $race)=mysql_fetch_array($sel);
$rustown = $gorod['rustown'];
$opis = $gorod['opis'];
$clan = $gorod['clan'];
$race = $gorod['race'];
if($clan=='' or $clan==0) $clan=$char['clan_id'];
if($race==0) $race=$char['race'];
if($char['clan_id']==$clan and $char['race']==$race)
{}
else
{
    echo'<style type="text/css">@import url("style/global.css");</style>';
    echo '<br><br><br><font size=5 face="Tahoma,Verdana" color=#00FF00><b><center>Доступ в этот город для тебя закрыт!';
    {if (function_exists("save_debug")) save_debug(); exit;}
}

$dostup=1;
$race = myquery("SELECT race FROM game_har WHERE id='".$char['race']."'");
if (mysql_num_rows($race))
{
    list($race) = mysql_fetch_array($race);
    $race = 'enter_'.$race;
    if ($gorod[$race]!='1') $dostup=0;
}
if ($dostup!=1)
{
    echo'<style type="text/css">@import url("style/global.css");</style>';
    echo '<br><br><br><font size=5 face="Tahoma,Verdana" color=#00FF00><b><center>Доступ в этот город для твоей расы закрыт!';
    {if (function_exists("save_debug")) save_debug(); exit;}
}

function online()
{
    global $char;
	global $town;
	
    $current_time = time();
    $online_range = $current_time - 300;

	echo'<meta http-equiv="refresh" content="30;url="gorod.php?option='.$town.'">';
    $select=myquery("select user_id,name from game_users where delay_reason=2 and user_id IN (SELECT user_id FROM game_users_map WHERE  map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' and map_name='".$char['map_name']."') and user_id IN (SELECT user_id FROM game_users_active WHERE last_active>$online_range)");
    while($sel=mysql_fetch_array($select))
    {
        echo '&nbsp;<a href="http://'.DOMAIN.'/view/?userid='.$sel["user_id"].'" target="_blank"><img border=0 src="http://'.IMG_DOMAIN.'/nav/i.gif"></a>'.$sel['name'].', &nbsp;';
    }
}

if (!isset($option)) $option='';
switch ($option)
{
    case $town:
        echo'<style type="text/css">'.$gorod['style'].'</style>';
        $center=str_replace('&option', "lib/town.php?option" ,$gorod['center']);
        echo $center;
        echo'<table border="1" cellspacing=0 cellpadding=0 width="100%" bordercolor="'.$gorod['color'].'"><tr><td>
        <table cellspacing=0 cellpadding=0 width="100%" align=center border=0 bgcolor="'.$gorod['color'].'"><tr bgcolor=000000><td><center>';
        $selopt = myquery("SELECT * FROM game_gorod_set_option WHERE gorod_id=$town");
        while ($opt = mysql_fetch_array($selopt))
        {
            $opt_id = $opt['option_id'];
            list($nameopt)=mysql_fetch_array(myquery("SELECT name FROM game_gorod_option WHERE id=$opt_id"));
            echo '[<a href=lib/town.php?option='.$opt_id.'>'.$nameopt.'</a>] ';
        }
        echo'[<a href="?option=exit">Выход</a>]</td></tr></table>
        <table cellspacing=0 cellpadding=0 width="100%" align=center border=0 bgcolor="'.$gorod['color'].'"><tr><td><center>Сейчас в '.$gorod['rustown'].':</td></tr><tr bgcolor=000000><td>';
        online();
        echo'</td></tr></table><table cellspacing=0 cellpadding=0 width="100%" align=center border=0 bgcolor="'.$gorod['color'].'"><tr><td><center>Последние новости:</td></tr><td bgcolor=000000"><center>';

        echo stripslashes($gorod['news']);
        $mag=myquery("select * from game_mag where town='$town' and name='".$char['name']."'");
        if (mysql_num_rows($mag))
        {
            echo'<br>Ты маг этого города! Тебе разрешено <a href=gorod.php?option=news&t='.$town.'>изменить новость</a>';
        }
        echo '</td></tr></table></td></tr></table>';
    //}
    //else
    //{
    //        $up=myquery("update game_users set sector='$color', x='$center', y='$links' where user_id='".$char['user_id']."'");
    //        header("Location: gorod/index.php");
    //        {if (function_exists("save_debug")) save_debug(); exit;}
    //}
    break;

    case 'news':
        echo'<style type="text/css">'.$gorod['style'].'</style>';
        include('inc/gorod/news.inc.php');
    break;


    case 'main':
	    if (!isset($dat_gorod)) $_SESSION['dat_gorod']='';
	    $dat_gorod = 0;
	    echo '<title>'.GAME_NAME.' :: Город</title>
	    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	    <meta name="Keywords" content="">
	    <frameset cols="*,530" frameborder="NO" border="1" framespacing="0">
	      <frame src="chat/index.php?opt=main" name="chat">
	      <frame src="gorod.php?option='.$town.'" name="gorod" scrolling="yes">
	    </frameset>';
    break;

    case 'vhod':
        echo '<script language="JavaScript" type="text/javascript">
        function vxod(){
	    location.href="gorod.php?option=main";
        }
        </script>';
 	    echo"<body bgcolor=#000000 text=#FFFFFF><body onLoad='setTimeout(\"vxod()\",1000)'><br><br><br><center><font face=verdana size=2><a href=\"#\" onClick=vxod()>".$gorod['vhod']."</a></center>";
	    $update=myquery("update game_users set delay_reason=2 where user_id='$user_id'");

        myquery("DELETE FROM game_chat_log WHERE user_id=$user_id");
	    myquery("INSERT INTO game_chat_log (user_id,town,name) VALUES ('".$char['user_id']."','".$town."','".$char['name']."')");
        //Уберем зависших игроков из чата города
        $current_time = time();
        $online_range = $current_time - 300;
        $sel = myquery("SELECT * FROM game_chat_log WHERE town='$town'");
        while ($usr = mysql_fetch_array($sel))
        {
            $user_chat = myquery("SELECT last_active FROM game_users_active WHERE user_id=".$usr['user_id']." LIMIT 1");
            list($last) = mysql_fetch_array($user_chat);
            if ($last<$online_range) $del=myquery("DELETE FROM game_chat_log WHERE (town='$town' AND user_id='".$usr['user_id']."')");
        }
    break;

    case 'exit':
        echo '<script language="JavaScript" type="text/javascript">
        function vixod(){
	    location.href="act.php";
        }
        </script>';
	    echo"<body bgcolor=#000000 text=#FFFFFF><body onLoad='setTimeout(\"vixod()\",1000)'><br><br><br><center><font face=verdana size=2><a href=\"#\" onClick=vixod()>Ты выходишь из города</a></center>";
        $update=myquery("DELETE FROM game_chat_log WHERE user_id=$user_id");
    break;
    }
}
if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
    show_debug();
}
mysql_close();

if (function_exists("save_debug")) save_debug(); 

?>