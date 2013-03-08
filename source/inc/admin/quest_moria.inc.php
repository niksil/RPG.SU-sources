<?php
if ($adm['quest'] >= 1)
{
	if (isset($_GET['edit']))
	{
		$q = mysql_fetch_array(myquery("SELECT * FROM dungeon_quests WHERE id=".$_GET['edit'].""));
		?>
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
						URL = "./ajax/admin/quest_moria.php?read="+current_quest;
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
							 el = document.getElementById("quest_res");
							 el.innerHTML = AjaxRequest.responseText;
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
						if (ids=='new')
						{
							res = document.getElementById("res_id").value;
							col = document.getElementById("new_col").value;
						}
						else
						{
							res = document.getElementById("res_id_"+ids).value;
							col = document.getElementById("col_"+ids).value;
						}
						URL = "./ajax/admin/quest_moria.php?quest="+current_quest+"&save="+res+"&col="+col;
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
		function delete_res(ids)
		{
			if(AjaxRequestWork)
			{
				try
				{
					if (AjaxRequestWork.readyState == 4 || AjaxRequestWork.readyState == 0)
					{
						URL = "./ajax/admin/quest_moria.php?delete="+ids;
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
		<br />
		<form name="quest" method="POST" action="admin.php?opt=main&option=quest_moria">
		<table border=1 cellspacing="2" cellpadding="1">
		<tr>
		<th> Квест "<?=$q['name'];?>"</th>
		</tr>
		<tr>
		<td>
			<table cellspacing="1">
			<tr>
			<td>Название квеста:</td><td><input type="text" name="questname" id="quest_name" size="50" value="<?=$q['name'];?>"></td>
			</tr>
			<tr>
			<td>Описание квеста:</td><td><textarea cols="50" rows="10" name="questdesc" id="quest_desc"><?=$q['description'];?></textarea></td>
			</tr>
			<tr>
			<td>Уровень подземелий квеста:</td><td><input type="text" name="questlevel" id="quest_level" size="5" maxsize="1" value="<?=$q['quest_level'];?>"> (от 1 до 3)</td>
			</tr>
			<tr>
			<td>Номер квеста на уровне:</td><td><input type="text" name="questid" id="quest_id" size="5" maxsize="1" value="<?=$q['quest_id'];?>"> (по порядку)</td>
			</tr>
			</table>
		</td>
		<td valign="middle">
		<input type="submit" name="submit_quest" value="Сохранить"><br />
		</td>
		</tr>
		</table>
		</form>
		<br />
		<br />
		<h4>Ресурсы для квеста:</h4>
		<br />
		<div id="quest_res"></div>
		<?
	}
	else
	{
		if (isset($_POST['submit_quest']))
		{
			myquery("INSERT INTO dungeon_quests (quest_level,quest_id,name,description) VALUES ('".$_POST['questlevel']."','".$_POST['questid']."','".$_POST['questname']."','".$_POST['questdesc']."') ON DUPLICATE KEY UPDATE quest_level='".$_POST['questlevel']."',quest_id='".$_POST['questid']."',name='".$_POST['questname']."',description='".$_POST['questdesc']."'");  
			if (isset($_POST['newquest']))
			{
				$idquest = mysql_insert_id(); 
				setLocation("admin.php?opt=main&option=quest_moria&edit=$idquest");
				die(); 
			}
		}
		if (isset($_GET['delete']))
		{
			myquery("DELETE FROM dungeon_quests WHERE id=".$_GET['delete']."");
		}
		//добавление нового квеста
		$selal = myquery("SELECT quest_level,quest_id FROM dungeon_quests ORDER BY quest_level DESC, quest_id DESC LIMIT 1");
		if (mysql_num_rows($selal)>0)
		{
			$new = mysql_fetch_array($selal);
		}
		else
		{
			$new = Array();
			$new["quest_level"]=1;
			$new["quest_id"]=0;
		}
		?>
		<br />
		<form name="new_quest" method="POST" action="">
		<table border=1 cellspacing="2" cellpadding="1">
		<tr>
		<th> Добавление нового квеста </th>
		</tr>
		<tr>
		<td>
			<table cellspacing="1">
			<tr>
			<td>Название квеста:</td><td><input type="text" name="questname" id="quest_name" size="50" value=""></td>
			</tr>
			<tr>
			<td>Описание квеста:</td><td><textarea cols="50" rows="10" name="questdesc" id="quest_desc"></textarea></td>
			</tr>
			<tr>
			<td>Уровень подземелий квеста:</td><td><input type="text" name="questlevel" id="quest_level" size="5" maxsize="1" value="<?=$new["quest_level"]?>"> (от 1 до 3)</td>
			</tr>
			<tr>
			<td>Номер квеста на уровне:</td><td><input type="text" name="questid" id="quest_id" size="5" maxsize="1" value="<? echo ($new["quest_id"]+1);?>"> (по порядку)</td>
			</tr>
			</table>
		</td>
		<td valign="middle">
		<input type="hidden" name="newquest" value="1">
		<input type="submit" name="submit_quest" value="Добавить"><br />
		</td>
		</tr>
		</table>
		</form>
		<br />
		<?
		//вывод текущих квестов
		echo '<table cellspacing=5 cellpadding=5>';
		echo '<tr><th>Уровень подземелья</th><th>Номер квеста</th><th>Название квеста</th><th>Удаление квеста</th></tr>';
		$sel = myquery("SELECT * FROM dungeon_quests ORDER BY quest_level,quest_id");
		while ($q = mysql_fetch_array($sel))
		{
			$href = $_SERVER["REQUEST_URI"];
			echo '<tr><td>'.$q['quest_level'].'</td><td>'.$q['quest_id'].'</td><td><a href="'.$href.'&edit='.$q['id'].'">'.$q['name'].'</a></td><td><a href="'.$href.'&delete='.$q['id'].'">Удалить квест</a></td></tr>';		
		}
		echo '</table>';
	}
} 
?>
