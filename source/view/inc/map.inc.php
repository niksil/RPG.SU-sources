<?

if (function_exists("start_debug")) start_debug(); 

if (!isset($view)) $view='';
if (!isset($sort)) $sort='npc_name';
$old_map = $map;

echo'<SCRIPT language=javascript src="http://'.DOMAIN.'/js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>';

echo'<br><center>
<form name="form1" action="" method="GET">
<select name="view">';
if ($view=='shop')
		echo'<option value=shop selected>Торговцы</option>';
else
		echo'<option value=shop>Торговцы</option>';

if ($view=='gorod')
		echo'<option value=gorod selected>Города</option>';
else
		echo'<option value=gorod>Города</option>';

if ($view=='npc')
		echo'<option value=npc selected>NPC (боты)</option>';
else
		echo'<option value=npc>NPC (боты)</option>';

if ($view=='pr')
		echo'<option value=pr selected>Переходы</option>';
else
		echo'<option value=pr>Переходы</option>';

if ($view=='craft')
		echo'<option value=craft selected>Шахты</option>';
else
		echo'<option value=craft>Шахты</option>';
		
echo '</select>

<select name="map">';
$result = myquery("SELECT * FROM game_maps WHERE maze<>1 ORDER BY name");
while($map=mysql_fetch_array($result))
{
	echo '<option value="'.$map['id'].'"'; if($old_map==$map['id']) echo 'selected'; echo'>'.$map['name'].'</option>';
}
echo '</select>';

echo ' <input type="submit" value="&nbsp;&nbsp;&nbsp;ок&nbsp;&nbsp;&nbsp;"></form><br><br>';

@$map = $_REQUEST['map'];

if (!preg_match('/^[a-z]*$/i', $view))
{
	$view='npc';
}

$map = (int)$map;
$sel_map = myquery("SELECT name FROM game_maps WHERE id=$map");
if ($sel_map==false OR mysql_num_rows($sel_map)==0)
{
	die("Карта не найдена");
}
$map_name = mysql_result($sel_map,0,0);

$sel_maze =  myquery("SELECT maze FROM game_maps WHERE id=$map");
$maze = 0;
if ($sel_maze!=false and mysql_num_rows($sel_maze)>0)
{
	list($maze) = mysql_fetch_array($sel_maze);
}
if ($maze!=1) 
{
	if($view=='npc')
	{
		if (!isset($napr)) $napr="ASC";
		if (!isset($sort))
		{
			$sort = 'npc_name';
		}
        if (!in_array($sort,array('npc_name','npc_max_hp','npc_max_mp','npc_level','npc_str','npc_dex','npc_pie','npc_vit','npc_spd','npc_ntl','npc_exp_max','npc_gold'))) $sort = 'npc_name';
		if ($sort=='npc_xpos' OR $sort=='npc_ypos') $sort='npc_name';
		$sortir = ''.$sort.' '.$napr.'';
		$npc_online = time();
		$npc=myquery("select game_npc.*,game_npc_template.* from game_npc,game_npc_template where game_npc.map_name='$map' and game_npc.view=1 AND game_npc.time_kill+game_npc_template.respawn<unix_timestamp() AND game_npc_template.npc_id=game_npc.npc_id ORDER BY $sortir");
		if(mysql_num_rows($npc))
		{
			 ?>
			 <script type="text/javascript">
			 function showhide(id)
			 {
				 elem = document.getElementById("opis"+id);
				 if (elem.style.display=="block")
				 {
					 elem.style.display="none";
				 }
				 else
				 {
					 elem.style.display="block";
				 }
			 }
			 </script>
			 <style>
			 .opis
			 {
				 cursor: url('/images/nav/hand.cur'), pointer;
			 }
			 </style>
			 <?
			echo '<table border="0"><tr><td align="center" colspan="15"><hr color=555555 size=1 width=100%>Игровые боты: NPC<br></td></tr>';
			if ($napr=="ASC") $napr="DESC";
			else $napr="ASC";
			echo '<tr>
			<td><a href = "?view=npc&sort=npc_name&map='.$map.'&napr='.$napr.'">Имя</a></td>
			<td><a href = "?view=npc&sort=npc_max_hp&map='.$map.'&napr='.$napr.'">Жизни</a></td>
			<td><a href = "?view=npc&sort=npc_max_mp&map='.$map.'&napr='.$napr.'">Мана</a></td>
			<td><a href = "?view=npc&sort=npc_level&map='.$map.'&napr='.$napr.'">Ур.</a></td>
			<td><a href = "?view=npc&sort=npc_str&map='.$map.'&napr='.$napr.'">Сила</a></td>
			<td><a href = "?view=npc&sort=npc_dex&map='.$map.'&napr='.$napr.'">Вын-ть</a></td>
			<td><a href = "?view=npc&sort=npc_pie&map='.$map.'&napr='.$napr.'">Ловк.</a></td>
			<td><a href = "?view=npc&sort=npc_vit&map='.$map.'&napr='.$napr.'">Защита</a></td>
			<td><a href = "?view=npc&sort=npc_spd&map='.$map.'&napr='.$napr.'">Мудр.</a></td>
			<td><a href = "?view=npc&sort=npc_ntl&map='.$map.'&napr='.$napr.'">Интел.</a></td>
			<td><a href = "?view=npc&sort=npc_exp_max&map='.$map.'&napr='.$napr.'">Опыт</a></td>
			<td><a href = "?view=npc&sort=npc_gold&map='.$map.'&napr='.$napr.'">Мон.</a></td>
			<td align="right">Х</td>
			<td align="right">Y</td>
			<td></td>

			</tr>';
			while($row=mysql_fetch_array($npc))
			{
				
				echo'<tr>
				<td><font color=#EFEFEF>'.$row["npc_name"].'</td>
				<td><font color=#FF4646>'.$row["npc_max_hp"].'</td>
				<td><font color=#8EC0FD>'.$row["npc_max_mp"].'</td>
				<td><font color=#FFFB53>'.$row["npc_level"].'</td>
				<td><font color=#B7FFB7>'.$row["npc_str"].'&plusmn;'.$row["npc_str_deviation"].'</td>
				<td><font color=#FFFFB0>'.$row["npc_dex"].'&plusmn;'.$row["npc_dex_deviation"].'</td>
				<td><font color=#FF9FCF>'.$row["npc_pie"].'&plusmn;'.$row["npc_pie_deviation"].'</td>
				<td><font color=#FFA87D>'.$row["npc_vit"].'&plusmn;'.$row["npc_vit_deviation"].'</td>
				<td><font color=#B6A2EA>'.$row["npc_spd"].'&plusmn;'.$row["npc_spd_deviation"].'</td>
				<td><font color=#F9C093>'.$row["npc_ntl"].'&plusmn;'.$row["npc_ntl_deviation"].'</td>
				<td><font color=#8EC0FD>'.$row["EXP"].' ('.$row["npc_exp_max"].')</td>
				<td><font color=#FFFB53>'.$row["npc_gold"].'</td>
				<td align="right"><font color=#EEEEEE>';
				if ($row["npc_exp_max"]<=200)
				{
					echo $row["xpos"];
					echo '</td><td align="right"><font color=#FF7DFF>';
					echo $row["ypos"];
				}
				else  
				{
					echo ''.$row["xpos"]+1*$row["xpos_view"].'&plusmn;2';
					echo '</td><td align="right"><font color=#FF7DFF>';
					echo ''.$row["ypos"]+1*$row["ypos_view"].'&plusmn;2';
				}
				echo '</td><td>';
				if ($row['npc_opis']!='')
				{
					echo '<span class="opis" onclick="showhide('.$row["id"].')">Описание</span>';
				}
				echo '</td></tr>';
				if ($row['npc_opis']!='')
				{
					echo '<tr height="0"><td colspan="14" align="center" style="color:#C0FFC0;"><div id="opis'.$row['id'].'" style="display:none;">'.$row['npc_opis'].'</div></td></tr>';
				}
			}
			echo'</table>';
		}
		else
		{
			echo'Ничего не найдено';
		}
	}


	if($view=='shop')
	{
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Торговцы:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Имя</font></b></td><td><b><font color=#FFFF00>Описание</font></b></td><td width="140"><b><font color=#FFFF00>X,Y</font></b></td></tr>';
		$shop=myquery("select * from game_shop where map=$map and view=1 ORDER BY BINARY name");
		if(mysql_num_rows($shop))
		{
			while($row=mysql_fetch_array($shop))
			{
				echo'<tr><td>'; 
	?><span onmousemove="movehint(event)" onmouseover="showhint('<font color=ff0000><b><?
				echo '<center><font color=#800000>'.$row["name"].'</font>';
				?></b></font>','<?
				echo '<font color=000000>';
				echo '<b><u>Функции:</u></b><br>';
				if ($row['prod']==1) echo 'Продажа вещей<br>';
				if ($row['remont']==1) echo 'Ремонт вещей<br>';
				if ($row['ident']==1) echo 'Идентификация вещей<br>';
				if ($row['kleymo']==1) echo 'Заклеймение вещей<br>';
				if ($row['prod']==1)
				{
					echo '<hr><font color=#0000FF>';
					if ($row['shlem']==1) echo 'Продает шлемы<br>';
					if ($row['oruj']==1) echo 'Продает оружие<br>';
					if ($row['dosp']==1) echo 'Продает доспехи<br>';
					if ($row['shit']==1) echo 'Продает щиты<br>';
					if ($row['pojas']==1) echo 'Продает пояса<br>';
					if ($row['mag']==1) echo 'Продает магию<br>';
					if ($row['ring']==1) echo 'Продает кольца<br>';
					if ($row['artef']==1) echo 'Продает артефакты<br>';
					if ($row['svitki']==1) echo 'Продает свитки<br>';
					if ($row['eliksir']==1) echo 'Продает эликсиры<br>';	
					if ($row['schema']==1) echo 'Продает схемы предметов<br>';
					if ($row['luk']==1) echo 'Продает луки<br>';
				echo '</font>';
				}
				echo '</font>';
				?>',0,1,event)" onmouseout="showhint('','',0,0,event)">
				<?php
				
				echo $row["name"].'</span></td><td>'.$row["privet"].'</td><td width="140">'.$map_name.' ('.$row["pos_x"].', '.$row["pos_y"].')';
				echo'</td></tr>';
			}
		}
		else
		{
			echo'<br>Ничего не найдено<br>';
		}
		echo '</table>';
	}


	if($view=='pr')
	{
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Переходы:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Название</font></b></td><td width="140"><b><font color=#FFFF00>x, y</font></b></td><td><b><font color=#FFFF00>Описание</font></b></td></tr>';
		$shop=myquery("select * from game_map where name='$map' and town!=0 and to_map_name!=''");
		while($row=mysql_fetch_array($shop))
		{
			$sh=myquery("select name,text,clan,user,race,time,gp,timestart,view from game_obj where id='".$row["town"]."'");
			list($name,$text,$clann,$user,$race,$time,$gp,$timestart,$view_obj)=mysql_fetch_array($sh);
			if ($timestart!='')
			{
				$d = explode(" ",$timestart);
				$dat = explode(".",$d[0]);
				$tim = explode(":",$d[1]);
				$timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
				if(time() < $timestamp_open)
				{
					$tme='no';
				}
				else
				{
					$tme='ok';
				}
			}
			else
			{
				$tme='ok';
			}
			
			if ($time!='' and $tme!='no')
			{
				$d = explode(" ",$time);
				$dat = explode(".",$d[0]);
				$tim = explode(":",$d[1]);
				$timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
				if(time() <= $timestamp_open)
				{
					//echo'<span style="color:red;font-weight:800;">Проход закроется '.$time.'</span><br>';
					$tme='ok';
				}
				else
				{
					$tme='no';
				}
			}
			elseif ($tme!='no')
			{
				$tme='ok';
			}
			if ($user!='')
			{
			}
			elseif ($tme=='ok' and $view_obj==1)
			{
				echo'<tr><td>'.$name.'</td><td>'.$map_name.' ('.$row["xpos"].', '.$row["ypos"].')</td><td>';
				if ($clann!='') echo'Вход ограничен для кланов<br>';
				if ($user!='') echo'Вход ограничен для игроков<br>';
				if ($race!='') echo'Вход только для расы '.$race.'<br>';
				if ($time!='') echo'Врямя закрытия прохода '.$time.'<br>';
				if ($gp!=0) echo'Плата за вход '.$gp.' золотых<br>';
				if ($clann=='' and $user=='' and $race=='' and $gp=='' and $time=='' ) echo'Свободный проход<br>';
				echo'</td></tr>';
			}
		}
		echo '</table>';
	}


	if($view=='gorod')
	{
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Города:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Город</font></b></td><td width="140"><b><font color=#FFFF00>x, y</font></b></td><td><b><font color=#FFFF00>Описание</font></b></td></tr>';
		$shop=myquery("select game_map.xpos,game_map.ypos,game_gorod.town,game_gorod.rustown,game_gorod.clan,game_gorod.opis,game_gorod.race,game_gorod_option.name from game_map,game_gorod,game_gorod_set_option,game_gorod_option where game_map.name='$map' and game_map.town!=0 and game_map.to_map_name='' and game_map.to_map_xpos='0' and game_map.to_map_ypos='0' and game_gorod.town=game_map.town and game_gorod.view='1' and game_gorod_option.id=game_gorod_set_option.option_id and game_gorod_set_option.gorod_id=game_map.town and game_gorod.rustown!='' ORDER BY BINARY game_gorod.rustown");
		$cur_gorod = 0;
		$send_end = array();
		$last_row = array();
		while($row=mysql_fetch_array($shop))
		{
			if ($cur_gorod != $row['town'].$row['xpos'].$row['ypos'])
			{
				if ($cur_gorod>0)
				{
					echo '</font>';
					?>',0,1,event)" onmouseout="showhint('','',0,0,event)">
					<?php
					echo''.$last_row['rustown'].'</span></td><td>'.$map_name.' ('.$last_row["xpos"].', '.$last_row["ypos"].')</td><td>';
					if ($last_row['clan']!=0) echo'Вход в город ограничен для кланов<br>';
					elseif ($last_row['race']!=0) echo'Вход только для расы '.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$last_row['race'].""),0,0).'<br>';
					else echo'Свободный вход в город<br>';
					echo'</td></tr>';  
					$send_end[$cur_gorod]=1; 
				}
				echo'<tr><td>';
				?><span onmousemove=movehint(event) onmouseover="showhint('<font color=ff0000><b><?
				echo '<center><font color=#800000>'.$row['rustown'].'</font>';
				?></b></font>','<?
				echo '<font color=000000>';
				echo '<b><u>В городе есть:</u></b><br>';
				echo '</font>';
				echo '<font color=#0000FF>';
				$cur_gorod = $row['town'].$row['xpos'].$row['ypos'];
				$send_end[$cur_gorod]=0;
			}
			echo $row['name'].'<br>';
			$last_row = $row;
		}
		if ($cur_gorod>0 AND $send_end[$cur_gorod]==0)
		{
			echo '</font>';
			?>',0,1,event)" onmouseout="showhint('','',0,0,event)">
			<?php
			echo''.$last_row['rustown'].'</span></td><td>'.$map_name.' ('.$last_row["xpos"].', '.$last_row["ypos"].')</td><td>';
			if ($last_row['clan']!=0) echo'Вход в город ограничен для кланов<br>';
			elseif ($last_row['race']!=0) echo'Вход только для расы '.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$last_row['race'].""),0,0).'<br>';
			else echo'Свободный вход в город<br>';
			echo'</td></tr>';
		} 
		echo '</table>';
	}

	if ($view=='craft')
	{
		$craft = myquery("SELECT craft_build_user.*,craft_build.* FROM craft_build_user,craft_build WHERE craft_build_user.map=$map AND craft_build_user.type=craft_build.id ORDER BY BINARY craft_build.name");    
		echo '<table width=500 border=0 align=center><tr><td><hr color=555555 size=1 width=100%>Шахты:<br></td></tr>';
		echo '<tr><td><b><font color=#FFFF00>Имя</font></b></td><td><b><font color=#FFFF00>Кол-во рабочих мест</font></b></td><td><b><font color=#FFFF00>Требование предмета</font></b></td><td width="140"><b><font color=#FFFF00>X,Y</font></b></td></tr>';
		if(mysql_num_rows($craft))
		{
			while($row=mysql_fetch_array($craft))
			{
				echo'<tr><td>'.$row["name"].'</span></td><td>'.$row["col"].'</td><td>'.mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$row['item'].""),0,0).'</td><td width="140">'.$map_name.' ('.$row["x"].', '.$row["y"].')</td></tr>';
			}
		}
		else
		{
			echo'<tr><td colspan=4>Ничего не найдено</td></tr>';
		}
		echo '</table>';
	}

}

if (function_exists("save_debug")) save_debug(); 

?>