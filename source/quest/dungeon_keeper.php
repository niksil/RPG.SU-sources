<?Php
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/lib_session.inc.php');
echo"<html>
<head>
<title>".GAME_NAME."</title>";
?>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="">
<meta name="Keywords" content="">
<style type="text/css">@import url("../style/global.css");</style>
</head>
<?
//массивчик для быстрого извлечения номера текущего уровня подземелья - ИЗМЕНИТЬ ПРИ ДОБАВЛЕНИИ!!!
$map_level_id=array(691=>1,692=>2,804=>3); 
//проверим, что перс находится на нулевых коордах одной из карт подземелий - ИЗМЕНИТЬ ПРИ ДОБАВЛЕНИИ!!!
if(($char['map_name']==691 OR $char['map_name']==692 OR $char['map_name']==804) AND $char['map_xpos']==0 AND $char['map_ypos']==0)
{
	?>
	<table width="100%"><tr><td width="256">
	<img src="http://<?=IMG_DOMAIN;?>/nav/dungeon_keeper.gif" align="middle" height="400" width="256"></td><td>
	<?
	OpenTable('title',"100%","400");
	echo'<br><center><font size=4 face=verdana color=#fce66b>Аванпост Гномов</font><br><br>';
	echo '<hr align=center size=2 width=80%>';
	
	//если хочет выйти в СЗ, спросим
	if (isset($_GET['exit']))
	{
		echo '<br>';
		echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify>';
		echo '<font color=#ff4433><b>"Если ты выйдешь из подземелий, то сможешь вернуться сюда не ранее, чем через 10 часов"</b>, - предупредил тебя охранник на выходе. - <b>"Ты точно хочешь выйти на поверхность?"</b></font><br><br>';
		echo '</p></tr></td></table>';
		echo '<hr align=center size=2 width=80%>';
		echo '<br><a href="?do_exit" target="game">Да, я хочу выйти из подземелий в Средиземье</a><br>';
		echo '<br><a href="?begin" target="game">Нет, я '.echo_sex('передумал','передумала').'</a><br><br>';
	}
	//если и правда хочет - выйдем
	elseif (isset($_GET['do_exit']))
	{
		myquery("UPDATE dungeon_users_data SET last_visit=".time()." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_map SET map_xpos=25,map_ypos=20,map_name=18 WHERE user_id=$user_id");
		setLocation("../act.php");
	}
	//добавим в БД информацию о задании
	elseif (isset($_GET['task']) and isset($_SESSION['dungeon']['quest_id']))
	{
		$quest_id=$_SESSION['dungeon']['quest_id'];
		$level=$map_level_id[$char['map_name']];
		include("dungeon_inc/dungeon_quests.php");
		myquery("UPDATE dungeon_users_data SET current_quest=".$quest_id." WHERE user_id=".$user_id."");
		for($i=1; $i<=count($quests[$level][$quest_id]['res']); $i++)
		{
			$id=$quests[$level][$quest_id]["res"][$i]["id"];
			$col=$quests[$level][$quest_id]["res"][$i]["kol"];
			myquery("INSERT INTO dungeon_users_progress (user_id,res_id,res_num) VALUES (".$user_id.",".$id.",".$col.")");
		}
		unset($_SESSION['dungeon']['quest_id']);
		setLocation("?talk");
	}
	//начало разговора с Хранителем
	elseif (isset($_GET['talk']))
	{
		echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify>';
		if ($char['map_name']==691)
		{
			echo '<font color=#aaffa8>Приветствую тебя, '.echo_sex('отважный исследователь','отважная исследовательница').'! Я очень рад, что ты '.echo_sex('решил','решила').' внести свой вклад в освобождение наших чудесных шахт от мерзких чудовищ, которые захватили их. Надеюсь, ты хорошо '.echo_sex('подготовился','подготовилась').' к походу, так как в глубинах шахт тебя будут подстерегать различные опасности, но если тебя не пугают трудности, то ты можешь приступить к освобождению наших шахт. Некоторые монстры особо досаждают нам, и если ты пожелаешь, то можешь получить у меня задание на их уничтожение. Однако я не очень доверчив к посторонним, и чтоб я был уверен, что ты добросовестно выполняешь мои задания, тебе придется приносить какие-нибудь подтверждения их выполнения, например, что-то с тела убитого монстра. Если ты серьезно '.echo_sex('настроен','настроена').' помочь нам и сможешь выполнить все мои задания, тогда имя твое будет покрыто славой и ты получишь достойную настоящего чемпиона награду! Вперед!</font>';
		}
		elseif ($char['map_name']==692)
		{
			echo '<font color=#aaffa8>Приветствую тебя, '.echo_sex('отважный исследователь','отважная исследовательница').'! Я рад что ты снова '.echo_sex('посетил','посетила').' подземелья Мории. После того как ты '.echo_sex('освободил','освободила').' Первый Уровень подземелий, стало намного спокойнее, но теперь нам снова очень нужна твоя помощь. Как ты знаешь, в подземельях Мории мы добываем редкий черный камень. Недавно вглубь второго уровня была отправлена экспедиция для добычи черного камня. Мы долго ждали их возвращения, но никаких вестей не было. Самые лучшие из наших воинов отправились на их поиски, но вернулся только один. Он был весь покрыт ужасными ранами и вскоре умер в мучениях, но перед смертью успел нам рассказать ужасные вещи. Орды нечисти заполонили весь Второй Уровень подземелья. Ядовитые пауки, живые мертвецы и прочие дьявольские создания убивают всех, кто осмелится войти вглубь второго уровня. Это все козни злых демонов глубин. Эх, добраться бы до них и всыпать им по первое число!!! Жаль, я уже не тот, что был в молодости. Вся надежда только на таких героев, как ты. Помоги нам освободить второй уровень, и ты получишь достойное вознаграждение!</font>';
		}
		elseif ($char['map_name']==804)
		{
			echo 'Черт возьми! Ты по-настоящему крепкий орешек! Я даже не знаю '.echo_sex('герой','героиня').' ты или '.echo_sex('безумец','безумная').', но я никогда бы не согласился пройти через все то, что '.echo_sex('прошел','прошла').' ты. Дашь автограф для дочурки? Ладно, ладно, не нервничай, прекращаю трепаться, вечно ты торопишься навстречу смерти. В-общем, на третьем уровне оказалось еще хуже, чем на втором. Старатели рассказывают о каких-то адских каменных порождениях, удары которых прошибают любую защиту. Не хотел бы я с ними встретиться. Но ты конечно другое дело, руки наверняка уже чешутся. Не знаю даже что нам придется придумать для того, чтобы наградить тебя, если ты освободишь 3 уровень, но мы уж что-то придумаем. Однако, сдается мне что на этот раз у тебя все же не хватит сил на это и гнить твоим косточкам в каком-нибудь темном и сыром коридоре. Получай задание и вперед! Если что,- кричи! Мы хоть будем знать куда не надо соваться. Да упокоит господь твою грешную душу!';
		}
		echo '</p></tr></td></table>';
		echo '<hr align=center size=2 width=80%>';
		echo '<br><a href="?choice=1" target="game">Получить задание</a>';
		echo '<br><a href="?choice=2" target="game">Просмотреть свое текущее задание</a>';
		echo '<br><a href="?choice=3" target="game">Отчитаться о выполнении задания (сдать ресурсы)</a>';
		echo '<br><a href="?begin" target="game">Закончить разговор</a><br><br>';
	}
	//если выбран один из пунктов разговора или идет сдача ресурсов
	elseif (isset($_GET['choice']) OR (isset($_POST['choice']) AND $_POST['choice']==3))
	{
		$level=$map_level_id[$char['map_name']];
		if(isset($_GET['choice'])) $choice = $_GET['choice'];
		else $choice=3;
		//перс берет квест
		if ($choice==1)
		{
			//проверим, нет ли у перса уже задания
			list($current_quest)=mysql_fetch_array(myquery("SELECT current_quest FROM dungeon_users_data WHERE user_id=".$user_id.""));
			if($current_quest!=0)
			{
				//cкажем, что квест уже есть
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
					echo '<font color=#aaffa8><b>Подожди-ка</b>, - хранитель подземелья сверился со своими записями. - <b>Но ты уже выполняешь наше задание! Сначала выполни одно, а потом уже получишь другое.</b></font><br><br>';
					echo '</p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?talk" target="game">Вернуться</a><br><br>';
			}else 
			{
				//выдача заданий
				//0 - посмотрим, на какой уровень уже есть доступ
				list($current_level)=mysql_fetch_array(myquery("SELECT current_level FROM dungeon_users_data WHERE user_id=".$user_id.""));
				//cколько заданий на каждом уровне
				include("dungeon_inc/dungeon_level_count.php");
				//если у перса есть задания на этом уровне
				if($current_level==$level AND $quests_num[$level]>0)
				{
					//1 - определим, какие задания перс еще не выполнил
					$level_quests=range(1,$quests_num[$level]);
					//сначала посмотрим, какие выполнил					
					$dones=myquery("SELECT quest_id FROM dungeon_quests_done WHERE user_id=".$user_id."");
					$done_quests=array();
					while (list($done)=mysql_fetch_array($dones))
					{
						$done_quests[count($done_quests)]=$done;
					}
					//теперь пересечем универсум и массив выполненых
					$free_quests=array();
					
					for($i=0;$i<count($level_quests);$i++)
					{
						if(!in_array($level_quests[$i],$done_quests))
							$free_quests[count($free_quests)]=$level_quests[$i];
					}
					
				}else $free_quests=array();
				//если нет кветов, которые перс мог бы выполнить
				if(count($free_quests)==0)
				{
					//говорим, что тут уже всё
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
					echo '<font color=#aaffa8><b>Извини, но у меня больше нет для тебя заданий</b>, - развел руками хранитель подземелья.</font><br><br>';
					echo '</p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?talk" target="game">Вернуться</a><br><br>';
				}else 
				{
					//2 - выберем одно из невыполненных
					$quest_id=$free_quests[array_rand($free_quests,1)];
					
					include("dungeon_inc/dungeon_quests.php");
					$caption=$quests[$level][$quest_id]['name'];
					$text=$quests[$level][$quest_id]['description'];
					if(isset($_SESSION['dungeon'])) unset($_SESSION['dungeon']);
					$_SESSION['dungeon']['quest_id']=$quest_id;
					$needle='';
					for($i=1; $i<=count($quests[$level][$quest_id]['res']); $i++)
					{
						$needle.=''.$res[$quests[$level][$quest_id]["res"][$i]["id"]]["name"].' - <b><font color=red>'.$quests[$level][$quest_id]["res"][$i]["kol"].'</font></b> шт<br>';
					}
					$needle=substr($needle,0,strlen($needle)-2);
					
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><UL>';
					echo '<font color=#bcb1ff><LI><b>Что надо сделать:</b><font color=#aaffa8><p> '.$caption.'</p></font><br>';
					echo '<font color=#bcb1ff><LI><b>Каким образом:</b><font color=#aaffa8><p> '.$text.'</p></font><br>';
					echo '<font color=#bcb1ff><LI><b>В подтверждение принести:</b><font color=#aaffa8><p> '.$needle.'</p></font><br>';
					
					echo '</UL></p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?task" target="game">Взяться</a>';
					echo '<br><a href="?talk" target="game">Отказаться</a><br><br>';
				}
			}
		}
		elseif ($choice==2)
		{
			//просмотр текущего задания
			$have_quest=myquery("SELECT current_quest FROM dungeon_users_data WHERE user_id=".$user_id."");
			$level=$map_level_id[$char['map_name']];
			list($current_level)=mysql_fetch_array(myquery("SELECT current_level FROM dungeon_users_data WHERE user_id=".$user_id.""));
			include("dungeon_inc/dungeon_level_count.php");
			//определим кол-во выполненых заданий
			if($current_level>$level)
			{
				$dones_num='Все задания уровня';
				$quest_id=0;
			}else 
			{
				list($quest_id)=mysql_fetch_array($have_quest);
				$dones_num=mysql_num_rows(myquery("SELECT user_id FROM dungeon_quests_done WHERE user_id=".$user_id.""));
				$dones_num.=' из '.$quests_num[$level];
			}
			echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
			echo '<b><center><font color=#aaffa8>Выполнено заданий на '.$level.' уровне подземелий: <font color=red>'.$dones_num.'</font></b></center><br><br>';
			//если квест есть
			if($quest_id>0)
			{
				include("dungeon_inc/dungeon_quests.php");
				$caption=$quests[$level][$quest_id]['name'];
				
				echo '<font color=#aaffa8><b>Так-так, <font color=red>'.$char["name"].'</font>, сейчас посмотрим</b>, - хранитель подземелья порылся в своих документах и извлек один из свитков.<br><br><center>';
				echo '<b>Ваше текущее задание:</b> '.$caption.'<br><br><b>Осталось сдать:</b></font>';
				$ress=myquery("SELECT res_id,res_num FROM dungeon_users_progress WHERE user_id=".$user_id."");
				for($i=1; $i<=mysql_num_rows($ress); $i++)
				{
					list($id,$got)=mysql_fetch_array($ress);
					for($j=1; $j<=count($quests[$level][$quest_id]["res"]); $j++)
						if($quests[$level][$quest_id]["res"][$j]["id"]==$id)
						{
							$n=$j;
							break;
						}
					$res_name=$res[$id]['name'];
					$need=$got;
					if($need<=0) $font='<font color=#aaffa8>'; else $font='<font color=#ff4433>';
					echo '<br>'.$font.''.$res_name.': '.$need.' шт.';
				}
			}//если квеста нет
			else 
			{
				echo '<font color=#aaffa8><b>Так-так, <font color=red>'.$char["name"].'</font>, сейчас посмотрим</b>, - хранитель подземелья порылся в своих документах и извлек один из свитков. - <b>Нет, сейчас у тебя нет никакого задания.</b></font>';
			}
			
			echo '</center></p></tr></td></table>';
			echo '<hr align=center size=2 width=80%>';
			echo '<br><a href="?talk" target="game">Вернуться</a><br><br>';			
		}
		//сдача ресурсов заданий
		elseif ($choice==3)
		{
			$have_quest=myquery("SELECT current_quest FROM dungeon_users_data WHERE user_id=".$user_id."");
			list($quest_id)=mysql_fetch_array($have_quest);
			include("dungeon_inc/dungeon_quests.php");
			//если квест есть
			if($quest_id>0)
			{
				$level=$map_level_id[$char['map_name']];
				//include("dungeon_inc/dungeon_quests.php");
				//если перс уже выбрал, что именно сдавать
				if(isset($_POST['ress_num']))
				{
					$ress_num=(int)$_POST['ress_num'];
					for($i=0; $i<$ress_num; $i++)
					{
						//для каждого реса
						$rid_index='rid'.$i;
						$col_index='col'.$i;
						$res_id=$_POST[$rid_index];
						if(!is_numeric($_POST[$col_index])) $res_col=0;
						else $res_col=max(0,$_POST[$col_index]);
						$the_res=myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id =".$res_id."");
						//если у перса есть этот рес и сдает больше 0
						if(mysql_num_rows($the_res) AND $res_col>0)
						{
							$res_col_is=mysql_result($the_res,0,0);
							//если перс сдает больше, чем есть, берем Всё, что есть
							if($res_col_is < $res_col)
								$res_col=$res_col_is;
							$weight=mysql_result(myquery("SELECT weight FROM craft_resource WHERE id=".$res_id.";"),0,0);
							$weight=$weight*$res_col;

							if($res_col < $res_col_is)
								myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$res_col.") WHERE user_id=".$user_id." AND res_id =".$res_id.";");
							else
								myquery("DELETE FROM craft_resource_user WHERE user_id=".$user_id." AND res_id =".$res_id.";");

							myquery("UPDATE game_users SET CW=CW-".$weight." WHERE user_id=".$user_id.";");
							
							//если перс сдает больше, чем надо, берем больше
							$res_need=mysql_result(myquery("SELECT res_num FROM dungeon_users_progress WHERE user_id=".$user_id." AND res_id=".$res_id.";"),0,0);
							$res_result=max(0,$res_need-$res_col);
							myquery("UPDATE dungeon_users_progress SET res_num=".$res_result." WHERE user_id=".$user_id." AND res_id =".$res_id.";");
						}
					}
					//unset($_POST['ress_num']);
					
					//проверим, не выполнен ли квест
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><center>';
					$done_check=myquery("SELECT res_num FROM dungeon_users_progress WHERE user_id=".$user_id."");
					$done=1;
					while(list($res_num)=mysql_fetch_array($done_check))
					{
						if($res_num!=0)
						{
							$done=0;
							break;
						}
					}
					//если выполнено
					if($done==1)
					{
						//пишем БД
						//отметим квест как пройденный
						myquery("INSERT INTO dungeon_quests_done (user_id,quest_id) VALUES (".$user_id.",".$quest_id.")");	
						//увеличим общее кол-во выполненых квестов	
						myquery("UPDATE dungeon_users_data SET current_quest=0, done_quests_num=done_quests_num+1 WHERE user_id=".$user_id."");
						myquery("DELETE FROM dungeon_users_progress WHERE user_id=".$user_id."");
						//даем зелье наградлим обычными
//						include('../inc/lib_craft.inc.php');
						//$eliksir = CreateArrayForCraftEliksir();
						
						//Даем эликсиры как предметы
						//число выполненых заданий
						$dones_num=mysql_num_rows(myquery("SELECT user_id FROM dungeon_quests_done WHERE user_id=".$user_id.""));
						/*
						switch ($dones_num)
						{
							case 5:
								$give_elik=array(316,323,318);
								$col=2;
							break;
							case 10:
								$give_elik=array(322,319,321);
								$col=2;
							break;
							case 15:
								$give_elik=array(325,326,320);
								$col=1;
							break;
							case 19:
								$give_elik=array(324,357,358);
								$col=1;
							break;
							case 20:
								$give_elik=array(317,359,360);
								$col=1;
							break;
							default:
								$give_elik=array(313,314,315);
								$col=1;
							break;
						}
						*/
						if ($char['map_name']==691)
						{
							$give_elik = array(zelye_glubin_item_id);
						}
						if ($char['map_name']==692)
						{
							$give_elik = array(zelye_glubin_medium_item_id);
						}
						if ($char['map_name']==804)
						{
							$give_elik = array(zelye_glubin_big_item_id);
						}
						$col=1;
						$priz='';
						for($j=0;$j<count($give_elik); $j++)
						{
							$i=$give_elik[$j];
							$Item = new Item();
							$ar = $Item->add_user($i,$user_id,1);
							if ($ar[0]>0)
							{
								$priz.='<br><font color=#bcb1ff>'.$Item->getFact('name').'</font><font color=#aaffa8> - </font><font color=red>'.$col.'</font> <font color=#aaffa8>шт., </font>';
							}
						}
						//====================================== 
						$priz=substr($priz,0,strlen($priz)-2);
						echo '<font color=#aaffa8><b>Отлично, задание выполнено! Спасибо, что '.echo_sex('помог','помогла').' нам в нашем нелегком деле! Но, конечно, одними словами моя благодарность не ограничится - в награду я даю тебе '.$priz.'!</b></font>';
						unset($col);

						//************************************		
						//проверка на полное прохождение уровня!!!!!!
						//cколько заданий на каждом уровне
						include("dungeon_inc/dungeon_level_count.php");
						if($dones_num>=$quests_num[$level])
						{
							$blazevic=612; 
							$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$blazevic.", '0', 'Игрок прошел подземелья', 'Здравствуйте, Вас беспокоят гномы Мории :) Спешим уведомить Вас, что игрок ".$char['name']." прошел ".$level." уровень подземелий.','0','".time()."')");
                            $stream_dan=2694; 
                            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$stream_dan.", '0', 'Игрок прошел подземелья', 'Здравствуйте, Вас беспокоят гномы Мории :) Спешим уведомить Вас, что игрок ".$char['name']." прошел ".$level." уровень подземелий.','0','".time()."')");
							
							echo '<br><br><font color=#aaffa8>Хранитель подземелий сверился со своими записями и удивленно вскричал: <font color=#aaffa8><b>Чтоб мне подавиться бородой Дарина, </font><font color=red>'.$char["name"].'</font>, <font color=#aaffa8> ты '.echo_sex('выполнил','выполнила').' все мои задания! Не думал я, что кто-то сможет это сделать. Ты '.echo_sex('достоин','достойна').' звания настоящего чемпиона. В подтверждение этого я даю тебе почетный знак великого победителя монстров и в виде дополнительной награды, дарю тебе <font color=#bcb1ff>понтову зОгОгулину</font>. Однако это еще не все - если ты захочешь заслужить еще большую славу и внести новый вклад в развитие шахт великой Мории, то сможешь войти в подземелья '.($level+1).' уровня. Вот уж где тебя ожидают задачи не для сопляков. Я предупрежу охрану, чтобы они пустили тебя на '.($level+1).' уровень, если ты пожелаешь туда попасть. Удачи!</b></font>';
							//поставим доступ на новый левел
							myquery("UPDATE dungeon_users_data SET current_level=current_level+1, current_quest=0 WHERE user_id=".$user_id."");
							//обнулим статистику по выполненым
							myquery("DELETE FROM dungeon_quests_done WHERE user_id=".$user_id."");
						}	
					}
					else 
					{
						echo '<br><font color=#aaffa8>Ты '.echo_sex('сдал','сдала').' ресурсы.</font><br>';
					}
				}
				else 
				{
					//вывод диалога
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><center>
					<font color=#aaffa8>Ты можешь сдать следущие ресурсы:<br><br>';
					//найдем, какие ресы надо сдавать
					$ress_id=array();
					$i=0;
					echo '<form action="?choice=3" method="post">';
					for($j=1; $j<=count($quests[$level][$quest_id]["res"]); $j++)
					{
						$res_id=$quests[$level][$quest_id]["res"][$j]["id"];
						$the_res=myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id =".$res_id."");
						list($done_check)=mysql_fetch_array(myquery("SELECT res_num FROM dungeon_users_progress WHERE res_id =".$res_id." AND user_id=".$user_id.""));
						if(mysql_num_rows($the_res)>0 AND $done_check>0)
						{
							$res_col=mysql_result($the_res,0,0);
							if($res_col>0)
							{
								$res_name=$res[$res_id]['name'];
								$inp_name='col'.$i;
								$hid_name='rid'.$i;
								echo '<font color=yellow><b>'.$res_name.'</b></font>, сдать 
								<INPUT type="text" size="3" maxlength="3" name="'.$inp_name.'" value="0"> шт. (У тебя <font color=red>'.$res_col.'</font> шт.)<br>
								<INPUT type="hidden" name="'.$hid_name.'" value="'.$res_id.'">';
								$i++;
							}
						}
					}
					if($i==0) echo '<font color=#ff4433><b>У тебя нет ресурсов, которые можно сдать</b></font><br>';
					else echo '<br><br><input type="submit" value="Сдать ресурсы">';
					echo '<INPUT type="hidden" name="ress_num" value="'.$i.'"></form>';
				}
			}else 
			{
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
				echo '<font color=#aaffa8><b>Так-так, <font color=red>'.$char["name"].'</font>, сейчас посмотрим</b>, - хранитель подземелья порылся в своих документах и извлек один из свитков. - <b>Нет, сейчас у тебя нет никакого задания.</b></font>';
			}
			
			echo '</center></p></tr></td></table>';
			echo '<hr align=center size=2 width=80%>';
			echo '<br><a href="?talk" target="game">Вернуться</a><br><br>';	
		}
		
		//QuoteTable('close');
	}
	else
	{
		echo '<br><a href="?talk" target="game">Поговорить с хранителем подземелья</a>';
		echo '<br><a href="?exit" target="game">Выйти из подземелий</a>';
		echo '<br><a href="../../act.php" target="game">Вернуться</a><br><br>';
	}
	
	OpenTable('close');
	?>
	</td></tr></table>
	<?
	include("../inc/template_footer.inc.php");
}else 
	echo  '<meta http-equiv="refresh" content="0;url=../../act.php">';

?>