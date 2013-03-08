<script language="Javascript">
function insertsmile(name)
{
	opener.document.forms["frm"].text.focus();
	opener.document.forms["frm"].text.value=opener.document.forms["frm"].text.value+" :"+name+": ";
	opener.focus();
}
</script>

<table>
  <tr>
		<td bgcolor=#00002D><b>Ты можешь использовать следующие смайлы:</b><br><center>
		<br>
		<table><tr>
		<?php
		$dh = opendir('smile/');
		$i=0;
		while($file = readdir($dh))
		{
			if ($file=='.') continue;
			if ($file=='..') continue;
			$len=strlen($file)-4;
			$smile = substr($file,0,$len);
			$img = '<td align="center" valign="middle"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick="insertsmile(\''.$smile.'\')"><img src=smile/'.$file.' border=0 alt='.$smile.'></span></td>';
			$i++;
			if ($i==8) { echo '</tr><tr>'; $i=0; };
			echo $img;
		}
		?>
		</tr></table>
		</td>
  </tr>
</table>