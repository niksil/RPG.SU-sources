<?
$dirclass = "../class";
require_once("../inc/engine.inc.php");
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

session_start();

echo '
<form name="email" method="post" action="">
Тема: <input type="text" name="subject" size="80" maxsize="200"><br />
Содержание: <textarea name="message" rows=30 cols=80>
</textarea><br />
Отправлять для игроков с user_id><input type="text" size="10" maxsize="10" name="from_id"';
if (isset($_SESSION['send_email_user_id']))
{
    echo ' value="'.($_SESSION['send_email_user_id']).'"';
}
echo '>
<input type="submit" name="send" value="Отправить письмо всем игрокам">
</form>
';

if (isset($_POST['send']) AND isset($_POST['message']))
{
    $from_id = (int)$_POST['from_id'];
    $sel_users = myquery("(SELECT game_users.user_id,game_users.name,game_users.clevel,game_har.name AS race,game_users_data.email FROM game_users,game_har,game_users_data WHERE game_users.user_id=game_users_data.user_id AND game_har.id=game_users.race AND game_users_data.send_mail='1' AND game_users.user_id>$from_id ORDER BY game_users.user_id) UNION (SELECT game_users_archive.user_id,game_users_archive.name,game_users_archive.clevel,game_har.name AS race,game_users_data.email FROM game_users_archive,game_har,game_users_data WHERE game_users_archive.user_id=game_users_data.user_id AND game_har.id=game_users_archive.race AND game_users_data.send_mail='1' AND game_users_archive.user_id>$from_id ORDER BY game_users_archive.user_id)");
    
    while ($usr = mysql_fetch_array($sel_users))
    {           
        $msg = 'Привет, '.$usr['name'].'!
        
        
        Администрация игры "'.GAME_NAME.'" сообщает тебе о новостях нашей игры:
        
        
        '.trim($_POST['message']).'
        
                
        PS. Данное письмо не является спамом, т.к. ты сам дал разрешение администрации присылать письма на твой электронный адрес. Если ты больше не хочешь получать письма от нас - эту настройку можно отключить. Для этого надо зайти в игру и в личных настройках игрока снять галку "Установи флажок чтобы получать от администрации письма на e-mail, указанный при регистрации: <'.$usr['email'].'>".
        ';
        if (isset($_POST['subject'])) $subject = $_POST['subject'];
        else $subject = 'Новости игры '.GAME_NAME.' - http://'.DOMAIN.'';
        
        	$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";

                mail($usr['email'], $subject, $msg, $headers); 

        echo 'user_id='.$usr['user_id'].'<br />';
        $_SESSION['send_email_user_id'] = $usr['user_id'];
    }
}                                    
?>