<?PHP
	$char_name='<font color=red>'.$char["name"].'</font>';
	$char_race=mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);
	$reward='<font color=yellow>'.$quest_user["quest_reward"].'</font>';
	
	//time
	if(!isset($its_journal))
	{
		$hours=floor(($quest_user['quest_finish_time']-$quest_user['quest_start_time'])/3600);
		$mins=floor(($quest_user['quest_finish_time']-$quest_user['quest_start_time'])/60)-$hours*60;
	}else 
	{
		$hours=max(0,floor(($quest_user['quest_finish_time']-time())/3600));
		$mins=max(0,floor(($quest_user['quest_finish_time']-time())/60)-$hours*60);
	}
	if($hours==0) $hours=''; else
	{	$hours.=' час';
		if($hours%10>=2 AND $hours%10<=4 AND ($hours<10 OR $hours>19)) $hours.='а';
		elseif ($hours%10>4 or $hours%10==0) $hours.='ов';
	}				
	if($mins==0 AND $hours!=0) $mins='';
	else 
	{	$mins.=' минут';
		if($mins==1 OR ($mins%10==1 AND $mins%100!=11)) $mins.='у';
		elseif($mins%10>=2 AND $mins%10<=4 AND ($mins<10 OR $mins>19)) $mins.='ы';				
	}
	$time='<font color=#aaffff>'.$hours.' '.$mins.'</font>';
	//			
	switch ($quest_user['quest_type'])
	{
		case 1: 
			$npc_name=$quest_user['par2_name'];//имя монстра 
			$npc_race=$quest_user['par3_name']; //раса монстра
		break;
		
		case 2: 
			$part_name=$quest_user['par1_name']; //название требуемой части монстра. 
			$num=$quest_user['par1_value'];  //число, необходимое кол-во частей. 
			$par2_rus_name=parameter_rus_name($quest_user['par2_name']); //название нужного параметра в формате ", сила которого не меньше". 
			$par2_value=$quest_user['par2_value']; //число, значение нужного параметра.
			$par3_rus_name=parameter_rus_name($quest_user['par3_name']);
			if($par3_rus_name!='')
				$par3_value=$quest_user['par3_value'];
			else 
				$par3_value='';
			$par4_rus_name=parameter_rus_name($quest_user['par4_name']);
			if($par4_rus_name!='')
				$par4_value=$quest_user['par4_value'];
			else 
				$par4_value='';
		break;
		
		case 3: 
			$exp=$quest_user['par1_value'];//необходимое кол-во опыта.    
		break;
		
		case 4: 
			$wins=$quest_user['par1_value'];//необходимое кол-во побед.  
		break;
		
		case 5: 
			list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));// - название карты. 
			list($rustowun)=mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$quest_user['par2_value'].""));//- название города.
		break;
		
		case 601: 
			list($map_name)=mysql_fetch_array(myquery("SELECT name FROM game_maps WHERE id=".$quest_user['par1_value'].""));// - название карты.
			$x=$quest_user['par2_value'];
			$y=$quest_user['par3_value'];// - соотв. координаты задания.
		break;
		
		case 7: 
			list($name)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value'].""));
			//название нужного предмета
		break;
		
		case 801: 
			$ids=array($quest_user['par1_value'],$quest_user['par2_value'],$quest_user['par3_value'],$quest_user['par4_value']);
			$name='';
			for($j=0;$j<4;$j++)
			{
				if(!isset($ids[$j])) $ids[$j]=0;
				list($cname)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$ids[$j].""));								
				if(!empty($cname))
				$name.=$cname.' или ';																
			}
			$name=substr($name,0,strlen($name)-4);
			unset($cname);
			unset($ids);
			unset($j);
		break;
		
		case 802: 
			$shop_name=mysql_result(myquery("SELECT name FROM game_shop WHERE id=".$quest_user['par1_value'].""),0,0);// - имя торговца. 
			$type_id=$quest_user['par2_value'];// номер для названия нужного типа в формате "хорошие кольца". 
			$num=$quest_user['par3_value']; //- необходимое кол-во предметов.    
		break;
		
		case 803: 
			$name='';
			$name=''.item_par_name($quest_user['par1_name']).' на '.$quest_user['par1_value'].'';												
			if($quest_user['par2_value']!=0)
				$name.=' и '.item_par_name($quest_user['par2_name']).' на '.$quest_user['par2_value'].'';//- необходимые параметры в формате "силу на 2 и ловкость на 1". 
			$num=$quest_user['par3_value']; //- необходимое кол-во предметов. 
		break;
		
		case 804: 
			list($wname)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$quest_user['par1_value']."")); //- название необходимого оружия. 
			$top=$quest_user['par2_value']; //- число, верхняя граница процента прочности. 
			$buttom=$quest_user['par3_value']; //- число, нижяя граница процента прочности.
		break;
	}
?>