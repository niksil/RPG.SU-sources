<?php

//ПЛАВИЛЬНАЯ МАСТЕРСКАЯ
$teplo = 10;
$nas_coal = 30;
$nas_samorodok = 50;

if (($hod==0)AND($timeout==0))
{
	$sel_founder = myquery("SELECT * FROM craft_build_founder WHERE user_id=$user_id");
	if ($sel_founder==false OR mysql_num_rows($sel_founder)==0)
	{
		$founder = Array();
		$founder['user_id']=$user_id;
		$founder['nas']=0;
		$founder['teplo']=0;
		$founder['res_id']=0;
		$founder['col_coal']=0;
		$founder['col_water']=0;
		$founder['col_res']=0;
		$founder['state']=0;
	}
	else
	{
		$founder = mysql_fetch_array($sel_founder);
	}
	$href = "?option=".$option."&part4&add=".$_GET['add']."";

	if (!isset($_GET['found_act']))
	{
		if (isset($_GET['mes'])) echo ('<b><font color="#C0FFC0">'.$_GET['mes'].'</font></b><br /><br />');
		
		echo 'Условия для работы в плавильне:<br /><br />
		<b>Чтобы выплавить слиток нужно:</b><br />
		1) Поддерживать оптимальное для плавления количество тепла в печи.<br />
		Тепло в печи регулируется с помощью добавления угля и воды в печь.<br />
		1 уголь = +'.$teplo.' тепла<br />
		1 вода = -'.$teplo.' тепла<br />
		2) Поддерживать насыщенность состава не менее 51%.<br />
		Насыщенность состава регулируется добавления сырья в плавильный котел.<br />
		1 руда = +'.$nas_coal.'% насыщенности состава<br />
		1 самородок = +'.$nas_samorodok.'% насыщенности состава<br /> <br /><br />
		<b>Таблица оптимальных величин тепла в печи для плавления:</b><br />
		Железная руда: требует от 59 до 70 тепла<br />
		Медная руда: требует от 49 до 60 тепла<br />
		Мифрилльная руда: требует от 69 до 80 тепла<br />
		Серебрянный самородок: требует от 29 до 40 тепла<br />
		Золотой самородок: требует от 39 до 50 тепла<br /><br />
		Для работы требуется иметь в руках предмет "Ковш литейщика"<br /><br /><br />';

		if (!checkCraftTrain($user_id,10))
		{
			echo '<br /><br />Ты не знаешь базовую профессию литейщика! Ты можешь выучить ее в городе у Учителя профессий.<br />Тебе запрещено заниматься этой профессией чаще, чем раз в 30 минут.<br /><br />';
		}
		else
		{
			echo '<a href="'.$href.'&found_act=1">Положить материал для переплавки в котел</a><br />';
			echo '<a href="'.$href.'&found_act=2">Положить уголь в печь</a><br />';
			echo '<a href="'.$href.'&found_act=3">Остудить печь водой</a><br />';
			echo '<a href="'.$href.'&found_act=4">Плавить</a><br />';
		}
	}
	else
	{
		$href.="&found_act=".$_GET['found_act'];
		if ($_GET['found_act']==1)
		{
			if (isset($_GET['add_res_id']))
			{
				$add_res_id = (int)$_GET['add_res_id'];
				$check = myquery("SELECT * FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id AND col>0 AND res_id IN (".$id_resource_for_founder.")");
				if (mysql_num_rows($check))
				{
					$res_user = mysql_fetch_array($check);
					$check2 = myquery("SELECT * FROM craft_build_founder WHERE user_id=$user_id");
					$stop = 0;
					if (mysql_num_rows($check2)>0)
					{
						$stop = 1;
						$res_founder = mysql_fetch_array($check2);
						if ($res_founder['res_id']==0 AND $res_founder['col_coal']==0 AND $res_founder['col_water']==0)
						{
							myquery("DELETE FROM craft_build_founder WHERE user_id=$user_id");
							$stop = 0;
						}
						else
						{
							if ($res_founder['res_id']!=$res_user['res_id'] AND $res_founder['res_id']!=0)
							{
								$stop = 2;
							}
						}
					}
					if (($stop==0) OR ($stop==1))
					{
						$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res_user['res_id'].""));
						myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=$add_res_id AND user_id=$user_id");
						myquery("DELETE FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id and col=0");
						myquery("UPDATE game_users SET CW=CW-".($res['weight'])." WHERE user_id=$user_id");	
						myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $add_res_id, 0, -1, ".time().", $user_id, 'z')");  
						$nas = 0;
						switch ($res_user['res_id'])
						{
							case $id_resource_iron_ore: $nas=$nas_coal; break;
							case $id_resource_copper_ore: $nas=$nas_coal; break;
							case $id_resource_mithril_ore: $nas=$nas_coal; break;
							case $id_resource_silver_nugget: $nas=$nas_samorodok; break;
							case $id_resource_gold_nugget: $nas=$nas_samorodok; break;
						}
						$founder['nas']+=$nas;
						$founder['nas']=min(100,$founder['nas']);
						if ($stop==0)
						{
							myquery("INSERT INTO craft_build_founder (user_id,res_id,col_res,nas) VALUES ($user_id,".$res_user['res_id'].",1,$nas)");
						}
						else
						{
							myquery("UPDATE craft_build_founder SET col_res=col_res+1,nas=LEAST(100,nas+$nas),res_id=".$res_user['res_id']." WHERE user_id=$user_id");
						}
						echo '<br />В плавильню добавлена 1 ед. ресурса <b><font color=red>'.$res['name'].'</font></b>';
					}
					else
					{
						echo '<br />В плавильню нельзя добавлять одновременно разные виды руды/самородков<br /><br />';
					}
				}
				else
				{
					echo '<br />Этот ресурс нельзя положить в плавильню!<br /><br />';
				}
			}
			$sel_res_founder = myquery("SELECT craft_resource.img1,craft_resource.img2,craft_resource.img3,craft_resource.name,craft_resource_user.* FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id IN (".$id_resource_for_founder.") AND craft_resource.id=craft_resource_user.res_id");
			if (mysql_num_rows($sel_res_founder)==0)
			{
				echo '<br /><br />У тебя нет ресурсов, которые могут быть переплавлены!<br /><br />';
			}                   
			else                                                                                                                                   
			{
				echo '<table>';
				while ($res = mysql_fetch_array($sel_res_founder))
				{
					echo '<tr><td><a href="'.$href.'&add_res_id='.$res['id'].'"><img border="0" src="http://'.IMG_DOMAIN.'/item/resources/'.$res['img3'].'.gif"></a></td><td><a href="'.$href.'&add_res_id='.$res['id'].'">Положить в плавильню</a> 1 ед. из '.$res['col'].' ед. ресурса <b><font color=red>'.$res['name'].'</font></b></td><tr>';					
				}
				echo '</table>';
			}  
		}
		elseif ($_GET['found_act']==2)
		{
			if (isset($_GET['add_res_id']))
			{
				$add_res_id = (int)$_GET['add_res_id'];
				$check = myquery("SELECT * FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id AND col>0 AND res_id IN (".$id_resource_coal.")");
				if (mysql_num_rows($check))
				{
					$res_user = mysql_fetch_array($check);
					$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res_user['res_id'].""));
					myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=$add_res_id AND user_id=$user_id");
					myquery("DELETE FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id and col=0");
					myquery("UPDATE game_users SET CW=CW-".($res['weight'])." WHERE user_id=$user_id");    
					$founder['teplo']+=$teplo;
					myquery("INSERT INTO craft_build_founder (user_id,teplo,col_coal) VALUES ($user_id,GREATEST(0,$teplo),1) ON DUPLICATE KEY UPDATE teplo=teplo+GREATEST(0,$teplo),col_coal=col_coal+1");
					myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $add_res_id, 0, -1, ".time().", $user_id, 'z')");       
					echo '<br />В печь добавлено '.($founder['col_coal']+1).' ед. ресурса <b><font color=red>'.$res['name'].'</font></b>';
				}
				else
				{
					echo '<br />У тебя нет ресурса "Уголь"!<br /><br />';
				}
			}
			$sel_res_founder = myquery("SELECT craft_resource.img1,craft_resource.img2,craft_resource.img3,craft_resource.name,craft_resource_user.* FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id IN (".$id_resource_coal.") AND craft_resource.id=craft_resource_user.res_id");
			if (mysql_num_rows($sel_res_founder)==0)
			{
				echo '<br /><br />У тебя нет ресурса "Уголь"!<br /><br />';
			}                   
			else                                                                                                                                   
			{
				echo '<table>';
				while ($res = mysql_fetch_array($sel_res_founder))
				{
					echo '<tr><td><a href="'.$href.'&add_res_id='.$res['id'].'"><img border="0" src="http://'.IMG_DOMAIN.'/item/resources/'.$res['img3'].'.gif"></a></td><td><a href="'.$href.'&add_res_id='.$res['id'].'">Положить уголь в печь</a> 1 ед. из '.$res['col'].' ед. ресурса <b><font color=red>'.$res['name'].'</font></b></td><tr>';                    
				}
				echo '</table>';
			}  
		}
		elseif ($_GET['found_act']==3)
		{
			if (isset($_GET['add_res_id']))
			{
				$add_res_id = (int)$_GET['add_res_id'];
				$check = myquery("SELECT * FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id AND col>0 AND res_id IN (".$id_resource_water.")");
				if (mysql_num_rows($check))
				{
					$res_user = mysql_fetch_array($check);
					$res = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".$res_user['res_id'].""));
					myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-1) WHERE id=$add_res_id AND user_id=$user_id");
					myquery("DELETE FROM craft_resource_user WHERE id=$add_res_id AND user_id=$user_id and col=0");
					myquery("UPDATE game_users SET CW=CW-".($res['weight'])." WHERE user_id=$user_id");    
					$colwater = 1;
					if ($founder['teplo']<$teplo)
					{
						$colwater = 0;
						$founder['teplo'] = 0;
					}
					else
					{
						$founder['teplo']=$founder['teplo']-$teplo;
					}
					myquery("INSERT INTO craft_build_founder (user_id,teplo,col_water) VALUES ($user_id,GREATEST(0,-$teplo),$colwater) ON DUPLICATE KEY UPDATE teplo=GREATEST(0,teplo-$teplo),col_water=col_water+$colwater");
					myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $add_res_id, 0, -1, ".time().", $user_id, 'z')");       
					echo '<br />Печь остужена 1 ед. ресурса <b><font color=red>'.$res['name'].'</font></b>';
				}
				else
				{
					echo '<br />У тебя нет ресурса "Вода"!<br /><br />';
				}
			}
			$sel_res_founder = myquery("SELECT craft_resource.img1,craft_resource.img2,craft_resource.img3,craft_resource.name,craft_resource_user.* FROM craft_resource_user,craft_resource WHERE craft_resource_user.user_id=$user_id AND craft_resource_user.col>0 AND craft_resource_user.res_id IN (".$id_resource_water.") AND craft_resource.id=craft_resource_user.res_id");
			if (mysql_num_rows($sel_res_founder)==0)
			{
				echo '<br /><br />У тебя нет ресурса "Вода"!<br /><br />';
			}                   
			else                                                                                                                                   
			{
				echo '<table>';
				while ($res = mysql_fetch_array($sel_res_founder))
				{
					echo '<tr><td><a href="'.$href.'&add_res_id='.$res['id'].'"><img border="0" src="http://'.IMG_DOMAIN.'/item/resources/'.$res['img3'].'.gif"></a></td><td><a href="'.$href.'&add_res_id='.$res['id'].'">Остудить печь</a> 1 ед. из '.$res['col'].' ед. ресурса <b><font color=red>'.$res['name'].'</font></b></td><tr>';                    
				}
				echo '</table>';
			}  
		}
		elseif ($_GET['found_act']==4)
		{
			if ($founder['res_id']==0)
			{
				echo '<br /><br />А что ты '.echo_sex('собрался','собралась').' переплавлять в плавильне?<br /><br />';
			}
			else
			{
				$check_item = myquery("SELECT * FROM game_items WHERE item_id=".$id_item_founder." AND user_id=$user_id AND priznak=0 AND used=21 AND item_uselife>0");
				if (mysql_num_rows($check_item)>0)
				{
					//начинаем работу. капча и все такое
					if (isset($_POST['digit']) OR isset($_POST['begin']))
					{
						if (isset($_SESSION['captcha']) and isset($_POST['digit']) and $_POST['digit']==$_SESSION['captcha'] and checkCraftTrain($user_id,10))
						{
							craft_setFunc($user_id,10);
							set_delay_reason_id($user_id,34);
							if (DOMAIN == TEST_SERVER) 
							{
								$dlit=5;
							}
							else
							{
								$dlit = max(120,600-getCraftLevel($user_id,10)*20);
							}
							$build_id='founder';
							myquery("DELETE FROM craft_build_rab WHERE user_id=$user_id");
							myquery("INSERT INTO craft_build_rab (user_id,build_id,date_rab,dlit) VALUES ($user_id,'$build_id',".time().",$dlit)");
							ForceFunc($user_id,func_craft);
							setLocation("../craft.php");
						}
						else
						{
							echo 'Ты не можешь начать работу на плавильне (введен неправильный код или у тебя недостаточно свободного места в инвентаре)!<br><br><br><br>'; 
						}
					}
					else
					{
						echo 'Для начала работы введи указанный ниже код <br>и нажми кнопку "Начать работу на плавильне"<br>';
						echo '<br><img src="../captcha/index.php?'.time().'">';
						$action = $href;
						echo '<form autocomplete="off" action="'.$action.'" method="POST" name="captcha"><br>
						<input type="text" size=6 maxsize=6 name="digit"><br>
						<input type="submit" name="subm" value="Начать работу на плавильне">
						</form>';
					}
				}
				else
				{
					echo '<br /><br />Перед началом работы на плавильне необходимо иметь в руках предмет "Ковш литейщика"<br /><br />';
				}
			}
		} 
		echo '<br /><br /><center><a href="?option='.$_GET['option'].'&part4&add='.$_GET['add'].'">Вернуться в плавильню</a></center><br /><br />';       
	}
	if ($founder['res_id']>0)
	{		
		echo '<br /><br />В плавильном котле находится '.$founder['col_res'].' ед. ресурса <b><font color=red>'.mysqlresult(myquery("SELECT name FROM craft_resource WHERE id=".$founder['res_id'].""),0,0).'</font></b>';
	}
	echo '<br /><br />Насыщенность состава в плавильном котле: '.$founder['nas'].', количество тепла в печи '.$founder['teplo'].'.<br /><br />';
} 
elseif ($hod-time()+$timeout<=0)
{
	//окончание работы
	$founder = mysql_fetch_array(myquery("SELECT * FROM craft_build_founder WHERE user_id=$user_id"));
	$fault = 1;
	$res_id_bullion = 0;
	switch ($founder['res_id'])
	{
		case $id_resource_iron_ore:
		{
			if ($founder['nas']>=51 AND $founder['teplo']>=59 AND $founder['teplo']<70)
			{
				$fault = 0;
				$res_id_bullion = $id_resource_iron_bullion;
			}
		}
		break;
		case $id_resource_copper_ore:
		{
			if ($founder['nas']>=51 AND $founder['teplo']>=49 AND $founder['teplo']<60)
			{
				$fault = 0;
				$res_id_bullion = $id_resource_copper_bullion;
			}
		}
		break;
		case $id_resource_silver_nugget:
		{
			if ($founder['nas']>=51 AND $founder['teplo']>=29 AND $founder['teplo']<40)
			{
				$fault = 0;
				$res_id_bullion = $id_resource_silver_bullion;
			}
		}
		break;
		case $id_resource_mithril_ore:
		{
			if ($founder['nas']>=51 AND $founder['teplo']>=69 AND $founder['teplo']<80)
			{
				$fault = 0;
				$res_id_bullion = $id_resource_mithril_bullion;
			}
		}
		break;
		case $id_resource_gold_nugget:
		{
			if ($founder['nas']>=51 AND $founder['teplo']>=39 AND $founder['teplo']<50)
			{
				$fault = 0;
				$res_id_bullion = $id_resource_gold_bullion;
			}
		}
		break;
	}
	$add_query = "";
	if ($fault==0)
	{
		if ($founder['state']==2)
		{
			$res_in = mysql_fetch_array(myquery("SELECT weight,name FROM craft_resource WHERE id=$res_id_bullion"));
			$change_weight = (double)$res_in['weight'];
			$prov=mysql_result(myquery("select count(*) from game_wm where user_id=$user_id and type=1"),0,0);
			if ($char['CC']-$char['CW']>$change_weight OR $prov>0)
			{
				myquery("insert into craft_resource_user (user_id, res_id, col) values ($user_id, $res_id_bullion, 1) ON DUPLICATE KEY UPDATE col=col+1");
				$add_query.=',CW=CW+'.$change_weight.'';
				myquery("UPDATE game_items SET item_uselife=item_uselife-".(mt_rand(400,600)/100)." WHERE user_id=$user_id AND used=21 AND priznak=0");
				list($id_item,$cur_uselife) = mysql_fetch_array(myquery("SELECT id,item_uselife FROM game_items WHERE priznak=0 AND user_id=$user_id AND used=21"));
				if ($cur_uselife<=0)
				{
					$Item = new Item($id_item);
					$Item->down();
				} 
				$mes='Получен ресурс: <i>'.$res_in['name'].'</i> в количестве 1 ед.';
				setCraftTimes($user_id,10,3,1);
				myquery("insert into craft_stat (build_id, gp, res_id, dob, vip, dat, user, type) values (0, 0, $res_id_bullion, 0, 1, ".time().", $user_id, 'z')");
			}
			else
			{
				$mes='Неудачная попытка работы на плавильне. Проверь, хватает ли у тебя места для новых ресурсов в инвентаре!';
			}
			myquery("DELETE FROM craft_build_founder WHERE user_id=$user_id");
		}
		else
		{
			mt_srand(make_seed());
			$new_nas = mt_rand(1,$founder['nas']-10);
			mt_srand(make_seed());
			$new_teplo = mt_rand(1,100);
			$new_state = $founder['state']+1;
			$mes='Выполнена '.$new_state.' из 3 стадий плавления.';
			myquery("UPDATE craft_build_founder SET nas=$new_nas,teplo=$new_teplo,state=state+1 WHERE user_id=$user_id");
		}	
	}
	else
	{
		$mes='Неудача, ты неправильно '.echo_sex('сбалансировал','сбалансировала').' тепло печи или насыщенность состава, все материалы потрачены впустую.';
		myquery("UPDATE craft_build_founder SET state=0,nas=0,teplo=0,res_id=0,col_res=0,col_coal=0,col_water=0 WHERE user_id=$user_id");        
	}
	$option = 18;
	//$option=19;
	$url = 'lib/town.php?option='.$option.'&part4&add=18&mes='.$mes;
	setLocation($url);
	//echo'<meta http-equiv="refresh" content="10;url='.$url.'">';
	exit_from_craft($add_query,1);
	/*echo '<br /><br /><center><a href="lib/town.php?option='.$option.'&part4&add=18">Вернуться в плавильню</a></center><br /><br />';       
     //Мы всё ещё в крафте! (не выходим то есть) хотя подход окончен
	exit_from_craft($add_query, 0);
     //В город. Чтобы крафт не на весь экран был.
	ForceFunc($user_id,func_city);
	 //Уточним крафт - игрок мог заходить на форум, смотреть дневники итд...
	set_delay_reason_id($user_id,34);
	 //Вернём игрока в город, где он и плавил.
	setLocation('/lib/town.php?option=18&part4&add=18');*/
}
else
{
	if (($hod>0) AND ($hod<=time()))
	{
		//еще работает таймер
		$founder = mysql_fetch_array(myquery("SELECT craft_build_founder.*,craft_resource.name FROM craft_build_founder,craft_resource WHERE craft_build_founder.user_id=$user_id AND craft_resource.id=craft_build_founder.res_id"));  
		echo'Ты '.echo_sex('занят','занята').' работой в плавильном цехе<br /><br />Ты переплавляешь ресурс '.$founder['name'].'<br /><br />Стадия плавления - '.($founder['state']+1).' из 3<br /><br />'; 

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
