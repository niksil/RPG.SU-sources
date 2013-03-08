<?

if (function_exists("start_debug")) start_debug(); 

$stroitel=$char['stroitel'];
if ($stroitel>=1)
{
	echo'<b>Ваша специализация строителя: '.$stroitel.'</b><br>Перечень доступных для постройки зданий:<br><br>';

	if (isset($create))
	{
		$create=(int)$create;
		//проверка ресурсов
		if ($char['clan_id']==1)
		{
			$select3=myquery("select * from craft_build where id='$create'");
		}
		else
		{
			$select3=myquery("select * from craft_build where admin=0 and id='$create'");
		}
		if (mysql_num_rows($select3))
		{
			$build3=mysql_fetch_array($select3);

			//проверка уровня специализации
			if ($build3['lev_need']>$stroitel)
			{
				$no=1;
				echo '<font color=red>Нехватает уровеня специализации строителя</font><br>';	
			}
		
			if ($build3['res_need']!='')
			{
				$a=explode("|",$build3['res_need']);
				for ($i=0;$i<count($a);$i++)
				{
					$b=explode("-",$a[$i]);
					$select1=myquery("select * from craft_resource_user where res_id='$b[0]' and user_id='$user_id' and col>='$b[1]'");
					if (!mysql_num_rows($select1)) 
					{
						$no=1;
						echo 'Нехватает ресурса: ';	
						$select=myquery("select * from craft_resource where id='$b[0]'");
						$build=mysql_fetch_array($select);
						echo "$build[name]<br>";
					}
				}
			}
		
			//проверка денег
			if ($build3['cost']>$char['GP'])
			{
				$no=1;
				echo '<br><font color=red>Нехватает денег</font>';	
			}
			
			$already = myquery("SELECT * FROM craft_build_user WHERE map=".$char['map_name']." AND x=".$char['map_xpos']." AND y=".$char['map_ypos']."");
			if (mysql_num_rows($already)>0)
			{
				$no=1;
				echo '<br><font color=red>Ты можешь построить только 1 здание на каждой гексе</font>';	
			}
			
			//если все хватает строим
			if (!isset($no) or $user_id==1)
			{
				if ($build3['res_need']!='')
				{
					$a=explode("|",$build3['res_need']);
					for ($i=0;$i<count($a);$i++)
					{
						$b=explode("-",$a[$i]);
						$select1=myquery("update craft_resource_user set col=GREATEST(0,col-$b[1]) where user_id='$user_id' and res_id='$b[0]'");
					}
				}	
				$query = "INSERT INTO craft_build_user (map, x, y, user_id, type, create_date, create_time, dohod, gold, sell, status) VALUES ('".$char['map_name']."', '".$char['map_xpos']."', '".$char['map_ypos']."', '$user_id', '".$build3['id']."', '".time()."', '".$build3['create_time']."', '', 0 ,0 ,'0')";
				myquery($query);
				myquery("UPDATE game_users SET gp=gp-$build3[cost], func='craft',hod=".time()." WHERE user_id='$user_id'");
				setGP($user_id,-$build3['cost'],15);
				echo'Здание будет построено через '.$build3['create_time'].' сек.<script>location.replace("craft.php");</script>';
			}
		}
	}

	if (isset($id))
	{
		$id=(int)$id;
		if ($char['clan_id']==1)
		{
			$select=myquery("select * from craft_build where id=$id");
		}
		else
		{
			$select=myquery("select * from craft_build where admin=0 and where id=$id");
		}
		if (mysql_num_rows($select))
		{
			$build=mysql_fetch_array($select);
			if ($build['dom']=='0')
			{
				echo"<font color=yellow><b>$build[name]</b></font><br>Рабочих мест: <font color=yellow>$build[col]</font><br><br>Производит:<br>";
				dohod($build['res_dob'],0);
				echo"<br><b><font color=red>Требует для постройки:</font></b><br>";
				treb($build['res_need']);
				echo"<br>Необходим уровень строительства: <font color=red>$build[lev_need]</font><br>";

				echo"<br>Особенности:";
				if ($build['race']!=0) echo"<br> может работать только раса ".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$build['race'].""),0,0)."";
				elseif ($build['clevel']<'0') echo"<br> Работать можно после $build[clevel] уровня";
				elseif ($build['item']!='' and $build['item']!='0') echo"<br> Дла работы необходим предмет $build[item]";
				else echo" нет.";
			}

			if ($build['dom']=='1')
			{
				echo"<b>$build[name]</b><br>Вмещает : $build[col] предметов<br>";
				echo"<br>Требует для постройки:<br>";
				treb($build['res_need']);
				echo"Необходим уровень строительства: $build[lev_need]<br>";

				echo"<br>Особенности:";
				if ($build['race']!=0) echo"<br> Хранить предметы может только раса ".mysql_result(myquery("SELECT name FROM game_har WHERE id=".$build['race'].""),0,0)."";
				elseif ($build['clevel']<'0') echo"<br> Хранить предметы можно после $build[clevel] уровня";
				else echo" нет.";
			}

			echo "<br><br>Цена <font color=red><b>$build[cost]</b></font> золотых<br><a href=?func=main&act=build&create=$build[id]>Построить</a>";
		}
	}


	if (!isset($id) and !isset($create))
	{
		if ($char['clan_id']==1)
		{
			$select=myquery("select * from craft_build");
		}
		else
		{
			$select=myquery("select * from craft_build where admin=0");
		}
		while ($build=mysql_fetch_array($select))
		{
			if ($build['lev_need']>$stroitel) echo '<font color=aaaaff>';
			else echo '<font color=#80FFFF>';
			echo'<a href=?func=main&act=build&id='.$build['id'].'>'.$build['name'].'</a> - '.$build['cost'].'</font><br>';
		}
	}
}

if (function_exists("save_debug")) save_debug(); 

?>