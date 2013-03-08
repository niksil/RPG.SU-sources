<?
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '8');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');
include('../inc/template_header.inc.php');

if (function_exists("start_debug")) start_debug(); 


if (isset($submn))
{
	$ref=(int)$ref;
	$color=addslashes($color);
	if (isset($priv)) $priv=1;
    else $priv='';
    if (isset($b)) $b=1;
    else $b='';
    if (isset($i)) $i=1;
    else $i='';
    $upd=myquery("update game_chat_option set ref='$ref',color='$color',privat='$priv',b='$b',i='$i',size='$size' where user_id='$user_id'");
}

$select=myquery("select * from game_chat_option where user_id='$user_id'");
$chato=mysql_fetch_array($select);

$select=myquery("select town from game_map where xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
if (!mysql_num_rows($select)) {if (function_exists("save_debug")) save_debug(); exit;}

list($town)=mysql_fetch_array($select);

if (!isset($opt)) $opt = '';
switch($opt)
{
	case 'main':
        $dat_gorod = 0;
		echo'
		<frameset rows="*,40" cols="*" border="1">
			<frameset cols="110,*" frameborder="NO" border="0">
				<frame src="" name="users" scrolling="NO" noresize>
				<frame src="index.php?&opt=index" name="index">
			</frameset>
			<frame src="index.php?opt=menu" name="menu" scrolling="NO" noresize>
		</frameset>
        ';
    break;

	case 'option':
		echo '<style type="text/css">
		BODY {        FONT-WEIGHT: normal; FONT-SIZE: 12px; BACKGROUND: #000000; MARGIN: 5px; COLOR: #c0c0c0;	scrollbar-face-color: #620706;
					scrollbar-shadow-color: #340403;
					scrollbar-highlight-color: #340403;
					scrollbar-3dlight-color: #620706;
					scrollbar-darkshadow-color: #620706;
					scrollbar-track-color: #1D1D1D;
					scrollbar-arrow-color: #FBF891;
				}
		TD {FONT-SIZE: 13px; FONT-FAMILY: Verdana}
        A:link {FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #aaaaff; FONT-FAMILY: Verdana; TEXT-DECORATION: none}
		A:visited {        FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #aaaaff; FONT-FAMILY: Verdana; TEXT-DECORATION: none}
		</style>';
		echo'<form name="form1" action="" method="post">
		<align=left><font face=verdana size=2>
        Скорость обновления экрана: <input type="text" size="3" maxlenght="2" name="ref" value="'.$chato['ref'].'"><br><br>
        Размер шрифта в пикселах: <input type="text" size="3" maxlenght="2" name="size" value="'.$chato['size'].'"><br><br>
        Цвет шрифта: <select name="color">
        <option value="00ff00"';
        if ($chato['color']=='00ff00') echo"selected";
        echo '>Зелёный</option><option value="666666"';
        if ($chato['color']=='666666') echo"selected";
        echo '>Серый</option>

        <option value="ffff00"';
        if ($chato['color']=='ffff00') echo"selected";
        echo '>Жёлтый</option><option value="0066ff"';
        if ($chato['color']=='0066ff') echo"selected";
        echo '>Голубой</option>


        <option value="990099"';
        if ($chato['color']=='990099') echo"selected";
        echo '>Розовый</option><option value="660033"';
        if ($chato['color']=='660033') echo"selected";
        echo '>Коричневый</option>
        <option value="ff0000"';
        if ($chato['color']=='ff0000') echo"selected";
        echo '>Красный</option><option value="0000ff"';
        if ($chato['color']=='0000ff') echo"selected";
        echo '>Синий</option>

        </select><br><br>
        <input type="checkbox" name="priv"';
        if ($chato['privat']<>'') echo" checked";
        echo '>Видеть только приват  <br><br>
        <input type="checkbox" name="b"';
        if ($chato['b']==1) echo" checked";
        echo '>Жирный<br><br>
        <input type="checkbox" name="i"';
        if ($chato['i']==1) echo" checked";
        echo '>Курсив<br><br>

        <input type="submit" value="Сохранить"><br><input type="hidden" name="submn" value=""><br>
		<a href=index.php?opt=index>Выйти</a>';
    break;

	case 'menu':
		echo '<style type="text/css">
		BODY {        FONT-WEIGHT: normal; FONT-SIZE: 12px; BACKGROUND: #223344; MARGIN: 5px; COLOR: #c0c0c0;	scrollbar-face-color: #620706;
				scrollbar-shadow-color: #340403;
	            scrollbar-highlight-color: #340403;
				scrollbar-3dlight-color: #620706;
				scrollbar-darkshadow-color: #620706;
	            scrollbar-track-color: #1D1D1D;
	            scrollbar-arrow-color: #FBF891;
            }
        INPUT {BACKGROUND-COLOR: #000000; BORDER-BOTTOM-COLOR: #aaaaff; BORDER-BOTTOM-WIDTH: 1px; BORDER-LEFT-COLOR: #aaaaff; BORDER-LEFT-WIDTH: 1px; BORDER-RIGHT-COLOR: #aaaaff; BORDER-RIGHT-WIDTH: 1px; BORDER-TOP-COLOR: #c0c0c0; BORDER-TOP-WIDTH: 1px; COLOR: #ffffff; FONT-FAMILY: Verdana; FONT-SIZE: 11px;}
        </style>';

        $mol=myquery("select * from game_chat_nakaz where town='$town' and user_id='$user_id' and nakaz='mol'");
        if (mysql_num_rows($mol))
        {
            {if (function_exists("save_debug")) save_debug(); exit;}
        }

        $slep=myquery("select * from game_chat_nakaz where town='$town' and user_id='$user_id' and nakaz='slep'");
        if (mysql_num_rows($slep))
        {
            {if (function_exists("save_debug")) save_debug(); exit;}
        }

        echo '<script language="JavaScript">
        function priv(name)
        {
            top.window.frames.chat.menu.document.form.chat_mess.focus();
            if (name.length>10)        top.window.frames.chat.menu.document.form.too.size=name.length;
            top.window.frames.chat.menu.document.form.to.value=name;
            if (name=="") name="Всем";
            top.window.frames.chat.menu.document.form.too.value=name;
            if (name.length>10)
              top.window.frames.chat.menu.document.form.too.size=name.length;
            else
              top.window.frames.chat.menu.document.form.too.size=10;
        }
        </script>';

		echo '<form autocomplete="off" action="index_users.php?opt=users" method="post" name="form" target="users"><input type="hidden" name="to"><input type=text name="too" style="text-align: center;" value="Всем" readonly="true" size="10" onClick="priv(\'\')">&nbsp;<input type="text" name="chat_mess" size="40">&nbsp;&nbsp;<input name="submit" type="submit" alt="Сказать" value="Сказать" onClick="this.form.chat_mess.focus();">&nbsp;&nbsp;<a href="?&opt=option" target="index"><img src="img/option.gif" border="0" alt="Настройки"></a></form>';

    break;

	case 'index':
        echo '<style type="text/css">
            BODY {        FONT-WEIGHT: normal; FONT-SIZE: 10px; BACKGROUND: #000000; COLOR: #ffffff; FONT-FAMILY: Verdana; 	scrollbar-face-color: #620706;
	                scrollbar-shadow-color: #340403;
	                scrollbar-highlight-color: #340403;
	                scrollbar-3dlight-color: #620706;
	                scrollbar-darkshadow-color: #620706;
	                scrollbar-track-color: #1D1D1D;
	                scrollbar-arrow-color: #FBF891;
                }
            </style>';

        echo '<script language="JavaScript">
        function priv(name)
        {
            top.window.frames.chat.menu.document.form.chat_mess.focus();
            if (name.length>10)        top.window.frames.chat.menu.document.form.too.size=name.length;
            if (name=="") name="Всем";
            top.window.frames.chat.menu.document.form.to.value=name;
            top.window.frames.chat.menu.document.form.too.value=name;
            top.window.frames.chat.menu.document.form.chat_mess.value=top.window.frames.chat.menu.document.form.chat_mess.value;
            top.window.frames.chat.menu.document.form.too.size=10;
        }
        function cha(name)
        {
            top.window.frames.chat.menu.document.form.chat_mess.focus();
            top.window.frames.chat.menu.document.form.chat_mess.value=name+", "+top.window.frames.chat.menu.document.form.chat_mess.value;
            top.window.frames.chat.menu.document.form.to.value="";
            top.window.frames.chat.menu.document.form.too.value="Всем";
            top.window.frames.chat.menu.document.form.too.size=10;
        }
        </script>';
		echo'<span id="chat_text"></span>';
		echo '<script>top.window.frames.chat.users.location.href="index_users.php?&opt=users"</script>';
		break;
}
mysql_close();

if (function_exists("save_debug")) save_debug(); 

?>