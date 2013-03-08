<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['users'] >= 1)
{
	echo '<div id="content" onclick="hideSuggestions();"><center>Управление игроками:</center><br><br>';
	echo '<center><font size="1" face="Verdana" color="#ffffff">Поиск: <input type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
	<input name="" type="button" value="Найти" onClick="location.href=\'admin.php?opt=main&option=users&name_v=\'+document.getElementById(\'keyword\').value">';

	if (isset($_GET['obn']))
	{
		$pers=myquery("select * from game_users where name='$obn'");
		if (!mysql_num_rows($pers)) $pers=myquery("select * from game_users_archive where name='$obn'");
		$p=mysql_fetch_array($pers);
		if (!isset($_POST['do_obnul']))
		{
			echo "<form name=frm method=post>";
			// Формула накопленного опыта
			$level=$p['clevel'];
			$i=0;
			$allexp = get_exp_from_level($level);
			$allgp = 0;
			for($i=0;$i<=$level-1;$i++)
			{
				if ($i >= 0 and $i < 9) $gp=50;        
				if ($i == 9) $gp=300;

				if ($i >= 10 and $i < 19) $gp=100;
				if ($i == 19) $gp=500;

				if ($i >= 20 and $i < 29) $gp=200;
				if ($i == 29) $gp=1000;

				if ($i >= 30 and $i < 39) $gp=300;
				if ($i == 39) $gp=1500; 
				
				$allgp+=$gp;                
			}
			echo'<font face=verdana size=2><b>'.$obn.' накопил <font color=ff0000>'.$allexp.'</font> опыта за '.$p['clevel'].' уровней (+ '.$p['EXP'].' не использованного опыта)<br>
			<input name="do_obnul" type="submit" value="Обновить игрока: '.$obn.'">
			<br /><br />
			<input name="allexp" type="hidden" value="'.$allexp.'">
			<input name="allgp" type="hidden" value="'.$allgp.'">
			</form>';
		}
		else
		{
			//Обновление харок, уровня и опыта
            do_obnul($p['user_id'],3);
            /*
			$all=$allexp+$p['EXP'];

			$result=myquery("select * from game_har where id=".$p['race']."");
			$row=mysql_fetch_array($result);

			$hp_maxn=$row["hp_max"];
			$mp_maxn=$row["mp_max"];
			$stm_maxn=$row["stm_max"];
			$strn=$row["str"];
			$ntln=$row["ntl"];
			$pien=$row["pie"];
			$vitn=$row["vit"];
			$dexn=$row["dex"];
			$spdn=$row["spd"];

			$upd=myquery("update game_users set clevel=0, HP=$hp_maxn, HP_MAX=$hp_maxn, HP_MAXX=$hp_maxn, MP=$mp_maxn, MP_MAX=$mp_maxn, STM=$stm_maxn, STM_MAX=$stm_maxn, EXP=$all, STR=$strn, NTL=$ntln, PIE=$pien, VIT=$vitn, DEX=$dexn, SPD=$spdn, STR_MAX=$strn, NTL_MAX=$ntln, PIE_MAX=$pien, VIT_MAX=$vitn, DEX_MAX=$dexn, SPD_MAX=$spdn, CC=40, lucky=0, lucky_max=0 where user_id=".$p['user_id']." limit 1");
			$upd=myquery("update game_users_archive set clevel=0, HP=$hp_maxn, HP_MAX=$hp_maxn, HP_MAXX=$hp_maxn, MP=$mp_maxn, MP_MAX=$mp_maxn, STM=$stm_maxn, STM_MAX=$stm_maxn, EXP=$all, STR=$strn, NTL=$ntln, PIE=$pien, VIT=$vitn, DEX=$dexn, SPD=$spdn, STR_MAX=$strn, NTL_MAX=$ntln, PIE_MAX=$pien, VIT_MAX=$vitn, DEX_MAX=$dexn, SPD_MAX=$spdn, CC=40, lucky=0, lucky_max=0 where user_id=".$p['user_id']." limit 1");

			echo'<br><br>1. Обновили все харки, опыт, уровень!<br>';

			//обновление навыков и специализаций
			$gp=0;
			$sel = myquery("SELECT SUM(game_vsadnik.cena) FROM game_vsadnik,game_users_horses WHERE game_vsadnik.id=game_users_horses.horse_id AND game_users_horses.user_id=".$p['user_id']." GROUP BY game_users_horses.user_id");
			$gp = mysqlresult($sel,0,0);
			myquery("DELETE FROM game_users_horses WHERE user_id=".$p['user_id']."");
			$gp-=$allgp;
			$upd=myquery("delete from game_spets_item where user_id=".$p['user_id']."");
			$upd=myquery("update game_users set MS_ART=0, MS_KULAK=0, MS_LUK=0, MS_WEAPON=0, MS_VOR=0, MS_VSADNIK=0, MS_PARIR=0, MS_LEK=0, MS_KUZN=0, MS_SPEAR=0, MS_AXE=0, MS_SWORD=0, MS_THROW=0, skill_war=0, skill_music=0, skill_cook=0, skill_art=0, skill_explor=0, skill_craft=0,skill_card=0,skill_pet=0,skill_uknow=0, dvij=1, exam=0, bound=0,vsadnik=0,GP=GP+$gp where user_id=".$p['user_id']."");
			$upd=myquery("update game_users_archive set MS_ART=0, MS_KULAK=0, MS_LUK=0, MS_WEAPON=0, MS_VOR=0, MS_VSADNIK=0, MS_PARIR=0, MS_LEK=0, MS_KUZN=0, MS_SPEAR=0, MS_AXE=0, MS_SWORD=0, MS_THROW=0, skill_war=0, skill_music=0, skill_cook=0, skill_art=0, skill_explor=0, skill_craft=0,skill_card=0,skill_pet=0,skill_uknow=0,dvij=1, exam=0, bound=0,vsadnik=0,GP=GP+$gp where user_id=".$p['user_id']."");
			setGP($p['user_id'],$gp,29);
			echo'2. Обновили навыки и специализации.<br>';

			//Снятие всех предметов
			$upd=myquery("update game_items set used=0 where user_id=".$p['user_id']." and priznak=0");
			echo'3. Сняли все предметы.<br>';
            */
			echo'<center>Поздравляю! Ты '.echo_sex('обновил','обновила').' игрока: '.$obn.'.';
		}
	}

if(isset($name_v))
{
	$usr=myquery("select game_users.*,game_users_func.func_id,combat_users.combat_id as boy,combat_users.time_last_active as hod from game_users left join (game_users_func) on (game_users.user_id=game_users_func.user_id) left join (combat_users) on (combat_users.user_id=game_users_func.user_id) where game_users.name='$name_v'");
	if (!mysql_num_rows($usr)) $usr=myquery("select game_users_archive.*,game_users_func.func_id,combat_users.combat_id as boy,combat_users.time_last_active as hod from game_users_archive left join (game_users_func) on (game_users_archive.user_id=game_users_func.user_id) left join (combat_users) on (combat_users.user_id=game_users_func.user_id) where game_users_archive.name='$name_v'");
	if (mysql_num_rows($usr))
	{
		$user = mysql_fetch_array($usr);
		$user_active = mysql_fetch_array(myquery("SELECT * FROM game_users_active WHERE user_id=".$user['user_id'].""));
		$user_map = mysql_fetch_array(myquery("SELECT map_name,map_xpos,map_ypos FROM game_users_map WHERE user_id=".$user['user_id'].""));
		$user_data=mysql_fetch_array(myquery("SELECT * FROM game_users_data WHERE user_id=".$user['user_id'].""));
		if (!isset($_POST['save']))
		{
			echo'<center><form action="" name="form1" method="post" autocomplete="off">
			<table border="0" width="100%">
			<tr><td width="200">id</td><td>'.$user['user_id'].'</td></tr>
			<tr><td width="200">Логин</td><td><input name="user_name" value="'.$user['user_name'].'" type="text" size="35"><input name="user_name_old" value="'.$user['user_name'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">e-mail</td><td><input name="email" value="'.$user_data['email'].'" type="text" size="35"><input name="email_old" value="'.$user_data['email'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Имя</td><td><input name="name" value="'.$user['name'].'" type="text" size="35"><input name="name_old" value="'.$user['name'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Статус</td><td><input name="STATUS" value="'.$user_data['STATUS'].'" type="text" size="35"><input name="STATUS_old" value="'.$user_data['STATUS'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Город</td><td><input name="gorod" value="'.$user_data['gorod'].'" type="text" size="35"><input name="gorod_old" value="'.$user_data['gorod'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Хобби</td><td><textarea cols=20 rows=5 name="hobbi">'.$user_data['hobbi'].'</textarea><textarea style="display:none" cols=0 rows=0 name="hobbi_old">'.$user_data['hobbi'].'</textarea></td></tr>
			<tr><td width="200">Информация</td><td><textarea cols=20 rows=5 name="info">'.$user_data['info'].'</textarea><textarea style="display:none" cols=0 rows=0 name="info_old">'.$user_data['info'].'</textarea></td></tr>
			<tr><td width="200">День рождения</td><td>
			<select name="dn">
			<option value=0></option>';
			for ($i=1;$i<32;$i++)
			{
				echo '<option'; if ($i==$user_data['dr_date']) echo ' selected'; echo'>'.$i.'</option>';
			}
			echo'</select><input name="dn_old" value="'.$user_data['dr_date'].'" type="hidden" size="35">
			<select name="ms">
			<option value=0></option>';
			for ($i=1;$i<13;$i++)
			{
				echo '<option'; if ($i==$user_data['dr_month']) echo ' selected'; echo'>'.$i.'</option>';
			}
			echo'</select><input name="ms_old" value="'.$user_data['dr_month'].'" type="hidden" size="35">
			<select name="god">
			<option value=0></option>';
			for ($i=1960;$i<2005;$i++)
			{
				echo '<option'; if ($i==$user_data['dr_year']) echo ' selected'; echo'>'.$i.'</option>';
			}
			echo'</select><input name="god_old" value="'.$user_data['dr_year'].'" type="hidden" size="35">
			</td></tr>
			<tr><td width="200">Пол игрока</td><td><select name="sex1">
			<option value=""'; if ($user_data['sex']=='') echo ' SELECTED'; echo'></option>
			<option value="male"'; if ($user_data['sex']=='male') echo ' SELECTED'; echo'>Мужской</option>
			<option value="female"'; if ($user_data['sex']=='female') echo ' SELECTED'; echo'>Женский</option>
			</select><input name="sex1_old" value="'.$user_data['sex'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">&nbsp;</td><td></td></tr>
			
			<tr><td width="200">Сменить пароль:</td><td><input name="newpass" type="text" value="" size="20" maxlength="20"> Новый пароль</td></tr>

			<tr><td width="200" align="center" colspan=2>
			<input name="save" type="submit" value="Сохранить">
			</td></tr>
			<tr><td width="200">&nbsp;</td><td></td></tr>';

			if ($adm['users'] == 2)
			{
				echo'
				<tr>
				<td width="200">HP / HP_MAX / HP_MAXX</td>
				<td><input name="HP" value="'.$user['HP'].'" type="text" size="5"><input name="HP_old" value="'.$user['HP'].'" type="hidden" size="35"> / <input name="HP_MAX" value="'.$user['HP_MAX'].'" type="text" size="5"><input name="HP_MAX_old" value="'.$user['HP_MAX'].'" type="hidden" size="35"> / <input name="HP_MAXX" value="'.$user['HP_MAXX'].'" type="text" size="5"><input name="HP_MAXX_old" value="'.$user['HP_MAXX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
				<td width="200">MP / MP_MAX</td>
				<td><input name="MP" value="'.$user['MP'].'" type="text" size="5"><input name="MP_old" value="'.$user['MP'].'" type="hidden" size="35"> / <input name="MP_MAX" value="'.$user['MP_MAX'].'" type="text" size="5"><input name="MP_MAX_old" value="'.$user['MP_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
				<td width="200">STM / STM_MAX</td>
				<td><input name="STM" value="'.$user['STM'].'" type="text" size="5"><input name="STM_old" value="'.$user['STM'].'" type="hidden" size="35"> / <input name="STM_MAX" value="'.$user['STM_MAX'].'" type="text" size="5"><input name="STM_MAX_old" value="'.$user['STM_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr><td width="200">Опыт</td><td><input name="EXP" value="'.$user['EXP'].'" type="text" size="15"><input name="EXP_old" value="'.$user['EXP'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Монеты</td><td><input name="GP" value="'.$user['GP'].'" type="text" size="15"><input name="GP_old" value="'.$user['GP'].'" type="hidden" size="35"></td></tr>

				<tr>
					<td width="200">Сила / Сила_MAX</td>
					<td><input name="STR" value="'.$user['STR'].'" type="text" size="5"><input name="STR_old" value="'.$user['STR'].'" type="hidden" size="35"> / <input name="STR_MAX" value="'.$user['STR_MAX'].'" type="text" size="5"><input name="STR_MAX_old" value="'.$user['STR_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
					<td width="200">Интеллект / Интеллект_MAX</td>
					<td><input name="NTL" value="'.$user['NTL'].'" type="text" size="5"><input name="NTL_old" value="'.$user['NTL'].'" type="hidden" size="35"> / <input name="NTL_MAX" value="'.$user['NTL_MAX'].'" type="text" size="5"><input name="NTL_MAX_old" value="'.$user['NTL_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
					<td width="200">Ловкость / Ловкость_MAX</td>
					<td><input name="PIE" value="'.$user['PIE'].'" type="text" size="5"><input name="PIE_old" value="'.$user['PIE'].'" type="hidden" size="35"> / <input name="PIE_MAX" value="'.$user['PIE_MAX'].'" type="text" size="5"><input name="PIE_MAX_old" value="'.$user['PIE_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
					<td width="200">Защита / Защита_MAX</td>
					<td><input name="VIT" value="'.$user['VIT'].'" type="text" size="5"><input name="VIT_old" value="'.$user['VIT'].'" type="hidden" size="35"> / <input name="VIT_MAX" value="'.$user['VIT_MAX'].'" type="text" size="5"><input name="VIT_MAX_old" value="'.$user['VIT_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
					<td width="200">Выносл. / Выносл._MAX</td>
					<td><input name="DEX" value="'.$user['DEX'].'" type="text" size="5"><input name="DEX_old" value="'.$user['DEX'].'" type="hidden" size="35"> / <input name="DEX_MAX" value="'.$user['DEX_MAX'].'" type="text" size="5"><input name="DEX_MAX_old" value="'.$user['DEX_MAX'].'" type="hidden" size="35"></td>
				</tr>
				<tr>
					<td width="200">Мудрость / Мудрость_MAX</td>
					<td><input name="SPD" value="'.$user['SPD'].'" type="text" size="5"><input name="SPD_old" value="'.$user['SPD'].'" type="hidden" size="35"> / <input name="SPD_MAX" value="'.$user['SPD_MAX'].'" type="text" size="5"><input name="SPD_MAX_old" value="'.$user['SPD_MAX'].'" type="hidden" size="35"></td>
				</tr>

					<td width="200">Удача / Удача_MAX</td>
					<td><input name="lucky" value="'.$user['lucky'].'" type="text" size="5"><input name="lucky_old" value="'.$user['lucky'].'" type="hidden" size="35"> / <input name="lucky_max" value="'.$user['lucky_max'].'" type="text" size="5"><input name="lucky_max_old" value="'.$user['lucky_max'].'" type="hidden" size="35"></td>
				</tr>

				<tr><td width="100">Вес</td><td><input name="CW" value="'.$user['CW'].'" type="text" size="15"><input name="CW_old" value="'.$user['CW'].'" type="hidden" size="35"></td></tr>
				<tr><td width="100">Максимальный вес</td><td><input name="CC" value="'.$user['CC'].'" type="text" size="15"><input name="CC_old" value="'.$user['CC'].'" type="hidden" size="35"></td></tr>
				<tr><td width="100">Карта</td><td>';

				echo'<select name="map">';
				$result = myquery("SELECT * FROM game_maps ORDER BY name");
				while($map=mysql_fetch_array($result))
				{
					echo '<option value='.$map['id'].'';
					if ($user_map['map_name']==$map['id']) echo ' selected';
					echo'>'.$map['name'].'</option>';
				}
				echo '</select><input name="map_old" value="'.$user_map['map_name'].'" type="hidden" size="35">';

				echo'</td></tr>
				<tr><td width="200">Коорд.Х</td><td><input name="map_xpos" value="'.$user_map['map_xpos'].'" type="text" size="5"><input name="map_xpos_old" value="'.$user_map['map_xpos'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Коорд.Y</td><td><input name="map_ypos" value="'.$user_map['map_ypos'].'" type="text" size="5"><input name="map_ypos_old" value="'.$user_map['map_ypos'].'" type="hidden" size="35"></td></tr>

				<tr><td width="200" align="center" colspan=2>
				<input name="save" type="submit" value="Сохранить">
				</td></tr>
				
				<tr><td width="200">Навык кулака</td><td><input name="MS_KULAK" value="'.$user['MS_KULAK'].'" type="text" size="5"><input name="MS_KULAK_old" value="'.$user['MS_KULAK'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык оружия</td><td><input name="MS_WEAPON" value="'.$user['MS_WEAPON'].'" type="text" size="5"><input name="MS_WEAPON_old" value="'.$user['MS_WEAPON'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык стрелкового оружия</td><td><input name="MS_LUK" value="'.$user['MS_LUK'].'" type="text" size="5"><input name="MS_LUK_old" value="'.$user['MS_LUK'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык метательного оружия</td><td><input name="MS_THROW" value="'.$user['MS_THROW'].'" type="text" size="5"><input name="MS_THROW_old" value="'.$user['MS_THROW'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык рубящего оружия</td><td><input name="MS_SWORD" value="'.$user['MS_SWORD'].'" type="text" size="5"><input name="MS_SWORD_old" value="'.$user['MS_SWORD'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык дробящего оружия</td><td><input name="MS_AXE" value="'.$user['MS_AXE'].'" type="text" size="5"><input name="MS_AXE_old" value="'.$user['MS_AXE'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык колющего оружия</td><td><input name="MS_SPEAR" value="'.$user['MS_SPEAR'].'" type="text" size="5"><input name="MS_SPEAR_old" value="'.$user['MS_SPEAR'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык арта</td><td><input name="MS_ART" value="'.$user['MS_ART'].'" type="text" size="5"><input name="MS_ART_old" value="'.$user['MS_ART'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык вора</td><td><input name="MS_VOR" value="'.$user['MS_VOR'].'" type="text" size="5"><input name="MS_VOR_old" value="'.$user['MS_VOR'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык парирования</td><td><input name="MS_PARIR" value="'.$user['MS_PARIR'].'" type="text" size="5"><input name="MS_PARIR_old" value="'.$user['MS_PARIR'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык кузнеца</td><td><input name="MS_KUZN" value="'.$user['MS_KUZN'].'" type="text" size="5"><input name="MS_KUZN_old" value="'.$user['MS_KUZN'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык лекаря</td><td><input name="MS_LEK" value="'.$user['MS_LEK'].'" type="text" size="5"><input name="MS_LEK_old" value="'.$user['MS_LEK'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Навык лошади</td><td><input name="MS_VSADNIK" value="'.$user['MS_VSADNIK'].'" type="text" size="5"><input name="MS_VSADNIK_old" value="'.$user['MS_VSADNIK'].'" type="hidden" size="35"></td></tr>

				<tr><td width="200" align="center" colspan=2>
				<input name="save" type="submit" value="Сохранить">
				</td></tr>';
				
				echo '<tr><td width="100">Лошадь</td><td>';

				echo'<select name="vsadnik">';
				echo '<option';
				if ($user['vsadnik']==0) echo ' selected';
				echo' value=0>Без коня</option>';
				$result = myquery("SELECT * FROM game_vsadnik ORDER BY id");
				while($vs=mysql_fetch_array($result))
				{
					echo '<option';
					if ($user['vsadnik']==$vs['id']) echo ' selected';
					echo' value='.$vs['id'].'>'.$vs['nazv'].'</option>';
				}
				echo '</select><input name="vsadnik_old" value="'.$user['vsadnik'].'" type="hidden" size="35">';

				echo'</td></tr>';
			}

			echo'<tr><td width="200">Раса</td><td>';
			echo '<select name=race>';
			$selrace = myquery("SELECT * FROM game_har");
			while ($race = mysql_fetch_array($selrace))
			{
				echo '<option value='.$race['id'].'';
				if ($race['id']==$user['race']) echo ' selected';
				echo '>'.$race['name'].'</option>';
			}
			echo '</select><input name="race_old" value="'.$user['race'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Аватар</td><td><input name="avatar" value="'.$user['avatar'].'" type="text" size="15"><input name="avatar_old" value="'.$user['avatar'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200" align="center" colspan=2>
			<input name="save" type="submit" value="Сохранить">
			</td></tr>';

			if ($adm['users'] == 2)
			{
				echo'<tr><td width="200"><font color=#FF0000>Уровень</font></td><td><input name="clevel" value="'.$user['clevel'].'" type="text" size="5"><input name="clevel_old" value="'.$user['clevel'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Неисп. харки</td><td><input name="bound" value="'.$user['bound'].'" type="text" size="5"><input name="bound_old" value="'.$user['bound'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Неисп. навыки</td><td><input name="exam" value="'.$user['exam'].'" type="text" size="5"><input name="exam_old" value="'.$user['exam'].'" type="hidden" size="35"></td></tr>

				<tr><td width="200">Побед</td><td><input name="win" value="'.$user['win'].'" type="text" size="15"><input name="win_old" value="'.$user['win'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Поражений</td><td><input name="lose" value="'.$user['lose'].'" type="text" size="15"><input name="lose_old" value="'.$user['lose'].'" type="hidden" size="35"></td></tr>

				<tr><td width="200">Возраст</td><td><input name="vozrast" value="'.$user_data['vozrast'].'" type="text" size="15"><input name="vozrast_old" value="'.$user_data['vozrast'].'" type="hidden" size="35"></td></tr>

				<tr><td width="200">boy</td><td><input name="boy" value="'.$user['boy'].'" type="text" size="15"><input name="boy_old" value="'.$user['boy'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">hod</td><td><input name="hod" value="'.$user['hod'].'" type="text" size="15"><input name="hod_old" value="'.$user['hod'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200"><font color=#0080C0>Клан</font></td><td>';
				echo'<select name="clan_id">';
				$result = myquery("SELECT * FROM game_clans WHERE raz=0 ORDER BY nazv");
				echo '<option value=0';
				if ($user['clan_id']==0) echo ' selected';
				echo'>Без клана</option>';
				while($clan=mysql_fetch_array($result))
				{
					echo '<option value='.$clan['clan_id'].'';
					if ($user['clan_id']==$clan['clan_id']) echo ' selected';
					echo'>'.$clan['nazv'].'</option>';
				}
				echo '</select><input name="clan_id_old" value="'.$user['clan_id'].'" type="hidden" size="35">';
				echo '</td></tr>';
			}
			echo'
			<tr><td width="200">func_id</td><td><input name="funct" value="'.$user['func_id'].'" type="text" size="15"><input name="funct_old" value="'.$user['func_id'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Рейтинг в клане</td><td><input name="clan_rating" value="'.$user_data['clan_rating'].'" type="text" size="25"><input name="clan_rating_old" value="'.$user_data['clan_rating'].'" type="hidden" size="35"></td></tr>
			<tr><td width="200">Звание в клане</td><td><input name="clan_zvanie" value="'.$user_data['clan_zvanie'].'" type="text" size="25"><input name="clan_zvanie_old" value="'.$user_data['clan_zvanie'].'" type="hidden" size="35"></td></tr>';
			if ($adm['users']>=2)
			{
				echo '
				<tr><td width="200">Время последнего выхода из клана</td><td><input name="last_clan_move" value="'.$user_data['last_clan_move'].'" type="text" size="25"><input name="last_clan_move_old" value="'.$user_data['last_clan_move'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Кол-во сброса навыков на алтаре</td><td><input name="count_reload" value="'.$user_data['count_reload'].'" type="text" size="25"><input name="count_reload_old" value="'.$user_data['count_reload'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Кол-во сброса характеристик на алтаре</td><td><input name="count_reload_har" value="'.$user_data['count_reload_har'].'" type="text" size="25"><input name="count_reload_har_old" value="'.$user_data['count_reload_har'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Кол-во обнулений</td><td><input name="obnul" value="'.$user_data['obnul'].'" type="text" size="25"><input name="obnul_old" value="'.$user_data['obnul'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Кол-во доступн. беспл. обнулений</td><td><input name="obnul_free" value="'.$user_data['obnul_free'].'" type="text" size="25"><input name="obnul_free_old" value="'.$user_data['obnul_free'].'" type="hidden" size="35"></td></tr>
				<tr><td width="200">Персональный рейтинг игрока</td><td><input name="user_rating" value="'.$user_data['user_rating'].'" type="text" size="10"><input name="user_rating_old" value="'.$user_data['user_rating'].'" type="hidden" size="35"></td></tr>
				 <tr><td width="200">Карма игрока</td><td><input name="karma" value="'.$user_data['karma'].'" type="text" size="10"><input name="karma_old" value="'.$user_data['karma'].'" type="hidden" size="35"></td></tr>';
				echo'<tr><td width="200">Склонность игрока:</td><td><select name="sel_sklon">';
				echo '<option value="0"';
				if ($user['sklon']==0) echo ' selected';
				echo '>Без склонности</option>';
				echo '<option value="1"';
				if ($user['sklon']==1) echo ' selected';
				echo '>Нейтральная</option>';
				echo '<option value="2"';
				if ($user['sklon']==2) echo ' selected';
				echo '>Светлая</option>';
				echo '<option value="3"';
				if ($user['sklon']==3) echo ' selected';
				echo '>Темная</option>';
				echo'</select><input name="sel_sklon_old" value="'.$user['sklon'].'" type="hidden" size="35"></td></tr>';
			}
			echo '
			<tr><td width="200" align="center" colspan=2>
			<input name="save" type="submit" value="Сохранить">
			<input type="button" value="Обнулить" onClick="location.href=\'admin.php?opt=main&option=users&obn='.$name_v.'\'">
			</td></tr>
			</table>
			</form>';
		}
		else
		{
			$user_id2 = $user['user_id'];
			$log = "";
			if (isset($_POST['newpass']) and $_POST['newpass']!='')
			{
				$npass = md5($_POST['newpass']);
				$result=myquery("update game_users set user_pass='$npass' where user_id=".$user['user_id']."");
				$result=myquery("update game_users_archive set user_pass='$npass' where user_id=".$user['user_id']."");
				$log.='<br><br>Пароль изменен<br><br>';
			}

			if (isset($user_name) AND $user_name!=$user_name_old)
			{
				myquery("UPDATE game_users SET user_name='$user_name' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET user_name='$user_name' WHERE user_id=$user_id2");
				$log.='<br />Изменен логин<br />';
			}
			if (isset($name) AND $name!=$name_old)
			{
				myquery("UPDATE game_users SET name='$name' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET name='$name' WHERE user_id=$user_id2");
				$log.='<br />Изменен ник<br />';
			}
			if (isset($HP) AND $HP!=$HP_old)
			{
				myquery("UPDATE game_users SET HP='$HP' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET HP='$HP' WHERE user_id=$user_id2");
				$log.='<br />Изменен жизни<br />';
			}
			if (isset($MP) AND $MP!=$MP_old)
			{
				myquery("UPDATE game_users SET MP='$MP' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MP='$MP' WHERE user_id=$user_id2");
				$log.='<br />Изменен мана<br />';
			}
			if (isset($STM) AND $STM!=$STM_old)
			{
				myquery("UPDATE game_users SET STM='$STM' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET STM='$STM' WHERE user_id=$user_id2");
				$log.='<br />Изменен энергия<br />';
			}
			if (isset($HP_MAX) AND $HP_MAX!=$HP_MAX_old)
			{
				myquery("UPDATE game_users SET HP_MAX='$HP_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET HP_MAX='$HP_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен жизни макс<br />';
			}
			if (isset($HP_MAXX) AND $HP_MAXX!=$HP_MAXX_old)
			{
				myquery("UPDATE game_users SET HP_MAXX='$HP_MAXX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET HP_MAXX='$HP_MAXX' WHERE user_id=$user_id2");
				$log.='<br />Изменен жизни макс1<br />';
			}
			if (isset($MP_MAX) AND $MP_MAX!=$MP_MAX_old)
			{
				myquery("UPDATE game_users SET MP_MAX='$MP_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MP_MAX='$MP_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен мана макс<br />';
			}
			if (isset($STM_MAX) AND $STM_MAX!=$STM_MAX_old)
			{
				myquery("UPDATE game_users SET STM_MAX='$STM_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET STM_MAX='$STM_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен энергия макс<br />';
			}
			if (isset($EXP) AND $EXP!=$EXP_old)
			{
				myquery("UPDATE game_users SET EXP='$EXP' WHERE user_id=$user_id2");
				setEXP($user_id2,$EXP-$EXP_old,4);
				myquery("UPDATE game_users_archive SET EXP='$EXP' WHERE user_id=$user_id2");
				$log.='<br />Изменен опыт<br />';
			}
			if (isset($GP) AND $GP!=$GP_old)
			{
				myquery("UPDATE game_users SET GP='$GP' WHERE user_id=$user_id2");
				setGP($user_id2,$GP-$GP_old,20);
				myquery("UPDATE game_users_archive SET GP='$GP' WHERE user_id=$user_id2");
				$log.='<br />Изменен монеты<br />';
			}
			if (isset($STR) AND $STR!=$STR_old)
			{
				myquery("UPDATE game_users SET STR='$STR' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET STR='$STR' WHERE user_id=$user_id2");
				$log.='<br />Изменен сила<br />';
			}
			if (isset($NTL) AND $NTL!=$NTL_old)
			{
				myquery("UPDATE game_users SET NTL='$NTL' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET NTL='$NTL' WHERE user_id=$user_id2");
				$log.='<br />Изменен интеллект<br />';
			}
			if (isset($PIE) AND $PIE!=$PIE_old)
			{
				myquery("UPDATE game_users SET PIE='$PIE' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET PIE='$PIE' WHERE user_id=$user_id2");
				$log.='<br />Изменен ловкость<br />';
			}
			if (isset($VIT) AND $VIT!=$VIT_old)
			{
				myquery("UPDATE game_users SET VIT='$VIT' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET VIT='$VIT' WHERE user_id=$user_id2");
				$log.='<br />Изменен защита<br />';
			}
			if (isset($DEX) AND $DEX!=$DEX_old)
			{
				myquery("UPDATE game_users SET DEX='$DEX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET DEX='$DEX' WHERE user_id=$user_id2");
				$log.='<br />Изменен выносливость<br />';
			}
			if (isset($SPD) AND $SPD!=$SPD_old)
			{
				myquery("UPDATE game_users SET SPD='$SPD' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET SPD='$SPD' WHERE user_id=$user_id2");
				$log.='<br />Изменен мудрость<br />';
			}
			if (isset($STR_MAX) AND $STR_MAX!=$STR_MAX_old)
			{
				myquery("UPDATE game_users SET STR_MAX='$STR_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET STR_MAX='$STR_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен сила макс<br />';
			}
			if (isset($NTL_MAX) AND $NTL_MAX!=$NTL_MAX_old)
			{
				myquery("UPDATE game_users SET NTL_MAX='$NTL_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET NTL_MAX='$NTL_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен интеллект макс<br />';
			}
			if (isset($PIE_MAX) AND $PIE_MAX!=$PIE_MAX_old)
			{
				myquery("UPDATE game_users SET PIE_MAX='$PIE_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET PIE_MAX='$PIE_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен ловкость макс<br />';
			}
			if (isset($VIT_MAX) AND $VIT_MAX!=$VIT_MAX_old)
			{
				myquery("UPDATE game_users SET VIT_MAX='$VIT_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET VIT_MAX='$VIT_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен защита макс<br />';
			}
			if (isset($DEX_MAX) AND $DEX_MAX!=$DEX_MAX_old)
			{
				myquery("UPDATE game_users SET DEX_MAX='$DEX_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET DEX_MAX='$DEX_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен выносливость макс<br />';
			}
			if (isset($SPD_MAX) AND $SPD_MAX!=$SPD_MAX_old)
			{
				myquery("UPDATE game_users SET SPD_MAX='$SPD_MAX' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET SPD_MAX='$SPD_MAX' WHERE user_id=$user_id2");
				$log.='<br />Изменен мудрость макс<br />';
			}
			if (isset($lucky) AND $lucky!=$lucky_old)
			{
				myquery("UPDATE game_users SET lucky='$lucky' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET lucky='$lucky' WHERE user_id=$user_id2");
				$log.='<br />Изменен удача<br />';
			}
			if (isset($lucky_max) AND $lucky_max!=$lucky_max_old)
			{
				myquery("UPDATE game_users SET lucky_max='$lucky_max' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET lucky_max='$lucky_max' WHERE user_id=$user_id2");
				$log.='<br />Изменен удача макс<br />';
			}
			if (isset($CW) AND $CW!=$CW_old)
			{
				myquery("UPDATE game_users SET CW='$CW' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET CW='$CW' WHERE user_id=$user_id2");
				$log.='<br />Изменен тек.вес<br />';
			}
			if (isset($CC) AND $CC!=$CC_old)
			{
				myquery("UPDATE game_users SET CC='$CC' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET CC='$CC' WHERE user_id=$user_id2");
				$log.='<br />Изменен макс.вес<br />';
			}
			if (isset($race) AND $race!=$race_old)
			{
				myquery("UPDATE game_users SET race='$race' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET race='$race' WHERE user_id=$user_id2");
				$log.='<br />Изменен раса<br />';
			}
			if (isset($avatar) AND $avatar!=$avatar_old)
			{
				myquery("UPDATE game_users SET avatar='$avatar' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET avatar='$avatar' WHERE user_id=$user_id2");
				$log.='<br />Изменен аватар<br />';
			}
			if (isset($clevel) AND $clevel!=$clevel_old)
			{
				myquery("UPDATE game_users SET clevel='$clevel' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET clevel='$clevel' WHERE user_id=$user_id2");
				$log.='<br />Изменен уровень<br />';
			}
			if (isset($bound) AND $bound!=$bound_old)
			{
				myquery("UPDATE game_users SET bound='$bound' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET bound='$bound' WHERE user_id=$user_id2");
				$log.='<br />Изменен неисп.харки<br />';
			}
			if (isset($exam) AND $exam!=$exam_old)
			{
				myquery("UPDATE game_users SET exam='$exam' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET exam='$exam' WHERE user_id=$user_id2");
				$log.='<br />Изменен неисп.навыки<br />';
			}
			if (isset($funct) AND $funct!=$funct_old)
			{
				myquery("UPDATE game_users_func SET func_id='$funct' WHERE user_id=$user_id2");
				//myquery("UPDATE game_users_archive SET func='$funct' WHERE user_id=$user_id2");
				$log.='<br />Изменен func<br />';
			}
			if (isset($win) AND $win!=$win_old)
			{
				myquery("UPDATE game_users SET win='$win' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET win='$win' WHERE user_id=$user_id2");
				$log.='<br />Изменен кол-во побед<br />';
			}
			if (isset($lose) AND $lose!=$lose_old)
			{
				myquery("UPDATE game_users SET lose='$lose' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET lose='$lose' WHERE user_id=$user_id2");
				$log.='<br />Изменен кол-во поражений<br />';
			}
			if (isset($clan_id) AND $clan_id!=$clan_id_old)
			{
				myquery("UPDATE game_users SET clan_id='$clan_id' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET clan_id='$clan_id' WHERE user_id=$user_id2");
				$log.='<br />Изменен клан<br />';
			}
			if (isset($MS_ART) AND $MS_ART!=$MS_ART_old)
			{
				myquery("UPDATE game_users SET MS_ART='$MS_ART' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_ART='$MS_ART' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык арта<br />';
			}
			if (isset($MS_VOR) AND $MS_VOR!=$MS_VOR_old)
			{
				myquery("UPDATE game_users SET MS_VOR='$MS_VOR' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_VOR='$MS_VOR' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык вора<br />';
			}
			if (isset($MS_KULAK) AND $MS_KULAK!=$MS_KULAK_old)
			{
				myquery("UPDATE game_users SET MS_KULAK='$MS_KULAK' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_KULAK='$MS_KULAK' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык кулака<br />';
			}
			if (isset($MS_WEAPON) AND $MS_WEAPON!=$MS_WEAPON_old)
			{
				myquery("UPDATE game_users SET MS_WEAPON='$MS_WEAPON' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_WEAPON='$MS_WEAPON' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык оружия<br />';
			}
			if (isset($MS_LUK) AND $MS_LUK!=$MS_LUK_old)
			{
				myquery("UPDATE game_users SET MS_LUK='$MS_LUK' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_LUK='$MS_LUK' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык лука<br />';
			}
			if (isset($MS_THROW) AND $MS_THROW!=$MS_THROW_old)
			{
				myquery("UPDATE game_users SET MS_THROW='$MS_THROW' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_THROW='$MS_THROW' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык метат.предмета<br />';
			}
			if (isset($MS_PARIR) AND $MS_PARIR!=$MS_PARIR_old)
			{
				myquery("UPDATE game_users SET MS_PARIR='$MS_PARIR' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_PARIR='$MS_PARIR' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык парирования<br />';
			}
			if (isset($MS_KUZN) AND $MS_KUZN!=$MS_KUZN_old)
			{
				myquery("UPDATE game_users SET MS_KUZN='$MS_KUZN' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_KUZN='$MS_KUZN' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык кузнеца<br />';
			}
			if (isset($MS_LEK) AND $MS_LEK!=$MS_LEK_old)
			{
				myquery("UPDATE game_users SET MS_LEK='$MS_LEK' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_LEK='$MS_LEK' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык лекаря<br />';
			}
			if (isset($MS_VSADNIK) AND $MS_VSADNIK!=$MS_VSADNIK_old)
			{
				myquery("UPDATE game_users SET MS_VSADNIK='$MS_VSADNIK' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_VSADNIK='$MS_VSADNIK' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык всадника<br />';
			}
			if (isset($MS_SWORD) AND $MS_SWORD!=$MS_SWORD_old)
			{
				myquery("UPDATE game_users SET MS_SWORD='$MS_SWORD' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_SWORD='$MS_SWORD' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык рубящего оружия<br />';
			}
			if (isset($MS_AXE) AND $MS_AXE!=$MS_AXE_old)
			{
				myquery("UPDATE game_users SET MS_AXE='$MS_AXE' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_AXE='$MS_AXE' WHERE user_id=$user_id2");
				$log.='<br />Изменен навык дробящего оружия<br />';
			}
			if (isset($MS_SPEAR) AND $MS_SPEAR!=$MS_SPEAR_old)
			{
				myquery("UPDATE game_users SET MS_SPEAR='$MS_SPEAR' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET MS_SPEAR='$MS_SPEAR' WHERE user_id=$user_id2");
				$log.='<br />Изменен колющего оружия<br />';
			}
			if (isset($vsadnik) AND $vsadnik!=$vsadnik_old)
			{
				myquery("UPDATE game_users SET vsadnik='$vsadnik' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET vsadnik='$vsadnik' WHERE user_id=$user_id2");
				$log.='<br />Изменен лошадь<br />';
			}
			if (isset($boy) AND $boy!=$boy_old)
			{
				myquery("UPDATE combat_users SET combat_id='$boy' WHERE user_id=$user_id2");
				//myquery("UPDATE game_users SET boy='$boy' WHERE user_id=$user_id2");
				//myquery("UPDATE game_users_archive SET boy='$boy' WHERE user_id=$user_id2");
				$log.='<br />Изменен ИД боя<br />';
			}
			if (isset($hod) AND $hod!=$hod_old)
			{
				myquery("UPDATE combat_users SET time_last_active='$hod' WHERE user_id=$user_id2");
				//myquery("UPDATE game_users SET hod='$hod' WHERE user_id=$user_id2");
				//myquery("UPDATE game_users_archive SET hod='$hod' WHERE user_id=$user_id2");
				$log.='<br />Изменен время посл.хода<br />';
			}
			if (isset($map) AND $map!=$map_old)
			{
				myquery("UPDATE game_users_map SET map_name='$map' WHERE user_id=$user_id2");
				$log.='<br />Изменен карта<br />';
			}
			if (isset($map_xpos) AND $map_xpos!=$map_xpos_old)
			{
				myquery("UPDATE game_users_map SET map_xpos='$map_xpos' WHERE user_id=$user_id2");
				$log.='<br />Изменен позиция Х<br />';
			}
			if (isset($map_ypos) AND $map_ypos!=$map_ypos_old)
			{
				myquery("UPDATE game_users_map SET map_ypos='$map_ypos' WHERE user_id=$user_id2");
				 $log.='<br />Изменен позиция Y<br />';
		   }
			if (isset($email) AND $email!=$email_old)
			{
				myquery("UPDATE game_users_data SET email='$email' WHERE user_id=$user_id2");
				$log.='<br />Изменен email<br />';
			}
			if (isset($STATUS) AND $STATUS!=$STATUS_old)
			{
				myquery("UPDATE game_users_data SET STATUS='$STATUS' WHERE user_id=$user_id2");
				$log.='<br />Изменен статус<br />';
			}
			if (isset($gorod) AND $gorod!=$gorod_old)
			{
				myquery("UPDATE game_users_data SET gorod='$gorod' WHERE user_id=$user_id2");
				$log.='<br />Изменен город<br />';
			}
			if (isset($hobbi) AND $hobbi!=$hobbi_old)
			{
				myquery("UPDATE game_users_data SET hobbi='$hobbi' WHERE user_id=$user_id2");
				$log.='<br />Изменен хобби<br />';
			}
			if (isset($info) AND $info!=$info_old)
			{
				myquery("UPDATE game_users_data SET info='$info' WHERE user_id=$user_id2");
				$log.='<br />Изменен инфо<br />';
			}
			if (isset($vozrast) AND $vozrast!=$vozrast_old)
			{
				myquery("UPDATE game_users_data SET vozrast='$vozrast' WHERE user_id=$user_id2");
				$log.='<br />Изменен возраст<br />';
			}
			if (isset($sex1) AND $sex1!=$sex1_old)
			{
				myquery("UPDATE game_users_data SET sex='$sex1' WHERE user_id=$user_id2");
				$log.='<br />Изменен пол<br />';
			}
			if (isset($dn) AND $dn!=$dn_old)
			{
				myquery("UPDATE game_users_data SET dr_date='$dn' WHERE user_id=$user_id2");
				$log.='<br />Изменен дата рождения<br />';
			}
			if (isset($ms) AND $ms!=$ms_old)
			{
				myquery("UPDATE game_users_data SET dr_month='$ms' WHERE user_id=$user_id2");
				$log.='<br />Изменен месяц рождения<br />';
			}
			if (isset($god) AND $god!=$god_old)
			{
				myquery("UPDATE game_users_data SET dr_year='$god' WHERE user_id=$user_id2");
				$log.='<br />Изменен год рождения<br />';
			}
			if (isset($obnul) AND $obnul!=$obnul_old)
			{
				myquery("UPDATE game_users_data SET obnul='$obnul' WHERE user_id=$user_id2");
				$log.='<br />Изменено кол-во обнулений<br />';
			}
			if (isset($obnul_free) AND $obnul_free!=$obnul_free_old)
			{
				myquery("UPDATE game_users_data SET obnul_free='$obnul_free' WHERE user_id=$user_id2");
				$log.='<br />Изменено кол-во доступных бесплатных обнулений<br />';
			}
			if (isset($count_reload) AND $count_reload!=$count_reload_old)
			{
				myquery("UPDATE game_users_data SET count_reload='$count_reload' WHERE user_id=$user_id2");
				$log.='<br />Изменено кол-во сбросов навыков на алтаре<br />';
			}
			if (isset($count_reload_har) AND $count_reload_har!=$count_reload_har_old)
			{
				myquery("UPDATE game_users_data SET count_reload_har='$count_reload_har' WHERE user_id=$user_id2");
				$log.='<br />Изменено кол-во сбросов характеристик на алтаре<br />';
			}
			if (isset($last_clan_move) AND $last_clan_move!=$last_clan_move_old)
			{
				myquery("UPDATE game_users_data SET last_clan_move='$last_clan_move' WHERE user_id=$user_id2");
				$log.='<br />Изменен возраст последнего выхода из клана<br />';
			}
			if (isset($clan_rating) AND $clan_rating!=$clan_rating_old)
			{
				myquery("UPDATE game_users_data SET clan_rating='$clan_rating' WHERE user_id=$user_id2");
				$log.='<br />Изменен рейтинг в клане<br />';
			}
			if (isset($clan_zvanie) AND $clan_zvanie!=$clan_zvanie_old)
			{
				myquery("UPDATE game_users_data SET clan_zvanie='$clan_zvanie' WHERE user_id=$user_id2");
				$log.='<br />Изменен звание в клане<br />';
			}
			if (isset($user_rating) AND $user_rating!=$user_rating_old)
			{
				myquery("UPDATE game_users_data SET user_rating='$user_rating' WHERE user_id=$user_id2");
				$log.='<br />Изменен рейтинг игрока<br />';
			}
			if (isset($karma) AND $karma!=$karma_old)
			{
				myquery("UPDATE game_users_data SET karma='$karma' WHERE user_id=$user_id2");
				$log.='<br />Изменен карма<br />';
			}
			if (isset($sel_sklon) AND $sel_sklon!=$sel_sklon_old)
			{
				myquery("UPDATE game_users SET sklon='$sel_sklon' WHERE user_id=$user_id2");
				myquery("UPDATE game_users_archive SET sklon='$sel_sklon' WHERE user_id=$user_id2");
				$log.='<br />Изменена склонность<br />';
			}
			echo $log;
			echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>Сохранено<b></font>';
		
			$da = getdate();
			$username = get_user("name",$user_id2,0);
			myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
			 'Изменил игрока <b>".$username."</b>: ".$log."',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')");
		}
	}
	else
	{
		echo'Игрок не найден.';
	}
}
echo '</div><script>init();</script>';
}

if (function_exists("save_debug")) save_debug(); 

?>