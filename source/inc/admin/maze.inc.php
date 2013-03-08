<?php

if (function_exists("start_debug")) start_debug(); 

if ($adm['map'] >= 1)
{

function generate_maze($id,$_POST)
{
	$dim_x = $_POST['XSize'];
	$dim_y = $_POST['YSize'];
	$cell_count = $dim_x*$dim_y;
	$moves = array();
	// MAZE CREATION
	for($i=0;$i<$cell_count;$i++){
		$maze[$i] = "01111"; // visted, NSEW
	}
	$visited=0;
	$pos = mt_rand(0,$cell_count-1);
	$enter = $pos;
	$maze[$pos]{0} = 1;
	$visited ++;
	// determine possible directions
	while($visited<$cell_count){
		$possible = "";
		if((floor($pos/$dim_x)==floor(($pos-1)/$dim_x)) and ($maze[$pos-1]{0}==0)){
			$possible .= "W";
		}
		if((floor($pos/$dim_x)==floor(($pos+1)/$dim_x)) and ($maze[$pos+1]{0}==0)){
			$possible .= "E";
		}
		if((($pos+$dim_x)<$cell_count) and ($maze[$pos+$dim_x]{0}==0)){
			$possible .= "S";
		}
		if((($pos-$dim_x)>=0) and ($maze[$pos-$dim_x]{0}==0)){
			$possible .= "N";
		}
		if($possible){
			$visited ++;
			array_push($moves,$pos);
			$direction = $possible{mt_rand(0,strlen($possible)-1)};
			switch($direction){
				case "N":
					$maze[$pos]{1} = 0;
					$maze[$pos-$dim_x]{2} = 0;
					$pos -= $dim_x;
				break; 
				case "S":
					$maze[$pos]{2} = 0;
					$maze[$pos+$dim_x]{1} = 0; 
					$pos += $dim_x; 
				break;
				case "E": 
					$maze[$pos]{3} = 0;
					$maze[$pos+1]{4} = 0;
					$pos ++;
				break;
				case "W":
					$maze[$pos]{4} = 0;
					$maze[$pos-1]{3} = 0;
					$pos --;
				break;
			}
			$maze[$pos]{0} = 1;
		}
		else{
			$pos = array_pop($moves);
		}
		$html = "<table style = \"border:2px solid black\"; cellspacing = \"0\" cellpadding = \"0\">";
		$posy=0;
		$posx=0;
		for($x=0;$x<$cell_count;$x++){
			$posx++;
			if($x % $dim_x == 0){
				$html .= "<tr>";
				if ($x!=0) $posy++;
				$posx=0;
			}
			$style = 'style'.$maze[$x]{2}.$maze[$x]{3};
			//$print='<table><tr><td colspan="2" align="center">'.$maze[$x]{1}.'</td></tr><td align="left">'.$maze[$x]{4}.'</td><td align="right">'.$maze[$x]{3}.'</td><tr></tr><tr><td colspan="2" align="center">'.$maze[$x]{2}.'</td></tr></table>';
			$print = "&nbsp;";
			$html.= "<td class = \"$style\" title=\"x-$posx, y-$posy\">$print</td>";
			if(($x % $dim_x) == ($dim_x-1)){
				$html .= "</tr>";
			}
		}
		$html .= "</table>";
	}
	?>
	<style>
	body{line-height:25px;}
	td{text-align:center;}
	.style00{width:20px;height:20px;border:0px;}
	.style01{width:20px;height:20px;border:0px;border-right:2px solid black;}
	.style10{width:20px;height:20px;border:0px;border-bottom:2px solid black;}
	.style11{width:20px;height:20px;border:0px;border-bottom:2px solid black;border-right:2px solid black;}
	strong{color:red;}
	</style>
	<?php 
	if (isset($html)) echo $html; 
	
	if ($_POST['variant']==0)
	{
		$variant = mt_rand(1,3);
	}
	else
	{
		$variant = $_POST['variant'];
	}
	$img = 'lab'.$variant.'/';
	
	$posy=0;
	$posx=0;
	for($x=0;$x<$cell_count;$x++){
		$posx++;
		if($x % $dim_x == 0){
			if ($x!=0) $posy++;
			$posx=0;
		}
		$geks = '';
		if ((1-(int)$maze[$x]{1})==1)
		{
			//доступен проход вверх
			$geks.='0';
		}
		else
		{
			$geks.='x';
		}
		if ($posx==$dim_x-1 AND $posy==$dim_y-1)
		{
			$geks.='V';
		}
		else
		{
			if ((1-(int)$maze[$x]{3})==1)
			{
				//доступен проход вправо
				$geks.='0';
			}
			else
			{
				$geks.='x';
			}
		}
		if ((1-(int)$maze[$x]{2})==1)
		{
			//доступен проход вниз
			$geks.='0';
		}
		else
		{
			$geks.='x';
		}
		if ((1-(int)$maze[$x]{4})==1)
		{
			//доступен проход влево
			$geks.='0';
		}
		else
		{
			$geks.='x';
		}
		$directory = 'images/map/Maze/SQUARE/'.$img;
        //$directory = '../'.$directory;

		$dh = opendir($directory);
		$i=0;
		$ar_geks = Array();
		while($file = readdir($dh))
		{
			if ($file=='.') continue;
			if ($file=='..') continue;
			$len=strlen($file)-4;
			$maze_geksa = substr($file,0,$len);
			if (substr($maze_geksa,0,4)==$geks)
			{
				$ar_geks[]=$maze_geksa;
				$i++;
			}
		}
		if ($i>1 and mt_rand(1,10)>=4)
		{
			$i--;
			$r=mt_rand(0,$i);
			$geks = $ar_geks[$r];
		}
		
		if ($posx==$dim_x-1 AND $posy==$dim_y-1)
		{
			$geks.='_vihod';
		}
		$img_geks=$img.$geks;
		//echo 'posx='.$posx.', posy='.$posy.', geksa='.$img_geks.'<br>';
		myquery("INSERT INTO game_maze (map_name,move_up,move_down,move_left,move_right,xpos,ypos,type,img) VALUES ($id,".(1-(int)$maze[$x]{1}).",".(1-(int)$maze[$x]{2}).",".(1-(int)$maze[$x]{4}).",".(1-(int)$maze[$x]{3}).",$posx,$posy,0,'".$img_geks."')");
	}
	myquery("UPDATE game_maze SET type=1 WHERE map_name=$id AND xpos=0 AND ypos=0");
	myquery("UPDATE game_maze SET type=2 WHERE map_name=$id AND xpos=".($_POST['XSize']-1)." AND ypos=".($_POST['YSize']-1)."");
	//добавим проход
	$str = 'Поздравляю тебя, странник! Теперь ты можешь покинуть пройденный лабиринт! ';
	if ($_POST['exp_win']>0 OR $_POST['gp_win']>0)
	{
		$str.= 'За твою смекалку, мудрость и бесстрашие, проявленное при прохождении лабиринта, ты будешь награжден! Твоя награда: <br>';
		if ($_POST['exp_win']>0)
		{
			$str.= $_POST['exp_win'].' очков опыта <br>';
			
		}
		if ($_POST['gp_win']>0)
		{
			$str.= $_POST['gp_win'].' золотых монет <br>';
			
		}
	}
	myquery("INSERT INTO game_obj (name,town,text,exit_lab,view) VALUES ('Выйти за Лабиринта','labirint".$id."_exit','$str',1,0)");
	$id_prohod=mysql_insert_id();
	myquery("INSERT INTO game_map (name,xpos,ypos,to_map_name,to_map_xpos,to_map_ypos,town) VALUES ($id,".($_POST['XSize']-1).",".($_POST['YSize']-1).",".$_POST['to_map_name'].",".$_POST['to_map_xpos'].",".$_POST['to_map_ypos'].",$id_prohod)");
	//Ну а теперь примочки к лабиринту
	$sel = myquery("SELECT xpos,ypos FROM game_maze WHERE (move_up+move_down+move_left+move_right)<=1 AND map_name=$id AND (xpos<>0 AND ypos<>0) AND (xpos<>$dim_x AND ypos<>$dim_y) AND type=0");
	$already_use = Array();
	$trap_set_count = 0;
	if (isset($_POST['chest_trap_count']) AND $_POST['chest_trap_count']>0)
	{
		for ($i=0;$i<$_POST['chest_trap_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['chest_trap_min'],$_POST['chest_trap_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel);
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use));
				myquery("UPDATE game_maze SET type=4,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
	if (isset($_POST['HP_trap_count']) AND $_POST['HP_trap_count']>0)
	{
		for ($i=0;$i<$_POST['HP_trap_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['HP_trap_min'],$_POST['HP_trap_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel);
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
				myquery("UPDATE game_maze SET type=5,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
	if (isset($_POST['MP_trap_count']) AND $_POST['MP_trap_count']>0)
	{
		for ($i=0;$i<$_POST['MP_trap_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['MP_trap_min'],$_POST['MP_trap_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel);
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
				myquery("UPDATE game_maze SET type=6,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
	if (isset($_POST['STM_trap_count']) AND $_POST['STM_trap_count']>0)
	{
		for ($i=0;$i<$_POST['STM_trap_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['STM_trap_min'],$_POST['STM_trap_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel) ;
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
				myquery("UPDATE game_maze SET type=7,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
	if (isset($_POST['teleport_trap_count']) AND $_POST['teleport_trap_count']>0)
	{
		for ($i=0;$i<$_POST['teleport_trap_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			do
			{
				jump_random_query($sel);
				$pos_array = mysql_fetch_assoc($sel);
				$map_xpos = $pos_array['xpos'];
				$map_ypos = $pos_array['ypos']; 
			} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
			myquery("UPDATE game_maze SET type=11 WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
			$trap_set_count++;        
			$already_use[]=''.$map_xpos.'_'.$map_ypos;
			//добавим проход
			myquery("INSERT INTO game_obj (name,town,text,view) VALUES ('Неизвестный Телепорт','labirint".$id."_teleport','Войди в телепорт! Но куда он ведет?! Никто этого не знает! Не знаешь и ты пока не попробуешь!',0)");
			$id_prohod=mysql_insert_id();
			$new_map_xpos = mt_rand(0,$_POST['XSize']-2);
			$new_map_ypos = mt_rand(0,$_POST['YSize']-2);
			myquery("INSERT INTO game_map (name,xpos,ypos,to_map_name,to_map_xpos,to_map_ypos,town) VALUES ($id,$map_xpos,$map_ypos,$id,$new_map_xpos,$new_map_ypos,$id_prohod)");
		}       
	}
	if (isset($_POST['teleport_null_trap_count']) AND $_POST['teleport_null_trap_count']>0)
	{
		for ($i=0;$i<$_POST['teleport_null_trap_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			do
			{
				jump_random_query($sel);
				$pos_array = mysql_fetch_assoc($sel);
				$map_xpos = $pos_array['xpos'];
				$map_ypos = $pos_array['ypos']; 
			} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
			myquery("UPDATE game_maze SET type=12 WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
			$trap_set_count++;        
			$already_use[]=''.$map_xpos.'_'.$map_ypos;
			//добавим проход
			myquery("INSERT INTO game_obj (name,town,text,view) VALUES ('Неизвестный Телепорт','labirint".$id."_teleport','Войди в телепорт! Но куда он ведет?! Никто этого не знает! Не знаешь и ты пока не попробуешь!',0)");
			$id_prohod=mysql_insert_id();
			myquery("INSERT INTO game_map (name,xpos,ypos,to_map_name,to_map_xpos,to_map_ypos,town) VALUES ($id,$map_xpos,$map_ypos,$id,0,0,$id_prohod)");
		}       
	}
	if (isset($_POST['chest_count']) AND $_POST['chest_count']>0)
	{
		for ($i=0;$i<$_POST['chest_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['chest_min'],$_POST['chest_max']);
			if ($money>0)
			{
				jump_random_query($sel);
				$pos_array = mysql_fetch_assoc($sel);
				$map_xpos = $pos_array['xpos'];
				$map_ypos = $pos_array['ypos']; 
				myquery("UPDATE game_maze SET type=3,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
				$trap_set_count++;
			} 
		}       
	}
	if (isset($_POST['HP_count']) AND $_POST['HP_count']>0)
	{
		for ($i=0;$i<$_POST['HP_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['HP_min'],$_POST['HP_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel);
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
				myquery("UPDATE game_maze SET type=8,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
	if (isset($_POST['MP_count']) AND $_POST['MP_count']>0)
	{
		for ($i=0;$i<$_POST['MP_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['MP_min'],$_POST['MP_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel);
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
				myquery("UPDATE game_maze SET type=9,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
	if (isset($_POST['STM_count']) AND $_POST['STM_count']>0)
	{
		for ($i=0;$i<$_POST['STM_count'];$i++)
		{
			if ($trap_set_count==mysql_num_rows($sel)) break;
			$money = mt_rand($_POST['STM_min'],$_POST['STM_max']);
			if ($money>0)
			{
				do
				{
					jump_random_query($sel) ;
					$pos_array = mysql_fetch_assoc($sel);
					$map_xpos = $pos_array['xpos'];
					$map_ypos = $pos_array['ypos']; 
				} while (in_array(''.$map_xpos.'_'.$map_ypos,$already_use)); 
				myquery("UPDATE game_maze SET type=10,effekt=$money WHERE map_name=$id AND xpos=$map_xpos AND ypos=$map_ypos");
				$trap_set_count++;        
				$already_use[]=''.$map_xpos.'_'.$map_ypos;
			} 
		}       
	}
}

echo '<b><font color=ff0000 size=2 face=verdana> '.GAME_NAME.' :: Генератор лабиринтов</font></b><br><br>';
if (!isset($_REQUEST['do'])) $_REQUEST['do'] = '';
switch ($_REQUEST['do'])
{
	case 'make_newmaze':
		if (isset($_POST['dolina'])) $dolina1='1';if (!isset($_POST['dolina'])) $dolina1='0';
		if (isset($_POST['not_exp'])) $not_exp1='1';if (!isset($_POST['not_exp'])) $not_exp1='0';
		if (isset($_POST['not_gp'])) $not_gp1='1';if (!isset($_POST['not_gp'])) $not_gp1='0';
		if (isset($_POST['not_win'])) $not_win1='1';if (!isset($_POST['not_win'])) $not_win1='0';
		if (isset($_POST['not_lose'])) $not_lose1='1';if (!isset($_POST['not_lose'])) $not_lose1='0';
		if (isset($_POST['boy_type1'])) $boy_type11='1';if (!isset($_POST['boy_type1'])) $boy_type11='0';
		if (isset($_POST['boy_type2'])) $boy_type21='1';if (!isset($_POST['boy_type2'])) $boy_type21='0';
		if (isset($_POST['boy_type3'])) $boy_type31='1';if (!isset($_POST['boy_type3'])) $boy_type31='0';
		if (isset($_POST['boy_type4'])) $boy_type41='1';if (!isset($_POST['boy_type4'])) $boy_type41='0';
		if (isset($_POST['boy_type5'])) $boy_type51='1';if (!isset($_POST['boy_type5'])) $boy_type51='0';
		myquery("INSERT INTO game_maps (name,dolina,not_exp,not_gp,boy_type1,boy_type2,boy_type3,boy_type4,boy_type5,not_win,not_lose,maze,count_npc,exp_maze,gp_maze) VALUES ('".$_POST['name_maze']."','".$dolina1."','".$not_exp1."','".$not_gp1."','".$boy_type11."','".$boy_type21."','".$boy_type31."','".$boy_type41."','".$boy_type51."','".$not_win1."',".$not_lose1.",1,'".$_POST['npc_count']."','".$_POST['exp_win']."','".$_POST['gp_win']."')");
		$id_maze = mysql_insert_id();
		generate_maze($id_maze,$_POST);
		echo 'Лабиринт <span STYLE="font-size:12px;font-weight:bold;color:#FF0000">'.$_POST['name_maze'].'</span> сгенерирован';
	break;
	
	case 'newmaze':
		echo'
		<form name="mazegen" action="""" method="post" autocomplete="off">
		Имя лабиринта: <input type="text" name="name_maze" size="30" maxlength="30"><br />
		Размер лабиринта:
		X - <input type="text" name="XSize" value="0" align="right" size="4" maxlength="3">, Y - <input type="text" name="YSize" value="0" align="right" size="4" maxlength="3"><br />
		(Вход в лабиринт всегда находится в позиции 0,0. Выход - всегда в нижнем правом углу)<br />
		<font color="yellow" face="Courier"><b>ВНИМАНИЕ! Максимальное кол-во ячеек лабиринта = 1600-1800</b></font><br />
		<input name="dolina" type="checkbox" value="dolina"> Долина смерти (Все ограничения на атаку снимаются)<br />
		<input name="not_exp" type="checkbox" value="not_exp"> Не давать на карте опыт за победу<br />
		<input name="not_gp" type="checkbox" value="not_gp"> Не давать на карте деньги за победу<br />
		<input name="not_win" type="checkbox" value="not_win"> Не давать на карте очки WIN за победу<br />
		<input name="not_lose" type="checkbox" value="not_lose"> Не давать на карте очки LOSE за проигрыш<br />
		<input name="boy_type1" type="checkbox" value="boy_type1"> На карте разрешен бой "Обычный бой"<br />
		<input name="boy_type2" type="checkbox" value="boy_type2"> На карте разрешен бой "Дуэль"<br />
		<input name="boy_type3" type="checkbox" value="boy_type3"> На карте разрешен бой "Общий бой"<br />
		<input name="boy_type4" type="checkbox" value="boy_type4"> На карте разрешен бой "Клановый бой"<br />
		<input name="boy_type5" type="checkbox" value="boy_type5"> На карте разрешен бой "Все против всех"<br />
		<table>
		<tr><td colspan="6">
		<input type="radio" name="variant" value="0" checked>Случайный фон лабиринта<br>
		<input type="radio" name="variant" value="1">Фон лабиринта "Землянные туннели"<br>
		<input type="radio" name="variant" value="2">Фон лабиринта "Каменные туннели"<br>
		<input type="radio" name="variant" value="3">Фон лабиринта "Подземелье"<br>
		</td></tr>
		<tr><td colspan="5">Количество ботов в лабиринте (боты создаются при входе в лабиринт отдельно для каждого игрока): </td><td><input type="text" name="npc_count" size="5" maxlength="3" value="0"></td></tr>
		<tr><td>Количество сундуков на карте (плюс монеты): </td><td><input type="text" name="chest_count" size="5" maxlength="5" value="0"></td><td> с золотом от </td><td><input type="text" name="chest_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="chest_max" size="6" maxlength="6" value="0"> монет</td></tr>
		<tr><td>Количество ловушек (минус монеты) на карте: </td><td><input type="text" name="chest_trap_count" size="5" maxlength="5" value="0"></td><td> с минус золота от </td><td><input type="text" name="chest_trap_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="chest_trap_max" size="6" maxlength="6" value="0"> монет</td></tr>
		<tr><td>Количество ловушек (минус жизни) на карте: </td><td><input type="text" name="HP_trap_count" size="5" maxlength="5" value="0"></td><td> с минус HP от </td><td><input type="text" name="HP_trap_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="HP_trap_max" size="6" maxlength="6" value="0"></td></tr>
		<tr><td>Количество ловушек (минус маны) на карте: </td><td><input type="text" name="MP_trap_count" size="5" maxlength="5" value="0"></td><td> с минус MP от </td><td><input type="text" name="MP_trap_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="MP_trap_max" size="6" maxlength="6" value="0"></td></tr>
		<tr><td>Количество ловушек (минус энергии) на карте: </td><td><input type="text" name="STM_trap_count" size="5" maxlength="5" value="0"></td><td> с минус STM от </td><td><input type="text" name="STM_trap_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="STM_trap_max" size="6" maxlength="6" value="0"></td></tr>
		<tr><td>Количество еды HP (плюс жизни) на карте: </td><td><input type="text" name="HP_count" size="5" maxlength="5" value="0"></td><td> с HP от </td><td><input type="text" name="HP_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="HP_max" size="6" maxlength="6" value="0"></td></tr>
		<tr><td>Количество еды MP (плюс маны) на карте: </td><td><input type="text" name="MP_count" size="5" maxlength="5" value="0"></td><td> с MP от </td><td><input type="text" name="MP_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="MP_max" size="6" maxlength="6" value="0"></td></tr>
		<tr><td>Количество еды STM (плюс энергии) на карте: </td><td><input type="text" name="STM_count" size="5" maxlength="5" value="0"></td><td> с STM от </td><td><input type="text" name="STM_min" size="5" maxlength="5" value="0"></td><td> до </td><td><input type="text" name="STM_max" size="6" maxlength="6" value="0"></td></tr>
		</td><td colspan=3>Количество случайных телепортов на карте: </td><td colspan=3><input type="text" name="teleport_trap_count" size="5" maxlength="5" value="0"></td></tr>
		</td><td colspan=3>Количество случайных телепортов в начало лабиринта на карте: </td><td colspan=3><input type="text" name="teleport_null_trap_count" size="5" maxlength="5" value="0"><br /></td></tr>
		<tr><td colspan=6>Выход из лабиринта:<br />
		Карта:
		<select name="to_map_name">';
		$selmap = myquery("SELECT * FROM game_maps ORDER BY name");
		while ($maps = mysql_fetch_array($selmap))
		{
			echo '<option value='.$maps['id'].'>'.$maps['name'].'</option>';
		}
		echo '</select> Координаты  X=<input type="text" name="to_map_xpos" size="5" maxlength="5" value="0"> Y=<input type="text" name="to_map_ypos" size="5" maxlength="5" value="0"></td></tr>
		<tr></td><td colspan=3>Опыт за прохождение лабиринта: </td><td colspan=3><input type="text" name="exp_win" size="15" maxlength="15" value="0"><br /></td></tr>
		<tr></td><td colspan=3>Деньги за прохождение лабиринта: </td><td colspan=3><input type="text" name="gp_win" size="10" maxlength="10" value="0"><br /></td></tr>
		</table><br /><br /><br />
		<input type="hidden" name="do" value="make_newmaze">
		<input type="submit" name="make_maze" value="Выполнить генерацию"></form>
		';
	break;
	
	case 'edit_maze_now':
	
		$img_maze_table='http://'.IMG_DOMAIN.'/race_table/gnom/table';
		echo'<style type="text/css">@import url("../style/global.css");</style><table border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img_maze_table.'_lt.gif"></td><td background="'.$img_maze_table.'_mt.gif"></td><td width="1" height="1"><img src="'.$img_maze_table.'_rt.gif"></td></tr>
		<tr><td background="'.$img_maze_table.'_lm.gif"></td><td background="'.$img_maze_table.'_mm.gif" valign="top"><table><tr><td colspan=2 align="center">';
		
		$map_selected = $_POST['map_selected']; 
		$sel_maze = myquery("SELECT * FROM game_maze WHERE map_name=$map_selected ORDER BY ypos ASC, xpos ASC");
		$maz_name = mysql_result(myquery("SELECT name FROM game_maps WHERE id=$map_selected"),0,0);
		list($max_x,$max_y) = mysql_fetch_array(myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=$map_selected ORDER BY xpos DESC, ypos DESC LIMIT 1"));
		
		echo '<div>Лабиринт: <span STYLE="font-size:12px;font-weight:bold;color:#FF0000">'.$maz_name.'</span>, X-<span STYLE="font-size:12px;font-weight:bold;color:#FFffff">'.($max_x+1).'</span>, Y-<span STYLE="font-size:12px;font-weight:bold;color:#FFffff">'.($max_y+1).'</span></div>';
		
		echo '</td></tr>';
		echo '<tr><td>
		<table cellspacing=0 cellpadding=7><tr><td colspan="4" align="center"><span STYLE="font-size:10px;font-weight:bold;color:#FFFFFF">В лабиринте находится:</span></td></tr>';
		$sel = myquery("SELECT * FROM game_maze WHERE map_name=$map_selected AND type>0 ORDER BY xpos ASC, ypos ASC");
		$i = 0;
		echo '<tr>';
		while ($trap=mysql_fetch_array($sel))
		{
			$i++;
			echo '<td> X-'.$trap['xpos'].', Y-'.$trap['ypos'].' </td><td>';
			switch ($trap['type'])
			{
				case 1:
					echo 'Вход в лабиринт';
				break;
				case 2:
					echo 'Выход из лабиринта';
				break;
				case 3:
					echo 'Сундук (+'.$trap['effekt'].' золота)';
				break;
				case 4:
					echo 'Сундук ЛОВУШКА (-'.$trap['effekt'].' золота)';
				break;
				case 5:
					echo 'HP ЛОВУШКА (-'.$trap['effekt'].' HP)';
				break;
				case 6:
					echo 'MP ЛОВУШКА (-'.$trap['effekt'].' MP)';
				break;
				case 7:
					echo 'STM ЛОВУШКА (-'.$trap['effekt'].' STM)';
				break;
				case 8:
					echo 'HP (+'.$trap['effekt'].' HP)';
				break;
				case 9:
					echo 'MP (+'.$trap['effekt'].' MP)';
				break;
				case 10:
					echo 'STM (+'.$trap['effekt'].' STM)';
				break;
				case 11:
					echo 'Телепорт в случайные координаты';
				break;
				case 12:
					echo 'Телепорт в начало лабиринта';
				break;
			}
			echo '</td>';
			if ($i==3)
			{
				echo '</tr><tr>';
				$i=0;
			}
		}
		if ($i!=0) {echo '</tr>';};
		echo '</table></td></tr>';
		echo '<tr><td>';
		$cur_y = -1;
		while ($maze = mysql_fetch_array($sel_maze))
		{
			if ($cur_y!=$maze['ypos'])
			{
				echo '<br>';
				$cur_y = $maze['ypos'];
			}
			echo '<img src="http://'.IMG_DOMAIN.'/map/Maze/SQUARE/'.$maze['img'].'.gif" width="32" height="32" border="0" title="X-'.$maze['xpos'].', Y-'.$maze['ypos'].'">';
		}
		echo '</td></tr></table>';
		
		$sel = myquery("SELECT type,COUNT(*) AS kol,MIN(effekt) AS min_effekt,MAX(effekt) AS max_effekt FROM game_maze WHERE map_name=$map_selected AND type>0 GROUP BY type ORDER BY type");
		QuoteTable('open');
		echo '<ol>';
		while ($trap = mysql_fetch_array($sel))
		{
			echo '<li>';
			switch ($trap['type'])
			{
				case 1:
					echo 'Вход в лабиринт';
				break;
				case 2:
					echo 'Выход из лабиринта';
				break;
				case 3:
					echo 'Сундук (+ золота от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 4:
					echo 'Сундук ЛОВУШКА (- золота от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 5:
					echo 'HP ЛОВУШКА (- HP от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 6:
					echo 'MP ЛОВУШКА (- MP от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 7:
					echo 'STM ЛОВУШКА (- STM от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 8:
					echo 'HP (+ HP от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 9:
					echo 'MP (+ MP от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 10:
					echo 'STM (+ STM от '.$trap['min_effekt'].' до '.$trap['max_effekt'].')';
				break;
				case 11:
					echo 'Телепорт в случайные координаты';
				break;
				case 12:
					echo 'Телепорт в начало лабиринта';
				break;
			}
			echo ' - '.$trap['kol'].'</li>';
		}
		echo '</ol>';
		QuoteTable('close');
		
		echo'</td><td background="'.$img_maze_table.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img_maze_table.'_lb.gif"></td><td background="'.$img_maze_table.'_mb.gif"></td><td width="1" height="1"><img src="'.$img_maze_table.'_rb.gif"></td></tr></table>';
		
	break;
	
	case 'editmaze':
		echo '<font color="#eeeeee"><br>Редактирование лабиринта</font><br>';
		$result = myquery("SELECT * FROM game_maps WHERE maze=1 ORDER BY name");
		if (mysql_num_rows($result) != 0)
		{
			echo '
			<form method="post" action="admin.php?opt=main&option=maze">
			<input type="hidden" name="do" value="edit_maze_now">
			<table cellpadding="0" cellspacing="4" border="0">';
			while ($map = mysql_fetch_array($result))
			{
				$cou = mysql_result(myquery("SELECT COUNT(*) FROM game_users_map WHERE map_name=".$map['id'].""),0,0);
				echo '<tr><td><input type="radio" name="map_selected" value="' . $map['id'] . '"></td><td>' . $map['name'] . '';
				if ($cou>0) echo '&nbsp;&nbsp;&nbsp; (на карте находится '.$cou.' '.pluralForm($cou, 'игрок', 'игрока', 'игроков').')';
				echo '</td></tr>';
			}
			echo '
			<tr><td colspan="2"><div align="right"><input type="submit" value="Редактировать" class="inputbutton"></div></td></tr>
			</table>
			</form>';
		}
		else
		{
			echo 'Ошибка<br>';
		}
	break;
	
	case 'delete_maze_now':
		echo '<font color="#eeeeee"><br>Удаление Лабиринта</font><br><br />';
		$map_selected = $_POST['map_selected'];
		$map_name = @mysql_result(@myquery("SELECT name FROM game_maps WHERE id=$map_selected"),0,0);
		$result = myquery("DELETE FROM game_maze WHERE map_name='$map_selected'");
		$result = myquery("DELETE FROM game_maps WHERE id='$map_selected'");
		$result = myquery("DELETE FROM game_map WHERE name='$map_selected'");
		myquery("DELETE FROM game_obj WHERE town='labirint".$map_selected."_exit'");
		myquery("DELETE FROM game_obj WHERE town='labirint".$map_selected."_teleport'");
		myquery("DELETE FROM game_npc WHERE map_name=$map_selected");
		myquery("DELETE FROM game_items WHERE map_name=$map_selected");
		echo 'Лабиринт "<b><font color="#eeeeee">' . $map_name . '</font></b>" удален.<br>';
		$result = myquery("OPTIMIZE TABLE game_maze");
		break;

	break;
	
	case 'delmaze':
		echo '<font color="#eeeeee"><br>Удаление лабиринта</font><br>';
		$result = myquery("SELECT * FROM game_maps WHERE maze=1 ORDER BY name");
		if (mysql_num_rows($result) != 0)
		{
			echo '
			<form method="post" action="admin.php?opt=main&option=maze">
			<input type="hidden" name="do" value="delete_maze_now">
			<table cellpadding="0" cellspacing="4" border="0">';
			while ($map = mysql_fetch_array($result))
			{
				$cou = mysql_result(myquery("SELECT COUNT(*) FROM game_users_map WHERE map_name=".$map['id'].""),0,0);
				echo '<tr><td><input type="radio" name="map_selected" value="' . $map['id'] . '"></td><td>' . $map['name'] . '';
				if ($cou>0) echo '&nbsp;&nbsp;&nbsp; (на карте находится '.$cou.' '.pluralForm($cou, 'игрок', 'игрока', 'игроков').')';
				echo '</td></tr>';
			}
			echo '
			<tr><td colspan="2"><div align="right"><input type="submit" value="Удалить" class="inputbutton"></div></td></tr>
			</table>
			</form>';
		}
		else
		{
			echo 'Ошибка<br>';
		}
	break;
	
	default:
		echo '&nbsp;&nbsp;&nbsp;Выберите действие: <br><br>';
		echo '&nbsp;&nbsp;&nbsp;<a href="?opt=main&option=maze&do=newmaze">Генерация нового лабиринта</a><br><br>';
		echo '&nbsp;&nbsp;&nbsp;<a href="?opt=main&option=maze&do=editmaze">Просмотр лабиринта</a><br><br>';
		echo '&nbsp;&nbsp;&nbsp;<a href="?opt=main&option=maze&do=delmaze">Удаление лабиринта</a><br><br>';
	break;
}

}

if (function_exists("save_debug")) save_debug(); 

?>