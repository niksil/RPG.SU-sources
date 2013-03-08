<?
require_once('engine.inc.php');
$path="".PATCH."/data";

function file_del($f, $t)
{
global $path;
$file = file("$path/$f.dat");
$num = count($file);
for($i=0;$i<=$num-1;$i++)
	{
	$row = explode("|",$file[$i]);   
	if($row[0]==$t)
		{
   		unset($file[$i]);
    		$fp1=fopen("$path/$f.dat","w"); 
    		fwrite($fp1,implode("",$file)); 
    		fclose($fp1);
   		}
	}
}


function file_add($f, $t)
{
global $path;
$file = fopen("$path/$f.dat", "a+");
flock($file,2);
fwrite($file,"$t\r\n");
flock($file,3);
fclose($file);
}


function log_add($t)
{
global $path;
global $char;
$nn=''.date('m').'/'.$char['user_id'].'-'.$char['name'].'';
$file = fopen("$path/logs/users/$nn.dat", "a+");
flock($file,2);
fwrite($file,"$t\r\n");
flock($file,3);
fclose($file);
}



function timer($t, $l, $v)
{
echo'<font color=red><b><span id="timerr1">'.($t-time()+$v).'</span></b></font> секунд
<script language="JavaScript" type="text/javascript">
function tim()
timer1 = document.getElementById("timerr1");	
{if (timer1.innerHTML<=0)
location.reload("'.$l.'");else{
timer1.innerHTML=timer1.innerHTML-1;
window.setTimeout("tim()",1000);}}tim();
</script>';
}
$date = Date("m.d H:i:s");
?>