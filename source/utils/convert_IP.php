<?
$dirclass="../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
//DbConnect();

echo '<form action="" method="post"><input type="text" name="host" size=25><input type="submit" name="runIP" value="IP->число"></form><br>';
echo '<form action="" method="post"><input type="text" name="host1" size=25><input type="submit" name="runNumber" value="число->IP"></form>';

if (isset($runIP))
{
	echo 'IP = '.$host.'<br>';
	echo 'number = '.ip2number($host).'<br>';
	echo 'IP-number = '.number2ip(ip2number($host)).'<br>';
}
if (isset($runNumber))
{
	echo 'number = '.$host1.'<br>';
	echo 'IP = '.number2ip($host1).'<br>';
	echo 'number = '.ip2number(number2ip($host1)).'<br>';
}
?>