<?php
if ($adm['quest'] >= 1)
{	
	//вставка нового слова
	function new_word($type, $num, $pos)
	{
		switch ($type)
		{
			case "oh":case "ent_w": 
			{
				//$text="вай-вай"; 
				$words=myquery("SELECT word FROM quest_engine_words WHERE type='".$type."'");
				jump_random_query($words);
				list($text)=mysql_fetch_array($words);
				break;
			}
			//case "ent_w": $text="короче говоря"; break;
			case "obr": 
			{
				//$text="сэр"; 
				global $user_id;
				list($sex)=mysql_fetch_array(myquery("SELECT sex FROM game_users_data WHERE user_id=".$user_id.""));
				if($sex=="male")
					$words=myquery("SELECT word FROM quest_engine_words WHERE SUBSTRING(word,1,1)='m' AND type='obr'");			
				else
					$words=myquery("SELECT word FROM quest_engine_words WHERE SUBSTRING(word,1,1)='f' AND type='obr'");			
				jump_random_query($words);
				list($text)=mysql_fetch_array($words);
				$text=substr($text,2);
				break;
			}
			
			case "gold": 
			{
				if($num%10==1)
					$text=$num.' золотой';
				else 	
					$text=$num.' золотых';			
				break;
			}	
			case "exp":
			{
				if($num%10==0 OR $num%10>=5)							
					$text=$num.' очков опыта';
				elseif($num%10==1) $text=$num.' очко опыта';
					else $text=$num.' очка опыта';	
				break;	
			}
			case "wins":
			{
				if($num%10==0 OR $user['par1_value']%10>=5)							
					$text=$num.' побед';
				elseif($num%10==1) $text=$num.' победу';
				else $text=$num.' победы';			
				break;
			}
			case "map_name":
			{
				$text=$num;
				if(substr_count($num,'елe'))  $text.='e';
				break;				
			}
			case "items":
			{
				switch ($num)
				{
					case 1:$text="хорошее оружие";break;
					case 2:$text="хорошие кольца";break;
					case 3:$text="хорошие артефакты";break;
					case 4:$text="хорошие щиты";break;
					case 5:$text="хорошие доспехи";break;
					case 6:$text="хорошие шлемы";break;
					case 7:$text="хорошая магия";break;
					case 8:$text="хорошие пояса";break;
					case 9:$text="хорошие ожерелья";break;
					case 10:$text="хорошие перчатки";break;
					case 11:$text="хорошая обувь";break;
					case 12:$text="хорошие бутылочки :)";break;		
				}
				break;
			}
			default: return ""; break;
		}
		if($pos==1)
		{
			$text[0]=strtoupper($text[0]);
		}
		return $text;
	}

	//преобразование кодов в казвания
	function NumToName($type, $num)
	{
		if($type=="action")
		{
			switch ($num)
			{
				case 11: return "1.1. Приветствие"; break;	
				case 12: return "1.2. Отказ (прощание)"; break;	
				case 13: return "1.3. Задание"; break;	
				case 14: return "1.4. Квеста нет"; break;
				case 21: return "2.1. Выполнил вовремя"; break;	
				case 22: return "2.2. Выполнил с опозданием"; break;	
				case 23: return "2.3. Провал и опоздание"; break;
				case 24: return "2.4. Не выполнил, но время еще есть"; break;
				case 31: return "3.1. Таверна. Приветствие"; break;
				case 32: return "3.2. Таверна. Вовремя"; break;
				case 33: return "3.3. Таверна. Опоздал"; break;
				case 34: return "3.4. Судоку"; break;
				case 40: return "4.0. Журнал"; break;
				//case 23: return ""; break;				
			}
		}
		elseif($type=="quest")
		{			
			switch ($num)
			{
				case 0: return "0. Тип квеста не нужен"; break;
				case 1: return "1. Убить большого монстра"; break;	
				case 2: return "2. Убить несколько монстров (по параметру)"; break;	
				case 3: return "3. Набрать опыта"; break;
				case 4: return "4. Набрать побед"; break;
				case 5: return "5. Отнести посылку в город"; break;
				case 601: return "601. Головоломка Судоку"; break;
				case 7: return "7. Принести Рар"; break;
				case 801: return "801. Принести дорогой предмет"; break;
				case 802: return "802. Принести N недорогих по дорговцу и типу"; break;
				case 803: return "803. Принести N недорогих по хар-кам"; break;
				case 804: return "804. Принести оружие по % поломки"; break;
				//case 23: return ""; break;
			}
		}
	}	
	
//открываем теги формы и таблицы	
echo '<form name=new id=1 method=post>
<input type=hidden name=option value="quest_topics">
<input type=hidden name=opt value="main">
<table width=100% border=0 cellspacing=3 cellpadding=0 align=left>';

//если мы в главном меню
if(!isset($act))
{		
	echo '<tr><td align="center" colspan="2"><font color=green size=5>ГЛАВНОЕ МЕНЮ :)</font></td></tr><br>';
	echo '<tr valign=center><td width="40%" align=right rowspan=6>Выберите нужный пункт:</td><td width="60%" align="left">
	<INPUT type="radio" name="act" value="add" checked=true>Добавить сюжет</td></tr><tr><td>
	<INPUT type="radio" name="act" value="view">Просмотр сюжетов</td></tr>
	<tr><td><INPUT type="radio" name="act" value="add_word">Добавить слово</td></tr>
	<tr><td><INPUT type="radio" name="act" value="view_words">Просмотр базы слов</td></tr>';
	echo '<tr><td><INPUT type="radio" name="act" value="add_owner">Добавить НПЦ</td></tr>
	<tr><td><INPUT type="radio" name="act" value="view_owners">Просмотр базы НПЦ</td></tr>';
	echo '<tr><td align="center" colspan=2><INPUT type="submit" value="Выбрать"></td></tr>';
}
//иначе, если в добавлении сюжета
elseif(isset($act) AND $act=="add")	
{	
	//шапочка
	echo '<tr><td align="center" colspan="2"><font color=green size=5>ДОБАВЛЕНИЕ СЮЖЕТА</font><br>
	<input type="button" value="В главное меню :)" onclick=location.href="?option=quest_topics&opt=main"></td></tr><tr><td>&#160;</td></tr>';
	echo '<input type=hidden name=act value="add">';
	//выберем ИД последнего сюжета
	list($last_id)=mysql_fetch_array(myquery("SELECT max(topic_id) FROM quest_engine_topics WHERE 1"));
	if(empty($last_id)) $last_id=0;
	//шаг = выбор номера сюжета
	if(!isset($new_topic_id))
	{		
		//обработка ошибок
		if(isset($nerror) AND $nerror=="not_num") echo '<tr><td align="center" colspan="2"><font color=orange> !Вы должны ввести число! </font></td></tr>';	
		if(isset($nerror) AND $nerror=="top_was") echo '<tr><td align="center" colspan="2"><font color=orange> !Запись с выбранными Вами параметрами уже существует! </font></td></tr>';
		//форма ввода
		echo '
		<tr><td align="right">';				
		echo 'Номер сюжета (последний номер = '.($last_id).'):</td><td>
		<input type=text name=new_topic_id size=50 value="'.($last_id+1).'">
		</td></tr>
		<tr><td colspan="2" align="center"><INPUT type="submit" value="Выбрать"></td></tr>';
	//шаг = выбор НПЦ для кветса	
	}elseif(!isset($new_owner_id))
	{
		//проверка
		if(!is_numeric($new_topic_id)) 
		echo  '<meta http-equiv="refresh" content="0;url=?opt=main&option=news&nerror=not_num&act=add">;
';
		//поля ввода-вывода
		echo '<input type=hidden name=new_topic_id value="'.$new_topic_id.'">';		
		echo '<tr><td align="right">Номер сюжета (последний номер = '.$last_id.'):</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_topic_id.'"></td></tr>';
		echo '
		<tr><td align="right">Выберите персонажа:</td>
		<td>
		<SELECT name="new_owner_id" size="1" width=50>';
		$owners=myquery("SELECT * FROM quest_engine_owners WHERE 1");
		while ($owner=mysql_fetch_array($owners))
			echo '<OPTION value="'.$owner['id'].'">'.$owner['name'].'</OPTION>';
		echo '</SELECT>
		</TD></TR>';
		echo '<tr><td colspan="2" align="center"><INPUT type="submit" value="Выбрать"> 
			<input type="button" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=add"></td></tr>';
	//шаг = выбор типа действия
	}elseif (!isset($new_action_type))
	{
		//поля ввода-вывода
		echo '<input type=hidden name=new_owner_id value="'.$new_owner_id.'">';
		echo '<input type=hidden name=new_topic_id value="'.$new_topic_id.'">';
		echo '<tr><td align="right">Номер сюжета (последний номер = '.$last_id.'):</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_topic_id.'"></td></tr>';
		list($new_owner_name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$new_owner_id.""));
		echo '<tr><td align="right">Выбранный персонаж:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_owner_name.'"></td></tr>';
		echo '
		<tr><td align="right">Выберите тип действия:</td>
		<td>
		<SELECT name="new_action_type" size="1">
		<OPTION value="11">1.1. Приветствие</OPTION>
		<OPTION value="12">1.2. Отказ (прошание)</OPTION>
		<OPTION value="13">1.3. Задание</OPTION>
		<OPTION value="14">1.4. Квеста нет</OPTION>	
		<OPTION value="21">2.1. Выполнил вовремя</OPTION>
		<OPTION value="22">2.2. Выполнил с опозданием</OPTION>
		<OPTION value="23">2.3. Провал и опоздание</OPTION>
		<OPTION value="24">2.4. Не выполнил, но время еще есть</OPTION>
		<OPTION value="31">3.1. Таверна. Приветствие</OPTION>
		<OPTION value="32">3.2. Таверна. Вовремя</OPTION>
		<OPTION value="33">3.3. Таверна. Опоздал</OPTION><BR>
		<OPTION value="34">3.4. Судоку</OPTION>
		<OPTION value="40">4.0. Журнал</OPTION>
		</SELECT>
		</TD></TR>';
		echo '<tr><td colspan="2" align="center"><INPUT type="submit" value="Выбрать"> 
		<input type="button" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=add&new_topic_id='.$new_topic_id.'"></td></tr>';
	//шаг = ввод типа квеста
	}elseif (!isset($new_quest_type))
	{
		//поля ввода-вывода
		$new_action_type_name=NumToName("action",$new_action_type);
		echo '<input type=hidden name=new_owner_id value="'.$new_owner_id.'">';
		echo '<input type=hidden name=new_topic_id value="'.$new_topic_id.'">';
		echo '<input type=hidden name=new_action_type value="'.$new_action_type.'">';
		echo '<tr><td align="right">Номер сюжета (последний номер = '.$last_id.'):</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_topic_id.'"></td></tr>';
		list($new_owner_name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$new_owner_id.""));
		echo '<tr><td align="right">Выбранный персонаж:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_owner_name.'"></td></tr>';
		echo '<tr><td align="right">Выбранный тип действия:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_action_type_name.'"></td></tr>';
		//для некоторый типов действий тип квеста не нужен или уже определен
		if($new_action_type!=11 AND $new_action_type!=12 AND $new_action_type!=14 AND $new_action_type!=31 AND $new_action_type!=32 AND $new_action_type!=33 AND $new_action_type!=34)
			echo '
			<tr><td align="right">Выберите тип квеста:</td>
			<td>
			<SELECT name="new_quest_type" size="1">
			<OPTION value="1">1. Убить большого монстра</OPTION>
			<OPTION value="2">2. Убить несколько монстров (по параметру)</OPTION>
			<OPTION value="3">3. Набрать опыта</OPTION>
			<OPTION value="4">4. Набрать побед</OPTION>
			<OPTION value="5">5. Отнести посылку в город</OPTION>
			<OPTION value="601">601. Головоломка Судоку</OPTION>
			<OPTION value="7">7. Принести Рар</OPTION>
			<OPTION value="801">801. Принести дорогой предмет</OPTION>
			<OPTION value="802">802. Принести N недорогих по дорговцу и типу</OPTION>
			<OPTION value="803">803. Принести N недорогих по хар-кам</OPTION><BR>
			<OPTION value="804">804. Принести оружие по % поломки</OPTION>
			</SELECT>
			</TD></TR>';
		else
		{
			if($new_action_type==11 OR $new_action_type==12 OR $new_action_type==14)
				$type_id=0;
			elseif ($new_action_type==34)
				$type_id=601;
			else 
				$type_id=5;
			echo '<tr><td align="right">Для этого типа действия тип квестов:</td><td>
			<input disabled type=text name=new_quest_type size=50 value="'.NumToName("quest",$type_id).'">
			<input type=hidden name=new_quest_type value="'.$type_id.'">
			</td></tr>';	
		}
		echo '<tr><td colspan="2" align="center"><INPUT type="submit" value="Выбрать"> 
		<input type="button" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=add&new_topic_id='.$new_topic_id.'&new_owner_id='.$new_owner_id.'"></td></tr>';	
	//шаг = ввод текста	
	}elseif (!isset($new_text))
	{
		//
		if($new_quest_type!=0 AND !isset($old_text))
		{
			list($topics)=mysql_fetch_array(myquery("SELECT id FROM quest_engine_topics WHERE topic_id=".$new_topic_id." AND owner_id=".$new_owner_id." AND quest_type=".$new_quest_type." AND action_type=".$new_action_type.""));
			if(!empty($topics))
				echo  '<meta http-equiv="refresh" content="0;url=?opt=main&option=news&nerror=top_was&act=add">';
		}
		$new_action_type_name=NumToName("action",$new_action_type);
		$new_quest_type_name=NumToName("quest",$new_quest_type);		
		echo '<input type=hidden name=new_owner_id value="'.$new_owner_id.'">';
		echo '<input type=hidden name=new_topic_id value="'.$new_topic_id.'">';
		echo '<input type=hidden name=new_action_type value="'.$new_action_type.'">';
		echo '<input type=hidden name=new_quest_type value="'.$new_quest_type.'">';
		echo '<tr><td align="right">Номер сюжета (последний номер = '.$last_id.'):</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_topic_id.'"></td></tr>';
		list($new_owner_name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$new_owner_id.""));
		echo '<tr><td align="right">Выбранный персонаж:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_owner_name.'"></td></tr>';
		echo '<tr><td align="right">Выбранный тип действия:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_action_type_name.'"></td></tr>';
		echo '<tr><td align="right">Выбранный тип квеста:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_quest_type_name.'"></td></tr>';
		echo '
		<tr><td align="right">
		Введите текст, используя следующие обозначения:<br>';		
		echo '<font color=red><b>ВНИМАНИЕ! НЕ ИСПОЛЬЗУЙТЕ СИМВОЛ ОДИНАРНЫХ И ДВОЙНЫХ КАВЫЧЕК В ВАШЕМ ТЕКСТЕ, КРОМЕ КАК ДЛЯ ВСТАВКИ ФУНКЦИИ (ПОКА ЧТО)!</b></font><br>
		<font color=grenn><b>НО! Зато можете использовать для форматирования текста html-теги :)</b></font>
		<BR><font color=orange>Для переноса строки используйте html тег < br >.</font>
		
		$char_name - имя перса. <BR> $char_race - раса перса. <BR>
		<font color=orange>В ф-ции new_word() вместо case надо вставить 1 или 0. 1 - слово вставится с большой буквы, 0 - с малой (по умолчанию будет использоваться малая буква).
		</font> <BR>
		".new_word("ent_w",0,case)." - ф-ия вставки вводного слова слова. <BR>
		".new_word("oh",0,case)." - ф-ия вставки междометия. <BR>
		".new_word("obr",0,case)." - ф-ия вставки обращения. <BR>
		".new_word("gold",$reward,0)." - ф-ия вставки суммы вознаграждения (в формате "27 золотых"). <BR>
		$time - кол-во вермени на квест (в формате 5 часов 3 минуты)
		<hr align="center" size="1" width="80%">';
						
		if($new_action_type!=11 AND $new_action_type!=12 AND $new_action_type!=31 AND $new_action_type!=32 AND $new_action_type!=33 AND $new_action_type!=34)
		{	
			switch ($new_quest_type)
			{
				case 1:
					echo '$npc_name - имя монстра (Им. падеж). <br> 
							$npc_race - раса монстра (Им. падеж). ';
					break;
				case 2:
					echo '$part_name - название требуемой части монстра.  
						<br>$num - число, необходимое кол-во частей. 
						<br>$par2(3,4)_rus_name - название нужного параметра в формате <font color=orange>", сила которого не меньше"</font>. <br>
					$par2(3,4)_value - число, значение нужного параметра. <br>
					<font color=red>Числа 2,3,4 - 1й, 2й и 3й параметры соответственно. Если параметра нет, ничего не вставится в текст. Рекомендуется вписывать все параметры друг за другом: $par2_rus_name $par2_value $par3_rus_name $par3_value $par4_rus_name $par4_value</font>. ';
					break;	
				case 3:
					echo '".new_word("exp",$exp,0)." - необходимый опыт в формате <font color=orange>"5 очков опыта"</font>. <br>
					$exp - необходимое кол-во опыта. ';
				break;	
				case 4:
					echo '".new_word("wins",$wins,0)." - необходимый число побед в формате <font color=orange>"5 побед"</font>. <br>
					$wins - необходимое число побед. ';
				break;
				case 5:
				echo '".new_word("map_name",$map_name,1)." - название карты. <br>
					$rustowun - название города. ';
					
				break;
				case 601:
				echo '".new_word("map_name",$map_name,1)." - название карты. <br>
					$x, $y - соотв. координаты задания. ';
					
				break;
				case 7:
					echo '$name - название нужного предмета. ';
				break;
				case 801:
					echo '$name - список нужных предметов в формате <font color=orange>"жестокая сабля или мистический меч или шлем хаоса"</font>. ';
				break;
				case 802:
					echo '$shop_name - имя торговца. <br>
						".new_word("items",$type_id,case)." - название нужного типа в формате <font color=orange>"хорошие кольца"</font>. <br>
						$num - необходимое кол-во предметов. ';
				break;
				case 803:
					echo '$name - необходимые параметры в формате  <font color=orange>"силу на 2 и ловкость на 1"</font>. <br>
						$num - необходимое кол-во предметов. <br>';
				break;
				case 804:
					echo '$wname - название необходимого оружия. <br>
						$top - число, верхняя граница процента прочности. <br>
						$bottom - число, нижяя граница процента прочности. <br>';
				break;
				
			}
		}
		if(!isset($old_text)) $old_text='';
		echo '</td>
		<td>
		<TEXTAREA class="input" name="new_text" rows="25" cols="70">'.$old_text.'</TEXTAREA>
		</TD></TR>';
		echo '<tr><td colspan="2" align="center"><INPUT type="submit" value="Выбрать"> 
		<input type="button" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=add&new_topic_id='.$new_topic_id.'&new_owner_id='.$new_owner_id.'&new_action_type='.$new_action_type.'"></td></tr>';	
				
	}elseif(!isset($add_it))
	{						
		$new_action_type_name=NumToName("action",$new_action_type);
		$new_quest_type_name=NumToName("quest",$new_quest_type);		
		echo '<input type=hidden name=new_owner_id value="'.$new_owner_id.'">';
		echo '<input type=hidden name=new_topic_id value="'.$new_topic_id.'">';
		echo '<input type=hidden name=new_action_type value="'.$new_action_type.'">';
		echo '<input type=hidden name=new_quest_type value="'.$new_quest_type.'">';		
		echo '<tr><td align="right">Номер сюжета (последний номер = '.$last_id.'):</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_topic_id.'"></td></tr>';
		list($new_owner_name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$new_owner_id.""));
		echo '<tr><td align="right">Выбранный персонаж:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_owner_name.'"></td></tr>';
		echo '<tr><td align="right">Выбранный тип действия:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_action_type_name.'"></td></tr>';
		echo '<tr><td align="right">Выбранный тип квеста:</td><td><input disabled type=text name=new_topic_id size=50 value="'.$new_quest_type_name.'"></td></tr>';		
		//если кто-то забыл установить регистр		
		if(strstr($new_text,'case'))
		{
			$new_text=str_replace('case','0',$new_text);									
			$note='<font color=red>Внимание! Вы забыли установить регистр в одной или более функции вставки слова. Регистр по умолчанию переведен в нижний. Проверьте текст и исправьте, если это неверно</font>';
		}else $note='';
		//если кто-то хочет влезть внутрь
		$denieded=array('HTTP','myqsl','query','$_','','$HTTP','db','include','require');
		$new_text=str_ireplace($denieded,'',$new_text);
		echo '<tr><td align="right">Исходный текст (не изменяйте его здесь, изменения не будут сохранены):<br>'.$note.'</td><td>'; 
		echo '<TEXTAREA class="input" name="new_text" rows="15" cols="70">'.$new_text.'</TEXTAREA>';
		 echo "</td></tr>";
		echo '<tr><td colspan="2" align="center">		
		<font color=orange>Текст записи (преобразованный):</font></td></tr>';	
		$code='echo "';
		$code.=$new_text;
		$code.='";';				
		echo '<tr><td align="right">Преобразованный текст:</td><td align="left" colspan="2">'.$code.'</td></tr>';
		echo '<input type=hidden name=add_it value=1>';		
		echo '<tr><td colspan="2" align="center"><font color=orange>Всё ли верно?</font></td></tr>';
		$old_text=str_replace('"','&quot;',$new_text);
		echo '<tr><td colspan="2" align="center"><INPUT type="submit" value="Да, добавить в БД"> 
		<input type="button" value="Нет, назад" onclick=history.back()></td></tr>';	
		//<input type="button" value="Нет, назад" onclick=main.history.back()></td></tr>';	
		//<input type="button" value="Нет, назад" onclick=location.href="?option=quest_topics&opt=main&act=add&new_topic_id='.$new_topic_id.'&new_owner_id='.$new_owner_id.'&new_action_type='.$new_action_type.'&new_quest_type='.$new_quest_type.'&old_text='.$old_text.'"></td></tr>';	
	} 
	else 
	{
		//если кто-то забыл установить регистр		
		if(strstr($new_text,'case'))
		{
			$new_text=str_replace('case','0',$new_text);									
			$note='<font color=red>Внимание! Вы забыли установить регистр в одной или более функции вставки слова. Регистр по умолчанию переведен в нижний. Проверьте текст и исправьте, если это неверно</font>';
		}else $note='';
		//если кто-то хочет влезть внутрь
		$denieded=array('HTTP','myqsl','query','$_','','$HTTP','db','include','require');
		$new_text=str_ireplace($denieded,'',$new_text);
		//преобразуем в код		
		$code='echo "';
		$code.=$new_text;
		$code.='";';
		//и засунем в БД
		list($topic)=mysql_fetch_array(myquery("SELECT id FROM quest_engine_topics WHERE 		
		topic_id=".$new_topic_id." AND
		owner_id=".$new_owner_id." AND
		action_type=".$new_action_type." AND
		quest_type=".$new_quest_type.""));
		//если надо - заменим
		if(!empty($topic))
			$del=myquery("DELETE FROM quest_engine_topics WHERE id=".$topic."");			
		myquery("INSERT INTO quest_engine_topics (topic_id,owner_id,action_type,quest_type,text) VALUES (".$new_topic_id.",".$new_owner_id.",".$new_action_type.",".$new_quest_type.",'".$code."')") or die("<font color=red>ОШИБКА! ЧТО-ТО ПОШЛО НЕ ТАК!</font>");
		echo '<tr><td colspan="2" align=center><font color=green size=5>ВСЕ В ПОРЯДКЕ, ЗАПИСЬ ДОБАВЛЕНА!</td></tr>';
		echo '<tr><td colspan="2" align=center><font color=green size=3>Подождите, вы будете автоматически перемещены.</td></tr>';
		echo  '<meta http-equiv="refresh" content="2;url=?option=quest_topics&opt=main&act=add">';
	}
	
}elseif (isset($act) AND $act=="view")
{
	if(!isset($edit))
	{
		echo '<input type="hidden" name="act" value="view">
		<tr><td align="center" colspan=7><font color=green size=5>ПРОСМОТР ЗАПИСЕЙ</font><br>
		<input type="button" value="В главное меню :)" onclick=location.href="?option=quest_topics&opt=main"></td></tr><tr><td colspan=7>&#160;</td></tr>';			
		?>
		<tr align="center" bgcolor="Teal"><td colspan="7">Поиск</td></tr>
		<tr bgcolor="#990099" align="center"><td>-</td><td>-</td><td>По ID</td><td>По Персонажу</td><td>По типу квеста</td><td>По типу действия</td><td>-</td></TR>		
		<tr><td></td><td></td>
		<td><INPUT type="text" name="search_topic_id" value="" size="5" maxlength="4"></td>
		<td>
		<?PHP
		echo '<SELECT name="search_owner_id" size="1" width=50>';
		echo '<OPTION value="0"> Пусто</OPTION>';
		$owners=myquery("SELECT * FROM quest_engine_owners WHERE 1");
		while ($owner=mysql_fetch_array($owners))
			echo '<OPTION value="'.$owner['id'].'">'.$owner['name'].'</OPTION>';		
		echo '</SELECT>';		
		?>
		</td>
		<td><SELECT name="search_quest_type" size="1">
			<OPTION value="0"> Пусто</OPTION>
			<OPTION value="1">1. Убить большого монстра</OPTION>
			<OPTION value="2">2. Убить несколько монстров (по параметру)</OPTION>
			<OPTION value="3">3. Набрать опыта</OPTION>
			<OPTION value="4">4. Набрать побед</OPTION>
			<OPTION value="5">5. Отнести посылку в город</OPTION>
			<OPTION value="601">601. Головоломка Судоку</OPTION>
			<OPTION value="7">7. Принести Рар</OPTION>
			<OPTION value="801">801. Принести дорогой предмет</OPTION>
			<OPTION value="802">802. Принести N недорогих по дорговцу и типу</OPTION>
			<OPTION value="803">803. Принести N недорогих по хар-кам</OPTION><BR>
			<OPTION value="804">804. Принести оружие по % поломки</OPTION>
			</SELECT></td>
		<td><SELECT name="search_action_type" size="1">
		<OPTION value="0"> Пусто</OPTION>
		<OPTION value="11">1.1. Приветствие</OPTION>
		<OPTION value="12">1.2. Отказ (прошание)</OPTION>
		<OPTION value="13">1.3. Задание</OPTION>
		<OPTION value="14">1.4. Квеста нет</OPTION>
		<OPTION value="21">2.1. Выполнил вовремя</OPTION>
		<OPTION value="22">2.2. Выполнил с опозданием</OPTION>
		<OPTION value="23">2.3. Провал и опоздание</OPTION>
		<OPTION value="24">2.4. Не выполнил, но время еще есть</OPTION>
		<OPTION value="31">3.1. Таверна. Приветствие</OPTION>
		<OPTION value="32">3.2. Таверна. Вовремя</OPTION>
		<OPTION value="33">3.3. Таверна. Опоздал</OPTION><BR>
		<OPTION value="34">3.4. Судоку</OPTION>
		<OPTION value="40">4.0. Журнал</OPTION>
		</SELECT></td><td></td></TR>
		<tr align="center"><td colspan="7"><INPUT type="submit" value="Искать"></TD></TR>
		<tr><td colspan="7">&nbsp;</TD></TR>
		<tr align="center" bgcolor="Teal">
		<td>№</td><td>Редактор</td><td>ID сюжета</td><td>Персонаж</td><td>Тип квеста</td><td>Тип действия</td><td>Текст</td>
		</TR>	
		<?
		$where='';
		if(isset($search_topic_id) AND is_numeric($search_topic_id) AND !empty($search_topic_id))
			$where.='topic_id='.$search_topic_id.' AND ';
		if(isset($search_owner_id) AND !empty($search_owner_id))
			$where.='owner_id='.$search_owner_id.' AND ';
		if(isset($search_quest_type) AND !empty($search_quest_type))
			$where.='quest_type='.$search_quest_type.' AND ';
		if(isset($search_action_type) AND !empty($search_action_type))
			$where.='action_type='.$search_action_type.' AND ';
		if( (!isset($search_topic_id) OR empty($search_topic_id)) AND 
			(!isset($search_owner_id) OR empty($search_owner_id))AND
			(!isset($search_quest_type) OR empty($search_quest_type)) AND 
			(!isset($search_action_type) OR empty($search_action_type)) )
			$where='1';
		else 
			$where=substr($where,0,strlen($where)-5);
		
		$topics=myquery("SELECT * FROM quest_engine_topics WHERE ".$where." ORDER BY topic_id, owner_id, quest_type, action_type ASC");
		$i=0;		
		while ($topic=mysql_fetch_array($topics))
		{			
			$i++;
			list($owner_name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$topic["owner_id"].""));			$text='view';/***/
			echo '<tr align="center">
			<td align="center">'.$i.'</td>
			<td align="center"> 
			<INPUT type="button" name="Edit" title="Edit" value="E" onclick=location.href="?option=quest_topics&opt=main&act=view&edit=edit&id='.$topic["id"].'">
			<INPUT type="button" name="Delete" title="Delete" value="D" onclick=location.href="?option=quest_topics&opt=main&act=view&edit=del&id='.$topic["id"].'">
			<INPUT type="button" name="View" title="View" value="V" onclick=location.href="?option=quest_topics&opt=main&act=view&edit=view&id='.$topic["id"].'">
			</td>			
			<td align="center">'.$topic["topic_id"].'</td><td>'.$owner_name.'</td><td>'.NumToName("quest", $topic["quest_type"]).'</td><td>'.NumToName("action", $topic["action_type"]).'</td><td align="left">'./*$topic["text"]*/$text.' <HR noshade size="1" width="90%"></td></td>
			</TR>';		
		}
	}elseif ($edit=="view")
	{
		echo '<tr><td align="center" colspan=2><font color=grenn size=5>ПРОСМОТР РЕЗУЛЬТАТА</font></td></tr>';
		$topic=myquery("SELECT quest_type,text FROM quest_engine_topics WHERE id=".$id."");
		list($quest_type,$text)=mysql_fetch_array($topic);
		include("quest/quest_engine_types/inc/standart_vars_view.inc.php");
		echo '<tr><td align=right width=50%><font size=2>Примерный результат:</font></td>
		<td align=left width=50%><font size=2>';
		//echo $text.'<br><br><br>';
		eval($text);		
		echo '</font></td></tr>';
		echo '<tr><td align=center colspan=2>
		<INPUT type="button" name="refresh" value="Обновить" onclick=parent.main.location.reload()>
		<INPUT type="button" name="back" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=view"></td></tr>';		
	}
	elseif ($edit=="edit")
	{
		$topic=mysql_fetch_array(myquery("SELECT * FROM quest_engine_topics WHERE id=".$id.""));
		$text=substr($topic['text'],6);
		$text=substr($text,0,strlen($text)-2);
		$text=str_replace('"','&quot;',$text);		
		echo '<tr><td align="center"><font color=orange size=5>ПEРЕХОД В РЕЖИМ РЕДАКТИРОВАНИЯ</font><br>Строка: '.$text.'';
	
		echo  '<meta http-equiv="refresh" content="3; url=?option=quest_topics&opt=main&act=add&new_topic_id='.$topic['topic_id'].'&new_owner_id='.$topic['owner_id'].'&new_action_type='.$topic['action_type'].'&new_quest_type='.$topic['quest_type'].'&old_text='.$text.'">';
	}elseif ($edit=="del")
	{
		echo '<tr><td align="center" colspan=5><font color=red size=5>ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ УДАЛИТЬ ЭТУ ЗАПИСЬ?</font>
		</td></tr><tr><td>&#160;</td></tr>';			
		?>
		<tr align="center" bgcolor="Teal">
		<td>Редактор</td><td>ID сюжета</td><td>Персонаж</td><td>Тип квеста</td><td>Тип действия</td><td>Текст</td>
		</TR>	
		<?
		$topic=mysql_fetch_array(myquery("SELECT * FROM quest_engine_topics WHERE id=".$id.""));
		list($owner_name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$topic["owner_id"].""));
		echo '<tr align="left">
			<td align="center">'.$topic["topic_id"].'</td><td>'.$owner_name.'</td><td>'.NumToName("quest", $topic["quest_type"]).'</td><td>'.NumToName("action", $topic["action_type"]).'</td><td>'.$topic["text"].'</td>
			</TR>';		
		echo '<tr><td>&#160;</td></tr>';
		echo '<tr><td colspan=5 align=center>
		<INPUT type="button" name="Yes" title="Да" value="Да-да-да, фтопку её!" onclick=location.href="?option=quest_topics&opt=main&act=view&edit=del_it&id='.$topic["id"].'">
			<INPUT type="button" name="No" title="Нет" value="Вай, нет, дверью ошибся!" onclick=location.href="?option=quest_topics&opt=main&act=view">
		</tr></td>';
	}elseif ($edit=="del_it")
	{
		$del=myquery("DELETE FROM quest_engine_topics WHERE id=".$id."");
		echo '<tr><td align="center"><font color=red size=5>ЗАПИСЬ УДАЛЕНА!</font>';
		echo  '<meta http-equiv="refresh" content="1; url=?option=quest_topics&opt=main&act=view">';
	}
}elseif (isset($act) AND $act=="add_word")
{
	echo '<tr><td align="center" colspan="2"><font color=green size=5>ДОБАВЛЕНИЕ СЛОВ</font><br>
		<input type="button" value="В главное меню :)" onclick=location.href="?option=quest_topics&opt=main"></td></tr><tr><td  colspan="2">&#160;</td></tr>';
	echo '<input type=hidden name=act value="add_word">';
	if(!isset($the_word))
	{
		$oh_s="selected";$obr_s="";$ent_w_s="";
		if(!isset($word)) $word="";
		if(!isset($id)) $id=0;
		echo '<INPUT TYPE="hidden" name="id" value='.$id.'>';
		if(isset($type))
		switch ($type)
		{
			case "oh": $oh_s="selected"; break;
			case "ent_w": $oh_s=""; $ent_w_s="selected"; break;
			case "obr": $oh_s=""; $obr_s="selected"; break;
		}
		echo '<tr><td align="right" width=50%>Тип слова:</TD><td align="left" width=50%>
		<SELECT name="word_type" size="3">
		<OPTION value="oh" '.$oh_s.'>Междометие</OPTION>
		<OPTION value="ent_w" '.$ent_w_s.'>Вводное слово</OPTION>
		<OPTION value="obr" '.$obr_s.'>Обращениe</OPTION>
		</SELECT>
		</TD></tr>
		<tr><td align="right" width=50%>Введите слово:<br><FONT color="orange">Для пометки обращения мужского рода используйте префикс m_ , а для женского - префикс f_ .</FONT>
		</TD><td width=50% align="left">
		<INPUT type="text" size="50" name="the_word" value='.$word.'>
		</td></tr>
		<tr><td colspan="2" align="center"><INPUT type="submit" value="Добавить в базу"></td></TR>';		
	}elseif (isset($the_word))
	{
		echo '<tr><td align="center" colspan="2">';
		if(!isset($word_type) OR ( $word_type!="obr" AND $word_type!="ent_w" AND $word_type!="oh")) 
		{
			echo '<FONT color="Red" size="5">ОШИБКА! НЕВЕРНЫЙ ТИП СЛОВА!</FONT>';			
		}elseif (empty($the_word))
		{
			echo '<FONT color="Red" size="5">ОШИБКА! ВЫ НЕ ВВЕЛИ СЛОВО!</FONT>';	
		}else 
		{
			if($word_type=="obr" AND substr($the_word,0,2)!="f_" AND substr($the_word,0,2)!="m_")
				echo '<FONT color="orange" size="5">ОШИБКА! ВЫ НЕ УКАЗАЛИ ПРЕФИКС РОДА!</FONT>';
			else 
			{
				if(isset($id) AND !empty($id))
					$del=myquery("DELETE FROM quest_engine_words WHERE id=".$id."");
				$add=myquery("INSERT INTO quest_engine_words (type, word) VALUES ('".$word_type."','".$the_word."')");
				echo '<FONT color="green" size="5">ВСЕ В ПОРЯДКЕ, СЛОВО ДОБАВЛЕНО!</FONT>';
			}
		}		
		echo '</td></tr>
		<tr><td align="center" colspan="2">
		<INPUT type="button" name="vack" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=add_word"></td></tr>
		<meta http-equiv="refresh" content="2; url=?option=quest_topics&opt=main&act=add_word">';
	}
}elseif (isset($act) AND $act=="view_words")
{
	if(!isset($edit))
	{
		echo '<tr><td align="center" colspan=6><font color=green size=5>ПРОСМОТР СЛОВ</font><br>
			<input type="button" value="В главное меню :)" onclick=location.href="?option=quest_topics&opt=main"></td></tr><tr><td colspan=4>&#160;</td></tr>';	
			$words=myquery("SELECT * FROM quest_engine_words WHERE 1 ORDER BY type, word ASC");
			?>
			<tr align="center">
			<td width="15%">&nbsp;</TD><td bgcolor="Teal" width="5%">№</td><td bgcolor="Teal" width="10%">Редактор</td><td bgcolor="Teal" width="10%">Тип слова</td><td bgcolor="Teal" width="15%">Слово</td><td width="15%">&nbsp;</TD>
			</TR>	
			<?
			$i=0;
			while ($word=mysql_fetch_array($words))
			{
				$i++;			
				echo '<tr align="left">
				<td>&nbsp;</TD>
				<td  align="center">'.$i.'</td>
				<td align="center"> 
				<INPUT type="button" name="Edit" title="Edit" value="E" onclick=location.href="?option=quest_topics&opt=main&act=view_words&edit=edit&id='.$word["id"].'">
				<INPUT type="button" name="Delete" title="Delete" value="D" onclick=location.href="?option=quest_topics&opt=main&act=view_words&edit=del&id='.$word["id"].'">				
				</td>
				<td align="center">'.$word["type"].'</td><td align="center">'.$word['word'].'
				<td>&nbsp;</TD>
				</TR>';		
			}
	}
	elseif ($edit=="edit")
	{
		$word=mysql_fetch_array(myquery("SELECT * FROM quest_engine_words WHERE id=".$id.""));		
		echo '<tr><td align="center"><font color=orange size=5>ПEРЕХОД В РЕЖИМ РЕДАКТИРОВАНИЯ</font>';
		echo  '<meta http-equiv="refresh" content="1; url=?option=quest_topics&opt=main&act=add_word&type='.$word['type'].'&word='.$word['word'].'&id='.$id.'">';
	}elseif ($edit=="del")
	{
		echo '<tr><td align="center" colspan=3><font color=red size=5>ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ УДАЛИТЬ ЭТУ ЗАПИСЬ?</font>
		</td></tr><tr><td colspan=3>&#160;</td></tr>';			
		?>
		<tr align="center" bgcolor="Teal">
		<td>№</td><td>Тип слова</td><td>Слово</td>
		</TR>	
		<?
		$word=mysql_fetch_array(myquery("SELECT * FROM quest_engine_words WHERE id=".$id.""));		
		echo '<tr align="left">
			<td align="center">1</td><td align="center">'.$word["type"].'</td><td align="center">'.$word['word'].'</td>
			</TR>';		
		echo '<tr><td colspan=3>&#160;</td></tr>';
		echo '<tr><td colspan=3 align=center>
		<INPUT type="button" name="Yes" title="Да" value="Да!" onclick=location.href="?option=quest_topics&opt=main&act=view_words&edit=del_it&id='.$word["id"].'">
			<INPUT type="button" name="No" title="Нет" value="Нет!" onclick=location.href="?option=quest_topics&opt=main&act=view_words">
		</tr></td>';
	}elseif ($edit=="del_it")
	{
		$del=myquery("DELETE FROM quest_engine_words WHERE id=".$id."");
		echo '<tr><td align="center"><font color=red size=5>ЗАПИСЬ УДАЛЕНА!</font>';
		echo  '<meta http-equiv="refresh" content="1; url=?option=quest_topics&opt=main&act=view_words">';
	}
}elseif (isset($act) AND $act=="add_owner")
{
	echo '<tr><td align="center" colspan="2"><font color=green size=5>ДОБАВЛЕНИЕ НПЦ</font><br>
		<input type="button" value="В главное меню :)" onclick=location.href="?option=quest_topics&opt=main"></td></tr><tr><td  colspan="2">&#160;</td></tr>';
	echo '<input type=hidden name=act value="add_owner">';
	if(!isset($npc_name))
	{
		if(isset($id))
		{
			$npc=mysql_fetch_array(myquery("SELECT * FROM quest_engine_owners WHERE id=".$id.""));
			$name=$npc['name'];
			$npc_id=$id;
			$map_name=$npc['map_name'];
			$map_x=$npc['map_xpos'];
			$map_y=$npc['map_ypos'];
			$enter = $npc['enter'];
			$about = $npc['about'];
		}
		else 
		{
			$name='';
			$npc_id='';
			$map_name='';
			$map_x='';
			$map_y='';
			$enter = '';
			$about = '';
		}
		echo '<tr><td align="right" width=50%>NPC\'s ID :</TD><td align="left" width=50%>
		<INPUT type="text" size="50" name="npc_id" value='.$npc_id.'></TD></tr>
		<tr><td align="right" width=50%>Имя (название) НПЦ:</TD><td align="left" width=50%>
		<INPUT type="text" size="50" name="npc_name" value='.$name.'></TD></tr>';
		$maps=myquery("SELECT id,name FROM game_maps WHERE 1");
		echo '<tr><td align="right" width=50%>Карта:</TD><td align="left" width=50%>
		<SELECT name="map_name">';
		while ($map=mysql_fetch_array($maps))
		{
			if($map['name']==$map_name) $sel='selected';
			else $sel='';
			echo '<OPTION value="'.$map['id'].' '.$sel.'">'.$map['name'].'</OPTION>';
		}
		echo '</SELECT></td></tr>';
		echo '<tr><td align="right" width=50%>X:</TD><td width=50% align="left">
		<INPUT type="text" size="10" name="map_x" value='.$map_x.'></td></tr>
		<tr><td align="right" width=50%>Y:</TD><td width=50% align="left">
		<INPUT type="text" size="10" name="map_y" value='.$map_y.'></td></tr>';
		
		echo '<tr><td align="right" width=50%>Текст входной ссылки:</TD><td width=50% align="left">
		<INPUT type="text" size="50" name="enter" value='.$enter.'></td></tr>
		<tr><td align="right" width=50%>Наружное описание:</TD><td width=50% align="left">
		<textarea name="about" cols="50" rows="15">'.$about.'</textarea></td></tr>';
		
		echo  '<tr><td colspan="2" align="center"><INPUT type="submit" value="Добавить в базу"></td></TR>';		
	}elseif (isset($npc_name))
	{
		echo '<tr><td align="center" colspan="2">';
		if(!isset($npc_id) OR empty($npc_id))
		{
			echo '<FONT color="Red" size="5">ОШИБКА! ВЫ НЕ ВВЕЛИ ID!</FONT>';	
		}
		elseif (!isset($npc_name) OR empty($npc_name))
		{
			echo '<FONT color="Red" size="5">ОШИБКА! ВЫ НЕ ВВЕЛИ СЛОВО!</FONT>';	
		}
		elseif (!isset($enter) OR empty($enter))
		{
			echo '<FONT color="Red" size="5">ОШИБКА! ВЫ НЕ ВВЕЛИ ВХОДНУЮ ССЫЛКУ!</FONT>';	
		}
		elseif (!isset($about) OR empty($about))
		{
			echo '<FONT color="Red" size="5">ОШИБКА! ВЫ НЕ ВВЕЛИ НАРУЖНЕЕ ОПИСАНИЕ!</FONT>';	
		}
		elseif(!isset($map_name) OR empty($map_name) OR !isset($map_x) OR empty($map_x) OR !isset($map_y) OR empty($map_y)) 
		{
			echo '<FONT color="Red" size="5">ОШИБКА! НЕВЕРНО УКАЗАНА КАРТА ИЛИ КООРДИНАТЫ!</FONT>';	
		}
		else 
		{	
			$del=myquery("DELETE FROM quest_engine_owners WHERE id=".$npc_id."");
			$add=myquery("INSERT INTO quest_engine_owners (id, name, map_name, map_xpos, map_ypos, enter, about) VALUES (".$npc_id.",'".$npc_name."',".$map_name.",".$map_x.",".$map_y.", '".$enter."', '".$about."')");
			echo '<FONT color="green" size="5">ВСЕ В ПОРЯДКЕ, НПЦ ДОБАВЛЕН!</FONT>';		
		}
		echo '</td></tr>
		<tr><td align="center" colspan="2">
		<INPUT type="button" name="vack" value="Назад" onclick=location.href="?option=quest_topics&opt=main&act=add_owner"></td></tr>
		<meta http-equiv="refresh" content="2; url=?option=quest_topics&opt=main&act=add_owner">';
	}
}elseif (isset($act) AND $act=="view_owners")
{
	if(!isset($edit))
	{
		echo '<tr><td align="center" colspan=7><font color=green size=5>ПРОСМОТР СЛОВ</font><br>
			<input type="button" value="В главное меню :)" onclick=location.href="?option=quest_topics&opt=main"></td></tr><tr><td colspan=4>&#160;</td></tr>';	
			$npcs=myquery("SELECT * FROM quest_engine_owners WHERE 1 ORDER BY id, name ASC");
			?>
			<tr align="center">
			<td bgcolor="Teal" width="5%">Редактор</td><td bgcolor="Teal" width="4%">ID</td><td bgcolor="Teal" width="12%">Имя</td><td bgcolor="Teal" width="12%">Карта</td><td bgcolor="Teal" width="4%">Х</TD><td bgcolor="Teal" width="4%">Y</TD><td bgcolor="Teal" width="10%">Вход</TD><td bgcolor="Teal" width="24%">Описание</TD>
			</TR>	
			<?
			$i=0;
			while ($npc=mysql_fetch_array($npcs))
			{
				list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$npc['map_name'].""));
				echo '<tr align="left">
				<td align="center"> 
				<INPUT type="button" name="Edit" title="Edit" value="E" onclick=location.href="?option=quest_topics&opt=main&act=view_owners&edit=edit&nid='.$npc["id"].'">
				<INPUT type="button" name="Delete" title="Delete" value="D" onclick=location.href="?option=quest_topics&opt=main&act=view_owners&edit=del&id='.$npc["id"].'">				
				</td>
				<td align="center">'.$npc['id'].'</TD>
				<td align="center">'.$npc['name'].'</td>
				<td align="center">'.$map_name.'</td>
				<td align="center">'.$npc['map_xpos'].'</td>
				<td align="center">'.$npc['map_ypos'].'</TD>
				<td align="center">'.$npc['enter'].'</td>
				<td align="center">'.$npc['about'].'</TD>
				</TR>';		
			}
	}
	elseif ($edit=="edit")
	{
		//$npc=mysql_fetch_array(myquery("SELECT * FROM quest_engine_words WHERE id=".$id.""));
				
		echo '<tr><td align="center"><font color=orange size=5>ПEРЕХОД В РЕЖИМ РЕДАКТИРОВАНИЯ</font>';
		echo  '<meta http-equiv="refresh" content="1; url=?option=quest_topics&opt=main&act=add_owner&id='.$nid.'">';
	}elseif ($edit=="del")
	{
		
		echo '<tr><td align="center" colspan=3><font color=red size=5>ВЫ УВЕРЕНЫ, ЧТО ХОТИТЕ УДАЛИТЬ ЭТУ ЗАПИСЬ?</font>
		</td></tr><tr><td colspan=3>&#160;</td></tr>';			
		?>
		<tr align="center" bgcolor="Teal">
		<td>id</td><td>Имя</td><td>Карта</td>
		</TR>	
		<?
		$npc=mysql_fetch_array(myquery("SELECT * FROM quest_engine_owners WHERE id=".$id.""));	
		list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$npc['map_name'].""));	
		echo '<tr align="left">
			<td align="center">'.$npc["id"].'</td><td align="center">'.$npc["name"].'</td><td align="center">'.$map_name.'</td>
			</TR>';		
		echo '<tr><td colspan=3>&#160;</td></tr>';
		echo '<tr><td colspan=3 align=center>
		<INPUT type="button" name="Yes" title="Да" value="Да!" onclick=location.href="?option=quest_topics&opt=main&act=view_owners&edit=del_it&id='.$npc["id"].'">
			<INPUT type="button" name="No" title="Нет" value="Нет!" onclick=location.href="?option=quest_topics&opt=main&act=view_owners">
		</tr></td>';
	}elseif ($edit=="del_it")
	{
		$del=myquery("DELETE FROM quest_engine_owners WHERE id=".$id."");
		echo '<tr><td align="center"><font color=red size=5>ЗАПИСЬ УДАЛЕНА!</font>';
		echo  '<meta http-equiv="refresh" content="1; url=?option=quest_topics&opt=main&act=view_owners">';
	}
}
echo '</FORM>';
echo '</table>';
}
?>