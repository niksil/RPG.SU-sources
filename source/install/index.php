<?
	if (file_exists("../inc/engine.inc.php"))
{
echo "Скрипт уже установлен! <br> Для переустановки удалите файл engine.inc.php";
} else {
clearstatcache ();
include('source/class/install.class.php');

ini_set('url_rewriter.tags', 'none');
session_start();
// Сто дней хранить куки
$expiry = 60*60*24*1;
setcookie(session_name(), session_id(), time()+$expiry, "/");

$install = new install();
if ((isset($_GET['install'])) && ($_GET['install'] == 'start'))
{
	set_time_limit(0);
	$install->install();
	die;
}
header("Content-Type: text/html; charset=windows-1251");
$html = $install->start();
print $html;
}
?>