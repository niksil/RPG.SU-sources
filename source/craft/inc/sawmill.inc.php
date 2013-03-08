<?php
//ЛЕСОПИЛКА
$est_doska = 0;
$est_brevno = 0;
$odet_pila = 0;
	
if (($hod==0)AND($timeout==0))
{
	if (!checkCraftTrain($user_id,7))
	{
		echo '<br /><br />Ты не знаешь базовую профессию плотника! Ты можешь выучить ее в городе у Учителя профессий.<br />Тебе запрещено заниматься этой профессией чаще, чем раз в 30 минут.<br /><br />';
		//exit_from_craft();
	}
	else
	{
		if (isset($from_house))
		{
			$gp = 0;
		}
		else
		{
			$gp = 3;
		}
		
		$add_url = '';
		if (isset($from_house))
		{
			$add_url='&option='.$option.'&part4&add='.$build_id;
		}

		$sel = myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=$id_resource_brevno AND col>0");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			$est_brevno = mysql_result($sel,0,0);
		}
		$sel = myquery("SELECT col FROM craft_resource_user WHERE user_id=$user_id AND res_id=$id_resource_doska AND col>0");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			$est_doska = mysql_result($sel,0,0);
		}
		$sel = myquery("SELECT id FROM game_items WHERE item_id=$id_item_pila AND used=21 AND user_id=$user_id AND priznak=0 AND item_uselife>0");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			$odet_pila = 1;
		}
		
		$type_work = 0;
		$res_id = 0;
		if (isset($_GET['raspil_brevno']) AND $odet_pila AND $est_brevno AND $char['GP']>=$gp)
		{
			$type_work = 1;
			$res_id = $id_resource_brevno;
		}
		if (isset($_GET['doski']) AND $odet_pila AND $est_doska AND $char['GP']>=$gp)
		{
			if (isset($_GET['strel']))
			{
				$type_work = 2;
			}
			if (isset($_GET['topor']))
			{
				$type_work = 3;
			}
			if (isset($_GET['kopi']))
			{
				$type_work = 4;
			}
			$res_id = $id_resource_doska;
		}

		if ($type_work==0)
		{
			QuoteTable('open');
			if (isset($_GET['mes']) and $_GET['mes']!='') echo ('<b><center><font color="#C0FFC0">'.$_GET['mes'].'</font></center></b><br />');
			$res = 0;
			//начальные запросы от игрока
			echo '<center>';
			if (!$odet_pila)
			{
				echo '<br/>Для работы на лесопилке необходимо иметь в руках рабочий инструмент - пилу!<br />';
			} 
			if ($gp>0)
			{
				echo '<br/><b>Для работы в общественной лесопилке необходимо заплатить '.$gp.' '.pluralForm($gp,'монету','монеты','монет').'!</b><br/>';
			} 
			if ($odet_pila AND $est_brevno AND $char['GP']>=$gp)
			{
				echo '<br/><a href="?raspil_brevno'.$add_url.'">Распилить бревно на доски</a><br />';
				$res = 1;
			}
			if ($odet_pila AND $est_doska AND $char['GP']>=$gp)
			{
				echo '<br /><a href="?doski&strel'.$add_url.'">Распилить доски на черенки для стрел</a><br />';
				echo '<br /><a href="?doski&topor'.$add_url.'">Распилить доски на рукояти для топоров</a><br />';
				echo '<br /><a href="?doski&kopi'.$add_url.'">Распилить доски на древки для копий</a><br />';
				$res = 1;
			}
			if ($res==0 and $odet_pila)
			{
				echo '<br/>Для работы на лесопилке необходимо иметь ресурсы: Бревно или Доска!<br />';
			} 
			echo '</center><br />';
			QuoteTable('close');
		}
		else
		{
			//начинаем работу. капча и все такое
			if (isset($_POST['digit']) OR isset($_POST['begin']))
			{
				//начинаем работу на лесопилке
				$craft = 1;

				$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$char['user_id']." AND type=1"),0,0);
				$res = mysql_fetch_array(myquery("SELECT weight FROM craft_resource WHERE id=$res_id"));
				if (($char['CC']-$char['CW'])<$res['weight'])
				{
					if ($prov==0) $craft = 0;
				}
				if ($char['GP']<$gp)
				{
					$craft = 0;
				}
						
				if ($craft==1 and isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'] and checkCraftTrain($user_id,7))
				{
					craft_setFunc($user_id,7);
					set_delay_reason_id($user_id,31);
					if ($gp>0)
					{
						myquery("UPDATE game_users SET GP=GP-$gp,CW=CW-".($gp*money_weight)." WHERE user_id=$user_id");
						setGP($user_id,-$gp,63);
					}
					if (DOMAIN == TEST_SERVER) 
					{
						$dlit=5;
					}
					else
					{
						$dlit = max(120,600-getCraftLevel($user_id,7)*20);
					}
					if (isset($from_house))
					{
						$build_id='sawmill';
					}
					$ad = 0;
					if (isset($_GET['part4'])) $ad=$_GET['add'];
					myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
					myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit,eliksir,`add`) VALUES ($user_id,'$build_id',".time().",$dlit,'$type_work',$ad)");
					ForceFunc($user_id,func_craft);
					setLocation("../craft.php");
				}
				else
				{
					echo 'Ты не можешь начать работу на лесопилке (введен неправильный код или у тебя недостаточно свободного места в инвентаре)!<br><br><br><br>'; 
				}
			}
			else
			{
				echo 'Для начала работы введи указанный ниже код <br>и нажми кнопку "Начать работу на лесопилке"<br>';
				echo '<br><img src="../captcha/index.php?'.time().'">';
				$action = "";
				if (isset($from_house))
				{
					if (isset($_GET['raspil_brevno']))
					{
						$action = "?raspil_brevno".$add_url;
					}
					if (isset($_GET['doski']))
					{
						if (isset($_GET['strel']))
						{
							$action = "?doski&strel".$add_url;
						}
						if (isset($_GET['topor']))
						{
							$action = "?doski&topor".$add_url;
						}
						if (isset($_GET['kopi']))
						{
							$action = "?doski&kopi".$add_url;
						}
					}
				}
				echo '<form autocomplete="off" action="'.$action.'" method="POST" name="captcha"><br>
				<input type="text" size=6 maxsize=6 name="digit"><br>
				<input type="submit" name="subm" value="Начать работу на лесопилке">
				</form>';
			}
		}    
	}
}
elseif ($hod-time()+$timeout<=0)
{
	include(getenv("DOCUMENT_ROOT")."/craft/inc/sawmill_endtime.inc.php");
}
else
{
	if (($hod>0) AND ($hod<=time()))
	{
		//еще работает таймер  
		if ($rab['eliksir']==1)
		{                                             
			echo'Ты '.echo_sex('занят','занята').' распилкой бревна на доски'; 
		}
		elseif ($rab['eliksir']==2)
		{                                             
			echo'Ты '.echo_sex('занят','занята').' распилкой доски на черенки для стрел'; 
		}
		elseif ($rab['eliksir']==3)
		{                                             
			echo'Ты '.echo_sex('занят','занята').' распилкой доски на рукояти для топоров'; 
		}
		elseif ($rab['eliksir']==4)
		{                                             
			echo'Ты '.echo_sex('занят','занята').' распилкой доски на древки для копий'; 
		}

		echo '<br>До конца работы осталось: <font color=ff0000><b><span id="timerr1">'.($hod+$timeout-time()).'</span></b></font> секунд</div> 
		<script language="JavaScript" type="text/javascript">
		function tim()
		{
			timer = document.getElementById("timerr1");
			if (timer.innerHTML<=0)
				location.reload();
			else
			{
				timer.innerHTML=timer.innerHTML-1;
				window.setTimeout("tim()",1000);
				if (timer.innerHTML%120==0)
				{
					location.reload();
				}
			}
		}
		tim();
		</script>';
	} 
}
?>