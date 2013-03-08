<?Php
/*$dirclass = "../../class";
require_once('../../inc/engine.inc.php');
require_once('../../inc/xray.inc.php');
require_once('../../inc/lib.inc.php');
require_once('../../inc/lib_session.inc.php');*/


function return_color($n)
{
	switch ($n)
	{
		case 0: return "black"; break;
		case 1: return "red"; break;
		case 2: return "orange"; break;
		case 3: return "yellow"; break;
		case 4: return "green"; break;
		case 5: return "aqua"; break;
		case 6: return "blue"; break;
		case 7: return "Fuchsia"; break;
		case 8: return "Purple"; break;
		case 9: return "Olive"; break;
	}
}


function return_filename($n)
{
	switch ($n)
	{
		case 0: return "empty"; break;
		case 1: return "red"; break;
		case 2: return "orange"; break;
		case 3: return "yellow"; break;
		case 4: return "green"; break;
		case 5: return "cian"; break;
		case 6: return "blue"; break;
		case 7: return "rosy"; break;
		case 8: return "purple"; break;
		case 9: return "hacky"; break;
	}
}

function print_sud()
{
	?>	
	<CENTER>	
	<HR height=1 weight=90% align=center><BR>
	<TABLE>
	<tr valign="middle">
	<td><TABLE align="center" bgcolor="#2F2F2F" border="5" cellspacing="0" cellpadding="0" height="306" width="306" >
	<?PHP
	if(!isset($_SESSION['sud']) OR !isset($_SESSION['use']))	
		read_data();		
		
	for($k=0;$k<3;$k++)
	{
		echo '<tr>';
		for($l=0;$l<3;$l++)
		{		
			echo '<td>';
			echo '<TABLE  align="center" bgcolor="#2F2F2F" border="1" cellspacing="0" cellpadding="0" height="102" width="102" bordercolor="#FFD800">';			
			for($i=$k*3;$i<=$k*3+2;$i++)
			{
				//&#8226; - bull
				echo '<tr>';
				for($j=$l*3;$j<=$l*3+2;$j++)
				{
					if(!isset($_SESSION['use'][$i][$j]))					
						echo '<td valign="middle" bgcolor='.return_color($_SESSION['sud'][$i][$j]).' align="center"><font size=5 color='.return_color($_SESSION['sud'][$i][$j]).'><IMG src="http://'.IMG_DOMAIN.'/quest/sudoku/dark_'.return_filename($_SESSION["sud"][$i][$j]).'.jpg" alt="X"></td>';						
					else 
					{
						if(isset($_SESSION['sud']['change']) AND $_SESSION['sud']['change']['i']==$i AND $_SESSION['sud']['change']['j']==$j)
							$img_pre="ch_light";
						else 
							$img_pre="light";	
						echo '<td bgcolor='.return_color($_SESSION['sud'][$i][$j]).' valign="middle" align="center" onClick=location.href="?mode=601&change&i='.$i.'&j='.$j.'"><font size=5 color='.return_color($_SESSION['sud'][$i][$j]).'><IMG src="http://'.IMG_DOMAIN.'/quest/sudoku/'.$img_pre.'_'.return_filename($_SESSION["sud"][$i][$j]).'.jpg" alt="&#8226;"></td>';
					}
				}
				echo '</tr>';
			}			
			echo '</table>';
			echo '</td>';
		}
		echo '</tr>';		
	}
	
	?>	
	</TABLE>
	</td>
	<?PHP
	
	
		/*$i=0;
		$onClick='location.href="?change&to_color='.$i.'"';
	}else $onClick="";*/
	
	echo '<td><TABLE align="center" bgcolor="#202020" width="132" border="0" cellspacing="1" cellpadding="0">  
	<tr><td colspan="3" bgcolor=#303141 align="center"><font color="#e9c9a0">Выберите цвет:</font></td>';
	for($i=1; $i<10; $i++)
	if(isset($_SESSION['sud']['change']))
	{
		echo '<tr align=center><TD>&nbsp;</TD><td height="33" width="25%" onClick=location.href="?mode=601&change&to_color='.$i.'" bgcolor="'.return_color($i).'"><IMG src="http://'.IMG_DOMAIN.'/quest/sudoku/light_'.return_filename($i).'.jpg" alt="&#8226;"></td><TD>&nbsp;</TD></tr>';
	
	}else echo '<tr align=center><TD>&nbsp;</TD><td height="33" width="25%" bgcolor="'.return_color($i).'"><IMG src="http://'.IMG_DOMAIN.'/quest/sudoku/light_'.return_filename($i).'.jpg" alt="&#8226;"></td><TD>&nbsp;</TD></tr>';
	echo '</table></td>';
	
	?>
	</FONT>
	</CENTER>	 
	</tr></TABLE>
	<?PHP
	echo '<HR height=1 weight=90% align=center><BR>';
}

function check_sud()
{
	global $char,$user_id;
	if(!isset($_SESSION['sud']) OR !isset($_SESSION['use']))	
		read_data();	
	
	$nums=range(1,9);
	$ch=0;
	for($i=0;$i<9;$i++)
		{		
			for($j=0;$j<9;$j++)
			{	
					$nu=0;	
					//массив вертикали и горизонтали
					$ver=$_SESSION['sud'][$i];
					$hor=array($_SESSION['sud'][0][$j],$_SESSION['sud'][1][$j],$_SESSION['sud'][2][$j],$_SESSION['sud'][3][$j],$_SESSION['sud'][4][$j],$_SESSION['sud'][5][$j],$_SESSION['sud'][6][$j],$_SESSION['sud'][7][$j],$_SESSION['sud'][8][$j]);
					//сформируем массив значений данного квадрата
					$kvad=array();
					$fk=floor($i/3)*3; $tk=$fk+2;
					$fm=floor($j/3)*3; $tm=$fm+2;
					for($k=$fk;$k<=$tk;$k++)
					for($m=$fm;$m<=$tm;$m++)			
					{	
						$kvad[$nu]=$_SESSION['sud'][$k][$m];
						$nu++;
					}
					$v_check=array_count_values($ver);
					$h_check=array_count_values($hor);					
					$k_check=array_count_values($kvad);															
					foreach ($v_check as $v) 
					{
				    	if($v>1) $ch=-1; break;
					}
					if($ch==-1) break;	
					foreach ($h_check as $v) 
					{
				    	if($v>1) $ch=-1; break;
					}
					if($ch==-1) break;	
					foreach ($k_check as $v) 
					{
				    	if($v>1) $ch=-1; break;
					}
					if($ch==-1) break;	
					//if(in_array(2,$v_check) OR in_array($k,$h_check) OR in_array($k,$k_check))  {$ch=-1; break;} 
			}
			if($ch==-1) {break;}
		}		
		echo '<center>';	
		echo '<br>';
		QuoteTable('open');
		if($ch==0)
		{
			//QuoteTable('open');
			echo '<div align=center><font color=green size=4>Сделано! Задание выплонено!</font>
			<br><a href="?mode=601&exit_puzzle">Продолжить</a></div>';
			//QuoteTable('close');	
			myquery("UPDATE quest_engine_users SET done=1 WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
			echo '<meta  http-equiv="refresh" content="2;url=?mode=601&exit_puzzle" tagert="game">';			
			/*echo '</p>';
			OpenTable('close');
			exit();			*/
		}else 
		{
			list($ers)=mysql_fetch_array(myquery("SELECT par4_value FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos'].""));		
			myquery("UPDATE quest_engine_users SET par4_value=par4_value+1  WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
			//QuoteTable('open');
			if($ers==0)
			{
				echo '<div align=center><font color=orange size=4>Неверная комбинация! Первая ошибка!</font>
				<br><a href="?mode=601">Продолжить</a></div>';
				echo '<meta  http-equiv="refresh" content="2;url=?mode=601" tagert="game">';
				//QuoteTable('close');		
				/*echo '</p>';
				OpenTable('close');
				exit();		*/
			}
			else
			{
				echo '<div align=center><font color=red size=4>Неверная комбинация! Вторая ошибка! Задание провалено!</font>
				<br><a href="?mode=601&exit_puzzle">Продолжить</a></div>';
				myquery("UPDATE quest_engine_users SET done=2 WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
				echo '<meta  http-equiv="refresh" content="2;url=?mode=601&exit_puzzle" tagert="game">';
				//QuoteTable('close');		
				/*echo '</p>';
				OpenTable('close');
				exit();		*/
			}			
			
		}
		
		QuoteTable('close');	
		echo '</p>';
		OpenTable('close');
		echo '</center>';
		exit();	
}	

function init_sudoku()
{	
	$ch=0;	
	$nums=range(1,9);	
	shuffle($nums);
	while ($ch==0)
	{		
		for($i=0;$i<9;$i++)		
			$_SESSION['sud'][$i]=array_fill(0,9,0);			
		for($i=0;$i<9;$i++)
		{		
			for($j=0;$j<9;$j++)
			{	
					$nu=0;	
					$vernhor=array_merge($_SESSION['sud'][$i],array($_SESSION['sud'][0][$j],$_SESSION['sud'][1][$j],$_SESSION['sud'][2][$j],$_SESSION['sud'][3][$j],$_SESSION['sud'][4][$j],$_SESSION['sud'][5][$j],$_SESSION['sud'][6][$j],$_SESSION['sud'][7][$j],$_SESSION['sud'][8][$j]));
					//сформируем массив значений данного квадрата
					$kvad=array();
					$fk=floor($i/3)*3; $tk=$fk+2;
					$fm=floor($j/3)*3; $tm=$fm+2;
					for($k=$fk;$k<=$tk;$k++)
					for($m=$fm;$m<=$tm;$m++)			
					{	
						$kvad[$nu]=$_SESSION['sud'][$k][$m];
						$nu++;
					}
					//my array_diff - сформируем массивы допустимых чисел
					$a1=array(); $a2=array();
					for($a=0;$a<9;$a++)
						if(!in_array($nums[$a],$vernhor)) $a1[count($a1)]=$nums[$a];
					for($a=0;$a<9;$a++)
						if(!in_array($nums[$a],$kvad)) $a2[count($a2)]=$nums[$a];						
					//my array_intersect - объединим массивы допустимых чисел (пересечение)
					$fin=array();
					for($a=0;$a<count($a1);$a++)
						if(in_array($a1[$a],$a2)) $fin[count($fin)]=$a1[$a];
					//удалим дублирующиеся значения
						$from=array_unique($fin);	
					//если не осталось в массиве ни одного элемента - попытка провалена			
					if(count($from)==0) {$ch=-1; break;}
					//выберем рандомный элемент	
					$n=mt_rand(0,count($from)-1);							
					$k=$from[$n];										
					unset($from);
					//засунем его в соответствующую ячейку
					$_SESSION['sud'][$i][$j]=$k;
			}
			if($ch==-1) {$ch=-2; break;}
		}
		if($ch==-2) {$ch=0; continue;} else $ch=1;		
	}	
	if(isset($_SESSION['use'])) unset($_SESSION['use']);
	for($k=0;$k<3;$k++)
	for($l=0;$l<3;$l++)
	{		
		if(empty($_SESSION['dop']['num']))
			$_SESSION['dop']['num']=mt_rand(2,5);
			
		while (1)
		{
			$ch=0;
			while ($ch==0)	
			{
				$i=mt_rand($k*3,$k*3+2);
				$j=mt_rand($l*3,$l*3+2);
				if(!isset($_SESSION['use'][$i][$j])) $ch=1; else $ch=0;
			}
			$_SESSION['use'][$i][$j]=1;
			$_SESSION['sud'][$i][$j]=0;
			$_SESSION['dop']['num']--;
		if($_SESSION['dop']['num']==0) break;
		}
	}
	unset($_SESSION['dop']);	
	//ID данных для квеста
   /* $ids=myquery("SELECT id FROM quest_engine_data ORDER BY id DESC");
    if(mysql_num_rows($ids)<0)
    	$last_id=0;
    else
     	list($last_id)=mysql_fetch_array($ids);
    $new_id=$last_id+1;
    myquery("UPDATE quest_engine_users SET par1_name='".$new_id."'");*/
	write_data();
}

function write_data()
{
	global $user_id,$char;
	//проверим, что перс там, где надо
	$id=(myquery("SELECT par1_name FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos'].""));
	if(mysql_num_rows($id)<=0)
	{
		if(isset($_SESSION['sud'])) unset($_SESSION['sud']);
		echo '<div align=center><font color=red size=4>Вы находитесь не в том месте!</font>
				<br><a href="?mode=601&exit_puzzle">Продолжить</a></div>';
		echo '<meta  http-equiv="refresh" content="2;url=?mode=601&exit_puzzle" tagert="game">';
	}
	else list($id)=mysql_fetch_array($id);
	//удалим старые записи
	/*myquery("DELETE FROM quest_engine_data WHERE id=".$id." AND user_id=".$user_id." AND quest_type=601");
	for($i=0;$i<9;$i++)
	for($j=0;$j<9;$j++)
	{		
		$value=$_SESSION['sud'][$i][$j];
		if(isset($_SESSION['use'][$i][$j])) 
			$option='use';
		else 
			$option='static';	
			
		$up_in=myquery("INSERT INTO quest_engine_data ( id , user_id , quest_type , x , y , value , doption ) VALUES (".$id.",".$user_id.",601,".$i.",".$j.",".$value.",'".$option."')") or die('QE item.'.mysql_error());
	}*/
	$field='';
	$field2='';	
	for($i=0;$i<9;$i++)
	for($j=0;$j<9;$j++)
	{		
		$field.=$_SESSION['sud'][$i][$j];		
		if(isset($_SESSION['use'][$i][$j])) 
		{
			$field2.=''.$i.''.$j.',';			
		}
	}
	myquery("UPDATE quest_engine_users set par1_name='".$field."',par2_name='".$field2."' WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
}

function read_data()
{
	global $user_id,$char;
	$id=(myquery("SELECT par1_name FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos'].""));
	if(mysql_num_rows($id)<=0)
	{
		if(isset($_SESSION['sud'])) unset($_SESSION['sud']);
		echo '<div align=center><font color=red size=4>Вы находитесь не в том месте!</font>
				<br><a href="?mode=601&exit_puzzle">Продолжить</a></div>';
		echo '<meta  http-equiv="refresh" content="2;url=?mode=601&exit_puzzle" tagert="game">';
	}
	else list($id)=mysql_fetch_array($id);
	
	/*$qdata=myquery("SELECT * FROM quest_engine_data WHERE id=".$id." AND user_id=".$user_id." AND quest_type=601")or die('365. '.mysql_error());
	if(mysql_num_rows($qdata)<=0)
	{
		init_sudoku();
		$qdata=myquery("SELECT * FROM quest_engine_data WHERE id=".$id." AND user_id=".$user_id." AND quest_type=601")or die('370. '.mysql_error());
	}
	while ($data=mysql_fetch_array($qdata))
	{
		$i=$data['x'];
		$j=$data['y'];
		$_SESSION['sud'][$i][$j]=(int)$data['value'];
		if($data['doption']=='use')
			$_SESSION['use'][$i][$j]=1;
	}*/
	
	
	//myquery("UPDATE quest_engine_users set par1_name='".$field."',par2_name='".$field2."' WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
	if (isset($_SESSION['sud'])) unset($_SESSION['sud']);
	if (isset($_SESSION['use'])) unset($_SESSION['use']);
	$data=myquery("SELECT par1_name FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
		list($data)=mysql_fetch_array($data);
		for($i=0;$i<9;$i++)
		for($j=0;$j<9;$j++)
		{
			$_SESSION['sud'][$i][$j]=(int)$data[$i*9+$j];
		}
		
		$data=myquery("SELECT par2_name FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
		list($data)=mysql_fetch_array($data);
		for($i=0;$i<9;$i++)
		for($j=0;$j<9;$j++)
		{
			$ch=''.$i.''.$j.','; 
			if(substr_count($data,$ch)>0)
				$_SESSION['use'][$i][$j]=1;
		}
}

OpenTable('title');
echo '<p align=left>';
//выход в СЗ
if(isset($exit_puzzle))
{
	//../../main.php?func=main
	if (isset($_SESSION['sud'])) unset($_SESSION['sud']);
	//myquery("update game_users set func='',hod=0,delay_reason='Игра' where user_id=".$user_id."");
	ForceFunc($user_id,5);
	set_delay_reason_id($user_id,1);
	echo '<script>location.replace("../../act.php?func=main")</script>';//top.window.frames.game.
	echo '</p>';
	OpenTable('close');
	exit();
}//elseif($char['func']!='qengine_puzzle') 
	//myquery("update game_users set func='qengine_puzzle',hod=0,delay_reason='Решает головоломку' where user_id=".$user_id."");
	
$quest=mysql_fetch_array(myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos'].""));
//если задание уже выполнено
if($quest['done']==1 OR $quest['done']==2)
{
	QuoteTable('open');
	echo '<div align=center><font color=red size=4>Здесь ничего нет.</font></div>';
	QuoteTable('close');
	echo '</p>';
	OpenTable('close');
	//?
	echo '<meta  http-equiv="refresh" content="1;url=?mode=601&exit_puzzle" tagert="game">';
	exit();
}
//проверим положение игрока
$check=myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']." AND done!=1");
if(mysql_num_rows($check)==0)
{
	QuoteTable('open');
	echo '<div align=center><font color=red size=4>Вы находитесь не в том месте!</font></div>';
	QuoteTable('close');
	echo '</p>';
	OpenTable('close');
	//?
	echo '<meta  http-equiv="refresh" content="1;url=?mode=601&exit_puzzle" tagert="game">';
	exit();
}
//проверим, инициализировано ли поле
if($quest['done']==0)
{
	init_sudoku();
	myquery("UPDATE quest_engine_users SET done=3 WHERE user_id='$user_id' AND quest_type=601 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']."");
}
//повеоим наличие сессий
if(!isset($_SESSION['sud']) OR !isset($_SESSION['use']))
{
	read_data();
}
//проверим на действия игрока
if(isset($change))
{
	if(isset($_SESSION['sud']['change'])) 
		if(isset($to_color))
		{
			$_SESSION['sud'][$_SESSION['sud']['change']['i']][$_SESSION['sud']['change']['j']]=$to_color;
			unset($_SESSION['sud']['change']);
			write_data();
		}elseif(!isset($i) AND !isset($j)) unset($_SESSION['sud']['change']); 
	if (isset($i) AND isset($j))
		{
			$_SESSION['sud']['change']['i']=$i;
			$_SESSION['sud']['change']['j']=$j;	
		}
	//echo '<meta  http-equiv="refresh" content="0;url=?mode=601">';
}
if(isset($check_it))
	check_sud();
//выведем на экран
	print_sud();
//echo '<script>top.window.frames.game.location.replace("../main.php?func=main")</script>';
//echo '<BR><BR><BR>';		
//варианты действий
//QuoteTable('open');
echo '<center>';
echo '<font color=#FF0000><a href="?mode=601&check_it"> Опробовать комбинацию. </a><BR>';		
echo '<font color=#FF0000><a href="?mode=601&exit_puzzle" target="game"> Выйти. </a>';		
//QuoteTable('close');		
echo '</center>';
echo '</p>';;
OpenTable('close');
//OpenTable('close');
//echo '<script>top.window.frames.game.location.replace("?exit_puzzle")</script>';
//include("../../inc/template_footer.inc.php");
?>
