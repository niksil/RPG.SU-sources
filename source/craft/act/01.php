<?

if (function_exists("start_debug")) start_debug(); 

if ($build_user!=$user_id) 
{
	QuoteTable('open'); 	
	echo '<a href="?func=main&act=02">Устроиться на работу<a><br>';
	QuoteTable('close'); 
}
if ($build_user==$user_id) 
{
	QuoteTable('open'); 	
	echo'<a href="?func=main&act=03">Состояние<a>';
	echo'<br><a href="?func=main&act=04">Настройка выплат<a>';
	echo'<br><a href="?func=main&act=05">Выставить на продажу<a>';
	echo'<br><a href="?func=main&act=07">Статистика<a>';
	QuoteTable('close'); 
}
	
if ($build_sell>=1) 
{
	echo'<br><br>Это здание выставлено на продажу за '.$build_sell.' золотых!<br><a href="?func=main&act=06">Купить<a>';
}

if (function_exists("save_debug")) save_debug(); 

?>