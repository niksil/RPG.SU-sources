<?php

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

include_once('inc/template.inc.php');
require_once('inc/template_header.inc.php');

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" width="100%" height="100%">
		<fieldset style="margin-left:55px;width:650px;margin-bottom:30px;padding:15px;">
			<legend><b>Форма для отправки сообщения администрации игры</b></legend>
			<p align="justify">
			Уважаемые игроки, мы (администрация игры) стараемся сделать все, чтобы игра была для вас простой, интересной и увлекательной.<br />
			Но без вашего мнения нам сложно определить где и что у нас получается не совсем удачно.<br />
			Если вам что-то непонятно в игре или вы не можете в чем-то разобраться - напишите нам жалобу и мы приложим все усилия, чтобы исправить этот непонятный вам аспект игры или сделать его более юзабельным.<br />
			Если вы хотите покинуть игру - мы хотели бы знать по какой причине и очень просим вас также написать нам об этом!<br />
			Свою жалобу вы можете написать в расположенной ниже форме<br />
			</p>
			<form name="jaloba" method="post" action="">
			Напишите текст жалобы в произвольной форме:<br />
			<textarea name="jaloba" style="width:100%;height:190px" ></textarea>
			<input type="submit" name="submit_jaloba" value="Отправить жалобу администрации игры">
			</form>
			<?
			if (isset($_POST['submit_jaloba']))
			{
				$msg = mysql_real_escape_string(htmlspecialchars($_POST['jaloba']));
				myquery("INSERT DELAYED INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('1', '".$user_id."', 'Жалоба администрации игры', '$msg','0','".time()."')");
				QuoteTable('open');
				echo '<br /><span style="color:red;font-weight:900;font-size:13px;">Спасибо! Ваше сообщение отправлено администрации игры!</span><br />';
				QuoteTable('close');
			}
			?>
		</fieldset>
		</td>
		<td valign="top" width="200">
		<table border=0 width=172 cellspacing="0" cellpadding="0">
			<tr>
				<td>
				<? include('inc/template_stats.inc.php'); ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?
if (function_exists("save_debug")) save_debug(); 

?>