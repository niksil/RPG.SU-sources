<?PHP

if (function_exists("start_debug")) start_debug(); 


function kick($num,$proc)
{	
	global $user_id;
	$hp=0;
	$mp=0;
	$sp=0;
	if($num==3)
	{
		$hp=1;
		$mp=1;
		$sp=1;
	}
	if ($num==2)
	{
		$hp=mt_rand(0,1);
		$sp=mt_rand(1-$hp,1);
		$mp=mt_rand(0,2-$hp-$sp);
	}
	if ($num==1)
	{
		$hp=mt_rand(0,1);
		$sp=mt_rand(0,1-$hp);
		$mp=mt_rand(0,1-$hp-$sp);
	}
	if($hp>0)
	{	
		$uhp=myquery("SELECT HP,HP_MAX FROM game_users WHERE user_id='$user_id'");
		$nhp=max(1,$uhp['HP']-$proc*$uhp['HP_MAX']);
		myquery("UPDATE game_users set HP=".$nhp." WHERE user_id='$user_id'");
	}
	if($mp>0)
	{	
		$ump=myquery("SELECT MP,MP_MAX FROM game_users WHERE user_id='$user_id'");
		$nmp=max(1,$ump['MP']-$proc*$ump['MP_MAX']);
		myquery("UPDATE game_users set MP=".$nmp." WHERE user_id='$user_id'");
	}
	if($sp>0)
	{	
		$usp=myquery("SELECT STM,STM_MAX FROM game_users WHERE user_id='$user_id'");
		$nsp=max(1,$usp['STM']-$proc*$usp['MAX_STM']);
		myquery("UPDATE game_users set STM=".$nsp." WHERE user_id='$user_id'");
	}
}

switch ($option)
{
	case 'work':
	{
		list($exp)=mysql_fetch_array(myquery("SELECT EXP FROM game_users WHERE user_id='$user_id'"));
		if($exp>=$prisoner['exp_was']+$prisoner['exp_need'])
		{			
			myquery("UPDATE game_users_map SET map_name=666, map_xpos=0, map_ypos=0  WHERE user_id='$user_id'");	
			$act='go_out';	
			setLocation("../act.php?prison_action=$act");
        }		
		else 
		{
			if($prisoner['last_active']==0) $prisoner['last_active']=time()-1;
			if(time()>$prisoner['last_active'])
			{
				$act='oborot_go';
                $add_exp = -2;
                if (isset($_POST['prison_button'.$_SESSION['right_knopka']]))
                {
                    $add_exp = 1;
                }
				myquery("UPDATE game_users SET EXP=EXP+$add_exp WHERE user_id='$user_id'");
				myquery("UPDATE game_prison SET last_active=".time()." WHERE user_id='$user_id'");
				if(time()-$prisoner['last_active']>15 AND time()-$prisoner['last_active']<900)
				{
					$act.='_slow';
					$dt=time()-$prisoner['last_active'];
					if($dt>=800)
					 kick(3,0.5);
					if($dt<800 AND $dt>=500)
					 kick(3,0.3);
					if ($dt<500 AND $dt>=300)
					 kick(2,0.4); 
					if ($dt<300 AND $dt>=100)
					 kick(2,0.2);
					if ($dt<100 AND $dt>=60)
					 kick(1,0.3);
					if ($dt<60 AND $dt>30)
					 kick(1,0.1);				 
				}
				setLocation("../act.php?prison_action=$act");
			}
			else setLocation("../act.php?prison_action=oborot_no");
		}
		break;
	}
	case 'exit':
	{
		list($exp)=mysql_fetch_array(myquery("SELECT EXP FROM game_users WHERE user_id='$user_id'"));
		if($exp>=$prisoner['exp_was']+$prisoner['exp_need'])
		{			
			$return_exp=myquery("UPDATE game_users SET EXP=".$prisoner['exp_was']." where user_id='$user_id'");
            $sel = myquery("SELECT map_from FROM game_prison WHERE user_id='$user_id'");
            if ($sel!=false AND mysql_num_rows($sel)>0)
            {
                list($idmap)=mysql_fetch_array($sel);
            }
            else
            {
                $idmap = mysql_result(myquery("SELECT id FROM game_maps WHERE name LIKE '%Средиземье%'"),0,0);
                if ($char['clevel']<5)
                {
                    $idmap = mysql_result(myquery("SELECT id FROM game_maps WHERE name LIKE '%Гильдия новичков%'"),0,0);
                }
            }
			$go=myquery("UPDATE game_users_map SET map_name=$idmap, map_xpos=0, map_ypos=0 where user_id='$user_id'");
            $del=myquery("delete from game_prison where user_id='$user_id'");    
			setLocation("../act.php?prison_action=done");			
		}
		else
			setLocation("../act.php?prison_action=cant_exit");
		break;
	}
	case 'run':
	{
		myquery("UPDATE game_users set HP=1 WHERE user_id='$user_id'");
		myquery("UPDATE game_users set MP=0 WHERE user_id='$user_id'");
		myquery("UPDATE game_users set STM=0 WHERE user_id='$user_id'");
		$new_exp=$prisoner['exp_need']+ceil($prisoner['exp_need']*0.15);
		myquery("UPDATE game_prison set exp_need=".$new_exp." WHERE user_id='$user_id'");		
		setLocation("../act.php?prison_action=run");
		break;
	}
}

if (function_exists("save_debug")) save_debug(); 

?>