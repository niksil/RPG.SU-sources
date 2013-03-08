<?PHP		

	echo '<align=left>';
	$npc=mysql_fetch_array(myquery("SELECT * FROM quest_engine_owners WHERE id=".$quest_user['quest_owner_id'].""));
	$npc_map=mysql_result(myquery("SELECT name FROM game_maps WHERE id=".$npc['map_name'].""),0,0);
	$ot_kogo=''.$npc['name'].' ('.$npc_map.' '.$npc['map_xpos'].','.$npc['map_ypos'].')';
	
	if($quest_user['quest_finish_time']-time()>0)
		$time=1;
	else $time=0;	
	switch ($quest_user['quest_type'])
	{		
		case 1:
		{			
			get_stat($time,$quest_user['done'],$status,$color);			
			
			$help='Голова убитого монстра &quot;отрубается&quot; сразу после его убийства непосредственно Вами. Если этого не произошло, а также в случае других неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 2:
		{			
			get_stat($time,$quest_user['done'],$status,$color);			
			if($quest_user['par3_name']=='') $quest_user['par3_value']='';
			if($quest_user['par4_name']=='') $quest_user['par4_value']='';
			/*$a=parameter_rus_name($quest_user['par2_name']);
			$b=parameter_rus_name($quest_user['par3_name']);
			$c=parameter_rus_name($quest_user['par4_name']);*/
			
			//$zd="Золото: ".new_word("gold",$reward,0)." Время: ".$time." Бот:".$npc_name." ".$npc_race."";
			$help='Нужная часть тела убитого монстра &quot;вырезается&quot; сразу после его убийства непосредственно Вами. Количество частей указывается в описании соответствующего предмета (т.е. убив 10 монстров, вы будете иметь в инвентаре не 10 их голов, а одну голову с пометкой &quot;10 штук&quot;. Если этого не произошло, а также в случае других неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		/*case 3:
		{						
			if($quest_user['par2_value']>=$quest_user['par1_value']) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);			
			
			if($quest_user['par1_value']%10==0 OR $quest_user['par1_value']%10>=5)							
				$exp='очков';
			elseif($quest_user['par1_value']%10==1) $exp='очко';
			else $exp='очка';	
			
			//$zadanie='Все, что вам нужно сделать - это заработать '.$quest_user['par1_value'].') '.$exp.' опыта и вернуться к заказчику до '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).' за обещанной наградой! Не опаздывайте!';
			$help='В задании указано не то количество опыта, которое вы должны иметь на момент возвращения к заказчику, а именно то количество, что вы должны заработать за означенный промежуток времени. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 4:
		{						
			if($char['win']>=$quest_user['par1_value']) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);			
			
			if($quest_user['par1_value']%10==0 OR $quest_user['par1_value']%10>=5)							
				$pobed='побед';
			elseif($quest_user['par1_value']%10==1) $pobed='победу';
			else $pobed='победы';			
			
			//$zadanie='Чтобы выполнить это задание, вам надо набрать '.$quest_user['par1_value'].') '.$pobed.' и вернуться к заказчику до '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).' за обещанной наградой! Вперед, на поле брани!';
			$help='В задании указано то количество побед, которое вы должны иметь на момент возвращения к заказчику. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}*/
		case 5:
		{			
			if($quest_user['done']==1) {$done=1; $time=1;}
			if($quest_user['done']==2) {$done=1; $time=0;}
			if($quest_user['done']==0) {$done=0;}
			get_stat($time,$done,$status,$color);	
			//list($rustowun)=mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$quest_user['par2_value'].""));
			//list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));	
			//if(substr_count($map_name,'елe'))	$map_name.='e';
			//$zadanie=$npc['name'].' попросил вас доставить некую послыку, заговоренную от вскрытия, в город '.$rustowun.', находящийся в '.$map_name.'. Там вам надо будет всретиться в таверне с нужным человеком - по словам заказчика, тот узнает вас сам, заберет посылку и вручит вам письмо, которое будет свидетельствовать о выполнении задания. Времени у вас на это - до '.strftime("%e-%m-%Y%t%T",$time).', иначе полной награды вам не видать.';
			$help='В задании указано время, за которое вы должны добраться до места назначения, а не время возвращения к заказчику. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 601:
		{
			if($quest_user['done']==1) $done=1;
			if($quest_user['done']==2 OR $quest_user['done']==0 OR $quest_user['done']==3) $done=0;			
			if($quest_user['done']==2) $time=0;
			get_stat($time,$done,$status,$color);				
			list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));			
			if(substr_count($map_name,'елe'))	$map_name.='e';
			list($rustowun)=mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$quest_user['par2_value'].""));
			//$zadanie=$npc['name'].' попросил вас доставить некую послыку, заговоренную от вскрытия, в город '.$rustowun.', находящийся в '.$map_name.'. Там вам надо будет всретиться в таверне с нужным человеком - по словам заказчика, тот узнает вас сам, заберет посылку и вручит вам письмо, которое будет свидетельствовать о выполнении задания. Времени у вас на это - до '.strftime("%e-%m-%Y%t%T",$time).', иначе полной награды вам не видать.';
			$help='Правила потом сюда напишу, ща Лень) (примерно так: есть поле 9х9, разделено на 9 квадратов 3х3. там цифры от 1 до 9 (у нас цвета). есть пустые клетки (черные).надо вставить в пустые клетки цифры (цвета) так, чтобы не было одинаковых цифр: по горизонталям и вертикалям поля и в квадратах 3х3. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 7:
		{
			//$bringed=myquery("SELECT * FROM game_items WHERE ident IN (SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value'].") AND user_id='$user_id'");
			$bringed=myquery("SELECT * FROM game_items WHERE item_id=".$quest_user['par1_value']." AND user_id='$user_id'");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);
			
			list($name)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value']." "));
			//$zadanie=''.$npc['name'].' просил вас принести ему '.$name.'. Причем управиться Вы должны до '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', только в этом случае Вас ждет награда в полном объеме!';
			$help='Трам-бам. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 801:
		{
			$k=0;$zakaz=0;
				$bringed=array();				
				for($j=1;$j<5;$j++)
					if($quest_user['par'.$j.'_value']!=0)
					{					
						$zakaz++;	
						$ind='par';
						$ind.=$j; $ind.='_value';		
						//$result=myquery("SELECT * FROM game_items WHERE ident IN (SELECT name FROM game_items_factsheet WHERE id=".$quest_user["$ind"].") AND used=0 AND user_id=".$user_id."");						
						$result=myquery("SELECT * FROM game_items WHERE item_id=".$quest_user["$ind"]." AND used=0 AND user_id=".$user_id."");						
						$items=mysql_num_rows($result);						
						if($items>0) 
						{
							$k++;
							$bringed[count($bringed)]=$j;
						}
					}
			if($k>0) $done=1; else $done=0;
			
			get_stat($time,$done,$status,$color);
			
			/*$name='';
			for($j=0;$j<4;$j++)
			{
				if(!isset($quest_user['par'.$j.'_value'])) $quest_user['par'.$j.'_value']=0;
			    list($cname)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par'.$j.'_value'].""));			
			    if(!empty($cname))
					$name.=$cname.' или ';																
			}
			$name=substr($name,0,strlen($name)-4);*/
			//$zadanie=''.$npc['name'].' просил вас принести ему какой-нибудь из следующих предметов: '.$name.'. Причем управиться Вы должны до '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', только в этом случае Вас ждет награда в полном объеме!';
			$help='Учтите, что заказчик сам выберет, какой из предметов взять. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 802:
		{
			
			$bringed=myquery("SELECT * FROM game_items WHERE shop_from=".$quest_user['par1_value']." AND type=".$quest_user['par2_value']." AND user_id='$user_id'");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);
			//list($shop_name)=mysql_fetch_array(myquery("SELECT name FROM game_shop WHERE id=".$quest_user['par1_value'].""));
			//$zd='Говорят, у  тоговца по имени '.$shop_name.' есть в продаже '.item_type_name($quest_user['par2_value']).'! '.$npc['name'].' просил притащить ему '.$quest_user['par3_value'].' предметов этого типа от этого торговца.';
			
	        //$zadanie=''.$zd.'. Причем управиться Вы должны до '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', только в этом случае Вас ждет награда в полном объеме! ';
			$help='Учтите, что заказчик спросит вас, какие именно предметы вы хотите отдать. То есть если даже в журнале квест отмечен как выполненный, не факт, что вы согласитесь отдать те предметы, которые подходят под условия задания. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 803:
		{
			if($quest_user['par2_value']>0)
						$bringed=myquery("SELECT * FROM game_items WHERE user_id='$user_id' AND used=0 AND ".$quest_user['par1_name'].">=".$quest_user['par1_value']." AND ".$quest_user['par2_name'].">=".$quest_user['par2_value']."");
					else 
						$bringed=myquery("SELECT * FROM game_items WHERE user_id='$user_id' AND used=0 AND ".$quest_user['par1_name'].">=".$quest_user['par1_value']."");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;	
			get_stat($time,$done,$status,$color);
			
			//$zd='предметов, которые повышают '.item_par_name($quest_user['par1_name']).' на '.$quest_user['par1_value'].'';																
			//					if(isset($quest_user['par2_value']) AND $quest_user['par2_value']!=0)
			//						$zd.=' и '.item_par_name($quest_user['par2_name']).' на '.$quest_user['par2_value'].'';
			//					$zd.=' в количестве '.$quest_user['par3_value'].' штук.';
			
			//$zadanie=''.$npc['name'].' просил притащить ему '.$zd.'. Причем управиться Вы должны до '.strftime("%e-%m-%Y%t%T",$quest_user['quest_finish_time']).', только в этом случае Вас ждет награда в полном объеме! ';
			$help='Учтите, что заказчик спросит вас, какие именно предметы вы хотите отдать. То есть если даже в журнале квест отмечен как выполненный, не факт, что вы согласитесь отдать те предметы, которые подходят под условия задания. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
		case 804:
		{
			$bringed=myquery("SELECT weight FROM game_items WHERE user_id='$user_id' AND used=0 AND item_id=".$quest_user["par1_value"]." AND item_uselife<=".$quest_user['par2_value']." AND item_uselife>=".$quest_user['par3_value']."");
			if(mysql_num_rows($bringed)>0) $done=1; else $done=0;
			get_stat($time,$done,$status,$color);
			list($wname)=mysql_fetch_array(myquery("SELECT name from game_items_factsheet WHERE id=".$quest_user["par1_value"].""));
			//$zadanie=''.$npc['name'].' просил притащить ему '.$wname.', с прочностью не больше, чем '.$quest_user["par2_value"].'%, но не меньше, чем '.$quest_user["par3_value"].'%. Времени у вас на это - до '.strftime("%e-%m-%Y%t%T",$time).', иначе полной награды вам не видать.';
			$help='Оружие, которое вы хотите сдать, не должно быть на вас одето. В случае каких-либо неполадок, сообщите об этом, пожалуйста, игроку Inquisitor_I.';
			break;
		}
	}	
	echo '<UL TYPE=circle>';
	echo '<LI><a name="q'.$i.'"><b>Где получен квест:</b> '.$ot_kogo.'</a>';
	echo '<LI><b>Статус:</b> <font color=#'.$color.'>'.$status.'</font>';
	echo '<LI><b>Задание: </b>';
	/*1*///echo '<font color=#00FF00 size=3>topic_id='.($quest_user['quest_topic_id']).'</font><br>';
	/*1*///echo '<font color=#00FF00 size=3>owner_id='.($quest_user['quest_owner_id']).'</font><br>';
	/*1*///echo '<font color=#00FF00 size=3>quest_type='.($quest_user['quest_type']).'</font><br>';
	//$time=min(0,$quest_user['quest_finish_']);
	

	
	$text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=40 AND quest_type=".$quest_user['quest_type']."");
	//if(!mysql_num_rows($text)) die('NO TOPICS');
	$its_journal=1;
	include("../quest/quest_engine_types/inc/standart_vars.inc.php");	
	
	if(mysql_num_rows($text)>0)
	{
		jump_random_query($text);
		list($text)=mysql_fetch_array($text);
	}
	else $text = "echo 'Запись в журнале. В ЬД не найдена.';";
	//testing
	switch ($quest_user['quest_type'])
	{
		case 1: $text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Бот: $npc_name $npc_race';"; break;
		case 2: $text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Части: $part_name - $num шт<br>Боты: бот $par2_rus_name $par2_value $par3_rus_name $par3_value $par4_rus_name $par4_value';"; break;
		case 5 :$text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Город: $rustowun, ".new_word("map_name",$map_name,1)."';"; break; 
		case 601:$text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Город: ".new_word("map_name",$map_name,1)." $x $y';"; break; 
		case 7:$text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Предмет: $name';"; break; 
		case 801:$text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Предметы: $name';"; break; 
		case 802:$text="echo 'Золото: ".new_word('gold',$reward,0)." <br>Время: $time <br>Предметы: у $shop_name ".new_word("items",$type_id,0)." $num штук';"; break;
	}
	//end testing
	
	eval($text);
	//echo $text;
	echo '<LI><b>Примечания:</b> '.$help.'';
	echo '<LI><a href="?journal='.$journal.'">Обновить</a>';
	echo '</UL>';
	//echo '</q>';

?>