<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['users'] >= 1)
{
    if(!isset($accept) and !isset($delete))
    {
        echo "<table border=0 cellspacing=3 cellpadding=3 align=left>";
        echo "<tr bgcolor=#333333><td>user_name</td><td>user_pass</td><td>name</td><td>email</td><td>race</td><td>uid</td><td>validate</td><td>rego_time</td><td></td></tr>";
        $qw=myquery("SELECT * FROM game_users_reg order BY rego_time ASC");
        while($ar=mysql_fetch_array($qw))
        {
            if ($ar['uid']>0)
            {
                $name_p = mysql_result(myquery("(SELECT name FROM game_users WHERE user_id=".$ar['uid'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$ar['uid'].")"),0,0);
            }
            else
            {
                $name_p = '';
            }
            echo'<tr>
			<td>'.$ar['user_name'].'</td>
            <td>'.$ar['user_pass'].'</td>
            <td>'.$ar['name'].'</td>
            <td>'.$ar['email'].'</td>
            <td>'.$ar['race'].'</td>
            <td>'.$name_p.'</td>
            <td>'.$ar['validate'].'</td>
            <td>'.date("d.m.y H:i",$ar['rego_time']).'</td>
            <td><a href=admin.php?opt=main&option=activate&accept='.$ar['id'].'>Активировать</a>   |   <a href=admin.php?opt=main&option=activate&delete='.$ar['id'].'>Удалить</a></td>
            </tr>';
        }
        echo'</table>';
    }

    if(isset($accept))
    {
        echo'Регистрация активирована';
		$result = myquery("SELECT user_name, user_pass, name, email, race, STATUS, gorod, hobbi, info, uid, validate, rego_time, dr_date, dr_month, dr_year, sex FROM game_users_reg WHERE id=$accept LIMIT 1");
		list($user_name, $user_pass, $name, $email, $race, $STATUS, $gorod, $hobbi, $info, $uid, $validate, $rego_time, $dr_date, $dr_month, $dr_year, $sex) = mysql_fetch_row($result);
        
		$result=myquery("select * from game_har where name='$race' and disable=0");
		if (mysql_num_rows($result) == 0)
		{
			echo'Ошибка активации [Неправильная Раса]';
		}
		else
		{
			$row=mysql_fetch_array($result);
			$hp1=$row["hp"];
			$hp_max1=$row["hp_max"];
			$mp1=$row["mp"];
			$mp_max1=$row["mp_max"];
			$stm1=$row["stm"];
			$stm_max1=$row["stm_max"];
			$exp1=$row["exp"];

			if ($uid!='')
			{
				$uid=(int)$uid;
				$u=myquery("select name from game_users where user_id='$uid'");
				if (!mysql_num_rows($u))
				{
					$u=myquery("select name from game_users_archive where user_id='$uid'");
				}
				$host_p = mysql_result(myquery("SELECT host FROM game_users_active WHERE user_id='$uid'"),0,0);
				list($name_p)=mysql_fetch_array($u);
				$user_host_p = HostIdentify();
				if (isset($uid) and ($user_host_p<>$host_p))
				{
					$gp1='150';
					$up=myquery("update game_users SET GP=GP+50,CW=CW+'".(50*money_weight)."' where user_id='$uid'");
					$up=myquery("update game_users_archive SET GP=GP+50,CW=CW+'".(50*money_weight)."' where user_id='$uid'");
				}
				else
				{
					$gp1=$row["gp"];
				}
			}
			else
			{
				$gp1=$row["gp"];
			}

			$str1=$row["str"];
			$ntl1=$row["ntl"];
			$pie1=$row["pie"];
			$vit1=$row["vit"];
			$dex1=$row["dex"];
			$spd1=$row["spd"];
			//$lucky1=$row["lucky"];
			//$vospr1=$row["vospr"];
			//$magic_res1=$row["magic_res"];
			$avatar=$row["race"];

			$start_map_name=$row["map_name"];
			$start_map_x=$row["map_x"];
			$start_map_y=$row["map_y"];

			$avatar = $avatar.'_'.$sex.'.gif';

			$result  = myquery("
			INSERT game_users SET
			user_name='$user_name',
			user_pass='" . md5($user_pass) . "',
			name='$name',
			HP='$hp1',
			HP_MAX='$hp_max1',
			MP='$mp1',
			MP_MAX='$mp_max1',
			STM='$stm1',
			STM_MAX='$stm_max1',
			EXP='$exp1',
			GP='$gp1',
			STR='$str1',
			NTL='$ntl1',
			PIE='$pie1',
			VIT='$vit1',
			DEX='$dex1',
			SPD='$spd1',
			STR_MAX='$str1',
			NTL_MAX='$ntl1',
			PIE_MAX='$pie1',
			VIT_MAX='$vit1',
			DEX_MAX='$dex1',
			SPD_MAX='$spd1',
			CW='".($gp1*money_weight)."',
			CC=40,
			race='".$row['id']."',
			avatar='$avatar'
			") or die('Database Error: ' . mysql_error() . '<br>');
			//lucky='$lucky1',
			//lucky_max='$lucky1',
			//vospr='$vospr1',
			//vospr_max='$vospr1',
			//magic_res='$magic_res1',
			//magic_res_max='$magic_res1',

			$result = myquery("DELETE FROM game_users_reg WHERE user_name = '$user_name'");
			list($uid) = mysql_fetch_array(myquery("SELECT user_id FROM game_users WHERE user_name='$user_name'"));

			$result  = myquery("
			INSERT game_users_map SET
			user_id='$uid',
			map_name='$start_map_name',
			map_xpos='$start_map_x',
			map_ypos='$start_map_y'
			") or die('Database Error: ' . mysql_error() . '<br>');

			$result  = myquery("
			INSERT game_users_data SET
			user_id='$uid',
			email='$email',
			status='$STATUS',
			gorod='$gorod',
			hobbi='$hobbi',
			info='$info',
			dr_date='$dr_date',
			dr_month='$dr_month',
			dr_year='$dr_year',
			sex='$sex',
			rego_time='$rego_time'
			") or die('Database Error: ' . mysql_error() . '<br>');
			myquery("INSERT INTO game_users_active (user_id,host,last_active) VALUES ('$uid','".HostIdentify()."','')");
			myquery("INSERT INTO game_users_active (user_id,host_more) VALUES ('$uid','".HostIdentifyMore()."')");
            myquery("INSERT INTO game_chat_option (user_id,ref,size,frame) VALUES ('$uid','1','10','220')");

			$da = getdate();
			$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
			 VALUES (
			 '".$char['name']."',
		     'Активировал регистрацию игрока: <b>".$name."</b>',
			 '".time()."',
			 '".$da['mday']."',
			 '".$da['mon']."',
			 '".$da['year']."')")
				 or die(mysql_error());
		}
		echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=activate">';
	}


	if(isset($delete))
	{
		echo'Регистрация удалена';
		$nazv = mysql_result(myquery("SELECT name FROM game_users_reg where id='$delete'"),0,0);
		$da = getdate();
		$log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) 
		 VALUES (
		 '".$char['name']."',
		 'Удалил регистрацию игрока: <b>".$nazv."</b>',
		 '".time()."',
		 '".$da['mday']."',
		 '".$da['mon']."',
		 '".$da['year']."')")
			 or die(mysql_error());
        $up=myquery("delete from game_users_reg where id='$delete'");
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=activate">';
    }

}

if (function_exists("save_debug")) save_debug(); 

?>