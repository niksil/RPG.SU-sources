<?php
class Combat
{
	private $char;//"владелец" скрипта   
	private $combat; //запись из combat
	private $souz;//массив записей игроков и ботов - союзников
	private $prot;//массив записей игроков и ботов - противников
	public $all;//массив записей всех игроков и ботов боя, в том числе и "владелец" скрипта 
	private $str_type_boy;//строковое название типа боя
	public $timeout; //таймаут хода боя
	private $decrease;//коэффициент уменьшения урона в формулах
	private $map; //запись game_maps где проходит бой
	private $log; //массив значений для записи лога хода боя
	private $k_exp; //коэффициент изменения опыта
	private $k_gp; //коэффициент изменения денег
	private $lockfile; //файл для блокировки
	public $add_exp_for_sklon;
	
	public function __construct($combat_id,$user_id,$state='')
	{
		if (!empty($state))
		{
			if (empty($combat_id))
			{
				$combat_id = $state['combat_id'];			
			}
		}                                      
		$this->char = mysql_fetch_array(myquery("SELECT * FROM combat_users WHERE user_id=".$user_id."")); 
		$this->log = array();
		$this->combat = mysql_fetch_array(myquery("SELECT * FROM combat WHERE combat_id=".$combat_id.""));
		switch ($this->combat['combat_type'])
		{
			case 1: $this->str_type_boy = "Обычный бой"; break;
			case 2: $this->str_type_boy = "Дуэль"; break;
			case 3: $this->str_type_boy = "Общий бой"; break;
			case 4: $this->str_type_boy = "Многоклановый бой"; break;
			case 5: $this->str_type_boy = "Все против всех"; break;
			case 6: $this->str_type_boy = "Бой склонностей"; break;
			case 7: $this->str_type_boy = "Бой рас"; break;
			case 8: $this->str_type_boy = "Турнирная дуэль"; break;
			case 9: $this->str_type_boy = "Турнирный групповой бой"; break;
			case 10: $this->str_type_boy = "Бой с тенью"; break;
			case 11: $this->str_type_boy = "Турнирный хаотичный бой"; break;
		}
		$this->decrease = 0.7;
		if ($this->combat['map_name']==691 OR $this->combat['map_name']==692 OR $this->combat['map_name']==804)
		{
			$this->decrease = 0.7;
		}
		$this->timeout = 120;

        //$this->timeout = 180000;  

		if (defined("add_exp_for_sklon"))
		{
			$this->add_exp_for_sklon = add_exp_for_sklon;
		}
		else
		{
			$this->add_exp_for_sklon = -1;
		}
		$this->souz = array();
		$this->prot = array();
		$this->all  = array();
        
		if ($state!='' AND in_array($state['state'],array(1,2,5,6,10)))
		{
			$this->all[$this->char['user_id']] = $this->char;
			$this->all[$this->char['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
			$this->all[$this->char['user_id']]['lose']=0; // ---//---
			$this->all[$this->char['user_id']]['exp']=0; // ---//--- 
			$this->all[$this->char['user_id']]['gp']=0; // ---//---
			$this->all[$this->char['user_id']]['state']=$state['state'];
            if ($this->char['clan_id']>0)
            {
			    $this->all[$this->char['user_id']]['alies']=mysql_result(mysql_query("SELECT alies FROM game_clans WHERE clan_id=".$this->char['clan_id'].""),0,0);
            }
            else
            {
                $this->all[$this->char['user_id']]['alies'] = 0;
            }
            
			//союзники
			$userinboy=myquery("
			select combat_users.*,combat_users_state.state from combat_users,combat_users_state 
			where combat_users.combat_id=".$combat_id." 
			and combat_users.user_id<>".$this->char['user_id']." 
			AND combat_users.join=0
			AND combat_users.side=".$this->char['side']."
			AND combat_users.user_id=combat_users_state.user_id
			ORDER BY combat_users.clan_id ASC, BINARY combat_users.name ASC");
			while ($us=mysql_fetch_array($userinboy))
			{
				if ($us['HP']>0) $this->souz[$us['user_id']]=$us;
				$this->all[$us['user_id']]=$us;
				$this->all[$us['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
				$this->all[$us['user_id']]['lose']=0; // ---//---
				$this->all[$us['user_id']]['exp']=0; // ---//--- 
				$this->all[$us['user_id']]['gp']=0; // ---//--- 
                $this->all[$us['user_id']]['npc_id_template'] = -1;
                if ($us['npc']==1)
                {
                    $this->all[$us['user_id']]['npc_id_template'] = mysql_result(myquery("SELECT npc_id FROM game_npc WHERE id=".$us['user_id'].""),0,0);
                }
			}
			
			//противники
			$order_by = "combat_users.clan_id ASC, BINARY combat_users.name ASC";
			if (chaos_war==1)
			{
				$order_by = "RAND()";
			}
			$userinboy=myquery("
			select combat_users.*,combat_users_state.state from combat_users,combat_users_state 
			where combat_users.combat_id=".$combat_id." 
			AND combat_users.join=0
			AND combat_users.side<>".$this->char['side']."
			AND combat_users.user_id=combat_users_state.user_id
			ORDER BY $order_by");
			while ($us=mysql_fetch_array($userinboy))
			{
				if ($us['HP']>0) $this->prot[$us['user_id']]=$us;
				$this->all[$us['user_id']]=$us;
				$this->all[$us['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
				$this->all[$us['user_id']]['lose']=0; // ---//---
				$this->all[$us['user_id']]['exp']=0; // ---//--- 
				$this->all[$us['user_id']]['gp']=0; // ---//--- 
                $this->all[$us['user_id']]['npc_id_template'] = -1;
                if ($us['npc']==1)
                {
                    $this->all[$us['user_id']]['npc_id_template'] = mysql_result(myquery("SELECT npc_id FROM game_npc WHERE id=".$us['user_id'].""),0,0);
                }
			}
		}
		else
		{
			//класс вызван из крона, нет "владельца"
			$userinboy=myquery("
			select combat_users.* from combat_users,combat_users_state 
			where combat_users.combat_id=".$this->combat['combat_id']." 
			AND combat_users.join=0
			AND combat_users.user_id=combat_users_state.user_id
			ORDER BY combat_users.clan_id ASC, BINARY combat_users.name ASC");
			while ($us=mysql_fetch_array($userinboy))
			{
				$this->all[$us['user_id']]=$us;
				$this->all[$us['user_id']]['win']=0; //увеличение соотв.поля в game-users по результатам расчета хода
				$this->all[$us['user_id']]['lose']=0; // ---//---
				$this->all[$us['user_id']]['exp']=0; // ---//--- 
				$this->all[$us['user_id']]['gp']=0; // ---//--- 
                $this->all[$us['user_id']]['npc_id_template'] = -1;
                if ($us['npc']==1)
                {
                    $this->all[$us['user_id']]['npc_id_template'] = mysql_result(myquery("SELECT npc_id FROM game_npc WHERE id=".$us['user_id'].""),0,0);
                }
			}
		}
		$this->map = mysql_fetch_array(myquery("SELECT * FROM game_maps WHERE id=".$this->combat['map_name'].""));
	}
	
	public function clear_combat()
	{
		ClearCombat($this->combat['combat_id']);
	}
	
	public function clear_user($user_id)
	{
		ClearCombatUser($user_id);
	}
	
	public function print_header()
	{
		PrintCombatHeader();
	}
	
	public function show_log()
	{
		ShowCombatLog($this->combat['combat_id'],$this->combat['hod']-1);
	}
	
	private function print_clan($clan_id)
	{
		if ($clan_id>0)
		{
			list($clan_name) = mysql_fetch_array(myquery("SELECT nazv FROM game_clans WHERE clan_id=$clan_id"));
			echo '<img src="http://'.IMG_DOMAIN.'/clan/'.$clan_id.'.gif" alt="'.$clan_name.'" title="'.$clan_name.'" border="0">';
		}
	}
	
	public function print_state1() //запрос подтверждения от игрока разрешения на начало боя
	{
		$prot = reset($this->prot);
		if (!isset($_GET['ok']) AND !isset($_GET['no']))
		{
			$this->print_header();
			?>           
			Тебя вызвали на поединок!<br />
			Твой противник: <?=$prot['name'];?> (<?=$prot['race'];?>  <?=$prot['clevel'];?> уровня)
			<?
			$this->print_clan($prot['clan_id']);
			print_sklon($prot);
			?>
			&nbsp;&nbsp;<br>Вызвал тебя на <b><font color=#FF0000><?=$this->str_type_boy;?></font></b>
			
			<div align="center">До конца выбора варианта осталось: 
			<font color=ff0000><b><span id="timerr1"><?=(time()+$this->timeout-5-$this->combat['time_last_hod']);?></span></b></font> секунд
			</div>
			<script language="JavaScript" type="text/javascript">
			function tim()
			{
				timer = document.getElementById("timerr1");
				if (timer.innerHTML<=0)
				{
					location.replace("combat.php?no");
				}
				else
				{
					timer.innerHTML=timer.innerHTML-1;
					window.setTimeout("tim()",1000);
				}
			}
			tim();
			</script>
			<br><br><?=echo_sex('Согласен','Согласна');?> ли ты на бой?<br><br><br>
			<input type="button" class="button" value="&nbsp;&nbsp;&nbsp;Да&nbsp;&nbsp;&nbsp;" OnClick=location.href="combat.php?ok">
			<input type="button" class="button" value="&nbsp;&nbsp;&nbsp;Нет&nbsp;&nbsp;&nbsp;" OnClick=location.href="combat.php?no">
			<meta http-equiv="refresh" content="15">
			<?
		}
		if (isset($_GET['ok']))
		{
			//начинаем бой
			combat_setFunc($this->char['user_id'],5,$this->combat['combat_id']);
			combat_setFunc($prot['user_id'],5,$this->combat['combat_id']);

			if ($this->combat['combat_type']==4)
			{
				myquery("INSERT INTO game_log (message,date,fromm,ob) VALUES ('".iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-weight:900;font-size:14px;color:red;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">ВНИМАНИЕ! <img align=\"center\" src=\"http://".IMG_DOMAIN."/clan/".$this->char['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->char['clan_id'].""),0,0)." и <img align=\"center\" src=\"http://".IMG_DOMAIN."/clan/".$this->all[$prot['user_id']]['clan_id'].".gif\"> ".mysql_result(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->all[$prot['user_id']]['clan_id'].""),0,0)." начинают бой: ".$this->map['name']."(".$this->combat['map_xpos']."; ".$this->combat['map_ypos'].") </span>'").",".time().",-1,1)");
			}
			setLocation("combat.php");
		}
		if (isset($_GET['no']))
		{
			combat_setFunc($this->char['user_id'],3,$this->combat['combat_id']);
			combat_setFunc($prot['user_id'],4,$this->combat['combat_id']);
			$this->clear_combat();
			setLocation("combat.php");
		}
	}
	
	public function print_state2() //ожидание подтверждения от противника
	{
		$prot = reset($this->prot);
		if (!isset($_GET['no']))
		{
			$this->print_header();
			?>
			<center>Ожидание подтверждения противника<br><br>
			<input type="button" class="button" value="Отказаться от вызова на бой" OnClick=location.href="combat.php?no">
			<br><br><div align="center">До конца ожидания осталось: 
			<font color=ff0000><b><span id="timerr1"><?=(time()+$this->timeout-5-$this->combat['time_last_hod']);?></span></b></font> секунд</div>
			<script language="JavaScript" type="text/javascript">
			function tim()
			{
				timer = document.getElementById("timerr1");
				if (timer.innerHTML<=0)
					location.replace("combat.php?no");
				else
				{
					timer.innerHTML=timer.innerHTML-1;
					window.setTimeout("tim()",1000);
				}
			}
			tim();
			</script>
			<meta http-equiv="refresh" content="15">
			<?
		}
		else
		{
			combat_setFunc($this->char['user_id'],3,$this->combat['combat_id']);
			combat_setFunc($prot['user_id'],4,$this->combat['combat_id']);
			$this->clear_combat();
			setLocation("combat.php");
		}
	}
	
	private function del_combat_user()
	{
		//удаляет признак о переводе игрока на скрипт боя, чтобы при след.обновлении экрана игрока перекидывало бы на act.php
		combat_delFunc($this->char['user_id']);
		ForceFunc($this->char['user_id'],5); 
	}
	
	private function print_user($user) //вывод данных по игроку в виде строк HTML Table
	{
		if ($user['HP_MAX']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = $user['HP'] / $user['HP_MAX'] * 100;
		}
		if ($bar_percentage >= 100)
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= 0)
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="'.(100 - $bar_percentage).'" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_green.gif" width="'.$bar_percentage.'" height="7" border="0">';
		}

		echo '
		<tr><td>Здоровье</td><td width=70% align=right>'.$user['HP'].' / '.$user['HP_MAX'].'</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'.$append_string.'<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"></div></td></tr>';

		if ($user['MP_MAX']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = $user['MP'] / $user['MP_MAX'] * 100;
		}
		if ($bar_percentage >= 100)
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= 0)
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="'.(100 - $bar_percentage).'" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_orange.gif" width="'.$bar_percentage.'" height="7" border="0">';
		}
		echo '
		<tr><td>Мана</td><td width=70% align=right>'.$user['MP'].' / '.$user['MP_MAX'].'</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'.$append_string.'<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"></td></tr>';

		if ($user['STM_MAX']==0)
		{
			$bar_percentage = 0;
		}
		else
		{
			$bar_percentage = $user['STM'] / $user['STM_MAX'] * 100;
		}
		if ($bar_percentage >= 100)
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_yellow.gif" width="100" height="7" border="0">';
		}
		elseif ($bar_percentage <= 0)
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="100" height="7" border="0">';
		}
		else
		{
			$append_string = '<img src="http://'.IMG_DOMAIN.'/bar/bar_empty.gif" width="'.(100 - $bar_percentage).'" height="7" border="0"><img src="http://'.IMG_DOMAIN.'/bar/bar_yellow.gif" width="'.$bar_percentage.'" height="7" border="0">';
		}
		echo '
		<tr><td>Энергия</td><td width=70% align=right>'.$user['STM'].' / '.$user['STM_MAX'].'</td></tr>
		<tr><td colspan="2"><div align="right"><img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0">'.$append_string.'<img src="http://'.IMG_DOMAIN.'/bar/bar_side.gif" width="1" height="7" border="0"></div></td></tr>';

		echo '
		<tr><td>Сила</td><td><div align="right">'.$user['STR'].'</td></tr>
		<tr><td>Интеллект</td><td><div align="right">'.$user['NTL'].'</td></tr>
		<tr><td>Ловкость</td><td><div align="right">'.$user['PIE'].'</td></tr>
		<tr><td>Защита</td><td><div align="right">'.$user['VIT'].'</td></tr>
		<tr><td>Мудрость</td><td><div align="right">'.$user['SPD'].'</td></tr>
		<tr><td>Выносливость</td><td><div align="right">'.$user['DEX'].'</td></tr>';        
	}
	
	private function print_left() //вывод левой части интерфейса - данных по самому игроку
	{
		OpenTable('title');
		echo '
		<div align="right"><font face="Verdana" size="2" color="#f3f3f3"><b>'.$this->char['name'].'</b></font></div>
		<br><img src = "http://'.IMG_DOMAIN.'/avatar/'.$this->char['avatar'].'"><br />
		<table cellpadding="2" cellspacing="0" width="200" border="0" align="center">';

		$this->print_user($this->char);
	  
		$sel_exp = myquery("SELECT SUM(exp),SUM(gp) FROM combat_users_exp WHERE user_id='".$this->char['user_id']."' AND combat_id='".$this->combat['combat_id']."'");
		list($EXP,$GP) = mysql_fetch_array($sel_exp);
		if ($this->map['not_exp']==0 AND $this->char['not_exp']==0 AND $EXP>0)
			echo '<tr><td valign="center" colspan=2><font color = "#FFFF00">Пул очков опыта</font><div align="right"><b><font color = "#FFFF00">'.$EXP.' </font></b></td></tr>';
		if ($this->map['not_gp']==0 AND $this->char['not_gp']==0 AND $GP>0)
			echo '<tr><td valign="center" colspan=2><font color = "#FFFF00">Пул монет</font><div align="right"><b><font color = "#FFFF00">'.$GP.' </font></b></td></tr>';
		
		echo '</table>';
		OpenTable('close');

		echo '<div style="background-color:black;display:none;" id="div_newusers">';
		OpenTable('title','100%');
		echo '<span id="span_newusers"></span>';
		OpenTable('close');
		echo '</div>';
	}
	
	private function print_user_hint($user)
	{
		echo'<table cellpadding="2" cellspacing="0" width="97%" border="0" onClick=vkogo="'.$user['user_id'].'";clickn(this) align=center>';
		echo '<tr><td><div align="left">';
		if (chaos_war==0)
		{
            if ($this->combat['combat_type']==40)//ДЛя многоклана тип боя = 4, пока отключили скрытие информации в многокланах.
            {
                if ($user['clan_id']>0 and ($user['clan_id']==$this->all[$this->char['user_id']]['alies'])or($user['clan_id']==$this->all[$this->char['user_id']]['clan_id']))
                {
                    $this->print_clan($user['clan_id']);
                    print_sklon($user);
                    echo'<font face="Verdana" size="1">'.$user['name'].' [';
                    ?><a onmousemove=movehint(event,1) onmouseover="showhint(
                    '<? echo '<font color=000000>'.$user['race'].' '.$user['clevel'].' уровня '; ?>',
                    '<?
                }
                else
                {
                    $user['clevel'] = "XXX";
                    $user['race'] = "*****";
                    echo'<font face="Verdana" size="1">ПРОТИВНИК [';
                    ?><a onmousemove=movehint(event,1) onmouseover="showhint(
                    '<? echo '<font color=000000>'.$user['race'].' '; ?>',
                    '<?                    
                }
            }
            else
            {
			    $this->print_clan($user['clan_id']);
			    print_sklon($user);
                echo'<font face="Verdana" size="1">'.$user['name'].' [';
                ?><a onmousemove=movehint(event,1) onmouseover="showhint(
                '<? echo '<font color=000000>'.$user['race'].' '.$user['clevel'].' уровня '; ?>',
                '<?
            }
			echo '<font color=000000>Жизни: '.$user['HP'].'/'.$user['HP_MAX'].'<br>';
			echo 'Мана: '.$user['MP'].'/'.$user['MP_MAX'].'<br>';
			echo 'Энергия: '.$user['STM'].'/'.$user['STM_MAX'].'<br>';
			echo 'Сила: '.$user['STR'].'<br>';
			echo 'Интеллект '.$user['NTL'].'<br>';
			echo 'Ловкость '.$user['PIE'].'<br>';
			echo 'Защита '.$user['VIT'].'<br>';
			echo 'Мудрость '.$user['SPD'].'<br>';
			echo 'Выносливость '.$user['DEX'].'<br>';
			?>',0,1,event,1)"  onmouseout="showhint('','',0,0,event,1)"><?
		}
		else
		{
			$user['name'] = "***********";
			$user['clevel'] = "XXX";
			echo'<font face="Verdana" size="1">'.$user['name'].' ['; 
		}
		echo ''.$user['clevel'] . '</a>]</font></div></td></tr>';
		echo'</table>';
	}
	
	private function print_right()
	{
		$count_right = count($this->souz)+count($this->prot);//кол-во союзников и противников
		if ($count_right==1)
		{
			//Бой 1 на 1 против бота или игрока
			OpenTable('title');
			$prot = reset($this->prot);
			echo'<div align="right"><font face="Verdana" size="2" color="#f3f3f3"><b>'.$prot['name'].'</b></font>';
			$this->print_clan($prot['clan_id']);
			print_sklon($prot);
			if ($prot['npc']==1)
			{
				echo '<br><img style="max-width:200px;" src = "http://'.IMG_DOMAIN.'/npc/'.$prot['avatar'].'.gif">';
			}
			else
			{
				echo '<br><img src = "http://'.IMG_DOMAIN.'/avatar/'.$prot['avatar'].'">';
			}
			echo'</div>';

			echo '
			<script>var vkogo='.$prot['user_id'].';var prot_id='.$prot['user_id'].';</script>
			<table cellpadding="2" cellspacing="0" width="100%" border="0">';
			
			$this->print_user($prot);
			
			echo '</table>';
			OpenTable('close');
		}
		else
		{
			//Групповой бой
			OpenTable('title');
			echo'<SCRIPT language=javascript src="js/info.js"></SCRIPT>
			<DIV id=hint  style="Z-INDEX: 0; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>
			<center><b><font face=verdana size=2>Групповой бой:</font></b></font><br>Выберите цель</center>
			<script language="JavaScript" type="text/javascript">
			old11="";vkogo=0;prot_id=0;
			function clickn(objName)
			{
				old11.bgColor="";
				objName.bgColor="555555";
				old11=objName;
			}
			</script>';
			echo'<br><center><font color=#00FF00>Союзники:</font><br>';
			// Союзники-игроки
			foreach ($this->souz as $key=>$value)
			{
				$user = $this->souz[$key];
				if ($user['npc']==1) continue;
				$this->print_user_hint($user);
			}
			// Союзники-боты
			foreach ($this->souz as $key=>$value)
			{
				$user = $this->souz[$key];
				if ($user['npc']==0) continue;
				$this->print_user_hint($user);
			}
			echo'<br><center><font color=#00FF00>Противники:</font><br>';
			// Противники-игроки
			foreach ($this->prot as $key=>$value)
			{
				$user = $this->prot[$key];
				if ($user['npc']==1) continue;
				$this->print_user_hint($user);
			}
			// Противники-боты
			foreach ($this->prot as $key=>$value)
			{
				$user = $this->prot[$key];
				if ($user['npc']==0) continue;
				$this->print_user_hint($user);
			}
			OpenTable('close');
		}
	}
	
	public function count_user($state=0)
	{
		//возвращает кол-во игроков боя
		$kol_user = 0;
		foreach ($this->all AS $key=>$value)
		{
			if ($this->all[$key]['npc']==0)
			{
				if ($state!=0)
				{
					if ($this->all[$key]['state']!=$state)
					{
						continue;
					}
				}
				$kol_user++;
			}
		}
		return $kol_user;
	}
	
	public function print_wait()
	{
		myquery("UPDATE combat_users SET time_last_active=".time()." WHERE user_id=".$this->char['user_id']."");
		//запуск расчета хода
		
		// Проверка многокланового боя по 3 ход
		$cont = 1;
		if ($this->combat['combat_type']==4)
		{
			if ($this->combat['hod']<=3)
			{
				$rest = $this->combat['time_last_hod']+$this->timeout-time();
				if ($rest>0)
				{
					$cont = 0;
				}
			}
		}
		if ($cont==1)
		{
			$all_users = mysql_result(myquery("SELECT COUNT(*) FROM combat_users WHERE combat_id=".$this->combat['combat_id'].""),0,0);
			$wait_users = mysql_result(myquery("SELECT COUNT(*) FROM combat_users_state WHERE state=6 AND combat_id=".$this->combat['combat_id'].""),0,0);
			if ($all_users==$wait_users)
			{
				//кол-во игроков боя = кол-во сходивших игроков боя
				//запускаем расчет хода
				/*
				$lockfile = getenv("DOCUMENT_ROOT").'/combat/lock/'.$this->combat['combat_id'].'.lock';
				$f = fopen($lockfile, "wb");
				$locked = 0;
				if (flock($f,LOCK_NB+LOCK_EX,$locked))
				{
					if (!$locked)
					{
						$this->calculate();
						//на всякий случай :-)
						//sleep(3);
						fclose($f);
						unlink($lockfile);
						setLocation("combat.php");
						die();
					}
				}
				*/
				// Сперва попробуем создать строку. Если она уже есть, mysql_affected_rows() вернет 0.
				mysql_query("INSERT IGNORE INTO combat_locked (combat_id, hod) VALUES ('".$this->combat['combat_id']."',".$this->combat['hod'].")");
				if (mysql_affected_rows())
				{
					$this->calculate();  
					setLocation("combat.php");
					die();  
				}
			}
		}
		$this->print_header();
		?>
		<table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width:200px;" valign="top" bgcolor="#000000">
				<?
				$this->print_left();
				$rest_second = $this->combat['time_last_hod']-time()+$this->timeout;
				?>
				</td>
				<td valign="top" bgcolor="#000000">
				<meta http-equiv="refresh" content="15">
				<center>
				<span id="text_wait">Ожидание ходов противников<br></span>
				<span id="text_wait1">До конца хода осталось: <font color=ff0000><b>
				<span id="timerr1"><?=$rest_second;?></span></b></font> секунд</span>
				<script language="JavaScript" type="text/javascript">
				function tim()
				{
					timer = document.getElementById("timerr1");
					timer.innerHTML=timer.innerHTML-1;
					if (timer.innerHTML<=0)
					{
						timer.innerHTML = "";
						txt_wait = document.getElementById("text_wait1");
						txt_wait.innerHTML = "";
						txt_wait = document.getElementById("text_wait");
						txt_wait.innerHTML = "Подождите, выполняется расчет хода боя.";
					}
					else
					{
						window.setTimeout("tim()",1000);
					}
				}
				tim();
				</script>
				<br>
				<input type="button" class="button" value="Обновить" onClick="location.reload()"></center><br /><br /><br /><br /><br />
				<? $this->show_log(); ?>
				</td>
				<td style="width:200px;" valign="top" bgcolor="#000000">
				<?
				$this->print_right();
				?>
				</td>
			</tr>
		</table>
		<?		
	}

	public function print_boy()
	{
		require_once('combat_print.php');
	}
	
    private function check_alies($kogo,$proc)
    {
        if ($this->combat['combat_type']==4)
        {
            //Проверка на союзный клан действует только в многоклановом бою
            if ($this->all[$this->char['user_id']]['clan_id']!=0)
            {
                if ($this->all[$this->char['user_id']]['alies']!=0)
                {
                    if ($this->all[$this->char['user_id']]['alies']==$this->all[$kogo]['clan_id'])
                    {
                        //Если атака по союзному клану, процент хода ставим = 0 только если в бою еще остались противники не из союзного клана
                        $kol_prot = 0;
                        foreach ($this->prot as $key=>$value)
                        {
                            if ($value['clan_id']!=$this->all[$this->char['user_id']]['alies'])
                            {
                                $kol_prot++;
                            }
                        }
                        if ($kol_prot!=0) $proc = 0;
                    }                                        
                }
                if ($this->all[$this->char['user_id']]['clan_id']==$this->all[$kogo]['clan_id'])
                {
                    //По своему клану бить нельзя
                    $proc = 0;
                }
            }
        }
        return $proc;
    }
    
	public function otpravka_hoda($otprav)
	{
		//$otprav имеет формат "Тип действия:Процент хода:Куда направлено действие(атака или защита):ID на кого направлено действие(может быть "" или 0 - тогда на себя):Чем сходил(ID записи)):Тип боевого приема атаки", последним действием идет тип позиции
		//тип действия:
		// a1 - атака кулаком
		// a2 - атака оружием
		// a3 - атака магией
		// a4 - атака артефактом
		// a5 - стрельба из лука
		// a6 - бросок предмета
        // a7 - использование свитка
		// z1 - защита щитом
		// z2 - защита магией
		// z3 - защита артефактом
		// l1 - лечение магией
		// l2 - лечение артефактом
		// l3 - лечение эликсиром
		//куда направлено:
		//     атака:
		//            1 - голова
		//            2 - тело
		//            3 - пах
		//            4 - плечо
		//            5 - ноги
		//     защита:
		//            1 - голова и плечо
		//            2 - тело и пах
		//            3 - пах и ноги     
		//тип атаки: 1 - обычная атака, 2 - прицельный удар, 3 - мощный удар, 4 - круговая защита, 5 - пробивающий удар
		
		$kol_defense_shit = 0;
		$kol_throw = 0;
        $kol_svitok = 0;
		$action_rand = mt_rand(0,999999999);
		if (preg_match("/^[0-9;alz:]+$/",$otprav))
		{
			$array_hod=explode(";",$otprav);
			$sum_proc = 0;
			$str_insert = '';
            $num = 0;
            $action_position = 1;
			foreach($array_hod as $key=>$hod)
			{
                $num++;
                if ($num==1)
                {
                    //позиция игрока в ходе
                    $action_position = (int)$array_hod[$key];
                }
                else
                {                   
				    $ar_hod = explode(":",$array_hod[$key]);                   
				    $action_type=trim($ar_hod[0]);
				    $action_proc=(int)$ar_hod[1];
				    $action_kuda=(int)$ar_hod[2];
				    $action_kogo=(int)$ar_hod[3];
				    $action_chem=(int)$ar_hod[4];
				    $action_priem=(int)$ar_hod[5];
				    if (($sum_proc+$action_proc)>100)
				    {
					    $action_proc = 100-$sum_proc;
				    }
                    if (($action_position!=2)AND($action_position!=3)) $action_position=1;
				    if ($this->char['user_id']==612)
				    {
					    //$say_chat =  ''.$action_type.':'.$action_proc.':'.$action_kuda.':'.$action_kogo.':'.$action_chem.':'.$action_priem;
					    //$say = iconv("Windows-1251","UTF-8//IGNORE","ОТЛАДКА: <span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say_chat."</b></span>");
					    //myquery("INSERT INTO game_log (`message`,`date`,`fromm`,`too`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1,612)");
				    }

				    //делаем проверки на возможность конкретного действия
				    if ($action_type=="z1")
				    {
					    //защита щитом не более 2 раз
					    $kol_defense_shit++;
					    if ($kol_defense_shit>2)
					    {
						    $action_proc = 0;
					    }
				    }
				    $action_type_sort = 0;
				    if (($action_proc>0)OR($action_type=="a5")OR($action_type=="a6")OR($action_type=="a7"))
				    {
					    switch($action_type)
					    {
						    case "a1":
						    {
							    //атака кулаком
							    if ($action_priem<1) $action_priem = 1;
							    if ($action_priem>3) $action_priem = 1;
							    if ($action_priem==2 AND $this->char['MS_KULAK']<3) $action_priem = 1;
							    if ($action_priem==3 AND $this->char['MS_KULAK']<6) $action_priem = 1;
							    $action_type = 11;
							    $action_type_sort = 1;
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a2":
						    {
							    if ($action_priem<1) $action_priem = 1;
							    if ($action_priem>5) $action_priem = 1;
							    if ($action_priem==2 AND $this->char['MS_WEAPON']<2) $action_priem = 1;
							    if ($action_priem==3 AND $this->char['MS_WEAPON']<6) $action_priem = 1;
                                if ($action_priem==5 AND $this->char['MS_WEAPON']<4) $action_priem = 1;
							    if ($action_priem==4 AND $this->char['MS_WEAPON']<8) $action_priem = 1;
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=1
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 12;
							    $action_type_sort = 1; 
							    if ($action_priem == 4)
							    {
								    //круговая защита оружием - кидаем в защиту
                                    if ($action_proc != 100)
                                    {
                                        $action_proc = 0;
                                        $action_priem = 1;
                                    }
                                    else
                                    {
								        $action_type = 21; 
								        $action_kogo = 0; 
								        $action_type_sort = 2; 
                                    }
							    }
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a3":
						    {
                                $action_priem = 1;
							    $selspets = myquery("SELECT id FROM game_spets_item WHERE user_id=".$this->char['user_id']." AND id=".$action_chem."");
							    if ($selspets==false OR mysql_num_rows($selspets)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 13;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a4":
						    {
                                $action_priem = 1;
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=3 
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 14;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a5":
						    {
							    $action_priem = 1;
							    $selused = myquery("
							    SELECT item_id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = -1;
							    }
							    else
							    {
								    list($item_id) = mysql_fetch_array($selused);
								    $check_luk = myquery("
								    SELECT game_items.item_id FROM game_items,game_items_factsheet WHERE
								    game_items.user_id=".$this->char['user_id']." 
								    AND game_items_factsheet.id = ".$item_id."
								    AND game_items_factsheet.quantity=game_items.item_id
								    AND game_items.priznak=0
								    AND game_items.item_uselife>0
								    AND game_items.used=4");
								    if ($check_luk==false OR mysql_num_rows($check_luk)==0)
								    {
									    $action_proc = -1;
								    }
								    else
								    {
									    list($item_id) = mysql_fetch_array($check_luk);
									    list($item_type)=mysql_fetch_array(myquery("SELECT type FROM game_items_factsheet WHERE id=".$item_id.""));
									    if ($item_type!=18) $action_proc = -1;
								    }
							    }
							    $kol_throw++;
							    if ($kol_throw>1) $action_proc = -1;
							    $action_type = 15;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
						    case "a6":
						    {
							    $action_priem = 1;
							    $selused = myquery("
							    SELECT item_id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = -1;
							    }
							    else
							    {
								    list($item_id) = mysql_fetch_array($selused);
								    $check_throw = myquery("
								    SELECT type_weapon,type_weapon_need FROM game_items_factsheet WHERE id=".$item_id." AND type=19");
								    if ($check_throw==false OR mysql_num_rows($check_throw)==0)
								    {
									    $action_proc = -1;
								    }
								    else
								    {
									    list($type_weapon,$type_weapon_need) = mysql_fetch_array($check_throw);
									    if ($type_weapon!=0)
									    {
										    $MS = 0;
										    if($type_weapon==1) {
											    $MS = $this->char['MS_KULAK'];
										    }
										    if($type_weapon==2) {
											    $MS = $this->char['MS_LUK'];
										    }
										    if($type_weapon==3) {
											    $MS = $this->char['MS_SWORD'];
										    }
										    if($type_weapon==4) {
											    $MS = $this->char['MS_AXE'];
										    }
										    if($type_weapon==5) {
											    $MS = $this->char['MS_SPEAR'];
										    }
										    if($type_weapon==6) {
											    $MS = $this->char['MS_THROW'];
										    }
										    if ($MS<$type_weapon_need) $action_proc = -1; 
									    }
								    }
							    }
							    $kol_throw++;
							    if ($kol_throw>1) $action_proc = -1;
							    $action_type = 16;
							    $action_type_sort = 1; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
						    }
						    break;
						    
                            case "a7":
                            {
                                $action_priem = 1;
                                $selused = myquery("
                                SELECT item_id 
                                FROM game_items 
                                WHERE user_id=".$this->char['user_id']." 
                                AND id = ".$action_chem." AND item_id IN (".item_id_svitok_light_usil.",".item_id_svitok_medium_usil.",".item_id_svitok_hard_usil.",".item_id_svitok_absolut_usil.",".item_id_svitok_light_sopr.",".item_id_svitok_medium_sopr.",".item_id_svitok_hard_sopr.",".item_id_svitok_absolut_sopr.")
                                AND priznak=0");
                                if ($selused==false OR mysql_num_rows($selused)==0)
                                {
                                    $action_proc = -1;
                                }
                                else
                                {
                                    list($item_id) = mysql_fetch_array($selused);
                                }
                                $kol_svitok++;
                                if ($kol_svitok>1) $action_proc = -1;
                                $action_type = 17;
                                $action_type_sort = 2; 
                                $action_proc = $this->check_alies($action_kogo,$action_proc);
                            }
                            break;
                            
						    case "z1":
						    {
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=4
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 21;
							    $action_type_sort = 3; 
						    }
						    break;
						    
						    case "z2":
						    {
							    $selspets = myquery("SELECT id FROM game_spets_item WHERE user_id=".$this->char['user_id']." AND id=".$action_chem."");
							    if ($selspets==false OR mysql_num_rows($selspets)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 22;
							    $action_type_sort = 3; 
						    }
						    break;
						    
						    case "z3":
						    {
							    $selused = myquery("
							    SELECT id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=3 
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 23;
							    $action_type_sort = 3; 
						    }
						    break;
						    
						    case "l1":
						    {
							    $selspets = myquery("SELECT id FROM game_spets_item WHERE user_id=".$this->char['user_id']." AND id=".$action_chem."");
							    if ($selspets==false OR mysql_num_rows($selspets)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 31;
							    $action_type_sort = 4; 
						    }
						    break;
						    
						    case "l2":
						    {
							    $selused = myquery("
							    SELECT game_items.id 
							    FROM game_items 
							    WHERE game_items.user_id=".$this->char['user_id']." 
							    AND game_items.id = ".$action_chem."
							    AND game_items.item_uselife>0
							    AND game_items.used=3
							    AND game_items.priznak=0");
							    if ($selused==false OR mysql_num_rows($selused)==0)
							    {
								    $action_proc = 0;
							    }
							    $action_type = 32;
							    $action_type_sort = 4; 
						    }
						    break;
						    
						    case "l3":
						    {
							    $already_eliksir = mysql_result(myquery("SELECT eliksir FROM combat_users WHERE user_id=".$this->char['user_id'].""),0,0);
							    if ($already_eliksir!=1)
							    {
								    $selused = myquery("
								    SELECT game_items.id 
								    FROM game_items,game_items_factsheet 
								    WHERE game_items.user_id=".$this->char['user_id']." 
								    AND game_items.id = ".$action_chem."
								    AND game_items_factsheet.id=game_items.item_id
								    AND game_items_factsheet.type=13
                                    AND game_items.used>0
								    AND game_items.item_uselife>0
								    AND game_items.used IN (12,13,14) 
								    AND game_items.priznak=0");
								    if ($selused==false OR mysql_num_rows($selused)==0)
								    {
									    $action_proc = 0;
								    }
								    $action_type = 33;
								    $action_type_sort = 4; 
								    if ($action_proc<100)
								    {
									    //эликсиры используются всегда на 100%
									    $action_proc = 0;
								    }
							    }
							    else
							    {
								    $action_proc = 0;
							    }
						    }
						    break;
						    
						    default:
						    {
							    $action_proc = 0;
						    }
						    break;
					    }
				    }
				    if ($action_type>=21 AND $action_type<=33)
				    {
					    //при лечении и защите если на кого = 0, значит на себя
					    //нельзя лечить и защищать ботов
					    if ($action_kogo==0)
					    {
						    $action_kogo = $this->char['user_id'];
					    }
					    if (!isset($this->all[$action_kogo]) OR $this->all[$action_kogo]['npc']==1)
					    {
						    //действие по боту
						    $action_proc = 0;
					    }
				    }
				    if ($action_type==21)
				    {
					    //если защищаем не себя - уменьшаем в 2 раза процент действий
					    if ($action_kogo!=$this->char['user_id'])
					    {
						    $action_proc=(int)$action_proc/2;
					    }
				    }
				    if ($action_proc>0 OR (($action_proc==0) AND (($action_type==15) OR ($action_type==16) OR ($action_type==17))))
				    {
					    //ход разрешен
					    //делаем запись в БД
					    $sum_proc+=$action_proc;
					    $str_insert.="(".$this->combat['combat_id'].",".$this->combat['hod'].",".$this->char['user_id'].",".$action_type.",".$action_chem.",".$action_kogo.",".$action_kuda.",".$action_proc.",".$action_priem.",".$action_rand.",".$action_type_sort.",".$action_position."),";
				    }
                }
			}
			if ($str_insert!="")
			{
				$str_insert = substr($str_insert,0,-1);
				myquery("INSERT INTO combat_actions (combat_id,hod,user_id,action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position) VALUES ".$str_insert.";");
			}
			$tek_time = time();
			combat_setFunc($this->char['user_id'],6,$this->combat['combat_id']);
			myquery("update combat_users set time_last_active=$tek_time where user_id=".$this->char['user_id']."");
		}
	}
	
	public function print_begin()
	{
		$rest_time = $this->combat['time_last_hod']-time();
		if ($rest_time<=0)
		{
			combat_setFunc($this->char['user_id'],5,$this->combat['combat_id']);
			foreach ($this->souz as $key=>$value)
			{
				combat_setFunc($key,5,$this->combat['combat_id']);    
			}
			foreach ($this->prot as $key=>$value)
			{
				combat_setFunc($key,5,$this->combat['combat_id']);    
			}
		}
		else
		{
			$this->print_header();
			?>
			<meta http-equiv="refresh" content="15">
			<center><br>До начала боя осталось <b><font color=#FF0000><?=$rest_time;?></font></b> секунд<br><br><br><br>
			<?
		}
	}
	
	//************************************************************************************
	// БЛОК ОБСЧЕТА ХОДА С ИЗМЕНЕНИЕМ СОСТОЯНИЙ ИГРОКОВ
	// при обсчете использовать prot* и souz* запрещено!
	//************************************************************************************
	
	private function make_hod_npc($npc_id)
	{
		$npc_temp=$this->all[$npc_id]['npc_id_template'];
		$kol_attack=myquery("Select * From game_npc_set_option Where npc_id=$npc_temp and opt_id=3");
        //Проверим, не с ботом НЕЧТО ли идет бой

		if (mysql_num_rows($kol_attack)>0)
		{
			$kol_attack=1;
			$prot_array = array();
            foreach ($this->all AS $key=>$value)
            {                                                                                                                                                         $action_rand = mt_rand(0,999999999);
                if ($this->all[$npc_id]['side']==$this->all[$key]['side']) continue;
                if ($this->all[$key]['join']==1) continue;
                if ($this->all[$npc_id]['HP']>0)
                {
                    $prot_array[] = $key;
                }
            }    
            if (sizeof($prot_array)>0)
            {
                $prot_npc = $prot_array[mt_rand(0,sizeof($prot_array)-1)];
            }     
		}

		// TODO
		// Игроков может быть несколько - для всех прогоняем алгоритм
		// Но это все хорошо бы переделать на функции + сделать функцию выбора кого атаковать
		// на выходе нее будет массив
		// (user_id => %-от атаки)
		foreach ($this->all AS $key=>$value)
		{   
			if ($kol_attack==1 AND $key!=$prot_npc) continue; 
            $action_rand = mt_rand(0,999999999);
			if ($this->all[$npc_id]['side']==$this->all[$key]['side']) continue;
			if ($this->all[$key]['join']==1) continue;
			if ($this->all[$npc_id]['HP']>0)
			{
				if ($this->all[$npc_id]['PIE']==$this->all[$key]['PIE'] AND $this->all[$npc_id]['SPD']==$this->all[$key]['SPD'])
				{
					//Для Подземки хитрый алгоритм - усложнение существования игроков :-)
					$act[1]['action']=0;
					$act[1]['procent']=0;
					$act[2]['procent']=0;
					$act[2]['action']=0;
					if ($this->all[$npc_id]['STR']>$this->all[$npc_id]['NTL'])
					{
						//удар оружием
						$act[1]['action']=1;
						$act[1]['procent']=100;
					}
					elseif ($this->all[$npc_id]['NTL']>$this->all[$npc_id]['STR'])
					{
						//удар магией
						$act[1]['action']=2;
						$act[1]['procent']=100;
					}
					else
					{
						//удар артом
						$act[1]['action']=3;
						$act[1]['procent']=100;
					}
				}
				else
				{
					$shield_defense = $this->all[$npc_id]['clevel']*2+10;
					if ($this->all[$key]['PIE']>=1*$this->all[$npc_id]['PIE'])
					{
						$A = true;
					}
					else
					{
						$A = false;
					}
					if ($this->all[$key]['SPD']>=1*$this->all[$npc_id]['SPD'])
					{
						$B = true;
					}
					else
					{
						$B = false;
					}
					if ($this->all[$key]['NTL']>=35)
					{
						$C = true;
					}
					else
					{
						$C = false;
					}
					if (($this->all[$key]['VIT']*2+10)>$this->all[$npc_id]['STR'])
					{
						$D = true;
					}
					else
					{
						$D = false;
					}
					$check=myquery("Select * From game_npc_set_option Where npc_id=$npc_temp and opt_id=2");
					if (mysql_num_rows($check)==0)
					{
						if ($this->all[$npc_id]['HP']<=0.24*$this->all[$npc_id]['HP_MAX']) $E=0.8;
						elseif ($this->all[$npc_id]['HP']<=0.50*$this->all[$npc_id]['HP_MAX']) $E=0.6;
						elseif ($this->all[$npc_id]['HP']<=0.75*$this->all[$npc_id]['HP_MAX']) $E=0.4;
						else $E=0.2;
					}
					else
					{
						$E=0;	
					}
					

					$act[1]['action'] = 0;
					$act[1]['procent'] = 0;
					$act[1]['spell'] = 0;
					$act[2]['action'] = 0;
					$act[2]['procent'] = 0;
					$act[2]['spell'] = 0;

					$R = mt_rand(0,10);
					if ($A==true)
					{
						if ($B==false AND $C==false)
						{
							//магия
							$act[1]['action'] = 2;$act[1]['procent']=(1-$E)*100;
						}
						else
						{
							//арт
							$act[1]['action'] = 3;$act[1]['procent']=(1-$E)*100;
						}
					}
					else
					{
						if ($D==false)
						{
							//оружие
							$act[1]['action'] = 4;$act[1]['procent']=(1-$E)*100;
						}
						else
						{
							if ($B==false AND $C==false)
							{
								//магия
								$act[1]['action'] = 2;$act[1]['procent']=(1-$E)*100;
							}
							else
							{
								//арт
								$act[1]['action'] = 3;$act[1]['procent']=(1-$E)*100;
							}
						}
					}
					if ($R<=4 AND $E>0.5)       {$act[2]['action'] = 5;$act[2]['procent']=$E*100;}
					elseif ($R<=4 AND $E<=0.5)  {                      $act[1]['procent']=100;}
					elseif ($R>4 AND $R<=6 and mysql_num_rows($check)==0) {$act[2]['action'] = 5;$act[2]['procent']=$E*100;}
					elseif ($R>6 AND $R<=8)     {$act[2]['action'] = 6;$act[2]['procent']=$E*100;}
					elseif ($R>8 AND $R<=10)    {$act[2]['action'] = 7;$act[2]['procent']=$E*100;}
				}

				if ($this->all[$key]['clevel']<=10) {$spell_level_min = 1; $spell_level_max = 5;}
				elseif ($this->all[$key]['clevel']<=19) {$spell_level_min = 5; $spell_level_max = 12;}
				elseif ($this->all[$key]['clevel']<=25) {$spell_level_min = 8; $spell_level_max = 15;}
				elseif ($this->all[$key]['clevel']<=29) {$spell_level_min = 11; $spell_level_max = 15;}
				elseif ($this->all[$key]['clevel']<=31) {$spell_level_min = 13; $spell_level_max = 15;}
				else {$spell_level_min = 14; $spell_level_max = 15;};

				if ($act[1]['procent']>100)  $act[1]['procent']=100;
				if ($act[2]['procent']>100)  $act[2]['procent']=100;
				if (($act[1]['procent']+$act[2]['procent'])>100) $act[2]['procent']=100-$act[1]['procent'];

				if ($this->all[$key]['clevel']<=5)
				{
					$act[1]['action']=1;
					$act[1]['procent']=100;
					$act[2]['procent']=0;
					$act[2]['action']=0;
				}
                                          
				for ($i=1;$i<=2;$i++)
				{
					if ($act[$i]['procent']>0)
					{
						if ($act[$i]['action']==5) $act[$i]['action']=6;
						if ($act[$i]['action']==2 OR $act[$i]['action']==5 OR $act[$i]['action']==6)
						{
							if ($act[$i]['action']==2) $img = 'Атака';
							if ($act[$i]['action']==5) $img = 'Лечение';
							if ($act[$i]['action']==6) $img = 'Защита';
							$sel_spell = myquery("SELECT * FROM game_spets WHERE img='$img' AND
							(
							(war>=$spell_level_min AND war<=$spell_level_max) OR
							(music>=$spell_level_min AND music<=$spell_level_max) OR
							(cook>=$spell_level_min AND cook<=$spell_level_max) OR
							(art>=$spell_level_min AND art<=$spell_level_max) OR
							(explor>=$spell_level_min AND explor<=$spell_level_max) OR
							(craft>=$spell_level_min AND craft<=$spell_level_max) OR
							(pet>=$spell_level_min AND pet<=$spell_level_max) OR
							(card>=$spell_level_min AND card<=$spell_level_max) OR
							(unknow>=$spell_level_min AND music<=$spell_level_max)
							)
							");
							jump_random_query($sel_spell);
							$spell = mysql_fetch_assoc($sel_spell);
							$act[$i]['spell'] = $spell['id'];
							if ($this->all[$npc_id]['MP']<($spell['mana']+$spell['mana_deviation'])) 
							{
								$act[$i]['action'] = 3;
								$act[$i]['spell'] = 0;
							}
						}
						$kud_attack = mt_rand(1,5);
						$kud_defense = mt_rand(1,3);
						switch ($act[$i]['action'])
						{
							case 1:   //атака оружием
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								12,0,$key,$kud_attack,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 2:   //магическая атака
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								13,".$act[$i]['spell'].",$key,0,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 3:   // атака артом
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								14,0,$key,$kud_attack,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 4:   //атака оружием
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								12,0,$key,$kud_attack,".$act[$i]['procent'].",0,$action_rand,1,".$this->all[$npc_id]['position'].")");
							break;
							case 5:   //лечение магией
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								31,".$act[$i]['spell'].",$key,0,".$act[$i]['procent'].",0,$action_rand,3,".$this->all[$npc_id]['position'].")");
							break;
							case 6:   //защита магией
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								22,".$act[$i]['spell'].",$npc_id,0,".$act[$i]['procent'].",0,$action_rand,3,".$this->all[$npc_id]['position'].")");
							break;
							case 7:   //защита щитом
								myquery("INSERT INTO combat_actions (
								combat_id,hod,user_id,
								action_type,action_chem,action_kogo,action_kuda,action_proc,action_priem,action_rand,action_type_sort,position)
								VALUES
								(".$this->combat['combat_id'].",".$this->combat['hod'].",$npc_id,
								21,0,$npc_id,$kud_defense,".$act[$i]['procent'].",0,$action_rand,3,".$this->all[$npc_id]['position'].")");
							break;
						}
					}
				}
			}
		}
	}
	
	private function get_bron($attack_kuda,$type_weapon,$attack_kogo)
	{
		$used = 0;
		switch ($attack_kuda)
		{
			case 1: //в голову
			{
				$used = 6;
			}
			break;
			case 2: //в тело
			{
				$used = 5;
			}
			break;
			case 3: //в пах
			{
				$used = 8;
			}
			break;
			case 4: //в плечо
			{
				$used = 2;
			}
			break;
			case 5: //в ноги
			{
				$used = 5;
			}
			break;
		}
		if ($used==0) return 0;

		if (!isset($this->all[$attack_kogo]['bron'][$used])) return 0;
		$def_type = $this->all[$attack_kogo]['bron'][$used]['def_type'];
		$def_index = $this->all[$attack_kogo]['bron'][$used]['def_index']; 
		
		switch ($def_type)
		{
			case 0: //одежда
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*1;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*1;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*0;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*0;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*0;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*1;
					}
					break;
				}
			}
			break;
			case 1: //кожанная
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*1.25;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*1;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*0.5;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*1.5;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*1;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*1;
					}
					break;
				}
			}
			break;
			case 2: //кольчужная
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*1.5;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*0.5;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*1.5;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*0.5;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*1;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*0.5;
					}
					break;
				}
			}
			break;
			case 3: //латы
			{
				switch ($type_weapon)
				{
					case 1: //Кулачное
					{
						return $def_index*2;
					}
					break;
					case 2: //Стрелковое
					{
						return $def_index*0.5;
					}
					break;
					case 3: //Рубящее
					{
						return $def_index*1.5;
					}
					break;
					case 4: //Дробящее
					{
						return $def_index*1;
					}
					break;
					case 5: //Колющее
					{
						return $def_index*0.5;
					}
					break;
					case 6: //Метательное
					{
						return $def_index*0.5;
					}
					break;
				}
			}
			break;
		}
		return 0;
	}

	private function get_koeff_from_sklon($kto,$kogo,$clan_id)
	{
		$add = 0;
		if ($kto==0 AND $clan_id>0)
		{
			$clan_sklon = mysql_result(myquery("SELECT sklon FROM game_clans WHERE clan_id=$clan_id"),0,0);
			if ($clan_sklon==0)
			{
				return 0.8;
			}
		}
		if ($kto==$this->add_exp_for_sklon) $add=0.2;
		if ($kto==1)
		{
			switch ($kogo)
			{
				case 0: return 1.2+$add;break;
				case 1: return 0.8+$add;break;
				case 2: return 1.2+$add;break;
				case 3: return 1.2+$add;break;
			}
		}
		if ($kto==2)
		{
			switch ($kogo)
			{
				case 0: return 1+$add;break;
				case 1: return 1.2+$add;break;
				case 2: return 0.8+$add;break;
				case 3: return 1.4+$add;break;
			}
		}
		if ($kto==3)
		{
			switch ($kogo)
			{
				case 0: return 1+$add;break;
				case 1: return 1.2+$add;break;
				case 2: return 1.4+$add;break;
				case 3: return 0.8+$add;break;
			}
		}
		return 1+$add;
	}
    
    private function calc_position($damage_hp,$kto,$kogo)
    {
        switch ($this->all[$kto]['position'])
        {
            case 1:
            {
                if ($this->all[$kogo]['position']==2)
                {
                    $damage_hp=ceil($damage_hp*1.2);
                }
            }
            break;
            
            case 2:
            {
            }
            break;
            
            case 3:
            {
                if ($this->all[$kogo]['position']==2)
                {
                    $damage_hp=ceil($damage_hp*0.25);
                }
                else
                {
                    $damage_hp=ceil($damage_hp*1.4);                   
                }               
            }
            break;
            
        }
        if ($this->all[$kogo]['svit_sopr']>0)
        {
            $damage_hp=max(0,ceil($damage_hp*(1-$this->all[$kogo]['svit_sopr']/100)));
        }
        return $damage_hp;
    }

	private function calculate() //расчет хода
	{	
		$da = getdate();
		$day = $da['mday'];
		$month = $da['mon'];

		$this->k_exp = 1;
		$this->k_gp = 1;
		if ($month==12 AND $day>=30) {$this->k_exp=1.5;};
		if ($month==1 AND $day<=5) {$this->k_exp=1.5;};
		if ($month==12 AND $day==31) {$this->k_exp=2.0;};
		if ($month==1 AND $day==1) {$this->k_exp=2.0;};

		if ($this->combat['combat_type']!=4) $this->k_exp=$this->k_exp/3;
		
		$sel_bron = myquery("SELECT game_items.user_id,game_items.used,game_items_factsheet.def_type,game_items_factsheet.def_index FROM game_items,game_items_factsheet,combat_users WHERE game_items.priznak=0 AND combat_users.join=0 AND game_items.user_id=combat_users.user_id AND combat_users.combat_id=".$this->combat['combat_id']." AND game_items.item_id=game_items_factsheet.id");
		while ($bron = mysql_fetch_array($sel_bron))
		{
			$this->all[$bron['user_id']]['bron'][$bron['used']]['def_type'] = $bron['def_type'];    
			$this->all[$bron['user_id']]['bron'][$bron['used']]['def_index'] = $bron['def_index'];    
		} 
		
		if (isset($this->char))
		{
			$this->log[$this->char['user_id']][0]['action'] = 1;
		} 
		
		foreach ($this->all as $key=>$value)
		{
			$this->all[$key]['defense']['HP']['all']                 =0;
			$this->all[$key]['defense']['HP']['golova']              =0;
			$this->all[$key]['defense']['HP']['telo']                =0;
			$this->all[$key]['defense']['HP']['pah']                 =0;
			$this->all[$key]['defense']['HP']['plecho']              =0;
			$this->all[$key]['defense']['HP']['nogi']                =0;
			$this->all[$key]['defense']['MP']                        =0;
			$this->all[$key]['defense']['STM']                       =0;
			$this->all[$key]['defense_all']['HP']['all']             =0;
			$this->all[$key]['defense_all']['HP']['golova']          =0;
			$this->all[$key]['defense_all']['HP']['telo']            =0;
			$this->all[$key]['defense_all']['HP']['pah']             =0;
			$this->all[$key]['defense_all']['HP']['plecho']          =0;
			$this->all[$key]['defense_all']['HP']['nogi']            =0;
			$this->all[$key]['defense_all']['MP']                    =0;
			$this->all[$key]['defense_all']['STM']                   =0;
			$this->all[$key]['proc']                                 =0;
            $this->all[$key]['svit_usil']                            =1;
            $this->all[$key]['svit_sopr']                            =0;
            $check_svit_sopr = myquery("SELECT action_chem FROM combat_actions WHERE hod=".$this->combat['hod']." AND combat_id=".$this->combat['combat_id']." AND user_id=".$key." AND action_type=17 LIMIT 1");
            if (mysql_num_rows($check_svit_sopr)>0)
            {
                list($item_svitok) = mysql_fetch_array($check_svit_sopr);
                if ($item_svitok == item_id_svitok_light_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 25;
                }
                if ($item_svitok == item_id_svitok_medium_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 50;
                }
                if ($item_svitok == item_id_svitok_hard_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 75;
                }
                if ($item_svitok == item_id_svitok_absolut_sopr)
                {
                    $this->all[$key]['svit_sopr'] = 100;
                }
            }
            $this->all[$key]['position']=mysqlresult(myquery("SELECT position FROM combat_actions WHERE hod=".$this->combat['hod']." AND combat_id=".$this->combat['combat_id']." AND user_id=".$key." LIMIT 1"));
            $this->log[$key][0]['action'] = 77+$this->all[$key]['position'];
			
			if ($this->all[$key]['npc']==1)
			{            
                $this->all[$key]['position'] = mt_rand(1,3);
                $this->log[$key][0]['action'] = 77+$this->all[$key]['position'];
				$this->make_hod_npc($key);
			}
		}
		
		$sort_log = array();
		$sel_action = myquery("SELECT * FROM combat_actions WHERE hod=".$this->combat['hod']." AND combat_id=".$this->combat['combat_id']." ORDER BY action_type_sort DESC, action_rand ASC");
		while ($act = mysql_fetch_array($sel_action))
		{
			$kto = $act['user_id'];
			$kogo = $act['action_kogo'];
			$sort_log[$kto] = $act['action_rand'];
			if ($this->all[$kto]['HP']<=0)
			{
				$this->log[$kto][0]['action'] = 4;
				continue;
			}
			if (isset($this->all[$kogo]))
			{
				if ($this->all[$kogo]['HP']<=0)
				{
					$kogo_name = $this->all[$kogo]['name']; 
					if ($this->all[$kogo]['pol']=='female')
					{
						$this->log[$kto][]['action'] = 61;
					}
					else
					{
						$this->log[$kto][]['action'] = 6;
					}
					$index = sizeof($this->log[$kto])-1;
					$this->log[$kto][$index]['na_kogo'] = $kogo;
					$this->log[$kto][$index]['na_kogo_name'] = $kogo_name;
					continue;
				}
			}
			else
			{
				$kogo_name = get_user("name",$kogo,0); 
				$this->log[$kto][]['action'] = 6;
				$index = sizeof($this->log[$kto])-1;
				$this->log[$kto][$index]['na_kogo'] = $kogo;
				$this->log[$kto][$index]['na_kogo_name'] = $kogo_name;
				continue;
			}

			if ($this->all[$kto]['side']!=$this->all[$kogo]['side'])
			{
				if ($act['action_type']>=30 AND $act['action_type']<40)
				{
					$this->log[$kto][]['action'] = 57;
					continue;
				}
				elseif ($act['action_type']>=20 AND $act['action_type']<30)
				{
					$this->log[$kto][]['action'] = 58;
					continue;
				}
			}
			else
			{
				if ($act['action_type']>=10 AND $act['action_type']<20)
				{
					$this->log[$kto][]['action'] = 60;
					continue;
				}
			}

			$kuda = '';
			if ($act['action_type']>=20 AND $act['action_type']<30)
			{  
				if ($act['action_type']<>22)
				{
					switch ($act['action_kuda'])
					{
						case 1:
						$kuda='голову и плечо';
						break;
						case 2:
						$kuda='тело и пах';
						break;
						case 3:
						$kuda='пах и ноги';
						break;
					}
				}
			}

			if ($act['action_type']>=10 AND $act['action_type']<20)
			{ 
				if ($this->all[$kogo]['HP']<=0)
				{
					continue;
				}
				if ($this->all[$kto]['HP']<=0)
				{
					continue;
				}
				switch ($act['action_kuda'])
				{
					case 1:
					$kuda='голову';
					break;
					case 2:
					$kuda='тело';
					break;
					case 3:
					$kuda='пах';
					break;
					case 4:
					$kuda='плечо';
					break;
					case 5:
					$kuda='ноги';
					break;
				}
			} 

			$damage_hp=0;
			$damage_mp=0;
			$damage_stm=0;

			if ($this->all[$kto]['npc']==1)
			{
				$Npc = new Npc($kto);   
			}
            
            $k_svitok_usil = 1;
            $k_svitok_sopr = 1;
			
			switch($act['action_type'])
			{
				//БЛОК ЛЕЧЕНИЯ
				case 31:
				{
					//лечение магическим заклинанием
					if ($this->all[$kto]['npc']==1)
						$select=myquery("select * from game_spets where id=".$act['action_chem']."");
					else
						$select=myquery("select * from game_spets_item where id=".$act['action_chem']." and user_id=$kto and img='Лечение'");
					if (mysql_num_rows($select))
					{
						$lech=mysql_fetch_array($select);
						$minus_mp = ceil($act['action_proc']/100*$this->decrease*mt_rand($lech['mana']-$lech['mana_deviation'],$lech['mana']+$lech['mana_deviation']));
						$minus_hp = ceil($act['action_proc']/100*$this->decrease*mt_rand($lech['hp']-$lech['hp_deviation'],$lech['hp']+$lech['hp_deviation']));
						$minus_stm = ceil($act['action_proc']/100*$this->decrease*mt_rand($lech['stm']-$lech['stm_deviation'],$lech['stm']+$lech['stm_deviation']));
						if (($this->all[$kto]['MP']>=$minus_mp AND $this->all[$kto]['HP']>=$minus_hp AND $this->all[$kto]['STM']>=$minus_stm AND $this->all[$kto]['npc']==0) OR ($this->all[$kto]['MP']>=$minus_stm AND $this->all[$kto]['npc']==1))
						{
							$lech_stm = 0;
							$lech_hp = 0;
							$lech_mp = 0;
							if ($lech['indx']!=0)
								$lech_hp=floor(mt_rand($lech['indx']-$lech['deviation']+$this->all[$kto]['NTL'],$lech['indx']+$lech['deviation']+$this->all[$kto]['NTL'])*$act['action_proc']/100*$this->decrease);
							if ($lech['indx_mp']!=0)
								$lech_mp=floor(mt_rand($lech['indx_mp']-$lech['indx_mp_deviation']+$this->all[$kto]['NTL'],$lech['indx_mp']+$lech['indx_mp_deviation']+$this->all[$kto]['NTL'])*$act['action_proc']/100*$this->decrease);
							if ($lech['indx_stm']!=0)
								$lech_stm=floor(mt_rand($lech['indx_stm']-$lech['indx_stm_deviation']+$this->all[$kto]['NTL'],$lech['indx_stm']+$lech['indx_stm_deviation']+$this->all[$kto]['NTL'])*$act['action_proc']/100*$this->decrease);

							//проверим промах для мага
							$random = mt_rand(1,100);
							$sel_magic = myquery("SELECT * FROM game_spets WHERE name='".$lech['name']."'");
							$level=0;
							$promah=0;
							while ($magic = mysql_fetch_array($sel_magic))
							{
								if ($magic['tip']==0)
								   $level = $magic['war'];
								elseif ($magic['tip']==1)
									$level = $magic['music'];
								elseif ($magic['tip']==2)
									$level = $magic['cook'];
								elseif ($magic['tip']==3)
									$level = $magic['art'];
								elseif ($magic['tip']==4)
									$level = $magic['explor'];
								elseif ($magic['tip']==5)
									$level = $magic['craft'];
								elseif ($magic['tip']==6)
									$level = $magic['card'];
								elseif ($magic['tip']==7)
									$level = $magic['pet'];
								elseif ($magic['tip']==8)
									$level = $magic['unknow'];
							}

							$check = 75 - $level - $this->all[$kogo]['SPD']*2 + $this->all[$kto]['SPD']*2 + $this->all[$kto]['lucky'] - $this->all[$kogo]['lucky'];
							if ($random>$check OR $random<=5-$this->all[$kto]['lucky'])
							{
								$lech_hp=0;
								$lech_mp=0;
								$lech_stm=0;
								$minus_hp=0;
								$minus_mp=0;
								$minus_stm=0;
								$promah=1;
							}

							if ($promah==1)
							{
								$this->log[$kto][]['action'] = 37;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['name'] = $lech['mode'];
								$this->log[$kto][$index]['mode'] = $lech['name'];
							}
							else
							{
								$this->log[$kto][]['action'] = 7;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['name'] = $lech['name'];
								$this->log[$kto][$index]['mode'] = $lech['mode'];
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $lech_hp;
								$this->log[$kto][$index]['add_mp'] = $lech_mp;
								$this->log[$kto][$index]['add_stm'] = $lech_stm;
								$this->log[$kto][$index]['minus_hp'] = $minus_hp;
								$this->log[$kto][$index]['minus_mp'] = $minus_mp;
								$this->log[$kto][$index]['minus_stm'] = $minus_stm;
							}

							$this->all[$kto]['HP']-=$minus_hp;
							$this->all[$kto]['MP']-=$minus_mp;
							$this->all[$kto]['STM']-=$minus_stm;
							$this->all[$kogo]['HP']+=$lech_hp;
							$this->all[$kogo]['MP']+=$lech_mp;
							$this->all[$kogo]['STM']+=$lech_stm;
						}
						else
						{
							$this->log[$kto][]['action'] = 8;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['chem'] = $lech['id'];
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 9;
					}
				}
				break;

				case 32:
				{
					//лечение артефактом
					$minus_STM = ceil($act['action_proc']/100*16);
					if ($this->all[$kto]['STM']>=$minus_STM)
					{
						$select=myquery("select game_items.id,game_items.item_uselife,game_items_factsheet.name,game_items_factsheet.id AS item_id,game_items_factsheet.indx,game_items.item_uselife,game_items.count_item from game_items, game_items_factsheet where game_items_factsheet.sv='Лечение' AND game_items.id=".$act['action_chem']." and game_items.user_id=$kto and game_items.item_uselife>0 and game_items_factsheet.type=3 and game_items.priznak=0 and game_items.used>0 and game_items.item_id=game_items_factsheet.id");
						if (mysql_num_rows($select))
						{
							$lech=mysql_fetch_array($select);
							if ($lech['item_uselife']>0 AND $lech['count_item']>0)
							{
								//$val=floor(($lech['indx']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100*$this->decrease);
                                $val=floor(($lech['indx']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100);

								$this->log[$kto][]['action'] = 10;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['name'] = $lech['name'];
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $val;
								$this->log[$kto][$index]['minus_stm'] = $minus_STM;

								if ($this->all[$kto]['npc']==0)
								{
									$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
									$up=myquery("update game_items set item_uselife=item_uselife-$polomka,count_item=count_item-1 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                    $this->check_item_down($act['action_chem'],$kto);
								}
								$this->all[$kto]['STM']-=$minus_STM;
								$this->all[$kogo]['HP']+=$val;
							}
						}
						else
						{
							$this->log[$kto][]['action'] = 11;
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 12;
					}
				}
				break;

				case 33:
				{
					//лечение эликсиром
					$minus_STM = ceil($act['action_proc']/100*6);
					if ($this->all[$kto]['STM']>=$minus_STM AND $act['action_proc']==100)
					{
						$select=myquery("select game_items_factsheet.name as ident,game_items_factsheet.weight,game_items_factsheet.hp_p,game_items_factsheet.mp_p,game_items_factsheet.stm_p,game_items.id from game_items_factsheet,game_items where game_items_factsheet.type=13 AND game_items.id=".$act['action_chem']." and game_items.user_id=$kto and game_items.item_id=game_items_factsheet.id and game_items.priznak=0 AND game_items.used IN (12,13,14)");
						if (mysql_num_rows($select))
						{
							$lech=mysql_fetch_array($select);

							$this->log[$kto][]['action'] = 13;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['name'] = $lech['ident'];
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['minus_stm'] = $minus_STM;

							$Item = new Item($lech['id']);
							$Item->admindelete();
							
							$this->all[$kto]['STM']-=$minus_STM;
							myquery("UPDATE combat_users SET eliksir=eliksir+1 WHERE user_id=$kto");
							$this->all[$kogo]['HP']+=floor($lech['hp_p']);
							$this->all[$kogo]['MP']+=floor($lech['mp_p']);
							$this->all[$kogo]['STM']+=floor($lech['stm_p']);
						}
						else
						{
							$this->log[$kto][]['action'] = 14;
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 15;
					}
				}
				break;

				//БЛОК ЗАЩИТЫ
				case 21:
				{
					//защищался щитом
					$shit=myquery("SELECT game_items_factsheet.name,game_items_factsheet.indx,game_items_factsheet.deviation,game_items_factsheet.type,game_items_factsheet.mode FROM game_items,game_items_factsheet WHERE game_items.item_id=game_items_factsheet.id AND game_items.used>0 AND game_items.priznak=0 AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']."");
					if (mysql_num_rows($shit) OR $this->all[$kto]['npc']==1)
					{
						if ($this->all[$kto]['npc']==0)
						{
							$zashit=mysql_fetch_array($shit);
						}
						else
						{
							$shield_defense = $this->all[$kto]['clevel']*2+10;
							$zashit['indx'] = $shield_defense;
							$zashit['deviation'] = 0;
							$zashit['type'] = 0;
							$zashit['mode']='Все тело';
						}
						$stm_need = 4;
						if ($kto!=$kogo)
						{
							$stm_need = 6;
						}
						if($zashit['type']==1) {
							$stm_need = 8;
						}
                        $minus_MP = -10000;
                        $cont = 1;
                        if ($act['action_priem']==4)
                        {
                            $uspeh = mt_rand(0,100);
                            if ($uspeh>70) $cont = 0;
                            //Круговая защита оружием
                            if ($this->all[$kto]['STM_MAX']>=$this->all[$kto]['MP_MAX'])
                            {
                                $stm_need = 0.25*$this->all[$kto]['STM_MAX'];
                            }
                            else
                            {
                                $minus_MP = 0.25*$this->all[$kto]['MP_MAX'];
                                $stm_need = -10000;
                            }
                        }
						$minus_STM = ceil($act['action_proc']/100*$stm_need);
						// Удачная попытка, энергии хватает
						if ($this->all[$kto]['STM']>=$minus_STM AND $this->all[$kto]['MP']>=$minus_MP AND $cont==1)
						{
                            if ($minus_MP>0)
                            {
                                $this->all[$kto]['MP']-=$minus_MP;
                            }
                            else
                            {
							    $this->all[$kto]['STM']-=$minus_STM;
                            }
                            if ($act['action_priem']!=4)
                            {
							    if($zashit['type']!=1) {
								    $defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_PARIR']*5,$zashit['indx']+$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_PARIR']*5)*$act['action_proc']/100);
							    }
							    else
							    {
								    $defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_WEAPON'],$zashit['indx']+$zashit['deviation']+$this->all[$kto]['VIT']+$this->all[$kto]['MS_WEAPON'])*$act['action_proc']/100);
							    }
                            }
                            else
                            {
                                $defense=9999;
                            }

							$this->log[$kto][]['action'] = 16;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['mode'] = $zashit['mode'];
							$this->log[$kto][$index]['name'] = $kuda;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $defense;
                            if ($act['action_priem']==4)
                            {
                                $this->log[$kto][$index]['minus_hp'] = 5;
                            }
                            if ($minus_MP>0)
                            {
                                $this->log[$kto][$index]['minus_mp'] = $minus_MP;
                                $this->log[$kto][$index]['minus_stm'] = 0;
                            }
                            else
                            {
								$this->log[$kto][$index]['minus_stm'] = $minus_STM;
                                $this->log[$kto][$index]['minus_mp'] = 0;
                            }

                            if ($act['action_priem']!=4)
                            {
							    switch ($act['action_kuda'])
							    {
								    case 1:
									    $this->all[$kogo]['defense']['HP']['golova']+=$defense;
									    $this->all[$kogo]['defense']['HP']['plecho']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
								    break;
								    case 2:
									    $this->all[$kogo]['defense']['HP']['telo']+=$defense;
									    $this->all[$kogo]['defense']['HP']['pah']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
								    break;
								    case 3:
									    $this->all[$kogo]['defense']['HP']['pah']+=$defense;
									    $this->all[$kogo]['defense']['HP']['nogi']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
									    $this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
								    break;
							    }
                            }
                            else
                            {
                                $this->all[$kogo]['defense']['HP']['all']+=$defense;
                                $this->all[$kogo]['defense']['HP']['golova']+=$defense;
                                $this->all[$kogo]['defense']['HP']['plecho']+=$defense;
                                $this->all[$kogo]['defense']['HP']['telo']+=$defense;
                                $this->all[$kogo]['defense']['HP']['pah']+=$defense;
                                $this->all[$kogo]['defense']['HP']['nogi']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['all']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
                            }
						}
						else
						{
                            // Неудачная попытка, энергии хватает (ну, в этот раз не повезло..)
                            if ($cont==0 AND $this->all[$kto]['STM'] >= $minus_STM AND $this->all[$kto]['MP'] >= $minus_MP)
                            {
                                $this->log[$kto][]['action'] = 77;
                                $index = sizeof($this->log[$kto])-1;
                                if ($minus_MP > 0)
                                {
                                  $this->log[$kto][$index]['minus_mp'] = $minus_MP;
                                  $this->all[$kto]['MP']-=$minus_MP;
                                }
                                else
                                {
                                  $this->log[$kto][$index]['minus_stm'] = $minus_STM;
                                  $this->all[$kto]['STM']-=$minus_STM;
                                }


                            }
                            // Неудачная попытка, энергии не хватает
                            else
                            {
							    $this->log[$kto][]['action'] = 17;
							    $index = sizeof($this->log[$kto])-1;
							    $this->log[$kto][$index]['mode'] = $zashit['mode'];
                            }
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 18;
					}
				}
				break;
				
				case 22:
				{
					 //защита навыком
					 if ($this->all[$kto]['npc']==0)
						$shit=myquery("SELECT * FROM game_spets_item WHERE user_id=$kto AND img='Защита' AND id=".$act['action_chem']." LIMIT 1");
					 else
						$shit=myquery("SELECT * FROM game_spets WHERE id=".$act['action_chem']."");
					 if (mysql_num_rows($shit))
					 {
						 $users_kto_NTL=$this->all[$kto]['NTL'];
						 $zashit = mysql_fetch_array($shit);
						 $minus_mp = ceil($act['action_proc']/100*$this->decrease*mt_rand($zashit['mana']-$zashit['mana_deviation'],$zashit['mana']+$zashit['mana_deviation']));
						 $minus_hp = ceil($act['action_proc']/100*$this->decrease*mt_rand($zashit['hp']-$zashit['hp_deviation'],$zashit['hp']+$zashit['hp_deviation']));
						 $minus_stm = ceil($act['action_proc']/100*$this->decrease*mt_rand($zashit['stm']-$zashit['stm_deviation'],$zashit['stm']+$zashit['stm_deviation']));
						 if (($this->all[$kto]['MP']>=$minus_mp AND 
						 $this->all[$kto]['HP']>=$minus_hp AND 
						 $this->all[$kto]['STM']>=$minus_stm AND 
						 $this->all[$kto]['npc']==0) 
						 OR ($this->all[$kto]['MP']>=$minus_mp AND $this->all[$kto]['npc']==1))
						 {
							$this->all[$kto]['HP']-=$minus_hp;
							$this->all[$kto]['MP']-=$minus_mp;
							$this->all[$kto]['STM']-=$minus_stm;

							$defense_hp=floor(mt_rand($zashit['indx']-$zashit['deviation']+$users_kto_NTL,$zashit['indx']+$zashit['deviation']+$users_kto_NTL)*$act['action_proc']/100*$this->decrease);
							$defense_mp=floor(mt_rand($zashit['indx_mp']-$zashit['indx_mp_deviation']+$users_kto_NTL,$zashit['indx_mp']+$zashit['indx_mp_deviation']+$users_kto_NTL)*$act['action_proc']/100*$this->decrease);
							$defense_stm=floor(mt_rand($zashit['indx_stm']-$zashit['indx_stm_deviation']+$users_kto_NTL,$zashit['indx_stm']+$zashit['indx_stm_deviation']+$users_kto_NTL)*$act['action_proc']/100*$this->decrease);

							//проверим промах для мага
							$random = mt_rand(1,100);
							$sel_magic = myquery("SELECT * FROM game_spets WHERE name='".$zashit['name']."'");
							$level=0;
							$promah=0;
							while ($magic = mysql_fetch_array($sel_magic))
							{
								if ($magic['tip']==0)
								   $level = $magic['war'];
								elseif ($magic['tip']==1)
									$level = $magic['music'];
								elseif ($magic['tip']==2)
									$level = $magic['cook'];
								elseif ($magic['tip']==3)
									$level = $magic['art'];
								elseif ($magic['tip']==4)
									$level = $magic['explor'];
								elseif ($magic['tip']==5)
									$level = $magic['craft'];
								elseif ($magic['tip']==6)
									$level = $magic['card'];
								elseif ($magic['tip']==7)
									$level = $magic['pet'];
								elseif ($magic['tip']==8)
									$level = $magic['unknow'];
							}

							$check = 75 - $level - $this->all[$kogo]['SPD']*2 + $this->all[$kto]['SPD']*2 + $this->all[$kto]['lucky'] - $this->all[$kogo]['lucky'];
							if (($random>$check and $kogo!=$kto) OR $random<=5-$this->all[$kto]['lucky'])
							{
								$defense_hp=0;
								$defense_mp=0;
								$defense_stm=0;
								$minus_hp=0;
								$minus_mp=0;
								$minus_stm=0;
								$promah=1;
							}

							if ($promah==1)
							{
								$this->log[$kto][]['action'] = 37;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['name'] = $zashit['mode'];
								$this->log[$kto][$index]['mode'] = $zashit['name'];
							}
							else
							{
								$this->log[$kto][]['action'] = 19;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['mode'] = $zashit['mode'];
								$this->log[$kto][$index]['name'] = $zashit['name'];
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $defense_hp;
								$this->log[$kto][$index]['add_mp'] = $defense_mp;
								$this->log[$kto][$index]['add_stm'] = $defense_stm;
								$this->log[$kto][$index]['minus_mp'] = $minus_mp;
								$this->log[$kto][$index]['minus_hp'] = $minus_hp;
								$this->log[$kto][$index]['minus_stm'] = $minus_stm;
							}
							$this->all[$kogo]['defense']['HP']['all']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['golova']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['plecho']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['telo']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['pah']+=$defense_hp;
							$this->all[$kogo]['defense']['HP']['nogi']+=$defense_hp;
							$this->all[$kogo]['defense']['MP']+=$defense_mp;
							$this->all[$kogo]['defense']['STM']+=$defense_stm;
							$this->all[$kogo]['defense_all']['HP']['all']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['golova']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['plecho']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['telo']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['pah']+=$defense_hp;
							$this->all[$kogo]['defense_all']['HP']['nogi']+=$defense_hp;
							$this->all[$kogo]['defense_all']['MP']+=$defense_mp;
							$this->all[$kogo]['defense_all']['STM']+=$defense_stm;
						}
						else
						{
							$this->log[$kto][]['action'] = 20;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['mode'] = $zashit['mode'];
							$this->log[$kto][$index]['name'] = $zashit['name'];
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 21;
					}
				}
				break;
				
				case 23:
				{
					//защита артефактом
					$minus_STM = ceil($act['action_proc']/100*4);
					if ($this->all[$kto]['STM']>=$minus_STM)
					{
						$shit=myquery("SELECT game_items.id,game_items_factsheet.mode,game_items_factsheet.name,game_items_factsheet.indx,game_items_factsheet.deviation,game_items.item_uselife,game_items.count_item FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items_factsheet.sv='Защита' AND game_items.used>0 AND game_items.priznak=0 AND game_items.item_uselife>0");
						if (mysql_num_rows($shit))
						{
							$zashit=mysql_fetch_array($shit);
							if (($zashit['item_uselife']>0 AND $zashit['count_item']>0)OR($this->all[$kto]['npc']>0))
							{
								$this->all[$kto]['STM']-=$minus_STM;
								
								if ($this->all[$kto]['npc']==0)
								{
									$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
									$up=myquery("update game_items set item_uselife=item_uselife-$polomka,count_item=count_item-1 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                    $this->check_item_down($act['action_chem'],$kto);
								}
								
								//$defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['MS_ART'],$zashit['indx']+$zashit['deviation']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100)*$this->decrease;
                                $defense=floor(mt_rand($zashit['indx']-$zashit['deviation']+$this->all[$kto]['MS_ART'],$zashit['indx']+$zashit['deviation']+$this->all[$kto]['MS_ART'])*$act['action_proc']/100);

								$this->log[$kto][]['action'] = 22;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['mode'] = $zashit['mode'];
								$this->log[$kto][$index]['name'] = $zashit['name'];
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $defense;
								$this->log[$kto][$index]['minus_stm'] = $minus_STM;
                                /*
								switch ($act['action_kuda'])
								{
									case 1:
									$this->all[$kogo]['defense']['HP']['golova']+=$defense;
									$this->all[$kogo]['defense']['HP']['plecho']+=$defense;
									$this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
									$this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
									break;
									case 2:
									$this->all[$kogo]['defense']['HP']['telo']+=$defense;
									$this->all[$kogo]['defense']['HP']['pah']+=$defense;
									$this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
									$this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
									break;
									case 3:
									$this->all[$kogo]['defense']['HP']['pah']+=$defense;
									$this->all[$kogo]['defense']['HP']['nogi']+=$defense;
									$this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
									$this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
									break;
								}
                                */
                                $this->all[$kogo]['defense']['HP']['all']+=$defense;
                                $this->all[$kogo]['defense']['HP']['golova']+=$defense;
                                $this->all[$kogo]['defense']['HP']['plecho']+=$defense;
                                $this->all[$kogo]['defense']['HP']['telo']+=$defense;
                                $this->all[$kogo]['defense']['HP']['pah']+=$defense;
                                $this->all[$kogo]['defense']['HP']['nogi']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['all']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['golova']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['plecho']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['telo']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['pah']+=$defense;
                                $this->all[$kogo]['defense_all']['HP']['nogi']+=$defense;
							}
						}
						else
						{
							$this->log[$kto][]['action'] = 23;
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 24;
					}
				}
				break;
				
				//БЛОК АТАКИ
				case 11:
				{
					//атака кулаком
                    $prob = false;
					$k=0;
					$est_weapon = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$kto AND used=1 AND priznak=0"),0,0);
					if ($est_weapon==0)
					{
						$k=2;
					}
					if($this->all[$kogo]['npc']==1)
					{
						$bron = 0;
					}
					else
					{
						$bron = $this->get_bron($act['action_kuda'],0,$kogo);
					}
					$damage_hp=max(0,floor($act['action_proc']/100*mt_rand($this->all[$kto]['STR']-1+$this->all[$kto]['MS_KULAK']*$k-$bron,$this->all[$kto]['STR']+1+$this->all[$kto]['MS_KULAK']*$k-$bron)));
					
					$promah=0;
					$kritic=0;
					//промах при ударе кулаком
					$random = mt_rand(1,100);
					$random_5=mt_rand(1,100);
					$krit=5+$this->all[$kto]['lucky'];
					if ($act['action_kuda']==1 OR $act['action_kuda']==3) $krit=8-$this->all[$kogo]['lucky'];
					//промахи при ударе оружием или кулаком
					$prot_parir = $this->all[$kogo]['MS_PARIR'];
					$user_bu = $this->all[$kto]['MS_KULAK'];

					$check = 75+($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'] + $user_bu - $prot_parir)*3;
					$type_attack = 'удар';
					if($act['action_priem']==2) {
						//Сила атаки уменьшается на 25%,  вероятность крита увеличивается на 2%
						$check+=25;
						$damage_hp=ceil($damage_hp*0.75);
						$type_attack = 'прицельный удар';
						$krit+=2;
					}
					if($act['action_priem']==3) {
						$check-=25;
						$damage_hp=ceil($damage_hp*1.25);
						$type_attack = 'мощный удар';
						$krit=$this->all[$kto]['lucky'];
					} 
					$ukus = 0;
                    $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                    $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
					if(($damage_hp<=0 or $this->all[$kto]['STM']<4) AND $act['action_proc']==100) $ukus = 1; 
					if (($random>$check or $random_5<=5-$this->all[$kto]['lucky']) and $random>10 ) //проверка попали-ли и 10% шанс попасть в любом случае
					{
						$damage_hp=0;
						$promah=1;
					}
					
					if ($ukus==0 and $promah==0)
					{
						if (($random<=$krit) AND (($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'])>5))
						{
							$damage_hp = 1.5*$damage_hp;
							$kritic=1;
						}
						elseif ($random<=$krit/2)
						{
							$damage_hp = 1.5*$damage_hp;
							$kritic=1;
						}
				   }
					
					$damage_hp=max(0,floor($damage_hp*$this->decrease));
					if ($promah==1)
					{
						$this->log[$kto][]['action'] = 25;
					}
					elseif ($kritic==1)
					{
						$this->log[$kto][]['action'] = 26;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['na_kogo'] = $kogo;
						$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
						$this->log[$kto][$index]['name'] = $type_attack;
						$this->log[$kto][$index]['mode'] = $kuda;
						$this->log[$kto][$index]['procent'] = $act['action_proc'];
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
					}
					else
					{
						if(($damage_hp<=0 or $this->all[$kto]['STM']<4) AND $act['action_proc']==100)
						{
							$damage_hp=1;
							$this->log[$kto][]['action'] = 27;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['mode'] = $kuda;
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $damage_hp;
						}
						else
						{
							$this->log[$kto][]['action'] = 28;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['na_kogo'] = $kogo;
							$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
							$this->log[$kto][$index]['name'] = $type_attack;
							$this->log[$kto][$index]['mode'] = $kuda;
							$this->log[$kto][$index]['procent'] = $act['action_proc'];
							$this->log[$kto][$index]['add_hp'] = $damage_hp;
						}
					}
				}
				break;

				case 12:
				{
					//атака оружием
                    $prob = false;
					$no_decrease=false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife, game_items.used, game_items_factsheet.type_weapon, game_items_factsheet.type_weapon_need FROM game_items,game_items_factsheet WHERE game_items.user_id=$kto  AND game_items.id=".$act['action_chem']." AND game_items_factsheet.id=game_items.item_id AND game_items.used>0 AND game_items.priznak=0");
					if (mysql_num_rows($the) or $this->all[$kto]['npc']==1)
					{
						 if ($this->all[$kto]['npc']==1)
						 {
							$npc_temp=$this->all[$kto]['npc_id_template'];
							$check_promah=myquery("Select id From game_npc_set_option Where opt_id=1 and npc_id=$npc_temp");
							$check_fix=myquery("Select id From game_npc_set_option Where opt_id=4 and npc_id=$npc_temp");
							$check_proc=myquery("Select id From game_npc_set_option Where opt_id=5 and npc_id=$npc_temp");
							$item_npc = $Npc->templ['item'];
							$weapon['ident']=$item_npc;
							$weapon['mode']=$item_npc;
							$weapon['indx']=0;
							$weapon['deviation']=0;
							$weapon['item_uselife']=100;
							$weapon['type_weapon'] = 0;
							$stm = 8;
							if($this->combat['map_name']==691 OR $this->combat['map_name']==692 OR $this->combat['map_name']==804)
							{
								$weapon['indx']=$this->all[$kogo]['STR'];
							}
							$r = mt_rand(1,10);
							if ($r<=1) {$act['action_priem']=2;}
							elseif ($r<=2) {$act['action_priem']=3;}
                            $minus_stm = ceil($act['action_proc']/100*$stm);
						 }
						 else
						 {
							 $weapon=mysql_fetch_array($the);
							 $stm = 8;
						     $minus_stm = ceil($act['action_proc']/100*$stm);
                             if ($act['action_priem']==5)
                             {
                                 $prob = true;
                                 $minus_stm = ceil($act['action_proc']/100*$this->all[$kto]['STM_MAX']*0.15);                                  
                             }
                         }


						 if ($this->all[$kto]['STM']>=$minus_stm OR $this->all[$kto]['npc']==1)
						 {
							 if ($weapon['item_uselife']>0)
							 {
								$MS_kto = 0;
								$MS_kogo = 0;
								$STRENGTH = $this->all[$kto]['STR'];
								if($weapon['type_weapon']==0) {
									$MS_kto = $this->all[$kto]['MS_WEAPON'];
									$MS_kogo = $this->all[$kogo]['MS_WEAPON'];
								}
								if($weapon['type_weapon']==1) {
									$MS_kto = $this->all[$kto]['MS_KULAK'];
									$MS_kogo = $this->all[$kogo]['MS_KULAK'];
								}
								if($weapon['type_weapon']==2) {
									$MS_kto = $this->all[$kto]['MS_LUK'];
									$MS_kogo = $this->all[$kogo]['MS_LUK'];
								}
								if($weapon['type_weapon']==3) {
									$MS_kto = $this->all[$kto]['MS_SWORD'];
									$MS_kogo = $this->all[$kogo]['MS_SWORD'];
								}
								if($weapon['type_weapon']==4) {
									$MS_kto = $this->all[$kto]['MS_AXE'];
									$MS_kogo = $this->all[$kogo]['MS_AXE'];
								}
								if($weapon['type_weapon']==5) {
									$MS_kto = $this->all[$kto]['MS_SPEAR'];
									$MS_kogo = $this->all[$kogo]['MS_SPEAR'];
								}
								$MS_kogo = $this->all[$kogo]['MS_PARIR'];
								$indx = $weapon['indx'];
								if($act['action_priem']==2) {
									$indx=ceil($indx*0.8);
								}
								elseif($act['action_priem']==3) {
									$indx=ceil($indx*1.2);
								}
                                
								if($this->all[$kogo]['npc']==1)
								{
									$bron = 0;
								}
								else
								{
									$bron = $this->get_bron($act['action_kuda'],$weapon['type_weapon'],$kogo);
								}
								if ($weapon['type_weapon']==2 AND $this->combat['hod']==1)
								{
									$damage_hp=floor($act['action_proc']/100*(mt_rand($STRENGTH*3+$indx-$weapon['deviation']+$MS_kto*3-$this->all[$kogo]['VIT']-$MS_kogo-$bron,$STRENGTH*3+$indx+$weapon['deviation']+$MS_kto*3-$this->all[$kogo]['VIT']-$MS_kogo-$bron)));
								}
								else
								{
									$damage_hp=floor($act['action_proc']/100*(mt_rand($STRENGTH+$indx-$weapon['deviation']+$MS_kto-$this->all[$kogo]['VIT']-$MS_kogo-$bron,$STRENGTH+$indx+$weapon['deviation']+$MS_kto-$this->all[$kogo]['VIT']-$MS_kogo-$bron)));
								}
                                
								//Проверим промахи
								$random = mt_rand(1,100);
								$random_5=mt_rand(1,100);
								$krit=5+$this->all[$kto]['lucky'];
								$promah=0;
								$polomka=0;
								$kritic=0;
								if ($act['action_kuda']==1 OR $act['action_kuda']==3) $krit=8-$this->all[$kogo]['lucky'];

								//промахи при ударе оружием
								$prot_parir = $MS_kogo;
								$user_bu = $MS_kto;

								$check = 75+($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'] + $user_bu - $prot_parir)*3;
								$type_attack = 'удар';
                                if (isset($prob) AND $prob) {
                                    $check+=15;
                                    $type_attack = 'пробивающий удар';
                                    //Пробивающий удар оружием
                                    //Урон - 50%
                                    $damage_hp = $damage_hp/2;
                                }
								if($act['action_priem']==2) {
									$check+=25;
									$type_attack = 'прицельный удар';
									$krit+=2;
								}
								if($act['action_priem']==3) {
									$check-=25;
									$type_attack = 'мощный удар';
									$krit = $this->all[$kto]['lucky'];
								}
								
								if ($this->all[$kto]['npc']==1)
								{
									if (mysql_num_rows($check_fix)>0)
									{
										list($id_record)=mysql_fetch_array($check_fix);
										list($min)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=1"));
										list($max)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=2"));
										$damage_hp = mt_rand($min,$max);
										$no_decrease = true;
									}
									elseif (mysql_num_rows($check_proc)>0)
									{
										list($id_record)=mysql_fetch_array($check_proc);
										list($proc)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=1"));
										$damage_hp = max(1,$proc*$this->all[$kogo]['HP_MAX']/100);
										$prob=true;
										$no_decrease=true;
										$type_attack = 'пробивающий удар';
									}
								}
								
								if ($this->all[$kto]['npc']==1 and mysql_num_rows($check_promah)>0)
								{
									$promah=2;
								}
								elseif (($random>$check or $random_5<=5-$this->all[$kto]['lucky']) and $random>10 ) //Проверка промахов при ударе оружием
								{
									$damage_hp=0;
									$promah=1;
								}
								
								if ($promah==0)
								{
									if (($random<=$krit) AND (($this->all[$kto]['PIE']-$this->all[$kogo]['PIE'])>5))
									{
										$damage_hp = 1.5*$damage_hp;
										$kritic = 1;
									}
									elseif ($random<=$krit/2)
									{
										$damage_hp = 1.5*$damage_hp;
										$kritic = 1;
									}
								}	
								
                                $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);

								$this->all[$kto]['STM']-=$minus_stm;
								if ($damage_hp>0 AND $this->all[$kto]['npc']==0)
								{
									$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
									myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                    $this->check_item_down($act['action_chem'],$kto);
								}
								if (!$no_decrease)
								{
									$damage_hp = max(0,floor($damage_hp*$this->decrease));
								}

								if ($promah==1)
								{
									$this->log[$kto][]['action'] = 29;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_stm'] = $minus_stm;
									$r = mt_rand(0,100);
									if ($r<=5 and $random_5>5-$this->all[$kto]['lucky'])
									{
										if($act['action_priem']==3)
										{
											$polomka = 100;
											if ($this->all[$kto]['npc']==0)
											{
												myquery("update game_items set item_uselife=0 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                                $this->check_item_down($act['action_chem'],$kto);
											}
											$this->log[$kto][]['action'] = 30;
										}
										else
										{
											$polomka = round(mt_rand(300,500)/100,2);
											if ($this->all[$kto]['npc']==0)
											{
												myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                                $this->check_item_down($act['action_chem'],$kto);
											}
											$this->log[$kto][]['action'] = 31;
											$index = sizeof($this->log[$kto])-1;
											$this->log[$kto][$index]['procent'] = $polomka;
										}
									}
								}
								elseif ($kritic==1)
								{
									$this->log[$kto][]['action'] = 32;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['na_kogo'] = $kogo;
									$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
									$this->log[$kto][$index]['name'] = $type_attack;
									$this->log[$kto][$index]['mode'] = $weapon['mode'];
									$this->log[$kto][$index]['kuda'] = $kuda;
									$this->log[$kto][$index]['procent'] = $act['action_proc'];
									$this->log[$kto][$index]['add_hp'] = $damage_hp;
									$this->log[$kto][$index]['minus_stm'] = $minus_stm;
								}
								else
								{
									$this->log[$kto][]['action'] = 33;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['na_kogo'] = $kogo;
									$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
									$this->log[$kto][$index]['name'] = $type_attack;
									$this->log[$kto][$index]['mode'] = $weapon['mode'];
									$this->log[$kto][$index]['kuda'] = $kuda;
									$this->log[$kto][$index]['procent'] = $act['action_proc'];
									$this->log[$kto][$index]['add_hp'] = $damage_hp;
									$this->log[$kto][$index]['minus_stm'] = $minus_stm;
								}
							 }
							 else
							 {
								$this->log[$kto][]['action'] = 34;
							 }
						 }
						 else
						 {
							$this->log[$kto][]['action'] = 35;
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 36;
					 }
				}
				break;

				case 13:
				{
					///атака магией
                    $prob = false;
					$no_decrease = false;
					if ($this->all[$kto]['npc']==0)
						$the=myquery("SELECT * FROM game_spets_item WHERE user_id=$kto AND id=".$act['action_chem']." and img='Атака' LIMIT 1");
					else
                    {
						$npc_temp=$this->all[$kto]['npc_id_template'];
						$check_promah=myquery("Select id From game_npc_set_option Where opt_id=1 and npc_id=$npc_temp");
						$check_fix=myquery("Select id From game_npc_set_option Where opt_id=4 and npc_id=$npc_temp");
						$check_proc=myquery("Select id From game_npc_set_option Where opt_id=5 and npc_id=$npc_temp");
						$the=myquery("SELECT * FROM game_spets WHERE id=".$act['action_chem']."");
                    }
					if (mysql_num_rows($the))
					{
						$weapon=mysql_fetch_array($the);
						$minus_mp = max(0,ceil($act['action_proc']/100*$this->decrease*mt_rand($weapon['mana']-$weapon['mana_deviation'],$weapon['mana']+$weapon['mana_deviation'])));
						$minus_hp = max(0,ceil($act['action_proc']/100*$this->decrease*mt_rand($weapon['hp']-$weapon['hp_deviation'],$weapon['hp']+$weapon['hp_deviation'])));
						$minus_stm = max(0,ceil($act['action_proc']/100*$this->decrease*mt_rand($weapon['stm']-$weapon['stm_deviation'],$weapon['stm']+$weapon['stm_deviation'])));

						if ($this->all[$kogo]['npc']>0)
						{
							$mag_def = 0;
						}
						else
						{
							$mag_def = mysql_result(myquery("SELECT SUM( game_items_factsheet.magic_def_index ) AS mag_def FROM game_items,game_items_factsheet WHERE game_items.item_id = game_items_factsheet.id AND game_items.user_id =$kogo AND game_items.priznak =0 AND game_items.used >0"),0,0);
						}

						if (($this->all[$kto]['MP']>=$minus_mp AND $this->all[$kto]['HP']>=$minus_hp AND $this->all[$kto]['STM']>=$minus_stm AND $this->all[$kto]['npc']==0) OR ($this->all[$kto]['MP']>=$minus_mp AND $this->all[$kto]['npc']==1))
						{
							if ($weapon['indx']!=0)
							{
								//$indx_hp = $this->all[$kto]['NTL']+$weapon['indx']-$this->all[$kogo]['NTL']-$mag_def;
								$indx_hp = $this->all[$kto]['NTL']+$weapon['indx']-$mag_def;
								$damage_hp=max(0,floor($act['action_proc']/100*mt_rand($indx_hp-$weapon['deviation'],$indx_hp+$weapon['deviation'])));
							}
							else
							{
								$damage_hp=0;
							}

							if ($weapon['indx_mp']!=0)
							{
								//$indx_mp = $this->all[$kto]['NTL']+$weapon['indx_mp']-$this->all[$kogo]['NTL'];
                                $indx_mp = $this->all[$kto]['NTL']+$weapon['indx_mp'];
								$damage_mp=max(0,floor($act['action_proc']/100*mt_rand($indx_mp-$weapon['indx_mp_deviation'],$indx_mp+$weapon['indx_mp_deviation'])));
							}
							else
							{
								$damage_mp=0;
							}

							if ($weapon['indx_stm']!=0)
							{
								//$indx_stm = $this->all[$kto]['NTL']+$weapon['indx_stm']-$this->all[$kogo]['NTL'];
                                $indx_stm = $this->all[$kto]['NTL']+$weapon['indx_stm'];
								$damage_stm=max(0,floor($act['action_proc']/100*mt_rand($indx_stm-$weapon['indx_stm_deviation'],$indx_stm+$weapon['indx_stm_deviation'])));
							}
							else
							{
								$damage_stm=0;
							}
							$this->all[$kto]['MP']-=$minus_mp;
							$this->all[$kto]['STM']-=$minus_stm;
							$this->all[$kto]['HP']-=$minus_hp;

							//проверим промах для мага
							$random = mt_rand(1,100);
							$sel_magic = myquery("SELECT * FROM game_spets WHERE name='".$weapon['name']."'");
							$level=0;
							$promah=0;
							while ($magic = mysql_fetch_array($sel_magic))
							{
								if ($magic['tip']==0)
								   $level = $magic['war'];
								elseif ($magic['tip']==1)
									$level = $magic['music'];
								elseif ($magic['tip']==2)
									$level = $magic['cook'];
								elseif ($magic['tip']==3)
									$level = $magic['art'];
								elseif ($magic['tip']==4)
									$level = $magic['explor'];
								elseif ($magic['tip']==5)
									$level = $magic['craft'];
								elseif ($magic['tip']==6)
									$level = $magic['card'];
								elseif ($magic['tip']==7)
									$level = $magic['pet'];
								elseif ($magic['tip']==8)
									$level = $magic['unknow'];
							}

							$check = 85 + ($this->all[$kto]['SPD']-$this->all[$kogo]['SPD'])*2 - $level + $this->all[$kto]['lucky'] - $this->all[$kogo]['lucky'];
							
							if ($this->all[$kto]['npc']==1)
							{
								if (mysql_num_rows($check_fix)>0)
								{
									list($id_record)=mysql_fetch_array($check_fix);
									list($min)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=1"));
									list($max)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=2"));
									$damage_hp = mt_rand($min,$max);
									$no_decrease = true;
								}
								elseif (mysql_num_rows($check_proc)>0)
								{
									list($id_record)=mysql_fetch_array($check_proc);
									list($proc)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=1"));
									$damage_hp = max(1,$proc*$this->all[$kogo]['HP_MAX']/100);
									$no_decrease = true;
									$prob = true;
									$type_attack = 'пробивающий удар';
								}		
							}
							if (!$no_decrease)
							{
								$damage_hp=max(0,floor($damage_hp*$this->decrease));
							}
							if ($this->all[$kto]['npc']==1 and mysql_num_rows($check_promah)>0)
							{
								$promah=0;
							}
							elseif ($random>$check OR $random<=5-$this->all[$kto]['lucky'])
							{
								$damage_hp=0;
								$damage_mp=0;
								$damage_stm=0;
								$promah=1;
							}
							$damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                            $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
							$polomka = mt_rand(1,5);
							$ok=1;
							if ($promah==1)
							{
								$this->log[$kto][]['action'] = 37;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['name'] = $weapon['mode'];
								$this->log[$kto][$index]['mode'] = $weapon['name'];
							}
							else
							{
								//сначала усложним жизнь магам
								if ($this->all[$kto]['npc']==1)
								{
								}
								else
								{
									$est = @mysql_result(@myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$kto AND used=1 AND priznak=0"),0,0);
									if ($est>0)
									{
										$r = mt_rand(1,100);
										if ($r<=5)
										{
											$polomka=$polomka*2;
											myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND used=1 AND priznak=0");
                                            $this->check_item_down(-1,$kto);
											$ok=2;
										}
										elseif ($r<=10)
										{
											myquery("update game_items set item_uselife=item_uselife-$polomka WHERE user_id=$kto AND used=1 AND priznak=0");
                                            $this->check_item_down(-1,$kto);
											$ok=3;
										}
									}
								}

								$this->log[$kto][]['action'] = 38;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['na_kogo'] = $kogo;
								$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								$this->log[$kto][$index]['name'] = $weapon['name'];
								$this->log[$kto][$index]['mode'] = $weapon['mode'];
								$this->log[$kto][$index]['procent'] = $act['action_proc'];
								$this->log[$kto][$index]['add_hp'] = $damage_hp;
								$this->log[$kto][$index]['add_mp'] = $damage_mp;
								$this->log[$kto][$index]['add_stm'] = $damage_stm;
							}
							$this->log[$kto][$index]['minus_mp'] = $minus_mp;
							$this->log[$kto][$index]['minus_hp'] = $minus_hp;
							$this->log[$kto][$index]['minus_stm'] = $minus_stm;
							if ($ok==2)
							{
								$this->log[$kto][]['action'] = 59;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['procent'] = $polomka;
							}
							if ($ok==3)
							{
								$this->log[$kto][]['action'] = 39;
								$index = sizeof($this->log[$kto])-1;
								$this->log[$kto][$index]['procent'] = $polomka;
							} 
						}
						else
						{
							$this->log[$kto][]['action'] = 40;
							$index = sizeof($this->log[$kto])-1;
							$this->log[$kto][$index]['name'] = $weapon['name'];
							$this->log[$kto][$index]['mode'] = $weapon['mode'];
						}
					}
					else
					{
						$this->log[$kto][]['action'] = 41;
					}
				}
				break;

				case 14:
				{
					//атака артефактом
                    $prob = false;
					$no_decrease = false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife, game_items.count_item FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used>0 AND game_items.priznak=0");
					if (mysql_num_rows($the) or $this->all[$kto]['npc']==1)
					{
						 if ($this->all[$kto]['npc']==1)
						 {
							$npc_temp=$this->all[$kto]['npc_id_template'];
							$check_fix=myquery("Select id From game_npc_set_option Where opt_id=4 and npc_id=$npc_temp");
							$check_proc=myquery("Select id From game_npc_set_option Where opt_id=5 and npc_id=$npc_temp");
							$item_npc = $Npc->templ['item'];
							$weapon['ident']=$item_npc;
							$weapon['mode']=$item_npc;
							$weapon['indx']=$this->all[$kto]['STR']-$this->all[$kto]['clevel'];
							$weapon['deviation']=0;
							$weapon['item_uselife']=100;
							$weapon['count_item']=1;
							$this->all[$kto]['STM']=200;
      
						 }
						 else
						 {
							$weapon=mysql_fetch_array($the);
						 }
						 $minus_stm = ceil($act['action_proc']/100*16);
						 if ($this->all[$kto]['STM']>=$minus_stm)
						 {
							 if ($weapon['item_uselife']>0 AND $weapon['count_item']>0)
							 {
                                $damage_hp=max(0,floor($act['action_proc']/100*$this->decrease*mt_rand($weapon['indx']-$weapon['deviation']+$this->all[$kto]['clevel']+$this->all[$kto]['MS_ART']-$this->all[$kogo]['MS_ART'],$weapon['indx']+$weapon['deviation']+$this->all[$kto]['clevel']+$this->all[$kto]['MS_ART']-$this->all[$kogo]['MS_ART'])));                         
								$this->all[$kto]['STM']-=$minus_stm;
								if ($this->all[$kto]['npc']==1)
								{
									if (mysql_num_rows($check_fix)>0)
									{
										list($id_record)=mysql_fetch_array($check_fix);
										list($min)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=1"));
										list($max)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=2"));
										$damage_hp = mt_rand($min,$max);
										$no_decrease = true;
									}
									elseif (mysql_num_rows($check_proc)>0)
									{
										list($id_record)=mysql_fetch_array($check_proc);
										list($proc)=mysql_fetch_array(myquery("Select value From game_npc_set_option_value Where id=$id_record and number=1"));
										$damage_hp = max(1,$proc*$this->all[$kogo]['HP_MAX']/100);
										$prob = true;
										$no_decrease = true;
										$type_attack = 'пробивающий удар';
									}		
								}
                                 $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                 $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
								 if ($this->all[$kto]['npc']==0)
								 {
									$polomka = round($act['action_proc']/100*mt_rand(10,100)/100,2);
									$up=myquery("update game_items set item_uselife=item_uselife-$polomka,count_item=count_item-1 WHERE user_id=$kto AND id=".$act['action_chem']." AND priznak=0");
                                    $this->check_item_down($act['action_chem'],$kto);
								 }
								 $this->log[$kto][]['action'] = 42;
								 $index = sizeof($this->log[$kto])-1;
								 $this->log[$kto][$index]['na_kogo'] = $kogo;
								 $this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								 $this->log[$kto][$index]['mode'] = $weapon['mode'];
								 $this->log[$kto][$index]['kuda'] = $kuda;
								 $this->log[$kto][$index]['procent'] = $act['action_proc'];
								 $this->log[$kto][$index]['add_hp'] = $damage_hp;
								 $this->log[$kto][$index]['minus_stm'] = $minus_stm;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 43;
							 }
						 }
						 else
						 {
							$this->log[$kto][]['action'] = 44;
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 45;
					 }
				}
				break;
				
				case 15:
				{
					//выстрел из лука
                    $prob = false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used=0 AND game_items.priznak=0");
					if (mysql_num_rows($the))
					{
						 $weapon=mysql_fetch_array($the);
						 $minus = 10;
						 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
						 {
							 $har = 'STM';
						 }
						 else
						 {
							 $har = 'MP';
						 }
						 if ($this->all[$kto][$har]>=$minus)
						 {
							 $chance = mt_rand(0,100);
							 if ($this->all[$kto]['npc']==0)
							 {
								$Item = new Item($weapon['id']);
								$Item->admindelete();
							 }
							 if ($chance<=(50+5*$this->all[$kto]['MS_LUK']))
							 {
								 $damage_hp=mt_rand($weapon['indx']-$weapon['deviation'],$weapon['indx']+$weapon['deviation']);
                                 $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                 $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
								 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
								 {
									$this->log[$kto][]['action'] = 64;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_stm'] = $minus;
									$this->all[$kto]['STM']-=$minus; 
								 }
								 else
								 {
									$this->log[$kto][]['action'] = 65;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_mp'] = $minus;
									$this->all[$kto]['MP']-=$minus; 
								 }
								 $this->log[$kto][$index]['na_kogo'] = $kogo;
								 $this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								 $this->log[$kto][$index]['mode'] = $weapon['mode'];
								 $this->log[$kto][$index]['kuda'] = $kuda;
								 $this->log[$kto][$index]['procent'] = 0;
								 $this->log[$kto][$index]['add_hp'] = $damage_hp;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 66;  
							 }
						 }
						 else
						 {
							 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
							 {
								 $this->log[$kto][]['action'] = 61;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 62;
							 }
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 63;
					 }
				}
				break;
				
				case 16:
				{
					//бросок метательного предмета
                    $prob = false;
					$the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items_factsheet.indx, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used=0 AND game_items.priznak=0");
					if (mysql_num_rows($the))
					{
						 $weapon=mysql_fetch_array($the);
						 $minus = 10;
						 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
						 {
							 $har = 'STM';
						 }
						 else
						 {
							 $har = 'MP';
						 }
						 if ($this->all[$kto][$har]>=$minus)
						 {
							 $chance = mt_rand(0,100);
							 if ($this->all[$kto]['npc']==0)
							 {
								$Item = new Item($weapon['id']);
								$Item->admindelete();
							 }
							 if ($chance<=(50+5*$this->all[$kto]['MS_THROW']))
							 {
								 $damage_hp=mt_rand($weapon['indx']-$weapon['deviation'],$weapon['indx']+$weapon['deviation']);
                                 $damage_hp=ceil($damage_hp*$this->all[$kto]['svit_usil']);
                                 $damage_hp=$this->calc_position($damage_hp,$kto,$kogo);
								 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
								 {
									$this->log[$kto][]['action'] = 70;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_stm'] = $minus;
									$this->all[$kto]['STM']-=$minus; 
								 }
								 else
								 {
									$this->log[$kto][]['action'] = 71;
									$index = sizeof($this->log[$kto])-1;
									$this->log[$kto][$index]['minus_mp'] = $minus;
									$this->all[$kto]['MP']-=$minus; 
								 }
								 $this->log[$kto][$index]['na_kogo'] = $kogo;
								 $this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
								 $this->log[$kto][$index]['mode'] = $weapon['mode'];
								 $this->log[$kto][$index]['kuda'] = $kuda;
								 $this->log[$kto][$index]['procent'] = 0;
								 $this->log[$kto][$index]['add_hp'] = $damage_hp;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 72;  
							 }
						 }
						 else
						 {
							 if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
							 {
								 $this->log[$kto][]['action'] = 67;
							 }
							 else
							 {
								 $this->log[$kto][]['action'] = 68;
							 }
						 }
					 }
					 else
					 {
						$this->log[$kto][]['action'] = 69;
					 }
				}
				break;

                case 17:
                {
                    //использование свитка усиления
                    $prob = false;
                    $the=myquery("SELECT game_items.id, game_items_factsheet.name as ident, game_items_factsheet.mode, game_items.item_id, game_items_factsheet.deviation, game_items_factsheet.img, game_items.item_uselife FROM game_items,game_items_factsheet WHERE game_items_factsheet.id=game_items.item_id AND game_items.user_id=$kto AND game_items.id=".$act['action_chem']." AND game_items.used IN (12,13,14) AND game_items.priznak=0");
                    if (mysql_num_rows($the))
                    {
                         $weapon=mysql_fetch_array($the);
                         $minus = 10;
                         if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
                         {
                             $har = 'STM';
                         }
                         else
                         {
                             $har = 'MP';
                         }
                         if ($this->all[$kto][$har]>=$minus)
                         {
                             if ($this->all[$kto]['npc']==0)
                             {
                                $Item = new Item($weapon['id']);
                                $Item->admindelete();
                             }
                             $this->log[$kto][]['action'] = 73;
                             $index = sizeof($this->log[$kto])-1;
                             if ($weapon['item_id']==item_id_svitok_light_sopr OR $weapon['item_id']==item_id_svitok_medium_sopr OR $weapon['item_id']==item_id_svitok_hard_sopr OR $weapon['item_id']==item_id_svitok_absolut_sopr)
                             {
                                 if ($weapon['item_id']==item_id_svitok_light_sopr) $k_svitok_usil = 25;
                                 if ($weapon['item_id']==item_id_svitok_medium_sopr) $k_svitok_usil = 50;
                                 if ($weapon['item_id']==item_id_svitok_hard_sopr) $k_svitok_usil = 75;
                                 if ($weapon['item_id']==item_id_svitok_absolut_sopr) $k_svitok_usil = 100;
                                 $this->log[$kto][$index]['mode'] = $weapon['ident'].' (-'.$k_svitok_usil.'% повреждений)';
                             }
                             else
                             {
                                 if ($weapon['item_id']==item_id_svitok_light_usil) $k_svitok_usil = 1.25;
                                 if ($weapon['item_id']==item_id_svitok_medium_usil) $k_svitok_usil = 1.50;
                                 if ($weapon['item_id']==item_id_svitok_hard_usil) $k_svitok_usil = 1.75;
                                 if ($weapon['item_id']==item_id_svitok_absolut_usil) $k_svitok_usil = 2.00;
                                 $this->log[$kto][$index]['mode'] = $weapon['ident'].' (+'.(($k_svitok_usil-1)*100).'% урона)';
                                 $this->all[$kto]['svit_usil'] = $k_svitok_usil;
                             }
                         }
                         else
                         {
                             if ($this->all[$kto]['STM_MAX']>$this->all[$kto]['MP_MAX'])
                             {
                                 $this->log[$kto][]['action'] = 74;
                             }
                             else
                             {
                                 $this->log[$kto][]['action'] = 75;
                             }
                         }
                     }
                     else
                     {
                        $this->log[$kto][]['action'] = 76;
                     }
                }
                break;
            }
            //$damage_hp = $damage_hp * $k_svitok_usil;
			
            //Снимем сломанные предметы
            $selused = myquery("
            SELECT game_items.id, game_items_factsheet.type
            FROM game_items, combat_users, game_items_factsheet 
            WHERE game_items.user_id=combat_users.user_id
            AND combat_users.combat_id=".$this->combat['combat_id']." 
            AND game_items.used>0 
            AND game_items.item_uselife<=0
            AND game_items.item_id=game_items_factsheet.id
            AND game_items_factsheet.type NOT IN (12,13,21,19)
            AND game_items.priznak=0");
            while ($it = mysql_fetch_array($selused))
            {
                $Item = new Item($it['id']);
                $Item->down();
            }
        
			//ОБСЧЕТ РЕЗУЛЬТАТА ДЕЙСТВИЙ ХОДА
			if ($damage_hp>0 OR $damage_mp>0 OR $damage_stm>0)
			{
				$damage_hp_start = $damage_hp;
				if ($act['action_type']==13)
				{
					//при атаке магией
					$defense_hp = 0;
					$defense_mp = 0;
					$defense_stm = 0;
					if ($damage_hp>0)
					{
						$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['all'],$damage_hp));
						$this->all[$kogo]['defense']['HP']['all']-=$defense_hp;
						$damage_hp=max(0,$damage_hp-$defense_hp);
					}
					if ($damage_mp>0)
					{
						$defense_mp=max(0,min($this->all[$kogo]['defense']['MP'],$damage_mp));
						$this->all[$kogo]['defense']['MP']-=$defense_mp;
						$damage_mp=max(0,$damage_mp-$defense_mp);
					}
					if ($damage_stm>0)
					{
						$defense_stm=max(0,min($this->all[$kogo]['defense']['STM'],$damage_stm));
						$this->all[$kogo]['defense']['STM']-=$defense_stm;
						$damage_stm=max(0,$damage_stm-$defense_stm);
					}
					if (isset($prob) and $prob)
                    {
                        //Пробивающий удар оружием
                        //Активная защита не действует
                        $damage_hp = $damage_hp_start;
                        $defense_hp = 0;                        
                    }
					if ($defense_hp>0 OR $defense_mp>0 OR $defense_stm>0)
					{
						$this->log[$kto][]['action'] = 46;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
						$this->log[$kto][$index]['add_mp'] = $damage_mp;
						$this->log[$kto][$index]['add_stm'] = $damage_stm;
						$this->log[$kto][$index]['minus_hp'] = $defense_hp;
						$this->log[$kto][$index]['minus_mp'] = $defense_mp;
						$this->log[$kto][$index]['minus_stm'] = $defense_stm;
					}
				}
				else
				{
					$defense_hp = 0;
					if ($damage_hp>0)
					{
						switch ($act['action_kuda'])
						{
							case 1:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['golova'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['golova']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 2:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['telo'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['telo']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 3:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['pah'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['pah']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 4:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['plecho'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['plecho']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
							case 5:
							$defense_hp=max(0,min($this->all[$kogo]['defense']['HP']['nogi'],$damage_hp));
							$this->all[$kogo]['defense']['HP']['nogi']-=$defense_hp;
							$damage_hp=max(0,$damage_hp-$defense_hp);
							break;
						}
					}
                    if (isset($prob) and $prob)
                    {
                        //Пробивающий удар оружием
                        //Активная защита не действует
                        $damage_hp = $damage_hp_start;
                        $defense_hp = 0;                        
                    }
					if ($defense_hp>0)
					{
						$this->log[$kto][]['action'] = 46;
						$index = sizeof($this->log[$kto])-1;
						$this->log[$kto][$index]['add_hp'] = $damage_hp;
						$this->log[$kto][$index]['minus_hp'] = $defense_hp;
					}
				}

				$damage_hp=max(0,$damage_hp);
				$damage_mp=max(0,$damage_mp);
				$damage_stm=max(0,$damage_stm);

				$exp = 0;
				$gp = 0;
				if ($this->all[$kto]['side']!=$this->all[$kogo]['side'])
				{
					//подсчитаем опыт и золото за нанесенный урон
					$dam_hp = min($damage_hp,$this->all[$kogo]['HP']);
					$k = $dam_hp/$this->all[$kogo]['HP_MAX'];
					if ($this->all[$kogo]['npc']==0)
					{
						$gp = $this->formula_gp($this->all[$kogo],$this->all[$kto],$dam_hp);
						$exp = $this->formula_exp($this->all[$kogo],$this->all[$kto],$dam_hp);
					}
					else
					{           
						$Npc = new Npc($kogo);
						$gp = round($k*$Npc->templ['npc_gold'],2);
						$exp = round($k*$Npc->npc['EXP']);	
						//$exp = round($k*$Npc->npc['EXP']/100*$this->getProcentExpNpc($kto));  Отмена урезки опыта при большом уровне игрока
						if ($this->combat['map_name']==691 OR $this->combat['map_name']==692 OR $this->combat['map_name']==804)
						{
							$gp=0;
							$exp=0;
						}
					}
					$exp=max(0,$exp);
					$gp=max(0,$gp);
					$userban=myquery("select count(*) from game_ban where user_id=$kto and type=2 and time>".time()."");
					if (mysql_result($userban,0,0)>0)
					{
						$exp = round($exp/5);
						$gp = round($gp/5,2);
					}
				}
				if ($this->map['not_exp']==1) $exp=0;
				if ($this->map['not_gp']==1) $gp=0;
				if ($this->all[$kto]['not_exp']==1) $exp=0;
				if ($this->all[$kto]['not_gp']==1) $gp=0;

				if ($this->all[$kto]['npc']==0 and ($exp>0 or $gp>0))
				{
					myquery("INSERT INTO combat_users_exp (combat_id,user_id,prot_id,exp,gp) VALUES (".$this->combat['combat_id'].",$kto,$kogo,$exp,$gp) ON DUPLICATE KEY UPDATE exp=exp+$exp,gp=gp+$gp");
				}

				$damage_hp = min($damage_hp,$this->all[$kogo]['HP']);
				$damage_mp = min($damage_mp,$this->all[$kogo]['MP']);
				$damage_stm = min($damage_stm,$this->all[$kogo]['STM']);
				
				$hp_before = $this->all[$kogo]['HP'];
				
				$this->all[$kogo]['HP']-=$damage_hp;
				$this->all[$kogo]['MP']-=$damage_mp;
				$this->all[$kogo]['STM']-=$damage_stm;
				$this->all[$kogo]['HP_start']=max(0,$this->all[$kogo]['HP_start']-$damage_hp);

				if($hp_before>0 AND $this->all[$kogo]['HP']<=0)
				{
					if ($this->all[$kogo]['npc']==1)
					{
						//АЙ-АЙ-ЯЙ... УБИЛИ БОТА....НИ ЗА ЧТО ЗАМОЧИЛИ....
                        if ($Npc->templ['npc_id']==npc_id_nechto)
                        {
                            $mas = array();
                            foreach ($this->all as $key => $value)
                            {
                                if ($this->all[$key]['npc']==0)
                                {
                                    for ($j=$this->all[$key]['hod_start']; $j<=$this->combat['hod']; $j++)
                                    {
                                        $mas[]=$key;
                                        if ($key==$kto)
                                        {
                                            $mas[]=$key;
                                        }
                                    }
                                }
                            }
                            if (sizeof($mas)>0)
                            {
                                $user_loot = $mas[mt_rand(0,sizeof($mas)-1)];
                            }
                            else
                            {
                                $user_loot = $kto;
                            }
                            $Npc->drop_loot($user_loot);
                            $say = 'В Средиземье ('.$this->combat['map_xpos'].','.$this->combat['map_ypos'].') было повержено [b]НЕЧТО[/b]. Имя героя, одолевшего этого монстра - [color=yellow][b]'.$this->all[$kto]['name'].'![/b][/color]. Слава герою!!!';
                            $say = iconv("Windows-1251","UTF-8//IGNORE","<span style=\"font-style:italic;font-size:12px;color:gold;font-family:Verdana,Tahoma,Arial,Helvetica,sans-serif\">".$say."</b></span>");
                            myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".mysql_real_escape_string($say)."',".time().",-1)");
                        }
                        else
                        {
							$Npc->drop_loot($kto);
							$Npc->check_hunter($kto);
                        }
					}
					//ВСЕ. КТО-ТО СЕЙЧАС СТАЛ ТРУПОМ.
					$this->user_dead($kogo,$kto);
					if ($this->all[$kto]['pol']=='female')
					{
						$this->log[$kto][]['action'] = 48;
					}
					else
					{
						$this->log[$kto][]['action'] = 49;
					}
					$index = sizeof($this->log[$kto])-1;
					$this->log[$kto][$index]['na_kogo'] = $kogo;
					$this->log[$kto][$index]['na_kogo_name'] = $this->all[$kogo]['name'];
					$this->log[$kto][$index]['name'] = $this->all[$kto]['name'];
					$this->log[$kto][$index]['mode'] = $kto;
					if ($this->all[$kto]['npc']==1 AND $this->all[$kogo]['npc']==0)
					{
						//бот убил игрока
						if (function_exists("save_stat"))
						{
							save_stat($kogo,$kto,'',2,'','','','','','','',''); 
						}
					}
				}
			}
		}  	
		
		//ЛОГ ДЛЯ ВХОДЯЩИХ В БОЙ
		$selnew = myquery("SELECT * FROM combat_users WHERE `join`=1 AND combat_id=".$this->combat['combat_id']."");
		$kol = mysql_num_rows($selnew);
		if ($kol>0)
		{
			$sort_log[0]=0;
			while ($newuser = mysql_fetch_array($selnew))
			{
				$this->log[0][]['action'] = 3;
				$index = sizeof($this->log[0])-1;
				$this->log[0][$index]['na_kogo'] = $newuser['user_id'];
				$this->log[0][$index]['na_kogo_name'] = $newuser['name'];
				if ($newuser['k_komu']>0)
				{
					$this->log[0][$index]['name'] = $this->all[$newuser['k_komu']]['name'];
					$this->log[0][$index]['mode'] = $newuser['svitok'];
				}
			}
		}
		
		//ФОРМИРОВАНИЕ ЛОГА ХОДА
		myquery("UPDATE game_combats_log SET hod=".$this->combat['hod']." WHERE boy=".$this->combat['combat_id']."");
		$value_insert = "";
		asort($sort_log);
		foreach ($sort_log as $uid=>$sort)
		{
			$nomer = $sort;
			$key = $uid;
			$user_log_array = $this->log[$uid];
			foreach ($user_log_array as $index=>$value)
			{
				$text_id = 0;
				$na_kogo = 0;
				$kto = 0;
				$log = $user_log_array[$index];
				if (!isset($log['mode'])) $log['mode']='';
				if (!isset($log['name'])) $log['name']='';
				if (!isset($log['kuda'])) $log['kuda']='';
				if ($log['mode']!='' OR $log['name']!='' OR $log['kuda']!='')
				{
					$che = myquery("SELECT id FROM game_combats_log_text WHERE (name='".$log['name']."' AND mode='".$log['mode']."' AND kuda='".$log['kuda']."')");
					if (mysql_num_rows($che))
					{
						list($text_id) = mysql_fetch_array($che);
					}
					else
					{
						myquery("INSERT INTO game_combats_log_text (name,mode,kuda) VALUES ('".$log['name']."','".$log['mode']."','".$log['kuda']."')");
						$text_id = mysql_insert_id();
					}
				}
				if (!isset($log['na_kogo'])) $log['na_kogo']='';
				if (!isset($log['na_kogo_name'])) $log['na_kogo_name']='';
				if ($log['na_kogo']!='' OR $log['na_kogo_name']!='')
				{
					$log['na_kogo'] = ''.$log['na_kogo'].'';
					$che = myquery("SELECT id FROM game_combats_log_text WHERE (name='".$log['na_kogo_name']."' AND mode='".$log['na_kogo']."')");
					if (mysql_num_rows($che))
					{
						list($na_kogo) = mysql_fetch_array($che);
					}
					else
					{
						myquery("INSERT INTO game_combats_log_text (name,mode) VALUES ('".$log['na_kogo_name']."','".$log['na_kogo']."')");
						$na_kogo = mysql_insert_id();
					}
				}
				$kto_name = '*****';
				if (isset($this->all[$key]))
				{
					$kto_name = $this->all[$key]['name'];
				}
				$che = myquery("SELECT id FROM game_combats_log_text WHERE (name='".$kto_name."' AND mode='".$key."')");
				if (mysql_num_rows($che))
				{
					list($kto) = mysql_fetch_array($che);
				}
				else
				{
					myquery("INSERT INTO game_combats_log_text (name,mode) VALUES ('".$kto_name."','".$key."')");
					$kto = mysql_insert_id();
				}
				if (!isset($log['action'])) $log['action']=0;
				if (!isset($text_id)) $text_id=0;
				if (!isset($na_kogo)) $na_kogo=0;
				if (!isset($log['add_hp'])) $log['add_hp']=0;
				if (!isset($log['add_mp'])) $log['add_mp']=0;
				if (!isset($log['add_stm'])) $log['add_stm']=0;
				if (!isset($log['minus_hp'])) $log['minus_hp']=0;
				if (!isset($log['minus_mp'])) $log['minus_mp']=0;
				if (!isset($log['minus_stm'])) $log['minus_stm']=0;
				if (!isset($log['procent'])) $log['procent']=0;

				if (strlen($value_insert)>0)
				{
					$value_insert.=",";    
				}
				$value_insert.="(".$this->combat['combat_id'].",".$key.",".$kto.",".$this->combat['hod'].",".$log['action'].",".$text_id.",".$log['procent'].",".$na_kogo.",".$log['add_hp'].",".$log['add_mp'].",".$log['add_stm'].",".$log['minus_hp'].",".$log['minus_mp'].",".$log['minus_stm'].",".$nomer.")";
			}
		}
		if ($value_insert!='')
		{
			myquery("INSERT INTO game_combats_log_data (boy,user_id,kto,hod,action,text_id,procent,na_kogo,add_hp,add_mp,add_stm,minus_hp,minus_mp,minus_stm,sort) VALUES ".$value_insert."");
		}
		
		//обновим основную запись об игроке по результатам расчета
		$str_update = '';
		foreach($this->all AS $key=>$value)
		{
			//if ($this->combat['combat_type']==8 OR $this->combat['combat_type']==9 OR $this->combat['combat_type']==10)
            if ($this->combat['combat_type']==10)
			{
				$this->all[$key]['lose']=0;
				$this->all[$key]['win']=0;
			}
			$this->all[$key]['HP']=max(0,min($this->all[$key]['HP'],$this->all[$key]['HP_MAX']));
			$this->all[$key]['MP']=max(0,min($this->all[$key]['MP'],$this->all[$key]['MP_MAX']));
			$this->all[$key]['STM']=max(0,min($this->all[$key]['STM'],$this->all[$key]['STM_MAX']));
			$query = "UPDATE combat_users SET HP=".((int)$this->all[$key]['HP']).",MP=".((int)$this->all[$key]['MP']).",STM=".((int)$this->all[$key]['STM'])." WHERE user_id=$key";
			$str_update.='<br />'.$query;
			myquery($query);
			if ($this->all[$key]['npc']==0)
			{
				myquery("UPDATE game_users SET HP=".((int)$this->all[$key]['HP']).",MP=".((int)$this->all[$key]['MP']).",STM=".((int)$this->all[$key]['STM']).",win=win+".((int)$this->all[$key]['win']).",lose=lose+".((int)$this->all[$key]['lose']).",GP=GP+".((double)$this->all[$key]['gp']).",EXP=EXP+".((int)$this->all[$key]['exp']).",CW=CW+".((double)(money_weight*$this->all[$key]['gp']))." WHERE user_id=$key");
			}
			elseif ($this->all[$key]['HP']>0)
			{
				myquery("UPDATE game_npc SET HP=".$this->all[$key]['HP'].",MP=".$this->all[$key]['MP'].",WIN=WIN+".$this->all[$key]['win']." WHERE id=$key");
			}
		}

		//удалим записи из combat_actions по предпоследний ход
		myquery("DELETE FROM combat_actions WHERE combat_id=".$this->combat['combat_id']." AND hod<".$this->combat['hod']."");
				
		if ($this->check_end()==-1)
		{
			//бой еще не окончен, переводим всех игроков в след.ход
			$time = time();
			myquery("UPDATE combat_users SET `join`=0,time_last_active=$time WHERE combat_id=".$this->combat['combat_id']."");
			$sel_users = myquery("SELECT user_id FROM combat_users_state WHERE combat_id=".$this->combat['combat_id']." AND state=6");
            $kol_users = 0;
			while ($us = mysql_fetch_array($sel_users))
			{
				$state = 5;
				$selnpc = myquery("SELECT 1 FROM combat_users WHERE user_id=".$us['user_id']." AND npc=1");
				if ($selnpc!=false AND mysql_num_rows($selnpc)>0)
				{
					$state = 6;
				}
                else
                {
                    $kol_users++;
                }
				combat_setFunc($us['user_id'],$state,$this->combat['combat_id']);
			}
            $extra = 1;
            if ($kol_users>=4 AND $this->combat['hod']>=3)
            {
                $extra = 2;
            }
			myquery("UPDATE combat SET hod=hod+1,time_last_hod=$time,extra=$extra WHERE combat_id=".$this->combat['combat_id']."");
			return 0;
		}
		else
		{
			//бой окончен, победила одна из сторон
			$sel_users = myquery("SELECT user_id FROM combat_users_state WHERE combat_id=".$this->combat['combat_id']." AND state IN (5,6)");
			while ($us = mysql_fetch_array($sel_users))
			{
				combat_setFunc($us['user_id'],7,$this->combat['combat_id'],$this->combat['hod']);
			}
			$check_npc=myquery("SELECT Distinct t2.user_id as id
							FROM combat_users_state as t1
							Join combat_users as t2 On t1.combat_id=t2.combat_id
							Where t2.npc=1 and t1.combat_id=".$this->combat['combat_id']." AND t1.state =7");

			while ($combat_npc = mysql_fetch_array($check_npc))
			{
				$Npc = new Npc($combat_npc['id']);
				if ($Npc->npc['stay']==2 or $Npc->npc['stay']==3) 
				{	
					myquery("Delete From game_npc Where id=".$Npc->npc['id']."");
					$flag=1;
				}
				if ($Npc->npc['stay']==3) 
				{
					myquery("Delete From game_npc_template Where npc_id=".$Npc->npc['npc_id']."");
					$flag++;
				}
				if (!isset($flag))
				{
					$check2=myquery("Select * From game_npc_set_option Where opt_id=13 and npc_id=".$Npc->npc['npc_id']."");
					if (mysql_num_rows($check2)>0) myquery("Update game_npc Set HP=".$Npc->templ['npc_max_hp'].", MP=".$Npc->templ['npc_max_mp']." Where npc_id=".$Npc->npc['npc_id']."");	
				}
			}		
			$this->clear_combat();
			return 1;
		}
	}
	
	private function break_items($user_id,$from_npc)
	{
		//уменьшим ресурс прочности предметов, кроме оружия
		$minus = 1;
		if ($from_npc==1)
		{
			$minus = 0.7;
		}
		myquery("UPDATE game_items,game_items_factsheet SET game_items.item_uselife=game_items.item_uselife-$minus WHERE game_items.user_id=$user_id AND game_items.used>0 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type NOT IN (1,3,12,13,19,21)");
		//если текущая поточная прочность = 0, то вещь снимаем
		$sel = myquery("SELECT game_items.id FROM game_items,game_items_factsheet WHERE game_items.used>0 AND game_items.priznak=0 AND game_items.user_id=$user_id AND game_items.item_uselife<=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.type NOT IN (12,13,19,21)");
		while (list($it_id) = mysql_fetch_array($sel))
		{
			$Item = new Item($it_id);
			$Item->down();
		}
	}
    
    private function check_item_down($id_items,$user_id)
    {
        if ($id_items == -1)
        {
            $sel = myquery("SELECT id FROM game_items WHERE used=1 AND priznak=0 AND user_id=$user_id AND item_uselife<=0");
            while (list($it_id) = mysql_fetch_array($sel))
            {
                $Item = new Item($it_id);
                $Item->down();
            }
        }
        else
        {
            $sel = myquery("SELECT id FROM game_items WHERE used>0 AND priznak=0 AND user_id=$user_id AND item_uselife<=0 AND id=$id_items");
            while (list($it_id) = mysql_fetch_array($sel))
            {
                $Item = new Item($it_id);
                $Item->down();
            }
        }
    }
	
	private function against_npc($user_id)
	{
		//проверяет - есть ли среди противников живые игроки
		//если есть - возврат false, иначе true
		foreach ($this->all as $key=>$value)
		{
			if ($this->all[$key]['side']!=$this->all[$user_id]['side'])
			{
				if ($this->all[$key]['npc']==0)
				{
					return false;
				}
			}
		}
		return true;
	}
	
	private function user_dead($user_id,$user_win) //отработка смерти игрока или бота в бою, одновременно отрабатываем действия над его противниками
	{
		if ($this->all[$user_id]['npc']==1)//Умер бот
		{
			//удаляем бота из таблицы состояний боя
			combat_delFunc($user_id);
			
			$expsumm = 0;
			$sel = myquery("SELECT * FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=".$user_id." AND (exp>0 OR gp>0)");                
            //начисляем накопленный опыт за бота игрокам
			while ($userwin = mysql_fetch_array($sel))
			{
				if (!isset($this->all[$userwin['user_id']])) continue;
				if ($this->map['not_exp']==1) $userwin['exp']=0;
				if ($this->map['not_gp']==1) $userwin['gp']=0;
				if ($this->all[$userwin['user_id']]['not_exp']==1) $userwin['exp']=0;
				if ($this->all[$userwin['user_id']]['not_gp']==1) $userwin['gp']=0;
				//для 3го типа квестов движка
				myquery("UPDATE quest_engine_users SET par2_value=par2_value+".$userwin['exp']." WHERE user_id=".$userwin['user_id']." AND quest_type=3");    
				$this->all[$userwin['user_id']]['exp']+=$userwin['exp'];
				$this->all[$userwin['user_id']]['gp']+=$userwin['gp'];
				if ($userwin['user_id']==$user_win)
				{
					$expsumm+=$userwin['exp'];
				}
				if ($userwin['gp']!=0) setGP($userwin['user_id'],$userwin['gp'],22);
				if ($userwin['exp']!=0) setEXP($userwin['user_id'],$userwin['exp'],1);
				if (function_exists("save_stat"))
				{
					save_stat($userwin['user_id'],$user_id,"",5,"","","",$userwin['gp'],"",$userwin['exp'],"","");
				}
				$userwin_clan = (int)$this->all[$userwin['user_id']]['clan_id'];
				$da = getdate();
				$user_exp_store = 0;
				$npc_exp_store = (int)$userwin['exp'];
				myquery("INSERT INTO game_combats_exp (clan_id,year,month,npc_exp,user_exp) VALUES ($userwin_clan,".$da['year'].",".$da['mon'].",$npc_exp_store,$user_exp_store) ON DUPLICATE KEY UPDATE npc_exp=npc_exp+$npc_exp_store,user_exp=user_exp+$user_exp_store");
				if ($userwin['exp']>0 or $userwin['gp']>0)
				{
					$mes = '<font color=\"#eeeeee\">Ты '.echo_sex('выиграл','выиграла',$this->all[$userwin['user_id']]['pol']).' бой. ';
					$mes.= ''.$this->all[$user_id]['name'].' был побежден и бежал в неизвестном направлении!'; 
					$mes.= ' Ты получаешь <span style=\"font-weight:800;color:gold;\">'.$userwin['exp'].'</span> опыта';
					if ($userwin['gp']!=0)
					{
						$mes.=' и <span style=\"font-weight:800;color:gold;\">'.$userwin['gp'].'</span> монет';
					}
					$mes = $mes.'!</font>';
					$result = myquery("INSERT game_battles SET attacker_id=".$userwin['user_id'].", target_id=$user_id, map_name=".$this->combat['map_name'].", map_xpos=".$this->combat['map_xpos'].", map_ypos=".$this->combat['map_ypos'].", contents='".mysql_real_escape_string($mes)."', post_time=".time()."");
				}
			}
			myquery("DELETE FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=".$user_id."");
			//перемещение бота, выставление времени респауна, "лечение" бота
			$this->clear_user($user_id);
			$Npc = new Npc($user_id);
			$Npc->on_dead();
			//если убивается бот вводного квеста в Гильдии Новичков
			$sel = myquery("SELECT step FROM game_users_intro WHERE user_id=$user_win");
			if ($sel!=false AND mysql_num_rows($sel)>0)
			{
				list($step)=mysql_fetch_array($sel);
				if ($step==10 OR $step==11)
				{
					myquery("UPDATE game_users_intro SET step=step+1 WHERE user_id=$user_win");
				}
			}
			
			//проверка квестовых заданий на убийство бота            
			$selquest = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_win AND quest_id=1 AND sost=2");
			if (mysql_num_rows($selquest))
			{
				if (isset($_SESSION['quest1_step']))
				{
					myquery("UPDATE game_quest_users SET sost=0,last_time=0 WHERE user_id=$user_win AND quest_id=1 AND sost=2");
					if ($_SESSION['quest1_step']==7) {$_SESSION['quest1_step']=8;};
					if ($_SESSION['quest1_step']==14) {$_SESSION['quest1_step']=15;};
					if ($_SESSION['quest1_step']==24) {$_SESSION['quest1_step']=25;};
					if ($_SESSION['quest1_step']==27) {$_SESSION['quest1_step']=28;};
					if ($_SESSION['quest1_step']==30) {$_SESSION['quest1_step']=31;};
					if ($_SESSION['quest1_step']==32) {$_SESSION['quest1_step']=33;};
					if ($_SESSION['quest1_step']==34) {$_SESSION['quest1_step']=35;};
					if ($_SESSION['quest1_step']==36) {$_SESSION['quest1_step']=37;};
					if ($_SESSION['quest1_step']==38) {$_SESSION['quest1_step']=39;};
					if ($_SESSION['quest1_step']==40) {$_SESSION['quest1_step']=41;};
					if ($_SESSION['quest1_step']==42) {$_SESSION['quest1_step']=43;};
					if ($_SESSION['quest1_step']==44) {$_SESSION['quest1_step']=45;};
					if ($_SESSION['quest1_step']==51) {$_SESSION['quest1_step']=52;};
				}
			}
			$selquest = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_win AND quest_id=21 AND sost=2");
			if (mysql_num_rows($selquest))
			{
				if (isset($_SESSION['quest2_step']))
				{
					myquery("UPDATE game_quest_users SET sost=0,last_time=0 WHERE user_id=$user_win AND quest_id=21 AND sost=2");
					if ($_SESSION['quest2_step']==201) {$_SESSION['quest2_step']=202;};
					if ($_SESSION['quest2_step']==203) {$_SESSION['quest2_step']=204;};
					if ($_SESSION['quest2_step']==205) {$_SESSION['quest2_step']=206;};
					if ($_SESSION['quest2_step']==207) {$_SESSION['quest2_step']=208;};
					if ($_SESSION['quest2_step']==209) {$_SESSION['quest2_step']=210;};
					if ($_SESSION['quest2_step']==211) {$_SESSION['quest2_step']=212;};
				}
			}
			
			//движок квестов
			include("quest/quest_engine_types/quests_engine_wincheck.php");
			if ($Npc->npc['npc_quest_id']>=2 AND $Npc->npc['npc_quest_id']<=7)
			{
				$selquest = myquery("SELECT * FROM game_quest_users WHERE user_id=$user_win AND quest_id=".$Npc->npc['npc_quest_id']." AND sost=".$Npc->npc['npc_id']."");
				if (mysql_num_rows($selquest))
				{
					$proc = 100;
					if ($Npc->npc['EXP']==0) $proc=0;
					elseif ($expsumm<($Npc->npc['EXP']-5)) $proc = 100*($expsumm/$Npc->npc['EXP']);
					$proc = min(100,$proc);
					myquery("DELETE FROM game_items WHERE user_id=$user_win AND used=0 AND item_for_quest=".$Npc->npc['npc_quest_id']."");
					$npc_item = $Npc->npc['npc_quest_item'];
					$Item = new Item();
					$ar = $Item->add_user($npc_item,$user_win,1,$Npc->npc['npc_quest_id']);
					if ($ar[0]==1)
					{
						myquery("UPDATE game_items SET item_uselife=$proc WHERE id=".$ar[1]."");
					}
				}
			}
		}
		else   //УМЕР ИГРОК
		{
            if ($this->combat['combat_type']==10)
			//if (($this->combat['combat_type']==8)
			//OR($this->combat['combat_type']==9)
			//OR($this->combat['combat_type']==10)
			//OR($this->combat['combat_type']==11))
			{
				$this->map['not_lose']=1;
				$this->map['not_win']=1;
			}
			
			$this->break_items($user_id,$this->all[$user_win]['npc']);//уменьшим текущий ресурс прочности предметов

			//переводим накопленный опыт в очки опыта
			$this->nachisl_exp_gp($user_id,2,$user_win);

			if ($this->against_npc($user_id))//если среди противников умершего есть только NPC
			{
				$this->check_quest_lose($user_id);
			}
			
            if ($this->combat['combat_type']<8 or $this->combat['combat_type']>11)
			//if (($this->combat['combat_type']!=8)
			//AND($this->combat['combat_type']!=9)
			//AND($this->combat['combat_type']!=10)
			//AND($this->combat['combat_type']!=11)) 
            {
                $this->user_teleport($user_id);
            }                
			
			//отразим смерть и на ездовом животном!
			$check = myquery("SELECT id,golod FROM game_users_horses WHERE used=1 AND user_id=$user_id");
			if (mysql_num_rows($check)>0)
			{
				list($id_horse,$golod) = mysql_fetch_array($check);
				$r = mt_rand(1,5);
				$k = 0;
				switch ($golod)
				{
					case 0: $k = 0; break;
					case 1: $k = 1; break;
					case 2: $k = 2; break;
					case 3: $k = 3; break;
					case 4: $k = 4; break;
					default: $k = 10; break;
				} 
				$add = $r*$k;
				myquery("UPDATE game_users_horses SET life=GREATEST(0,life-$add) WHERE id=$id_horse");
			}
			
			//$this->all[$user_id]['HP']=1;
			//$this->all[$user_id]['MP']=1;
			//$this->all[$user_id]['STM']=1;
			if ($this->all[$user_win]['npc']==0)
			{
				if ($this->map['not_lose']==0) $this->all[$user_id]['lose']++;
				save_stat($user_id,'','',6,'','',$user_win,0,$this->all[$user_win]['clan_id'],0,$this->all[$user_id]['clevel'],$this->all[$user_win]['clevel']);
			}
			else
			{
				/*list($npc_flag,$npc_template_id) = mysql_fetch_array(myquery("SELECT npc_flag,npc_id FROM game_npc WHERE id=$user_win"));
				if ($npc_flag==2 OR $npc_flag==3)
				{
					//из квеста новичков
					myquery("UPDATE game_npc SET HP=".$this->all[$user_win]['HP_MAX'].",MP=".$this->all[$user_win]['MP_MAX']." WHERE id=$user_win");
				}
				elseif ($npc_flag==4)
				{
					//из боя с тенью
					myquery("DELETE FROM game_npc WHERE id=$user_win");
					myquery("DELETE FROM game_npc_template WHERE npc_id=$npc_template_id");	
				}*/
			}
			if ($this->map['not_win']==0) $this->all[$user_win]['win']++;
			
			combat_setFunc($user_id,8,$this->combat['combat_id'],$this->combat['hod']);//установим состояние игрока в бою
			$this->clear_user($user_id);//очистим записи боя от этого игрока
		}
	}

	private function formula_exp($kogo,$kto,$dam_hp)
	{ 
		$damage = (int)$dam_hp;
		if ($damage == -1)
		{
			$damage = 0.1*$kogo['HP_MAX'];
		}
		else
		{
			$damage = min($damage,$kogo['HP_start']);
		}
		$exp = max(0,round(($damage/2*5+($damage/4*($kogo['clevel']-$kto['clevel'])))*$this->k_exp * $this->get_koeff_from_sklon($kto['sklon'],$kogo['sklon'],$kto['clan_id']),2));
		if ($this->combat['turnir_type']!=0 AND $kto['clevel']>1)
		{
			$exp=$exp*0.7;
		}
        else
        {
            $exp=$exp*$this->combat['extra'];
        }
		return $exp;
	}
	
	private function formula_gp($kogo,$kto,$dam_hp)
	{
		$damage = (int)$dam_hp;
		if ($damage == -1)
		{
			$damage = 0.1*$kogo['HP_MAX'];
		}
		else
		{
			$damage = min($damage,$kogo['HP_start']);
		}
		$k = $damage/$kogo['HP_MAX'];
		$gp = round($k*( $kogo['clevel']*3 )*$this->k_gp * $this->get_koeff_from_sklon($kto['sklon'],$kogo['sklon'],$kto['clan_id']),2);
		if ($this->combat['turnir_type']!=0)
		{
			$gp=$gp/10;
		}
		return $gp;
	}
	
	private function nachisl_exp_gp($user_id,$par,$user_win=0)
	{
		//теперь дадим опыт за умершего всем кто его бил и еще жив на данный момент
		$sel = myquery("SELECT * FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=$user_id AND (exp>0 OR gp>0)");
		//проверим, а не очередная ли это дуэль между одними участниками боя	
		if (($this->combat['combat_type']>=1 or $this->combat['combat_type']<=3 or $this->combat['combat_type']==5) and !$this->against_npc($user_id) and $par!=1)
		{
			/*$date_time_array = getdate(time());
			$sec=$date_time_array['seconds']+$date_time_array['minutes']*60+$date_time_array['hours']*3600;*/
			list($kol)=mysql_fetch_array(myquery("Select count(1) from combat_users where combat_id=".$this->combat['combat_id'].""));
			$test=myquery("Select game_combats_users.boy, count(game_combats_users.user_id) as tt
						   From game_combats_users 
						   Join game_combats_log on game_combats_log.boy=game_combats_users.boy
						   Where time>(UNIX_TIMESTAMP()-24*60*60) and type in (1,2,3,5)
						   Group By game_combats_users.boy
						   Having tt=$kol");
			if (mysql_num_rows($test)>0)
			{
				$kol_combats=0;
				while ($combat_id=mysql_fetch_array($test))
				{
					$f=1;
					$test2=myquery("Select user_id From game_combats_users Where boy=".$combat_id['boy']."");
					while ($us_id=mysql_fetch_array($test2))
					{
						$test3=myquery("Select * From combat_users where combat_id=".$this->combat['combat_id']." and user_id=".$us_id['user_id']."");
						if (mysql_num_rows($test3)==0) $f=0;
					}
					if ($f==1) $kol_combats++;
				}
			}
		}
		while ($userwin = mysql_fetch_array($sel))
		{
			if (isset($kol_combats) and $kol_combats>2)
			{
				$koef=max(0,1-($kol_combats-2)*0.2);
				$userwin['exp']=max(1,$userwin['exp']*$koef);
				$userwin['gp']=max(1,$userwin['gp']*$koef);
				if ($koef<0.5) myquery("Update game_users Set win=win-1 Where user_id=$user_win");
			}
			if ($this->all[$userwin['user_id']]['npc']==1) continue;
			if ($this->all[$userwin['user_id']]['HP']<=0) continue;
			if ($this->all[$userwin['user_id']]['side']==$this->all[$user_id]['side']) continue;
			
			$last_userwin_id = $userwin['user_id'];
			myquery("DELETE FROM combat_users_exp WHERE combat_id=".$this->combat['combat_id']." AND prot_id=$user_id AND user_id=".$userwin['user_id']."");
			if ($this->map['not_exp']==1) $userwin['exp']=0;
			if ($this->map['not_gp']==1) $userwin['gp']=0;
			if ($this->all[$userwin['user_id']]['not_exp']==1) $userwin['exp']=0;
			if ($this->all[$userwin['user_id']]['not_gp']==1) $userwin['gp']=0;
			$mes = '';
			if ($userwin['exp']>0 or $userwin['gp']>0)
			{
				//для 3го типа квестов движка
				myquery("UPDATE quest_engine_users SET par2_value=par2_value+".$userwin['exp']." WHERE user_id=".$userwin['user_id']." AND quest_type=3");
				
				$mes.='<font color="#0080C0" size="2" face="Verdana">&nbsp;&nbsp;'.$this->all[$user_id]['name'].'';
				if ($par==1)
				{
					if ($this->all[$user_id]['pol']=='female')
					{
						$mes.=' вылетела по таймауту.';
						$this->log[$userwin['user_id']][]['action'] = 53; 
						$index = sizeof($this->log[$userwin['user_id']])-1;
						$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
						$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
					}
					else 
					{
						$mes.=' вылетел по таймауту.';
						$this->log[$userwin['user_id']][]['action'] = 54; 
						$index = sizeof($this->log[$userwin['user_id']])-1;
						$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
						$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
					}
					//при вылете по таймауту не вызывается calculate, поэтому обновляем БД здесь
					myquery("UPDATE game_users SET EXP=EXP+".$userwin['exp'].",GP=GP+".$userwin['gp']." WHERE user_id=".$last_userwin_id."");
				}
				elseif ($par==2)
				{
					if ($last_userwin_id==$user_win and $this->map['not_win']==0)
					{
						//запишем статистику
						if (function_exists("save_stat"))
						{
							save_stat($user_win,'','',7,'','',$user_id,$userwin['gp'],$this->all[$user_win]['clan_id'],$userwin['exp'],$this->all[$user_id]['clevel'],$this->all[$user_win]['clevel']);
						}
					}
					
					if ($user_win==$userwin['user_id'])
					{
						$mes = '<font color=\"#eeeeee\">Ты '.echo_sex('победил','победила',$this->all[$user_win]['pol']).' игрока <b>'.$this->all[$user_id]['name'].'</b> и он';
						if ($this->all[$user_id]['pol']=='female')
							$mes.='а бежала';
						else
							$mes.=' бежал';
						$mes.=' в неизвестном направлении!</font> ';
					 }
					else
					{
						$mes = '<font color=\"#eeeeee\">Игрок <b>'.$this->all[$user_id]['name'].'</b> ';
						if ($this->all[$user_id]['pol']=='female')
							$mes.='была побеждена и бежала';
						else
							$mes.='был побежден и бежал';
						$mes.=' в неизвестном направлении!</font> ';
					}

					//и сообщим об этом в логах
					if ($this->all[$user_id]['pol']=='female') 
					{
						$this->log[$userwin['user_id']][]['action'] = 50; 
						$index = sizeof($this->log[$userwin['user_id']])-1;
						$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
						$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
					}
					else 
					{
						$this->log[$userwin['user_id']][]['action'] = 51; 
						$index = sizeof($this->log[$userwin['user_id']])-1;
						$this->log[$userwin['user_id']][$index]['na_kogo'] = $user_id;
						$this->log[$userwin['user_id']][$index]['na_kogo_name'] = $this->all[$user_id]['name'];
					}
					$this->all[$userwin['user_id']]['exp']+=$userwin['exp'];	
					$this->all[$userwin['user_id']]['gp']+=$userwin['gp'];
				 }
				
				$this->log[$userwin['user_id']][]['action'] = 52; 
				$index = sizeof($this->log[$userwin['user_id']])-1;
				$this->log[$userwin['user_id']][$index]['add_hp'] = $userwin['exp'];
				$this->log[$userwin['user_id']][$index]['procent'] = $userwin['gp'];
				
				setGP($userwin['user_id'],$userwin['gp'],25);
				setEXP($userwin['user_id'],$userwin['exp'],2);
				$da = getdate();
				$userwin_clan = $this->all[$userwin['user_id']]['clan_id'];
				$user_exp_store = $userwin['exp'];
				$npc_exp_store = 0;
				myquery("INSERT INTO game_combats_exp (clan_id,year,month,npc_exp,user_exp) VALUES ($userwin_clan,".$da['year'].",".$da['mon'].",$npc_exp_store,$user_exp_store) ON DUPLICATE KEY UPDATE npc_exp=npc_exp+$npc_exp_store,user_exp=user_exp+$user_exp_store");

				//и сообщим об этом в логах
				$mes.='    Ты получаешь ';
				if ($userwin['exp']>0) $mes.='<b><font color="#FF0000">'.$userwin['exp'].'</font></b> очков опыта';
				if ($userwin['gp']>0)
				{
					if ($userwin['exp']>0) $mes.=' и ';
					$mes.='<b><font color="#FF0000">'.$userwin['gp'].'</font></b> монет';
				}
				$mes.='</font><br>';
				if ($userwin['exp']>0 or $userwin['gp']>0)
				{
					$result = myquery("INSERT game_battles SET attacker_id=".$userwin['user_id'].", target_id=0, map_name=".$this->combat['map_name'].", map_xpos=".$this->combat['map_xpos'].", map_ypos=".$this->combat['map_ypos'].", contents='".mysql_real_escape_string($mes)."', post_time=".time()."");
				}
			}
		}
	}
	
	private function check_quest_lose($user_id)
	{
		//обработаем квесты
		$sel000 = myquery("SELECT * FROM game_quest_users WHERE quest_id=1 AND user_id=$user_id");
		if (mysql_num_rows($sel000)>0)
		{
			myquery("UPDATE game_quest_users SET sost=0,last_time=".time()." WHERE quest_id=1 AND user_id=$user_id");
		}
		$sel000 = myquery("SELECT * FROM game_quest_users WHERE (quest_id>=2 or quest_id<=7) AND user_id=$user_id");
		if (mysql_num_rows($sel000)>0)
		{
			foreach($this->prot AS $key=>$value)
			{
				myquery("UPDATE game_quest_users SET sost=-1 WHERE sost=".$key." AND user_id=$user_id");
			}
		}
	}
	
	public function user_out($user_id) //отработка вылета игрока из боя по таймауту, одновременно отрабатываем действия над его противниками
	{
		$this->break_items($user_id,0);//уменьшим текущий ресурс прочности предметов
		$this->user_teleport($user_id);
		$travma = 0;
		if (!$this->against_npc($user_id))//если среди противников есть не только NPC
		{
			//наносим травмы
			$current_timeout = date("d-j-Y",time());
			list($last_timeout_boy,$last_timeout) = mysql_fetch_array(myquery("SELECT last_timeout_boy,last_timeout FROM game_users_data WHERE user_id=$user_id"));
			$last_timeout = date("d-j-Y",$last_timeout); 
			if ($last_timeout==$current_timeout AND $last_timeout_boy!=$this->combat['combat_id'] AND $this->map['name']!='Гильдия новичков')
			{
				$travma = 1;
			}
			myquery("UPDATE game_users_data SET last_timeout=".time().",last_timeout_boy=".$this->combat['combat_id']." WHERE user_id=$user_id");
			//если среди противников только 1 живой игрок - тогда победу дадим ему, если несколько, то победу даем рандомно
			$prot = array();
			foreach($this->all AS $key=>$value)
			{
				if ($value['side']!=$this->all[$user_id]['side'])
				{
					$prot[] = $key;
				}
			}
			$user_win_id = 0;
			if (count($prot)==1)
			{
				$user_win_id = $prot[0];
			}
			else
			{
				$r = mt_rand(0,count($prot)-1);
				$user_win_id = $prot[$r];                
			}
			if ($user_win_id>0)
			{
				if ($this->all[$user_win_id]['npc']==0)
				{
					myquery("UPDATE game_users SET WIN=WIN+1 WHERE user_id=$user_win_id");
				}
				else
				{
					myquery("UPDATE game_npc SET WIN=WIN+1 WHERE id=$user_win_id");
				}
			}
		}
		else
		{
			//таймаут в бою с ботами
			$this->check_quest_lose($user_id);
		}
		
		//статистика
		if (function_exists("save_stat"))
		{
			save_stat($user_id,'','',3,'','','','','','','','');
		}
		
		//за вылетевшего по тайму дадим опыта = опыт за полное убийство/количество противников, но не более 10%    и если вылет идет на первом ходу
		$all = array();
		foreach($this->all AS $key=>$value)
		{
			if ($this->all[$key]['side']==$this->all[$user_id]['side']) continue;
			if ($this->all[$key]['npc']==0)
			{
				if ($this->all[$key]['HP']>0)
				{
					$all[]=$key;
				}
			}
		}
		if (count($all)>0)
		{
			$k = min(0.1,1/count($all));
		}
		else
		{
			$k = 0;
		}
		$gp = 0;
		foreach($all AS $key=>$value)
		{
			$exp = $this->formula_exp($this->all[$user_id],$this->all[$value],-1);				
			if ($this->map['not_exp']==1) $exp = 0;
			if ($this->map['not_gp']==1) $gp=0;
			if ($this->all[$value]['not_exp']==1) $exp = 0;
			if ($this->all[$value]['not_gp']==1) $gp=0;
			if ($exp!=0 OR $gp!=0) myquery("INSERT INTO combat_users_exp (combat_id,user_id,prot_id,exp,gp) VALUES (".$this->combat['combat_id'].",".$key.",".$user_id.",$exp,$gp) ON DUPLICATE KEY UPDATE exp=exp+$exp,gp=gp+$gp");
		}
		
		//переводим накопленный опыт в очки опыта
		$this->nachisl_exp_gp($user_id,1);
		
		//$this->all[$user_id]['HP']=1;
		//$this->all[$user_id]['MP']=1;
		//$this->all[$user_id]['STM']=1;
		//$this->all[$user_id]['lose']++;
		$query = "UPDATE game_users SET HP=1,MP=1,STM=1";
		//$travma = 0;//ВРЕМЕННО ДО ИСПРАВЛЕНИЯ БАГОВ!
		if ($travma==1)
		{
			$query.=",HP_MAX=HP_MAX*0.95";
		}
		if (!$this->against_npc($user_id))
		{
			$query.=",LOSE=LOSE+1";
		}
		$query.=" WHERE user_id=$user_id";
		myquery($query);
		
		combat_setFunc($user_id,8,$this->combat['combat_id'],$this->combat['hod']);
		$this->clear_user($user_id);
		check_boy($this->combat['combat_id']);
		
		//запишем в лог хода
		if ($this->all[$user_id]['pol']=='female')
		{
			$log_id = 53;
			$mes = "Ты вылетела по таймауту";  
		}
		else                         
		{
			$log_id = 54;
			$mes = "Ты вылетел по таймауту";  
		}
		$text_id = 0;
		$sel_text_id = myquery("SELECT id FROM game_combats_log_text WHERE name='".$this->all[$user_id]['name']."' AND mode='".$user_id."'");
		if (mysql_num_rows($sel_text_id)==0)
		{
			myquery("INSERT INTO game_combats_log_text (name,mode) VALUES ('".$this->all[$user_id]['name']."',$user_id)");  
			$text_id = mysql_insert_id();
		}
		else
		{
			list($text_id) = mysql_fetch_array($sel_text_id);
		}
		myquery("INSERT INTO game_combats_log_data (boy,user_id,hod,action,na_kogo) VALUES (".$this->combat['combat_id'].",0,".$this->combat['hod'].",$log_id,$text_id)");
		
		myquery("INSERT game_battles SET attacker_id=$user_id, target_id=0, map_name=".$this->combat['map_name'].", map_xpos=".$this->combat['map_xpos'].", map_ypos=".$this->combat['map_ypos'].", contents='".mysql_real_escape_string($mes)."', post_time=".time()."");
	}
	
	private function check_end() //проверка окончания боя по условию победы одной из сторон
	{
		$flag = -1;
		$sel_side = myquery("SELECT DISTINCT side FROM combat_users WHERE combat_id=".$this->combat['combat_id']."");
		if (mysql_num_rows($sel_side)==1)
		{
			//бой окончен, победила одна из сторон
			list($flag) = mysql_fetch_array($sel_side);
		}
		return $flag;
	}
	
	private function user_teleport($user_id)  //перемещение проигравших игроков к рандомному городу
	{
        $not_maze_array = array(691,692,804,id_map_tuman);
		if ($this->map['maze']==1 AND !in_array($this->combat['map_name'],$not_maze_array))
		{
			//В лабиринте (но не в Подземельях Мории) при смерти от бота выкидываем в начало лабиринта
			$map_now=$this->map['id'];
			$xrandmap = 0;
			$yrandmap = 0;
		}
		else
		{
			if ($this->map['arena']==1 OR in_array($this->combat['map_name'],$not_maze_array))
				//Из Арены Смерти и Подземелий Мории и Туманных Гор выкидываем в Средиземье
				$map_now=@mysql_result(@myquery("SELECT id FROM game_maps WHERE name LIKE 'Средиземье'"),0,0);
			else
				$map_now=@mysql_result(@myquery("SELECT map_name FROM game_users_map WHERE user_id=$user_id"),0,0);

			$sel = myquery("SELECT game_map.town AS town,game_map.name AS map_name, game_map.xpos AS xpos, game_map.ypos AS ypos FROM game_map JOIN game_gorod ON game_map.town=game_gorod.town WHERE game_gorod.rustown<>'' AND game_map.name=$map_now AND game_map.to_map_name='' and game_gorod.clan=0");
			if ($sel==false OR !mysql_num_rows($sel))
			{
				$battle_map_query = myquery("SELECT xpos,ypos,name FROM game_map where name='$map_now' ORDER BY xpos DESC, ypos DESC LIMIT 1");
				$battle_map_result = mysql_fetch_array($battle_map_query, MYSQL_ASSOC);
				$xrandmap = mt_rand(0, $battle_map_result['xpos']);
				$yrandmap = mt_rand(0, $battle_map_result['ypos']);
			}
			else
			{
				jump_random_query(&$sel);
				$town=mysql_fetch_assoc($sel);
				$xrandmap = $town['xpos'];
				$yrandmap = $town['ypos'];
			}
			if (strstr($this->map['name'],"Подземелья")!=false)
			{
				myquery("UPDATE dungeon_users_data SET last_visit=".time()." WHERE user_id=$user_id");
			}
		}
		myquery("update game_users_map set map_name=$map_now,map_xpos=$xrandmap,map_ypos=$yrandmap where user_id=$user_id");
	}
	
	private function getProcentExpNpc($kto)
	{
		if ($this->all[$kto]['npc']==0)
		{
			list($currLevel,$currExp) = mysql_fetch_array(myquery("SELECT clevel,EXP FROM view_active_users WHERE user_id=$kto"));
			/*
			$allExp=$currExp;
			for($i=0;$i<=$currLevel-1;$i++)
			{
				if ($i == 0)
				{
					$exp = 200;
				}
				else
				{
					$exp = $i*($i+1)*200;
				}
				$allExp+=$exp;
			}
			$newLevel = floor((1+sqrt(($allExp+50)/50))/2);
			*/
            
            
			$newLevel = $currLevel;
			if ($newLevel<25)
			{
				$procent = 100;   
			}
			else
			{
				$procent = 100-3*($newLevel-24);
				$procent = max(10,min(100,$procent));
			}
            
            //$procent = 100;
			return $procent;
		}
		else
		{
			return 100;
		}
	}
}
?>