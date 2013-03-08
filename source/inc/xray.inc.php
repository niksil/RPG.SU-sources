<?php
require_once("engine.inc.php");
if (!isset($dirclass)) $dirclass="class";
require_once("$dirclass/class_timer.php");
require_once("$dirclass/class_item.php");
require_once("$dirclass/class_anti_mate.php");
require_once("$dirclass/class_npc.php");

$MyTimer = new Timer();
$MyTimer->Init();

include ("define.inc.php");

error_reporting('E_ALL');

//Закоментировал функцию отправки ошибок в ПМ определенному юзеру, т.с. чтобы не пугать нубов. X-ray

/*
function send_error($error,$theme='Ошибка в скриптах')
{
  global $user_id;
  
  if ($theme!='Ошибка в скриптах')
  {
	$kol = mysql_result(mysql_query("SELECT COUNT(*) FROM game_pm WHERE komu=1 AND view=0 AND theme='".mysql_real_escape_string($theme)."'"),0,0);
  }
  else
  {
	$kol = 0;
  }
  if ($kol==0)
	  mysql_query("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('1', '$user_id', '".mysql_real_escape_string($theme)."', '".mysql_real_escape_string($error)."', '0','0',".time().")");
  
	 if ((DOMAIN=='localhost')OR(DOMAIN=='test1.ru')) die($error);
}

*/

set_error_handler('error_handler',E_ALL);

function error_handler($errNo, $errStr, $errFile, $errLine)
{
	$handlers = ob_list_handlers();
	while ( ! empty($handlers) )    {
		ob_end_clean();
		$handlers = ob_list_handlers();
	}
	$error_message = 'ERRNO: '.$errNo.'<br>'.
					 'TEXT: '.$errStr.'<br>'.
		   'LOCATION: '.$errFile.
		   ', line '.$errLine;
	//send_error('<span style="color:red;font-weight:400;font-size:12pt">'.$error_message.'</span>','Ошибка: '.$errNo.' - '.$errFile.', '.$errLine.''); //Для функции send_error X-ray
		echo("<span style=\"color:red;font-weight:400;font-size:12pt\">".$error_message."</span><br>Ошибка: ".$errNo." - ".$errFile.", ".$errLine."");
}

function format_query ($query)
{
  return("<p><b>Query was:</b><br/><textarea cols='50' rows='10'>$query</textarea></p>");
}

$GLOBALS['numsql'] = 0;
$GLOBALS['debuginfo'] = '';
$time_mysql_query = 0;
function myquery ($query)
{
	global $time_mysql_query;
	$backtrace = debug_backtrace();
	$back1 = $backtrace;
	$backtrace = " in : " . $backtrace[0]["file"] . ", on line: " . $backtrace[0]["line"] . "";
	if (DEBUG_RUN==1)
	{
		$MyTimerSQL = new Timer();
		$MyTimerSQL->Init();
		//$result = mysql_query($query) or trigger_error(mysql_errno() . ": <b>" . mysql_error() . $backtrace . format_query($query) , E_USER_ERROR); //это эксперименты админов, можно не трогать. X-ray
	    //$result = mysql_query($query) or send_error(mysql_errno() . ": <b>" . mysql_error() . $backtrace . "<br /><br /><p>Query: ".$query."</p>", $backtrace); //для функции send_error X-ray
		$result = mysql_query($query) or trigger_error(mysql_errno().": <b>" . mysql_error() . $backtrace . "<br /><br /><p>Query: ".$query."</p>", $backtrace);
		$exec_time_mysql = $MyTimerSQL->GetTime(5);

		$GLOBALS['numsql']++;
		$time_mysql_query+=$exec_time_mysql;
		$GLOBALS['debuginfo'].='<tr><td>'.$query.'</td><td><span style="color:#C0FFFF">'.$exec_time_mysql.'</span></td><td><span style="color:lightgrey">'.$backtrace.'</span></tr>';
		if (isset($GLOBALS['debug'][$back1[0]['file']]))
		{
			$GLOBALS['debug'][$back1[0]['file']]['time_sql']+=$exec_time_mysql;
			$GLOBALS['debug'][$back1[0]['file']]['count_sql']+=1;
		}
	}
	else
	{
		$result = mysql_query($query);
	}
/*
	if (strpos($query,"EXP")!==FALSE AND strpos($query,"game_users")!==FALSE AND strpos($query,"UPDATE")!==FALSE)
	{
		mysql_query("INSERT INTO query_log (query,timestamp,filename) VALUES ('$query',".time().",'$backtrace')");
	}
*/
	return($result);
}

function start_debug()
{
	if (DEBUG_RUN==1)
	{
		$backtrace = debug_backtrace();
		list($micro_seconds, $seconds) = explode(" ", microtime());
		$micro = (float)$micro_seconds + (float)$seconds;
		$GLOBALS['debug'][$backtrace[0]['file']]['time_script']=$micro;
		$GLOBALS['debug'][$backtrace[0]['file']]['time_sql']=0;
		$GLOBALS['debug'][$backtrace[0]['file']]['count_sql']=0;
		$GLOBALS['debug'][$backtrace[0]['file']]['script_name']=$backtrace[0]['file'];
		$GLOBALS['debug'][$backtrace[0]['file']]['backtrace']=gzcompress(serialize($_REQUEST),9);
	}
}

function save_debug()
{
	global $_SERVER;
	if (DEBUG_RUN>0)
	{
		$backtrace = debug_backtrace();
		if (isset($GLOBALS['debug'][$backtrace[0]['file']]))
		{
		  $ar = $GLOBALS['debug'][$backtrace[0]['file']];
		  //mysql_close();
		  $db_stat = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
		  mysql_select_db(PHPRPG_DB_NAME,$db_stat) or die(mysql_error());
		  list($micro_seconds, $seconds) = explode(" ", microtime());
		  $micro = (float)$micro_seconds + (float)$seconds;
		  $micro = number_format($micro - $ar['time_script'], 6, '.', '');
		  if (DEBUG_RUN>1)
		  {
			mysql_query("INSERT DELAYED INTO game_debug (id,script_name,time_script,count_sql,time_sql,savetime,backtrace) VALUES ('','".$ar['script_name']."','".$micro."','".$ar['count_sql']."','".$ar['time_sql']."','".time()."','".$ar['backtrace']."')",$db_stat);
		  }
		  mysql_close($db_stat);

		  $db = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
		  mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());
		}
	}
}

function show_debug()
{
	if (DEBUG_RUN>0)
	{
	?>
	<script language="JavaScript" type="text/javascript" src="../js/cookies.js"></script>
	<script language="JavaScript" type="text/javascript">
	function debuginfo()
	{
		el = document.getElementById("debuginfo");
		img = document.getElementById("imgdebug");
		var expDate = getExpDate(180,0,0);
		if (el.style.display=='none')
		{
			el.style.display='block';
			img.src='http://<?=IMG_DOMAIN;?>/admin/collapse_thead.gif';
			var data="1";
		}
		else
		{
			el.style.display='none';
			img.src='http://<?=IMG_DOMAIN;?>/admin/collapse_thead_collapsed.gif';
			var data="0"
		}
		setCookie("DebugInfo", data, expDate)
	}
	</script>
	<?
	$GLOBALS['debuginfo'] = str_replace('select','<SPAN STYLE="COLOR:#FFFF35">SELECT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('SELECT','<SPAN STYLE="COLOR:#FFFF35">SELECT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('update','<SPAN STYLE="COLOR:#FFFF35">UPDATE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('UPDATE','<SPAN STYLE="COLOR:#FFFF35">UPDATE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('delete','<SPAN STYLE="COLOR:#FFFF35">DELETE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('DELETE','<SPAN STYLE="COLOR:#FFFF35">DELETE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('from','<SPAN STYLE="COLOR:#FFFF35">FROM</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('FROM','<SPAN STYLE="COLOR:#FFFF35">FROM</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('where','<SPAN STYLE="COLOR:#FFFF35">WHERE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('WHERE','<SPAN STYLE="COLOR:#FFFF35">WHERE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('UNION','<SPAN STYLE="COLOR:#FFFF35">UNION</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('union','<SPAN STYLE="COLOR:#FFFF35">UNION</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('set','<SPAN STYLE="COLOR:#FFFF35">SET</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('SET','<SPAN STYLE="COLOR:#FFFF35">SET</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' LIMIT','<SPAN STYLE="COLOR:#FFFF35"> LIMIT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' limit','<SPAN STYLE="COLOR:#FFFF35"> LIMIT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('insert','<SPAN STYLE="COLOR:#FFFF35">INSERT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('INSERT','<SPAN STYLE="COLOR:#FFFF35">INSERT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' into','<SPAN STYLE="COLOR:#FFFF35"> INTO</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' INTO','<SPAN STYLE="COLOR:#FFFF35"> INTO</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' left','<SPAN STYLE="COLOR:#FFFF35"> LEFT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' LEFT','<SPAN STYLE="COLOR:#FFFF35"> LEFT</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' join','<SPAN STYLE="COLOR:#FFFF35"> JOIN</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' JOIN','<SPAN STYLE="COLOR:#FFFF35"> JOIN</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' on','<SPAN STYLE="COLOR:#FFFF35"> ON</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace(' ON','<SPAN STYLE="COLOR:#FFFF35"> ON</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('duplicate','<SPAN STYLE="COLOR:#FFFF35"> DUPLICATE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('DUPLICATE','<SPAN STYLE="COLOR:#FFFF35"> DUPLICATE</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('key','<SPAN STYLE="COLOR:#FFFF35"> KEY</SPAN>',$GLOBALS['debuginfo']);
	$GLOBALS['debuginfo'] = str_replace('KEY','<SPAN STYLE="COLOR:#FFFF35"> KEY</SPAN>',$GLOBALS['debuginfo']);
	if (isset($_COOKIE['DebugInfo']) AND $_COOKIE['DebugInfo']=='1')
	{
		$disp = 'block';
		$img = 'collapse_thead.gif';
	}
	else
	{
		$disp = 'none';
		$img = 'collapse_thead_collapsed.gif';
	}
	echo '<div onClick="debuginfo();" style="cursor:url(\'/images/nav/hand.cur\'), pointer;background-color:black;color:white;spacing:2px;border:solid 2px;padding:5px;font-family:Verdana,Arial;font-size:12px;font-weight:800;">Debug Info <img id="imgdebug" src="http://'.IMG_DOMAIN.'/admin/'.$img.'" border=0 onClick="debuginfo();"> | Module_id: ?'./*MODULE_ID.*/'</div><table id="debuginfo" style="width:auto;height:auto;overflow:auto;display:'.$disp.';background-color:#333333;color:white;spacing:2px;border:solid 2px;padding:5px;font-family:Verdana,Arial;font-size:10px;font-weight:400;">'.$GLOBALS['debuginfo'].'<tr><td colspan="3"><hr>POST</td></tr><tr><td colspan="3"><pre>'.print_r($_POST,true).'</pre></td></tr><tr><td colspan="3"><hr>GET</td></tr><tr><td colspan="3"><pre>'.print_r($_GET,true).'</pre></td></tr>';
	if (isset($_SESSION))
	{   
		echo'<tr><td colspan="3"><hr>SESSION</td></tr><tr><td colspan="3"><pre>'.print_r($_SESSION,true).'</pre></td></tr>';
	}
	echo '</table>';
	}
}

function clear_freechat($char)
{
	//clear new chat
}


function flag_debug($str)
{
	$say = iconv("Windows-1251","UTF-8//IGNORE","ОТЛАДКА: <span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$str."</b></span>");
	myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".$say."',".time().",-1)");
}
?>
