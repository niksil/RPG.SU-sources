<?
//ob_start('ob_gzhandler',9);
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '8');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

echo '<html><head><title>'.GAME_NAME.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">@import url("combat.css");</style></head><body>';

if (!isset($type)) $type='chat';

switch($type)
{
    case 'chat':
        $_SESSION['arcomage_chat_id']=0;

	    echo '
        <script language="JavaScript">
        function refresh_chat()
        {
	        top.window.frames.chat.location.reload();
        }
        </script>            
            <table height="168" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr height="14">
                <td width="15" background="http://'.IMG_DOMAIN.'/nav/1_01.jpg" >&nbsp;</td>
                <td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg">&nbsp;</td>
                <td width="15" background="http://'.IMG_DOMAIN.'/nav/1_05.jpg">&nbsp;</td>
              </tr>
              <tr>
                <td width="5" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg">&nbsp;</td>
                <td bgcolor="#000000">
                    <table height="100%" border="0" width="100%" cellpadding="0">
                    <tr>
                      <td height="100%" rowspan="2" width="150">
						<iframe height="135" name="users" width="100%" frameborder="1" src=""></iframe>
                      </td>
                      <td height="100%">
                        <iframe height="115" name="chat_f" scrolling="yes" width="100%" frameborder="1" src="?type=talk"></iframe>
                      </td>
                    </tr>
                    <tr height="20">
					  <form autocomplete="off" action="?type=users" method="post" target="users" name="form1">
                      <td valign="bottom">
                        <input name="type" type="hidden" value="users">
                        <input name="textg" type="text" size="60">&nbsp;
                        <input type="submit" value="Сказать">&nbsp;&nbsp;&nbsp;
                        [<a href="#" onClick=refresh_chat()>Обн.чат</a>]
                      </td>
                      </form>
                    </tr>
                    </table>
                </td>
                <td width="15" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg">&nbsp;</td>
              </tr>
              <tr height="14">
                <td background="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15">&nbsp;</td>
                <td background="http://'.IMG_DOMAIN.'/nav/1_25.jpg">&nbsp;</td>
                <td background="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15">&nbsp;</td>
              </tr>
			</table>';
        break;


    case 'users':
	{
        include("chatusers.inc.php");
	}
    break;

	case 'talk':
    {
		include("talk.inc.php");
    }
    break;
}
echo '</body></html>';
if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
    show_debug();
}

if (function_exists("save_debug")) save_debug(); 

?>