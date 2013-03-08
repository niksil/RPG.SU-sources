<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['gorod'] >= 1)
{
echo '<center>Конструктор городов:<br>';

if (isset($new))
{
	if (!isset($save))
	{
		echo'<form action="" method="post"><table border=0>
		<tr><td>Название:</td><td><input type=text name=town value=""></td></tr>
		<tr><td>Русское название:</td><td><input type=text name=rustown value=""></td></tr>
		<tr><td>Текст входа:</td><td><input type=text name=vhod value="" size=99></td></tr>
		<tr><td>Только для клана:</td><td><input type=text name=clan value="" size=3></td></tr>
		<tr><td>Только для расы:</td><td>
		<select name=race><option value=0></option>';
		$selrace = myquery("SELECT * FROM game_har WHERE disable=0");
		while ($race = mysql_fetch_array($selrace))
		{
			echo '<option value='.$race['id'].'>'.$race['name'].'</option>';
		}
		echo '</select></td></tr>
		<tr><td>Описание перед входом:</td><td><textarea name=opis cols=70 class=input rows=4></textarea></td></tr>

		<tr><td>Цвет:</td><td><input type=text name=color value=""></td></tr>

		<tr><td>Стиль:</td><td><textarea name=style cols=70 class=input rows=8></textarea></td></tr>
		<tr><td>Дизайн города:</td><td><textarea name=center cols=70 class=input rows=20></textarea></td></tr>';

		echo'<tr><td>Опции:</td><td><select name="options[]" size=10 multiple><option value="0">Нет опций</option>';
		$selopt=myquery("select * from game_gorod_option order by name");
		while ($opt=mysql_fetch_array($selopt))
		{
			echo '<option value='.$opt['id'].'>'.$opt['name'].'</option>';
		}
		echo '</select></td></tr>';

		echo '<tr><td>Новость:</td><td><textarea name="news" cols=70 class=input rows=5></textarea></td></tr>


		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>Характеристики тренера в городе</font></center></td></tr>';


		echo'<tr><td align=right>Сила</td><td><input name="STR" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Интеллект</td><td><input name="NTL" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Ловкость</td><td><input name="PIE" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Защита</td><td><input name="VIT" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Выносливость</td><td><input name="DEX" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мудрость</td><td><input name="SPD" type="checkbox" value="1"><td></td></tr>

		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>Навыки тренера в городе</font></center></td></tr>';


		echo'<tr><td align=right>Навык воина</td><td><input name="WAR" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык волшебника</td><td><input name="COOK" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык лучника</td><td><input name="ART" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык паладина</td><td><input name="EXPLOR" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык варвара</td><td><input name="CRAFT" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык вора</td><td><input name="CARD" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык разбойника</td><td><input name="UNKNOW" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык барда</td><td><input name="MUSIC" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Навык друида</td><td><input name="PET" type="checkbox" value="1"><td></td></tr>

		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>Специализации тренера в городе</font></center></td></tr>';

		echo'<tr><td align=right>Эксперт артефактов</td><td><input name="MS_ART" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Верховая езда</td><td><input name="MS_VSADNIK" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Вор</td><td><input name="MS_VOR " type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Лекарь</td><td><input name="MS_LEK" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мастер парирования</td><td><input name="MS_PARIR" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Кузнец</td><td><input name="MS_KUZN" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Искусство кулачного боя</td><td><input name="MS_KULAK" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Эксперт воинских умений</td><td><input name="MS_WEAPON" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мастер стрелкового оружия</td><td><input name="MS_LUK" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мастер метательного оружия</td><td><input name="MS_THROW" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мастер рубящего оружия</td><td><input name="MS_SWORD" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мастер дробящего оружия</td><td><input name="MS_AXE" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Мастер колющего оружия</td><td><input name="MS_SPEAR" type="checkbox" value="1"><td></td></tr>';
 
		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>В город могут заходить</font></center></td></tr>';

		echo'<tr><td align=right>Эльфы</td><td><input name="enter_elf" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Орки</td><td><input name="enter_orc" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Назгулы</td><td><input name="enter_nazgul" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Хоббиты</td><td><input name="enter_hobbit" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Люди</td><td><input name="enter_human" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Гномы</td><td><input name="enter_gnome" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Гоблины</td><td><input name="enter_goblin" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Тролли</td><td><input name="enter_troll" type="checkbox" value="1"><td></td></tr>';

		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>В городе могу продавать предметы</font></center></td></tr>';

		echo'<tr><td align=right>Эльфы</td><td><input name="torg_elf" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Орки</td><td><input name="torg_orc" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Назгулы</td><td><input name="torg_nazgul" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Хоббиты</td><td><input name="torg_hobbit" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Люди</td><td><input name="torg_human" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Гномы</td><td><input name="torg_gnome" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Гоблины</td><td><input name="torg_goblin" type="checkbox" value="1"><td></td></tr>';
		echo'<tr><td align=right>Тролли</td><td><input name="torg_troll" type="checkbox" value="1"><td></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td align=right>Вид города</td><td>
		<select name="side1">
		<option value="0">Темный город</option>
		<option value="1">Светлый город</option>
		<option value="2" selected>Нейтральный город</option>
		</td></tr>';

		echo'<tr><td align=right>Виден в view.'.DOMAIN.'</td><td><input name="view" type="checkbox" value="1"></td></tr>';

		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Добавить"><input name="save" type="hidden" value=""></td></tr>';
		echo'</table>';
	}
	else
	{
		echo'Город добавлен';
		if (isset($view) and $view=='1') $v='1';
		if (!isset($view)) $v='0';

		if (isset($enter_elf) and $enter_elf=='1') $enter_elf1='1';
		if (!isset($enter_elf)) $enter_elf1='0';
		if (isset($enter_orc) and $enter_orc=='1') $enter_orc1='1';
		if (!isset($enter_orc)) $enter_orc1='0';
		if (isset($enter_nazgul) and $enter_nazgul=='1') $enter_nazgul1='1';
		if (!isset($enter_nazgul)) $enter_nazgul1='0';
		if (isset($enter_hobbit) and $enter_hobbit=='1') $enter_hobbit1='1';
		if (!isset($enter_hobbit)) $enter_hobbit1='0';
		if (isset($enter_human) and $enter_human=='1') $enter_human1='1';
		if (!isset($enter_human)) $enter_human1='0';
		if (isset($enter_gnome) and $enter_gnome=='1') $enter_gnome1='1';
		if (!isset($enter_gnome)) $enter_gnome1='0';
		if (isset($enter_troll) and $enter_troll=='1') $enter_troll1='1';
		if (!isset($enter_troll)) $enter_troll1='0';
		if (isset($enter_goblin) and $enter_goblin=='1') $enter_goblin1='1';
		if (!isset($enter_goblin)) $enter_goblin1='0';


		if (isset($torg_elf) and $torg_elf=='1') $torg_elf1='1';
		if (!isset($torg_elf)) $torg_elf1='0';
		if (isset($torg_orc) and $torg_orc=='1') $torg_orc1='1';
		if (!isset($torg_orc)) $torg_orc1='0';
		if (isset($torg_nazgul) and $torg_nazgul=='1') $torg_nazgul1='1';
		if (!isset($torg_nazgul)) $torg_nazgul1='0';
		if (isset($torg_hobbit) and $torg_hobbit=='1') $torg_hobbit1='1';
		if (!isset($torg_hobbit)) $torg_hobbit1='0';
		if (isset($torg_human) and $torg_human=='1') $torg_human1='1';
		if (!isset($torg_human)) $torg_human1='0';
		if (isset($torg_gnome) and $torg_gnome=='1') $torg_gnome1='1';
		if (!isset($torg_gnome)) $torg_gnome1='0';
		if (isset($torg_troll) and $torg_troll=='1') $torg_troll1='1';
		if (!isset($torg_troll)) $torg_troll1='0';
		if (isset($torg_goblin) and $torg_goblin=='1') $torg_goblin1='1';
		if (!isset($torg_goblin)) $torg_goblin1='0';



		if (isset($STR) and $STR=='1') $STR1='1';
		if (!isset($STR)) $STR1='0';

		if (isset($NTL) and $NTL=='1') $NTL1='1';
		if (!isset($NTL)) $NTL1='0';

		if (isset($PIE) and $PIE=='1') $PIE1='1';
		if (!isset($PIE)) $PIE1='0';

		if (isset($VIT) and $VIT=='1') $VIT1='1';
		if (!isset($VIT)) $VIT1='0';

		if (isset($DEX) and $DEX=='1') $DEX1='1';
		if (!isset($DEX)) $DEX1='0';

		if (isset($SPD) and $SPD=='1') $SPD1='1';
		if (!isset($SPD)) $SPD1='0';



		if (isset($WAR) and $WAR=='1') $WAR1='1';
		if (!isset($WAR)) $WAR1='0';

		if (isset($COOK) and $COOK=='1') $COOK1='1';
		if (!isset($COOK)) $COOK1='0';

		if (isset($ART) and $ART=='1') $ART1='1';
		if (!isset($ART)) $ART1='0';

		if (isset($EXPLOR) and $EXPLOR=='1') $EXPLOR1='1';
		if (!isset($EXPLOR)) $EXPLOR1='0';

		if (isset($CRAFT) and $CRAFT=='1') $CRAFT1='1';
		if (!isset($CRAFT)) $CRAFT1='0';

		if (isset($CARD) and $CARD=='1') $CARD1='1';
		if (!isset($CARD)) $CARD1='0';

		if (isset($UNKNOW) and $UNKNOW=='1') $UNKNOW1='1';
		if (!isset($UNKNOW)) $UNKNOW1='0';

		if (isset($MUSIC) and $MUSIC=='1') $MUSIC1='1';
		if (!isset($MUSIC)) $MUSIC1='0';

		if (isset($PET) and $PET=='1') $PET1='1';
		if (!isset($PET)) $PET1='0';

		if (isset($MS_KULAK) and $MS_KULAK=='1') $MS_KULAK1='1';
		if (!isset($MS_KULAK)) $MS_KULAK1='0';

		if (isset($MS_WEAPON) and $MS_WEAPON=='1') $MS_WEAPON1='1';
		if (!isset($MS_WEAPON)) $MS_WEAPON1='0';

		if (isset($MS_ART) and $MS_ART=='1') $MS_ART1='1';
		if (!isset($MS_ART)) $MS_ART1='0';

		if (isset($MS_VSADNIK) and $MS_VSADNIK=='1') $MS_VSADNIK1='1';
		if (!isset($MS_VSADNIK)) $MS_VSADNIK1='0';

		if (isset($MS_VOR) and $MS_VOR=='1') $MS_VOR1='1';
		if (!isset($MS_VOR)) $MS_VOR1='0';

		if (isset($MS_LEK) and $MS_LEK=='1') $MS_LEK1='1';
		if (!isset($MS_LEK)) $MS_LEK1='0';

		if (isset($MS_PARIR) and $MS_PARIR=='1') $MS_PARIR1='1';
		if (!isset($MS_PARIR)) $MS_PARIR1='0';

		if (isset($MS_KUZN) and $MS_KUZN=='1') $MS_KUZN1='1';
		if (!isset($MS_KUZN)) $MS_KUZN1='0';

		if (isset($MS_LUK) and $MS_LUK=='1') $MS_LUK1='1';
		if (!isset($MS_LUK)) $MS_LUK1='0';
		
		if (isset($MS_THROW) and $MS_THROW=='1') $MS_THROW1='1';
		if (!isset($MS_THROW)) $MS_THROW1='0';

		if (isset($MS_SWORD) and $MS_SWORD=='1') $MS_SWORD1='1';
		if (!isset($MS_SWORD)) $MS_SWORD1='0';

		if (isset($MS_AXE) and $MS_AXE=='1') $MS_AXE1='1';
		if (!isset($MS_AXE)) $MS_AXE1='0';

		if (isset($MS_SPEAR) and $MS_SPEAR=='1') $MS_SPEAR1='1';
		if (!isset($MS_SPEAR)) $MS_SPEAR1='0';

		$update=myquery("INSERT INTO game_gorod (name,rustown,clan,race,vhod,opis,style,color,center,news,STR,NTL,PIE,VIT,DEX,SPD,WAR,COOK,ART,EXPLOR,CRAFT,CARD,UNKNOW,MUSIC,PET,MS_KULAK,MS_WEAPON,MS_ART,MS_VSADNIK,MS_VOR,MS_LEK,MS_PARIR,MS_KUZN,view,torg_Elf,torg_Orc,torg_Nazgul,torg_Hobbit,torg_Human,torg_Gnom,torg_Goblin,torg_Troll,enter_Elf,enter_Orc,enter_Nazgul,enter_Hobbit,enter_Human,enter_Gnom,enter_Goblin,enter_Troll,side,MS_LUK,MS_SWORD,MS_SPEAR,MS_AXE,MS_THROW)
		VALUES ('$town','$rustown','$clan','$race','$vhod','$opis','$style','$color','$center','$news','$STR1','$NTL1','$PIE1','$VIT1','$DEX1','$SPD1','$WAR1','$COOK1','$ART1','$EXPLOR1','$CRAFT1','$CARD1','$UNKNOW1','$MUSIC1','$PET1','$MS_KULAK1','$MS_WEAPON1','$MS_ART1','$MS_VSADNIK1','$MS_VOR1','$MS_LEK1','$MS_PARIR1','$MS_KUZN1','$v','$torg_elf1','$torg_orc1','$torg_nazgul1','$torg_hobbit1','$torg_human1','$torg_gnome1','$torg_goblin1','$torg_troll1','$enter_elf1','$enter_orc1','$enter_nazgul1','$enter_hobbit1','$enter_human1','$enter_gnome1','$enter_goblin1','$enter_troll1','$side1','$MS_LUK1','$MS_SWORD1','$MS_SPEAR1','$MS_AXE1','$$MS_THROW1')");

		$gorod_id = mysql_insert_id();
		if (isset($_REQUEST["options"]))
		{
			for ($i=0; $i<count($_REQUEST["options"]); $i++)
			{
				if ($_REQUEST["options"][$i]>0)
				{
					myquery("INSERT INTO game_gorod_set_option (gorod_id,option_id) VALUES ($gorod_id,'".$_REQUEST["options"][$i]."')");
				}
			}
		}
	   
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Добавил новый город: <b>".$rustown."(".$town.")</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
	}
}


if (isset($del_g))
{
	echo'Город удален<br><br>';
		$rustown = mysql_result(myquery("SELECT rustown FROM game_gorod WHERE town='$del_g'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил город: <b>".$rustown."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	$update=myquery("delete from game_gorod where town='$del_g'");
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
}

if (isset($del_p))
{
	echo'Проход удален<br><br>';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил проход: <b>".$del_p."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	$update=myquery("delete from game_obj where town='$del_p'");
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
}


if (isset($edit))
{
	if (!isset($save))
	{

		$sel=myquery("select * from game_gorod where town='$edit'");
		$shop=mysql_fetch_array($sel);

		echo'<br><style type="text/css">'.$shop['style'].'</style>'.$shop['center'].'<br><br>';



		echo'<form action="" method="post"><table border=0>
		<tr><td>Название:</td><td><input type=text name=town value='.$shop['name'].'></td></tr>
		<tr><td>Русское название:</td><td><input type=text name=rustown value="'.$shop['rustown'].'"></td></tr>
		<tr><td>Текст входа:</td><td><input type=text name=vhod value="'.$shop['vhod'].'" size=99></td></tr>
		<tr><td>Только для клана:</td><td><input type=text name=clan value="'.$shop['clan'].'" size=3></td></tr>
		<tr><td>Только для расы:</td><td>';
		echo '<select name=race><option value=0></option>';
		$selrace = myquery("SELECT * FROM game_har WHERE disable=0");
		while ($race = mysql_fetch_array($selrace))
		{
			echo '<option value='.$race['id'].'';
			if ($race['id']==$shop['race']) echo ' selected';
			echo '>'.$race['name'].'</option>';
		}
		echo '</select><tr><td>Описание перед входом:</td><td><textarea name=opis cols=70 class=input rows=4>'.$shop['opis'].'</textarea></td></tr>
		<tr><td>Цвет:</td><td><input type=text name=color value='.$shop['color'].'></td></tr>

		<tr><td>Стиль:</td><td><textarea name=style cols=70 class=input rows=8>'.$shop['style'].'</textarea></td></tr>
		<tr><td>Дизайн города:</td><td><textarea name=center cols=70 class=input rows=20>'.$shop['center'].'</textarea></td></tr>';

		echo'<tr><td>Опции:</td><td><select name="options[]" size=10 multiple><option value="0">Нет опций</option>';
		$selopt=myquery("select * from game_gorod_option order by name");
		while ($opt=mysql_fetch_array($selopt))
		{
			echo '<option value='.$opt['id'];
			$check = myquery("SELECT * FROM game_gorod_set_option WHERE option_id='".$opt['id']."' AND gorod_id='$edit'");
			if (mysql_num_rows($check)) echo ' selected';
			echo '>'.$opt['name'].'</option>';
		}
		echo'</select></td></tr>';
		echo'<tr><td>Новость:</td><td><textarea name="news" cols=70 class=input rows=5>'.$shop['news'].'</textarea></td></tr>

		<tr><td>Игра в городе:</td><td>
		<select name="game">';

			if ($shop['game_file']!='') echo '<option value="'.$shop['game_file'].'" selected>'.$shop['game_name'].'</option>';

			echo'
			<option value="arcanoid.swf">Арканоид (102940 байт)</option>
			<option value="battleships.swf">Морской бой (377909 байт)</option>
			<option value="cubebuster.swf">Взрывающиеся кубики (317946 байт)</option>
			<option value="gyroball.swf">Гиробол (102128 байт)</option>
			<option value="IQtest.swf">IQ тест (474307 байт)</option>
			<option value="mahjonggwm.swf">Mahjongg (276634 байт)</option>
			<option value="Solitaire.swf">Пасьянс (196304 байт)</option>
			<option value="Tetris.swf">Тетрис (455273 байт)</option>
			<option value="AirFight.SWF">Воздушный бой (121339 байт)</option>
			<option value="Ball.SWF">Арканойд 2 (40387 байт)</option>
			<option value="Bandit.SWF">Однорукий бандит (56445 байт)</option>
			<option value="Bart Fignt.SWF">Поединок Симпсонов (41966 байт)</option>
			<option value="Bobbi.SWF">Помоги Бобби (97935 байт)</option>
			<option value="Burnsie.SWF">Спаси Симпсона от термоядерных отходов (33770 байт)</option>
			<option value="Busy.SWF">Головоломка КОЛЬЦА (118111 байт)</option>
			<option value="Car 1.SWF">Гонки COTSEY&nbsp;SPY&nbsp;HUNTER (170318 байт)</option>
			<option value="Car 2.SWF">Гонки (149833 байт)</option>
			<option value="Catogochi.SWF">Кошкогочи (111780 байт)</option>
			<option value="Colpac.SWF">Наперстки 2 (33563 байт)</option>
			<option value="Crystal.SWF">Остров кристаллов (147833 байт)</option>
			<option value="Fight.swf">Подеремся (88237 байт)</option>
			<option value="Flying Elefant.SWF">Летающие слоники (120501 байт)</option>
			<option value="Football.SWF">Футбол (45590 байт)</option>
			<option value="Golf.SWF">Гольф (107373 байт)</option>
			<option value="Gomer.SWF">Злой Симпсон (15013 байт)</option>
			<option value="Gruppa.SWF">Виртуальный джаз-бенд (234886 байт)</option>
			<option value="Hunting.SWF">Охота (114235 байт)</option>
			<option value="Japan.SWF">Японские мечи (318352 байт)</option>
			<option value="Nery Pout.SWF">Концерт для хора с котенком (113892 байт)</option>
			<option value="Oracul.SWF">Гадание с оракулом (66931 байт)</option>
			<option value="Paceman 1.SWF">Пак-ман 1 (116408 байт)</option>
			<option value="Paceman 2.SWF">Пак-ман 2 (42334 байт)</option>
			<option value="Pomni 1.SWF">Запоминайка 1 (58429 байт)</option>
			<option value="Pomni 2.SWF">Запоминайка 2 (62440 байт)</option>
			<option value="Qest.SWF">Квест (273175 байт)</option>
			<option value="Ruletca.SWF">Рулетка (121298 байт)</option>
			<option value="Safari.SWF">Сафари (233070 байт)</option>
			<option value="Sciner.SWF">Спаси Симпсона (157317 байт)</option>
			<option value="SeaFight.SWF">Морской бой 2 (160872 байт)</option>
			<option value="Shooter.SWF">Стрелялка (2023776 байт)</option>
			<option value="Sniper.SWF">Снайпер (77745 байт)</option>
			<option value="Socks.SWF">Socks (261310 байт)</option>
			<option value="Tetris 2.SWF">Тетрис 3 (31743 байт)</option>
			<option value="Tetris 3.SWF">Тетрис 4 (146976 байт)</option>
			<option value="Worm.SWF">Червяк (71846 байт)</option>
			<option value="XvsO.SWF">Крестики-нолики (81695 байт)</option>
			</select>
		</td></tr>

		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>Характеристики тренера в городе</font></center></td></tr>';

		echo'<tr><td align=right>Сила</td><td><input name="STR" type="checkbox" value="1"'; if($shop['STR']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Интеллект</td><td><input name="NTL" type="checkbox" value="1"'; if($shop['NTL']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Ловкость</td><td><input name="PIE" type="checkbox" value="1"'; if($shop['PIE']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Защита</td><td><input name="VIT" type="checkbox" value="1"'; if($shop['VIT']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Выносливость</td><td><input name="DEX" type="checkbox" value="1"'; if($shop['DEX']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мудрость</td><td><input name="SPD" type="checkbox" value="1"'; if($shop['SPD']==1) echo'checked'; echo'></td></tr>

		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>Навыки тренера в городе</font></center></td></tr>';

		echo'<tr><td align=right>Навык воина</td><td><input name="WAR" type="checkbox" value="1"'; if($shop['WAR']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык волшебника</td><td><input name="COOK" type="checkbox" value="1"'; if($shop['COOK']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык лучника</td><td><input name="ART" type="checkbox" value="1"'; if($shop['ART']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык паладина</td><td><input name="EXPLOR" type="checkbox" value="1"'; if($shop['EXPLOR']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык варвара</td><td><input name="CRAFT" type="checkbox" value="1"'; if($shop['CRAFT']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык вора</td><td><input name="CARD" type="checkbox" value="1"'; if($shop['CARD']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык разбойника</td><td><input name="UNKNOW" type="checkbox" value="1"'; if($shop['UNKNOW']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык барда</td><td><input name="MUSIC" type="checkbox" value="1"'; if($shop['MUSIC']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Навык друида</td><td><input name="PET" type="checkbox" value="1"'; if($shop['PET']==1) echo'checked'; echo'></td></tr>

		<tr><td></td></tr>
		<tr><td><center><font color=ff0000 size=2 face=verdana>Специализации алтаря в городе</font></center></td></tr>';

		echo'<tr><td align=right>Эксперт артефактов</td><td><input name="MS_ART" type="checkbox" value="1"'; if($shop['MS_ART']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Верховая езда</td><td><input name="MS_VSADNIK" type="checkbox" value="1"'; if($shop['MS_VSADNIK']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Вор</td><td><input name="MS_VOR" type="checkbox" value="1"'; if($shop['MS_VOR']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Лекарь</td><td><input name="MS_LEK" type="checkbox" value="1"'; if($shop['MS_LEK']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мастер парирования</td><td><input name="MS_PARIR" type="checkbox" value="1"'; if($shop['MS_PARIR']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Кузнец</td><td><input name="MS_KUZN" type="checkbox" value="1"'; if($shop['MS_KUZN']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Искусство кулачного боя</td><td><input name="MS_KULAK" type="checkbox" value="1"'; if($shop['MS_KULAK']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Эксперт воинских умений</td><td><input name="MS_WEAPON" type="checkbox" value="1"'; if($shop['MS_WEAPON']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мастер стрелкового оружия</td><td><input name="MS_LUK" type="checkbox" value="1"'; if($shop['MS_LUK']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мастер метательного оружия</td><td><input name="MS_THROW" type="checkbox" value="1"'; if($shop['MS_THROW']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мастер рубящего оружия</td><td><input name="MS_SWORD" type="checkbox" value="1"'; if($shop['MS_SWORD']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мастер дробящего оружия</td><td><input name="MS_AXE" type="checkbox" value="1"'; if($shop['MS_AXE']==1) echo'checked'; echo'></td></tr>';
		echo'<tr><td align=right>Мастер колющего оружия</td><td><input name="MS_SPEAR" type="checkbox" value="1"'; if($shop['MS_SPEAR']==1) echo'checked'; echo'></td></tr>';

		echo'
		<tr><td><center><font color=ff0000 size=2 face=verdana>В город могут заходить</font></center></td></tr>';

		echo'<tr><td align=right>Эльфы</td><td><input name="enter_elf" type="checkbox" value="1"'; if($shop['enter_Elf']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Орки</td><td><input name="enter_orc" type="checkbox" value="1"'; if($shop['enter_Orc']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Назгулы</td><td><input name="enter_nazgul" type="checkbox" value="1"'; if($shop['enter_Nazgul']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Хоббиты</td><td><input name="enter_hobbit" type="checkbox" value="1"'; if($shop['enter_Hobbit']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Люди</td><td><input name="enter_human" type="checkbox" value="1"'; if($shop['enter_Human']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Гномы</td><td><input name="enter_gnome" type="checkbox" value="1"'; if($shop['enter_Gnom']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Гоблины</td><td><input name="enter_goblin" type="checkbox" value="1"'; if($shop['enter_Goblin']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Тролли</td><td><input name="enter_troll" type="checkbox" value="1"'; if($shop['enter_Troll']==1) echo'checked'; echo'><td></td></tr>';

		echo'<tr><td><center><font color=ff0000 size=2 face=verdana>В городе могут продавать предметы</font></center></td></tr>';

		echo'<tr><td align=right>Эльфы</td><td><input name="torg_elf" type="checkbox" value="1"'; if($shop['torg_Elf']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Орки</td><td><input name="torg_orc" type="checkbox" value="1"'; if($shop['torg_Orc']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Назгулы</td><td><input name="torg_nazgul" type="checkbox" value="1"'; if($shop['torg_Nazgul']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Хоббиты</td><td><input name="torg_hobbit" type="checkbox" value="1"'; if($shop['torg_Hobbit']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Люди</td><td><input name="torg_human" type="checkbox" value="1"'; if($shop['torg_Human']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Гномы</td><td><input name="torg_gnome" type="checkbox" value="1"'; if($shop['torg_Gnom']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Гоблины</td><td><input name="torg_goblin" type="checkbox" value="1"'; if($shop['torg_Goblin']==1) echo'checked'; echo'><td></td></tr>';
		echo'<tr><td align=right>Тролли</td><td><input name="torg_troll" type="checkbox" value="1"'; if($shop['torg_Troll']==1) echo'checked'; echo'><td></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td align=right>Вид города</td><td>
		<select name="side1">
		<option value="0"';if ($shop['side']=='0') echo ' selected'; echo'>Темный город</option>
		<option value="1"';if ($shop['side']=='1') echo ' selected'; echo'>Светлый город</option>
		<option value="2"';if ($shop['side']=='2') echo ' selected'; echo'>Нейтральный город</option>
		</td></tr>';

		echo'<tr><td align=right>Виден в view.'.DOMAIN.'></td><td><input name="view" type="checkbox" value="1"'; if($shop['view']==1) echo'checked'; echo'></td></tr>';

		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value=""></td></tr>';


		echo'</table>';
		}
		else
		{
		echo'Город изменен';

if (isset($view) and $view=='1') $v='1';
if (!isset($view)) $v='0';


		if (isset($enter_elf) and $enter_elf=='1') $enter_elf1='1';
		if (!isset($enter_elf)) $enter_elf1='0';
		if (isset($enter_orc) and $enter_orc=='1') $enter_orc1='1';
		if (!isset($enter_orc)) $enter_orc1='0';
		if (isset($enter_nazgul) and $enter_nazgul=='1') $enter_nazgul1='1';
		if (!isset($enter_nazgul)) $enter_nazgul1='0';
		if (isset($enter_hobbit) and $enter_hobbit=='1') $enter_hobbit1='1';
		if (!isset($enter_hobbit)) $enter_hobbit1='0';
		if (isset($enter_human) and $enter_human=='1') $enter_human1='1';
		if (!isset($enter_human)) $enter_human1='0';
		if (isset($enter_gnome) and $enter_gnome=='1') $enter_gnome1='1';
		if (!isset($enter_gnome)) $enter_gnome1='0';
		if (isset($enter_troll) and $enter_troll=='1') $enter_troll1='1';
		if (!isset($enter_troll)) $enter_troll1='0';
		if (isset($enter_goblin) and $enter_goblin=='1') $enter_goblin1='1';
		if (!isset($enter_goblin)) $enter_goblin1='0';


		if (isset($torg_elf) and $torg_elf=='1') $torg_elf1='1';
		if (!isset($torg_elf)) $torg_elf1='0';
		if (isset($torg_orc) and $torg_orc=='1') $torg_orc1='1';
		if (!isset($torg_orc)) $torg_orc1='0';
		if (isset($torg_nazgul) and $torg_nazgul=='1') $torg_nazgul1='1';
		if (!isset($torg_nazgul)) $torg_nazgul1='0';
		if (isset($torg_hobbit) and $torg_hobbit=='1') $torg_hobbit1='1';
		if (!isset($torg_hobbit)) $torg_hobbit1='0';
		if (isset($torg_human) and $torg_human=='1') $torg_human1='1';
		if (!isset($torg_human)) $torg_human1='0';
		if (isset($torg_gnome) and $torg_gnome=='1') $torg_gnome1='1';
		if (!isset($torg_gnome)) $torg_gnome1='0';
		if (isset($torg_troll) and $torg_troll=='1') $torg_troll1='1';
		if (!isset($torg_troll)) $torg_troll1='0';
		if (isset($torg_goblin) and $torg_goblin=='1') $torg_goblin1='1';
		if (!isset($torg_goblin)) $torg_goblin1='0';


if (isset($STR) and $STR=='1') $STR1='1';
if (!isset($STR)) $STR1='0';

if (isset($NTL) and $NTL=='1') $NTL1='1';
if (!isset($NTL)) $NTL1='0';

if (isset($PIE) and $PIE=='1') $PIE1='1';
if (!isset($PIE)) $PIE1='0';

if (isset($VIT) and $VIT=='1') $VIT1='1';
if (!isset($VIT)) $VIT1='0';

if (isset($DEX) and $DEX=='1') $DEX1='1';
if (!isset($DEX)) $DEX1='0';

if (isset($SPD) and $SPD=='1') $SPD1='1';
if (!isset($SPD)) $SPD1='0';



if (isset($WAR) and $WAR=='1') $WAR1='1';
if (!isset($WAR)) $WAR1='0';

if (isset($COOK) and $COOK=='1') $COOK1='1';
if (!isset($COOK)) $COOK1='0';

if (isset($ART) and $ART=='1') $ART1='1';
if (!isset($ART)) $ART1='0';

if (isset($EXPLOR) and $EXPLOR=='1') $EXPLOR1='1';
if (!isset($EXPLOR)) $EXPLOR1='0';

if (isset($CRAFT) and $CRAFT=='1') $CRAFT1='1';
if (!isset($CRAFT)) $CRAFT1='0';

if (isset($CARD) and $CARD=='1') $CARD1='1';
if (!isset($CARD)) $CARD1='0';

if (isset($UNKNOW) and $UNKNOW=='1') $UNKNOW1='1';
if (!isset($UNKNOW)) $UNKNOW1='0';

if (isset($MUSIC) and $MUSIC=='1') $MUSIC1='1';
if (!isset($MUSIC)) $MUSIC1='0';

if (isset($PET) and $PET=='1') $PET1='1';
if (!isset($PET)) $PET1='0';

if (isset($MS_KULAK) and $MS_KULAK=='1') $MS_KULAK1='1';
if (!isset($MS_KULAK)) $MS_KULAK1='0';

if (isset($MS_WEAPON) and $MS_WEAPON=='1') $MS_WEAPON1='1';
if (!isset($MS_WEAPON)) $MS_WEAPON1='0';

if (isset($MS_ART) and $MS_ART=='1') $MS_ART1='1';
if (!isset($MS_ART)) $MS_ART1='0';

if (isset($MS_VSADNIK) and $MS_VSADNIK=='1') $MS_VSADNIK1='1';
if (!isset($MS_VSADNIK)) $MS_VSADNIK1='0';

if (isset($MS_VOR) and $MS_VOR=='1') $MS_VOR1='1';
if (!isset($MS_VOR)) $MS_VOR1='0';

if (isset($MS_LEK) and $MS_LEK=='1') $MS_LEK1='1';
if (!isset($MS_LEK)) $MS_LEK1='0';

if (isset($MS_PARIR) and $MS_PARIR=='1') $MS_PARIR1='1';
if (!isset($MS_PARIR)) $MS_PARIR1='0';

if (isset($MS_KUZN) and $MS_KUZN=='1') $MS_KUZN1='1';
if (!isset($MS_KUZN)) $MS_KUZN1='0';

if (isset($MS_LUK) and $MS_LUK=='1') $MS_LUK1='1';
if (!isset($MS_LUK)) $MS_LUK1='0';

if (isset($MS_THROW) and $MS_THROW=='1') $MS_THROW1='1';
if (!isset($MS_THROW)) $MS_THROW1='0';

if (isset($MS_SWORD) and $MS_SWORD=='1') $MS_SWORD1='1';
if (!isset($MS_SWORD)) $MS_SWORD1='0';

if (isset($MS_AXE) and $MS_AXE=='1') $MS_AXE1='1';
if (!isset($MS_AXE)) $MS_AXE1='0';

if (isset($MS_SPEAR) and $MS_SPEAR=='1') $MS_SPEAR1='1';
if (!isset($MS_SPEAR)) $MS_SPEAR1='0';

if (isset($game))
{
		if ($game=="arcanoid.swf")
		{
		$game_file = "arcanoid.swf";
		$game_name="Арканоид (102940 байт)";
		}
		elseif ($game=="battleships.swf")
		{
		$game_file = "battleships.swf";
		$game_name="Морской бой (377909 байт)";
		}
		elseif ($game=="cubebuster.swf")
		{
		$game_file = "cubebuster.swf";
		$game_name="Взрывающиеся кубики (317946 байт)";
		}
		elseif ($game=="gyroball.swf")
		{
		$game_file = "gyroball.swf";
		$game_name="Гиробол (102128 байт)";
		}
		elseif ($game=="IQtest.swf")
		{
		$game_file = "IQtest.swf";
		$game_name="IQ тест (474307 байт)";
		}
		elseif ($game=="mahjonggwm.swf")
		{
		$game_file = "mahjonggwm.swf";
		$game_name="Mahjongg (276634 байт)";
		}
		elseif ($game=="Solitaire.swf")
		{
		$game_file = "Solitaire.swf";
		$game_name="Пасьянс (196304 байт)";
		}
		elseif ($game=="Tetris.swf")
		{
		$game_file = "Tetris.swf";
		$game_name="Тетрис (455273 байт)";
		}
		elseif ($game=="AirFight.SWF")
		{
		$game_file = "AirFight.SWF";
		$game_name="Воздушный бой (121339 байт)";
		}
		elseif ($game=="Ball.SWF")
		{
		$game_file = "Ball.SWF";
		$game_name="Арканойд 2 (40387 байт)";
		}
		elseif ($game=="Bandit.SWF")
		{
		$game_file = "Bandit.SWF";
		$game_name="Однорукий бандит (56445 байт)";
		}
		elseif ($game=="Bart Fignt.SWF")
		{
		$game_file = "Bart Fignt.SWF";
		$game_name="Поединок Симпсонов (41966 байт)";
		}
		elseif ($game=="Bobbi.SWF")
		{
		$game_file = "Bobbi.SWF";
		$game_name="Помоги Бобби (97935 байт)";
		}
		elseif ($game=="Burnsie.SWF")
		{
		$game_file = "Burnsie.SWF";
		$game_name="Спаси Симпсона от термоядерных отходов (33770 байт)";
		}
		elseif ($game=="Busy.SWF")
		{
		$game_file = "Busy.SWF";
		$game_name="Головоломка КОЛЬЦА (118111 байт)";
		}
		elseif ($game=="Car 1.SWF")
		{
		$game_file = "Car 1.SWF";
		$game_name="Гонки COTSEY&nbsp;SPY&nbsp;HUNTER (170318 байт)";
		}
		elseif ($game=="Car 2.SWF")
		{
		$game_file = "Car 2.SWF";
		$game_name="Гонки (149833 байт)";
		}
		elseif ($game=="Catogochi.SWF")
		{
		$game_file = "Catogochi.SWF";
		$game_name="Кошкогочи (111780 байт)";
		}
		elseif ($game=="Colpac.SWF")
		{
		$game_file = "Colpac.SWF";
		$game_name="Наперстки 2 (33563 байт)";
		}
		elseif ($game=="Crystal.SWF")
		{
		$game_file = "Crystal.SWF";
		$game_name="Остров кристаллов (147833 байт)";
		}
		elseif ($game=="Fight.swf")
		{
		$game_file = "Fight.swf";
		$game_name="Подеремся (88237 байт)";
		}
		elseif ($game=="Flying Elefant.SWF")
		{
		$game_file = "Flying Elefant.SWF";
		$game_name="Летающие слоники (120501 байт)";
		}
		elseif ($game=="Football.SWF")
		{
		$game_file = "Football.SWF";
		$game_name="Футбол (45590 байт)";
		}
		elseif ($game=="Golf.SWF")
		{
		$game_file = "Golf.SWF";
		$game_name="Гольф (107373 байт)";
		}
		elseif ($game=="Gomer.SWF")
		{
		$game_file = "Gomer.SWF";
		$game_name="Злой Симпсон (15013 байт)";
		}
		elseif ($game=="Gruppa.SWF")
		{
		$game_file = "Gruppa.SWF";
		$game_name="Виртуальный джаз-бенд (234886 байт)";
		}
		elseif ($game=="Hunting.SWF")
		{
		$game_file = "Hunting.SWF";
		$game_name="Охота (114235 байт)";
		}
		elseif ($game=="Japan.SWF")
		{
		$game_file = "Japan.SWF";
		$game_name="Японские мечи (318352 байт)";
		}
		elseif ($game=="Nery Pout.SWF")
		{
		$game_file = "Nery Pout.SWF";
		$game_name="Концерт для хора с котенком (113892 байт)";
		}
		elseif ($game=="Oracul.SWF")
		{
		$game_file = "Oracul.SWF";
		$game_name="Гадание с оракулом (66931 байт)";
		}
		elseif ($game=="Paceman 1.SWF")
		{
		$game_file = "Paceman 1.SWF";
		$game_name="Пак-ман 1 (116408 байт)";
		}
		elseif ($game=="Paceman 2.SWF")
		{
		$game_file = "Paceman 2.SWF";
		$game_name="Пак-ман 2 (42334 байт)";
		}
		elseif ($game=="Pomni 1.SWF")
		{
		$game_file = "Pomni 1.SWF";
		$game_name="Запоминайка 1 (58429 байт)";
		}
		elseif ($game=="Pomni 2.SWF")
		{
		$game_file = "Pomni 2.SWF";
		$game_name="Запоминайка 2 (62440 байт)";
		}
		elseif ($game=="Qest.SWF")
		{
		$game_file = "Qest.SWF";
		$game_name="Квест (273175 байт)";
		}
		elseif ($game=="Ruletca.SWF")
		{
		$game_file = "Ruletca.SWF";
		$game_name="Рулетка (121298 байт)";
		}
		elseif ($game=="Safari.SWF")
		{
		$game_file = "Safari.SWF";
		$game_name="Сафари (233070 байт)";
		}
		elseif ($game=="Sciner.SWF")
		{
		$game_file = "Sciner.SWF";
		$game_name="Спаси Симпсона (157317 байт)";
		}
		elseif ($game=="SeaFight.SWF")
		{
		$game_file = "SeaFight.SWF";
		$game_name="Морской бой 2 (160872 байт)";
		}
		elseif ($game=="Shooter.SWF")
		{
		$game_file = "Shooter.SWF";
		$game_name="Стрелялка (2023776 байт)";
		}
		elseif ($game=="Sniper.SWF")
		{
		$game_file = "Sniper.SWF";
		$game_name="Снайпер (77745 байт)";
		}
		elseif ($game=="Socks.SWF")
		{
		$game_file = "Socks.SWF";
		$game_name="Socks (261310 байт)";
		}
		elseif ($game=="Tetris 2.SWF")
		{
		$game_file = "Tetris 2.SWF";
		$game_name="Тетрис 3 (31743 байт)";
		}
		elseif ($game=="Tetris 3.SWF")
		{
		$game_file = "Tetris 3.SWF";
		$game_name="Тетрис 4 (146976 байт)";
		}
		elseif ($game=="Worm.SWF")
		{
		$game_file = "Worm.SWF";
		$game_name="Червяк (71846 байт)";
		}
		elseif ($game=="XvsO.SWF")
		{
		$game_file = "XvsO.SWF";
		$game_name="Крестики-нолики (81695 байт)";
		}
	else
		{
		$game_file = "";
		$game_name="";
		}
}
else
{
		$game_file = "";
		$game_name="";
}

$up=myquery("update game_gorod set
name='$town',
rustown='$rustown',
vhod='$vhod',
opis='".$opis."',
style='$style',
color='$color',
center='$center',
news='".$news."',
clan='$clan',
race='$race',
view='$v',

STR='$STR1',
NTL='$NTL1',
ART='$ART1',
VIT='$VIT1',
DEX='$DEX1',
SPD='$SPD1',

WAR='$WAR1',
COOK='$COOK1',
PIE='$PIE1',
EXPLOR='$EXPLOR1',
CRAFT='$CRAFT1',
CARD='$CARD1',
UNKNOW='$UNKNOW1',
MUSIC='$MUSIC1',
PET='$PET1',

MS_KULAK='$MS_KULAK1',
MS_WEAPON='$MS_WEAPON1',
MS_ART='$MS_ART1',
MS_VSADNIK='$MS_VSADNIK1',
MS_VOR='$MS_VOR1',
MS_LEK='$MS_LEK1',
MS_PARIR='$MS_PARIR1',
MS_KUZN='$MS_KUZN1',
MS_LUK='$MS_LUK1',
MS_THROW='$MS_THROW1',
MS_SWORD='$MS_SWORD1',
MS_AXE='$MS_AXE1',
MS_SPEAR='$MS_SPEAR1',

game_file='$game_file',
game_name='$game_name',

torg_Elf='$torg_elf1',
torg_Orc='$torg_orc1',
torg_Nazgul='$torg_nazgul1',
torg_Hobbit='$torg_hobbit1',
torg_Human='$torg_human1',
torg_Gnom='$torg_gnome1',
torg_Goblin='$torg_goblin1',
torg_Troll='$torg_troll1',

side='$side1',

enter_Elf='$enter_elf1',
enter_Orc='$enter_orc1',
enter_Nazgul='$enter_nazgul1',
enter_Hobbit='$enter_hobbit1',
enter_Human='$enter_human1',
enter_Gnom='$enter_gnome1',
enter_Goblin='$enter_goblin1',
enter_Troll='$enter_troll1'

where town='$edit'") or die(mysql_error());

		myquery("DELETE FROM game_gorod_set_option WHERE gorod_id=$edit");
		if (isset($_REQUEST["options"]))
		{
			for ($i=0; $i<count($_REQUEST["options"]); $i++)
			{
				if ($_REQUEST["options"][$i]>0)
				{
					myquery("INSERT INTO game_gorod_set_option (gorod_id,option_id) VALUES ($edit,'".$_REQUEST["options"][$i]."')");
				}
			}
		}
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Изменил город: <b>".$rustown."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
	 echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
		}
}


if(!isset($edit) and !isset($new) and !isset($new_obj) and !isset($edit_obj))
{
	echo'<a href="?opt=main&option=gorod&new">Добавить город</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?opt=main&option=gorod_option">Редактировать опции городов</a></center>';
	echo'<table border=0 width=70% align=center>';
	$sel=myquery("select * from game_gorod where rustown<>'' order by binary rustown ASC");
	$i=0;
	while($shop=mysql_fetch_array($sel))
	{
		$i++;
		if ($i==1) {echo '<tr bgcolor=#000040>';}
		else {$i=0; echo '<tr bgcolor=#00002B>';};
		echo'<td align="right">'.$shop['name'].'</td><td align="right">'.$shop['rustown'].'</td><td><a href="?opt=main&option=gorod&edit='.$shop['town'].'">Редактировать</a>, <a href="?opt=main&option=gorod&del_g='.$shop['town'].'">Удалить</a></td></tr>';
	}
	$sel=myquery("select * from game_gorod where rustown='' order by name ASC");
	$i=0;
	while($shop=mysql_fetch_array($sel))
	{
		$i++;
		if ($i==1) {echo '<tr bgcolor=#400000>';}
		else {$i=0; echo '<tr bgcolor=#400040>';};
		echo'<td align="right">'.$shop['name'].'</td><td align="right">'.$shop['name'].'</td><td><a href="?opt=main&option=gorod&edit='.$shop['town'].'">Редактировать</a>, <a href="?opt=main&option=gorod&del_g='.$shop['town'].'">Удалить</a></td></tr>';
	}
	echo'</table>';
}


echo'<br><hr>';
echo '<center>Обьекты на карте:<br>';

if (isset($new_obj))
{
	if (!isset($save))
	{
		echo'<form action="" method="post"><table border=0>
		<tr><td>Название:</td><td><input type=text name=town></td></tr>
		<tr><td>Кнопка входа:</td><td><input type=text name=name></td></tr>
		<tr><td>Описание перед входом:</td><td><textarea name=text cols=70 class=input rows=4></textarea></td></tr>
		<tr><td>Вход только кланам (перечислить id кланов через запятую):</td><td><input type=text name=clan size=70></td></tr>
		<tr><td>Вход только игрокам (перечислить id игроков через запятую)</td><td><input type=text name=userr size=70></td></tr>
		<tr><td>Вход только расе (Одно наименование)</td><td><input type=text name=race></td></tr>
		<tr><td>Плата за вход</td><td><input type=text name=gp size=3> золотых</td></tr>
		<tr><td>Время открытия </td><td><input type=text name=timestart value="'.date("d.m.Y H:i").'" size=20> Шаблон: 21.03.2005 15:43 (если пусто - открыто всегда)</td></tr>
		<tr><td>Время закрытия </td><td><input type=text name=time value="'.date("d.m.Y H:i").'" size=20> Шаблон: 21.03.2005 15:43 (если пусто - открыто всегда)</td></tr>
		<tr><td align=right>Виден в view.'.DOMAIN.'</td><td><input name="view" type="checkbox" value="1" checked></td></tr>
		<tr><td align=right>Проход перемещается</td><td><input name="moved" type="checkbox" value="1"></td></tr>
		<tr><td align=right>Таймаут перемещения</td><td><select name="movetime">
		<option value="0">Не перемещать</option>
		<option value="1">1 минута</option>
		<option value="3">3 минуты</option>
		<option value="10">10 минут</option>
		<option value="60">1 час</option>
		<option value="120">2 часа</option>
		<option value="180">3 часа</option>
		<option value="240">4 часа</option>
		<option value="360">6 часов</option>
		<option value="720">12 часов</option>
		</select></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Добавить"><input name="save" type="hidden" value=""></td></tr>';
		echo'</table>';
	}
	else
	{
		echo'Проход '.$town.' добавлен';
		if (isset($view) and $view=='1') $v='1';
		if (!isset($view)) $v='0';
		if (isset($moved) and $moved=='1') $m='1';
		if (!isset($moved)) $m='0';

		$update=myquery("INSERT INTO game_obj (town,name,text,clan,user,race,time,gp,timestart,view,moved,movetime)
		VALUES ('$town','$name','$text','$clan','$userr','$race','$time','$gp','$timestart','$v','$m','$movetime')");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Добавил проход: <b>".$town."</b> (".$name.")',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
	}
}

if (isset($edit_obj))
{
	if (isset($_GET['del_cond']))
	{
		myquery("DELETE FROM game_obj_require WHERE id=".$_GET['del_cond']."");
	}
	if (!isset($_POST['save']) AND !isset($_POST['add_cond']) AND !isset($_POST['save_cond']))
	{
		$sel=myquery("select * from game_obj where id='$edit_obj'");
		$shop=mysql_fetch_array($sel);
		echo'<form action="" method="post"><input name="edit_obj" type="hidden" value="'.$edit_obj.'"><table border=0>
		<tr><td>Название:</td><td><input type=text name=town value="'.$shop['town'].'"></td></tr>
		<tr><td>Кнопка входа:</td><td><input type=text name=name value="'.$shop['name'].'"></td></tr>
		<tr><td>Описание перед входом:</td><td><textarea name=text cols=70 class=input rows=4>'.$shop['text'].'</textarea></td></tr>
		<tr><td>Вход только кланам (перечислить id кланов через запятую):</td><td><input type=text name=clan value="'.$shop['clan'].'" size=70></td></tr>
		<tr><td>Вход только игрокам (перечислить id игроков через запятую)</td><td><input type=text name=userr value="'.$shop['user'].'" size=70></td></tr>
		<tr><td>Вход только Расе (Одно наименование)</td><td><input type=text name=race value="'.$shop['race'].'"></td></tr>
		<tr><td>Плата за вход</td><td><input type=text name=gp value="'.$shop['gp'].'" size=3> золотых</td></tr>
		<tr><td>Время открытия </td><td><input type=text name=timestart value="'.$shop['timestart'].'" size=20> Шаблон: 21.03.2005 15:43 (если пусто - открыто всегда)</td></tr>
		<tr><td>Время закрытия </td><td><input type=text name=time value="'.$shop['time'].'" size=20> Шаблон: 21.03.2005 15:43 (если пусто - открыто всегда)</td></tr>
		<tr><td align=right>Виден в view.'.DOMAIN.'</td><td><input name="view" type="checkbox" value="1"';
		if ($shop['view']==1) echo ' checked';
		echo '></td></tr>
		<tr><td align=right>Проход перемещается</td><td><input name="moved" type="checkbox" value="1"';
		if ($shop['moved']==1) echo ' checked';
		echo '></td></tr>
		<tr><td align=right>Таймаут перемещения</td><td><select name="movetime">
		<option value="0"'; if ($shop['movetime']==0) echo ' selected'; echo'>Не перемещать</option>
		<option value="1"'; if ($shop['movetime']==1) echo ' selected'; echo'>1 минута</option>
		<option value="3"'; if ($shop['movetime']==3) echo ' selected'; echo'>3 минуты</option>
		<option value="10"'; if ($shop['movetime']==10) echo ' selected'; echo'>10 минут</option>
		<option value="60"'; if ($shop['movetime']==60) echo ' selected'; echo'>1 час</option>
		<option value="120"'; if ($shop['movetime']==120) echo ' selected'; echo'>2 часа</option>
		<option value="180"'; if ($shop['movetime']==180) echo ' selected'; echo'>3 часа</option>
		<option value="240"'; if ($shop['movetime']==240) echo ' selected'; echo'>4 часа</option>
		<option value="360"'; if ($shop['movetime']==360) echo ' selected'; echo'>6 часов</option>
		<option value="720"'; if ($shop['movetime']==720) echo ' selected'; echo'>12 часов</option>
		</select></td></tr>';
		$sel_require = myquery("SELECT DISTINCT nomer FROM game_obj_require WHERE obj_id=".$shop['id']."");
		if (mysql_num_rows($sel_require))
		{
			echo '<tr><td colspan="2" align="center">Заданные условия для прохода</td></tr><tr><td colspan="2" align="center">';
			
			echo '<table cellspacing="1" border="1" cellpadding="4">';
			while (list($nom)=mysql_fetch_array($sel_require))
			{
				$sel_cond = myquery("SELECT * FROM game_obj_require WHERE nomer=$nom AND obj_id=".$shop['id']."");
				echo '<tr><td rowspan="'.(mysql_num_rows($sel_cond)+1).'" align="center" valign="middle">'.$nom.'</td></tr>';
				while ($cond = mysql_fetch_array($sel_cond))
				{
					echo '<tr><td>';
					switch ($cond['type'])
					{
						case 1:
							echo 'Уровень игрока ';
						break;
						case 2:
							echo 'Количество наличных денег ';
						break;
						case 3:
							echo 'Наличие предмета ';
						break;
						case 34:
							echo 'Одетый предмет ';
						break;
						case 4:
							echo 'Наличие коня ';
						break;
						case 5:
							echo 'HP MAX ';
						break;
						case 6:
							echo 'MP MAX ';
						break;
						case 7:
							echo 'STM MAX ';
						break;
						case 8:
							echo 'Сила игрока ';
						break;
						case 9:
							echo 'Интеллект игрока ';
						break;
						case 10:
							echo 'Ловкость игрока ';
						break;
						case 11:
							echo 'Мудрость игрока ';
						break;
						case 12:
							echo 'Выносливость игрока ';
						break;
						case 13:
							echo 'Специализация "владение артефактом" ';
						break;
						case 14:
							echo 'Специализация "вор" ';
						break;
						case 15:
							echo 'Специализация "владение оружием" ';
						break;
						case 16:
							echo 'Специализация "кулачный бой" ';
						break;
						case 17:
							echo 'Специализация "парирование" ';
						break;
						case 18:
							echo 'Специализация "лекарь" ';
						break;
						case 19:
							echo 'Количество побед ';
						break;
						case 20:
							echo 'Количество поражений ';
						break;
						case 21:
							echo 'Выиграно в Две Башни ';
						break;
						case 22:
							echo 'Проиграно в Две Башни ';
						break;
						case 23:
							echo 'Пройдено лабиринтов ';
						break;
						case 24:
							echo 'Магия "Воин" ';
						break;
						case 25:
							echo 'Магия "Волшебник" ';
						break;
						case 26:
							echo 'Магия "Лучник" ';
						break;
						case 27:
							echo 'Магия "Вор" ';
						break;
						case 28:
							echo 'Магия "Разбойник" ';
						break;
						case 29:
							echo 'Магия "Бард" ';
						break;
						case 30:
							echo 'Магия "Варвар" ';
						break;
						case 31:
							echo 'Магия "Друид" ';
						break;
						case 32:
							echo 'Магия "Паладин" ';
						break;
						case 32:
							echo 'Защита игрока ';
						break;
						case 100:
							echo 'Кодовое слово ';
						break;
						case 101:
							echo 'Склонность игрока (1 - нейтрал, 2 - свет, 3 - тьма)';
						break;
					}
					switch ($cond['condition'])
					{
						case 1:
							echo '<=';
						break;
						case 2:
							echo '<';
						break;
						case 3:
							echo '=';
						break;
						case 4:
							echo '>=';
						break;
						case 5:
							echo '>';
						break;
						case 6:
							echo '<>';
						break;
					}
					echo ' '.$cond['value'].'</td><td><a href="admin.php?opt=main&option=gorod&edit_obj='.$_GET['edit_obj'].'&del_cond='.$cond['id'].'">Удалить условие</a></td></tr>';    
				}
				echo '</tr>';
			}
			echo '</table>';
			
			echo '</td></tr>';
		}
		echo'<tr><td colspan=2 align=center></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="add_cond" type="submit" value="Добавить условия для прохода"></td></tr>';
		echo'<tr><td colspan=2 align=center></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Сохранить"></td></tr>';
		echo'</table></form>';
	}
	elseif (isset($_POST['add_cond']))
	{
		?>
		<script type="text/javascript">
		function check_visible()
		{
			type = document.getElementById("type");
			items = document.getElementById("items");
			horse = document.getElementById("horse");
			value = document.getElementById("value");
			condition = document.getElementById("condition");
			if (type.value=="3" || type.value=="34")
			{
				items.style.display="block";    
				horse.style.display="none";    
				value.style.display="none";
				condition.value="3";
				condition.disabled=true;
			}
			else
			if (type.value=="4")
			{
				items.style.display="none";    
				horse.style.display="block";    
				value.style.display="none";    
				condition.value="3";
				condition.disabled=true;
			}
			else
			{
				items.style.display="none";    
				horse.style.display="none";    
				value.style.display="block";    
				condition.disabled=false;
			}
			return;
		}
		</script>
		<?
		echo '<form action="" method="post" autocomplete="off"><input name="edit_obj" type="hidden" value="'.$edit_obj.'"><table border=0>';
		echo '<tr><td>Номер условия</td><td><input type="text" name="nomer" value="" size="3" maxsize="3"></td></tr>';        
		echo '<tr><td>Тип условия</td><td><select id="type" name="type" onchange="check_visible();">
		<option value="1">Уровень игрока</option>
		<option value="2">Количество наличных денег</option>
		<option value="3">Наличие предмета</option>
		<option value="34">Одетый предмет</option>
		<option value="4">Наличие коня</option>
		<option value="5">HP MAX</option>
		<option value="6">MP MAX</option>
		<option value="7">STM MAX</option>
		<option value="8">Сила игрока</option>
		<option value="9">Интеллект игрока</option>
		<option value="10">Ловкость игрока</option>
		<option value="11">Мудрость игрока</option>
		<option value="12">Выносливость игрока</option>
		<option value="33">Защита игрока</option>
		<option value="13">Специализация "владение артефактом"</option>
		<option value="14">Специализация "вор"</option>
		<option value="15">Специализация "владение оружием"</option>
		<option value="16">Специализация "кулачный бой"</option>
		<option value="17">Специализация "парирование"</option>
		<option value="18">Специализация "лекарь"</option>
		<option value="19">Количество побед</option>
		<option value="20">Количество поражений</option>
		<option value="21">Выиграно в Две Башни</option>
		<option value="22">Проиграно в Две Башни</option>
		<option value="23">Пройдено лабиринтов</option>
		<option value="24">Магия "Воин"</option>
		<option value="25">Магия "Волшебник"</option>
		<option value="26">Магия "Лучник"</option>
		<option value="27">Магия "Вор"</option>
		<option value="28">Магия "Разбойник"</option>
		<option value="29">Магия "Бард"</option>
		<option value="30">Магия "Варвар"</option>
		<option value="31">Магия "Друид"</option>
		<option value="32">Магия "Паладин"</option>
		<option value="100">Кодовое слово</option>
		<option value="101">Склонность игрока (1 - нейтрал, 2 - свет, 3 - тьма)</option>
		</select>
		</td></tr>';        
		echo '<tr><td>Знак условия</td><td><select id="condition" name="condition">
		<option value="1"><=</option>
		<option value="2"><</option>
		<option value="3">=</option>
		<option value="4">>=</option>
		<option value="5">></option>
		<option value="6"><></option>
		</select>
		</td></tr>';        
		echo '<tr><td>Значение условия</td><td>';
		$sel_item = myquery("SELECT game_items_factsheet.id,game_items_factsheet.name,game_items_factsheet.type,game_items_factsheet.race,game_har.name AS race_name FROM game_items_factsheet LEFT JOIN (game_har) ON (game_har.id=game_items_factsheet.race) ORDER BY game_items_factsheet.type,game_items_factsheet.name");
		echo '<select id="items" name="items" style="display:none;">';
		while ($item = mysql_fetch_array($sel_item))
		{
			echo '<option value="'.$item['name'].'">'.$item['name'].' ('.$item['type'].' '.(($item['race_name']==NULL) ? '' : $item['race_name'] ).')</option>';
		}
		echo '</select>';
		$sel_item = myquery("SELECT id,nazv FROM game_vsadnik ORDER BY nazv");
		echo '<select id="horse" name="horse" style="display:none;">';
		while ($item = mysql_fetch_array($sel_item))
		{
			echo '<option value="'.$item['id'].'">'.$item['nazv'].'</option>';
		}
		echo '</select>';
		echo '<input id="value" style="display:block;" type="text" name="value" value="" size="30" maxsize="30"></td></tr>';        
		echo'<tr><td colspan=2 align=center></td></tr>';
		echo'<tr><td colspan=2 align=center><input name="save_cond" type="submit" value="Сохранить"></td></tr>';
		echo'</table></form><br><br>
		Примечание: Для условий с одинаковым номером выполняется функия "И". Для условий с разными номерами выполняется функция "ИЛИ". Например, надо чтобы проход был открыт для игроков выше 30 уровня или для игроков у которых 50 силы и 20 интеллекта. Тогда надо записать 3 условия. Условие 1 - номер условия "1", условие = "уровень выше 30", Условие 2 - номер условия "2", условие = "сила = 50", Условие 3 - номер условия "2", условие = "интеллект = 20".';
	}                                                         
	elseif (isset($_POST['save_cond']))
	{
		echo'Добавлено условие для прохода';
		 if ($_POST['type']==3 OR $_POST['type']==34)
		 {
			$_POST['condition']="=";
			$_POST['value']=$_POST['items'];
		 }
		 if ($_POST['type']==4)
		 {
			$_POST['condition']="=";
			$_POST['value']=$_POST['horse'];
		 }
		$up=myquery("INSERT INTO game_obj_require (`obj_id`,`nomer`,`type`,`condition`,`value`) VALUES ('".$_POST['edit_obj']."','".$_POST['nomer']."','".$_POST['type']."','".$_POST['condition']."','".$_POST['value']."')");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod&edit_obj='.$_POST['edit_obj'].'">';
	}
	elseif (isset($_POST['save']))
	{
		echo'Проход '.$town.' изменен';
		if (isset($view) and $view=='1') $v='1';
		if (!isset($view)) $v='0';
		if (isset($moved) and $moved=='1') $m='1';
		if (!isset($moved)) $m='0';

		$up=myquery("update game_obj set
		town='$town',
		name='$name',
		text='$text',
		clan='$clan',
		user='$userr',
		race='$race',
		time='$time',
		timestart='$timestart',
		view='$v',
		moved='$m',
		movetime='$movetime',
		gp='$gp' where id='$edit_obj'");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		VALUES (
		'".$char['name']."',
		'Изменил проход: <b>".$town."</b>',
		'".time()."',
		'".$da['mday']."',
		'".$da['mon']."',
		'".$da['year']."')")
		 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=gorod">';
	}
}


if(!isset($edit) and !isset($new) and !isset($edit_obj) and !isset($new_obj))
{
	echo'<a href="?opt=main&option=gorod&new_obj">Добавить проход</a></center>';
	echo'<table border=0 width=70% align=center>';
	$sel=myquery("select * from game_obj order by town DESC");
	$i=0;
	while($shop=mysql_fetch_array($sel))
		{
		$i++;
		if ($i==1) {echo '<tr bgcolor=#580058>';}
		else {$i=0; echo '<tr bgcolor=#585800>';};
		echo'<td align="right">'.$shop['town'].'</td><td><a href="?opt=main&option=gorod&edit_obj='.$shop['id'].'">Редактировать</a>, <a href="?opt=main&option=gorod&del_p='.$shop['town'].'">Удалить</a></td></tr>';
		}
	echo'</table>';
}

echo'<center><a href="?opt=main&option=gorod">Главная</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>