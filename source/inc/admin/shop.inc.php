<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['shop'] >= 1)
{
echo '<center>Управление торговцами:<br>';

	if (isset($new))
	{
		if (!isset($save))
		{
			echo'<form action="" method="post"><table border=0>
			<tr><td>Имя:</td><td><input type=text name=name value=""></td></tr>
			<tr><td>Рисунок:</td><td><input type=text name=name_img value=""></td></tr>
			<tr><td>Кнопка вход:</td><td><input type=text name=vhod value=""></td></tr>

			<tr><td>Название перед входом:</td><td><textarea name=text cols=30 class=input rows=4></textarea></td></tr>
			<tr><td>Описание перед входом:</td><td><textarea name=privet cols=30 class=input rows=6></textarea></td></tr>

			<tr><td>Текст приветствия:</td><td><textarea name="ind" cols=30 class=input rows=10></textarea></td></tr>
			<tr><td>&nbsp;</td><td></td></tr>';

			echo'<tr><td>Продажа</td><td><input name=prod type=checkbox value=1></td></tr>';
			echo'<tr><td>Ремонт</td><td><input name=remont type=checkbox value=1></td></tr>';
			echo'<tr><td>Идентификация</td><td><input name=ident type=checkbox value=1></td></tr>';
			echo'<tr><td>Клеймение</td><td><input name=kleymo type=checkbox value=0> (нельзя устанавливать одновременно с идентификацией)</td></tr>';

			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td colspan=2 align=center><font color=ff0000><b>Максимум 4 категории!!!</b></font></td></tr>';
			echo'<tr><td>Шлемы</td><td><input name=shlem type=checkbox value=1> (максимальный запас на складе: <input type=text name=shlem_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Оружие</td><td><input name=oruj type=checkbox value=1> (максимальный запас на складе: <input type=text name=oruj_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Доспехи</td><td><input name=dosp type=checkbox value=1> (максимальный запас на складе: <input type=text name=dosp_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Щиты</td><td><input name=shit type=checkbox value=1> (максимальный запас на складе: <input type=text name=shit_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Пояса</td><td><input name=pojas type=checkbox value=1> (максимальный запас на складе: <input type=text name=pojas_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Магия</td><td><input name=mag type=checkbox value=1> (максимальный запас на складе: <input type=text name=mag_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Кольца</td><td><input name=ring type=checkbox value=1> (максимальный запас на складе: <input type=text name=ring_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Артефакты</td><td><input name=artef type=checkbox value=1> (максимальный запас на складе: <input type=text name=artef_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Ожерелья</td><td><input name=amulet type=checkbox value=1> (максимальный запас на складе: <input type=text name=amulet_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Перчатки</td><td><input name=perch type=checkbox value=1> (максимальный запас на складе: <input type=text name=perch_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Обувь</td><td><input name=boots type=checkbox value=1> (максимальный запас на складе: <input type=text name=boots_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Свитки</td><td><input name=svitki type=checkbox value=1> (максимальный запас на складе: <input type=text name=svitki_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Эликсиры</td><td><input name=eliksir type=checkbox value=1> (максимальный запас на складе: <input type=text name=eliksir_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Поножи</td><td><input name=shtan type=checkbox value=1> (максимальный запас на складе: <input type=text name=shtan_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Наручи</td><td><input name=naruchi type=checkbox value=1> (максимальный запас на складе: <input type=text name=naruchi_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Украшения</td><td><input name=ukrash type=checkbox value=1> (максимальный запас на складе: <input type=text name=ukrash_store_max value="" size=7>)</td></tr>';
			//echo'<tr><td>Магические книги</td><td><input name=magic_books type=checkbox value=1> (максимальный запас на складе: <input type=text name=magic_books_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Инструменты</td><td><input name=instrument type=checkbox value=1> (максимальный запас на складе: <input type=text name=instrument_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Схемы предметов</td><td><input name=schema type=checkbox value=1> (максимальный запас на складе: <input type=text name=schema_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Луки</td><td><input name=luk type=checkbox value=1> (максимальный запас на складе: <input type=text name=luk_store_max value="" size=7>)</td></tr>';
			echo'<tr><td>Прочее</td><td><input name=others type=checkbox value=1> (максимальный запас на складе: <input type=text name=others_store_max value="" size=7>)</td></tr>';

			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td>Расположение:</td><td>';

			echo'<select name=map>';
			$result = myquery("SELECT * FROM game_maps ORDER BY BINARY name");
			while($map=mysql_fetch_array($result))
			{
				echo '<option value='.$map['id'].'>'.$map['name'].'';
				echo '</option>';
			}
			echo '</select> ';
			echo'x-<input type=text name=pos_x value="" size=2>, y-<input type=text name=pos_y value="" size=2></td></tr>';

			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td>Цена продажи предметов в % (минимальная/максимальная)</td><td><input type=text name=cena_prod_min value="" size=4> / <input type=text name=cena_prod_max value="" size=4></td></tr>';
			echo'<tr><td>Цена покупки предметов в % (минимальная/максимальная)</td><td><input type=text name=cena_pok_min value="" size=4> / <input type=text name=cena_pok_max value="" size=4></td></tr>';
			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td align=right>Виден в view.'.DOMAIN.'</td><td><input name="view" type="checkbox" value="1"></td></tr>';
			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value=""></td></tr>';

			echo'</table>';
		}
		else
		{
			if (isset($view) and $view=='1') $v='1';
			if (!isset($view)) $v='0';

			if(!isset($prod)) $prod=0;
			if(!isset($remont)) $remont=0;
			if(!isset($ident)) $ident=0;
			if(!isset($kleymo)) $kleymo=0;
			if(!isset($shlem)) $shlem=0;
			if(!isset($oruj)) $oruj=0;
			if(!isset($dosp)) $dosp=0;
			if(!isset($shit)) $shit=0;
			if(!isset($pojas)) $pojas=0;
			if(!isset($mag)) $mag=0;
			if(!isset($ring)) $ring=0;
			if(!isset($artef)) $artef=0;
			if(!isset($svitki)) $svitki=0;
			if(!isset($amulet)) $amulet=0;
			if(!isset($boots)) $boots=0;
			if(!isset($perch)) $perch=0;
			if(!isset($eliksir)) $eliksir=0;
			if(!isset($naruchi)) $naruchi=0;
			if(!isset($shtan)) $shtan=0;
			if(!isset($ukrash)) $ukrash=0;
			if(!isset($magic_books)) $magic_books=0;
			if(!isset($magic_books_store_max)) $magic_books_store_max=0;
			if(!isset($schema)) $schema=0;
			if(!isset($instrument)) $instrument=0;
			if(!isset($luk)) $luk=0;
			if(!isset($others)) $others=0;

			$update=myquery("INSERT INTO game_shop (
			name,name_img,text,vhod,privet,ind,prod,remont,ident,
			shlem,oruj,dosp,shit,pojas,mag,ring,artef,
			other,map,pos_x,pos_y,cena_pok,cena_prod,view,
			cena_pok_min,cena_pok_max,cena_prod_min,cena_prod_max,
			shlem_store_max,shlem_store_current,
			oruj_store_max,oruj_store_current,
			dosp_store_max,dosp_store_current,
			shit_store_max,shit_store_current,
			mag_store_max,mag_store_current,
			pojas_store_max,pojas_store_current,
			ring_store_max,ring_store_current,  
			artef_store_max,artef_store_current,
			amulet,amulet_store_max,amulet_store_current,
			perch,perch_store_max,perch_store_current,
			boots,boots_store_max,boots_store_current,
			svitki,svitki_store_max,svitki_store_current,
			eliksir,eliksir_store_max,eliksir_store_current,
			shtan,shtan_store_max,shtan_store_current,
			naruchi,naruchi_store_max,naruchi_store_current,
			ukrash,ukrash_store_max,ukrash_store_current,
			magic_books,magic_books_store_max,magic_books_store_current,
			`schema`,schema_store_max,schema_store_current,
			luk,luk_store_max,luk_store_current,
			instrument,instrument_store_max,instrument_store_current,
			others,others_store_max,others_store_current,
			kleymo
			) 
			VALUES 
			('$name', '$name_img', '$text', '$vhod', '$privet', '$ind', '$prod', '$remont', '$ident', 
			'$shlem', '$oruj', '$dosp', '$shit', '$pojas', '$mag', '$ring', '$artef', 
			'0', '$map', '$pos_x', '$pos_y', '$cena_pok_min', '$cena_prod_max','$v',
			'$cena_pok_min','$cena_pok_max','$cena_prod_min','$cena_prod_max',
			'$shlem_store_max','$shlem_store_max',
			'$oruj_store_max','$oruj_store_max',
			'$dosp_store_max','$dosp_store_max',
			'$shit_store_max','$shit_store_max',
			'$mag_store_max','$mag_store_max',
			'$pojas_store_max','$pojas_store_max',
			'$ring_store_max','$ring_store_max',
			'$artef_store_max','$artef_store_max',
			'$amulet','$amulet_store_max','$amulet_store_max',
			'$perch','$perch_store_max','$perch_store_max',
			'$boots','$boots_store_max','$boots_store_max',
			'$svitki','$svitki_store_max','$svitki_store_max',
			'$eliksir','$eliksir_store_max','$eliksir_store_max',
			'$shtan','$shtan_store_max','$shtan_store_max',
			'$naruchi','$naruchi_store_max','$naruchi_store_max',
			'$ukrash','$ukrash_store_max','$ukrash_store_max',
			'$magic_books','$magic_books_store_max','$magic_books_store_max',
			'$schema','$schema_store_max','$schema_store_max',
			'$luk','$luk_store_max','$luk_store_max',
			'$instrument','$instrument_store_max','$instrument_store_max',
			'$others','$others_store_max','$others_store_max',
			'$kleymo'
			)");
			echo'Торговец добавлен';
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Добавил торговца: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=shop">';
		}
	}

	if (isset($edit))
	{
		if (!isset($save))
		{
			$sel=myquery("select * from game_shop where id='$edit'");
			$shop=mysql_fetch_array($sel);
			echo'<form action="" method="post"><table border=0>
			<tr><td>Имя:</td><td><input type=text name=name value='.$shop['name'].'></td></tr>
			<tr><td>Рисунок:</td><td><input type=text name=name_img value='.$shop['name_img'].'></td></tr>
			<tr><td>Кнопка вход:</td><td><input type=text name=vhod value='.$shop['vhod'].'></td></tr>

			<tr><td>Название перед входом:</td><td><textarea name=text cols=30 class=input rows=4>'.$shop['text'].'</textarea></td></tr>
			<tr><td>Описание перед входом:</td><td><textarea name=privet cols=30 class=input rows=6>'.$shop['privet'].'</textarea></td></tr>

			<tr><td>Текст приветствия:</td><td><textarea name="ind" cols=30 class=input rows=10>'.$shop['ind'].'</textarea></td></tr>
			<tr><td>&nbsp;</td><td></td></tr>';

			echo'<tr><td>Продажа</td><td><input name=prod type=checkbox value=1 '; if($shop['prod']==1) echo'checked'; echo'></td></tr>';
			echo'<tr><td>Ремонт</td><td><input name=remont type=checkbox value=1 '; if($shop['remont']==1) echo'checked'; echo'></td></tr>';
			echo'<tr><td>Идентификация</td><td><input name=ident type=checkbox value=1 '; if($shop['ident']==1) echo'checked'; echo'></td></tr>';
			echo'<tr><td>Клеймение</td><td><input name=kleymo type=checkbox value=1 '; if($shop['kleymo']==1) echo'checked'; echo'> (нельзя устанавливать вместе с идентификацией))</td></tr>';

			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td colspan=2 align=center><font color=ff0000><b>Максимум 4 категории!!!</b></font></td></tr>';
			echo'<tr><td>Шлемы</td><td><input name=shlem type=checkbox value=1 '; if($shop['shlem']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=shlem_store_current value="'.$shop['shlem_store_current'].'" size=7> / <input type=text name=shlem_store_max value="'.$shop['shlem_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Оружие</td><td><input name=oruj type=checkbox value=1 '; if($shop['oruj']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=oruj_store_current value="'.$shop['oruj_store_current'].'" size=7> / <input type=text name=oruj_store_max value="'.$shop['oruj_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Доспехи</td><td><input name=dosp type=checkbox value=1 '; if($shop['dosp']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=dosp_store_current value="'.$shop['dosp_store_current'].'" size=7> / <input type=text name=dosp_store_max value="'.$shop['dosp_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Щиты</td><td><input name=shit type=checkbox value=1 '; if($shop['shit']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=shit_store_current value="'.$shop['shit_store_current'].'" size=7> / <input type=text name=shit_store_max value="'.$shop['shit_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Пояса</td><td><input name=pojas type=checkbox value=1 '; if($shop['pojas']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=pojas_store_current value="'.$shop['pojas_store_current'].'" size=7> / <input type=text name=pojas_store_max value="'.$shop['pojas_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Магия</td><td><input name=mag type=checkbox value=1 '; if($shop['mag']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=mag_store_current value="'.$shop['mag_store_current'].'" size=7> / <input type=text name=mag_store_max value="'.$shop['mag_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Кольца</td><td><input name=ring type=checkbox value=1 '; if($shop['ring']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=ring_store_current value="'.$shop['ring_store_current'].'" size=7> / <input type=text name=ring_store_max value="'.$shop['ring_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Артефакты</td><td><input name=artef type=checkbox value=1 '; if($shop['artef']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=artef_store_current value="'.$shop['artef_store_current'].'" size=7> / <input type=text name=artef_store_max value="'.$shop['artef_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Ожерелья</td><td><input name=amulet type=checkbox value=1 '; if($shop['amulet']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=amulet_store_current value="'.$shop['amulet_store_current'].'" size=7> / <input type=text name=amulet_store_max value="'.$shop['amulet_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Перчатки</td><td><input name=perch type=checkbox value=1 '; if($shop['perch']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=perch_store_current value="'.$shop['perch_store_current'].'" size=7> / <input type=text name=perch_store_max value="'.$shop['perch_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Обувь</td><td><input name=boots type=checkbox value=1 '; if($shop['boots']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=boots_store_current value="'.$shop['boots_store_current'].'" size=7> / <input type=text name=boots_store_max value="'.$shop['boots_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Свитки</td><td><input name=svitki type=checkbox value=1 '; if($shop['svitki']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=svitki_store_current value="'.$shop['svitki_store_current'].'" size=7> / <input type=text name=svitki_store_max value="'.$shop['svitki_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Эликсиры</td><td><input name=eliksir type=checkbox value=1 '; if($shop['eliksir']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=eliksir_store_current value="'.$shop['eliksir_store_current'].'" size=7> / <input type=text name=eliksir_store_max value="'.$shop['eliksir_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Поножи</td><td><input name=shtan type=checkbox value=1 '; if($shop['shtan']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=shtan_store_current value="'.$shop['shtan_store_current'].'" size=7> / <input type=text name=shtan_store_max value="'.$shop['shtan_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Наручи</td><td><input name=naruchi type=checkbox value=1 '; if($shop['naruchi']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=naruchi_store_current value="'.$shop['naruchi_store_current'].'" size=7> / <input type=text name=naruchi_store_max value="'.$shop['naruchi_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Украшения</td><td><input name=ukrash type=checkbox value=1 '; if($shop['ukrash']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=ukrash_store_current value="'.$shop['ukrash_store_current'].'" size=7> / <input type=text name=ukrash_store_max value="'.$shop['ukrash_store_max'].'" size=7></td></tr>';
			//echo'<tr><td>Магические книги</td><td><input name=magic_books type=checkbox value=1 '; if($shop['magic_books']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=magic_books_store_current value="'.$shop['magic_books_store_current'].'" size=7> / <input type=text name=magic_books_store_max value="'.$shop['magic_books_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Инструменты</td><td><input name=instrument type=checkbox value=1 '; if($shop['instrument']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=instrument_store_current value="'.$shop['instrument_store_current'].'" size=7> / <input type=text name=instrument_store_max value="'.$shop['instrument_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Схемы предметов</td><td><input name=schema type=checkbox value=1 '; if($shop['schema']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=schema_store_current value="'.$shop['schema_store_current'].'" size=7> / <input type=text name=schema_store_max value="'.$shop['schema_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Луки</td><td><input name=luk type=checkbox value=1 '; if($shop['luk']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=luk_store_current value="'.$shop['luk_store_current'].'" size=7> / <input type=text name=luk_store_max value="'.$shop['luk_store_max'].'" size=7></td></tr>';
			echo'<tr><td>Прочее</td><td><input name=others type=checkbox value=1 '; if($shop['others']==1) echo'checked'; echo'> (текущий / максимальный запас) <input type=text name=others_store_current value="'.$shop['others_store_current'].'" size=7> / <input type=text name=others_store_max value="'.$shop['others_store_max'].'" size=7></td></tr>';

			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td>Расположение:</td><td>';

			echo'<select name=map>';
			$result = myquery("SELECT * FROM game_maps ORDER BY BINARY name");
			while($map=mysql_fetch_array($result))
			{
			echo '<option value='.$map['id'].'';
			if ($shop['map']==$map['id']) echo ' selected';
			echo '>'.$map['name'].'';
			echo '</option>';
			}
			echo '</select> ';
			echo'x-<input type=text name=pos_x value='.$shop['pos_x'].' size=2>, y-<input type=text name=pos_y value='.$shop['pos_y'].' size=2></td></tr>';

			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td>Минимальная / текущая /<br> максимальная цена продажи предметов в %</td><td><input type=text name=cena_prod_min value='.$shop['cena_prod_min'].' size=4> / <input type=text name=cena_prod value='.$shop['cena_prod'].' size=4> / <input type=text name=cena_prod_max value='.$shop['cena_prod_max'].' size=4></td></tr>';
			echo'<tr><td>Минимальная / текуща /<br> максимальная цена покупки предметов в %</td><td><input type=text name=cena_pok_min value='.$shop['cena_pok_min'].' size=4> / <input type=text name=cena_pok value='.$shop['cena_pok'].' size=4> / <input type=text name=cena_pok_max value='.$shop['cena_pok_max'].' size=4></td></tr>';
			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td align=right>Виден в view.'.DOMAIN.'</td><td><input name="view" type="checkbox" value="1"'; if($shop['view']==1) echo'checked'; echo'></td></tr>';
			echo'<tr><td>&nbsp;</td><td></td></tr>';
			echo'<tr><td colspan=2 align=center><input name="save" type="submit" value="Сохранить"><input name="save" type="hidden" value=""></td></tr>';
			echo'</table>';
		}
		else
		{
			if (isset($view) and $view=='1') $v='1';
			if (!isset($view)) $v='0';
			if(!isset($prod)) $prod=0;
			if(!isset($remont)) $remont=0;
			if(!isset($ident)) $ident=0;
			if(!isset($kleymo)) $kleymo=0;
			if(!isset($shlem)) $shlem=0;
			if(!isset($oruj)) $oruj=0;
			if(!isset($dosp)) $dosp=0;
			if(!isset($shit)) $shit=0;
			if(!isset($pojas)) $pojas=0;
			if(!isset($mag)) $mag=0;
			if(!isset($ring)) $ring=0;
			if(!isset($artef)) $artef=0;
			if(!isset($svitki)) $svitki=0;
			if(!isset($amulet)) $amulet=0;
			if(!isset($boots)) $boots=0;
			if(!isset($shtan)) $shtan=0;
			if(!isset($perch)) $perch=0;
			if(!isset($eliksir)) $eliksir=0;
			if(!isset($ukrash)) $ukrash=0;
			if(!isset($naruchi)) $naruchi=0;

			if(!isset($magic_books)) $magic_books=0;
			if(!isset($magic_books_store_max)) $magic_books_store_max=0;
			if(!isset($magic_books_store_current)) $magic_books_store_current=0;

			if(!isset($instrument)) $instrument=0;
			if(!isset($instrument_store_max)) $instrument_store_max=0;
			if(!isset($instrument_store_current)) $instrument_store_current=0;

			if(!isset($others)) $others=0;
			if(!isset($others_store_max)) $others_store_max=0;
			if(!isset($others_store_current)) $others_store_current=0;

			if(!isset($naruchi)) $naruchi=0;
			if(!isset($schema)) $schema=0;
			if(!isset($luk)) $luk=0;

			echo'Торговец изменен';
			$up=myquery("update game_shop set
			name='$name',
			name_img='$name_img',
			text='".$text."',
			vhod='".$vhod."',
			privet='".$privet."',
			ind='$ind',
			prod='".$prod."',
			remont='$remont',
			ident='$ident',
			shlem='$shlem',
			oruj='$oruj',
			dosp='$dosp',
			shit='$shit',
			pojas='$pojas',
			mag='$mag',
			ring='$ring',
			artef='$artef',
			svitki='$svitki',
			amulet='$amulet',
			eliksir='$eliksir',
			boots='$boots',
			perch='$perch',
			naruchi='$naruchi',
			ukrash='$ukrash',
			shtan='$shtan',
			magic_books='$magic_books',
			instrument='$instrument',
			`schema`='$schema',
			luk='$luk',
			kleymo='$kleymo',
			shlem_store_current='$shlem_store_current',
			oruj_store_current='$oruj_store_current',
			dosp_store_current='$dosp_store_current',
			shit_store_current='$shit_store_current',
			pojas_store_current='$pojas_store_current',
			mag_store_current='$mag_store_current',
			ring_store_current='$ring_store_current',
			artef_store_current='$artef_store_current',
			shlem_store_max='$shlem_store_max',
			oruj_store_max='$oruj_store_max',
			dosp_store_max='$dosp_store_max',
			shit_store_max='$shit_store_max',
			pojas_store_max='$pojas_store_max',
			mag_store_max='$mag_store_max',
			ring_store_max='$ring_store_max',
			artef_store_max='$artef_store_max',
			svitki_store_current='$svitki_store_current',
			svitki_store_max='$svitki_store_max',
			shtan_store_current='$shtan_store_current',
			shtan_store_max='$shtan_store_max',
			amulet_store_current='$amulet_store_current',
			amulet_store_max='$amulet_store_max',
			boots_store_current='$boots_store_current',
			boots_store_max='$boots_store_max',
			eliksir_store_current='$eliksir_store_current',
			eliksir_store_max='$eliksir_store_max',
			perch_store_current='$perch_store_current',
			perch_store_max='$perch_store_max',
			ukrash_store_current='$ukrash_store_current',
			ukrash_store_max='$ukrash_store_max',
			naruchi_store_current='$naruchi_store_current',
			naruchi_store_max='$naruchi_store_max',
			magic_books_store_current='$magic_books_store_current',
			magic_books_store_max='$magic_books_store_max',
			schema_store_current='$schema_store_current',
			schema_store_max='$schema_store_max',
			luk_store_current='$luk_store_current',
			luk_store_max='$luk_store_max',
			instrument_store_current='$instrument_store_current',
			instrument_store_max='$instrument_store_max',
			other='0',
			others='$others',
			others_store_current='$others_store_current',
			others_store_max='$others_store_max',
			map='$map',
			pos_x='$pos_x',
			pos_y='$pos_y',
			cena_pok='$cena_pok',
			cena_prod='$cena_prod',
			cena_pok_min='$cena_pok_min',
			cena_prod_min='$cena_prod_min',
			cena_pok_max='$cena_pok_max',
			cena_prod_max='$cena_prod_max',
			view='$v'
			where id='$edit'");
			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
			 VALUES (
			 '".$char['name']."',
			 'Изменил торговца: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
			echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=shop">';
		}
	}

	if(isset($delete))
	{
		echo'Запись удалена';
		list($name) = mysql_fetch_array(myquery("SELECT name FROM game_shop WHERE id='$delete'"));
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
		 VALUES (
		 '".$char['name']."',
		 'Удалил торговца №<b>".$name."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
		$up=myquery("delete from game_shop where id='$delete'");
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=shop">';
	}

	if(!isset($edit) and !isset($new) and !isset($delete))
	{
		echo'<a href="?opt=main&option=shop&new">Добавить торговца</a></center>';
		echo'<table border=0 cellspacing=5 align=center>';
		$sel=myquery("select * from game_shop order by id DESC");
		while($shop=mysql_fetch_array($sel))
		{
			echo'<tr><td>'.$shop['name'].'<br>'.$shop['cena_prod_min'].'/'.$shop['cena_prod'].'/'.$shop['cena_prod_max'].'<br>'.$shop['cena_pok_min'].'/'.$shop['cena_pok'].'/'.$shop['cena_pok_max'].'</td><td>'.@mysql_result(@myquery("SELECT name FROM game_maps WHERE id=".$shop['map'].""),0,0).' ('.$shop['pos_x'].','.$shop['pos_y'].')</td>';
			if ($shop['shlem']=='1')
			{
				echo '<td style="color:#C0FFFF;">Шлемы - '.$shop['shlem_store_current'].' / '.$shop['shlem_store_max'].'</td>';
			}
			if ($shop['oruj']=='1')
			{
				echo '<td style="color:#C0C0FF;">Оружие - '.$shop['oruj_store_current'].' / '.$shop['oruj_store_max'].'</td>';
			}
			if ($shop['dosp']=='1')
			{
				echo '<td style="color:#C0FFC0;">Доспехи - '.$shop['dosp_store_current'].' / '.$shop['dosp_store_max'].'</td>';
			}
			if ($shop['shit']=='1')
			{
				echo '<td style="color:#FFFFC0;">Щиты - '.$shop['shit_store_current'].' / '.$shop['shit_store_max'].'</td>';
			}
			if ($shop['pojas']=='1')
			{
				echo '<td style="color:#FFC0C0;">Пояса - '.$shop['pojas_store_current'].' / '.$shop['pojas_store_max'].'</td>';
			}
			if ($shop['mag']=='1')
			{
				echo '<td style="color:#FFC0FF;">Магия - '.$shop['mag_store_current'].' / '.$shop['mag_store_max'].'</td>';
			}
			if ($shop['ring']=='1')
			{
				echo '<td style="color:#F0F0F0;">Кольца - '.$shop['ring_store_current'].' / '.$shop['ring_store_max'].'</td>';
			}
			if ($shop['artef']=='1')
			{
				echo '<td style="color:#FF80FF;">Артефакты - '.$shop['artef_store_current'].' / '.$shop['artef_store_max'].'</td>';
			}
			if ($shop['amulet']=='1')
			{
				echo '<td style="color:#008080;">Ожерелья</td>';
			}
			if ($shop['perch']=='1')
			{
				echo '<td style="color:#008080;">Перчатки</td>';
			}
			if ($shop['boots']=='1')
			{
				echo '<td style="color:#008080;">Обувь</td>';
			}
			if ($shop['shtan']=='1')
			{
				echo '<td style="color:#008080;">Поножи</td>';
			}
			if ($shop['naruchi']=='1')
			{
				echo '<td style="color:#008080;">Наручи</td>';
			}
			if ($shop['ukrash']=='1')
			{
				echo '<td style="color:#008080;">Украшения</td>';
			}
			if ($shop['magic_books']=='1')
			{
				//echo '<td style="color:#008080;">Магические книги</td>';
			}
			if ($shop['svitki']=='1')
			{
				echo '<td style="color:#FF80FF;">Свитки - '.$shop['svitki_store_current'].' / '.$shop['svitki_store_max'].'</td>';
			}
			if ($shop['eliksir']=='1')
			{
				echo '<td style="color:#FF80FF;">Эликсиры - '.$shop['eliksir_store_current'].' / '.$shop['eliksir_store_max'].'</td>';
			}
			if ($shop['schema']=='1')
			{
				echo '<td style="color:#FF80FF;">Схемы предметов - '.$shop['schema_store_current'].' / '.$shop['schema_store_max'].'</td>';
			}
			if ($shop['luk']=='1')
			{
				echo '<td style="color:#FF80FF;">Луки - '.$shop['luk_store_current'].' / '.$shop['luk_store_max'].'</td>';
			}
			if ($shop['instrument']=='1')
			{
				echo '<td style="color:#FF80FF;">Инструменты - '.$shop['instrument_store_current'].' / '.$shop['instrument_store_max'].'</td>';
			}
			if ($shop['others']=='1')
			{
				echo '<td style="color:#FF80FF;">Прочее - '.$shop['others_store_current'].' / '.$shop['others_store_max'].'</td>';
			}
			echo '<td><a href="?opt=main&option=shop&edit='.$shop['id'].'">Редактировать</a></td><td><a href="?opt=main&option=shop&delete='.$shop['id'].'">Удалить</a></td></tr>';
		}
		echo'</table>';
	}
	echo'<center><a href="?opt=main&option=shop">Главная</a>';
}

if (function_exists("save_debug")) save_debug(); 

?>