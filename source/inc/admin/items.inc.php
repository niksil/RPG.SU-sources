<script type="text/javascript" language="JavaScript">
function count()
{
top.form.need.value=top.form.ostr.value+top.form.ontl.value+top.form.opie.value+top.form.ovit.value+top.form.odex.value+top.form.ospd.value
}
</script>
<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['items'] >= 1)
{
	if(isset($info) and $adm['items'] == 2)
	{
		$info = (int)$info;
		$item=mysql_fetch_array(myquery("select * from game_items_factsheet where id=$info"));
		$sel_user = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND priznak=0 GROUP BY item_id");
		list($count_user) = mysql_fetch_array($sel_user);
		$sel_down = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND priznak=2 GROUP BY item_id");    
		list($count_down) = mysql_fetch_array($sel_down);
		$sel_rynok = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND (priznak=1 OR priznak=3) GROUP BY item_id");    
		list($count_rynok) = mysql_fetch_array($sel_rynok);
		$sel_hran = myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." AND priznak=4 GROUP BY item_id");    
		list($count_hran) = mysql_fetch_array($sel_hran);

		$all_items = $count_user+$count_rynok+$count_down+$count_hran;
		echo '<center><b>Подсчет количества предмета <font color=#FFFFFF>'.$item['name'].'</font> в игре</b><br>';
		echo '<ul>';
		echo '<li>Количество у игроков: <font color=#FFFF00><b>'.$count_user.'</b></font></li>';
		echo '<li>Количество на рынках и на почте: <font color=#FFFF00><b>'.$count_rynok.'</b></font></li>';
		echo '<li>Количество на земле: <font color=#FFFF00><b>'.$count_down.'</b></font></li>';
		echo '<li>Количество в личных хранилищах: <font color=#FFFF00><b>'.$count_hran.'</b></font></li>';
		echo '</ul>';
		echo '<b>Всего <font color=#FF0000><b>'.$all_items.'</b></font> '.pluralForm($all_items,'предмет','предмета','предметов').'<br>';

		$select = myquery("SELECT user_id,SUM(count_item) AS kol FROM game_items WHERE item_id=".$item['id']." AND priznak=0 GROUP BY user_id ORDER BY kol DESC");
		if (mysql_num_rows($select))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">Предметы у игроков</font></b><br><br>';
			echo '<table>';
			$nom=0;
			while ($it=mysql_fetch_array($select))
			{
				$nom++;
				if ($nom==6) $nom=1;
				if ($nom==1) echo '<tr>';
				echo '<td>';
				echo '<font size="2" color="#bbbbbb">';
				$clan_id = (int)get_user('clan_id',$it['user_id']); 
				if ($clan_id!=0) echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$clan_id.'.gif"> ';
				echo '<a href="http://'.DOMAIN.'/view/?userid='.$it['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
				echo'' . get_user('name',$it['user_id']). '</font> ('.$it['kol'].')<br>';
				echo '</td>';
				if ($nom==5) echo '</tr>';
			}
			echo '</table>';
		}
				
		$select = myquery("SELECT user_id,SUM(count_item) AS kol FROM game_items WHERE item_id=".$item['id']." AND priznak=4 GROUP BY user_id ORDER BY kol DESC");
		if (mysql_num_rows($select))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">Предметы в домах игроков</font></b><br><br>';
			echo '<table>';
			$nom=0;
			while ($it=mysql_fetch_array($select))
			{
				$nom++;
				if ($nom==6) $nom=1;
				if ($nom==1) echo '<tr>';
				echo '<td>';
				echo '<font size="2" color="#bbbbbb">';
				$clan_id = (int)get_user('clan_id',$it['user_id']); 
				if ($clan_id!=0) echo'<img src="http://'.IMG_DOMAIN.'/clan/'.$clan_id.'.gif"> ';
				echo '<a href="http://'.DOMAIN.'/view/?userid='.$it['user_id'].'" target="_blank"><img src="http://'.IMG_DOMAIN.'/nav/i.gif" border=0 alt="Инфо"></a>';
				echo'' . get_user('name',$it['user_id']). '</font> ('.$it['kol'].')<br>';
				echo '</td>';
				if ($nom==5) echo '</tr>';
			}
			echo '</table>';
		}
				
		$select = myquery("SELECT game_items.map_name,game_items.map_xpos,game_items.map_ypos,game_maps.name FROM game_items LEFT JOIN game_maps ON game_maps.id=game_items.map_name WHERE item_id=".$item['id']." AND priznak=2 ORDER BY game_maps.name");
		if (mysql_num_rows($select ))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на земле</font></b><br><br>';
			echo '<table>';
			while ($it=mysql_fetch_array($select))
			{
				echo '<tr><td>';
				echo '<font size="2" color="#bbbbbb">';
				echo 'Лежит на карте: '.$it['name'].' x-'.$it['map_xpos'].', y-'.$it['map_ypos'].'';
				echo '</font><br>';
				echo '</td></tr>';
			}
			echo '</table>';
		}

		$select_old_items = myquery("SELECT DISTINCT town FROM game_items WHERE item_id=".$item['id']." AND priznak=1");
		if (mysql_num_rows($select_old_items))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на рынке</font></b><br><br>';
			{
				echo '<table>';
				while (list($town) = mysql_fetch_array($select_old_items))
				{
					$kol_items = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id']." AND town=$town"),0,0);
					$select = myquery("SELECT rustown FROM game_gorod WHERE town=".$town."");
					list($rustown) = mysql_fetch_array($select);
					if ($kol_items!=0)
					{
						echo '<tr><td>';
						echo '<font size="2" color="#bbbbbb">';
						echo ''.$kol_items.' '.pluralForm($kol_items,'предмет','предмета','предметов').' на рынке в городе: '.$rustown.'.<br>';
						echo '</font>';
						echo '</td></tr>';
					}
				}
				echo '</table>';
			}
		}
		
		$select_old_items = myquery("SELECT DISTINCT town FROM game_items WHERE item_id=".$item['id']." AND priznak=3");
		if (mysql_num_rows($select_old_items))
		{
			echo'<br><hr><br><b><font size="3" color="#bbbbbb">Предметы на почте</font></b><br><br>';
			{
				echo '<table>';
				while (list($town) = mysql_fetch_array($select_old_items))
				{
					$kol_items = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id']." AND town=$town"),0,0);
					$select = myquery("SELECT rustown FROM game_gorod WHERE town=".$town."");
					list($rustown) = mysql_fetch_array($select);
					if ($kol_items!=0)
					{
						echo '<tr><td>';
						echo '<font size="2" color="#bbbbbb">';
						echo ''.$kol_items.' '.pluralForm($kol_items,'предмет','предмета','предметов').' на почте в городе: '.$rustown.'.<br>';
						echo '</font>';
						echo '</td></tr>';
					}
				}
				echo '</table>';
			}
		}
		
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if(isset($new))
	{
		echo'<center><b>Добавление нового предмета</b><br><br>';
		echo'Ты хочешь добавить: <br><table cellspacing=2 cellpadding=2 border=0><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=1">Оружие</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=4">Щит</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=5">Доспех</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=3">Артефакт</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=6">Шлем</a></td></tr><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=7">Магию</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=2">Кольцо</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=8">Пояс</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=10">Перчатки</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=11">Обувь</a></td></tr><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=24">Инструменты</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=14">Поножи</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=15">Наручи</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=16">Украшения</a></td></tr><tr align="center"><td>
		<a href="admin.php?opt=main&option=items&new&tp_new=18">Луки</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=21">Стрелы</a> &nbsp;|&nbsp;  
		<a href="admin.php?opt=main&option=items&new&tp_new=19">Метательные предметы</a></td></tr><tr align="center"><td>';
        //<a href="admin.php?opt=main&option=items&new&tp_new=17">Маг.книги</a> | 
        echo '<a href="admin.php?opt=main&option=items&new&tp_new=13">Эликсиры</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&new&tp_new=12">Свитки</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&new&tp_new=9">Ожерелья</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&new&tp_new=22">Руны амулета</a></td></tr>
        <tr align="center"><td><a href="admin.php?opt=main&option=items&new&tp_new=20">Схемы предметов</a></td></tr>
        <tr align="center"><td><a href="admin.php?opt=main&option=items&new&tp_new=23">Комплекты предметов</a></td></tr></table>';
		if (isset($tp_new))
		{
			if (!isset($save))
			{
				echo '
				<script language="JavaScript" type="text/javascript">
				function count1()
				{
					val=
					Number(document.new_item_form.ostr.value)+
					Number(document.new_item_form.ontl.value)+
					Number(document.new_item_form.opie.value)+
					Number(document.new_item_form.ovit.value)+
					Number(document.new_item_form.odex.value)+
					Number(document.new_item_form.ospd.value)+
					Number(document.new_item_form.olucky.value);
					if (val>=5) val=0;
					val=val/2;
					document.new_item_form.need.value=val;
				}
				function count2()
				{
					document.new_item_form.plus.value=
					Number(document.new_item_form.dstr.value)+
					Number(document.new_item_form.dntl.value)+
					Number(document.new_item_form.dpie.value)+
					Number(document.new_item_form.dvit.value)+
					Number(document.new_item_form.ddex.value)+
					Number(document.new_item_form.dspd.value)+
					Number(document.new_item_form.dlucky.value)+
					((Number(document.new_item_form.hp_p.value)+
					  Number(document.new_item_form.mp_p.value)+
					  Number(document.new_item_form.stm_p.value)
					 )/10
					);
				}
				</script>
				';
				echo'<center><form action="" name="new_item_form" method="post">
				<table border="0" width="100%">
				<tr><td align="right">Название:</td><td><input name="name" value="" type="text" size="25"></td></tr>
				<tr><td align="right">Тип:</td><td><input name="type" value="'.type_str($tp_new).'" type="text" size="25" readonly="true"></td></tr>';

                if ($tp_new!=23)
                {
				    if($tp_new==1 OR $tp_new==21 OR $tp_new==19) echo'<tr><td align="right">Урон:</td><td><input name="indx" value="" type="text" size="5">&plusmn;<input name="deviation" value="" type="text" size="3"></td></tr>';
				    if($tp_new==3) echo'<tr><td align="right">Кол-во зарядов:</td><td><input name="item_uselife" value="" type="text" size="5"></td></tr>';
				    else $item_uselife=100;

				    if($tp_new==4) echo'<tr><td align="right">Защита:</td><td><input name="indx" value="" type="text" size="5"></td></tr>';

				    if($tp_new==3) 
				    {
					    echo'<tr><td align="right">Действие артефакта</td><td><select name="sv"><option>Атака</option><option selected>Защита</option><option>Лечение</option></select></td></tr>
					    <tr><td align="right">Урон, защита или лечение:</td><td><input name="indx" value="" type="text" size="5">&plusmn;<input name="deviation" value="" type="text" size="3"></td></tr>';
				    }
				    if($tp_new==1 or $tp_new==3 or $tp_new==4 or $tp_new==18 OR $tp_new==19 OR $tp_new==21) echo'<tr><td align="right">Чем ударил</td><td><input name="mode" value="" type="text" size="40"> - пример: двуручным мечом</td></tr>';
				    echo'<tr><td align="right">Вес:</td><td><input name="weight" value="" type="text" size="4"></td></tr>
				    <tr><td align="right">Редкость:</td><td><input name="redkost" value="" type="text" size="4" maxsize="2"></td></tr>';
                }
                echo '<tr><td align="right">Описание предмета: (если нужно)</td><td><textarea name="curse" cols="30" rows="15"></textarea></td></tr>

				<tr><td align="right">Адрес рисунка:</td><td><input name="imgg" value="*имя" type="text" size="40"> *имя - название файла (без .gif)</td></tr>
				<tr><td align="right">Адрес БОЛЬШОГО рисунка:</td><td><input name="imgbig" value="big/*имя" type="text" size="40"> *имя - название файла</td></tr>';

                if ($tp_new!=23)
                {
				    echo'<tr><td align="right">&nbsp;</td><td></td></tr>
				    <tr><td align="right">Базовая цена:</td><td><input name="item_cost" value="" type="text" size="8"> монет (без наценок торговцев)</td></tr>';
				    if ($tp_new==3)
				    {
					    echo'<tr><td align="right">Стоимость зарядки:</td><td><input name="quantity" value="20" type="text" size="8">% от базовой цены</td></tr>'; 
					    echo'<tr><td align="right">Время между зарядкой</td><td><input name="cooldown" value="120" type="text" size="8"> секунд</td></tr>'; 
				    }
				    if($tp_new==1 OR $tp_new==18 OR $tp_new==19)
				    {
					    echo'<tr><td align=right>Класс оружия:</td><td><select name="type_weapon"><option value="0">Без класса</option>';
					    echo'<option value="1">Кулачное</option>';
					    echo'<option value="2">Стрелковое</option>';
					    echo'<option value="3">Рубящее</option>';
					    echo'<option value="4">Дробящее</option>';
					    echo'<option value="5">Колющее</option>';
					    echo'<option value="6">Метательное</option>';
					    echo'</select></td></tr>';
					    echo '<tr><td align=right>Требует уровень навыка:</td><td><input name="type_weapon_need" value="" type="text" size="5"></td></tr>';
				    }
				    if ($tp_new==1)
				    {
					    echo '<tr><td align=right>Двуручное оружие</td><td><input name="in_two_hands" type="checkbox" value="1"></td></tr>';
				    }
				    if ($tp_new!=20)
				    {
					    echo'<tr><td align="right">&nbsp;</td><td></td></tr>

					    <tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>Требует характеристики:</b></font></td><td>
					    <tr><td align="right">Уровень: </td><td><input name="oclevel" value="" type="text" size="3"></td></tr>
					    <tr><td align="right">Сила: </td><td><input name="ostr" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">Интеллект: </td><td><input name="ontl" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">Ловкость: </td><td><input name="opie" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">Защита: </td><td><input name="ovit" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">Выносливость: </td><td><input name="odex" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">Мудрость: </td><td><input name="ospd" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">Удача: </td><td><input name="olucky" value="" type="text" size="3" onChange="count1()"></td></tr>
					    <tr><td align="right">&nbsp;</td><td></td></tr>
					    <tr><td align="right">Сумма треб.характеристик</td><td><input name="need" value="" type="text" size="4" readonly="true"></td></tr>
					    <tr><td align="right">&nbsp;</td><td></td></tr>';
                    }
                }
                
                if ($tp_new!=20)
                {
                    echo '
					<tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>Поднимает характеристики:</b></font></td><td>
					<tr><td align="right">Сила: </td><td><input name="dstr" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Интеллект: </td><td><input name="dntl" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Ловкость: </td><td><input name="dpie" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Защита: </td><td><input name="dvit" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Выносливость: </td><td><input name="ddex" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Мудрость: </td><td><input name="dspd" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Удача: </td><td><input name="dlucky" value="" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">&nbsp;</td><td>';

					echo'<tr bgcolor=000000><td align="right">Поднятие жизней маны и энергии:</td>
					<td>
					<table border=0>
					<tr><td><input name="hp_p" value="" type="text" size="3" onChange="count2()"></td><td>Жизнь</td></tr>
					<tr><td><input name="mp_p" value="" type="text" size="3" onChange="count2()"></td><td>Мана</td></tr>
					<tr><td><input name="stm_p" value="" type="text" size="3" onChange="count2()"></td><td>Энергия</td></tr>
					</table>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>';

					echo'<tr bgcolor=000000><td align="right">Повышение переноса вещей:</td>
					<td>
					На: <input name="cc_p" value="" type="text" size="2"><br>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>';
                    
					if(($tp_new==2 OR $tp_new==5 OR $tp_new==6 OR $tp_new==8)and($tp_new!=23))
					{
						echo'<tr bgcolor=000000><td align="right" width="50%">Вид брони<br /><br />(кольцо защищает плечо, доспех защищает ноги и тело, шлем защищает голову, пояс защищает пах)</td>
						<td>
						&nbsp;<input name="def_type" value="0" type="radio" checked>  одежда<br>
						&nbsp;<input name="def_type" value="1" type="radio">  кожанная<br>
						&nbsp;<input name="def_type" value="2" type="radio">  кольчужная (плетеная)<br>
						&nbsp;<input name="def_type" value="3" type="radio">  латы (пластинчатая)<br>
						<br>
						Значение "физической" защиты: <input name="def_index" value="0" type="text" size="4" maxsize="3"><br />
						Значение "магической" защиты: <input name="magic_def_index" value="0" type="text" size="4" maxsize="3">
						</td>
						</tr>';
					}

					echo'<tr><td align="right">Сумма повыш.характеристик</td><td><input name="plus" value="" type="text" size="4" readonly="true"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';
				}
				else
				{
					echo '<tr><td align="right">Уровень схемы</td><td>
					<select name="variant">
					<option value="1">Схема первого уровня</option>
					<option value="2">Схема второго уровня</option>
					<option value="3">Схема третьего уровня</option>
					<option value="4">Схема четвертого уровня</option>
					<option value="5">Схема пятого уровня</option>
					</select>
					<table border=1 cellspacing=2>
					<tr><td valign="middle">Схема первого уровня</td><td>требует для использования:<br />навык "оружейник" - 0<br />уровень игрока - 8<br />начальное время на изготовление вещи - 120 мин.</td></tr>
					<tr><td valign="middle">Схема второго уровня</td><td>требует для использования:<br />навык "оружейник" - 55<br />уровень игрока - 12<br />начальное время на изготовление вещи - 180 мин.</td></tr>
					<tr><td valign="middle">Схема третьего уровня</td><td>требует для использования:<br />навык "оружейник" - 85<br />уровень игрока - 16<br />начальное время на изготовление вещи - 240 мин.</td></tr>
					<tr><td valign="middle">Схема четвертого уровня</td><td>требует для использования:<br />навык "оружейник" - 115<br />уровень игрока - 20<br />начальное время на изготовление вещи - 300 мин.</td></tr>
					<tr><td valign="middle">Схема пятого уровня</td><td>требует для использования:<br />навык "оружейник" - 145<br />уровень игрока - 24<br />начальное время на изготовление вещи - 420 мин.</td></tr>
					</table>
					</td></tr>';
					?>
					<script type="text/javascript">
					/* URL to the PHP page called for receiving suggestions for a keyword*/
					var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
					var startSearch = 2;
					</script>
					<?
					echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>Схема для предмета</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script><tr><td align=right>Кол-во создаваемых вещей</td><td><input name="quantity"  type="text" size="5" value="1">';
				}

                if ($tp_new!=23)
                {
				    echo'<tr><td align="right">Только для расы:</td><td><select name="race"><option value=0 selected></option>';
				    $res=myquery("select name,id from game_har where disable=0");
				    while($option=mysql_fetch_array($res))
					    echo "<option value=".$option['id'].">".$option["name"]."</option>";
				    echo'</select></td></tr>
				    <tr><td align="right">&nbsp;</td><td></td></tr>
				    <tr><td align=right>"Личный" предмет</td><td><input name="personal" type="checkbox" value="1"></td></tr>
				    <tr><td align=right>Предмет можно одеть</td><td><input name="can_up" type="checkbox" value="1" ';
				    if ($tp_new!=19 AND $tp_new!=21 AND $tp_new!=20) echo 'checked';
				    echo '></td></tr>
				    <tr><td align=right>У предмета уменьшается долговечность</td><td><input name="breakdown" type="checkbox" value="1"></td></tr>
				    <tr><td align="right">Долговечность предмета</td><td><input name="item_uselife_max" value="100" type="text" size="5"></td></tr>';
                }
                echo ' 
                <tr><td align=right>Количество предметов макс. у 1 игрока (0 - без лимита)</td><td><input name="kol_per_user" value="0" type="text" size="4" maxsize="3"></td></tr>				     
                <tr><td align=right>Режим для энциклопедии</td><td>
				<select name="view">
				<option value="0">Отображать краткую информацию</option>
				<option value="1" selected>Отображать полную информацию</option>
				<option value="2">Не отображать</option>
				</select>
				</td></tr>';
                if ($tp_new!=23)
                {
                    echo '
				    <tr><td align=right>Принадлежность для клана:</td><td>
				    <select name="clan_id">
				    <option value="0" selected>Без привязки к клану</option>';
				    $sel = myquery("SELECT clan_id,nazv FROM game_clans WHERE raz='0' ORDER BY clan_id");
				    while ($cl = mysql_fetch_array($sel))
				    {
					    echo '<option value="'.$cl['clan_id'].'">'.$cl['nazv'].'</option>'; 
				    }
				    echo '</select>
				    </td></tr>
				    <tr><td align="right">&nbsp;</td><td></td></tr>';
                }
                echo '
				<tr><td align="right"><input name="save" type="submit" value="Добавить"></td></tr>
				<input name="save" type="hidden" value="">
				</table>
				</form>';
			}
			else
			{
				echo'<br><br><center><font color=ff0000 size=2 face=verdana><b>Предмет: '.$name.' добавлен<b></font>';
				if (isset($personal) and $personal=='1') $pers=1;
				if (!isset($personal)) $pers=0;

				if (isset($can_up) and $can_up=='1') $can_up1=1;
				if (!isset($can_up)) $can_up1=0;

				if (isset($breakdown) and $breakdown=='1') $break=1;
				if (!isset($breakdown)) $break=0;

				if (isset($in_two_hands) and $in_two_hands=='1') $in_two=1;
				if (!isset($in_two_hands)) $in_two=0;

				if(!isset($indx)) $indx=0;
				if(!isset($deviation)) $deviation=0;
				if(!isset($mode)) $mode='';
				if(!isset($sv)) $sv='';

				if(!isset($item_uselife)) $item_uselife=100;

				if(!isset($hp_p)) $hp_p=0;
				if(!isset($mp_p)) $mp_p=0;
				if(!isset($stm_p)) $stm_p=0;
				if(!isset($cc_p)) $cc_p=0;
				
				$type = $_GET['tp_new'];

				if(!isset($type_weapon)) $type_weapon=0;
				if(!isset($type_weapon_need)) $type_weapon_need=0;
				if(!isset($def_type)) $def_type=0;
				if(!isset($def_index)) $def_index=0;
				if(!isset($magic_def_index)) $magic_def_index=0;
				if(!isset($clan_id)) $clan_id=0;
				
				//if ($type==12) $pers=1;
				
				if(!isset($quantity)) $quantity=0; 
				if(!isset($cooldown)) $cooldown=0; 
                
                if (!isset($kol_per_user)) $kol_per_user=0;

				if(!isset($ostr)) $ostr=0; 
				if(!isset($ontl)) $ontl=0; 
				if(!isset($opie)) $opie=0; 
				if(!isset($ovit)) $ovit=0; 
				if(!isset($odex)) $odex=0; 
				if(!isset($ospd)) $ospd=0; 
				if(!isset($oclevel)) $oclevel=0; 
				if(!isset($dstr)) $dstr=0; 
				if(!isset($dntl)) $dntl=0; 
				if(!isset($dpie)) $dpie=0; 
				if(!isset($dvit)) $dvit=0; 
				if(!isset($ddex)) $ddex=0; 
				if(!isset($dspd)) $dspd=0; 
				if(!isset($sv)) $sv=0; 
				if(!isset($race)) $race=0; 
				if(!isset($hp_p)) $hp_p=0; 
				if(!isset($mp_p)) $mp_p=0; 
				if(!isset($stm_p)) $stm_p=0; 
				if(!isset($cc_p)) $cc_p=0; 
				if(!isset($quantity)) $quantity=0; 
				if(!isset($def_type)) $def_type=0; 
				if(!isset($def_index)) $def_index=0; 
				if(!isset($type_weapon)) $type_weapon=0; 
				if(!isset($type_weapon_need)) $type_weapon_need=0; 
				if(!isset($break)) $break=0; 
				if(!isset($item_uselife_max)) $item_uselife_max=0; 
				if(!isset($magic_def_index)) $magic_def_index=0; 
				if(!isset($in_two)) $in_two=0; 
				if(!isset($olucky)) $olucky=0; 
				if(!isset($dlucky)) $dlucky=0; 
				if(!isset($can_up1)) $can_up1=0; 
				if(!isset($clan_id)) $clan_id=0; 
				if(!isset($variant)) $variant=0; 
				if(!isset($item_name)) $item_name=''; 

				if ($type==20)
				{
					$oclevel = $variant;
					$item_id = 0;
					$selitemid = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
					if (mysql_num_rows($selitemid)>0)
					{
						$item_id=mysqlresult($selitemid,0,0);
					}
					$indx = $item_id;
				}
                
                if (!isset($weight)) $weight = 0;
                if (!isset($item_cost)) $item_cost = 0;
                if (!isset($redkost)) $redkost = 0;
                
				$up=myquery("INSERT INTO game_items_factsheet
				(name,type,indx,deviation,mode,weight,curse,img,item_uselife,item_cost,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p,view,redkost,imgbig,personal,quantity,cooldown,def_type,def_index,type_weapon,type_weapon_need,breakdown,item_uselife_max,magic_def_index,in_two_hands,olucky,dlucky,can_up,clan_id,kol_per_user)
				VALUES ('$name','$type','$indx','$deviation','".mysql_real_escape_string($mode)."','$weight','".mysql_real_escape_string($curse)."','$imgg','$item_uselife','$item_cost','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p','$view','$redkost','$imgbig','$pers','$quantity','$cooldown','$def_type','$def_index','$type_weapon','$type_weapon_need','$break','$item_uselife_max','$magic_def_index','$in_two','$olucky','$dlucky','$can_up1','$clan_id','$kol_per_user')");

				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Добавил новый предмет <b>".$name."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
				echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=items">';

			}
		}
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if(isset($rename) and $adm['items'] == 2)
	{
		$select = myquery("UPDATE game_items_factsheet SET name = '".$name2."' WHERE name='".$name1."'");
		echo'<center><br><br><font color=ff0000><b>Предмет: '.$name1.' переименован в: '.$name2.'!!!</b></font></center><br>';
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Групповое переименование предмета <b>".$name1."</b> в <b>".$name2."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
		 or die(mysql_error());
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	function delete_from_map($name_items,$item_id=0)
	{
		global $char;
		if ($item_id==0) $sel_item_id = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_items."'");
		if ($item_id!=0 OR mysql_num_rows($sel_item_id))
		{
			if ($item_id==0) list($item_id) = mysql_fetch_array($sel_item_id);
			$kol = mysql_result(myquery("SELECT COUNT(*) from game_items WHERE item_id=$item_id AND priznak=2"),0,0);
			if ($kol>0)
			{
				$select = myquery("DELETE from game_items WHERE item_id=$item_id AND priznak=2");
				echo'<center><br><br><font color=ff0000><b>Предмет: '.$name_items.'('.$kol.' шт.) удален с карт!!!</b></font></center><br>';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Удалил с карт предмет <b>".$name_items."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
			}
		}
	}

	if(isset($del_0) and $adm['items'] == 2)
	{
		delete_from_map($name_items);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	function delete_from_market($name_items,$item_id=0)
	{
		global $char;
		if ($item_id==0) $sel_item_id = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_items."'");
		if ($item_id!=0 OR mysql_num_rows($sel_item_id))
		{
			if ($item_id==0) list($item_id) = mysql_fetch_array($sel_item_id);
			$kol = mysql_result(myquery("SELECT COUNT(*) from game_items WHERE item_id=$item_id AND (priznak=1 OR priznak=3)"),0,0);
			if ($kol>0)
			{
				$select = myquery("DELETE from game_items WHERE item_id=$item_id AND (priznak=1 OR priznak=3 OR priznak=4)");
				echo'<center><br><br><font color=ff0000><b>Предмет: '.$name_items.'('.$kol.' шт.) удален с рынков!!!</b></font></center><br>';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Удалил с рынков предмет <b>".$name_items."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
			}
		}
	}

	if(isset($del_1) and $adm['items'] == 2)
	{
		delete_from_market($name_items);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	
	function delete_from_users($name_items,$item_id=0)
	{
		global $char;
		if ($item_id==0) $sel_item_id = myquery("SELECT id FROM game_items_factsheet WHERE name='".$name_items."'");
		if ($item_id!=0 OR mysql_num_rows($sel_item_id))
		{
			if ($item_id==0) list($item_id) = mysql_fetch_array($sel_item_id);
			$kol = mysql_result(myquery("SELECT COUNT(*) from game_items WHERE item_id=$item_id AND priznak=0"),0,0);
			if ($kol>0)
			{
				$sel_user = myquery("SELECT id FROM game_items WHERE item_id=$item_id AND priznak=0");
				while (list($itemsid) = mysql_fetch_array($sel_user))
				{
					$Item = new Item($itemsid);
					$Item->admindelete();
				}
				echo'<center><br><br><font color=ff0000><b>Предмет: '.$name_items.'('.$kol.' шт.) удален у игроков!!!</b></font></center><br>';
				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
				 VALUES (
				 '".$char['name']."',
				 'Удалил у игроков предмет <b>".$name_items."</b>',
				 '".time()."',
				 '".$da['mday']."',
				 '".$da['mon']."',
				 '".$da['year']."')")
				 or die(mysql_error());
			}
		}
	}
	
	if(isset($del_2) and $adm['items'] == 2)
	{
		delete_from_users($name_items);
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if(isset($del_3) and $adm['items'] == 2)
	{
		list($name_items, $type_items) = mysql_fetch_array(myquery("SELECT name, type FROM game_items_factsheet WHERE id=$del"));
                if ($type_items == 23)
		{
			$res = myquery("SELECT `id`,`user_id` FROM `game_items` WHERE `priznak`=0 AND `used`=22 AND `item_id` = $del");
			while ($list = mysql_fetch_array($res))
			{
				$item = new Item($list['id']);
				$item->setChar($list['user_id']);
				$item->down();
			}

			myquery("DELETE FROM `game_items_complect` WHERE `complect_id` = $del;");
		}
		delete_from_map("",$del);
		delete_from_market("",$del);
		delete_from_users("",$del);
		$select = myquery("DELETE from game_items_factsheet where id='".$del."'");
		echo'<center><br><br><font color=ff0000><b>Предмет: '.$name_items.' удален из энциклопедии!!!</b></font></center><br>';
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил из энциклопедии предмет <b>".$name_items."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
		 or die(mysql_error());
		echo '<meta http-equiv="refresh" content="1;url=admin.php?option=items&opt=main">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}
	
	if (isset($delete_items) AND $adm['items'] == 2)
	{
		?>
		<script type="text/javascript">
		/* URL to the PHP page called for receiving suggestions for a keyword*/
		var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
		var startSearch = 3;
		</script><?
		echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
		echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
		echo '<div id="content" onclick="hideSuggestions();"><center>Удаление предметов:</center><br><br>';
		echo '<center><form action="" method="post">
		Имя предмета        <input id="keyword" name="name_items" value="" type="text" size="50" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div><br><br>';
		echo '<input name="del_0" type="submit" value="Удалить выброшенные на землю">';
		echo '<br><br>';
		echo '<input name="del_1" type="submit" value="Удалить с рынков, хранилищ и почты">';
		echo '<br><br>';
		echo '<input name="del_2" type="submit" value="Удалить у игроков">';
		echo '<br><br>';
		echo '<input name="del_3" type="submit" value="Удалить из энциклопедии (автоматически будет удалено из игры полностью)">';
		echo '<br><br>';
		echo '</form></center>';
		echo '</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table></div><script>init();</script>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if (isset($rename_items) AND $adm['items'] == 2)
	{
		?>
		<script type="text/javascript">
		/* URL to the PHP page called for receiving suggestions for a keyword*/
		var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
		var startSearch = 1;
  </script><?
		echo '<link href="suggest/suggest.css" rel="stylesheet" type="text/css">';
		echo '<script type="text/javascript" src="suggest/suggest.js"></script>';
		echo '<div id="content" onclick="hideSuggestions();"><center>Переименование предметов:</center><br><br>';
		echo '<center><form action="" method="post">
		<table border="0" width="100%">
		<tr><td>Имя предмета</td><td><input name="name1" value="" type="text" size="50"  onkeyup="handleKeyUp(event)" id="keyword"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td><td>Заменить на</td><td>';
		echo '<input name="name2" value="" type="text" size="50">';
		echo '</td>';
		echo '<td><input name="rename" type="submit" value="Переименовать"></td>';
		echo '</td></tr>';
		echo '</table></form>';
		echo '</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table></div><script>init();</script>';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	if (isset($clon) AND $adm['items'] == 2)
	{
		$sel = myquery("SELECT * FROM game_items_factsheet WHERE id=$clon");
		$item = mysql_fetch_array($sel);
		$name_items = $item['name'];
		$new_name = '!('.$name_items.')!';
		$new = myquery("
		INSERT INTO game_items_factsheet
			(name,type,indx,deviation,mode,weight,curse,item_uselife,item_cost,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p,personal,view,quantity,cooldown,def_type,def_index,type_weapon,type_weapon_need,in_two_hands,olucky,dlucky,can_up,clan_id)
		VALUES
			('$new_name','".$item['type']."','".$item['indx']."','".$item['deviation']."','".$item['mode']."','".$item['weight']."','".$item['curse']."','".$item['item_uselife']."','".$item['item_cost']."','".$item['ostr']."','".$item['ontl']."','".$item['opie']."','".$item['ovit']."','".$item['odex']."','".$item['ospd']."','".$item['oclevel']."','".$item['dstr']."','".$item['dntl']."','".$item['dpie']."','".$item['dvit']."','".$item['ddex']."','".$item['dspd']."','".$item['sv']."','".$item['race']."','".$item['hp_p']."','".$item['mp_p']."','".$item['stm_p']."','".$item['cc_p']."','".$item['personal']."','".$item['view']."','".$item['quantity']."','".$item['cooldown']."','".$item['def_type']."','".$item['def_index']."','".$item['type_weapon']."','".$item['type_weapon_need']."','".$item['in_two_hands']."','".$item['olucky']."','".$item['dlucky']."','".$item['can_up']."','".$item['clan_id']."')");
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Скопировал предмет:  <b>".$name_items."</b> в предмет <b>".$new_name."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
				 or die(mysql_error());
		echo'<center><br><br><font color=ff0000><b>Предмет: "'.$name_items.'" клонирован в предмет "'.$new_name.'"!!!</b></font></center><br>';
		echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';
		echo '<meta http-equiv="refresh" content="3;url=admin.php?option=items&opt=main&tp='.$item['type'].'">';
		{if (function_exists("save_debug")) save_debug(); exit;}
	}

	//Основной экран
	echo'<CENTER><br /><a href="admin.php?opt=main&option=items&new">Добавить предмет</a>';
	if($adm['items'] == 2)
	{
		echo' | <a href="admin.php?opt=main&option=items&delete_items">Удаление предметов</a>';
		echo' | <a href="admin.php?opt=main&option=items&rename_items">Переименование предметов</a>&nbsp;<br />&nbsp;';
        echo'&nbsp;<br /><hr>
        <br><table cellspacing=2 cellpadding=2 border=0><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=1">Оружие</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=4">Щит</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=5">Доспех</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=3">Артефакт</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=6">Шлем</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=7">Магию</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=2">Кольцо</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=8">Пояс</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=10">Перчатки</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=11">Обувь</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=24">Инструменты</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=14">Поножи</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=15">Наручи</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=16">Украшения</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=18">Луки</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=21">Стрелы</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=19">Метательные предметы</a></td></tr><tr align="center"><td>';
        //<a href="admin.php?opt=main&option=items&tp=17">Маг.книги</a> | 
        echo '<a href="admin.php?opt=main&option=items&tp=13">Эликсиры</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=12">Свитки</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=9">Ожерелья</a> &nbsp;|&nbsp;  
        <a href="admin.php?opt=main&option=items&tp=22">Руны амулета</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=20">Схемы предметов</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=23">Комплекты предметов</a></td></tr><tr align="center"><td>
        <a href="admin.php?opt=main&option=items&tp=666">Остальное</a></td></tr><tr align="center">
        <td><a href="admin.php?opt=main&option=items&tp=0">Все предметы</a></td></tr></table>';
	}

	if (isset($tp))
	{
        $line=40;
		if ($tp==666)
		{
			$items=myquery("select COUNT(*) from game_items_factsheet where type>=90 order by id DESC");
		}
		elseif ($tp==0)
        {
            $items=myquery("select COUNT(*) from game_items_factsheet order by name, id DESC");
            $line=mysql_result($items,0,0)+10;
        }
        else
		{
			$items=myquery("select COUNT(*) from game_items_factsheet where type=$tp order by id DESC");
		}
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$allpage=ceil(mysql_result($items,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		if ($tp==666)
		{
			$items=myquery("select game_items_factsheet.*,game_har.name as race_name from game_items_factsheet left join (game_har) on (game_har.id=game_items_factsheet.race) where game_items_factsheet.type>=90 order by game_items_factsheet.id DESC limit ".(($page-1)*$line).", $line");
		}
		elseif ($tp==0)
        {
            $items=myquery("select game_items_factsheet.*,game_har.name as race_name from game_items_factsheet left join (game_har) on (game_har.id=game_items_factsheet.race) order by game_items_factsheet.name ASC");
        }
        else
		{
			$items=myquery("select game_items_factsheet.*,game_har.name as race_name from game_items_factsheet left join (game_har) on (game_har.id=game_items_factsheet.race) where game_items_factsheet.type=$tp order by game_items_factsheet.id DESC limit ".(($page-1)*$line).", $line");
		}
		echo'<table border=0><tr><td>Название</td><td>Редк</td><td>Урон</td><td>Цена</td><td>Раса</td><td>В игре</td><td colspan="5" align="center">Действие</td></tr>';
		while($item=mysql_fetch_array($items))
		{
			echo'<tr'; if ($item['sv']=='Атака') echo ' bgcolor="#800003"'; if ($item['sv']=='Защита') echo ' bgcolor="#020062"'; if ($item['sv']=='Лечение') echo ' bgcolor="#045500"';echo'><td><font color=#FF8080>'.$item['id'].'</font>. ';
			if ($item['view']=='0') echo '<font color=#FF8080>'.$item['name'].'</font>';
			elseif ($item['view']=='2') echo '<span style="color:#00FF40;text-decoration:line-through;">'.$item['name'].'</span>'; 
			else echo $item['name'];
			//echo'</td><td><font color=#FF80C0>'.$item['redkost'].'</font></td><td>'.$item['indx'].'&plusmn;'.$item['deviation'].'</td><td><font color=#FFFF00>'.$item['item_cost'].'</font></td><td><font color=#FF0000>'.(($item['race']==0) ? "&nbsp;" : $item['race_name']).'<font></td><td>'.mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id'].""),0,0).'</td>';
			if ($tp==3)
			{
				$count_item = mysqlresult(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$item['id'].""),0,0);
			}
			else
			{
				$count_item = mysqlresult(myquery("SELECT SUM(count_item) FROM game_items WHERE item_id=".$item['id']." GROUP BY item_id"),0,0);
			}
			echo'</td><td><font color=#FF80C0>'.$item['redkost'].'</font></td><td>'.$item['indx'].'&plusmn;'.$item['deviation'].'</td><td><font color=#FFFF00>'.$item['item_cost'].'</font></td><td><font color=#FF0000>'.(($item['race']==0) ? "&nbsp;" : $item['race_name']).'<font></td><td>'.$count_item.'</td>';

			if($adm['items'] == 2)
			{
				echo'<td><a href="admin.php?opt=main&option=items&edit='.$item['id'].'">Ред.</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				echo'<td><a href="admin.php?opt=main&option=items&del_3&del='.$item['id'].'">Уд.</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				if ($item['type']!=23) echo'<td><a href="admin.php?opt=main&option=items&add_user='.$item['id'].'">Доб.игроку</a>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				echo'<td><a href="admin.php?opt=main&option=items&clon='.$item['id'].'">Клон.</a></td>';
				if ($item['type']!=23) echo'<td><a href="admin.php?opt=main&option=items&info='.$item['id'].'" target="_blank">Инфо</a></td>';
			}
			echo'</tr>';
		}
		echo'</table>';
		$href = 'admin.php?opt=main&option=items&tp='.$tp.'&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}


	if(isset($add_user) and $adm['items'] == 2)
	{
		if (!isset($see))
		{
			$items=myquery("select name from game_items_factsheet where id=$add_user");
			list($name_item)=mysql_fetch_array($items);
			echo '<div id="content" onclick="hideSuggestions();"><hr><center>Добавление предмета: <font size="2" face="Verdana" color="#ff0000"><b>'.$name_item.'</b></font> для игрока:<br>';
			echo '<form action="admin.php?opt=main&option=items&add_user='.$add_user.'" method="post"><font size="1" face="Verdana" color="#ffffff">Имя игрока: <input name="user_name" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
			<input name="see" type="submit" value="Добавить игроку"></form></div><script>init();</script>';
		}
		else
		{
			$sel = myquery("SELECT user_id FROM game_users WHERE name='$user_name'");
			if (!mysql_num_rows($sel)) $sel = myquery("SELECT user_id FROM game_users_archive WHERE name='$user_name'");
			if (mysql_num_rows($sel)>0)
			{
				list($user_id_add)=mysql_fetch_array($sel);
				$Item = new Item();
				$ar = $Item->add_user($add_user,$user_id_add,0);
				
				if ($ar[0]>0)
				{
					echo '<hr><center>Сделано';
					$da = getdate();
					$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
					 VALUES (
					 '".$char['name']."',
					 'Добавил предмет <b>".$Item->getFact('name')."</b> игроку <b>".$user_name."</b>',
					 '".time()."',
					 '".$da['mday']."',
					 '".$da['mon']."',
					 '".$da['year']."')")
							 or die(mysql_error());
					echo '<meta http-equiv="refresh" content="3;url=admin.php?option=items&opt=main">';
				}
			}
			else
				echo 'Игрок не найден!';
		}
	}

	if(isset($edit) and $adm['items'] == 2)
	{
		$usr=myquery("select * from game_items_factsheet where id=$edit");
		if (mysql_num_rows($usr))
		{
			$it=mysql_fetch_array($usr);
			if (!isset($save))
			{
				echo'
				<script language="JavaScript" type="text/javascript">
				function count1()
				{
					document.form1.need.value=
					Number(document.form1.ostr1.value)+
					Number(document.form1.ontl1.value)+
					Number(document.form1.opie1.value)+
					Number(document.form1.ovit1.value)+
					Number(document.form1.odex1.value)+
					Number(document.form1.ospd1.value)+
					Number(document.form1.olucky1.value);
				}
				function count2()
				{
					document.form1.plus.value=
					Number(document.form1.dstr1.value)+
					Number(document.form1.dntl1.value)+
					Number(document.form1.dpie1.value)+
					Number(document.form1.dvit1.value)+
					Number(document.form1.ddex1.value)+
					Number(document.form1.dspd1.value)+
					Number(document.form1.dlucky1.value)+
					((Number(document.form1.hp_p1.value)+
					  Number(document.form1.mp_p1.value)+
					  Number(document.form1.stm_p1.value)
					 )/10
					);
				}
				</script>
				<body OnLoad="count1();count2();">
				<center><form action="" name="form1" method="post">
				<table border="0" width="100%">
				<tr><td align="right">Название:</td><td><input name="name1" value="'.$it['name'].'" type="text" size="40"></td></tr>';

                if ($it['type']!=23)
                {
				    if($it['type']==1 OR $it['type']==19 OR $it['type']==21) echo'<tr><td align="right">Урон:</td><td><input name="indx1" value="'.$it['indx'].'" type="text" size="5">&plusmn;<input name="deviation1" value="'.$it['deviation'].'" type="text" size="3"></td></tr>';
				    if($it['type']==3) echo'<tr><td align="right">Кол-во зарядов:</td><td><input name="item_uselife1" value="'.$it['item_uselife'].'" type="text" size="5"></td></tr>';

				    if($it['type']==4) echo'<tr><td align="right">Защита:</td><td><input name="indx1" value="'.$it['indx'].'" type="text" size="5"></td></tr>';

				    if($it['type']==3)
				    {
					    echo '<tr><td align="right">Действие артефакта</td><td><select name="sv1"><option'; if ($it['sv']=='Атака') echo ' selected'; echo'>Атака</option><option'; if ($it['sv']=='Защита') echo ' selected'; echo'>Защита</option><option'; if ($it['sv']=='Лечение') echo ' selected'; echo'>Лечение</option></select></td></tr>';
					    echo '<tr><td align="right">Урон, защита или лечение:</td><td><input name="indx1" value="'.$it['indx'].'" type="text" size="5">&plusmn;<input name="deviation1" value="'.$it['deviation'].'" type="text" size="3"></td></tr>';
				    }
				    if($it['type']==1 or $it['type']==3 or $it['type']==4 or $it['type']==18 or $it['type']==19 or $it['type']==21) echo'<tr><td align="right">Чем ударил</td><td><input name="mode1" value="'.stripslashes($it['mode']).'" type="text" size="40"></td></tr>';
				    echo'<tr><td align="right">Вес:</td><td><input name="weight1" value="'.$it['weight'].'" type="text" size="4"></td></tr>
				    <tr><td align="right">Редкость:</td><td><input name="redkost" value="'.$it['redkost'].'" type="text" size="4" maxsize="2"></td></tr>';
                }
				echo '<tr><td align="right">Описание предмета: (если нужно)</td><td><textarea name="curse1" cols="40" rows="15">'.stripslashes($it['curse']).'</textarea></td></tr>';
				echo'<tr><td align="right">Адрес рисунка:</td><td><input name="imgg1" value="'.$it['img'].'" type="text" size="40">&nbsp;&nbsp;<img src="http://'.IMG_DOMAIN.'/item/'.$it['img'].'.gif" align="middle"></td></tr>';
				echo'<tr><td align="right">Адрес БОЛЬШОГО рисунка:</td><td><input name="imgbig1" value="'.$it['imgbig'].'" type="text" size="40"></td></tr>';
                if ($it['type']!=23)
                {
				    echo'<tr><td align="right">&nbsp;</td><td></td></tr>
				    <tr><td align="right">Базовая цена:</td><td><input name="item_cost1" value="'.$it['item_cost'].'" type="text" size="8"> монет (без наценок торговцев)</td></tr>';
				    if ($it['type']==3)
				    {
					    echo'<tr><td align="right">Стоимость зарядки:</td><td><input name="quantity1" value="'.$it['quantity'].'" type="text" size="8">% от базовой цены</td></tr>';             
					    echo'<tr><td align="right">Время между зарядками</td><td><input name="cooldown1" value="'.$it['cooldown'].'" type="text" size="8"> секунд</td></tr>';
				    }
				    if($it['type']==1 OR $it['type']==18 OR $it['type']==19)
				    {
					    echo'<tr><td align=right>Класс оружия:</td><td><select name="type_weapon1"><option value="0"'; if($it['type_weapon']==0) {echo' selected ';}; echo'>Без класса</option>';
					    echo'<option value="1"'; if($it['type_weapon']==1) {echo' selected ';}; echo'>Кулачное</option>';
					    echo'<option value="2"'; if($it['type_weapon']==2) {echo' selected ';}; echo'>Стрелковое</option>';
					    echo'<option value="3"'; if($it['type_weapon']==3) {echo' selected ';}; echo'>Рубящее</option>';
					    echo'<option value="4"'; if($it['type_weapon']==4) {echo' selected ';}; echo'>Дробящее</option>';
					    echo'<option value="5"'; if($it['type_weapon']==5) {echo' selected ';}; echo'>Колющее</option>';
					    echo'<option value="6"'; if($it['type_weapon']==6) {echo' selected ';}; echo'>Метательное</option>';
					    echo'</select></td></tr>';
					    echo '<tr><td align=right>Требует уровень навыка:</td><td><input name="type_weapon_need1" value="'.$it['type_weapon_need'].'" type="text" size="5"></td></tr>
					    <tr><td align=right>Двуручное оружие</td><td><input name="in_two_hands" type="checkbox" value="1"'; if($it['in_two_hands']==1) echo' checked'; echo'></td></tr>';
				    }
                }
				
				if ($it['type']==21)
				{
					?>
					<script type="text/javascript">
					/* URL to the PHP page called for receiving suggestions for a keyword*/
					var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
					var startSearch = 2;
					</script>
					<?
					$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$it['quantity'].""),0,0);
					echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>Используется с предметом</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value="'.$item_name.'"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script>';
				}
				
				if ($it['type']!=20 and $it['type']!=23)
				{
					echo '<tr><td align="right">&nbsp;</td><td></td></tr>

					<tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>Требует характеристики:</b></font></td><td>
					<tr><td align="right">Уровень: </td><td><input name="oclevel1" value="'.$it['oclevel'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">Сила: </td><td><input name="ostr1" value="'.$it['ostr'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">Интеллект: </td><td><input name="ontl1" value="'.$it['ontl'].'" type="text" size="3" onChange="count1()"></td></tr><br>
					<tr><td align="right">Ловкость: </td><td><input name="opie1" value="'.$it['opie'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">Защита: </td><td><input name="ovit1" value="'.$it['ovit'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">Выносливость: </td><td><input name="odex1" value="'.$it['odex'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">Мудрость: </td><td><input name="ospd1" value="'.$it['ospd'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">Удача: </td><td><input name="olucky1" value="'.$it['olucky'].'" type="text" size="3" onChange="count1()"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>
					<tr><td align="right">Сумма треб.характеристик</td><td><input name="need" value="" type="text" size="4" readonly="true"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';
                }
                
                if ($it['type']!=20)
                {
					echo '<tr><td colspan="2" align="center" valign=top><font color="gold" size="2"><b>Поднимает характеристики:</b></font></td><td>
					<tr><td align="right">Сила: </td><td><input name="dstr1" value="'.$it['dstr'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Интеллект: </td><td><input name="dntl1" value="'.$it['dntl'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Ловкость: </td><td><input name="dpie1" value="'.$it['dpie'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Защита: </td><td><input name="dvit1" value="'.$it['dvit'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Выносливость: </td><td><input name="ddex1" value="'.$it['ddex'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Мудрость: </td><td><input name="dspd1" value="'.$it['dspd'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">Удача: </td><td><input name="dlucky1" value="'.$it['dlucky'].'" type="text" size="3" onChange="count2()"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';

					echo'<tr bgcolor=000000><td align="right">Поднятие жизней маны и энергии:</td>
					<td>
					<table border=0>
					<tr><td><input name="hp_p1" value="'.$it['hp_p'].'" type="text" size="3" onChange="count2()"></td><td>Жизнь</td></tr>
					<tr><td><input name="mp_p1" value="'.$it['mp_p'].'" type="text" size="3" onChange="count2()"><td>Мана</td></td></tr>
					<tr><td><input name="stm_p1" value="'.$it['stm_p'].'" type="text" size="3" onChange="count2()"></td><td>Энергия</td></tr>
					</table>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>
					<tr bgcolor=000000><td align="right">Повышение переноса вещей:</td>
					<td>
					На: <input name="cc_p1" value="'.$it['cc_p'].'" type="text" size="2"><br>
					</td>
					</tr><tr><td align="right">&nbsp;</td></tr>';
                

					if($it['type']==2 OR $it['type']==5 OR $it['type']==6 OR $it['type']==8)
					{
						echo'<tr bgcolor=000000><td align="right" width="50%">Вид брони<br /><br />(кольцо защищает плечо, доспех защищает ноги и тело, шлем защищает голову, пояс защищает пах)</td>
						<td>
						&nbsp;<input name="def_type1" value="0" type="radio"'; if ($it['def_type']==0) echo ' checked'; echo'>  одежда<br>
						&nbsp;<input name="def_type1" value="1" type="radio"'; if ($it['def_type']==1) echo ' checked'; echo'>  кожанная<br>
						&nbsp;<input name="def_type1" value="2" type="radio"'; if ($it['def_type']==2) echo ' checked'; echo'>  кольчужная (плетеная)<br>
						&nbsp;<input name="def_type1" value="3" type="radio"'; if ($it['def_type']==3) echo ' checked'; echo'>  латы (пластинчатая)<br>
						<br>
						Значение "физической" защиты: <input name="def_index1" value="'.$it['def_index'].'" type="text" size="4" maxsize="3"><br />
						Значение "магической" защиты: <input name="magic_def_index1" value="'.$it['magic_def_index'].'" type="text" size="4" maxsize="3">
						</td>
						</tr>';
					}

					echo'<tr><td align="right">Сумма повыш.характеристик</td><td><input name="plus" value="" type="text" size="4" readonly="true"></td></tr>
					<tr><td align="right">&nbsp;</td><td></td></tr>';
                    if ($it['type']==23)
                    {
                        ?>
                        <script type="text/javascript">
                        /* URL to the PHP page called for receiving suggestions for a keyword*/
                        var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
                        var startSearch = 2;
                        </script>
                        <?
                        
                        //echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>Добавить предмет в комплект?</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value="'.$item_name.'"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script><tr><td align=right>Кол-во создаваемых вещей</td><td><input name="quantity1"  type="text" size="5" value="'.$it['quantity'].'">';
                        echo '
                        <script type="text/javascript">
                        function sh_hd_schema()
                        {
                            el = document.getElementById("schema");
                            if (el.style.display=="none")
                            {
                                el.style.display="block";
                            }
                            else
                            {
                                el.style.display="none";
                            }
                        }
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
                        function refresh_schema()
                        {
                            if(AjaxRequest)
                            {
                                try
                                {
                                    if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
                                    {
                                        URL = "./ajax/admin/item_complect.php?read='.$it['id'].'";
                                        AjaxRequest.open("GET", URL, true);
                                        AjaxRequest.onreadystatechange = process_refresh;
                                        AjaxRequest.send(null);
                                    }
                                }
                                catch(e)
                                {
                                }
                            }
                            else
                            {
                                AjaxRequest = createXmlHttpRequestObject();
                                setTimeout("refresh_schema();", 50);
                            }
                        }
                        function process_refresh()
                        {
                            try
                            {
                                if (AjaxRequest.readyState == 4)
                                {
                                    if (AjaxRequest.status == 200)
                                    {
                                        try
                                        {
                                             el = document.getElementById("schema");
                                             el.innerHTML = AjaxRequest.responseText;
                                        }
                                        catch(e)
                                        {
                                        }
                                    }
                                    else
                                    {
                                    }
                                }
                            }
                            catch(e)
                            {
                            }
                        }
                        
                        function save_schema(ids)
                        {
                            if(AjaxRequestWork)
                            {
                                try
                                {
                                    if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
                                    {
                                        if (ids==\'new\')
                                        {
                                            item = document.getElementById("item_id").value;
                                        }
                                        else
                                        {
                                            item = document.getElementById("item_id_"+ids).value;
                                        }
                                        URL = "./ajax/admin/item_complect.php?read='.$it['id'].'&save="+item;
                                        AjaxRequestWork.open("GET", URL, true);
                                        AjaxRequestWork.onreadystatechange = process_save_delete;
                                        AjaxRequestWork.send(null);
                                    }
                                }
                                catch(e)
                                {
                                }
                            }
                            else
                            {
                                AjaxRequestWork = createXmlHttpRequestObject();
                            }
                        }
                        function delete_schema(ids)
                        {
                            if(AjaxRequestWork)
                            {
                                try
                                {
                                    if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
                                    {
                                        URL = "./ajax/admin/item_complect.php?read='.$it['id'].'&delete="+ids;
                                        AjaxRequestWork.open("GET", URL, true);
                                        AjaxRequestWork.onreadystatechange = process_save_delete;
                                        AjaxRequestWork.send(null);
                                    }
                                }
                                catch(e)
                                {
                                }
                            }
                            else
                            {
                                AjaxRequestWork = createXmlHttpRequestObject();
                            }
                        }
                        function process_save_delete()
                        {
                            try
                            {
                                if (AjaxRequestWork.readyState == 4)
                                {
                                    if (AjaxRequestWork.status == 200)
                                    {
                                        try
                                        {
                                             if (AjaxRequestWork.responseText!=\'ok\')
                                             {
                                                //alert(AjaxRequestWork.responseText)
                                             }
                                        }
                                        catch(e)
                                        {
                                        }
                                    }
                                    else
                                    {
                                    }
                                }
                            }
                            catch(e)
                            {
                            }
                            refresh_schema();
                        }

                        var AjaxRequest = createXmlHttpRequestObject();
                        var AjaxRequestWork = createXmlHttpRequestObject();
                        refresh_schema();
                        </script>
                        <tr style="height:50px;"><td style="border:2px gold groove;" colspan=2 align="center"><span style="color:red;font-weight:900;" onClick="sh_hd_schema()";>Создать/Редактировать состав комплекта предметов</span><div id="schema" style="display:none;">
                        </div></td></tr>';
                            
                    }
				}
				else
				{
					echo '<tr><td align="right">Уровень схемы</td><td>
					<select name="variant">
					<option value="1"'; if ($it['oclevel']==1) echo ' selected'; echo'>Схема первого уровня</option>
					<option value="2"'; if ($it['oclevel']==2) echo ' selected'; echo'>Схема второго уровня</option>
					<option value="3"'; if ($it['oclevel']==3) echo ' selected'; echo'>Схема третьего уровня</option>
					<option value="4"'; if ($it['oclevel']==4) echo ' selected'; echo'>Схема четвертого уровня</option>
					<option value="5"'; if ($it['oclevel']==5) echo ' selected'; echo'>Схема пятого уровня</option>
					</select>
					<table border=1 cellspacing=2>
					<tr><td valign="middle">Схема первого уровня</td><td>требует для использования:<br />навык "оружейник" - 0<br />уровень игрока - 8<br />начальное время на изготовление вещи - 120 мин.</td></tr>
					<tr><td valign="middle">Схема второго уровня</td><td>требует для использования:<br />навык "оружейник" - 55<br />уровень игрока - 12<br />начальное время на изготовление вещи - 180 мин.</td></tr>
					<tr><td valign="middle">Схема третьего уровня</td><td>требует для использования:<br />навык "оружейник" - 85<br />уровень игрока - 15<br />начальное время на изготовление вещи - 240 мин.</td></tr>
					<tr><td valign="middle">Схема четвертого уровня</td><td>требует для использования:<br />навык "оружейник" - 115<br />уровень игрока - 19<br />начальное время на изготовление вещи - 300 мин.</td></tr>
					<tr><td valign="middle">Схема пятого уровня</td><td>требует для использования:<br />навык "оружейник" - 145<br />уровень игрока - 22<br />начальное время на изготовление вещи - 420 мин.</td></tr>
					</table>
					</td></tr>';
					?>
					<script type="text/javascript">
					/* URL to the PHP page called for receiving suggestions for a keyword*/
					var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
					var startSearch = 2;
					</script>
					<?
					$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$it['indx'].""),0,0);
					echo '<div id="content" onclick="hideSuggestions();"><tr><td align=right>Схема для предмета</td><td><input name="item_name" id="keyword" type="text" size="50" onkeyup="handleKeyUp(event)" value="'.$item_name.'"><div style="display:none;" id="scroll"><div id="suggest"></div></div></td></tr></div><script>init();</script><tr><td align=right>Кол-во создаваемых вещей</td><td><input name="quantity1"  type="text" size="5" value="'.$it['quantity'].'">';
					echo '
					<script type="text/javascript">
					function sh_hd_schema()
					{
						el = document.getElementById("schema");
						if (el.style.display=="none")
						{
							el.style.display="block";
						}
						else
						{
							el.style.display="none";
						}
					}
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
					function refresh_schema()
					{
						if(AjaxRequest)
						{
							try
							{
								if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
								{
									URL = "./ajax/admin/item_schema.php?read='.$it['id'].'";
									AjaxRequest.open("GET", URL, true);
									AjaxRequest.onreadystatechange = process_refresh;
									AjaxRequest.send(null);
								}
							}
							catch(e)
							{
							}
						}
						else
						{
							AjaxRequest = createXmlHttpRequestObject();
							setTimeout("refresh_schema();", 50);
						}
					}
					function process_refresh()
					{
						try
						{
							if (AjaxRequest.readyState == 4)
							{
								if (AjaxRequest.status == 200)
								{
									try
									{
										 el = document.getElementById("schema");
										 el.innerHTML = AjaxRequest.responseText;
									}
									catch(e)
									{
									}
								}
								else
								{
								}
							}
						}
						catch(e)
						{
						}
					}
					
					function save_schema(ids)
					{
						if(AjaxRequestWork)
						{
							try
							{
								if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
								{
									if (ids==\'new\')
									{
										res = document.getElementById("res_id").value;
										col = document.getElementById("new_col").value;
									}
									else
									{
										res = document.getElementById("res_id_"+ids).value;
										col = document.getElementById("col_"+ids).value;
									}
									URL = "./ajax/admin/item_schema.php?read='.$it['id'].'&save="+res+"&col="+col;
									AjaxRequestWork.open("GET", URL, true);
									AjaxRequestWork.onreadystatechange = process_save_delete;
									AjaxRequestWork.send(null);
								}
							}
							catch(e)
							{
							}
						}
						else
						{
							AjaxRequestWork = createXmlHttpRequestObject();
						}
					}
					function delete_schema(ids)
					{
						if(AjaxRequestWork)
						{
							try
							{
								if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
								{
									URL = "./ajax/admin/item_schema.php?read='.$it['id'].'&delete="+ids;
									AjaxRequestWork.open("GET", URL, true);
									AjaxRequestWork.onreadystatechange = process_save_delete;
									AjaxRequestWork.send(null);
								}
							}
							catch(e)
							{
							}
						}
						else
						{
							AjaxRequestWork = createXmlHttpRequestObject();
						}
					}
					function process_save_delete()
					{
						try
						{
							if (AjaxRequestWork.readyState == 4)
							{
								if (AjaxRequestWork.status == 200)
								{
									try
									{
										 if (AjaxRequestWork.responseText!=\'ok\')
										 {
											//alert(AjaxRequestWork.responseText)
										 }
									}
									catch(e)
									{
									}
								}
								else
								{
								}
							}
						}
						catch(e)
						{
						}
						refresh_schema();
					}

					var AjaxRequest = createXmlHttpRequestObject();
					var AjaxRequestWork = createXmlHttpRequestObject();
					refresh_schema();
					</script>
					<tr style="height:50px;"><td style="border:2px gold groove;" colspan=2 align="center"><span style="color:red;font-weight:900;" onClick="sh_hd_schema()";>Создать/Редактировать схему создания предмета</span><div id="schema" style="display:none;">
					</div></td></tr>';
				}
				
				echo'<tr><td align="right">Только для расы:</td><td><select name="race1"><option value=0></option>';
				$res=myquery("select id,name from game_har where disable=0");
				while($option=mysql_fetch_array($res))
				{
					echo "<option value=".$option['id']."";
					if ($it['race']==$option["id"]) echo ' selected';
					echo ">".$option["name"]."</option>";
				}
				echo'</select></td></tr>';
				
				echo'<tr><td align="right">Торговцы:</td><td><select name="torg[]" size=10 multiple><option value="0">Не продается</option>';
				$sel3=myquery("select game_shop.*,game_maps.name AS map_name from game_shop,game_maps WHERE game_maps.id=game_shop.map order by binary game_maps.name,game_shop.pos_x,game_shop.pos_y");
				while ($shop=mysql_fetch_array($sel3))
				{
					$f = 1;
					switch($it['type'])
					{
						case 6: { if ($shop['shlem']!='1') $f=0;}; break;
						case 1: { if ($shop['oruj']!='1') $f=0;}; break;
						case 5: { if ($shop['dosp']!='1') $f=0;}; break;
						case 4: { if ($shop['shit']!='1') $f=0;}; break;
						case 8: { if ($shop['pojas']!='1') $f=0;}; break;
						case 7: { if ($shop['mag']!='1') $f=0;}; break;
						case 2: { if ($shop['ring']!='1') $f=0;}; break;
						case 3: { if ($shop['artef']!='1') $f=0;}; break;
						case 12: { if ($shop['svitki']!='1') $f=0;}; break;
						case 13: { if ($shop['eliksir']!='1') $f=0;}; break;
						case 20: { if ($shop['schema']!='1') $f=0;}; break;
						case 18: { if ($shop['luk']!='1') $f=0;}; break;
						case 24: { if ($shop['instrument']!='1') $f=0;}; break;
						case 97: { if ($shop['others']!='1') $f=0;}; break;
						default: {$f=0;}; break;
					}
					if ($f==1)
					{
						echo '<option value='.$shop['id'];
						$shop_name = $shop['name'].'  -  '.$shop['map_name'].' '.$shop['pos_x'].' '.$shop['pos_y'];
						$check = myquery("SELECT * FROM game_shop_items WHERE shop_id='".$shop['id']."' AND items_id='$edit'");
						if (mysql_num_rows($check)) echo ' selected';
						/*
						if ($shop['shlem']=='1') $shop_name.=' | Шлемы';
						if ($shop['oruj']=='1') $shop_name.=' | Оружие';
						if ($shop['dosp']=='1') $shop_name.=' | Доспехи';
						if ($shop['shit']=='1') $shop_name.=' | Щиты';
						if ($shop['pojas']=='1') $shop_name.=' | Пояса';
						if ($shop['mag']=='1') $shop_name.=' | Магия';
						if ($shop['ring']=='1') $shop_name.=' | Кольца';
						if ($shop['artef']=='1') $shop_name.=' | Артефакты';
						if ($shop['svitki']=='1') $shop_name.=' | Свитки';
						if ($shop['eliksir']=='1') $shop_name.=' | Эликсиры"';
						if ($shop['schema']=='1') $shop_name.=' | Схемы предметов';
						if ($shop['luk']=='1') $shop_name.=' | Луки';
						*/
						echo '>'.$shop_name.'</option>';
					}
				}
				echo'</select></td></tr>';

				$sell=mysql_result(myquery("select count(*) from game_items where item_id='".$it['id']."' AND priznak=0"),0,0);

				echo'<tr><td colspan=2><font color=ff0000><b>Предмет будет автоматически изменен у всех! (Находится у '.$sell.' игроков!!!)</b></font></td></tr>
				<tr><td align=right>"Личный" предмет</td><td><input name="personal" type="checkbox" value="1"'; if($it['personal']==1) echo' checked'; echo'></td></tr>
				<tr><td align=right>Предмет можно одеть</td><td><input name="can_up" type="checkbox" value="1"'; if($it['can_up']==1) echo' checked'; echo'></td></tr>
				<tr><td align=right>У предмета уменьшается долговечность</td><td><input name="breakdown" type="checkbox" value="1"'; if($it['breakdown']==1) echo' checked'; echo'></td></tr>
				<tr><td align="right">Долговечность предмета</td><td><input name="item_uselife_max" value="'.$it['item_uselife_max'].'" type="text" size="5" maxsize="5"></td></tr>
                <tr><td align=right>Количество предметов макс. у 1 игрока (0 - без лимита)</td><td><input name="kol_per_user1" value="'.$it['kol_per_user'].'" type="text" size="4" maxsize="3"></td></tr>                     
				<tr><td align=right>Режим для энциклопедии</td><td>
				<select name="view">
				<option value="0"'; if ($it['view']=='0') echo ' selected'; echo '>Отображать краткую информацию</option>
				<option value="1"'; if ($it['view']=='1') echo ' selected'; echo '>Отображать полную информацию</option>
				<option value="2"'; if ($it['view']=='2') echo ' selected'; echo '>Не отображать</option>
				</select>
				</td></tr>
				<tr><td align=right>Принадлежность для клана:</td><td>
				<select name="clan_id1">
				<option value="0"'; if ($it['clan_id']==0) echo ' selected'; echo '>Без привязки к клану</option>';
				$sel = myquery("SELECT clan_id,nazv FROM game_clans WHERE raz='0' ORDER BY clan_id");
				while ($cl = mysql_fetch_array($sel))
				{
					echo '<option value="'.$cl['clan_id'].'"'; if ($it['clan_id']==$cl['clan_id']) echo ' selected'; echo '>'.$cl['nazv'].'</option>'; 
				}
				echo '</select>
				</td></tr>
				<tr><td align="right">&nbsp;</td><td></td></tr>
				<input name="edit" type="hidden" value="'.$edit.'">
				<tr><td align="right"><input name="save" type="submit" value="Сохранить"></td>';
				echo'</tr>        <input name="save" type="hidden" value="">
				</table>
				</form>';
				echo '          <a href="?opt=main&option=search&item_name='.$it['name'].'">Задать поиск по предмету</a>';
			}
			else
			{
				$cur_item = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=$edit"));
				echo'<br><br><center>Предмет: <font color=ff0000 size=2 face=verdana><b>'.$cur_item['name'].'<b></font> изменен!';
				$log=myquery("INSERT INTO game_log_adm (adm,cur_time,dei) VALUES ('".$char['name']."','".date("j.m.Y H:i")."','Изменил предмет:  ".$cur_item['name']." (новое название - ".$name1."')");
				if (isset($personal) and $personal=='1') $pers='1';
				if (!isset($personal)) $pers='0';
				if (isset($can_up) and $can_up=='1') $can_up1='1';
				if (!isset($can_up)) $can_up1='0';
				if (isset($breakdown) and $breakdown=='1') $break='1';
				if (!isset($breakdown)) $break='0';
				if (isset($in_two_hands) and $in_two_hands=='1') $in_two='1';
				if (!isset($in_two_hands)) $in_two='0';
				if(!isset($indx1)) $indx1='0';
				if(!isset($deviation1)) $deviation1='0';
				if(!isset($mode1)) $mode1='';
				if(!isset($sv1)) $sv1='';

				if(!isset($item_uselife1)) $item_uselife1=100;

				if(!isset($hp_p1)) $hp_p1=0;
				if(!isset($mp_p1)) $mp_p1=0;
				if(!isset($stm_p1)) $stm_p1=0;
				if(!isset($cc_p1)) $cc_p1=0;
                
                if (!isset($kol_per_user1)) $kol_per_user1=0;
				
				if(!isset($ostr1)) $ostr1=0;
				if(!isset($ontl1)) $ontl1=0;
				if(!isset($opie1)) $opie1=0;
				if(!isset($ovit1)) $ovit1=0;
				if(!isset($odex1)) $odex1=0;
				if(!isset($ospd1)) $ospd1=0;
				if(!isset($olucky1)) $olucky1=0;
				if(!isset($oclevel1)) $oclevel1=0;
				if(!isset($dstr1)) $dstr1=0;
				if(!isset($dntl1)) $dntl1=0;
				if(!isset($dpie1)) $dpie1=0;
				if(!isset($dvit1)) $dvit1=0;
				if(!isset($ddex1)) $ddex1=0;
				if(!isset($dspd1)) $dspd1=0;
				if(!isset($dlucky1)) $dlucky1=0;
				if(!isset($sv1)) $sv1=0;
				if(!isset($race1)) $race1=0;
				if(!isset($hp_p1)) $hp_p1=0;
				if(!isset($mp_p1)) $mp_p1=0;
				if(!isset($stm_p1)) $stm_p1=0;
				if(!isset($cc_p1)) $cc_p1=0;
				if(!isset($type_weapon1)) $type_weapon1=0;
				if(!isset($type_weapon_need1)) $type_weapon_need1=0;
				if(!isset($def_type1)) $def_type1=0;
				if(!isset($def_index1)) $def_index1=0;
				if(!isset($magic_def_index1)) $magic_def_index1=0;
				if(!isset($item_uselife_max)) $item_uselife_max=0;
				if(!isset($in_two)) $in_two=0;
				if(!isset($quantity1)) $quantity1=0;
				if(!isset($cooldown1)) $cooldown1=0;
                if(!isset($weight1)) $weight1=0;
                if(!isset($item_cost1)) $item_cost1=0;
                if(!isset($redkost)) $redkost="";

				if ($it['type']==20)
				{
					$oclevel1 = $variant;
					$item_id = 0;
					$selitemid = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
					if (mysql_num_rows($selitemid)>0)
					{
						$item_id=mysqlresult($selitemid,0,0);
					}
					$indx1 = $item_id;
				}
				
				if ($it['type']==21)
				{
					$item_id = 0;
					$selitemid = myquery("SELECT id FROM game_items_factsheet WHERE name='".$item_name."'");
					if (mysql_num_rows($selitemid)>0)
					{
						$item_id=mysqlresult($selitemid,0,0);
					}
					$quantity1 = $item_id;
				}
				
				if(!isset($type_weapon1)) $type_weapon1=0;
				if(!isset($type_weapon_need1)) $type_weapon_need1=0;
				if(!isset($def_index1)) $def_index1=0;
				if(!isset($magic_def_index1)) $magic_def_index1=0;
				if(!isset($def_type1)) $def_type1=0;
				if(!isset($clan_id1)) $clan_id1=0;
				
				$update=myquery("update game_items_factsheet set
				name='$name1',
				indx='$indx1',
				deviation='$deviation1',
				mode='".mysql_real_escape_string($mode1)."',
				weight='$weight1',
				curse='".mysql_real_escape_string($curse1)."',
				img='$imgg1',
				item_uselife='$item_uselife1',
				item_cost='$item_cost1',
				ostr='$ostr1',
				ontl='$ontl1',
				opie='$opie1',
				ovit='$ovit1',
				odex='$odex1',
				ospd='$ospd1',
				olucky='$olucky1',
				oclevel='$oclevel1',
				dstr='$dstr1',
				dntl='$dntl1',
				dpie='$dpie1',
				dvit='$dvit1',
				ddex='$ddex1',
				dspd='$dspd1',
				dlucky='$dlucky1',
				sv='$sv1',
				race='$race1',
				view='$view',
				redkost='$redkost',
				hp_p='$hp_p1',
				mp_p='$mp_p1',
				stm_p='$stm_p1',
				cc_p='$cc_p1',
				personal='$pers',
				quantity='$quantity1',
				cooldown='$cooldown1',
				type_weapon='$type_weapon1',
				type_weapon_need='$type_weapon_need1',
				def_type='$def_type1',
				def_index='$def_index1',
				magic_def_index='$magic_def_index1',
				breakdown='$break',
				item_uselife_max='$item_uselife_max',
				in_two_hands='$in_two',
				can_up='$can_up1',
				clan_id='$clan_id1',
                kol_per_user='$kol_per_user1',
				imgbig='$imgbig1' where id=$edit");

				$da = getdate();
				$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			   VALUES (
			   '".$char['name']."',
			   'Обновил в энциклопедии предмет <b>".$name1."</b> (старое название - ".$cur_item['name'].")',
			   '".time()."',
			   '".$da['mday']."',
			   '".$da['mon']."',
			   '".$da['year']."')")
					   or die(mysql_error());
				myquery("DELETE FROM game_shop_items WHERE items_id=$edit");
				if (isset($_REQUEST["torg"]))
				{
					for ($i=0; $i<count($_REQUEST["torg"]); $i++)
					{
						if ($_REQUEST["torg"][$i]>0)
							myquery("INSERT INTO game_shop_items (shop_id,items_id) VALUES (".$_REQUEST["torg"][$i].",$edit)");
					}
				}

				//обновим макс.предел прочности предмета
				$delta_item_uselife_max = $cur_item['item_uselife_max']-$item_uselife_max;
				myquery("UPDATE game_items SET item_uselife_max=GREATEST(1,item_uselife_max-$delta_item_uselife_max) WHERE item_id=$edit");

				//проверим одетые веши и при необходимости изменим характеристики игрока
				$sel_item_on_user = myquery("SELECT user_id FROM game_items WHERE user_id>0 AND used<>0 AND item_id=$edit AND priznak=0");
				while ($item_user = mysql_fetch_array($sel_item_on_user))
				{
					if ($cur_item['weight']!=$weight1)
					{
						$razn = $weight1-$cur_item['weight'];
						myquery("UPDATE game_users SET CW=(CW + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET CW=(CW + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dstr']!=$dstr1)
					{
						$razn = $dstr1-$cur_item['dstr'];
						myquery("UPDATE game_users SET STR=(STR + $razn),STR_MAX=(STR_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET STR=(STR + $razn),STR_MAX=(STR_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dntl']!=$dntl1)
					{
						$razn = $dntl1-$cur_item['dntl'];
						myquery("UPDATE game_users SET NTL=(NTL + $razn),NTL_MAX=(NTL_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET NTL=(NTL + $razn),NTL_MAX=(NTL_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dpie']!=$dpie1)
					{
						$razn = $dpie1-$cur_item['dpie'];
						myquery("UPDATE game_users SET PIE=(PIE + $razn),PIE_MAX=(PIE_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET PIE=(PIE + $razn),PIE_MAX=(PIE_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dvit']!=$dvit1)
					{
						$razn = $dvit1-$cur_item['dvit'];
						myquery("UPDATE game_users SET VIT=(VIT + $razn),VIT_MAX=(VIT_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET VIT=(VIT + $razn),VIT_MAX=(VIT_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['ddex']!=$ddex1)
					{
						$razn = $ddex1-$cur_item['ddex'];
						myquery("UPDATE game_users SET DEX=(DEX + $razn),DEX_MAX=(DEX_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET DEX=(DEX + $razn),DEX_MAX=(DEX_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dspd']!=$dspd1)
					{
						$razn = $dspd1-$cur_item['dspd'];
						myquery("UPDATE game_users SET SPD=(SPD + $razn),SPD_MAX=(SPD_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET SPD=(SPD + $razn),SPD_MAX=(SPD_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['dlucky']!=$dlucky1)
					{
						$razn = $dlucky1-$cur_item['dlucky'];
						myquery("UPDATE game_users SET lucky=(lucky + $razn),lucky_max=(lucky_max + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET lucky=(lucky + $razn),lucky_max=(lucky_max + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['hp_p']!=$hp_p1)
					{
						$razn = $hp_p1-$cur_item['hp_p'];
						myquery("UPDATE game_users SET HP=(HP + $razn),HP_MAX=(HP_MAX + $razn),HP_MAXX=(HP_MAXX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET HP=(HP + $razn),HP_MAX=(HP_MAX + $razn),HP_MAXX=(HP_MAXX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['mp_p']!=$mp_p1)
					{
						$razn = $mp_p1-$cur_item['mp_p'];
						myquery("UPDATE game_users SET MP=(MP + $razn),MP_MAX=(MP_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET MP=(MP + $razn),MP_MAX=(MP_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['stm_p']!=$stm_p1)
					{
						$razn = $stm_p1-$cur_item['stm_p'];
						myquery("UPDATE game_users SET STM=(STM + $razn),STM_MAX=(STM_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET STM=(STM + $razn),STM_MAX=(STM_MAX + $razn) WHERE user_id=".$item_user['user_id']."");
					}
					if ($cur_item['cc_p']!=$cc_p1)
					{
						$razn = $cc_p1-$cur_item['cc_p'];
						myquery("UPDATE game_users SET CC=(CC + $razn) WHERE user_id=".$item_user['user_id']."");
						myquery("UPDATE game_users_archive SET CC=(CC + $razn) WHERE user_id=".$item_user['user_id']."");
					}
				}
				echo '<br>Изменены характеристики (предмет был одет) у '.mysql_num_rows($sel_item_on_user).' игроков<br>';
			}
		}
	}
}

if (function_exists("save_debug")) save_debug(); 
?>
</body>
</html>