<?
// $adm['npc'] = 1 - редактирование существующих ботов
// $adm['npc'] = 2 - добавление новых и редактирование существующих ботов
// $adm['npc'] = 3 - редактирование шаблона ботов и дропа
// $adm['npc'] = 4 - добавление новых и редактирование шаблона ботов и дропа
if (function_exists("start_debug")) start_debug(); 

if ($adm['npc'] >= 3)
{
	if (isset($_GET['new']) AND $adm['npc'] >= 4)
	{
		if (isset($_POST['submit']))
		{
			if (!isset($_POST['canmove']))
			{
				$canmove = 0;
			}
			else
			{
				$canmove = 1;
			}
			myquery("INSERT INTO game_npc_template SET npc_name='".$_POST['npc_name']."',npc_race='".$_POST['npc_race']."',npc_max_hp='".$_POST['npc_max_hp']."',npc_max_mp='".$_POST['npc_max_mp']."',npc_level='".$_POST['npc_level']."',respawn='".$_POST['respawn']."',npc_str='".$_POST['npc_str']."',npc_str_deviation='".$_POST['npc_str_deviation']."',npc_dex='".$_POST['npc_dex']."',npc_dex_deviation='".$_POST['npc_dex_deviation']."',npc_pie='".$_POST['npc_pie']."',npc_pie_deviation='".$_POST['npc_pie_deviation']."',npc_vit='".$_POST['npc_vit']."',npc_vit_deviation='".$_POST['npc_vit_deviation']."',npc_spd='".$_POST['npc_spd']."',npc_spd_deviation='".$_POST['npc_spd_deviation']."',npc_ntl='".$_POST['npc_ntl']."',npc_ntl_deviation='".$_POST['npc_ntl_deviation']."',npc_exp_max='".$_POST['npc_exp_max']."',npc_gold='".$_POST['npc_gold']."',canmove='".$canmove."',item='".$_POST['item']."',agressive='".$_POST['agressive']."',agressive_level='".$_POST['agressive_level']."',npc_img='".$_POST['npc_img']."',npc_opis='".$_POST['npc_opis']."'");
			//сохраняем шаблон бота
			setLocation("admin.php?opt=main&option=npc_template");
		}
		else
		{
			echo'
			<form name="edit_npc_template" action="admin.php?opt=main&option=npc_template&new" method="POST">
			<table border=0><tr><td><table border=0 bgcolor=111111>
			<tr><td>Имя (раса): </td><td><input type="text" name="npc_name" size="40" value=""> <br />(<input type="text" name="npc_race" size="38" value="">)</td></tr>
			<tr><td>Жизни/Мана: </td><td><input type="text" name="npc_max_hp" size="7" value="">/<input type="text" name="npc_max_mp" size="7" value=""></td></tr>
			<tr><td>Уровень: </td><td><input type="text" name="npc_level" size="5" value=""></td></tr>
			<tr><td>Время воскрешения: </td><td><input type="text" name="respawn" size="10" value=""> секунд</td></tr>
			<tr><td>Сила: </td><td><input type="text" size="5" value="" name="npc_str">&plusmn;<input type="text" size="5" value="" name="npc_str_deviation"></td></tr>
			<tr><td>Выносливость: </td><td><input type="text" size="5" value="" name="npc_dex">&plusmn;<input type="text" size="5" value="" name="npc_dex_deviation"></td></tr>
			<tr><td>Ловкость: </td><td><input type="text" size="5" value="" name="npc_pie">&plusmn;<input type="text" size="5" value="" name="npc_pie_deviation"></td></tr>
			<tr><td>Защита: </td><td><input type="text" size="5" value="" name="npc_vit">&plusmn;<input type="text" size="5" value="" name="npc_vit_deviation"></td></tr>
			<tr><td>Мудрость: </td><td><input type="text" size="5" value="" name="npc_spd">&plusmn;<input type="text" size="5" value="" name="npc_spd_deviation"></td></tr>
			<tr><td>Интеллект: </td><td><input type="text" size="5" value="" name="npc_ntl">&plusmn;<input type="text" size="5" value="" name="npc_ntl_deviation"></td></tr>

			<tr><td>Опыт: </td><td style="color:white;font-size:12px;font-weight:700;"><input type="text" size="5" value="" name="npc_exp_max"></td></tr>
			<tr><td>Золото: </td><td style="color:white;font-size:12px;font-weight:700;"><input type="text" size="5" value="" name="npc_gold"></td></tr>';
			
			echo '<tr><td align="right"><input name="canmove" type="checkbox" value="1" checked></td><td>';
			echo 'Бот "путешествует" по карте';
			
			echo'</td></tr><tr><td>Предмет атаки: </td><td><input type="text" name="item" size="40" value=""></td></tr>

			<tr><td>Агрессивность монстра: </td><td>
			<select name="agressive">
			<option value="-1">Бот мирный. На него напасть нельзя.</option>
			<option value="0" selected>Бот ни на кого не нападает</option>
			<option value="1">Бот нападает по разнице уровней</option>
			<option value="2">Бот нападает на всех игроков</option>
			</select> Разница в <input name="agressive_level" value="" type="text" size="2"> уровней
			</td></tr>

			</table></td>
			<td>
			Картинка бота: <br />/images/npc/<input type="text" name="npc_img" size="20" value="" name="npc_img">.gif<br />
			<br /><br />Описание бота:<br /><textarea cols="30" rows="10" name="npc_opis"></textarea> </td>
			</tr></table><br><br><input name="submit" type="submit" value="Сохранить">';
		}
	}
	elseif (isset($_GET['edit']))
	{
		$itemc = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=".$_GET['edit'].""));
		if (isset($_POST['submit']))
		{
			if (!isset($_POST['canmove']))
			{
				$canmove = 0;
			}
			else
			{
				$canmove = 1;
			}
			myquery("UPDATE game_npc_template SET npc_name='".$_POST['npc_name']."',npc_race='".$_POST['npc_race']."',npc_max_hp='".$_POST['npc_max_hp']."',npc_max_mp='".$_POST['npc_max_mp']."',npc_level='".$_POST['npc_level']."',respawn='".$_POST['respawn']."',npc_str='".$_POST['npc_str']."',npc_str_deviation='".$_POST['npc_str_deviation']."',npc_dex='".$_POST['npc_dex']."',npc_dex_deviation='".$_POST['npc_dex_deviation']."',npc_pie='".$_POST['npc_pie']."',npc_pie_deviation='".$_POST['npc_pie_deviation']."',npc_vit='".$_POST['npc_vit']."',npc_vit_deviation='".$_POST['npc_vit_deviation']."',npc_spd='".$_POST['npc_spd']."',npc_spd_deviation='".$_POST['npc_spd_deviation']."',npc_ntl='".$_POST['npc_ntl']."',npc_ntl_deviation='".$_POST['npc_ntl_deviation']."',npc_exp_max='".$_POST['npc_exp_max']."',npc_gold='".$_POST['npc_gold']."',canmove='".$canmove."',item='".$_POST['item']."',agressive='".$_POST['agressive']."',agressive_level='".$_POST['agressive_level']."',npc_img='".$_POST['npc_img']."',npc_opis='".$_POST['npc_opis']."' WHERE npc_id=".$_GET['edit']."");
			//сохраняем шаблон бота
			myquery("Update game_npc Set EXP=".$_POST['npc_exp_max']." Where npc_id=".$_GET['edit'].""); //Изменяем получаемый опыт с бота
			setLocation("admin.php?opt=main&option=npc_template&page=".$_GET['page']."");
		}
		elseif (isset($npcopt))
		{
			include('npc_template_option.inc.php');
		}
		else
		{
			echo'
			<form name="edit_npc_template" action="admin.php?opt=main&option=npc_template&edit='.$_GET['edit'].'&page='.$_GET['page'].'" method="POST">
			<table border=0><tr><td><table border=0 bgcolor=111111>
			<tr><td>Имя (раса): </td><td><input type="text" name="npc_name" size="40" value="'.$itemc['npc_name'].'"> <br /> (<input type="text" name="npc_race" size="35" value="'.$itemc['npc_race'].'">)</td></tr>
			<tr><td>Жизни/Мана: </td><td><input type="text" name="npc_max_hp" size="7" value="'.$itemc['npc_max_hp'].'">/<input type="text" name="npc_max_mp" size="7" value="'.$itemc['npc_max_mp'].'"></td></tr>
			<tr><td>Уровень: </td><td><input type="text" name="npc_level" size="5" value="'.$itemc['npc_level'].'"></td></tr>
			<tr><td>Время воскрешения: </td><td><input type="text" name="respawn" size="10" value="'.$itemc['respawn'].'"> секунд</td></tr>
			<tr><td>Сила: </td><td><input type="text" size="5" value="'.$itemc['npc_str'].'" name="npc_str">&plusmn;<input type="text" size="5" value="'.$itemc['npc_str_deviation'].'" name="npc_str_deviation"></td></tr>
			<tr><td>Выносливость: </td><td><input type="text" size="5" value="'.$itemc['npc_dex'].'" name="npc_dex">&plusmn;<input type="text" size="5" value="'.$itemc['npc_dex_deviation'].'" name="npc_dex_deviation"></td></tr>
			<tr><td>Ловкость: </td><td><input type="text" size="5" value="'.$itemc['npc_pie'].'" name="npc_pie">&plusmn;<input type="text" size="5" value="'.$itemc['npc_pie_deviation'].'" name="npc_pie_deviation"></td></tr>
			<tr><td>Защита: </td><td><input type="text" size="5" value="'.$itemc['npc_vit'].'" name="npc_vit">&plusmn;<input type="text" size="5" value="'.$itemc['npc_vit_deviation'].'" name="npc_vit_deviation"></td></tr>
			<tr><td>Мудрость: </td><td><input type="text" size="5" value="'.$itemc['npc_spd'].'" name="npc_spd">&plusmn;<input type="text" size="5" value="'.$itemc['npc_spd_deviation'].'" name="npc_spd_deviation"></td></tr>
			<tr><td>Интеллект: </td><td><input type="text" size="5" value="'.$itemc['npc_ntl'].'" name="npc_ntl">&plusmn;<input type="text" size="5" value="'.$itemc['npc_ntl_deviation'].'" name="npc_ntl_deviation"></td></tr>

			<tr><td>Опыт: </td><td style="color:white;font-size:12px;font-weight:700;"><input type="text" size="5" value="'.$itemc['npc_exp_max'].'" name="npc_exp_max"></td></tr>
			<tr><td>Золото: </td><td style="color:white;font-size:12px;font-weight:700;"><input type="text" size="5" value="'.$itemc['npc_gold'].'" name="npc_gold"></td></tr>';
			
			echo '<tr><td align="right"><input name="canmove" type="checkbox" value="1"';
			if ($itemc['canmove'] == 1) echo' checked';
			echo '></td><td>Бот "путешествует" по карте';
			
			echo'</td></tr><tr><td>Предмет атаки: </td><td><input type="text" name="item" size="40" value="'.$itemc['item'].'"></td></tr>

			<tr><td>Агрессивность монстра: </td><td>
			<select name="agressive">
			<option value="-1"'; if ($itemc['agressive']=='-1') echo ' selected'; echo'>Бот мирный. На него напасть нельзя.</option>
			<option value="0"'; if ($itemc['agressive']=='0') echo ' selected'; echo'>Бот ни на кого не нападает</option>
			<option value="1"'; if ($itemc['agressive']=='1') echo ' selected'; echo'>Бот нападает по разнице уровней</option>
			<option value="2"'; if ($itemc['agressive']=='2') echo ' selected'; echo'>Бот нападает на всех игроков</option>
			</select> Разница в <input name="agressive_level" value="'.$itemc['agressive_level'].'" type="text" size="2"> уровней
			</td></tr>
			</table></td>
			<td>
			Картинка бота: <br />/images/npc/<input type="text" name="npc_img" size="20" value="'.$itemc['npc_img'].'" name="npc_img">.gif<br />
			<img src="http://'.IMG_DOMAIN.'/npc/'.$itemc['npc_img'].'.gif" border=1><br /><br />Описание бота:<br /><textarea cols="30" rows="10" name="npc_opis">'.$itemc['npc_opis'].'</textarea> </td>
			</tr></table><br><br><input type="hidden" name="submit" value="1"><input type="submit" value="Сохранить"></form><br />';
			echo '<a href="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&npcopt">Дополнительные опции</a>';
			?>
			<script type="text/javascript">
			/* URL to the PHP page called for receiving suggestions for a keyword*/
			var getFunctionsUrl = "suggest/suggest_items.php?keyword=";
			var startSearch = 1;
			
			function switch_type(type)
			{
				if(type.selectedIndex==0)
				{
					getFunctionsUrl = "suggest/suggest_items.php?keyword=";
				}
				if(type.selectedIndex==1)
				{
					getFunctionsUrl = "suggest/suggest_resource.php?keyword=";
				}
			}
			</script>
			<link href="suggest/suggest.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="suggest/suggest.js"></script>
			<script type="text/javascript">
			var current_quest=<?=$_GET['edit'];?>;
			function createXmlHttpRequestObject()
			{
				var xmlHttp;
				try
				{
					xmlHttp = new XMLHttpRequest();
				}
				catch(e)
				{
					var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
					"MSXML2.XMLHTTP.5.0",
					"MSXML2.XMLHTTP.4.0",
					"MSXML2.XMLHTTP.3.0",
					"MSXML2.XMLHTTP",
					"Microsoft.XMLHTTP");
					for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
					{
						try
						{
							xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
						}
						catch (e) {}
					}
				}
				if (!xmlHttp)
					alert("Error creating the XMLHttpRequest object.");
				else
					return xmlHttp;
			}
			function refresh_res()
			{
				if(AjaxRequest)
				{
					try
					{
						if (AjaxRequest.readyState == 4 || AjaxRequest.readyState == 0)
						{
							URL = "./ajax/admin/npc_drop.php?read="+current_quest;
							AjaxRequest.open("GET", URL, true);
							AjaxRequest.onreadystatechange = process_refresh;
							AjaxRequest.send(null);
						}
					}
					catch(e)
					{
					}
				}
				else
				{
					AjaxRequest = createXmlHttpRequestObject();
					setTimeout("refresh_res();", 50);
				}
			}
			function process_refresh()
			{
				try
				{
					if (AjaxRequest.readyState == 4)
					{
						if (AjaxRequest.status == 200)
						{
							try
							{
								 el = document.getElementById("div_npc_drop");
								 el.innerHTML = AjaxRequest.responseText;
								 init();
							}
							catch(e)
							{
							}
						}
						else
						{
						}
					}
				}
				catch(e)
				{
				}
			}
			
			function save_res(ids)
			{
				if(AjaxRequestWork)
				{
					try
					{
						if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
						{
							var sBody = "";
							if (ids=='new')
							{
								sBody=sBody+"variant="+document.getElementById("variant").value;
								sBody=sBody+"&drop_type="+document.getElementById("drop_type").value;
								sBody=sBody+"&name_items="+encodeURIComponent(document.getElementById("keyword").value);
								sBody=sBody+"&chance="+document.getElementById("chance").value;
								sBody=sBody+"&chance_max="+document.getElementById("chance_max").value;
								sBody=sBody+"&kuda="+document.getElementById("kuda").value;
								sBody=sBody+"&mincount="+document.getElementById("mincount").value;
								sBody=sBody+"&maxcount="+document.getElementById("maxcount").value;
							}
							else
							{
								sBody+="variant="+document.getElementById("variant"+ids).value;
								sBody+="&drop_type="+document.getElementById("drop_type"+ids).value;
								sBody+="&name_items="+encodeURIComponent(document.getElementById("name_items"+ids).value);
								sBody+="&chance="+document.getElementById("chance"+ids).value;
								sBody+="&chance_max="+document.getElementById("chance_max"+ids).value;
								sBody+="&kuda="+document.getElementById("kuda"+ids).value;
								sBody+="&mincount="+document.getElementById("mincount"+ids).value;
								sBody+="&maxcount="+document.getElementById("maxcount"+ids).value;
								sBody+="&drop_id="+ids;
							}
							URL = "./ajax/admin/npc_drop.php?save="+current_quest;
							AjaxRequestWork.open("POST", URL, true);
							AjaxRequestWork.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
							AjaxRequestWork.onreadystatechange = process_save_delete;
							AjaxRequestWork.send(sBody);
						}
					}
					catch(e)
					{
					}
				}
				else
				{
					AjaxRequestWork = createXmlHttpRequestObject();
				}
			}
			function delete_res(ids)
			{
				if(AjaxRequestWork)
				{
					try
					{
						if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
						{
							URL = "./ajax/admin/npc_drop.php?delete="+ids;
							AjaxRequestWork.open("GET", URL, true);
							AjaxRequestWork.onreadystatechange = process_save_delete;
							AjaxRequestWork.send(null);
						}
					}
					catch(e)
					{
					}
				}
				else
				{
					AjaxRequestWork = createXmlHttpRequestObject();
				}
			}
			function process_save_delete()
			{
				try
				{
					if (AjaxRequestWork.readyState == 4)
					{
						if (AjaxRequestWork.status == 200)
						{
							try
							{
								 if (AjaxRequestWork.responseText=='ok')
								 {
								 }
								 else
								 {
									alert(AjaxRequestWork.responseText)
								 }
							}
							catch(e)
							{
							}
						}
						else
						{
						}
					}
				}
				catch(e)
				{
				}
				refresh_res();
			}

			var AjaxRequest = createXmlHttpRequestObject();
			var AjaxRequestWork = createXmlHttpRequestObject();
			refresh_res(); 
			</script>
			<div id="div_npc_drop"></div>
			<?
		}
	}
	else
	{
		if (isset($_GET['delete']))
		{
			myquery("DELETE FROM game_npc WHERE npc_id=".$_GET['delete']."");
			myquery("DELETE FROM game_npc_template WHERE npc_id=".$_GET['delete']."");
		}
		
		$order_by = "npc_id DESC";
		if (isset($_GET['order'])) 
		{
			$order_by = $_GET['order'];
			$_SESSION['npc_order'] = $order_by;
		}
		elseif (isset($_SESSION['npc_order'])) 
		{
			$order_by = $_SESSION['npc_order'];
		}
		
		$sel_all = myquery("SELECT COUNT(*) FROM game_npc_template ORDER BY $order_by");
		
		echo '<a href="admin.php?opt=main&option=npc_template&order=npc_img ASC">Сортировка по имени картинки</a><br />';
		echo '<a href="admin.php?opt=main&option=npc_template&order=BINARY npc_name ASC">Сортировка по имени бота</a><br />';
		echo '<a href="admin.php?opt=main&option=npc_template&order=npc_id DESC">Сортировка по убыванию ID</a><br />';
		
		if ($adm['npc']>=4)
		{
			echo '<br /><br /><br /><a href="admin.php?opt=main&option=npc_template&new">Добавить новый шаблон</a><br /><br />';
		}
		
		if (!isset($page)) $page=1;
		$page=(int)$page;
		$line=10;
		$allpage=ceil(mysql_result($sel_all,0,0)/$line);
		if ($page>$allpage) $page=$allpage;
		if ($page<1) $page=1;
		
		$sel = myquery("SELECT * FROM game_npc_template ORDER BY $order_by limit ".(($page-1)*$line).", $line");
		while ($itemc = mysql_fetch_array($sel))
		{
			echo'<table border=0><tr><td><a href="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&page='.$page.'">Редактировать</a><br />';
			if ($itemc['to_delete']==1)
			{
				echo 'БОТ БУДЕТ УДАЛЕН!<br /><table border=0 bgcolor=#800000>';
			}
			else
			{
				echo '<table border=0 bgcolor=111111>';
			}
			echo'
			<tr><td>Имя (раса): </td><td>'.$itemc['npc_name'].' ('.$itemc['npc_race'].')</td></tr>
			<tr><td>Жизни/Мана: </td><td>'.$itemc['npc_max_hp'].'/'.$itemc['npc_max_mp'].'</td></tr>
			<tr><td>Уровень: </td><td>'.$itemc['npc_level'].'</td></tr>
			<tr><td>Время воскрешения: </td><td>'.$itemc['respawn'].' секунд</td></tr>
			<tr><td>Сила: </td><td>'.$itemc['npc_str'].'&plusmn;'.$itemc['npc_str_deviation'].'</td></tr>
			<tr><td>Выносливость: </td><td>'.$itemc['npc_dex'].'&plusmn;'.$itemc['npc_dex_deviation'].'</td></tr>
			<tr><td>Ловкость: </td><td>'.$itemc['npc_pie'].'&plusmn;'.$itemc['npc_pie_deviation'].'</td></tr>
			<tr><td>Защита: </td><td>'.$itemc['npc_vit'].'&plusmn;'.$itemc['npc_vit_deviation'].'</td></tr>
			<tr><td>Мудрость: </td><td>'.$itemc['npc_spd'].'&plusmn;'.$itemc['npc_spd_deviation'].'</td></tr>
			<tr><td>Интеллект: </td><td>'.$itemc['npc_ntl'].'&plusmn;'.$itemc['npc_ntl_deviation'].'</td></tr>

			<tr><td>Опыт: </td><td style="color:white;font-size:12px;font-weight:700;">'.$itemc['npc_exp_max'].'</td></tr>
			<tr><td>Золото: </td><td style="color:white;font-size:12px;font-weight:700;">'.$itemc['npc_gold'].'</td></tr>';
			
			if ($itemc['canmove'] == 0) echo'<tr><td colspan=2><b><font color=ff0000>Не передвигается по гексам</font></b></td></tr>';
			echo'
			<tr><td>Предмет атаки: </td><td>'.$itemc['item'].'</td></tr>
			<tr><td colspan=2>';

			if ($itemc['agressive']=='-1') echo '<font color=#80FFFF> Бот мирный. Почтовый работник. На него напасть нельзя.</font>';
			if ($itemc['agressive']=='0') echo '<font color=#80FFFF> Бот не агрессивен</font>';
			if ($itemc['agressive']=='1') echo '<font color=#00FF00> Бот нападет на игроков, у которых уровень на '.$itemc['agressive_level'].' > уровня бота</font>';
			if ($itemc['agressive']=='2') echo '<font color=#FF0000><b> Бот нападает на ВСЕХ игроков!<b></font>

			</td></tr>
			<tr><td colspan=2>&nbsp;';
			if ($adm['npc']>=4)
			{
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php?opt=main&option=npc_template&delete='.$itemc['npc_id'].'&page='.$page.'">Удалить</a>';
			}
			echo '</td></tr>';
			
			echo'</table></td>
			<td><a href="admin.php?opt=main&option=npc_template&edit='.$itemc['npc_id'].'&page='.$page.'"><img src="http://'.IMG_DOMAIN.'/npc/'.$itemc['npc_img'].'.gif" border=1></a><br />http://'.IMG_DOMAIN.'/npc/'.$itemc['npc_img'].'.gif
			';
			$sel_pos = myquery("SELECT game_npc.id,game_npc.xpos,game_npc.ypos,game_maps.name FROM game_npc,game_maps WHERE game_npc.npc_id='".$itemc['npc_id']."' AND game_npc.map_name=game_maps.id");
			if (mysql_num_rows($sel_pos)>0)
			{
				echo '<br /><br />Боты по шаблону:<ul>';
				while ($npcpos = mysql_fetch_array($sel_pos))
				{
					echo '&nbsp;'.$npcpos['name'].'  X-'.$npcpos['xpos'].',  Y-'.$npcpos['ypos'];
					echo '&nbsp;&nbsp;&nbsp;<a href="admin.php?opt=main&option=npc&edit='.$npcpos['id'].'&npc_template">Редакт.</a><br />';
				}
				echo '</ul>';
			}
			echo '</td>

			</tr></table><br><br>';
		}
		$href = 'admin.php?opt=main&option=npc_template&';
		echo'<center>Страница: ';
		show_page($page,$allpage,$href);
	}
}

if (function_exists("save_debug")) save_debug(); 
?>