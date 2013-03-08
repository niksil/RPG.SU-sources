<?php

if (function_exists("start_debug")) start_debug(); 

if ($_GET['prison_action'] == 'oborot_go')
{
	$f=mt_rand(1,7);
	switch ($f)
	{
		case 1:
			QuoteTable('open');
	    	echo '<font color=#FFFF00>Э-эх, у-ухнем!</font>';
	    	QuoteTable('close');
	    	break;
	    case 2:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>Еще разик..</font>';
	    	QuoteTable('close');
	    	break;
	    case 3:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>Ну, навались!</font>';
	    	QuoteTable('close');
	    	break;
	    case 4:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>&quot;Шевелись, насекомое!&quot; - звонко щелкнул кнутом надсмотрщик.</font>';
	    	QuoteTable('close');
	    	break;	    		    	
		case 5:
			QuoteTable('open');
	    	echo '<font color=#FFFF00>Ползи, родимый, ползи!</font>';
	    	QuoteTable('close');
	    	break;	    	
		case 6:
			QuoteTable('open');
	    	echo '<font color=#FFFF00>На один оборот ближе к свободе!</font>';
	    	QuoteTable('close');
	    	break;	    		    	
	    case 7:
	    	QuoteTable('open');
	    	echo '<font color=#FFFF00>И-и - раз! И-и - два!</font>';
	    	QuoteTable('close');
	    	break;	    	    	
	}
}
if ($_GET['prison_action'] == 'oborot_no')
{
	QuoteTable('open');
    echo '<font color=#FFFF00>Оборот еще не закончен!</font>';
    QuoteTable('close');
}
elseif ($_GET['prison_action'] == 'oborot_go_slow')
{
	QuoteTable('open');
	echo '<font color=#FFFF00>&quot;Пошевеливайся, ты, мясо!&quot; - вскричал надсмотрщик и огрел тебя плетью.</font>';	
	QuoteTable('close');
}
elseif ($_GET['prison_action'] == 'cant_exit')
{  
	QuoteTable('open');
	list($exp)=mysql_fetch_array(myquery("SELECT EXP FROM game_users WHERE user_id='$user_id'"));      	
	$play=mysql_fetch_array(myquery("select exp_was,exp_need from game_prison where user_id='$user_id'"));        
    $ob=$play['exp_was']+$play['exp_need']-$exp;  
	echo '<font color=#FFFF00>&quot;Что, уже '.echo_sex('собрался','собралась').' на волю?&quot; - ухмыльнулся тебе в лицо надсмотрщик. - Иди работай, *ВЫРЕЗАНО ЦЕНЗУРОЙ*, у тебя еще '.$ob.' оборотов впереди!</font>';	
	QuoteTable('close');
	echo '<br><br>';
}
elseif ($_GET['prison_action'] == 'run')
{
	QuoteTable('open');
	echo '<font color=#FFFF00>Ты '.echo_sex('попробовал','попробовала').' аккуратно спуститься к морю, но кубарем '.echo_sex('полетел','полетела').' с обрыва. Надсмотрщики подобрали тебя, втащили обратно на остров, избили ногами и оставили на земле - приходить в себя. За попытку побега срок наказания увеличили на 15%.</font>';	
	QuoteTable('close');
	echo '<br><br>';
}
elseif ($_GET['prison_action'] == 'done')
{
	QuoteTable('open');
	echo '<font color=#16FF31 size=5> <B>На свободу - с чистой совестью!</B></font>';
	QuoteTable('close');
	echo '<br><br>';
}elseif ($_GET['prison_action'] == 'go_out')
{
	QuoteTable('open');
	echo '<font color=#FFFF00>Надсмотрщики оттащили тебя от Ворота и немилосердными пинками погнали к гавани. Там они хлопнули тебя по лбу печатью "Освобожден" и заботливой ногой задали тебе направление движения - к смотрителю порта.<B></B></font>';
	QuoteTable('close');
	echo '<br><br>';
}

if (function_exists("save_debug")) save_debug(); 

?>