<?

if (function_exists("start_debug")) start_debug(); 

require('arcomage/inc/template_header.inc.php');
echo'
<table width="100%" height="550" border="0" align="center" cellpadding="0" cellspacing="0">
<tr><td width="50%" height="550" valign="top" bgcolor="#000000">';

include("arcomage/inc/left.inc.php");

echo'</td><td height="550" width="640" align="center" valign="top" bgcolor="#000000">';

echo '
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr bgcolor="#333333">
		<td><div align="center">До конца хода осталось: <font color=ff0000><b><span id="timerr1">'.($arcomage['timehod']-time()+$timeout).'</span></b></font> секунд</div>
		<script language="JavaScript" type="text/javascript">
		function tim()
		{
			timer = document.getElementById("timerr1");
			if (timer.innerHTML<=0)
				location.replace("arcomage.php");
			else
			{
				timer.innerHTML=timer.innerHTML-1;
				window.setTimeout("tim()",1000);
			}
		}
		tim();
		</script>
		</td>
	</tr>
	<tr>
		<td width=640>
		<div style="position:relative;">
		<img src="http://'.IMG_DOMAIN.'/arcomage/layout.jpg" width=640 height=480>';

		echo '<span style="width:640; position:absolute; left:0; top:10; "><center><font color=#FFFFFF face="Verdana" size=2><b>Ход игры: <font color=#FF0000>'.$arcomage['hod'].'</font></b></font><br><font color=#FFFFFF face="Verdana">Условия победы: <br>1) уничтожить башню противника; <br>2) построить свою башню до <font color=#FF0000>'.$arcomage['tower_win'].'</font> единиц; <br>3) накопить любого ресурса до <font color=#FF0000>'.$arcomage['resource_win'].'</font> единиц</font>';
		if ($arcomage['money']>0) echo '<br>Ставка на игру: <font color=#FF0000><b>'.$arcomage['money'].'</b></font> монет.';
		echo'</span>';
		
		?>
		<script language="JavaScript" type="text/javascript">
		function put_card(IMG_DOMAIN,card,k,alt_card,left,top,deltaY,deltaX,variant)
		{
			if (navigator.appName=="Netscape")
			{
				top=top-deltaY;
			}
			if (window.opera)
			{
				if (variant!=5)
				{
					top=top-130;
				}
			}
			if (document.all)
			{
				if (variant>0)
				{
					top=top-130;
					left=left-310;
				}
			}
			document.write('<div style="width:94px;display:inline;border:solid;border-width:1px;position:absolute; left:'+(left+k*deltaX)+'px; top:'+top+'px;">');
			if (variant==2)
			{
				document.write('<a href="?card='+card+'" style="width:94px;height:126px;"><span onMouseOver="this.style.background=\'black\';this.style.cursor=\'http://'+IMG_DOMAIN+'/nav/hand.cur\', pointer;" onMouseOut="this.style.background=\'\';this.style.cursor=\'\';">');
			}
			if (variant==1)
			{
				document.write('<span onMouseOver="this.style.cursor=\'http://'+IMG_DOMAIN+'/arcomage/not.ico\';" onMouseOut="this.style.cursor=\'\';" >');
			} 
			document.write('<img border="0" width="94" height="126" src="http://'+IMG_DOMAIN+'/arcomage/card'+card+'.jpg" alt="'+alt_card+'" title="Карта №'+card+'">'); 
			if (variant==2)
			{
				
				document.write('<br><button onclick="location.href=\'?card='+card+'\'" style="width:94px;font-size:9px;">Ход картой</button>');
				document.write('</span></a>');
			}  
			if (variant==1)
			{
				document.write('</span>');
			}  
			document.write('</div>');
		}
		</script>
		<?php 
		
		//покажем карты прошлого хода
		if ($arcomage['hod']>1)
		{
			echo'<span style="width:640; position:absolute; left:0; top:120; "><center><font color=#FFFFFF><b>Карты предыдущего хода</b></span>';
			$sel_cards = myquery("SELECT card_id FROM arcomage_history WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id='$user_id' AND hod='".($arcomage['hod']-1)."' AND fall=0");
			$k=0;
			while (list($card) = mysql_fetch_array($sel_cards))
			{
				?><script>put_card("<?=IMG_DOMAIN;?>",<?=$card;?>,<?=$k;?>,"<?=htmlspecialchars(alt_card($card));?>",5,150,120,100);</script><?php
				$k++;
			}
			$sel_cards = myquery("SELECT card_id FROM arcomage_history WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id<>'$user_id' AND hod='".($arcomage['hod']-1)."' AND fall=0");
			$k=0;
			while (list($card) = mysql_fetch_array($sel_cards))
			{
				?><script>put_card("<?=IMG_DOMAIN;?>",<?=$card;?>,<?=$k;?>,"<?=htmlspecialchars(alt_card($card));?>",540,150,120,-100);</script><?php
				$k++;
			}
		}

		//покажем сходившие карты
		$sel_cards = myquery("SELECT card_id FROM arcomage_history WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id='$user_id' AND hod='".$arcomage['hod']."' AND fall=0");
		if (mysql_num_rows($sel_cards))
		{
			$k=0;
			while (list($card) = mysql_fetch_array($sel_cards))
			{
				?><script>put_card("<?=IMG_DOMAIN;?>",<?=$card;?>,<?=$k;?>,"<?=htmlspecialchars(alt_card($card));?>",5,5,120,120,5);</script><?php 
				$k++;
			}
		}

		?>
		<script language="JavaScript" type="text/javascript">
		function put_fall(k,left,top,deltaY,deltaX,s,card)
		{
			if (navigator.appName=="Netscape")
			{
				top=top-deltaY;
			}
			if (document.all)
			{
				top=top-130;
				left=left-313;
			} 
			if (window.opera)
			{
				top=top-130;
			}
			document.write('<span style="position:absolute; left:'+(left+k*deltaX)+'; top:'+top+'; ">');
		}
		</script>
		<?php 
		echo '</div><div style="position:absolute;">';
		//покажем карты на руках
		$cards = myquery("SELECT card_id FROM arcomage_users_cards WHERE user_id='$user_id' AND arcomage_id='".$charboy['arcomage_id']."' LIMIT 5");
		$k=0;
		while (list($card) = mysql_fetch_array($cards))
		{
			$dostup = check_dostup($card,$charboy);
			$dostup++;
			?><script>put_card("<?=IMG_DOMAIN;?>",<?=$card;?>,<?=$k;?>,"<?=htmlspecialchars(alt_card($card));?>",25,0,120,120,<?=$dostup;?>);</script><?php 
			if ($card!=55)
			{
				?><script>put_fall(<?=$k;?>,35,-30,120,120);</script><?php
				echo '<input type="button" name="fall'.$k.'" value="Сбросить" onClick=location.href="?card='.$card.'&fall"></span>';
			}

			$k++;
		}
		echo'</div>
		</td>
	</tr>
</table>
</td>
<td width="50%" height="550" valign="top" bgcolor="#000000">';

include("arcomage/inc/right.inc.php");
echo'</td>
</tr></table>';

if (function_exists("save_debug")) save_debug(); 

?>