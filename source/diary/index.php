<?php
//ob_start('ob_gzhandler',9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
$start_time = StartTiming();


if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '7');
}
else
{
	die();
}

if (function_exists("start_debug")) start_debug(); 

if (isset($_COOKIE['rpgsu_sess']) AND isset($_COOKIE['rpgsu_login']) AND isset($_COOKIE['rpgsu_pass']))
{
	require('../inc/lib_session.inc.php');
	$sel = @mysql_result(@myquery("SELECT COUNT(*) FROM game_prison WHERE user_id='$user_id'"),0,0);
	if ($sel>0) die('Ты на каторге!');
	set_delay_reason_id($user_id,6);
	$select=myquery("select * from game_admins where user_id='$user_id'");
	if (mysql_num_rows($select))
	{
		$adm='adm';
		$clan='clan';
	}
	else
	{
		if ($char['clan_id']!='0') $clan='clan';
	}
	$result=myquery("SELECT * FROM game_admins WHERE user_id=".$user_id." LIMIT 1");
	if (mysql_num_rows($result) == 0)
	{
		$adm=mysql_fetch_array($result);
		if($adm['forum']>='1') $admin=1;
	}
}
else
{
	$user_id = 0;
	$char['name'] = 'Гость';
}

if (!isset($option)) $option='';
if ($option=='random')
{
	$query = myquery("SELECT DISTINCT user_id FROM blog_post");
	$all = mysql_num_rows($query);
	if ($all>0)
	{
		$r = mt_rand(0,$all-1);
		mysql_data_seek($query,$r);
		$ar = mysql_fetch_assoc($query);
		setLocation("index.php?option=user&user=".$ar['user_id']."");
	}
	else
	{
		setLocation("index.php");
	}
}

include('header.inc.php');
include('func.php');

if ($user_id>0)
{
	include ("../lib/menu.php");
}

//верхний топ
echo'
<table width="100%" border="0" cellpadding="0" cellspacing="0" background="'.$img.'/img/top-back.gif">
	<tr height="100">
	<td width="140"><img src="'.$img.'/img/top-left.gif" width="140" height="100"></td>
	<td width="25%" valign=top><br /><br /><b>Привет, <font color=#ff0000>'.$char['name'].'</font>!</b></td>
	<td width="447"><img src="'.$img.'/img/top-midleft.gif" width="79" height="100"><img src="'.$img.'/img/logo2.jpg" width="285" height="100"><img src="'.$img.'/img/top-midright.gif" width="83" height="100"></td>
	<td valign=top>&nbsp;</td>
	<td width="139"><img src="'.$img.'/img/top-right.gif" width="139" height="100"></td>
  </tr>
</table>';




echo'
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td style="width:250px;" valign="top" height="100%">';
		include('right.php');
	echo'
	</td>
	<td valign=top>';

function detect_rights($blog_user,&$status)
{
	global $user_id;
	$blog_user = (int)$blog_user;
	$sel = myquery("SELECT * FROM blog_users WHERE user_id='$blog_user'");
	if ($sel==false OR mysql_num_rows($sel)==0) return 'Дневника не существует!<br />';
	$dd = mysql_fetch_array($sel);
	$sel_close = myquery("SELECT * FROM blog_closed WHERE user_id=$blog_user");
	$status = 0;
	if ($user_id>0)
	{
		if ($user_id == 1) return 1;
		
		$sel_close_user = myquery("SELECT * FROM blog_closed WHERE user_id=$blog_user AND close_id=$user_id"); 
		if (mysql_num_rows($sel_close_user))
		{
			$status = 2;
			return 'Этот дневник закрыт!<br>';
		}
		
		if ($dd['status']==2)
		{
//			$prov2=myquery("select * from blog_friends where user_id='".$dd['user_id']."' and friend_id='$user_id'");
            $prov2=myquery("select * from blog_friends where user_id='$blog_user' and friend_id='$user_id'");
			if (mysql_num_rows($prov2)=='0' && $blog_user != $user_id)
			{
				$status = 2;
				return 'Этот дневник открыт только для друзей!<br>';
			}
		}
		if ($dd['status']==3 and $dd['user_id']!=$user_id)
		{
			$status = 3;
			return 'Этот дневник закрыт для всех!<br>';
		}
	}
	else
	{
		if (mysql_num_rows($sel_close)>0)
		{
			$status = 1;
			return 'Этот дневник закрыт для гостей!<br> ';
		}
		if ($dd['status']==3)
		{
			$status = 3;
			return 'Этот дневник закрыт для всех!<br> ';
		}
		if ($dd['status']==2)
		{
			$status = 2;
			return 'Этот дневник открыт только для друзей!<br> ';
		}
		if ($dd['status']==1)
		{
			$status = 1;
			return 'Этот дневник закрыт для гостей!<br> ';
		}
	}
	return 1;
}
	
switch($option)
{
	default:
	//Дневники

	if (!isset($page)) $page=1;
	if ($page=='n')	$page=99;
	$page=(int)$page;
	if ($page<1) $page=1;
	$line=25;
	$pg=myquery("SELECT COUNT(DISTINCT user_id) FROM blog_users");
	$allpage=ceil(mysql_result($pg,0,0)/$line);
	if ($page>$allpage) $page=$allpage;

	echo'
	<table cellspacing=0 cellpadding=0 width="100%" border=0>
		<tr>
			<td colspan="3" background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
		</tr>
		<tr>  
			 <td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			 <td background="'.$img.'/img/menu_fd.gif">
				<table cellspacing=0 cellpadding=4 width="100%" border=0>
					<tr>
						<td align="center">Имя</td>
						<td width=70>Записи</td>
						<td width=140>Последняя запись</td>
						<td width=70>Комментарии</td>
						<td width=140>Последний комментарий</td>
						<td width=70>Просмотров</td>
					</tr>
				</table>
			</td> 
			<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr>  
			<td colspan="3" background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
		</tr>
	</table>';

	echo'
	<table cellspacing=0 cellpadding=0 width="100%" background="'.$img.'/img/story-content-bg2.gif" border=0>
		<TR>
			<TD width=20 background="'.$img.'/img/center_hg.gif"><IMG height=20 src="'.$img.'/img/pixel.gif" width=20></TD>
			<TD background="'.$img.'/img/center_h_fd.gif"><IMG height=20 src="'.$img.'/img/pixel.gif" width=20></TD>
			<TD width=20 background="'.$img.'/img/center_hd.gif"><IMG height=20 src="'.$img.'/img/pixel.gif" width=20></TD>
		</TR>
		<TR>
			<TD width=20 background="'.$img.'/img/center_g_fd.gif"><IMG height=20 src="'.$img.'/img/pixel.gif" width=20></TD>
			<TD>
				<table cellspacing=1 cellpadding=1 width="100%">';
				if ($page<1) $page=1;
				$sel=myquery("SELECT DISTINCT blog_post.user_id,blog_post.post_id, blog_users.name, blog_users.comments, blog_users.zap, blog_users.prosm, blog_users.status, blog_users.lastcomm, MAX(blog_post.post_time) as post_time
FROM blog_post, blog_users
WHERE blog_users.user_id = blog_post.user_id
GROUP BY blog_post.user_id
ORDER BY post_time DESC LIMIT ".(($page-1)*$line).", $line");
				while($d=mysql_fetch_array($sel))
				{
					$dlastadd = date('d.m.Y : H:i:s', $d['post_time']);
					echo'
					<tr>
						<td width=20>';
						$status = 0;
						$rights = detect_rights($d['user_id'],$status);
						if ($status!=0) echo'<img src="'.$img.'/img/'.$status.'.gif" >';
						else echo '&nbsp;';
						echo'</td>';
						if ($rights==1)
						{
							echo '<td align="left">&nbsp;&nbsp;&nbsp;<a href="?option=user&user='.$d['user_id'].'">'.$d['name'].'</a></td>';
						}
						else
						{
							echo '<td align="left">&nbsp;&nbsp;&nbsp;'.$d['name'].'</td>';
						}
						echo '
						<td width=70>'.$d['zap'].'</td>';
						if ($rights==1)
						{
							echo '<td width=150><a href="?option=comment&user='.$d['user_id'].'&comm='.$d['post_id'].'">'.$dlastadd.'</a></td>
';
						}
						else
						{
							echo '<td width=150>'.$dlastadd.'</td>
';
						}
						echo '
						<td width=75>'.$d['comments'].'</td>
						<td width=145>'.$d['lastcomm'].'</td>
						<td width=70>'.$d['prosm'].'</td>
					</tr>';
				}
				echo'
				</table>
			</TD>
			<TD width=20 background="'.$img.'/img/center_d_fd.gif"><IMG height=20 src="'.$img.'/img/pixel.gif" width=20></TD>
		</TR>
		<TR>
			<TD width=20><IMG height=20 src="'.$img.'/img/center_bg.gif" width=20></TD>
			<TD background="'.$img.'/img/center_b_fd.gif">&nbsp;</TD>
			<TD width=20><IMG height=20 src="'.$img.'/img/center_b_d.gif" width=20></TD>
		</TR>
	</TABLE>';
	
	echo 'Страница: ';
	show_page($page,$allpage,"?");
	echo'&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.$img.'/img/1.gif"> Закрыт для гостей <img src="'.$img.'/img/2.gif"> Открыт только для друзей <img src="'.$img.'/img/3.gif"> Закрыт всем';
	
	break;

	case 'user':
	{
		if (!isset($user)) die("Ошибка");
		$user=(int)$user;

		$prov=myquery("select * from blog_users where user_id='$user'");
		if (mysql_num_rows($prov)!=0)
		{
			$dd=mysql_fetch_array($prov);

			if (!isset($page)) $page=1;
			if ($page=='n')	$page=99;
			$page=(int)$page;
			if ($page<1) $page=1;
			$line=$dd['col_z'];
			$pg=myquery("SELECT COUNT(*) FROM blog_post where user_id='".$user."'");
			if ($line<1) $line=1;
			$allpage=ceil(mysql_result($pg,0,0)/$line);
			if ($page>$allpage) $page=$allpage;

			$str_begin='<br><br><font color=red><b>';
			$str_end='</b></font><br /><br /><a href="index.php">Выйти на главную</a>';
			$status = 0;
			$rights = detect_rights($user,$status);
			if ($rights!=1)
			{
				echo ''.$str_begin.$rights.$str_end.'';
			}
			else
			{
				if ($user_id!=1 AND $user_id!=0)
					myquery("update blog_users set prosm=prosm+1 where user_id='".$user."'");

				echo'
				<table cellspacing=0 cellpadding=0 width="100%" border=0>
					<tr>
						<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
						<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
						<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
					</tr>
					<tr>
						<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
						<td background="'.$img.'/img/menu_fd.gif">
							<table cellspacing=0 cellpadding=4 width="100%" border=0>
								<tr>
									<td><font class=block-title color=#363636><b><div align=left>&nbsp;&nbsp;&nbsp;<font color=red size=2><b>Дневник '.$dd['name'].' ('.stripslashes($dd['nazv']).')</b></font></div></td>
									<td><div align=right>[<a href="index.php">На главную</a>] [<a href="?option=random">Случайный дневник</a>]';  
									if ($user_id>0 AND $user==$user_id) 
									{
										echo' [<a href="?option=new">Создать новую запись</a>]'; 
									}
									echo'</div></b></font></td>
								</tr>
							</table>
						</td>
						<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
					</tr>
					<tr>
						<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
						<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width=127></td>
						<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
					</tr>
				</table>';

				if ($page<1) $page=1;
				$sel=myquery("SELECT blog_post.*,count(blog_comm.comm_id) as comments FROM blog_post LEFT JOIN blog_comm ON (blog_post.post_id=blog_comm.post_id) WHERE blog_post.user_id='$user' group by blog_post.post_id order by blog_post.post_id desc LIMIT ".(($page-1)*$line).", $line");
				if ($sel!=false)
				{
					while($p=mysql_fetch_array($sel))
					{
						if ($p['onlyfriends']=='1')
						{
							$is_show = 0;
							if ($user==$user_id) $is_show = 1;
							$prov2=myquery("select * from blog_friends where user_id='$user' and friend_id='$user_id'");
							if (mysql_num_rows($prov2)) $is_show = 1;
							//$is_admin=mysql_result(myquery("select count(*) from game_admins where user_id='$user_id' and forum>='1'"),0,0);
							//if ($is_admin>0) $is_show = 1;
							if ($is_show==0)
                              continue;
						}
						echo'<div style="padding-top:15px;background-image:url('.$img.'/img/story-bg.gif);width:100%;height:38px;color:#FFFFD7;font-weight:900;font-size:9pt;">&nbsp;&nbsp;&nbsp;'.date("H:i:s   d-m-Y",$p['post_time']).' - '.stripslashes($p['nazv']).'</div>';
						
						$p['post'] = stripslashes(nl2br(convert_in_tags($p['post'], '?user='.$p['user_id'].'&option=comment&comm='.$p['post_id'].'&cut')));

						echo '<p align="left" style="margin:10px;font-size:9pt;">'.$p['post'].'</p>';

						if ($p['nastroy']!='') echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Настроение:</b> '.stripslashes($p['nastroy']).'';
						if ($p['music']!='') echo'<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Слушаю:</b> '.stripslashes($p['music']).'';

						if ($p['nocomm']=='1') echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=red>Запрещены комментарии</font></b>';
						if ($p['onlyfriends']=='1') echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=red>Виден только друзьям</font></b>';

						echo'<div style="text-align:right;padding-top:24px;background-image:url('.$img.'/img/story-bottom-bg.gif);width:100%;height:28px;color:#000000;font-weight:900;">';
						if ($user_id>0 AND ($p['user_id']==$user_id or (isset($admin) AND $admin=='1')))
						{
							echo'<a href="?option=edit&edit='.$p['post_id'].'&user='.$p['user_id'].'">Исправить</a> &#149; <a href="?option=del&del='.$p['post_id'].'">Удалить</a> &#149; <a href="?user='.$p['user_id'].'&option=comment&comm='.$p['post_id'].'" title="Нажмите сюда, чтобы добавить комментарий">Комментариев: ['.$p['comments'].']</a>';
						}
						else
						{
							echo'<a href="?option=comment&comm='.$p['post_id'].'&user=" title="Нажмите сюда, чтобы добавить комментарий">Комментариев: ['.$p['comments'].']</a>';
						}
						echo'&nbsp;&nbsp;&nbsp;&nbsp;</div>';
					}
				}

				if ($allpage>1)
				{
					$href = '?option=user&user='.$user.'&';
					echo'Страница: ';
					show_page($page,$allpage,$href);
				}
			}
		}
		else
		{
			echo 'Дневника не существует<br /><br />Для создания дневника зайди в <a href="?option=setup&user='.$user.'">"Настройки дневника"</a> и укажи там все параметры настроек!';
		}
	}
	break;


	case 'new':
	{           
		if ($user_id>0)
		{
			if (isset($text))
			{
				if ($text<>'')
				{
					$text   =   mysql_real_escape_string(htmlspecialchars($text));
					$top    =   mysql_real_escape_string(htmlspecialchars($top));

					if (isset($closed)) $closed='1';
					else $closed='0';

					if (isset($no_comment)) $no_comment='1';
					else $no_comment='0';

					$up=myquery("update blog_users set zap=zap+1, lastadd='".time()."' where user_id='".$char['user_id']."'");
					$ins=myquery("insert into blog_post (user_id, nazv, post, post_time, comments, onlyfriends, nocomm, nastroy, music)
					values
					('".$user_id."','$top','$text','".time()."','','$closed','$no_comment','".mysql_real_escape_string(htmlspecialchars($nastroy))."','".mysql_real_escape_string(htmlspecialchars($music))."')");
					setLocation("?option=user&user=".$char['user_id']."");
				}
				else $error='';
			}

			if (!isset($text) or isset($error))
			{        
				echo'<center>';
				pokazat_formu_otveta('Создать новую запись в дневнике',1,'');
			}
		}
	}
	break;

	case 'del':
	{
		if ($user_id>0)
		{
			$id=(int)$del;
			$is_admin=mysql_result(myquery("select count(*) from game_admins where user_id='$user_id' and forum>='1'"),0,0);
			$is_blogger=mysql_result(myquery("select count(*) from blog_post where post_id='$id' and user_id='$user_id'"),0,0);
			if ($is_admin>0 or $is_blogger>0)
			{
				$num=mysql_result(myquery("select count(*) from blog_comm where post_id='id'"),0,0);
				$up=myquery("update blog_users set comments=comments-".$num.",zap=zap-1 where user_id=$user_id");
				myquery("delete from blog_comm where post_id='$id'");
				myquery("delete from blog_post where post_id='$id'");
				setLocation("?option=user&user=".$char['user_id']."");
			}
		}
	}
	break;


	case 'del_comm':
	{
		if ($user_id>0)
		{
			$id=(int)$del;
			$is_blogger = 0;
			$comm = myquery("select post_id,user_id from blog_comm where comm_id='$id'");
			if (mysql_num_rows($comm)>0)
			{
				list($post_id,$autor_id) = mysql_fetch_array($comm);
				list($blogger_id) = mysql_fetch_array(myquery("SELECT user_id FROM blog_post WHERE post_id='$post_id'")); 
				$is_admin=mysql_result(myquery("select count(*) from game_admins where user_id='$user_id' and forum>='1'"),0,0);
				if ($autor_id==$user_id or $is_admin>0 or $blogger_id==$user_id)
				{
					myquery("update blog_users set comments=comments-1 where user_id='$blogger_id'");
					myquery("update blog_post set comments=comments-1 where post_id='$post_id'");
					$del=myquery("delete from blog_comm where comm_id='$id'");
					setLocation("?option=user&user=".$char['user_id']."");
				}
			}
		}
	}
	break;

	case 'edit':
	{
		if ($user_id>0)
		{
			$edit=(int)$edit;
		 
			if (isset($text))
			{
				if ($text<>'')
				{
					$text=mysql_real_escape_string(htmlspecialchars($text));

					if (isset($closed)) $closed='1';
					else $closed='0';

					if (isset($no_comment)) $no_comment='1';
					else $no_comment='0';

					$ins=myquery("update blog_post set post='$text', onlyfriends='$closed', nocomm='$no_comment', nastroy='".mysql_real_escape_string(htmlspecialchars($nastroy))."', music='".mysql_real_escape_string(htmlspecialchars($music))."' where post_id='$edit' and user_id=$user_id");
					setLocation("?option=user&user=".$user_id."");
				}
			}
			else
			{        
				echo'<center>';
				$sel=myquery("select * from blog_post where post_id='$edit' and user_id='".$char['user_id']."'");
				if (mysql_num_rows($sel))
				{
					$edd=mysql_fetch_array($sel);
					$txt=stripslashes($edd['post']);
					pokazat_formu_otveta('Редактировать',2,$txt,stripslashes($edd['nastroy']),stripslashes($edd['music']),$edd['onlyfriends'],$edd['nocomm']);
				}
			}
		}
	}
	break;

	case 'love':
	{
		if ($user_id>0)
		{
			echo'
			<table cellspacing=0 cellpadding=0 width="100%" border=0>
				<tr>
					<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
					<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
					<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
				</tr>
				<tr>
					<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
					<td background="'.$img.'/img/menu_fd.gif">
						<table cellspacing=0 cellpadding=4 width="100%" border=0>
							<tr>
								<td><div align=right>[<a href="index.php">На главную</a>] [<a href="?option=random">Случайный дневник</a>]</div></td>
							</tr>
						</table>
					</td>
					<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
				</tr>
				<tr>
					<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
					<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
					<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
				</tr>
			</table>';

			echo'
			<table width="100%" border="0" cellpadding="0" cellspacing="1">';
			$sel=myquery("select blog_love.*,blog_users.name AS friend_name from blog_love,blog_users where blog_love.friend_id=blog_users.user_id AND blog_love.user_id='".$char['user_id']."'");
			if(mysql_num_rows($sel)!='0')
			{
				while($lov=mysql_fetch_array($sel))
				{
					$status = 0;
					if (detect_rights($lov['friend_id'],$status)!=1) continue;
					$sell=myquery("select * from blog_post where user_id='".$lov['friend_id']."' order by post_id desc limit 1");
					if ($sell!=false AND mysql_num_rows($sell)>0)
					{
						$p=mysql_fetch_array($sell);
						$p['post'] = stripslashes(nl2br(convert_in_tags($p['post'])));
						$rest = '<p align="left" style="margin:10px;font-size:9pt;">'.$p['post'].'</p>';
						list($viewimg) = mysql_fetch_array(myquery("SELECT viewimg FROM blog_users WHERE user_id='".$char['user_id']."'")); 
						echo'
						<tr>
						<td width="16%" height=24 background="'.$img.'/img/comm.jpg"><center><font color=red><b>'.$lov['friend_name'].'<br></td>
						<td width="84%" background="'.$img.'/img/comm.jpg">&nbsp;&nbsp;Написал: <font color=red><b>'.date("H:i:s   d-m-Y",$p['post_time']).'!</font> Тема: '.stripslashes($p['nazv']).'</b><br></td>
						</tr>
						<tr>
						<td width="16%" background="'.$img.'/img/story-content-bg2.gif" valign=top>'; if ($viewimg!='0') echo'<center><br><img src="'.$img.'/photo/'.$p['user_id'].'.gif"><br><br>'; echo'</td>
						<td width="84%" background="'.$img.'/img/story-content-bg2.gif" valign=top>'.$rest.'________________<br><br><a href="?option=user&user='.$p['user_id'].'">[Читать дальше]</a><br />&nbsp;</td>
						</tr>';
					}
				}
			}
			else
			{
				echo'<tr><td align=center><br>Нет избраных дневников<br><br></td></tr>';
			}
			echo' <tr>
			<td width="16%" height=24 background="'.$img.'/img/comm.jpg">&nbsp;</td>
			<td width="84%" background="'.$img.'/img/comm.jpg"></td>
			</tr></table>';
		}
	}
	break;

	case 'comment':
	{
		if (!isset($comm)) {
			break;
		}
		$comm=(int)$comm;

		$prov=myquery("select * from blog_post where post_id='".$comm."'");
		if ($prov==false OR mysql_num_rows($prov)==0) 
		{
			break;
		}
		$dd=mysql_fetch_array($prov);

		echo'
		<table cellspacing=0 cellpadding=0 width="100%" border=0>
			<tr>
				<td width=16><img height=6 src="'.$img.'/img/menu_hg.gif" width=16></td>
				<td background="'.$img.'/img/menu_h_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width="100%"></td>
				<td width=16><img height=6 src="'.$img.'/img/menu_hd.gif" width=16></td>
			</tr>
			<tr>
				<td width=16 background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
				<td background="'.$img.'/img/menu_fd.gif">
					<table cellspacing=0 cellpadding=4 width="100%" border=0>
						<tr>
							<td><div align=right>[<a href="index.php?option=user&user='.$dd['user_id'].'">Выйти в дневник</a>]  [<a href="index.php">На главную</a>] [<a href="?option=random">Случайный дневник</a>]</div></td>
						</tr>
					</table>
				</td>
				<td width=16 background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
			</tr>
			<tr>
				<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
				<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif" width=127></td>
				<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
			</tr>
		</table>';

		$status = 0;
		if (detect_rights($dd['user_id'],$status)!=1) exit;
		
		if ($dd['onlyfriends']=='1')
		{
			$is_show = 0;
			if ($dd['user_id']==$user_id) $is_show = 1;
			$prov2=myquery("select * from blog_friends where user_id='".$dd['user_id']."' and friend_id='$user_id'");
			if (mysql_num_rows($prov2)) $is_show = 1;
			if ($is_show==0) 
			{
				break;
			}
		}
	
		$us=myquery("select * from blog_users where user_id='".$dd['user_id']."'");
		$uss=mysql_fetch_array($us);

		if (!isset($page)) $page=1;
		if ($page=='n')	$page=99;
		$page=(int)$page;
		$line=$uss['col_c'];
		if ($line<1) $line=1;
		$pg=myquery("SELECT COUNT(*) FROM blog_comm where post_id='".$comm."'");
		$allpage=ceil(mysql_result($pg,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;

		//покажем запись из дневника
        if (isset($_GET['cut']))
          $dd['post'] = nl2br(stripslashes(convert_in_tags($dd['post'])));
        else
          $dd['post'] = nl2br(stripslashes(convert_in_tags($dd['post'], $_SERVER["REQUEST_URI"].'&cut')));
          
		echo'
		<table width="100%" border="0" cellpadding="0" cellspacing="1">
			<tr>
				<td width="16%" height=24 background="'.$img.'/img/comm.jpg"><center><font color=red><b>'.$uss['name'].'<br></td>
				<td width="84%" background="'.$img.'/img/comm.jpg">&nbsp;&nbsp;<font color=red><b>&nbsp;&nbsp;&nbsp;'.date("H:i:s   d-m-Y",$dd['post_time']).' - '.stripslashes($dd['nazv']).'</b></font><br></td>
			</tr>
			<tr>
				<td width="16%" background="'.$img.'/img/story-content-bg2.gif" valign=top>'; if ($uss['viewimg']!='0') echo'<center><br><img src="'.$img.'/photo/'.$dd['user_id'].'.gif">'; echo'</td>
				<td width="84%" background="'.$img.'/img/story-content-bg2.gif" valign=top><p align="left" style="margin:10px;font-size:9pt;">'.$dd['post'].'</p>';
				if ($dd['nastroy']!='') echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Настроение:</b> '.stripslashes($dd['nastroy']).'';
				if ($dd['music']!='') echo'<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>Слушаю:</b> '.stripslashes($dd['music']).'';

				if ($dd['nocomm']=='1') echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=red>Запрещены комментарии</font></b>';
				if ($dd['onlyfriends']=='1') echo'<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<b><font color=red>Виден только друзьям</font></b>';

				echo '</td>
			</tr>
			<tr>
				<td colspan=2>&nbsp;</td>
			</tr>
			<tr>
				<td width="16%" height=24 background="'.$img.'/img/comm.jpg">&nbsp;</td>
				<td width="84%" background="'.$img.'/img/comm.jpg"></td>
			</tr>';
		
			if ($user_id>0)
			{
				$up=myquery("update blog_users set prosm=prosm+1 where user_id='".$dd['user_id']."'");
			}

			if ($dd['nocomm']=='1')
			{
				echo'
				<tr>
					<td colspan=2><center><br><br><font color=red><b>Добавление комментариев запрещено<br><a href="index.php?option=user&user='.$dd['user_id'].'">Выйти в дневник</a></b></font></center></td>
				</tr>';
				break;
			}
	   
	$sel=myquery("select blog_comm.*,blog_users.viewimg AS viewimg,blog_users.name AS user_name from blog_comm,blog_users where blog_comm.user_id=blog_users.user_id AND blog_comm.post_id='$comm' order by blog_comm.comm_id asc limit ".(($page-1)*$line).", $line");
	if ($sel!=false)
	{
		while($p=mysql_fetch_array($sel))
		{
			$p['post'] = stripslashes(nl2br(convert_in_tags($p['post'])));

			echo'
			<tr>
				<td width="16%" height=24 background="'.$img.'/img/comm.jpg"><center><font color=red><b><a href="index.php?option=user&user='.$p['user_id'].'">'.$p['user_name'].'</a><br></td>
				<td width="84%" background="'.$img.'/img/comm.jpg">&nbsp;&nbsp;Написал: <font color=red><b>'.date("H:i:s   d-m-Y",$p['comm_time']).'</b></font><br></td>
			</tr>
			<tr>
				<td width="16%" background="'.$img.'/img/story-content-bg2.gif" valign=top>'; if ($p['viewimg']!='0') echo'<center><br><img src="'.$img.'/photo/'.$p['user_id'].'.gif">'; echo'</td>
				<td width="84%" background="'.$img.'/img/story-content-bg2.gif" valign=top><a name="comm'.$p['comm_id'].'">&nbsp;</a><p align="left" style="margin:10px;font-size:8pt;">'.$p['post'].'</p></td>
			</tr>

			<tr>';
			if ($user_id==0)
			{
				echo'<td colspan=2></td>';
			}
			else
			{
				$prov=myquery("select * from blog_post where post_id='$comm' and user_id='".$char['user_id']."'");

				$is_admin=mysql_result(myquery("select count(*) from game_admins where user_id='$user_id' and forum>='1'"),0,0);
				if ($p['user_id']==$user_id or $dd['user_id']==$user_id or $is_admin>0)
				{
					echo'<td colspan=2 align=right><a href="?option=del_comm&del='.$p['comm_id'].'">Удалить</a></td>';
				}
				else
				{
					echo'<td colspan=2></td>';
				}
			}
			echo'</tr>';
		}
	}

	echo' 
		<tr>
			<td width="16%" height=24 background="'.$img.'/img/comm.jpg">&nbsp;</td>
			<td width="84%" background="'.$img.'/img/comm.jpg"></td>
		</tr>
	</table>';

	if ($allpage>1)
	{
		$href = '?option=comment&comm='.$comm.'&user='.$dd['user_id'].'';
		echo'Страница: ';
		show_page($page,$allpage,$href);
	}

	if ($user_id>0)
	{
		if (isset($text))
		{
			if ($text<>'')
			{
				$text=mysql_real_escape_string(htmlspecialchars($text));

				$ins=myquery("insert into blog_comm (post_id, user_id, post, comm_time)
				values
				('".$comm."','".$char['user_id']."','$text','".time()."')");

				$up=myquery("update blog_post set comments=comments+1 where post_id='$comm'");

				$sel=myquery("select user_id from blog_post where post_id='$comm'");
				list($userr)=mysql_fetch_array($sel);

				myquery("update blog_users set comments=comments+1, lastcomm='".date("d.m.y H:i")."' where user_id='$userr'");	
				setLocation("?option=comment&comm=".$comm."");
			}
		}

		if (!isset($text))
		{
			echo'<center>';
			pokazat_formu_otveta('Оставить комментарий', 3,'');
		}
	}
	}
	break;



	case 'setup':

	if ($user_id>0)
	{
		$sel=myquery("select * from blog_users where user_id='".$char['user_id']."'");
		$my=mysql_fetch_array($sel);

		if (!isset($see))
		{
			echo'
			<table border=0 width=100%>
				<tr>
					<td width=50% valign=top>
					<form action="" method="post">&nbsp;&nbsp;<input name=nazv type="text" value="'.stripslashes($my['nazv']).'" size=40> Название твоего дневника<br>
					&nbsp;&nbsp;<input name=col_z type="text" value="'; if($my['col_z']=='') { echo'5';} else { echo $my['col_z']; } echo'" size=2> Количество записей на странице<br>
					&nbsp;&nbsp;<input name=col_c type="text" value="'; if($my['col_c']=='') { echo'10';} else {echo $my['col_c']; } echo'" size=2> Количество комментариев на странице<br>
					&nbsp;&nbsp;<select name=linfo name="info"><option value=1 '; if ($my['linfo']=='1') echo'SELECTED'; echo'>Да</option><option value=0 '; if ($my['linfo']=='0') echo'SELECTED'; echo'>Нет</option></select> Отображать твой профиль<br>
					&nbsp;&nbsp;<select name="rating"><option value=1 '; if ($my['rating']=='1') echo'SELECTED'; echo'>Да</option><option value=0 '; if ($my['rating']=='0') echo'SELECTED'; echo'>Нет</option></select> Отображать твой рейтинг<br>
					&nbsp;&nbsp;<select name="friends"><option value=1 '; if ($my['friends']=='1') echo'SELECTED'; echo'>Да</option><option value=0 '; if ($my['friends']=='0') echo'SELECTED'; echo'>Нет</option></select> Отображать список твоих друзей<br>
					&nbsp;&nbsp;<select name="comm"><option value=1 '; if ($my['viewcomm']=='1') echo'SELECTED'; echo'>Да</option><option value=0 '; if ($my['viewcomm']=='0') echo'SELECTED'; echo'>Нет</option></select> Отображать меню последних комментариев<br><br>
					&nbsp;&nbsp;<select name="status">
					<option value=0 '; if ($my['status']=='0') echo'SELECTED'; echo'>Открыт всем</option>
					<option value=1 '; if ($my['status']=='1') echo'SELECTED'; echo'>Открыт для всех кроме гостей</option>
					<option value=2 '; if ($my['status']=='2') echo'SELECTED'; echo'>Открыт только для друзей</option>
					<option value=3 '; if ($my['status']=='3') echo'SELECTED'; echo'>Закрыт для всех</option>
					</select> Уровень доступа к дневнику<br><br><br>
					&nbsp;&nbsp;<input name="submit" type="submit" value="Сохранить"><input name="see" type="hidden" value="">
					</form>
					</td>
					<td valign=top>';
					if (mysql_num_rows($sel)>0)
					{
						if ($my['viewimg']!='') echo "Ваша Фотография:<br><img src=$img/photo/$my[viewimg]>";
						echo'<br><br><a href="?option=photo&user='.$user_id.'">Закачать фотографию</a><br>';
						echo'<a href="?option=friends&user='.$user_id.'">Список друзей</a><br>';
						echo'<a href="?option=closed&user='.$user_id.'">Список "врагов" (для них дневник всегда закрыт)</a><br>';
						echo'<a href="?option=optlove&user='.$user_id.'">Настройка избранных дневников</a><br>';
					}
					echo'
					</td>
				</tr>
			</table>';
		}
		else
		{
			$provv=myquery("select * from blog_users where user_id='$user_id'");
			if (mysql_num_rows($provv)!='0')
			{
				myquery("update blog_users set nazv='".mysql_real_escape_string(htmlspecialchars($nazv))."', col_z='$col_z', col_c='$col_c', linfo='$linfo', rating='$rating', friends='$friends', viewcomm='$comm', status='$status' where user_id='".$char['user_id']."'");
			}
			else
			{
				$up=myquery("INSERT INTO blog_users (user_id, name, nazv, col_z, col_c, viewimg, linfo, rating, friends, viewcomm, prosm, status, zap, comments, lastcomm, lastadd) VALUES ('".$char['user_id']."', '".$char['name']."', '$nazv', '$col_z', '$col_c', '', '$linfo', '$rating', '$friends', '$comm', '0', '$status', '0', '0', '', '')");
			}
			echo'<center><b><font color=red>Настройки сохранены!</font></b></center>';
		}
	}
	break;

    case 'rules':
    {
        include("law.php");
    }
    break;
        

	case 'friends':
	{
		if ($user_id>0)
		{
			if (isset($del))
			{
				$del=(int)$del;
				$prov=myquery("select * from blog_friends where user_id='$user_id' and friend_id='$del'");
				if (mysql_num_rows($prov)!='0')
				{
					myquery("delete from blog_friends where user_id='$user_id' and friend_id='$del'");
					echo'<center><br><br><font color=red><b>удален из списка друзей</b></font></center><br /><br />';
				}
			}

			echo'Список друзей:<br><br>';
			$fru=myquery(" (
			SELECT game_users.name AS friend_name, blog_friends.friend_id
			FROM blog_friends, game_users
			WHERE blog_friends.friend_id = game_users.user_id
			AND blog_friends.user_id =$user_id
			)
			UNION (

			SELECT game_users_archive.name AS friend_name, blog_friends.friend_id
			FROM blog_friends, game_users_archive
			WHERE blog_friends.friend_id = game_users_archive.user_id
			AND blog_friends.user_id =$user_id
			) ");
			if (mysql_num_rows($fru)!=0)
			{
				while($elf=mysql_fetch_array($fru))
				{
					echo'<b>'.$elf['friend_name'].'</b> - [<a href="?option=friends&del='.$elf['friend_id'].'&user='.$user_id.'">Удалить</a>]<br>';
				}
			}
			else
			{
				echo'<b><font color=red>В списке друзей нет никого!</font></b>';
			}


			if (!isset($see))
			{
				echo'
				<script type="text/javascript">
				/* URL to the PHP page called for receiving suggestions for a keyword*/
				var getFunctionsUrl = "../suggest/suggest.php?keyword=";
				</script>';
				echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
				echo'<script type="text/javascript" src="../suggest/suggest.js"></script>';

				echo'<div id="content" onclick="hideSuggestions();"><br /><br /><form action="" method="post">Добавить в друзья: <input type="text" name="nam" size=50 id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div> <input type="submit" value="Добавить"><input name="see" type="hidden" value=""></form>';
				echo '</div><script>init();</script>';
			}
			else
			{
				$friend_id = get_user("user_id",$nam,1);
				if ($friend_id!="~~~")
				{
                    $sel = @mysql_result(@myquery("SELECT COUNT(*) FROM blog_friends WHERE user_id='$user_id' AND friend_id='$friend_id'"),0,0);
                    if ($sel > 0)
                      echo'Уже в друзьях!';
                    else
                    {
					  echo'<br><br>Список друзей обновлен!';
					  myquery("insert into blog_friends (user_id, friend_id) values ('".$char['user_id']."','".$friend_id."')");
                    }
				}
				else
				{
					echo'<br><br>Игрок не найден!';
				}
			}
		}
	}
	break;

	case 'closed':
	{
		if ($user_id>0)
		{
			if (isset($del))
			{
				$del=(int)$del;
				$prov=myquery("select * from blog_closed where user_id='$user_id' and close_id='$del'");
				if (mysql_num_rows($prov)!=0)
				{
					myquery("delete from blog_closed where user_id='$user_id' and close_id='$del'");
					echo'<center><br><br><font color=red><b>удален из списка "врагов"</b></font></center><br /><br />';
				}
			}

			echo'Список "врагов":<br><br>';
			$fru=myquery(" (
			SELECT game_users.name AS close_name, blog_closed.close_id
			FROM blog_closed, game_users
			WHERE blog_closed.close_id = game_users.user_id
			AND blog_closed.user_id =$user_id
			)
			UNION (

			SELECT game_users_archive.name AS close_name, blog_closed.close_id
			FROM blog_closed, game_users_archive
			WHERE blog_closed.close_id = game_users_archive.user_id
			AND blog_closed.user_id =$user_id
			) ");
			if (mysql_num_rows($fru)!=0)
			{
				while($elf=mysql_fetch_array($fru))
				{
					echo'<b>'.$elf['close_name'].'</b> - [<a href="?option=closed&del='.$elf['close_id'].'&user='.$user_id.'">Удалить</a>]<br>';
				}
			}
			else
			{
				echo'<b><font color=red>В списке "врагов" нет никого!</font></b>';
			}


			if (!isset($see))
			{
				echo'
				<script type="text/javascript">
				/* URL to the PHP page called for receiving suggestions for a keyword*/
				var getFunctionsUrl = "../suggest/suggest.php?keyword=";
				</script>';
				echo'<link href="../suggest/suggest.css" rel="stylesheet" type="text/css">';
				echo'<script type="text/javascript" src="../suggest/suggest.js"></script>';

				echo'<div id="content" onclick="hideSuggestions();"><br /><br /><form action="" method="post">Добавить в "враги": <input type="text" name="nam" size=50 id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div> <input type="submit" value="Добавить"><input name="see" type="hidden" value=""></form>';
				echo '</div><script>init();</script>';
			}
			else
			{
				$close_id = get_user("user_id",$nam,1);
				if ($close_id!="~~~")
				{
					echo'<br><br>Список "врагов" обновлен!';
					myquery("insert into blog_closed (user_id, close_id) values ('".$char['user_id']."','".$close_id."')");
				}
				else
				{
					echo'<br><br>Игрок не найден!';
				}
			}
		}
	}
	break;

	case 'optlove':
	if ($user_id>0)
	{
		if (isset($del))
		{
			$del=(int)$del;
			$prov=myquery("select * from blog_love where user_id='".$char['user_id']."' and friend_id='$del'");
			if (mysql_num_rows($prov)!='0')
			{
				myquery("delete from blog_love where user_id='".$char['user_id']."' and friend_id='$del'");
				echo'<center><br><br><font color=red><b>Удален из списка избранных дневников</b></font></center><br /><br />';
			}
		}

		echo'Список избранных дневников:<br><br>';
		$fru=myquery("select blog_love.*,blog_users.name AS friend_name from blog_love,blog_users where blog_love.friend_id=blog_users.user_id AND blog_love.user_id='".$char['user_id']."'");
		if (mysql_num_rows($fru)!='0')
		{
			while($elf=mysql_fetch_array($fru))
			{
				echo'<b>'.$elf['friend_name'].'</b> - [<a href="?option=optlove&del='.$elf['friend_id'].'">Удалить</a>]<br>';
			}
		}
		else
		{
			echo'<b><font color=red>В списке избранных дневников нет никого!</font></b>';
		}


		if (!isset($see))
		{
			echo'<br /><br /><form action="" method="post">Добавить в избранные дневники: <input type="text" name="nam" size=50> <input type="submit" value="Добавить"><input name="see" type="hidden" value=""></form>';
		}
		else
		{
			$prov=myquery("select * from blog_users where name='".$nam."'");
			if (mysql_num_rows($prov)!='0')
			{
				echo'<br><br>Список избранных дневников обновлен!';
				$pro=mysql_fetch_array($prov);
				myquery("insert into blog_love (user_id, friend_id) values ('".$char['user_id']."','".$pro['user_id']."')");
			}
			else
			{
				echo'<br><br>У такого пользователя нет дневника!';
			}
		}
	}
	break;

	case 'photo':
	if ($user_id>0)
	{
		$sel=myquery("select * from blog_users where user_id='".$char['user_id']."'");
		$my=mysql_fetch_array($sel);
		if (!isset($see))
		{
			echo'<table width=100% align=center><tr><td>';
		
			if ($my['viewimg']!='') echo "Ваша Фотография:<br><img src=$img/photo/$my[viewimg]>";

			if (!isset($upload)) $upload="";
			$absolute_path = "../../images/diary/photo";
			$size_limit = "yes";
			$limit_size = "102400";
			$limit_ext = "yes";
			$ext_count = "2";
			$image_max_width        = "200";    // максимальные ширина и высота
			$image_max_height        = "250";   //  для графических файлов
			$extensions = array(".gif", ".jpeg", ".jpg", ".GIF", ".JPEG", ".JPG");
			switch($upload)
			{
				default:
					echo"<br><br>Максимальный размер ".($limit_size/1024)." килобайт (Только .gif и .jpg)<br>
					<form method=\"POST\" action=\"?option=photo&upload=doupload\" enctype=\"multipart/form-data\">
					<input type=file name=file size=20 > <input name=\"submit\" type=\"submit\" value=Закачать>
					</form>";

					echo'Фотография:<br>
					1. Не должна быть больше размеров: '.$image_max_width.'х'.$image_max_height.'<br>
					2. Не должна иметь рекламное, порнографическое содержание<br>
					3. Все недопустимые фотографии будут удаляться, а владельцы наказаны.';
				break;

				case "doupload":
					$endresult = "<font size=\"2\">Фотография закачана</font>";
					if (!isset($file_name) OR $file_name == "")
					{
						$endresult = "<font size=\"2\">Ты ничего не ".echo_sex('выбрал','выбрала')."</font>";
					}
					else
					{
						if (($size_limit == "yes") && ($limit_size < $file_size) AND ($char['clan_id']!=1))
						{
							$endresult = "<font size=\"2\">Большой размер</font>";
						}
						else
						{
							$size = GetImageSize($file);
							list($width,$height,$bar,$foo) = $size;
							if ($bar!=1 AND $bar!=2 AND $bar!=3 AND $bar!=6)
							{
								$endresult = "<font size=\"2\">Разешены форматы: GIF JPG PNG BMP</font>";
							}
							elseif ($width > $image_max_width AND $char['clan_id']!=1)
							{
								$endresult = "Ошибка! Изображение должно быть не шире\n ".$image_max_width." пикселей, а твое $width пикселей<br></li>";
							}
							elseif ($height > $image_max_height AND $char['clan_id']!=1)
							{
								$endresult = "Ошибка! Изображение должно быть не выше\n " . $image_max_height . " пикселей, а твое $height пикселей<br></li>";
							}
							else
							{
								$file_name=''.$char['user_id'].'.gif';
								if(is_file("$absolute_path/$file_name"))
								{
									@unlink ("$absolute_path/$file_name");
								}
								@copy($file, "$absolute_path/$file_name") or $endresult = "<font size=\"2\">Такой файл уже существует</font>";
								if ($endresult == "<font size=\"2\">Фотография закачана</font>")
								{
									$upd=myquery("update blog_users set viewimg='".$file_name."' where user_id=".$char['user_id']."");
								}
							}
						}
					}
					echo"<tr><td></td><td><center> $endresult  <a href=?option=photo>назад</a> </center></td></tr>";
				break;
			}
			echo '<tr><td></td></tr></table>';
		}
	}
	break;
}

echo'    
	</td>
</tr>
<tr>
	<td width="250">
	<table width="250" height="80" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="16" background="'.$img.'/img/menu_g_fd.gif">&nbsp;</td>
			<td >&nbsp;</td>
			<td width="16" background="'.$img.'/img/menu_d_fd.gif">&nbsp;</td>
		</tr>
		<tr height="3">
			<td width=16><img height=6 src="'.$img.'/img/menu_bg.gif" width=16></td>
			<td background="'.$img.'/img/menu_b_fd.gif"><img height=6 src="'.$img.'/img/pixel.gif"></td>
			<td width=16><img height=6 src="'.$img.'/img/menu_bd.gif" width=16></td>
		</tr>
	</table>
	</td>
	<td valign="bottom">    
		<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
			<tr> 
				<td>&nbsp;</td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr> 
				<td background="'.$img.'/img/bot-back.gif">
				<div style="width:100%" align="center"><img src="'.$img.'/img/bot-mid.gif" width="352" height="70"></div>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<center>';
include("../lib/banners.php");
echo'
</center>
</body>

</html>';

if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}

if (function_exists("save_debug")) save_debug(); 

?>