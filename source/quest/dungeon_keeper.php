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
//��������� ��� �������� ���������� ������ �������� ������ ���������� - �������� ��� ����������!!!
$map_level_id=array(691=>1,692=>2,804=>3); 
//��������, ��� ���� ��������� �� ������� ������� ����� �� ���� ���������� - �������� ��� ����������!!!
if(($char['map_name']==691 OR $char['map_name']==692 OR $char['map_name']==804) AND $char['map_xpos']==0 AND $char['map_ypos']==0)
{
	?>
	<table width="100%"><tr><td width="256">
	<img src="http://<?=IMG_DOMAIN;?>/nav/dungeon_keeper.gif" align="middle" height="400" width="256"></td><td>
	<?
	OpenTable('title',"100%","400");
	echo'<br><center><font size=4 face=verdana color=#fce66b>�������� ������</font><br><br>';
	echo '<hr align=center size=2 width=80%>';
	
	//���� ����� ����� � ��, �������
	if (isset($_GET['exit']))
	{
		echo '<br>';
		echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify>';
		echo '<font color=#ff4433><b>"���� �� ������� �� ����������, �� ������� ��������� ���� �� �����, ��� ����� 10 �����"</b>, - ����������� ���� �������� �� ������. - <b>"�� ����� ������ ����� �� �����������?"</b></font><br><br>';
		echo '</p></tr></td></table>';
		echo '<hr align=center size=2 width=80%>';
		echo '<br><a href="?do_exit" target="game">��, � ���� ����� �� ���������� � ����������</a><br>';
		echo '<br><a href="?begin" target="game">���, � '.echo_sex('���������','����������').'</a><br><br>';
	}
	//���� � ������ ����� - ������
	elseif (isset($_GET['do_exit']))
	{
		myquery("UPDATE dungeon_users_data SET last_visit=".time()." WHERE user_id=".$user_id."");
		myquery("UPDATE game_users_map SET map_xpos=25,map_ypos=20,map_name=18 WHERE user_id=$user_id");
		setLocation("../act.php");
	}
	//������� � �� ���������� � �������
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
	//������ ��������� � ����������
	elseif (isset($_GET['talk']))
	{
		echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify>';
		if ($char['map_name']==691)
		{
			echo '<font color=#aaffa8>����������� ����, '.echo_sex('�������� �������������','�������� �����������������').'! � ����� ���, ��� �� '.echo_sex('�����','������').' ������ ���� ����� � ������������ ����� �������� ���� �� ������� �������, ������� ��������� ��. �������, �� ������ '.echo_sex('������������','�������������').' � ������, ��� ��� � �������� ���� ���� ����� ������������ ��������� ���������, �� ���� ���� �� ������ ���������, �� �� ������ ���������� � ������������ ����� ����. ��������� ������� ����� ��������� ���, � ���� �� ���������, �� ������ �������� � ���� ������� �� �� �����������. ������ � �� ����� �������� � �����������, � ���� � ��� ������, ��� �� ������������� ���������� ��� �������, ���� �������� ��������� �����-������ ������������� �� ����������, ��������, ���-�� � ���� ������� �������. ���� �� �������� '.echo_sex('��������','���������').' ������ ��� � ������� ��������� ��� ��� �������, ����� ��� ���� ����� ������� ������ � �� �������� ��������� ���������� �������� �������! ������!</font>';
		}
		elseif ($char['map_name']==692)
		{
			echo '<font color=#aaffa8>����������� ����, '.echo_sex('�������� �������������','�������� �����������������').'! � ��� ��� �� ����� '.echo_sex('�������','��������').' ���������� �����. ����� ���� ��� �� '.echo_sex('���������','����������').' ������ ������� ����������, ����� ������� ���������, �� ������ ��� ����� ����� ����� ���� ������. ��� �� ������, � ����������� ����� �� �������� ������ ������ ������. ������� ������ ������� ������ ���� ���������� ���������� ��� ������ ������� �����. �� ����� ����� �� �����������, �� ������� ������ �� ����. ����� ������ �� ����� ������ ����������� �� �� ������, �� �������� ������ ����. �� ��� ���� ������ �������� ������ � ������ ���� � ��������, �� ����� ������� ����� ��� ���������� ������� ����. ���� ������� ���������� ���� ������ ������� ����������. �������� �����, ����� �������� � ������ ����������� �������� ������� ����, ��� ��������� ����� ������ ������� ������. ��� ��� ����� ���� ������� ������. ��, ��������� �� �� ��� � ������� �� �� ������ �����!!! ����, � ��� �� ���, ��� ��� � ���������. ��� ������� ������ �� ����� ������, ��� ��. ������ ��� ���������� ������ �������, � �� �������� ��������� ��������������!</font>';
		}
		elseif ($char['map_name']==804)
		{
			echo '���� ������! �� ��-���������� ������� ������! � ���� �� ���� '.echo_sex('�����','�������').' �� ��� '.echo_sex('�������','��������').', �� � ������� �� �� ���������� ������ ����� ��� ��, ��� '.echo_sex('������','������').' ��. ���� �������� ��� �������? �����, �����, �� ���������, ��������� ���������, ����� �� ���������� ��������� ������. �-�����, �� ������� ������ ��������� ��� ����, ��� �� ������. ��������� ������������ � �����-�� ������ �������� �����������, ����� ������� ��������� ����� ������. �� ����� �� � � ���� �����������. �� �� ������� ������ ����, ���� ��������� ��� �������. �� ���� ���� ��� ��� �������� ��������� ��� ����, ����� ��������� ����, ���� �� ���������� 3 �������, �� �� �� ���-�� ���������. ������, ������� ��� ��� �� ���� ��� � ���� ��� �� �� ������ ��� �� ��� � ����� ����� ��������� � �����-������ ������ � ����� ��������. ������� ������� � ������! ���� ���,- �����! �� ���� ����� ����� ���� �� ���� ��������. �� ������� ������� ���� ������� ����!';
		}
		echo '</p></tr></td></table>';
		echo '<hr align=center size=2 width=80%>';
		echo '<br><a href="?choice=1" target="game">�������� �������</a>';
		echo '<br><a href="?choice=2" target="game">����������� ���� ������� �������</a>';
		echo '<br><a href="?choice=3" target="game">���������� � ���������� ������� (����� �������)</a>';
		echo '<br><a href="?begin" target="game">��������� ��������</a><br><br>';
	}
	//���� ������ ���� �� ������� ��������� ��� ���� ����� ��������
	elseif (isset($_GET['choice']) OR (isset($_POST['choice']) AND $_POST['choice']==3))
	{
		$level=$map_level_id[$char['map_name']];
		if(isset($_GET['choice'])) $choice = $_GET['choice'];
		else $choice=3;
		//���� ����� �����
		if ($choice==1)
		{
			//��������, ��� �� � ����� ��� �������
			list($current_quest)=mysql_fetch_array(myquery("SELECT current_quest FROM dungeon_users_data WHERE user_id=".$user_id.""));
			if($current_quest!=0)
			{
				//c�����, ��� ����� ��� ����
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
					echo '<font color=#aaffa8><b>�������-��</b>, - ��������� ���������� �������� �� ������ ��������. - <b>�� �� ��� ���������� ���� �������! ������� ������� ����, � ����� ��� �������� ������.</b></font><br><br>';
					echo '</p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?talk" target="game">���������</a><br><br>';
			}else 
			{
				//������ �������
				//0 - ���������, �� ����� ������� ��� ���� ������
				list($current_level)=mysql_fetch_array(myquery("SELECT current_level FROM dungeon_users_data WHERE user_id=".$user_id.""));
				//c������ ������� �� ������ ������
				include("dungeon_inc/dungeon_level_count.php");
				//���� � ����� ���� ������� �� ���� ������
				if($current_level==$level AND $quests_num[$level]>0)
				{
					//1 - ���������, ����� ������� ���� ��� �� ��������
					$level_quests=range(1,$quests_num[$level]);
					//������� ���������, ����� ��������					
					$dones=myquery("SELECT quest_id FROM dungeon_quests_done WHERE user_id=".$user_id."");
					$done_quests=array();
					while (list($done)=mysql_fetch_array($dones))
					{
						$done_quests[count($done_quests)]=$done;
					}
					//������ ��������� ��������� � ������ ����������
					$free_quests=array();
					
					for($i=0;$i<count($level_quests);$i++)
					{
						if(!in_array($level_quests[$i],$done_quests))
							$free_quests[count($free_quests)]=$level_quests[$i];
					}
					
				}else $free_quests=array();
				//���� ��� ������, ������� ���� ��� �� ���������
				if(count($free_quests)==0)
				{
					//�������, ��� ��� ��� ��
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
					echo '<font color=#aaffa8><b>������, �� � ���� ������ ��� ��� ���� �������</b>, - ������ ������ ��������� ����������.</font><br><br>';
					echo '</p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?talk" target="game">���������</a><br><br>';
				}else 
				{
					//2 - ������� ���� �� �������������
					$quest_id=$free_quests[array_rand($free_quests,1)];
					
					include("dungeon_inc/dungeon_quests.php");
					$caption=$quests[$level][$quest_id]['name'];
					$text=$quests[$level][$quest_id]['description'];
					if(isset($_SESSION['dungeon'])) unset($_SESSION['dungeon']);
					$_SESSION['dungeon']['quest_id']=$quest_id;
					$needle='';
					for($i=1; $i<=count($quests[$level][$quest_id]['res']); $i++)
					{
						$needle.=''.$res[$quests[$level][$quest_id]["res"][$i]["id"]]["name"].' - <b><font color=red>'.$quests[$level][$quest_id]["res"][$i]["kol"].'</font></b> ��<br>';
					}
					$needle=substr($needle,0,strlen($needle)-2);
					
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><UL>';
					echo '<font color=#bcb1ff><LI><b>��� ���� �������:</b><font color=#aaffa8><p> '.$caption.'</p></font><br>';
					echo '<font color=#bcb1ff><LI><b>����� �������:</b><font color=#aaffa8><p> '.$text.'</p></font><br>';
					echo '<font color=#bcb1ff><LI><b>� ������������� ��������:</b><font color=#aaffa8><p> '.$needle.'</p></font><br>';
					
					echo '</UL></p></tr></td></table>';
					echo '<hr align=center size=2 width=80%>';
					echo '<br><a href="?task" target="game">�������</a>';
					echo '<br><a href="?talk" target="game">����������</a><br><br>';
				}
			}
		}
		elseif ($choice==2)
		{
			//�������� �������� �������
			$have_quest=myquery("SELECT current_quest FROM dungeon_users_data WHERE user_id=".$user_id."");
			$level=$map_level_id[$char['map_name']];
			list($current_level)=mysql_fetch_array(myquery("SELECT current_level FROM dungeon_users_data WHERE user_id=".$user_id.""));
			include("dungeon_inc/dungeon_level_count.php");
			//��������� ���-�� ���������� �������
			if($current_level>$level)
			{
				$dones_num='��� ������� ������';
				$quest_id=0;
			}else 
			{
				list($quest_id)=mysql_fetch_array($have_quest);
				$dones_num=mysql_num_rows(myquery("SELECT user_id FROM dungeon_quests_done WHERE user_id=".$user_id.""));
				$dones_num.=' �� '.$quests_num[$level];
			}
			echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
			echo '<b><center><font color=#aaffa8>��������� ������� �� '.$level.' ������ ����������: <font color=red>'.$dones_num.'</font></b></center><br><br>';
			//���� ����� ����
			if($quest_id>0)
			{
				include("dungeon_inc/dungeon_quests.php");
				$caption=$quests[$level][$quest_id]['name'];
				
				echo '<font color=#aaffa8><b>���-���, <font color=red>'.$char["name"].'</font>, ������ ���������</b>, - ��������� ���������� ������� � ����� ���������� � ������ ���� �� �������.<br><br><center>';
				echo '<b>���� ������� �������:</b> '.$caption.'<br><br><b>�������� �����:</b></font>';
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
					echo '<br>'.$font.''.$res_name.': '.$need.' ��.';
				}
			}//���� ������ ���
			else 
			{
				echo '<font color=#aaffa8><b>���-���, <font color=red>'.$char["name"].'</font>, ������ ���������</b>, - ��������� ���������� ������� � ����� ���������� � ������ ���� �� �������. - <b>���, ������ � ���� ��� �������� �������.</b></font>';
			}
			
			echo '</center></p></tr></td></table>';
			echo '<hr align=center size=2 width=80%>';
			echo '<br><a href="?talk" target="game">���������</a><br><br>';			
		}
		//����� �������� �������
		elseif ($choice==3)
		{
			$have_quest=myquery("SELECT current_quest FROM dungeon_users_data WHERE user_id=".$user_id."");
			list($quest_id)=mysql_fetch_array($have_quest);
			include("dungeon_inc/dungeon_quests.php");
			//���� ����� ����
			if($quest_id>0)
			{
				$level=$map_level_id[$char['map_name']];
				//include("dungeon_inc/dungeon_quests.php");
				//���� ���� ��� ������, ��� ������ �������
				if(isset($_POST['ress_num']))
				{
					$ress_num=(int)$_POST['ress_num'];
					for($i=0; $i<$ress_num; $i++)
					{
						//��� ������� ����
						$rid_index='rid'.$i;
						$col_index='col'.$i;
						$res_id=$_POST[$rid_index];
						if(!is_numeric($_POST[$col_index])) $res_col=0;
						else $res_col=max(0,$_POST[$col_index]);
						$the_res=myquery("SELECT col FROM craft_resource_user WHERE user_id=".$user_id." AND res_id =".$res_id."");
						//���� � ����� ���� ���� ��� � ����� ������ 0
						if(mysql_num_rows($the_res) AND $res_col>0)
						{
							$res_col_is=mysql_result($the_res,0,0);
							//���� ���� ����� ������, ��� ����, ����� ��, ��� ����
							if($res_col_is < $res_col)
								$res_col=$res_col_is;
							$weight=mysql_result(myquery("SELECT weight FROM craft_resource WHERE id=".$res_id.";"),0,0);
							$weight=$weight*$res_col;

							if($res_col < $res_col_is)
								myquery("UPDATE craft_resource_user SET col=GREATEST(0,col-".$res_col.") WHERE user_id=".$user_id." AND res_id =".$res_id.";");
							else
								myquery("DELETE FROM craft_resource_user WHERE user_id=".$user_id." AND res_id =".$res_id.";");

							myquery("UPDATE game_users SET CW=CW-".$weight." WHERE user_id=".$user_id.";");
							
							//���� ���� ����� ������, ��� ����, ����� ������
							$res_need=mysql_result(myquery("SELECT res_num FROM dungeon_users_progress WHERE user_id=".$user_id." AND res_id=".$res_id.";"),0,0);
							$res_result=max(0,$res_need-$res_col);
							myquery("UPDATE dungeon_users_progress SET res_num=".$res_result." WHERE user_id=".$user_id." AND res_id =".$res_id.";");
						}
					}
					//unset($_POST['ress_num']);
					
					//��������, �� �������� �� �����
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
					//���� ���������
					if($done==1)
					{
						//����� ��
						//������� ����� ��� ����������
						myquery("INSERT INTO dungeon_quests_done (user_id,quest_id) VALUES (".$user_id.",".$quest_id.")");	
						//�������� ����� ���-�� ���������� �������	
						myquery("UPDATE dungeon_users_data SET current_quest=0, done_quests_num=done_quests_num+1 WHERE user_id=".$user_id."");
						myquery("DELETE FROM dungeon_users_progress WHERE user_id=".$user_id."");
						//���� ����� ��������� ��������
//						include('../inc/lib_craft.inc.php');
						//$eliksir = CreateArrayForCraftEliksir();
						
						//���� �������� ��� ��������
						//����� ���������� �������
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
								$priz.='<br><font color=#bcb1ff>'.$Item->getFact('name').'</font><font color=#aaffa8> - </font><font color=red>'.$col.'</font> <font color=#aaffa8>��., </font>';
							}
						}
						//====================================== 
						$priz=substr($priz,0,strlen($priz)-2);
						echo '<font color=#aaffa8><b>�������, ������� ���������! �������, ��� '.echo_sex('�����','�������').' ��� � ����� �������� ����! ��, �������, ������ ������� ��� ������������� �� ����������� - � ������� � ��� ���� '.$priz.'!</b></font>';
						unset($col);

						//************************************		
						//�������� �� ������ ����������� ������!!!!!!
						//c������ ������� �� ������ ������
						include("dungeon_inc/dungeon_level_count.php");
						if($dones_num>=$quests_num[$level])
						{
							$blazevic=612; 
							$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$blazevic.", '0', '����� ������ ����������', '������������, ��� ��������� ����� ����� :) ������ ��������� ���, ��� ����� ".$char['name']." ������ ".$level." ������� ����������.','0','".time()."')");
                            $stream_dan=2694; 
                            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES (".$stream_dan.", '0', '����� ������ ����������', '������������, ��� ��������� ����� ����� :) ������ ��������� ���, ��� ����� ".$char['name']." ������ ".$level." ������� ����������.','0','".time()."')");
							
							echo '<br><br><font color=#aaffa8>��������� ���������� �������� �� ������ �������� � ��������� ��������: <font color=#aaffa8><b>���� ��� ���������� ������� ������, </font><font color=red>'.$char["name"].'</font>, <font color=#aaffa8> �� '.echo_sex('��������','���������').' ��� ��� �������! �� ����� �, ��� ���-�� ������ ��� �������. �� '.echo_sex('�������','��������').' ������ ���������� ��������. � ������������� ����� � ��� ���� �������� ���� �������� ���������� �������� � � ���� �������������� �������, ���� ���� <font color=#bcb1ff>������� ����������</font>. ������ ��� ��� �� ��� - ���� �� �������� ��������� ��� ������� ����� � ������ ����� ����� � �������� ���� ������� �����, �� ������� ����� � ���������� '.($level+1).' ������. ��� �� ��� ���� ������� ������ �� ��� ��������. � ���������� ������, ����� ��� ������� ���� �� '.($level+1).' �������, ���� �� ��������� ���� �������. �����!</b></font>';
							//�������� ������ �� ����� �����
							myquery("UPDATE dungeon_users_data SET current_level=current_level+1, current_quest=0 WHERE user_id=".$user_id."");
							//������� ���������� �� ����������
							myquery("DELETE FROM dungeon_quests_done WHERE user_id=".$user_id."");
						}	
					}
					else 
					{
						echo '<br><font color=#aaffa8>�� '.echo_sex('����','�����').' �������.</font><br>';
					}
				}
				else 
				{
					//����� �������
					echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br><center>
					<font color=#aaffa8>�� ������ ����� �������� �������:<br><br>';
					//������, ����� ���� ���� �������
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
								echo '<font color=yellow><b>'.$res_name.'</b></font>, ����� 
								<INPUT type="text" size="3" maxlength="3" name="'.$inp_name.'" value="0"> ��. (� ���� <font color=red>'.$res_col.'</font> ��.)<br>
								<INPUT type="hidden" name="'.$hid_name.'" value="'.$res_id.'">';
								$i++;
							}
						}
					}
					if($i==0) echo '<font color=#ff4433><b>� ���� ��� ��������, ������� ����� �����</b></font><br>';
					else echo '<br><br><input type="submit" value="����� �������">';
					echo '<INPUT type="hidden" name="ress_num" value="'.$i.'"></form>';
				}
			}else 
			{
				echo '<table cellpadding="0" cellspacing="0" width="80%" border="0"><tr><td><p align=justify><br>';
				echo '<font color=#aaffa8><b>���-���, <font color=red>'.$char["name"].'</font>, ������ ���������</b>, - ��������� ���������� ������� � ����� ���������� � ������ ���� �� �������. - <b>���, ������ � ���� ��� �������� �������.</b></font>';
			}
			
			echo '</center></p></tr></td></table>';
			echo '<hr align=center size=2 width=80%>';
			echo '<br><a href="?talk" target="game">���������</a><br><br>';	
		}
		
		//QuoteTable('close');
	}
	else
	{
		echo '<br><a href="?talk" target="game">���������� � ���������� ����������</a>';
		echo '<br><a href="?exit" target="game">����� �� ����������</a>';
		echo '<br><a href="../../act.php" target="game">���������</a><br><br>';
	}
	
	OpenTable('close');
	?>
	</td></tr></table>
	<?
	include("../inc/template_footer.inc.php");
}else 
	echo  '<meta http-equiv="refresh" content="0;url=../../act.php">';

?>