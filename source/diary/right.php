<?php
if (function_exists("start_debug")) start_debug(); 

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
	setLocation('index.php');
}
else
{     
	echo '<table cellspacing=0 cellpadding=0 border=0 width="250" height=100%>';
	if (isset($user))
	{
		$elf=myquery("select * from blog_users where user_id='$user'");
		if (mysql_num_rows($elf)!=0)
		{
			$prof=mysql_fetch_array($elf);
			if ($prof['linfo']!='0')
			{
				echo'
					<tr>
						<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
						<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
						<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
					</tr>
					<tr>
						<td width=16 background="'.$img.'/img/menu_g_fd.gif" height=6 width=16></td>
						<td background="'.$img.'/img/menu_fd.gif">
							<div style="color:red;font-weight:900;padding:4px;">Дневник: '.$prof['name'].'</div>
						</td>
						<td width=16 background="'.$img.'/img/menu_d_fd.gif" height=6 width=16></td>
					</tr>
					<tr>
						<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
						<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
						<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
					</tr>

					<tr>
						<td width="16" background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
						<td align="center">';
							if ($prof['viewimg']!='') { echo'<br><img src='.$img.'/photo/'.$prof['viewimg'].'><br>'; }

							if ($prof['rating']!='0') 
							{
								$sel=myquery("select * from blog_rating where rate_id='".$prof['user_id']."'");
								$n=mysql_num_rows($sel);
								$col = 0;
								while($c=mysql_fetch_array($sel))
								{
									$col=$col+$c['rate'];
								}
								if ($n!=0)
								{
									$col=$col/$n;
									$col = round($col, 3); 
								}
								else
									$col = 0;

								echo'<br>Рейтинг: '.$col.' (Голосов: '.$n.')<br>';

								if ($user_id>0)
								{
									$prov4=myquery("select * from blog_rating where user_id='".$char['user_id']."' and rate_id='$user'");
									if (mysql_num_rows($prov4)=='0' and $user!=$char['user_id'])
									{
										if (!isset($see))
										{
											echo'
											<form action="" method="post">
												<input type="radio" name="r" value=1>1
												<input type="radio" name="r" value=2>2
												<input type="radio" name="r" value=3>3
												<input type="radio" name="r" value=4>4
												<input type="radio" name="r" value=5>5<br>
												<input type="submit" value="Проголосовать">
												<input name="see" type="hidden" value="">
											</form>';
										}
										else
										{
											if (isset($user) AND isset($_POST['r']))
											{
												myquery("insert into blog_rating (user_id, rate_id, rate) values('".$char['user_id']."','$user','".$_POST['r']."')");
											}
										}
									}
								}
							}

							if ($prof['friends']!='0') 
							{
								echo'<br><span style="font-weight:900;color:white;">Друзья:</span> <br />';
								$fr=myquery("select blog_friends.*,game_users.name AS friend_name1,game_users_archive.name AS friend_name2 from blog_friends left join (game_users) on (game_users.user_id=blog_friends.friend_id) left join (game_users_archive) on (game_users_archive.user_id=blog_friends.friend_id) WHERE blog_friends.user_id='".$prof['user_id']."'");
								if (mysql_num_rows($fr)!='0')
								{
									while($ff=mysql_fetch_array($fr))
									{
                                        $name = ($ff['friend_name1']==NULL) ? $ff['friend_name2'] : $ff['friend_name1'];
										echo'<a href="?option=user&user='.$ff['friend_id'].'">'.$name.'</a>, ';
									}
								}
								else
								{
									echo'нет.';
								}

								echo'<br><span style="font-weight:900;color:white;">Я в друзьях: </span><br />';
								$selme = myquery("SELECT blog_friends.*,blog_users.name AS user_name from blog_friends,blog_users where blog_friends.user_id=blog_users.user_id AND blog_friends.friend_id=".$prof['user_id']."");
								if (mysql_num_rows($selme)!='0') 
								{
									while($ff=mysql_fetch_array($selme))
									{
										echo'<a href="?option=user&user='.$ff['user_id'].'">'.$ff['user_name'].'</a>, ';
									}
								}
								else
								{
									echo'нет.';
								}
							}

						echo'</td>
						<td width="16" background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
					</tr>';

				}

				if ($prof['viewcomm']!='0') 
				{
					echo'
					<tr>
						<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
						<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
						<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
					</tr>
					<tr>
						<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
						<td background="'.$img.'/img/menu_fd.gif"><div style="color:white;font-weight:900;padding:4px;">Последние комментарии:</div></td>
						<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
					</tr>
					<tr>
						<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
						<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width=127></td>
						<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
					</tr>
					<tr>
						<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
						<td><p><br />';
						if (isset($user) AND $user>0)
						{
							$sel=myquery("select blog_comm.*,blog_users.name AS user_name,blog_post.nazv from blog_comm,blog_post,blog_users where blog_users.user_id=blog_comm.user_id AND blog_comm.post_id=blog_post.post_id AND blog_post.user_id='$user' order by blog_comm.comm_id desc limit 5");
							if ($sel!=false AND mysql_num_rows($sel)>0)
							{
								while($comme=mysql_fetch_array($sel))
								{
									$rest = substr($comme['nazv'], 0, 10);
									$rest.='...';
									echo '&nbsp;'.$comme['user_name'].' (<a href="?option=comment&comm='.$comme['post_id'].'&page=n&user='.$user.'#comm'.$comme['comm_id'].'">'.$rest.'</a>)<br>';
								}
							}
						}
						echo'<br /></p>
						</td>
						<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
					</tr>';
			}
		}
	}

	if ($user_id>0)
	{
		echo'
		<tr>
			<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
			<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
		</tr>
		<tr>
			<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td background="'.$img.'/img/menu_fd.gif"><div style="color:white;font-weight:900;padding:4px;">Добро пожаловать!</div>
			</td><td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr>
			<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
			<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width=127></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
		</tr>
		<tr>
			<td width="16" background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td>
				<br>
				&nbsp;&nbsp;<a href="?">· На главную</a><br>
				&nbsp;&nbsp;<a href="?option=user&user='.$char['user_id'].'">· Мой дневник</a><br>
				&nbsp;&nbsp;<a href="?option=love&user='.$char['user_id'].'">· Избранные дневники</a><br>
				&nbsp;&nbsp;<a href="?option=setup&user='.$char['user_id'].'">· Настройки дневника</a><br>&nbsp;<br>
			</td>
			<td width="16" background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>';
	}

	echo'
		<tr>
			<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
			<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
		</tr>
		<tr>
			<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td background="'.$img.'/img/menu_fd.gif"><div style="color:white;font-weight:900;padding:4px;"><!--Поиск в дневниках:-->&nbsp;</div></td>
			<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr>
			<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
			<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
		</tr>
		<tr>
			<td width="16" background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td> 
				<br>
<!--				<form action="?option=search" method="post">
				<center><b>Искать:</b><br></center>
				&nbsp;&nbsp;<input type="radio" name="opt" value=1>в записях<br>
				&nbsp;&nbsp;<input type="radio" name="opt" value=2>комментариях<br>
				&nbsp;&nbsp;<input type="radio" name="opt" value=3>дневник по нику<br>
				&nbsp;&nbsp;<input name="search" type="text" value="" size="20" maxlength="100"> <input name="submit" type="submit" value="Найти">
				</form>  -->
				&nbsp;&nbsp;<a href="?option=rules">Правила дневников</a><br>
				&nbsp;&nbsp;<a href="?option=random">Случайный дневник</a><br><br>
			</td>
			<td width="16" background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr>
			<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
			<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
		</tr>
		<tr>
			<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td background="'.$img.'/img/menu_fd.gif"><div style="color:white;font-weight:900;padding:4px;">Статистика:</div></td>
			<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr>
			<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
			<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
		</tr>
		<tr>
			<td width="16" background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td> 
				<br>';
				$n1 = mysql_result(myquery("SELECT COUNT( DISTINCT user_id ) FROM blog_post"),0,0);
				$n2 = mysql_result(myquery("SELECT COUNT(*) FROM blog_post"),0,0);
				$n3 = mysql_result(myquery("SELECT COUNT(*) FROM blog_comm"),0,0);
				echo'
				&nbsp;&nbsp;Дневников: '.$n1.'<br>
				&nbsp;&nbsp;Записей: '.$n2.'<br>
				&nbsp;&nbsp;Комментариев: '.$n3.'
			</td>
			<td width="16" background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr height="100%">
			<td width="16" background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td>&nbsp;</td>
			<td width="16" background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
	</table>';
}

if (function_exists("save_debug")) save_debug(); 

?>