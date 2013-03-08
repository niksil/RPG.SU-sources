<?php

?>
<br /><br /><br />
<style type="text/css">
.intro
{
	font-weight: normal;
	font-family: Verdana, sans-serif;
	font-size: 12px;
	color: gold;
	margin : 5px;
}
</style>
<?

function print_step1()
{
	QuoteTable('open');
	echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Эктор</b><br /><img src="http://'.IMG_DOMAIN.'/avatar/gallery/035.gif" title="Эктор" alter="Эктор" align="left"><span class="intro">Приветствую тебя, о смелый искатель приключений!! Я Эктор - глава гильдии новичков! Ты '.echo_sex('должен','должна').' пройти небольшое обучение, прежде чем сможешь отправиться на поиски славы и приключений!!! И я помогу тебе в этом!<br /><br /><br />Прежде всего тебе надо пройти обряд посвящения в адепты гильдии. Для этого ты '.echo_sex('должен','должна').' совершить паломничество к четырем небесным камням истины, привезенных сюда из самого Валинора, благословенного края, согретого мудростью Валар.  
В них содержится великая мудрость! Первый камень расположен на 0-0, чтобы попасть туда иди на запад и чуточку на север!<br /><br /><br />Для перемещения тебе надо щелкать по квадратикам карты (эти квадратики называются "гексы"). Карта располагается в левом верхнем углу экрана слева от фразы "ВОЙТИ в ГОРОД "Гильдия новичков."<br />Твое положение на карте - всегда в центре. Для перемещения на запад тебе надо щелкнуть мышкой на гексу слева от твоего положения по координате (7,1). Потом продолжай также щелкать по карте, пока не доберешься до точки с координатами (0,0)."
</span>';
	QuoteTable('close');
}

function print_step2()
{
	QuoteTable('open');
	echo '<img src="http://'.IMG_DOMAIN.'/obelisk/obelisk_siniy.gif" align="left"><span class="intro">Вот ты и '.echo_sex('нашел','нашла').' первый камень истины. На камне написано:<br /><br />
	Внемли мне,  о чужестранец, и ты узнаешь о магах!! Сильных и благородных! Повергающих врагов своих небесными молниями и жарким пламенем, защищающих силой скрытой в тверди земной и врачующих раны живительной влагой воды!! Каждый маг очень мудр, это помогает ему получать больше опыта в бою, а  высокий интеллект позволяет наносить страшные раны врагу! Чтобы творить заклинания магу нужна мана (если повысить интеллект, то и мана вырастет на 10 единиц)<br /><br />
	Теперь твой путь лежит на юг, к следующему камню истины, который расположен на координате (0,19)</span>';
	QuoteTable('close');
}

function print_step3()
{
	QuoteTable('open');
	echo '<img src="http://'.IMG_DOMAIN.'/obelisk/obelisk_krasniy.gif" align="left"><span class="intro">Вот ты и '.echo_sex('нашел','нашла').' второй камень истины. На камне написано:<br /><br />
	Внемли истине о воинах, путешественник!! Яростных и бесстрашных! Владеющих рунными мечами и крепкими щитами!! Каждому воину не занимать силы и ловкости, их удар способен рассечь пополам дикого быка, а щит выдержит любой удар врага! Для своих могучих ударов воинам нужна энергия (каждое повышение ловкости увеличит твою энергию на 10 единиц)<br /><br />
	Теперь твой путь лежит на восток, к следующему камню истины, который расположен на координате (19,19)</span>';
	QuoteTable('close');
}

function print_step4()
{
	QuoteTable('open');
	echo '<img src="http://'.IMG_DOMAIN.'/obelisk/obelisk_jeltiy.gif" align="left"><span class="intro">Вот ты и '.echo_sex('нашел','нашла').' третий камень истины. На камне написано:<br /><br />
	Услышь же истину об алхимии, странник!! На просторах нашей страны растут чудодейственные травы, алхимики научились варить из них волшебные эликсиры, которые можно использовать, чтобы восстановить здоровье, ману или энергию....их можно использовать лишь один раз за бой. Бывают малые, средние и большие эликсиры ( они восстанавливают по 50 100 и 200 единиц соответственно ). <br /><br />
	У этого камня ты '.echo_sex('нашел','нашла').' 3 эликсира - малый эликсир здоровья, малый эликсир маны и малый эликсир энергии. Они автоматически попали в твой инвентарь. Увидеть их ты можешь, нажав на надпись "Инвентарь" в верхнем меню игры. Только не забудь потом опять вернуться в игру, нажав в этой же верхней строчке надпись "Игра".<br /><br />
	Теперь твой путь лежит на север, к следующему камню истины, который расположен на координате (19,0)</span>';
	QuoteTable('close');
}

function print_step5()
{
	QuoteTable('open');
	echo '<img src="http://'.IMG_DOMAIN.'/obelisk/obelisk_dark.gif" align="left"><span class="intro">Вот ты и '.echo_sex('нашел','нашла').' последний камень истины. На камне написано:<br /><br />
	Узнай же о тьме и о свете, герой! В этом мире много зла и добра, света и тьмы, каждый может выбрать свою сторону и погасить свет в своей душе или изгнать тьму, стать рыцарем зла или палладином света! Эльфы, гномы, хоббиты издавна относились к войскам света, а орки, тролли, гоблины и назгулы были созданы силами тьмы для зла и разрушений, в душе людей изначально зло и тьма находились в балансе. Но за всю историю от сотворения мира было множество добрых троллей и орков, приносящих добро и темных эльфов, свершающих ритуалы зла и тьмы!! Каждый сам волен выбирать свою судьбу и цель своего существования!!<br /><br />
	Теперь ты '.echo_sex('проникся','прониклась').' мудростью камней истины и можешь следовать на запад к Эктору за новыми заданиями (Эктор находится на координате 8,1)</span>';
	QuoteTable('close');
}

function print_step6($add_gp=0)
{
	global $user_id;
	QuoteTable('open');
	$gp = 20;
	echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Эктор</b><br /><img src="http://'.IMG_DOMAIN.'/avatar/gallery/035.gif" title="Эктор" alter="Эктор" align="left"><span class="intro">Мои поздравления!! Отныне ты адепт гильдии новичков!! Теперь отправляйся на координату 13-16 и проведай моего старого друга Пелагаста, я слышал что в его магазин привезли партию новых мечей, принеси мне <b><u>короткий меч</u></b>. ';
	if ($add_gp==1)
	{
		echo 'Вот тебе '.$gp.' монет на расходы. ';
	}
	echo 'Когда дойдешь на эту координату - нажми на надпись "Войти", чтобы попасть в магазин Пелагаста. В магазине нажми на изображение Оружия и купи короткий меч</span>';
	if ($add_gp==1) 
	{
		myquery("UPDATE game_users SET GP=GP+$gp,CW=CW+".($gp*money_weight)." WHERE user_id=$user_id");
		setGP($user_id,$gp,60);
	}
	QuoteTable('close');
}

function print_step7()
{
	QuoteTable('open');
	echo 'Ты '.echo_sex('купил','купила').' короткий меч. Теперь возвращайся к Эктору за новыми заданиями (Эктор находится на координате 8,1)</span>';
	QuoteTable('close');
}

function print_step8($add_gp=0)
{
	global $user_id;
	$gp = 35;
	QuoteTable('open');
	echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Эктор</b><br /><img src="http://'.IMG_DOMAIN.'/avatar/gallery/035.gif" title="Эктор" alter="Эктор" align="left"><span class="intro">Ты быстро '.echo_sex('вернулся','вернулась').'! Это превосходный меч!!! Но я слишком стар для таких игрушек. Оставь его себе. Он тебе несомненно сослужит хорошую службу! Если хочешь подержать его в руках посмотри в свой инвентарь и найдешь меч там среди оружия. Нажми по надписи "Взять оружие" чтобы взять этот меч в свои руки<br /><br />Да и еще кое что! Твоя защита пока недостаточно высока, сходи еще раз к Пелагасту на координаты 13-16 и выбери себе что-нибудь из доспехов и щитов.';
	if ($add_gp==1)
	{
		echo 'Вот тебе '.$gp.' монет( на <b><u>круглый щит</u></b> и <b><u>кольчугу</u></b> ) на расходы.';
	}
	echo 'Когда дойдешь на эту координату - нажми на надпись "Войти", чтобы попасть в магазин Пелагаста. В магазине нажми на изображение Доспехов или Щитов и купи себе обмундирование</span>';
	if ($add_gp==1) 
	{
		myquery("UPDATE game_users SET GP=GP+$gp,CW=CW+".($gp*money_weight)." WHERE user_id=$user_id");
		setGP($user_id,$gp,60);
	}
	QuoteTable('close');
}

function print_step9()
{
	QuoteTable('open');
	echo 'Ты '.echo_sex('купил','купила').' защитное обмундирование. Теперь возвращайся к Эктору за новыми заданиями (Эктор находится на координате 8,1)</span>';
	QuoteTable('close');
}

function print_step10()
{
	QuoteTable('open');
	echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Эктор</b><br /><img src="http://'.IMG_DOMAIN.'/avatar/gallery/035.gif" title="Эктор" alter="Эктор" align="left"><span class="intro">Теперь ты достаточно '.echo_sex('силен','сильна').', чтобы сразиться в своих первых битвах!! До меня дошла весть что на координате 16-7 крестьян беспокоит своими нападениями лесной волк, а на координате 3-7 на нас напал ученик могущественного темного волшебника!!! Ты '.echo_sex('должен','должна').' сразить обоих противников!!!';
	echo 'Когда дойдешь на эти координаты и увидешь волка или темного волшебника - нажми на них или на значок меча справа от них, чтобы напасть и обязательно победить их!</span>';
	QuoteTable('close');
}

function print_step11()
{
	print_step10();
}

function print_step12()
{
	QuoteTable('open');
	echo '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Эктор</b><br /><img src="http://'.IMG_DOMAIN.'/avatar/gallery/035.gif" title="Эктор" alter="Эктор" align="left"><span class="intro">Теперь ты достаточно '.echo_sex('опытен','опытна').' и не нуждаешься в моих наставлениях!!! Запомни также что за победу над реальным игроком дают больше монет, чем за победу над разными монстрами и компьютерными ботами.... Узнать о том, где находятся реальные игроки, ты можешь открыв список Онлайн. Для этого надо щелкнуть по надписи "Онлайн" в верхней строчке экрана игры. Не забудь потом вернуться в игру нажав на надпись "Игра" в этой же верхней строчке.<br />
Удачи тебе ученик!!! И пусть хранят тебя силы Арды!!
</span>';
	QuoteTable('close');
}

function print_step($step,$add_gp=0)
{
	switch ($step)
	{
		case 1:{print_step1();}break;
		case 2:{print_step2();}break;
		case 3:{print_step3();}break;
		case 4:{print_step4();}break;
		case 5:{print_step5();}break;
		case 6:{print_step6($add_gp);}break;
		case 7:{print_step7();}break;
		case 8:{print_step8($add_gp);}break;
		case 9:{print_step9();}break;
		case 10:{print_step10();}break;
		case 11:{print_step11();}break;
		case 12:{print_step12();}break;
	}
}

$sel = myquery("SELECT step FROM game_users_intro WHERE user_id=$user_id");
$step = 0;
if ($sel!=false OR mysql_num_rows($sel)>0)
{
	list($step) = mysql_fetch_array($sel); 
}
if ($step==0 AND (isset($from_jurnal)))
{
	unset($from_jurnal);
}
if (isset($from_jurnal))
{
	echo '<h3>Квест от Эктора для новых игроков в Гильдии Новичков</h3>';
	print_step($step);
}
else
{
	if ($char['map_xpos']==8 AND $char['map_ypos']==1)
	{
		if ($step==0 OR $step==1)
		{
			if($step==0) myquery("INSERT INTO game_users_intro (user_id,step) VALUES ($user_id,1)");
			print_step1();
		}
		if ($step==5 OR $step==6)
		{
			myquery("UPDATE game_users_intro SET step=6 WHERE user_id=$user_id");
			$add_gp = 0;
			if ($step==5) $add_gp = 1;
			print_step6($add_gp); 
		}
		if ($step==7 OR $step==8)
		{
			if (!isset($from_jurnal)) myquery("UPDATE game_users_intro SET step=8 WHERE user_id=$user_id");
			$add_gp = 0;
			if ($step==7) $add_gp = 1;
			print_step8($add_gp); 
		}
		if ($step==9 OR $step==10)
		{
			myquery("UPDATE game_users_intro SET step=10 WHERE user_id=$user_id");
			print_step10(); 
			if ($step==9)
			{                                                                               
				//генерируем двух ботов только для этого игрока!
				
				//лесной волк
				//myquery("INSERT INTO game_npc_template (npc_name,npc_img,npc_race,npc_max_hp,npc_max_mp,npc_str,npc_dex,npc_pie,npc_vit,npc_spd,item,npc_ntl,agressive,npc_level,npc_exp_max,to_delete) VALUES ('Лесной волк','1129','Волк','".$char['HP_MAX']."','".$char['MP_MAX']."',4,4,4,1,3,'лапой',2,'0',1,300,1)");
				$npc_id = 1055718;	
				myquery("INSERT INTO game_npc (npc_id,prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,EXP,stay) VALUES ($npc_id,'1',$user_id,".$char['map_name'].",16,7,45,65,0,300,4)");
				
				//темный волшебник
				//myquery("INSERT INTO game_npc_template (npc_name,npc_img,npc_race,npc_max_hp,npc_max_mp,npc_str,npc_dex,npc_pie,npc_vit,npc_spd,item,npc_ntl,agressive,npc_level,npc_exp_max,to_delete) VALUES ('Темный волшебник','1-0-0','Маг','".$char['HP_MAX']."','".$char['MP_MAX']."',1,5,2,0,4,'лапой',4,'0',1,300,1)");
				$npc_id = 1055854;
				myquery("INSERT INTO game_npc (npc_id,prizrak,for_user_id,map_name,xpos,ypos,HP,MP,view,EXP,stay) VALUES ($npc_id,'1',$user_id,".$char['map_name'].",3,7,45,50,0,300,4)");
			}
		}
		if ($step==11)
		{
			print_step11(); 
		}
		if ($step==12)
		{
			print_step12(); 
		}
	}
	elseif ($char['map_xpos']==0 AND $char['map_ypos']==0) 
	{
		if ($step==1 OR $step==2)
		{
			myquery("UPDATE game_users_intro SET step=2 WHERE user_id=$user_id");
			print_step2();
		}
	}
	elseif ($char['map_xpos']==0 AND $char['map_ypos']==19) 
	{
		if ($step==2 OR $step==3)
		{
			myquery("UPDATE game_users_intro SET step=3 WHERE user_id=$user_id");
			print_step3();
		}
	}
	elseif ($char['map_xpos']==19 AND $char['map_ypos']==19) 
	{
		if ($step==3 OR $step==4)
		{
			myquery("UPDATE game_users_intro SET step=4 WHERE user_id=$user_id");
			print_step4();
			if ($step==3)
			{
				//добавим элики
				$Item = new Item();
				$ar = $Item->add_user(313,$user_id,0);
				$ar = $Item->add_user(314,$user_id,0);
				$ar = $Item->add_user(315,$user_id,0);
			}
		}
	}
	elseif ($char['map_xpos']==19 AND $char['map_ypos']==0) 
	{
		if ($step==4 OR $step==5)
		{
			myquery("UPDATE game_users_intro SET step=5 WHERE user_id=$user_id");
			print_step5();
		}
	}
	elseif ($char['map_xpos']==13 AND $char['map_ypos']==16) 
	{
		if ($step==7)
		{
			print_step7();
		}
		if ($step==9)
		{
			print_step9();
		}
	}
}
?>