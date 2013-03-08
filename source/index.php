<?php
//ob_start('ob_gzhandler',9);
if (file_exists("inc/engine.inc.php"))
{
function LoginUser($user)
{
	
	global $user_host,$user_host_more,$user_name;
	if (empty($HTTP_USER_AGENT))
	{
		$HTTP_USER_AGENT = getenv('HTTP_USER_AGENT');
	}
	if (empty($HTTP_REFERER))
	{
		$HTTP_REFERER = getenv('HTTP_REFERER');
	}
	$error_log=0;
	
	$user_id = $user['user_id'];
	$userr=mysql_query("delete from game_ban where time<=".time()." and time!='-1'");
	$userr=mysql_query("select * from game_ban where (((user_id='$user_id' or ip='$user_host') and type=0) or (type=1 and user_id<='$user_host' and ip>='$user_host')) and (time>".time()." or time='-1')");
	
	if (mysql_num_rows($userr))
	{		
		$userr=mysql_fetch_array($userr);		
		if ($userr['time']=='-1')
		{			
			Header("Location:".$_SERVER['PHP_SELF']."?error=ban&time=-1");		
			$error_log=1;			
		}
		else
			if ($userr['ip']==$user_host and $userr['type']==0)
			{
				Header("Location:".$_SERVER['PHP_SELF']."?error=banip&time=".($userr['time']-time())."");
				$error_log=1;
			}
			elseif ($userr['ip']>=$user_host and $userr['user_id']<=$user_host and $userr['type']==1)
			{				
				Header("Location:".$_SERVER['PHP_SELF']."?error=banip&time=".($userr['time']-time())."");
				$error_log=1;
			}
			elseif ($userr['user_id']==$user_id and $userr['type']==0)
			{
				Header("Location:".$_SERVER['PHP_SELF']."?error=ban&time=".($userr['time']-time())."");
				$error_log=1;
			}
	}
	$try = mysql_result(mysql_query("SELECT COUNT(*) FROM game_login WHERE user_name='$user_name' AND host=$user_host"),0,0);
	if($try>=10)
	{
		Header("Location:".$_SERVER['PHP_SELF']."?error=try");
		//$error_log=1;
	}
	list($only_IP,$only_IP_number) = mysql_fetch_array(mysql_query("SELECT only_IP,only_IP_number FROM game_users_data WHERE user_id=".$user['user_id'].""));
	if ($only_IP==1)
	{
		if ($only_IP_number!=$user_host)
		{
			Header("Location:".$_SERVER['PHP_SELF']."?error=ip");
			$error_log=1;
		}
	}
	
	mt_srand((double)microtime()*100000);
	$sess = md5(uniqid(mt_rand()));
	$s='';
	session_name("rpgsu_sess");
	session_id($sess); 
	session_start();

	$_SESSION['user_id'] = $user['user_id'];
	$_SESSION['user_time'] = time();
	$_SESSION['user_host_ip'] = $user_host;

	setcookie("rpgsu_login", $user['user_name'],0,"/");
	setcookie("rpgsu_pass",md5($user['user_pass']),0,"/");
	setcookie("rpgsu_sess", $sess,0,"/");

	$result = mysql_query("INSERT INTO game_activity (time, day, hour, agent, host, ref, name, host_more) VALUES ('".time()."','".date('Y-m-d',time())."','".date('H:i', time())."','".$HTTP_USER_AGENT."','$user_host','".$HTTP_REFERER."','".$user['name']."','".$user_host_more."')");

	$link='main.php';
	
	$sel_prev_active = myquery("SELECT last_active FROM game_users_active WHERE user_id=$user_id");
	if ($sel_prev_active!=false AND mysql_num_rows($sel_prev_active)>0)
	{
		$prev_active = mysql_result($sel_prev_active,0,0);
	}
	else
	{
		$prev_active = 0;
	}
	$user_time = time();
	list($last_visit) = mysql_fetch_array(mysql_query("SELECT last_visit FROM game_users_data WHERE user_id='".$user['user_id']."'"));
	$razn_time = $user_time - $prev_active;
	if ($razn_time>250 AND $last_visit!=0)
	{
		list($map_id) = mysql_result(myquery("SELECT map_name FROM game_users_map WHERE user_id=".$user['user_id'].""),0,0);
		if ($map_id==35)
		{
			//игрок на колизее и возможно читерит
			myquery("UPDATE game_users_map SET map_name=18 WHERE user_id=".$user_id['user_id']."");
		}
	}
	if ($razn_time>=360)
	{
		$maze_id = "(691,692,804)";
		//сделаем апдейт STM,MP,HP со времени последней игры
		$event_cycles = floor($razn_time/60);
		if ($user['STM']<$user['STM_MAX'])
		{
			myquery("
			UPDATE game_users,game_users_map
			SET game_users.STM = game_users.STM + CEILING(8 * game_users.DEX / 3) * $event_cycles 
			WHERE (game_users.STM/game_users.STM_MAX) > 0.6 
			AND game_users.STM != game_users.STM_MAX
			AND game_users.user_id=game_users_map.user_id
			AND game_users_map.map_name NOT IN ".$maze_id."
			AND game_users.user_id=$user_id");

			myquery("
			UPDATE game_users,game_users_map
			SET game_users.STM = game_users.STM + CEILING(7 * game_users.DEX / 3) * $event_cycles 
			WHERE (game_users.STM/game_users.STM_MAX) > 0.3 
			AND (game_users.STM/game_users.STM_MAX) <= 0.6
			AND game_users.user_id=game_users_map.user_id
			AND game_users_map.map_name NOT IN ".$maze_id."
			AND game_users.user_id=$user_id");

			myquery("
			UPDATE game_users,game_users_map 
			SET game_users.STM = game_users.STM + CEILING(6 * game_users.DEX / 3) * $event_cycles 
			WHERE (game_users.STM/game_users.STM_MAX) > 0.15 
			AND (game_users.STM/game_users.STM_MAX) <= 0.3
			AND game_users.user_id=game_users_map.user_id
			AND game_users_map.map_name NOT IN ".$maze_id."
			AND game_users.user_id=$user_id");

			myquery("
			UPDATE game_users,game_users_map
			SET game_users.STM = game_users.STM + CEILING(5 * game_users.DEX / 3) * $event_cycles 
			WHERE (game_users.STM/game_users.STM_MAX) <= 0.15 
			AND game_users.user_id=game_users_map.user_id
			AND game_users_map.map_name NOT IN ".$maze_id."
			AND game_users.user_id=$user_id");
			
			myquery("UPDATE game_users,game_users_map
			SET game_users.HP = game_users.HP + ROUND(game_users.DEX / 6) * $event_cycles
			WHERE game_users.DEX>=0
			AND game_users.user_id=game_users_map.user_id
			AND game_users_map.map_name NOT IN ".$maze_id."
			AND game_users.user_id=$user_id");
			
			myquery("UPDATE game_users,game_users_map
			SET game_users.MP = game_users.MP + ROUND(game_users.NTL / 8) * $event_cycles/2
			WHERE game_users.NTL>=0
			AND game_users.user_id=game_users_map.user_id
			AND game_users_map.map_name NOT IN ".$maze_id."
			AND game_users.user_id=$user_id");
		}
	}

	//Пропишем последний визит игрока  
	mysql_query("INSERT INTO game_users_active SET last_active='$user_time', host='$user_host', user_id='$user_id' ON DUPLICATE KEY UPDATE last_active='$user_time',host='$user_host'"); 
	mysql_query("INSERT INTO game_users_active_host SET host_more='$user_host_more', user_id='$user_id' ON DUPLICATE KEY UPDATE host_more='$user_host_more'");
	mysql_query("INSERT INTO game_users_active_delay SET delay_reason='0', user_id='$user_id' ON DUPLICATE KEY UPDATE delay_reason='0'");
	mysql_query("INSERT IGNORE INTO game_users_func (`user_id`,`func_id`) VALUES ('".$user_id."','5')");
	mysql_query("INSERT IGNORE INTO game_users_data SET user_id='$user_id'");

	$check = mysql_result(mysql_query("SELECT COUNT(*) FROM game_users_map WHERE user_id='$user_id'"),0,0);
	if ($check==0) mysql_query("INSERT INTO game_users_map SET user_id='$user_id',map_name=18,map_xpos=26,map_ypos=27");

	list($last_visit) = mysql_fetch_array(mysql_query("SELECT last_visit FROM game_users_data WHERE user_id='".$user['user_id']."'"));
	mysql_query("UPDATE game_users_data SET last_visit='$user_time' WHERE user_id='$user_id' LIMIT 1");
	//Пропишем возраст игрока
	if (date("d.m.Y",$last_visit)!=date("d.m.Y",$user_time))
	{
		//уменьшим остаток жизни лошадки
		myquery("UPDATE game_users_horses SET life=GREATEST(0,life-1), golod=golod+1  WHERE user_id=$user_id");
		//проверим на смерть лошадки
		$selhorse = myquery("SELECT * FROM game_users_horses WHERE user_id=$user_id AND life=0");
		while($h = mysql_fetch_array($selhorse))
		{
			kill_horse($h['id']);
		}
		$da = getdate();
		$result = mysql_query("UPDATE game_users_data SET vozrast=vozrast+1, month_visits=month_visits+1 WHERE user_id='$user_id' LIMIT 1");
		$chck = mysql_query("SELECT * FROM game_clans_vozrast WHERE clan_id=".$user['clan_id']." AND month=".$da['mon']." AND year=".$da['year']." AND user_id=$user_id");
		if (mysql_num_rows($chck))
		{
			mysql_query("UPDATE game_clans_vozrast SET vozrast=vozrast+1 WHERE clan_id=".$user['clan_id']." AND month=".$da['mon']." AND year=".$da['year']." AND user_id=$user_id");
		}
		else
		{
			mysql_query("INSERT INTO game_clans_vozrast (clan_id,month,year,vozrast,user_id) VALUES (".$user['clan_id'].",".$da['mon'].",".$da['year'].",1,$user_id)");
		}
	}
    /*
	$max_users = (int)mysqlresult(myquery("SELECT step FROM game_cron_log WHERE cron='max_users'"),0,0);
	$curr_users = max(0,mysqlresult(myquery("SELECT COUNT(*) FROM view_active_users"),0,0));
	if ($curr_users>$max_users)
	{
		myquery("UPDATE game_cron_log SET step=$curr_users,timecron=".time()." WHERE cron='max_users'");
	}
    */
	//сообщим в приват от нафани всем сокланам о приходе игрока
	if ($user['clan_id']>0 AND (time()-$last_visit>=600) AND $user['clevel'] >= 8)
	{
		$sex = mysql_result(myquery("SELECT sex FROM game_users_data WHERE user_id=$user_id"),0,0);
		$pismo = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-size:12px;color:#C0FFC0;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">В игру ".echo_sex('вошел','вошла',$sex)." <b>".$user['name']."</b></span>");
		mysql_query("INSERT INTO game_log (`message`,`date`,`fromm`,`too`,`ptype`) VALUES ('".$pismo."',".time().",-1,".$user['clan_id'].",2)");
	}
    
    $obnul_free = mysql_result(mysql_query("SELECT obnul_free FROM game_users_data WHERE user_id=$user_id"),0,0);
    if ($obnul_free>0)
    {
        $link = "obnul.php";
    }

	//Если игрок заходит в первый раз - откроем всплывающее сообщение
	if ($error_log==0)
	{
		if ($last_visit==0)
		{
			?><script>
			window.open("help.htm","help","width=650,height=550");
			top.location.replace("<?=$link;?>");
			</script><?
		}
		else
		{
			Header("Location:$link");
		}
	}
	mysql_close();
}
function generate_password($number)  
{  
    $arr = array('a','b','c','d','e','f',  
                 'g','h','i','j','k','l',  
                 'm','n','o','p','r','s',  
                 't','u','v','x','y','z',  
                 'A','B','C','D','E','F',  
                 'G','H','I','J','K','L',  
                 'M','N','O','P','R','S',  
                 'T','U','V','X','Y','Z',  
                 '1','2','3','4','5','6',  
                 '7','8','9','0','.',',',  
                 '(',')','[',']','!','?',  
                 '&','^','%','@','*','$',  
                 '<','>','/','|','+','-',  
                 '{','}','`','~');  
    // Генерируем пароль  
    $pass = "";  
    for($i = 0; $i < $number; $i++)  
    {  
      // Вычисляем случайный индекс массива  
      $index = rand(0, count($arr) - 1);  
      $pass .= $arr[$index];  
    }  
    return $pass;  
}  
  
function PrintRace()
{
	$r = mysql_query("select name, race, hp, mp, stm, exp, gp, str, ntl, pie, vit, dex, spd, skill_war, skill_cook, skill_art, skill_explor, skill_craft, skill_card, skill_uknow, skill_music,  skill_pet  from game_har where disable=0") or die(mysql_error());
	while (list($name, $race, $hp, $mp, $stm, $exp, $gp, $str, $ntl, $pie, $vit, $dex, $spd, $skill_war, $skill_cook, $skill_art, $skill_explor, $skill_craft, $skill_card, $skill_uknow, $skill_music,  $skill_pet)=mysql_fetch_array($r))
	{
		echo '<tr><td width="25%"><img src="http://'.IMG_DOMAIN.'/info/'.$race.'.gif" width="145" height="196"></td><td width="81%">';
		echo'Раса: '.$name.'<br>Жизнь: '.$hp.'<br>Мана: '.$mp.'<br>Энергия: '.$stm.'<br>Золото: '.$gp.'<br><br>Сила: '.$str.'<br>Интеллект: '.$ntl.'<br>Ловкость: '.$pie.'<br>Защита: '.$vit.'<br>Выносливость: '.$dex.'<br>Мудрость: '.$spd.'
		<br><br>Доступны магические школы: ';
		if ($skill_war>=30) echo '<font color=ff0000><b>Воин</b></font>, '; elseif ($skill_war>=10) echo 'Воин, ';
		if ($skill_cook>=30) echo '<font color=ff0000><b>Волшебник</b></font>, '; elseif ($skill_cook>=10) echo 'Волшебник, ';
		if ($skill_art>=30) echo '<font color=ff0000><b>Лучник</b></font>, '; elseif ($skill_art>=10) echo 'Лучник, ';
		if ($skill_explor>=30) echo '<font color=ff0000><b>Паладин</b></font>, '; elseif ($skill_explor>=10) echo 'Паладин, ';
		if ($skill_craft>=30) echo '<font color=ff0000><b>Варвар</b></font>, '; elseif ($skill_craft>=10) echo 'Варвар, ';
		if ($skill_card>=30) echo '<font color=ff0000><b>Вор</b></font>, '; elseif ($skill_card>=10) echo 'Вор, ';
		if ($skill_uknow>=30) echo '<font color=ff0000><b>Разбойник</b></font>, '; elseif ($skill_uknow>=10) echo 'Разбойник, ';
		if ($skill_music>=30) echo '<font color=ff0000><b>Бард</b></font>, '; elseif ($skill_music>=10) echo 'Бард, ';
		if ($skill_pet>=30) echo '<font color=ff0000><b>Друид</b></font>, '; elseif ($skill_pet>=10) echo 'Друид, ';
		echo'</td></tr>';
	}
}

function ForgetPassword()
{
	global $_POST;

	echo'
	<center>
	<table width="100%" height=10 border="0" cellspacing="0" cellpadding="0" align=center>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="23" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><div align="center"><br><strong>'.GAME_NAME.' :: Восстановление пароля</strong></div></td>
	<td width="70" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="10"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr>
	<tr>
	<td width="15" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3">

	<center>
	<table width="100%" height="100%"  border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
	</tr>
	<tr>
	<td width="5" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
	<td bgcolor="313131"><center>';

	if (!isset($_POST['save']))
	{
		echo'<form action="" method="post"><b>ВАЖНО!</b> При востановлении пароля он автоматически меняется на новый.<br><br>';
		echo'Логин: <input type=text name=user_name>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
		echo'Email: <input type=text name=email><br><br><input type=submit value="Выслать новый пароль"><input name="save" type="hidden" value="">';
	}
	else
	{
		@$user_name = $_POST['user_name'];
		@$email = $_POST['email'];

		$sel=mysql_query("select user_id, user_name from game_users where user_name='$user_name' limit 1");
		if (!mysql_num_rows($sel))
		{
			$sel=mysql_query("select user_id, user_name from game_users_archive where user_name='$user_name' limit 1");
		}
		if (mysql_num_rows($sel))
		{
			$user=mysql_fetch_array($sel);
			$email_user = mysql_result(mysql_query("SELECT email FROM game_users_data WHERE user_id='".$user['user_id']."'"),0,0);
			if($email_user==$email)
			{
				$login=mt_rand(1000,999999);
				
			$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";
				
				$message  = "[http://".DOMAIN."] ".GAME_NAME.". Восстановление забытого пароля!\n\n";
				$message .= "Ваши данные:\n";
				$message .= "Логин: $user_name\n";
				$message .= "Новый пароль: $login\n\n";

				$subject = ''.GAME_NAME.' [Восстановление забытого пароля]';

                mail($email, $subject, $message, $headers); 

				echo'На почту <b><font face=verdana size=2 color=ff0000>'.$email.'</font></b> выслан новый пароль! рекомендуем сразу после входа в игру сменить пароль!';
				$login=md5($login);
				$up=mysql_query("update game_users set user_pass='$login' where user_id='".$user['user_id']."'");
				$up=mysql_query("update game_users_archive set user_pass='$login' where user_id='".$user['user_id']."'");
				$up=mysql_query("update game_users_data set only_IP=0,only_IP_number=0 where user_id='".$user['user_id']."'");
			}
			else
			{
				echo 'Неправильный емайл';
			}
		}
		else
		{
			echo 'Неправильный логин';
		}
	}
	echo'<br><br><center><a href="'.$_SERVER['PHP_SELF'].'">На главную</a>';

	echo '</td>
	<td  width="5" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
	</tr>
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
	<td width="7"><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
	</tr>
	</table>

	</td>
	<td width="10" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg"></td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td width="10"><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr>
	</table>
	';
}

function BeginRegistration()
{
	?>
	<style type="text/css">
	.validate
	{
		display:none;
		color:red;
		font-size:11px;
		text-align:left;
		font-family:Tahoma,Times,Arial,Verdana,sans-serif;
	}
	</style>
	<script type="text/javascript">
	var xmlHttp = createXmlHttpRequestObject();
	var serverAddress = "validate.php";
	var showErrors = true;
	var cache = new Array();

	function createXmlHttpRequestObject()
	{
		var xmlHttp;
		try
		{
			xmlHttp = new XMLHttpRequest();
		}
		catch(e)
		{
			var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
			"MSXML2.XMLHTTP.5.0",
			"MSXML2.XMLHTTP.4.0",
			"MSXML2.XMLHTTP.3.0",
			"MSXML2.XMLHTTP",
			"Microsoft.XMLHTTP");
			for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
			{
				try
				{
					xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
				}
				catch (e) {}
			}
		}
		if (!xmlHttp)
			alert("Error creating the XMLHttpRequest object.");
		else
			return xmlHttp;
	}

	function displayError($message)
	{
		if (showErrors)
		{
			showErrors = false;
			alert("Обнаружена ошибка: \n" + $message);
			setTimeout("validate()",10000);
		}
	}

	function validate(inputValue,fieldId)
	{
		if (xmlHttp)
		{
			if (inputValue && fieldId)
			{
				inputValue = encodeURIComponent(inputValue);
				filedId = encodeURIComponent(fieldId);
				cache.push("inputValue=" + inputValue + "&fieldId=" + fieldId);
			}
			try
			{
				if ((xmlHttp.readyState == 4 || xmlHttp.readyState == 0) && cache.length>0)
				{
					var CacheEntry = cache.shift();
					xmlHttp.open("POST", serverAddress, true);
					xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xmlHttp.onreadystatechange = handleRequestStateChange;
					xmlHttp.send(CacheEntry);
				}
			}
			catch(e)
			{
				displayError(e.toString());
			}
		}
	}

	function handleRequestStateChange()
	{
		if (xmlHttp.readyState == 4)
		{
			if (xmlHttp.status == 200)
			{
				try
				{
					readResponse();
				}
				catch(e)
				{
					displayError(e.toString());
				}
			}
			else
			{
				displayError(xmlHttp.statusText);
			}
		}
	}

	function readResponse()
	{
		var response = xmlHttp.responseText;
		if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0) throw(response.length == 0 ? "Server error." : response);
		responseXml = xmlHttp.responseXML;
		xmlDoc = responseXml.documentElement;
		if (xmlDoc.hasChildNodes())
		{
		result = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
		fieldId = xmlDoc.getElementsByTagName("fieldid")[0].firstChild.data;
		message = document.getElementById(fieldId + "_error1");
		if (message) message.style.display = (result == "1") ? "block" : "none";
		message = document.getElementById(fieldId + "_error2");
		if (message) message.style.display = (result == "2") ? "block" : "none";
		message = document.getElementById(fieldId + "_error3");
		if (message) message.style.display = (result == "3") ? "block" : "none";
		}
		setTimeout("validate()",10000);
	}

	function setFocus()
	{
		document.getElementById("user_name").focus();
	}
	
	function check_form()
	{
		form = document.forms['registration'];
		error = 0;
		if (form.user_name.value=='')
		{
			form.user_name.focus();
			error=error+1;
			alert('Необходимо ввести логин для входа в игру!');
		}
		if (form.name.value==''&&error==0)
		{
			form.name.focus();
			error=error+1;
			alert('Необходимо ввести игровое имя вашего персонажа!');
		}
		/*if (form.user_pass1.value==''&&error==0)
		{
			form.user_pass1.focus();
			error=error+1;
			alert('Необходимо ввести пароль для входа в игру!');
		}
		if (form.user_pass2.value==''&&error==0)
		{
			form.user_pass1.focus();
			error=error+1;
			alert('Необходимо повторить пароль для входа в игру!');
		}*/
		if (form.email.value==''&&error==0)
		{
			form.email.focus();
			error=error+1;
			alert('Необходимо указать ваш e-mail адрес!');
		}
		if (form.sogl.checked==false&&error==0)
		{
			form.sogl.focus();
			error=error+1;
			alert('Вы не согласны с Законами. Мы не можем Вас зарегистрировать!');
		}
		if (form.captcha.value==''&&error==0)
		{
			form.captcha.focus();
			error=error+1;
			alert('Необходимо указать кодовое число, изображенное на картинке!');
		}
		if (error==0)
		{
			form.submit();
		}
	}
	</script>
	<?php
	echo '<center>';
	echo '<form name="registration"action="'.$_SERVER['PHP_SELF'].'?option=reg" method="post" autocomplete="off">';

	echo '
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
	<tr>
	<td width="0"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="0" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><div align="center"><br><strong>'.GAME_NAME.' :: Регистрация</strong></div></td>
	<td width="70" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="0"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr>
	<tr>
	<td width="15" height="90" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3">
	<div align="center">

	<table width="100%" height="50"  border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
	</tr>
	<tr>
	<td width="5" height="50" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
	<td height="50" bgcolor="313131">';
	if (isset($error_msg))
	{
		echo '<center><b><font color=ff0000>'.$error_msg.'</font></b></center>';
	}
	echo '<table cellpadding="5" cellspacing="0" border="0" width="100%"><tr><td width="190">
	<div align="right">Логин:</div></td>
	<td width="130">
	<input tabindex="1" checkit=0 type="text" id="user_name" name="user_name" maxlength="20" size="24" class="input" onblur="validate(this.value,this.id)" value="';
		if (isset($_SESSION['values']['user_name'])) echo @$_SESSION['values']['user_name']; echo '">
	<div class="validate" id="user_name_error1">Данный логин уже используется. Выберите другой!</div>
	<div class="validate" id="user_name_error2">Неправильный логин (он должен состоять только из символов латиницы, кириллицы и цифр)!</div>
	<div class="validate" id="user_name_error3">Логин не пропущен цензурой. Выберите другой логин!</div>
	</td>
	<td width="218" align="right">
	<input tabindex="10" type=text name="status" maxlength="20" size="26"class="input" value="';
		if (isset($_SESSION['values']['status'])) echo $_SESSION['values']['status']; echo '">
	</td>
	<td width="190">Ваш статус</td>
	</tr>
	<tr>
	<td width="136" align="right">Игровое имя:</td>
	<td width="130">
	<input tabindex="2" type=text checkit=0 id="name" name="name" maxlength="16" size="24" class="input" onblur="validate(this.value,this.id)" value="';
		if (isset($_SESSION['values']['name'])) echo $_SESSION['values']['name']; echo '">
	<div class="validate" id="name_error1">Данное имя уже используется. Выберите другое!</div>
	<div class="validate" id="name_error2">Неправильное игровое имя (разрешены только символы кириллицы и латиницы)!</div>
	<div class="validate" id="name_error3">Имя не пропущено цензурой. Выберите другое имя!</div>
	</td>
	<td width="218" align="right">
	<input tabindex="11" type="text" name="gorod" maxlength="20" size="26" class="input" value="';
		if (isset($_SESSION['values']['gorod'])) echo $_SESSION['values']['gorod']; echo '">
	</td>
	<td width="190">Город</td>
	</tr>
	<tr>
	<td width="136"><div align="right">Ваш Email (*):</div></td>
	<td width="130">
		<input  tabindex="5" type="text" id="email" name="email" maxlength="60" size="24" class="input" onblur="validate(this.value,this.id)" value="';
		if (isset($_SESSION['values']['email'])) echo $_SESSION['values']['email']; echo '">
		<div class="validate" id="email_error1">Данный email уже используется. Выберите другой!</div>
		<div class="validate" id="email_error2">Неправильный е-майл!</div>
	</td>
	<td width="218" align="right">
	<input  tabindex="12" type="text" name="hobbi" maxlength="20" size="26" class="input" value="';
		if (isset($_SESSION['values']['hobbi'])) echo $_SESSION['values']['hobbi']; echo '">
	</td>
	<td width="190">Ваше хобби</td>
	</tr>
	<tr>
	<td width="136"><div align="right">Дата рождения:</div></td>
	<td width="200">
	<select  tabindex="6" name="dn">
	<option value=0></option>';
	for ($i=1;$i<32;$i++)
	{
			echo '<option';
			if (isset($_SESSION['values']['dn']) AND $i==$_SESSION['values']['dn']) echo ' selected';
			echo '>'.$i.'</option>';
	}
	echo'</select>
	<select  tabindex="7" name="ms">
	<option value=0></option>';
	for ($i=1;$i<13;$i++)
	{
			echo '<option';
			if (isset($_SESSION['values']['ms']) AND $i==$_SESSION['values']['ms']) echo ' selected';
			echo '>'.$i.'</option>';
	}
	echo'</select>
	<select  tabindex="8" name="god">
	<option value=0></option>';
	for ($i=1960;$i<2005;$i++)
	{
			echo '<option';
			if (isset($_SESSION['values']['god']) AND $i==$_SESSION['values']['god']) echo ' selected';
			echo '>'.$i.'</option>';
	}
	echo'</select>
	</td>
	<td rowspan="4" align="right">
	<textarea  tabindex="13" name="info" cols="18" class="input" rows="7">';
		if (isset($_SESSION['values']['info'])) echo $_SESSION['values']['info']; echo '</textarea>
	</td>
	<td width="190">Личная информация</td>
	</tr>
	<tr>
	</tr><tr>';
	echo'
	<td width="136"><div align="right">Ваш пол:</div></td>
	<td width="130">
	<select  tabindex="9" name="sex">
	<option value="male"';
		if (isset($_SESSION['values']['sez']) AND "male"==$_SESSION['values']['sex']) echo ' selected';
		echo '>Мужской</option>
	<option value="female"';
		if (isset($_SESSION['values']['sex']) AND "female"==$_SESSION['values']['sex']) echo ' selected';
		echo '>Женский</option>
	</select>
	</td>
	</tr>
	<tr>
	<td width="136"><div align="right">Ваша Раса:</div></td>
	<td width="130">
	<select tabindex="14" name="avata" id="avat">';
	$res=mysql_query("select * from game_har where disable=0");
	$i=0;
	$ran = mt_rand(1,mysql_num_rows($res));
	while($option=mysql_fetch_array($res))
	{
		$i++;
		echo '<option value="'.$option['race'].'" ';
		if (isset($_SESSION['values']['avata']) AND $option['name']==$_SESSION['values']['avata']) echo ' selected';
		elseif ($i==$ran) echo ' selected';
		echo '>'.$option["name"].'</option>';
	}
	echo'</select></td>
	</tr>
	 <tr>
	<td colspan=4 align=center><font face=verdana color=red size=2>Пароль будет автоматически сгенерирован системой и выслан вам на Email</font></td></tr>
	</table>
	<p>(*) - Администрация оставляет за собой право использовать указанный Вами e-mail для отправки Вам писем уведомительного характера, если не будет другого доступного способа доставить Вам эту информацию. Отказаться от получения таких писем Вы сможете после регистрации в Личных Настройках своего персонажа.</p>
	<center>
	<br>
	<input  tabindex="15" name="sogl" type="checkbox" value="1"> Я согласен с законами игрового мира.<br><br>';
	echo '<br><img src="captcha/index.php?'.time().'">';
	echo '<br><br>Введите кодовое число, изображенное на картинке: <input tabindex="17" autocomplete="off" type="text" value="" name="captcha">';

	echo '<br><br><input tabindex="16" type="button" name="Register" value="Зарегистрироваться" onclick="check_form()"></div>';

	if (isset($_GET['uid']))
	{
		$uid=(int)$_GET['uid'];
		echo'<input name="uid" type="hidden" value="' . $uid . '">';
		$u=mysql_query("select name from game_users where user_id='$uid'");
		if (!mysql_num_rows($u)) $u=mysql_query("select name from game_users_archive where user_id='$uid'");
		if (mysql_num_rows($u))
		{
			list($name)=mysql_fetch_array($u);
			$host = mysql_result(mysql_query("SELECT host FROM game_users_active WHERE user_id='$uid'"),0,0);
			$user_host = HostIdentify();
			if ($user_host!=$host) echo'<br><br><center><b>Вас привел игрок <font color=ff0000>'.$name.'</font></b></center>';
		}
	}
	 echo '</form></td>
	 <td  width="5" height="50" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
	 </tr>
	 <tr>
	 <td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
	 <td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
	 <td><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
	</tr>
	</table>
	</div>

	</td>
	<td width="10" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg"></td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td width="10" colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td width="15" ><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr>
	</table>';


	echo '<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="23" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><div align="center"><br>Законы:</div></td>
	<td width="70" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="10"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr>

	<tr>
	<td width="15" height="90" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3">
	<IFRAME NAME="zakon" SRC="lib/zakon.php" HEIGHT="180" WIDTH="100%" BORDER="0" FRAMEBORDER="0" scrolling="1"></IFRAME>
	</td>
	<td width="10"background="http://'.IMG_DOMAIN.'/nav/333_17.jpg"></td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr>
	</table>

	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
	<tr><td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="23" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><div align="center"><br>Начальные характеристики каждой расы:</div></td>
	<td width="70" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="10"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr><tr><td width="15" height="90" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3"><div align="center">
	<table width="100%" height="50"  border="0" align="right" cellpadding="0" cellspacing="0">
	<tr><td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td><td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
	</tr><tr><td width="5" height="50" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td><td height="50" bgcolor="313131">';

	echo '<table cellpadding="5" cellspacing="0" border="0" width="100%">
	<table width="95%" border="0" cellspacing="3" cellpadding="5" align="center">';

	echo '<center><font face="Verdana" size=2><font color=ff0000><b>Жирным красным шрифтом</b></font> у каждой расы выделены приоритетные магические навыки, в которых эта раса может достичь максимального совершенства - развить их до 10 уровня. Остальные же навыки раса сможет выучить только лишь до 10 уровня</font></center>';

	PrintRace();

	echo'</table>';

	echo '</td>
	<td  width="5" height="50" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
	</tr>
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
	</tr>
	</table>
	</div>

	</td>
	<td width="10" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg">&nbsp;</td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr>
	</table>';
}

function EndRegistration()
{

	global $_POST;
	echo'<center>';
	echo '
	<table width="100%" height=10 border="0" cellspacing="0" cellpadding="0" align=center>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="23" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><div align="center"><br>'.GAME_NAME.' :: Регистрация</div></td>
	<td width="70" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="10"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr>
	<tr>
	<td width="15" height="90" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3"><div align="center">
	<table width="100%" height="100%"  border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
	</tr>
	<tr>
	<td width="5" height="100%" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td><td height="50" bgcolor="313131"><center>';

	if (isset($_POST['user_name']))
	{
		$user_name = $_POST['user_name'];
	}
	else
	{
		$user_name = '';
	}
	if (isset($_POST['email']))
	{
		$email = $_POST['email'];
	}
	else
	{
		$email = '';
	}
	if (isset($_POST['name']))
	{
		$name = $_POST['name'];
	}
	else
	{
		$name = '';
	}
	if (isset($_POST['status']))
	{
		$status = $_POST['status'];
	}
	else
	{
		$status = '';
	}
	if (isset($_POST['avata']))
	{
		$avata = $_POST['avata'];
	}
	else
	{
		$avata = '';
	}
	if (isset($_POST['gorod']))
	{
		$gorod = $_POST['gorod'];
	}
	else
	{
		$gorod = '';
	}
	if (isset($_POST['hobbi']))
	{
		$hobbi = $_POST['hobbi'];
	}
	else
	{
		$hobbi = '';
	}
	if (isset($_POST['info']))
	{
		$info = $_POST['info'];
	}
	else
	{
		$info = '';
	}
	if (isset($_POST['dn']))
	{
		$dn = $_POST['dn'];
	}
	else
	{
		$dn = '';
	}
	if (isset($_POST['ms']))
	{
		$ms = $_POST['ms'];
	}
	else
	{
		$ms = '';
	}
	if (isset($_POST['god']))
	{
		$god = $_POST['god'];
	}
	else
	{
		$god = '';
	}
	if (isset($_POST['uid']))
	{
		$uid = $_POST['uid'];
	}
	else
	{
		$uid = '';
	}
	if (isset($_POST['sex']))
	{
		$sex = $_POST['sex'];
	}
	else
	{
		$sex = '';
	}
	if (isset($_POST['sogl']))
	{
		$sogl = $_POST['sogl'];
	}
	else
	{
		$sogl = '';
	}
	@$_SESSION['values']['user_name'] = $user_name;
	@$_SESSION['values']['email'] = $email;
	@$_SESSION['values']['name'] = $name;
	@$_SESSION['values']['status'] = $status;
	@$_SESSION['values']['avata'] = $avata;
	@$_SESSION['values']['gorod'] = $gorod;
	@$_SESSION['values']['hobbi'] = $hobbi;
	@$_SESSION['values']['info'] = $info;
	@$_SESSION['values']['dn'] = $dn;
	@$_SESSION['values']['ms'] = $ms;
	@$_SESSION['values']['god'] = $god;
	@$_SESSION['values']['uid'] = $uid;
	@$_SESSION['values']['sex'] = $sex;

	$error_msg = '';
	if (!isset($sogl)) $sogl=0;

	setlocale (LC_ALL, "ru_RU.CP1251");
	$String_AM = new anti_mate;
	$user_name_filter = $String_AM->filter($user_name);
	$name_filter = $String_AM->filter($name);

	if (!($user_name && $email && $name))
	{
		$error_msg = 'Заполните все поля.</a>';
	}
	elseif (!(preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zzA-Z0-9-]+)*@[_a-zzA-Z0-9-]+(\.[a-zzA-Z0-9-]+)*$/', $email)))
	{
		$error_msg = 'Неправильный е-майл';
		$email = '*Ошибка';
	}
	elseif (!(preg_match('/^[_a-zA-Zа-яА-Я0-9]*$/', $user_name)))
	{
		$error_msg = 'Неправильный логин (он должен состоять только из символов латиницы, кириллицы и цифр).';
		$user_name = '*Ошибка';
	}
	elseif ($user_name!=$user_name_filter)
	{
		$error_msg = 'Логин не пропущен цензурой. Выберите другой логин!';
		$name = '*Ошибка';
	}
	elseif (!(preg_match('/1/', $sogl)))
	{
		$error_msg = 'Вы не согласны с Законами.';
	}
	elseif (!(preg_match('/^[_a-zA-Zа-яА-Я]*$/', $name)))
	{
		$error_msg = 'Неправильное игровое имя (разрешены только символы кириллицы или латиницы)';
		$name = '*Ошибка';
	}
	elseif ($name!=$name_filter)
	{
		$error_msg = 'Игровое имя не пропущено цензурой. Выберите другое имя!';
		$name = '*Ошибка';
	}
	else
	{
		$result = mysql_query("SELECT user_id FROM game_users WHERE user_name='$user_name' LIMIT 1");
		if (!mysql_num_rows($result)) $result = mysql_query("SELECT user_id FROM game_users_archive WHERE user_name='$user_name' LIMIT 1");
		if (mysql_num_rows($result) != 0)
		{
			$error_msg = 'Извините, но логин ' . $user_name . ' уже используется в игре!';
			$user_name = "*$user_name";
		}
		$result = mysql_query("SELECT user_id FROM game_users WHERE name='$name' LIMIT 1");
		if (!mysql_num_rows($result)) $result = mysql_query("SELECT user_id FROM game_users_archive WHERE name='$name' LIMIT 1");
		if ((mysql_num_rows($result) != 0))
		{
			$error_msg = 'Извините, но имя ' . $name . ' уже используется в игре!';
			$name = "*$name";
		}
		$result = mysql_query("SELECT user_id FROM game_users_data WHERE email='$email' LIMIT 1");
		if ((mysql_num_rows($result) != 0))
		{
			$error_msg = 'Такой е-майл уже используется';
			$email = "*$email";
		}
	}

	if (!isset($_SESSION['captcha']))
	{
		$error_msg = 'Неправильно введено кодовое число';
	}
	elseif (!isset($_POST['captcha']))
	{
		$error_msg = 'Неправильно введено кодовое число';
	}
	elseif ($_SESSION['captcha']!=$_POST['captcha'])
	{
		$error_msg = 'Неправильно введено кодовое число';
	}

	$result=mysql_query("select * from game_har where race='$avata' and disable=0");
	if (mysql_num_rows($result) == 0)
	{
		$error_msg = 'Выбрана неправильная раса';
	}
	if ($error_msg == '')
	{
		$user_time = time();
		$info = htmlspecialchars($info);

		/*
		$result = mysql_query("INSERT INTO game_users_reg (user_name, user_pass, name, email, race, STATUS, gorod, hobbi, info, uid, validate, rego_time, dr_date, dr_month, dr_year, sex) VALUES ('$user_name', '$user_pass1', '$name', '$email', '$avata', '$status', '$gorod', '$hobbi', '$info', '$uid', '$validation_string', '$user_time', '$dn', '$ms', '$god', '$sex')") or die(mysql_error());
        
        // Отсылаем письмо с паролем  ////////////////////////////
        	//Заголовок
			$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";
		
		//Письмо
		$message  = "Привет $name.\n\n";

		$message .= "Вы зарегистрировались в ролевой онлайн игре ".GAME_NAME."\n\n";
		$message .= "Для подтверждения регистрации зайдите по следующей ссылке:\n\n";
		$message .= "http://".DOMAIN."/".$_SERVER['PHP_SELF']."?option=activate&user_name=$user_name&validate=$validation_string\n\n";
		$message .= "Ваши данные:";
		$message .= "Логин: $user_name\n";
		$message .= "Пароль: $user_pass1\n\n";
		$message .= "ВАЖНОЕ: Если в течении 48 часов активация не происходит, персонажи удаляются автоматически.\n\n";
        //Тема
		$subject = ''.GAME_NAME.' [Активация] - '.DOMAIN.'';
		//Отсылаем
         mail($email, $subject, $message, $headers); 
         //////////////////////////////////////////////////////////

		echo '<br>Спасибо за регистрацию<br><br>
		К вам на почту отправлено сообщение с кодом активации вашего персонажа.<br>
		<b>ВАЖНОЕ:</b> Если в течении 48 часов активация не происходит, персонаж удаляется автоматически.<br><br>
		<a href="'.$_SERVER['PHP_SELF'].'">На главную</a>';

		 */
		//активируем сразу!

			$row=mysql_fetch_array($result);
			$hp1=$row["hp"];
			$hp_max1=$row["hp_max"];
			$mp1=$row["mp"];
			$mp_max1=$row["mp_max"];
			$stm1=$row["stm"];
			$stm_max1=$row["stm_max"];
			$exp1=$row["exp"];

			$invite = 0;
			if (isset($uid) and $uid!='')
			{
				$uid=(int)$uid;
				$u=mysql_query("select name from game_users where user_id='$uid'");
				if (!mysql_num_rows($u))
				{
					$u=mysql_query("select name from game_users_archive where user_id='$uid'");
				}
				$sel = mysql_query("SELECT host FROM game_users_active WHERE user_id='$uid'");
				if ($sel!=false AND mysql_num_rows($sel)>0)
				{
					$host_p = mysql_result($sel,0,0);
				}
				else
				{
					$host_p = "";
				}
				list($name_p)=mysql_fetch_array($u);
				$user_host_p = HostIdentify();
				if ($uid>0 and $user_host_p!=$host_p)
				{
					$gp1=$row["gp"]+100;
					$invite = 1;
				}
				else
				{
					$gp1=$row["gp"];
				}
			}
			else
			{
				$gp1=$row["gp"];
			}

			$str1=$row["str"];
			$ntl1=$row["ntl"];
			$pie1=$row["pie"];
			$vit1=$row["vit"];
			$dex1=$row["dex"];
			$spd1=$row["spd"];
			//$lucky1=$row["lucky"];
			//$vospr1=$row["vospr"];
			//$magic_res1=$row["magic_res"];
			$avatar=$row["race"];

			$start_map_name=$row["map_name"];
			$start_map_x=$row["map_x"];
			$start_map_y=$row["map_y"];

			$avatar = $avatar.'_'.$sex.'.gif';
			$user_pass=generate_password(10);
			$result  = mysql_query("
			INSERT game_users SET
			user_name='$user_name',
			user_pass='" . md5($user_pass) . "',
			name='$name',
			HP='$hp1',
			HP_MAX='$hp_max1',
			HP_MAXX='$hp_max1',
			MP='$mp1',
			MP_MAX='$mp_max1',
			STM='$stm1',
			STM_MAX='$stm_max1',
			EXP='$exp1',
			GP='$gp1',
			STR='$str1',
			NTL='$ntl1',
			PIE='$pie1',
			VIT='$vit1',
			DEX='$dex1',
			SPD='$spd1',
			STR_MAX='$str1',
			NTL_MAX='$ntl1',
			PIE_MAX='$pie1',
			VIT_MAX='$vit1',
			DEX_MAX='$dex1',
			SPD_MAX='$spd1',
			CW='".($gp1*money_weight)."',
			CC=40,
			race=".$row['id'].",
			avatar='$avatar'
			") or die('Database Error: ' . mysql_error() . '<br>');
			$newuserid = mysql_insert_id();
			setGP($newuserid,$gp1,4);
			if ($invite==1)
			{
				mysql_query("INSERT INTO game_invite (user_id,invite_id,invite_regtime) VALUES ($uid,$newuserid,".time().")");
			}
			//lucky='$lucky1',
			//lucky_max='$lucky1',
			//vospr='$vospr1',
			//vospr_max='$vospr1',
			//magic_res='$magic_res1',
			//magic_res_max='$magic_res1',

			//$result = mysql_query("DELETE FROM game_users_reg WHERE user_name = '$user_name'");
			$sel_uid = mysql_query("SELECT user_id FROM game_users WHERE user_name='$user_name'");
			list($uid) = mysql_fetch_array($sel_uid);

			$result  = mysql_query("
			INSERT game_users_map SET
			user_id='$uid',
			map_name='$start_map_name',
			map_xpos='$start_map_x',
			map_ypos='$start_map_y'
			") or die('Database Error: ' . mysql_error() . '<br>');

			$result  = mysql_query("
			INSERT game_users_data SET
			user_id='$uid',
			email='$email',
			status='$status',
			gorod='$gorod',
			hobbi='$hobbi',
			info='$info',
			dr_date='$dn',
			dr_month='$ms',
			dr_year='$god',
			sex='$sex',
			rego_time='$user_time'
			") or die('Database Error: ' . mysql_error() . '<br>');
			mysql_query("INSERT INTO game_users_active (user_id,host,last_active) VALUES ('$uid','".HostIdentify()."','')");
			mysql_query("INSERT INTO game_users_active_host (user_id,host_more) VALUES ('$uid','".HostIdentifyMore()."')");
			mysql_query("INSERT INTO game_chat_option (user_id,ref,size,frame) VALUES ('$uid','1','13','250')");

            // Отсылаем письмо с паролем  ////////////////////////////
			//Заголовок
			$headers="";
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			$headers .= "Reply-To: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Date: ".date("r")."\n";
			$headers .= "Message-ID: <".date("YmdHis")."no_reply@".DOMAIN.">\n";
			$headers .= "Return-Path: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Delivered-to: \"".GAME_NAME."\" <no_reply@".DOMAIN.">\n";
			$headers .= "Importance: High\n";
			$headers .= "X-MSMail-Priority: High\n";
			//Письмо
			$message  = "Привет, $name!\n\n";
			$message .= "Вы зарегистрировались в ролевой онлайн игре ".GAME_NAME."\n\n";
			$message .= "Ваши данные:\n";
			$message .= "Логин: $user_name\n";
			$message .= "Пароль: $user_pass\n\n";
			//Тема
			$subject = 'Ваши данные регистрации в он-лайн игре '.GAME_NAME.' - '.DOMAIN.'';
			//Отсылаем
            mail($email, $subject, $message, $headers); 
            //////////////////////////////////////////////////////////

			echo '<br>Спасибо за регистрацию<br><br>
		Теперь Вы можете войти в наш мир! <br>Пусть не за горами будет тот день, когда весь мир будет содрогаться от звука Вашего имени!<br>
			<br><br><a href="'.$_SERVER['PHP_SELF'].'">На главную страницу</a>';
	}
	else
	{
		$error_msg = 'Ошибка: ' . $error_msg . '<br><br><a href="'.$_SERVER['PHP_SELF'].'?option=register">Назад</a>';
		echo $error_msg;
	}

	echo '</td>
	<td  width="5" height="50" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
	</tr>
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8">
	</td>
	</tr>
	</table>
	</div>
	</td>
	<td width="10" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg">&nbsp;</td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr>
	</table>';
}

function Activate()
{
	global $_GET;
	echo'<center>';
	echo '<table border="0" cellspacing="0" cellpadding="0" align=center>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="23" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td width="879" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><div align="center"><br>'.GAME_NAME.' :: Активация</div></td>
	<td width="70" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="10"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr>
	<tr>
	<td width="15" height="90" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3"><div align="center">
	<table width="100%" height="50"  border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
	</tr>
	<tr>
	<td width="5" height="100" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
	<td height="50" bgcolor="313131" align="center" valign="center">';

	@$user_name = $_GET['user_name'];
	@$validate = $_GET['validate'];
	if (isset($user_name) && isset($validate))
	{
		$result = mysql_query("SELECT user_name, user_pass, name, email, race, STATUS, gorod, hobbi, info, uid, validate, rego_time, dr_date, dr_month, dr_year, sex FROM game_users_reg WHERE user_name='$user_name' AND validate='$validate' LIMIT 1");
		if (mysql_num_rows($result) != 0)
		{
			list($user_name, $user_pass, $name, $email, $race, $STATUS, $gorod, $hobbi, $info, $uid, $validate, $rego_time, $dr_date, $dr_month, $dr_year, $sex) = mysql_fetch_row($result);
			$result=mysql_query("select * from game_har where name='$race' and disable=0");
			if (mysql_num_rows($result) == 0)
			{
				echo'Ошибка активации [Неправильная Раса]';
			}
			else
			{
				$row=mysql_fetch_array($result);
				$hp1=$row["hp"];
				$hp_max1=$row["hp_max"];
				$mp1=$row["mp"];
				$mp_max1=$row["mp_max"];
				$stm1=$row["stm"];
				$stm_max1=$row["stm_max"];
				$exp1=$row["exp"];

				if ($uid!='')
				{
					$uid=(int)$uid;
					$u=mysql_query("select name from game_users where user_id='$uid'");
					if (!mysql_num_rows($u))
					{
						$u=mysql_query("select name from game_users_archive where user_id='$uid'");
					}
					$host_p = mysql_result(mysql_query("SELECT host FROM game_users_active WHERE user_id='$uid'"),0,0);
					list($name_p)=mysql_fetch_array($u);
					$user_host_p = HostIdentify();
					if (isset($uid) and ($user_host_p<>$host_p))
					{
						$gp1='150';
						$up=mysql_query("update game_users SET GP=GP+50,CW=CW+'".(50*money_weight)."' where user_id='$uid'");
						setGP($uid,50,3);
						$up=mysql_query("update game_users_archive SET GP=GP+50,CW=CW+'".(50*money_weight)."' where user_id='$uid'");
					}
					else
					{
						$gp1=$row["gp"];
					}
				}
				else
				{
					$gp1=$row["gp"];
				}

				$str1=$row["str"];
				$ntl1=$row["ntl"];
				$pie1=$row["pie"];
				$vit1=$row["vit"];
				$dex1=$row["dex"];
				$spd1=$row["spd"];
				//$lucky1=$row["lucky"];
				//$vospr1=$row["vospr"];
				//$magic_res1=$row["magic_res"];
				$avatar=$row["race"];

				$start_map_name=$row["map_name"];
				$start_map_x=$row["map_x"];
				$start_map_y=$row["map_y"];

				$avatar = $avatar.'_'.$sex.'.gif';

				$result  = mysql_query("
				INSERT game_users SET
				user_name='$user_name',
				user_pass='" . md5($user_pass) . "',
				name='$name',
				HP='$hp1',
				HP_MAX='$hp_max1',
				HP_MAXX='$hp_max1',
				MP='$mp1',
				MP_MAX='$mp_max1',
				STM='$stm1',
				STM_MAX='$stm_max1',
				EXP='$exp1',
				GP='$gp1',
				STR='$str1',
				NTL='$ntl1',
				PIE='$pie1',
				VIT='$vit1',
				DEX='$dex1',
				SPD='$spd1',
				STR_MAX='$str1',
				NTL_MAX='$ntl1',
				PIE_MAX='$pie1',
				VIT_MAX='$vit1',
				DEX_MAX='$dex1',
				SPD_MAX='$spd1',
				CW='".($gp1*money_weight)."',
				CC=40,
				race='".$row['id']."',
				avatar='$avatar'
				") or die('Database Error: ' . mysql_error() . '<br>');
				//lucky='$lucky1',
				//lucky_max='$lucky1',
				//vospr='$vospr1',
				//vospr_max='$vospr1',
				//magic_res='$magic_res1',
				//magic_res_max='$magic_res1',

				$result = mysql_query("DELETE FROM game_users_reg WHERE user_name = '$user_name'");
				list($uid) = mysql_fetch_array(mysql_query("SELECT user_id FROM game_users WHERE user_name='$user_name'"));

				$result  = mysql_query("
				INSERT game_users_map SET
				user_id='$uid',
				map_name='$start_map_name',
				map_xpos='$start_map_x',
				map_ypos='$start_map_y'
				") or die('Database Error: ' . mysql_error() . '<br>');

				$result  = mysql_query("
				INSERT game_users_data SET
				user_id='$uid',
				email='$email',
				status='$STATUS',
				gorod='$gorod',
				hobbi='$hobbi',
				info='$info',
				dr_date='$dr_date',
				dr_month='$dr_month',
				dr_year='$dr_year',
				sex='$sex',
				rego_time='$rego_time'
				") or die('Database Error: ' . mysql_error() . '<br>');
				mysql_query("INSERT INTO game_users_active (user_id,host,last_active) VALUES ('$uid','".HostIdentify()."','')");
				mysql_query("INSERT INTO game_users_active_host (user_id,host_more) VALUES ('$uid','".HostIdentifyMore()."')");
				mysql_query("INSERT INTO game_chat_option (user_id,ref,size,frame) VALUES ('$uid','1','13','220')");
				echo 'Спасибо <b>' . $name . '</b>, вы активированы! <br>';

				if ($uid!='' and $gp1==150)
				{
					echo'<br>Вы пришли по сcылке игрока '.$name_p.'. У вас 150 монет на начало игры!';
				}
				echo'<br><br><a href="'.$_SERVER['PHP_SELF'].'">Вернуться на главную</a>';
			}
		}
		else
		{
			echo'<center>Ошибка активации [Неправильный код]</center>';
		}
	}
	else
	{
		echo'<center>Ошибка активации</center>';
	}

	echo '</td>
	<td  width="5" height="50" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
	</tr>
	<tr>
	<td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
	</tr>
	</table>
	</div>
	</td>
	<td width="15" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg">&nbsp;</td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr></table>';
}

function History()
{
	global $_GET;
	echo'<center>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="0"><img src="http://'.IMG_DOMAIN.'/nav/1_01.jpg" width="15" height="33"></td>
	<td width="0    " background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04_1.jpg" width="70" height="33"></td>
	<td background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"></td>
	<td width="0" background="http://'.IMG_DOMAIN.'/nav/1_03.jpg"><img src="http://'.IMG_DOMAIN.'/nav/1_04.jpg" width="70" height="33"></td>
	<td width="0"><img src="http://'.IMG_DOMAIN.'/nav/1_05.jpg" width="15" height="33"></td>
	</tr>
	<tr>
	<td width="15" background="http://'.IMG_DOMAIN.'/nav/1_16.jpg"></td>
	<td colspan="3">';

	$saga = 0;
	if (isset($_GET['saga'])) $saga = (int)$_GET['saga'];
	switch ($saga)
	{
		case 1:
		include ('utils/history/help.htm');
		break;

		case 2:
		include ('utils/history/saga.htm');
		break;

		case 3:
		include ('utils/history/gazeta.htm');
		break;

		case 4:
		include ('utils/history/money.htm');
		break;

		case 5:
		include ('utils/history/slova.htm');
		break;

		case 6:
		include ('utils/history/journal.htm');
		break;

		case 7:
		include ('best_sz_2007.html');
		break;
	}

	echo'</td>
	<td width="0" background="http://'.IMG_DOMAIN.'/nav/333_17.jpg"></td>
	</tr>
	<tr>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_23.jpg" width="15" height="14"></td>
	<td colspan="3" background="http://'.IMG_DOMAIN.'/nav/1_25.jpg"></td>
	<td width="15"><img src="http://'.IMG_DOMAIN.'/nav/1_26.jpg" width="15" height="14"></td>
	</tr>
	</table>';
}






/* НАЧАЛО ОСНОНВНОГО БЛОКА */



include('inc/engine.inc.php');
require('inc/xray.inc.php');
include('inc/lib.inc.php');

if (function_exists("start_debug")) start_debug();


DbConnect();

$user_host = HostIdentify();
$sel = myquery("SELECT COUNT(*) FROM block_ip WHERE ip=".$user_host."");
if (mysql_result($sel,0,0)>0)
{
	die('Игра на реконструкции');
}

if (isset($_GET['option']))
{
	$option = $_GET['option'];
}
else
{
	$option = 'default';
}
if($option=='login')
{
	if (!isset($_POST['user_name'])) {if (function_exists("save_debug")) save_debug(); exit;}
	if (!isset($_POST['user_pass'])) {if (function_exists("save_debug")) save_debug(); exit;}
	$user_name = $_POST['user_name'];
	$us_pass = $_POST['user_pass'];
	$user_pass = md5($us_pass);

	if (!empty($user['user_id']))
	{
		unset($user['user_id']);
	}
	if (!(preg_match('/^[ _a-zа-яA-ZА-Я0-9]*$/', $user_name)))
	{
		$user_name = '*Ошибка - некорректный логин';
	}
	if ($user_name == '')
	{
		$user_name = '*Ошибка - некорректный логин';
	}

	$result = mysql_query("SELECT * FROM game_users WHERE user_name='$user_name' AND user_pass='$user_pass' LIMIT 1");
	if (!mysql_num_rows($result))
	{
		//достаем игрока из архива
		$sel = mysql_query("SELECT * FROM game_users_archive WHERE user_name='$user_name' AND user_pass='$user_pass' LIMIT 1");
		if (mysql_num_rows($sel))
		{
			$up = mysql_query("INSERT INTO game_users SELECT * FROM game_users_archive WHERE user_name='$user_name' AND user_pass='$user_pass' LIMIT 1");
			$up = mysql_query("DELETE FROM game_users_archive WHERE user_name='$user_name' AND user_pass='$user_pass'");
		}
		$result = mysql_query("SELECT * FROM game_users WHERE user_name='$user_name' AND user_pass='$user_pass' LIMIT 1");
	}
	$user = mysql_fetch_array($result);

	setcookie("rpgsu_login",0,time()-84000000,"/");
	setcookie("rpgsu_pass",0,time()-84000000,"/");
	setcookie("rpgsu_sess",0,time()-84000000,"/");
	setcookie("rpgsu_admin",0,time()-84000000,"/");
	$user_host_more = HostIdentifyMore();
	if ($user['user_id'])
	{
		LoginUser($user);
	}
	else
	{
		$result1 = mysql_query("SELECT * FROM game_users WHERE user_name='$user_name' LIMIT 1");
		$result2 = mysql_query("SELECT * FROM game_users_archive WHERE user_name='$user_name' LIMIT 1");

		$result3 = mysql_query("SELECT * FROM game_users WHERE user_name='$user_name' AND user_pass='$user_pass' LIMIT 1");
		$result4 = mysql_query("SELECT * FROM game_users_archive WHERE user_name='$user_name' AND user_pass='$user_pass' LIMIT 1");

		if (!mysql_num_rows($result1) AND !mysql_num_rows($result2))
		{
			Header("Location:".$_SERVER['PHP_SELF']."?error=login");
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
		if (!mysql_num_rows($result3) AND !mysql_num_rows($result4))
		{
			mysql_query("INSERT INTO game_login (user_name,host,time_try) VALUES ('$user_name',$user_host,".time().")");
			Header("Location:".$_SERVER['PHP_SELF']."?error=pass");
			{if (function_exists("save_debug")) save_debug(); exit;}
		}
	}
	{if (function_exists("save_debug")) save_debug(); exit;}
}
elseif ($option=='rss')
{
	include('class/class_rss_generator.inc.php');
	$news=mysql_query("SELECT * FROM game_news where status='0' ORDER BY id DESC");
	$items = Array();
	while($newsa=mysql_fetch_array($news))
	{
		$items[] = Array("title"=>$newsa['theme'],"description"=>$newsa['text'],"link"=>'http://'.DOMAIN.'/');
	}
	$feed = new rss_generator(''.GAME_NAME.' :: Новости');
	$feed->__set('title',''.GAME_NAME.' :: Новости');
	$feed->__set('description','Последние новости');
	$feed->__set('link','http://'.DOMAIN.'/');
	header('Content-type: application/xml');
	echo $feed->get($items);
	{if (function_exists("save_debug")) save_debug(); exit;}
}
elseif ($option=='rss_forum')
{
	include('class/class_rss_generator.inc.php');
	function convert_in_tags($text)
	{
		$preg = array(
		  '/(?<!\\\\)\[color(?::\w+)?=(.*?)\](.*?)\[\/color(?::\w+)?\]/si'   => "",
		  '/(?<!\\\\)\[size(?::\w+)?=(.*?)\](.*?)\[\/size(?::\w+)?\]/si'     => "",
		  '/(?<!\\\\)\[font(?::\w+)?=(.*?)\](.*?)\[\/font(?::\w+)?\]/si'     => "",
		  '/(?<!\\\\)\[align(?::\w+)?=(.*?)\](.*?)\[\/align(?::\w+)?\]/si'   => "",
		  '/(?<!\\\\)\[b(?::\w+)?\](.*?)\[\/b(?::\w+)?\]/si'                 => "",
		  '/(?<!\\\\)\[i(?::\w+)?\](.*?)\[\/i(?::\w+)?\]/si'                 => "",
		  '/(?<!\\\\)\[u(?::\w+)?\](.*?)\[\/u(?::\w+)?\]/si'                 => "",
		  '/(?<!\\\\)\[center(?::\w+)?\](.*?)\[\/center(?::\w+)?\]/si'       => "",
		  // [code]
		  '/(?<!\\\\)\[code(?::\w+)?\](.*?)\[\/code(?::\w+)?\]/si'           => "",
		  // [email]
		  '/(?<!\\\\)\[email(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si'         => "",
		  '/(?<!\\\\)\[email(?::\w+)?=(.*?)\](.*?)\[\/email(?::\w+)?\]/si'   => "",
		  // [url]
		  '/(?<!\\\\)\[url(?::\w+)?\]www\.(.*?)\[\/url(?::\w+)?\]/si'        => "",
		  '/(?<!\\\\)\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'             => "",
		  '/(?<!\\\\)\[url(?::\w+)?=(.*?)?\](.*?)\[\/url(?::\w+)?\]/si'      => "",
		  // [img]
		  '/(?<!\\\\)\[img(?::\w+)?\](.*?)\[\/img(?::\w+)?\]/si'             => "",
		  '/(?<!\\\\)\[img(?::\w+)?=(.*?)x(.*?)\](.*?)\[\/img(?::\w+)?\]/si' => "",
		  // [quote]
		  // [list]
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\*(?::\w+)?\](.*?)(?=(?:\s*<br\s*\/?>\s*)?\[\*|(?:\s*<br\s*\/?>\s*)?\[\/?list)/si' => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list(:(?!u|o)\w+)?\](?:<br\s*\/?>)?/si'    => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:u(:\w+)?\](?:<br\s*\/?>)?/si'         => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:o(:\w+)?\](?:<br\s*\/?>)?/si'         => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(:(?!u|o)\w+)?\]\s*(?:<br\s*\/?>)?/si'   => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:u(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:o(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=1\]\s*(?:<br\s*\/?>)?/si' => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=i\]\s*(?:<br\s*\/?>)?/s'  => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=I\]\s*(?:<br\s*\/?>)?/s'  => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=a\]\s*(?:<br\s*\/?>)?/s'  => "",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=A\]\s*(?:<br\s*\/?>)?/s'  => "",
		  // escaped tags like \[b], \[color], \[url], ...
		  '/\\\\(\[\/?\w+(?::\w+)*\])/'                                      => ""
		);
		$text = preg_replace(array_keys($preg), array_values($preg), $text);
		if (strpos($text,':')!==false)
		{
			$dh = opendir('forum/smile/');
			while($file = readdir($dh))
			{
				if ($file=='.') continue;
				if ($file=='..') continue;
				$len=strlen($file)-4;
				$filesmile = ':'.substr($file,0,$len).':';
				$text = str_replace($filesmile,"",$text);
			}
		}
		return $text;
	}

	$items = Array();
	$sel=mysql_query("select forum_topics.last_user AS last_user, forum_topics.id AS id, forum_topics.top AS top, forum_topics.text AS text FROM forum_topics,forum_kat,forum_main WHERE forum_main.level='' AND forum_main.id=forum_kat.main_id AND forum_kat.id = forum_topics.kat_id AND forum_kat.clan='0' order by forum_topics.last_date DESC limit 15");
	while ($w=mysql_fetch_array($sel))
	{
		list($last_otvet) = mysql_fetch_array(mysql_query("SELECT text FROM forum_otv WHERE topics_id=".$w['id']." ORDER BY id DESC LIMIT 1"));
		$last_otvet = convert_in_tags($last_otvet);
		$items[] = Array("title"=>$w['top'],"description"=>$last_otvet,"link"=>'http://'.DOMAIN.'/forum/?act=topic&id='.$w['id'].'&page=n');
	}
	$feed = new rss_generator(''.GAME_NAME.' :: Зал Палантиров');
	$feed->__set('title',''.GAME_NAME.' :: Зал Палантиров');
	$feed->__set('description','Последние сообщения из Зала Палантиров');
	$feed->__set('link','http://'.DOMAIN.'/forum/');
	header('Content-type: application/xml');
	echo $feed->get($items);
	{if (function_exists("save_debug")) save_debug(); exit;}
}
else
{
	session_start();
	if ($option == 'register')
	{
		Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Дата в прошлом
		Header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT"); // Изменилась
		Header("Cache-Control: no-cache, must-revalidate");  // для HTTP/1.1
		Header("Pragma: no-cache");                          // для HTTP/1.0
	}
}
//echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
include('inc/template_header.inc.php');
echo "<link title=\"Новости ".GAME_NAME."\" href=\"http://".DOMAIN."/".$_SERVER['PHP_SELF']."?option=rss\" type=\"application/rss+xml\" rel=\"alternate\" />
<link title=\"Обсуждения в Зале Палантиров\" href=\"http://".DOMAIN."/".$_SERVER['PHP_SELF']."?option=rss_forum\" type=\"application/rss+xml\" rel=\"alternate\" />";

?>
<link rel="STYLESHEET" type="text/css" href="/style/style.css">
<style type="text/css">
BODY, .back {
  background: black url("http://<?=IMG_DOMAIN;?>/nav/image_01.jpg");
  background-repeat: repeat;
  margin: 0;
  CURSOR: url('/images/nav/normal.cur'), default; 
}
.news {
  background: black url("http://<?=IMG_DOMAIN;?>/nav/story-content-bg2.gif");
  background-repeat: repeat;
}
a, a:visited, a:link {CURSOR: url('/images/nav/hand.cur'), pointer; }
a:hover {CURSOR: url('/images/nav/hand.cur'), pointer; }
</style>
<BODY>
<center>
<SCRIPT type="text/javascript" language=javascript src="js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>
<TABLE summary="" WIDTH=845 BORDER=0 STYLE="BORDER-COLOR:#808080;HEIGHT:921px;" CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD ROWSPAN=17 width="9" height="920">
			&nbsp;</TD>
		<TD COLSPAN=3 width="346" height="175"><A HREF="http://<?php echo DOMAIN;?>/"><IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_02.jpg" WIDTH=346 HEIGHT=175 ALT="" BORDER=0></A></TD>
		<TD width="255" height="175"><A HREF="http://<?php echo DOMAIN;?>/"><IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_03.jpg" WIDTH=255 HEIGHT=175 ALT="" BORDER=0></A></TD>
		<TD COLSPAN=6 width="229" height="175"><A HREF="http://<?php echo DOMAIN;?>/"><IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_04.jpg" WIDTH=229 HEIGHT=175 ALT="" BORDER=0></A></TD>
		<TD ROWSPAN=17 width="10" height="920">&nbsp;</TD>
		<TD width="1" height="175"><IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=175 ALT=""></TD>
	</TR>
	<TR>
		<TD COLSPAN=10 width="830" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_06.jpg" WIDTH=830 HEIGHT=1 ALT="" BORDER=0></TD>
		<TD width="1" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=1 ALT="" BORDER=0></TD>
	</TR>
	<TR>
		<TD ROWSPAN=14 width="29" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_070.jpg)" valign=top height="687">
			<img border="0" alt="" src="http://<?php echo IMG_DOMAIN;?>/nav/image_07.jpg" width="29" height="535"></TD>
		<TD ROWSPAN=13 width="600" colspan="4" bgcolor="#000000" valign="top" height="720">

	<?php
	// ДАЛЕЕ ИДЕТ ОСНОВНОЙ ЭКРАН КОТОРЫЙ БУДЕМ ЗАПОЛНЯТЬ  В ЗАВИСИМОСТИ ОТ ВЫБРАННЫХ ОПЦИЙ

	switch ($option)
	{
		case 'for':
			ForgetPassword();
		break;

		case 'reg':
			EndRegistration();
		break;

		case 'activate':
			Activate();
		break;

		case 'register':
			BeginRegistration();
		break;

		case 'history':
			History();
		break;

		default:
?>

  <table summary="" border="0" cellpadding="0" cellspacing="0"  width="560" style="height:23">
	<TR>
		<TD ROWSPAN=5 width="216" height="21" valign="top">
			<div style="position: relative;">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_08_2.jpg" WIDTH=216 HEIGHT=130 ALT=""><br>

			<?php
			if (isset($error))
			{
				if($error=='ban')
				{
				if ($time=='-1')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="#FFFF00" face=Verdana,Tahoma,Arial><center>Вы забанены навечно!</center></font></div>';
				else
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Вы забанены ещё на '.(ceil($time/60)).' минут</center></font></div>';
				}
				elseif($error=='host')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>В игре уже присутствует игрок с вашим IP</center></font></div>';
				elseif($error=='banip')
				{
				if ($time=='-1')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Ваш IP-адрес забанен навечно!</center></font></div>';
				else
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial ><center>Ваш IP-адрес забанен ещё на '.(ceil($time/60)).' минут</center></font></div>';
				}
				elseif($error=='banipdiap')
				{
				if ($time=='-1')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Ваш IP-адрес забанен навечно!</center></font></div>';
				else
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial ><center>Ваш IP-адрес забанен ещё на '.(ceil($time/60)).' минут</center></font></div>';
				}
				elseif($error=='login')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Вы указали неправильный логин</center></font></div>';
				elseif($error=='pass')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Вы указали неправильный пароль</center></font></div>';
				elseif($error=='try')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Превышено макс.кол-во неудачных входов. Логин заблокирован.</center></font></div>';
				elseif($error=='ip')
					echo '<div style="width:500px;position:absolute; top: 5px; left:125px"><font size=2 color="FFFF00" face=Verdana,Tahoma,Arial><center>Запрещен вход данным персонажем с этого IP адреса</center></font></div>';
			}
			?>
			<div style="width:150px;position:absolute; top: 50px; left:35px">
			<form action="<?=$_SERVER['PHP_SELF'];?>?option=login" method="post" name="login_form">
			<font color="#FFFFFF" face="Verdana,Tahoma,Arial"><b>Логин</b></font><br>
			<input type="text" name="user_name" size="25" maxlength="20" class="input" style="width:150px;"><br>
			<font color="#FFFFFF" face="Verdana,Tahoma,Arial"><b>Пароль</b></font><br>
			<input type="password" name="user_pass" size="25" maxlength="32" class="input" style="width:150px;">
			<input type="submit" value="&nbsp;&nbsp;&nbsp;Вход&nbsp;&nbsp;&nbsp;" class="inputbutton">
			</form></div></div>
			<a href="<?=$_SERVER['PHP_SELF'];?>?option=register"><IMG TITLE="Регистрация нового персонажа" SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_08_1.jpg" WIDTH=216 HEIGHT=68 ALT="Регистрация нового персонажа" BORDER="0"></a>

			<?php
			$result = mysql_query("SELECT name,user_id,clan_id FROM view_active_users ORDER BY clan_id ASC, name ASC");
			$online_number = mysql_num_rows($result);
			/*
			$online='<div style=\\\'color:black;white-space:nowrap;\\\'>';
			$nom=0;
			$pred_clan = -2;
			while ($on = mysql_fetch_array($result))
			{
				if ($pred_clan!=$on['clan_id'])
				{
					if ($pred_clan!=-2)
					{
						$online.='</div><div style=\\\'color:black;white-space:nowrap;\\\'>';
					}
					$nom=0;
					$pred_clan=$on['clan_id'];
				}
				$online.=''.$on['name'].'';
				if ($on['clan_id']>0)
				{
					$online.='<img src=http://'.IMG_DOMAIN.'/clan/'.$on['clan_id'].'.gif border=0 width=16 height=16>';
				}
				$online.='';
				$nom++;
				if ($nom<$online_number)
				{
					$online.=', ';
					if ($nom==6)
					{
						$online.='</div><div style=\\\'color:black;white-space:nowrap;\\\'>';
						$nom=0;
					}

				}
			}
			$online.='</div>';
			?>
			<center><b>
			<a onmousemove=movehint(event) onmouseover="showhint('<b><font face=verdana size=2 color=FF0000>Игроки находящиеся в игре</font></b>','<font face=verdana size=1 color=#000099><?php echo $online; ?></font>',0,1,event)" onmouseout="showhint('','',0,0,event)">
			*/
			?>
			<center><b> 
			В игре - <font color=#ff0000><?= $online_number; ?></font> <?=pluralForm($online_number,'игрок','игрока','игроков');?></b></center>
		</TD>
		<TD width="335" height="1" valign="top">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_09.jpg" WIDTH=384 HEIGHT=8 ALT=""></TD>
	</TR>
	<TR>
		<TD width="335" height="42" valign="top" align="right" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_13.jpg)">
			&nbsp;</TD>
	</TR>
	<TR>
		<TD width="335" bgcolor="#000000" height="96" valign="top">
			<?php
			echo'<div style="text-align:center;width:384px;color:gold;font-size:11px;font-weight:800;letter-spacing:3px;margin-top:-13px;z-index:150;">Сообщения из Зала Палантиров:</div>';
			echo'<div style="position:absolute;"><div style="margin-top:1px;position:relative;HEIGHT: 128px; WIDTH: 333px; text-align:left;"><MARQUEE id=threadticker onmouseover=this.scrollAmount=0 onmouseout=this.scrollAmount=1 scrollAmount=1 direction=up style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid;HEIGHT: 128px; WIDTH: 333px;">';
			$sel=mysql_query("
			SELECT forum_topics.last_user AS last_user, forum_topics.id AS id, forum_topics.top AS top, (
				SELECT forum_name.name
					FROM forum_name
					WHERE forum_topics.last_user = forum_name.user_id
					) AS last_name
			FROM forum_topics, forum_kat,forum_main
			WHERE forum_kat.id = forum_topics.kat_id
			AND forum_main.id=forum_kat.main_id
		AND forum_main.level=''
			ORDER BY forum_topics.last_date DESC
			LIMIT 15
			");
			while ($w=mysql_fetch_array($sel))
			{
				echo''.$w['last_name'].' :: <a href="http://'.DOMAIN.'/forum/?act=topic&amp;id='.$w['id'].'&amp;page=n">'.stripslashes($w['top']).'</a><BR>';
			}
			?>
			</div>
			</div>
			</TD>
	</TR>
	<TR>
		<TD width="335" height="46" valign="bottom">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_16.jpg" WIDTH=384 HEIGHT=29 ALT=""></TD>
	</TR>
	<TR>
		<TD width="335" bgcolor="#000000" height="1" align="right">
			<a href="<?=$_SERVER['PHP_SELF'];?>?option=for">Забыли пароль?</a>
		</TD>
	</TR>
  </table>
	<div style="color:#FFFFB3;width:600px;font-weight:900;text-align:center;">Последние новости:</div>
	<table summary="" border="0" cellpadding="0" cellspacing="0" width="100%" CLASS="news">
		<?php
		$news=mysql_query("SELECT * FROM game_news where status='0' ORDER BY id DESC limit 5");
		while($newsa=mysql_fetch_array($news))
		{
			echo'
			<tr>
			<td width="5"><img alt="" src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
			<td style="background-image:url(http://'.IMG_DOMAIN.'/nav/1_09.jpg)"></td>
			<td width="5"><img alt="" src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
			</tr>
			<tr>
			<td width="5" style="background-image:url(http://'.IMG_DOMAIN.'/nav/1_17.jpg)"></td>
			<td class="news" height="100%" bgcolor="#313131">';
			echo "
			<DIV><b><FONT COLOR=#D1D1D1>($newsa[created])</FONT></b></DIV>
			<DIV align=\"center\" style=\"filter:glow(color=#333399, strength=5)\"><font color=\"#CCCCCC\" size=3 face=\"Times, Helvetica, sans-serif\"><b>$newsa[theme]</b></font></DIV><br>
			<font face=verdana,arial,tahoma>$newsa[text]</font>";

			echo'
			</td>
			<td  width="5" style="background-image:url(http://'.IMG_DOMAIN.'/nav/1_15.jpg)"></td>
			</tr>
			<tr>
			<td width="5"><img alt="" src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
			<td style="background-image:url(http://'.IMG_DOMAIN.'/nav/1_20.jpg)"></td>
			<td><img alt="" src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
			</tr>';
		}
		?>
  </table>
			<center><a href="news.php" target="_blank" title="Прочитать все новости">Прочитать все новости</a></center>
<?php
	}
?>
			</TD>
		<TD ROWSPAN=14 width="201" colspan="5" bgcolor="#000000" valign=top height="687" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/caps.gif)">


<TABLE summary="" WIDTH=131 BORDER=0 CELLPADDING=0 CELLSPACING=0 style="height:201">
	<TR>
		<TD ROWSPAN=4 width="1" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_10.jpg)" height="402">
			&nbsp;</TD>
		<TD COLSPAN=3 width="1" height="33">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_11.jpg" WIDTH=137 HEIGHT=33 ALT=""></TD>
		<TD ROWSPAN=4 width="3" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_12.jpg)" height="402">
			&nbsp;
			</TD>
	</TR>
	<TR>
		<TD COLSPAN=3 width="120" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_14.jpg)" height="303" valign="top">
			<div align="left">
			<?php
			echo'
			<a style="font-size:13px;color:#00FFFF;" href=http://'.DOMAIN.'/view/ target="_blank">Описание ИГРЫ</a></br>
			<a href=http://'.DOMAIN.'/forum/ target="_blank">Зал Палантиров</a></br>
			<a href=http://'.DOMAIN.'/info/ target="_blank">Энциклопедия</a></br>
			<a href=http://'.DOMAIN.'/diary/ target="_blank">Дневники</a></br>
			<a href=http://'.DOMAIN.'/view/?clan target="_blank">Кланы</a></br>
			<a href=http://'.DOMAIN.'/view/?top target="_blank">Рейтинг</a></br>
			<a href=http://'.DOMAIN.'/view/?log target="_blank">Логи боев</a></br>';

			echo'
			<br><a href=http://'.DOMAIN.'/map.php target="_blank">Вся карта</a></br>
			<br><a href=?option=history&amp;saga=3>Вестник<br></a>
			<br><a href=?option=history&amp;saga=6>Журнал<br>"Хай Лэвел"</a></br></br>
			<a href=?option=history&amp;saga=5>Словарь<br>терминов</a></br>
			<a href=?option=history&amp;saga=7>Самый-<br />Самый 2007</a></br>
			<br><font color=#FFFF00 size=2 face="verdana"><b>Хроники:</b></font></br>
			<a href=?option=history&amp;saga=2>Вторая битва великих сил</a>';
			?>
			</div>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=3 width="1" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_19.jpg" WIDTH=137 HEIGHT=45 ALT=""></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 width="120" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_20.jpg)" height="160" class="ererf" valign="top">
		<div style="width:120px; filter:glow(color=#333399, strength=5);text-align:center;"><font color="#CCCCCC">Сегодня отмечают<br>день рождения:</font></div>
		<br><br>
		<?php
		$date_ar = getdate();
		$date = $date_ar['mday'];
		$month = $date_ar['mon'];
		$seldr = mysql_query("(SELECT name,clevel FROM game_users WHERE clevel>5 AND user_id IN (SELECT user_id FROM game_users_data WHERE dr_date=$date AND dr_month=$month)) UNION (SELECT name,clevel FROM game_users_archive WHERE clevel>5 AND user_id IN (SELECT user_id FROM game_users_data WHERE dr_date=$date AND dr_month=$month)) ORDER BY clevel DESC");
		if (!mysql_num_rows($seldr))
		{
			echo '<div style="width:120;text-align:center;"><i><font color=#FFFFCC>Жаль, но именинников сегодня нет</font></i></div>';
		}
		else
		{
			while ($dr = mysql_fetch_array($seldr))
			{
				echo '<b><font color=#FFFF00>&nbsp;'.$dr['name'].'</font></b> ['.$dr['clevel'].']<br>';
			}
		}
		?>

		</TD>
	</TR>
	<TR>
		<TD ROWSPAN=5 width="1" height="9">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_21.jpg" WIDTH=29 HEIGHT=139 ALT=""></TD>
		<TD COLSPAN=3 width="1" height="46">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_200.jpg" WIDTH=137 HEIGHT=46 ALT=""></TD>
		<TD ROWSPAN=5 width="3" height="9">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_23.jpg" WIDTH=35 HEIGHT=139 ALT=""></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 width="1" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_24.jpg" WIDTH=137 HEIGHT=11 ALT=""></TD>
	</TR>
	<TR>
		<TD ROWSPAN=3 width="9" height="9">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_25.jpg" WIDTH=14 HEIGHT=82 ALT=""></TD>
		<TD width="88" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_26.jpg)" height="30" ALIGN="CENTER" VALIGN="MIDDLE">
			<CENTER>
			<IMG SRC="mail.gif" WIDTH="88" HEIGHT="11" BORDER=0 ALT="Отправить письмо администрации">
			</CENTER>
		</TD>
		<TD ROWSPAN=3 width="30" height="9">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_27.jpg" WIDTH=35 HEIGHT=82 ALT=""></TD>
	</TR>
	<TR>
		<TD ROWSPAN=2 width="57" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_28.jpg" WIDTH=88 HEIGHT=52 ALT=""></TD>
	</TR>
</TABLE>
	<center>
	<div style="width:140px; position:relative;left:-12px;"><span style="filter:glow(color=#333399, strength=5);"><font color="#CCCCCC">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Наши друзья!</font></span><br>
	<? include("lib/friends.php"); ?>
	</div>
	</center>
</TD>
		<TD width="1" height="12">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=8 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="37">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=25 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="25">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=17 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="117">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=81 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="43">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=29 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="56">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=38 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="23">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=16 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="63">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=45 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="195">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=137 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="65">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=46 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="15">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=11 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="42">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=30 ALT=""></TD>
	</TR>
	<TR>
		<TD width="1" height="27">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=19 ALT=""></TD>
	</TR>
	<TR>
		<TD COLSPAN=4 width="600" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/image_29.jpg" WIDTH=600 HEIGHT=33 ALT="">
			</TD>
		<TD width="1" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=33 ALT=""></TD>
	</TR>
	<TR>
		<TD COLSPAN=10 width="830" style="background-image:url(http://<?php echo IMG_DOMAIN;?>/nav/image_30.jpg)" height="57">
		&nbsp;</TD>
		<TD width="1" height="57">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=1 HEIGHT=57 ALT=""></TD>
	</TR>
	<TR>
		<TD width="9" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=100% HEIGHT=1 ALT=""></TD>
		<TD width="29" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=29 HEIGHT=1 ALT=""></TD>
		<TD width="216" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=216 HEIGHT=1 ALT=""></TD>
		<TD width="101" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=101 HEIGHT=1 ALT=""></TD>
		<TD width="255" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=255 HEIGHT=1 ALT=""></TD>
		<TD width="28" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=28 HEIGHT=1 ALT=""></TD>
		<TD width="29" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=29 HEIGHT=1 ALT=""></TD>
		<TD width="14" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=14 HEIGHT=1 ALT=""></TD>
		<TD width="88" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=88 HEIGHT=1 ALT=""></TD>
		<TD width="35" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=35 HEIGHT=1 ALT=""></TD>
		<TD width="35" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=35 HEIGHT=1 ALT=""></TD>
		<TD width="10" height="1">
			<IMG SRC="http://<?php echo IMG_DOMAIN;?>/nav/spacer.gif" WIDTH=100% HEIGHT=1 ALT=""></TD>
		<TD width="1" height="1"></TD>
	</TR>
</TABLE>


<center>
<?php
$max_users = mysql_fetch_array(myquery("SELECT step,timecron FROM game_cron_log WHERE cron='max_users'"));
echo '<font face=verdana size=1 color=#ffffff>Максимальное кол-во игроков в онлайне       <b>'.$max_users['step'].'</b>      было <b>'.date("d.m.Y H:i:s",$max_users['timecron']).'</b><br />';
echo "<br>
Copyright © 2004-".date("Y")." <a href=\"http://".DOMAIN."\">".GAME_NAME."</a> <br>";
?>
Дизайн сайта оптимизирован на разрешение экрана не менее 1024х768 <br>
Программные материалы, графические элементы размещенные на сайте, являются интеллектуальной собственностью авторов<br>и защищены законом об авторских правах. Запрещено использование элементов сайта без письменного разрешения владельцев!<br>Администрация сайта не несет ответственности за информацию, публикуемую игроками в разрешенных для этого местах<br><br>
Внимание! Для работы с нашим сайтом вам необходимо разрешить прием COOKIE!<br>
Наш сайт работает в браузерах:<br>
<img src="http://<?=IMG_DOMAIN?>/firefox.png" width="16" height="16" alt="Mozilla FireFox" title="Mozilla FireFox">
<img src="http://<?=IMG_DOMAIN?>/opera.png" width="16" height="16" alt="Opera" title="Opera">
<img src="http://<?=IMG_DOMAIN?>/ie.png" width="16" height="16" alt="Microsoft Internet Explorer" title="Microsoft Internet Explorer">
<img src="http://<?=IMG_DOMAIN?>/avantbrowser.png" width="16" height="16" alt="Avant Browser" title="Avant Browser">
<img src="http://<?=IMG_DOMAIN?>/maxthon.png" width="16" height="16" alt="Maxthon (MyIE2)" title="Maxthon (MyIE2)">
<img src="http://<?=IMG_DOMAIN?>/slimbrowser.png" width="16" height="16" alt="SlimBrowser" title="SlimBrowser">
<img src="http://<?=IMG_DOMAIN?>/netscape.png" width="16" height="16" alt="Netscape Navigator" title="Netscape Navigator">
</font></center>
<?
include("lib/banners.php");
?>
</center>
</BODY>
</HTML>
<?php
mysql_close();
if (function_exists("save_debug")) save_debug();
} else {
header("Location: /install");
exit();
}
?>