<style type="text/css">
ul.bb-list-unordered { }
ol.bb-list-ordered { }
ol.bb-list-ordered-d { list-style-type:decimal; }
ol.bb-list-ordered-lr { list-style-type:lower-roman; }
ol.bb-list-ordered-ur { list-style-type:upper-roman; }
ol.bb-list-ordered-la { list-style-type:lower-alpha; }
ol.bb-list-ordered-ua { list-style-type:upper-alpha; }
ol.bb-listitem { }

.bb-code,
.bb-php {
  border: 1px solid black;
  padding: 10px;
  font-family: Courier;
  white-space: nowrap;
}

.bb-email { }
.bb-url { }

.bb-image {
  border-width: 0;
  border-style: none;
}
BODY
{
	scrollbar-face-color: #620706;
	scrollbar-shadow-color: #340403;
	scrollbar-highlight-color: #340403;
	scrollbar-3dlight-color: #620706;
	scrollbar-darkshadow-color: #620706;
	scrollbar-track-color: #1D1D1D;
	scrollbar-arrow-color: #FBF891;
	padding: 0;
	margin: 0;
	font-weight : normal;
	font-size : 12px;
	font-family : Verdana, tahoma, arial, helvetica, sans-serif;
	background : #000000;
	margin : 0;
	color : #D4D4D4;
}
</style>

<script language="Javascript">
function insertsmile(name)
{
	document.frm.text.focus();
	document.frm.text.value=document.frm.text.value+" :"+name+": ";
}
</script>
<?php
function pokazat_formu_otveta($nazv_knopki,$new_tema,$text,$nastr='',$music='',$closed=0,$no_comment=0)
{
	global $user_id;
	if ($user_id==0) return;
?>
<a name="anchor1">
<form name="frm" action="" method="post">
<table width="100%">
	<tr>
		<td style="width:145px;">&nbsp;</td>
		<td><a href="#" onmouseover="copyQ();" onclick="pasteQ();">Выделите текст и нажмите сюда для цитирования</a></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td style="width:145px;"></td>
		<td style="width:35px;"><input type="button" class="button" accesskey="b" name="addbbcode0" value="B" style="font-weight:bold; width: 30px" onClick="bbstyle(0)" /></td>
		<td style="width:35px;"><span class="genmed"><input type="button" class="button" accesskey="i" name="addbbcode2" value="i" style="font-style:italic; width: 30px" onClick="bbstyle(2)" /></span></td>
		<td style="width:35px;"><span class="genmed"><input type="button" class="button" accesskey="u" name="addbbcode4" value="u" style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" /></span></td>
		<td style="width:35px;"><span class="genmed"><input type="button" class="button" accesskey="s" name="addbbcode22" value="S" style="text-decoration: line-through; width: 30px" onClick="bbstyle(22)" /></span></td>
		<td style="width:55px;"><span class="genmed"><input type="button" class="button" accesskey="c" name="addbbcode18" value="center" style="width: 50px" onClick="bbstyle(18)" /></span></td>
		<td style="width:55px;"><span class="genmed"><input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" /></span></td>
		<td style="width:45px;"><span class="genmed"><input type="button" class="button" accesskey="c" name="addbbcode8" value="Code" style="width: 40px" onClick="bbstyle(8)" /></span></td>
		<td style="width:45px;"><span class="genmed"><input type="button" class="button" accesskey="l" name="addbbcode10" value="List" style="width: 40px" onClick="bbstyle(10)" /></span></td>
		<td style="width:45px;"><span class="genmed"><input type="button" class="button" accesskey="o" name="addbbcode12" value="List=" style="width: 40px" onClick="bbstyle(12)" /></span></td>
		<td style="width:45px;"><span class="genmed"><input type="button" class="button" accesskey="p" name="addbbcode14" value="Img" style="width: 40px"  onClick="bbstyle(14)" /></span></td>
		<td style="width:55px;"><span class="genmed"><input type="button" class="button" accesskey="e" name="addbbcode20" value="email" style="text-decoration: underline; width: 50px" onClick="bbstyle(20)" /></span></td>
		<td style="width:45px;"><span class="genmed"><input type="button" class="button" accesskey="u" name="addbbcode14" value="Cut" style="width: 40px"  onClick="bbstyle(24)" /></span></td>
		<td></td>
	</tr>
	<tr>
		<td style="width:145px;"></td>
		<td colspan="11">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td nowrap="nowrap">
						<span class="genmed">&nbsp;Шрифт:
						<select name="addbbcode26" onChange="bbfontstyle('[font=' + this.form.addbbcode26.options[this.form.addbbcode26.selectedIndex].value + ']', '[/font]')">
						<option value="arial" class="genmed">arial</option>
						<option value="tahoma" class="genmed">tahoma</option>
						<option value="verdana" selected class="genmed">verdana</option>
						<option value="times" class="genmed">times</option>
						<option  value="courier" class="genmed">courier</option>
						</select>
						&nbsp;Размер:
						<select name="addbbcode24" onChange="bbfontstyle('[size=' + this.form.addbbcode24.options[this.form.addbbcode24.selectedIndex].value + ']', '[/size]')">
						<option value="7" class="genmed">Tiny</option>
						<option value="9" class="genmed">Small</option>
						<option value="12" selected class="genmed">Normal</option>
						<option value="18" class="genmed">Large</option>
						<option value="24" class="genmed">Huge</option>
						</select>
						&nbsp;Цвет:
						<select name="addbbcode28" onChange="bbfontstyle('[color=' + this.form.addbbcode28.options[this.form.addbbcode28.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;">
						<option style="color:lightgrey; background-color: black" value="#444444" class="genmed">Default</option>
						<option style="color:darkred; background-color: black" value="darkred" class="genmed">Dark Red</option>
						<option style="color:red; background-color: black" value="red" class="genmed">Red</option>
						<option style="color:orange; background-color: black" value="orange" class="genmed">Orange</option>
						<option style="color:brown; background-color: black" value="brown" class="genmed">Brown</option>
						<option style="color:yellow; background-color: black" value="yellow" class="genmed">Yellow</option>
						<option style="color:green; background-color: black" value="green" class="genmed">Green</option>
						<option style="color:olive; background-color: black" value="olive" class="genmed">Olive</option>
						<option style="color:cyan; background-color: black" value="cyan" class="genmed">Cyan</option>
						<option style="color:blue; background-color: black" value="blue" class="genmed">Blue</option>
						<option style="color:darkblue; background-color: black" value="darkblue" class="genmed">Dark Blue</option>
						<option style="color:indigo; background-color: black" value="indigo" class="genmed">Indigo</option>
						<option style="color:violet; background-color: black" value="violet" class="genmed">Violet</option>
						<option style="color:white; background-color: black" value="white" class="genmed">White</option>
						</select>
						</span>
					</td>
					<td nowrap="nowrap" align="right"><span class="gensmall"><a href="javascript:bbstyle(-1)" class="genmed">Закрыть тэги</a></span></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" border="0">
<?php
if ($new_tema==1)
{
echo'
	<tr>
		<td><span style="width:145px;"></span>Тема: <input style="width:100%;" maxlength="255" name="top"></td>
	</tr>
';
}
?>
	<tr>
		<td style="width:100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td style="width:145px;">
						<table>
							<tr>
							<?php
							$dh = opendir('smile/');
							$i=0;
							while($file = readdir($dh))
							{
								if ($file=='.') continue;
								if ($file=='..') continue;
								$len=strlen($file)-4;
								$smile = substr($file,0,$len);
								$img = '<td height=35 align="center" valign="middle"><span style="cursor:url(\'/images/nav/hand.cur\'), pointer" onClick="insertsmile(\''.$smile.'\')"><img src=smile/'.$file.' border=0 alt='.$smile.'></span></td>';
								$i++;
								if ($i==3) { echo '</tr><tr>'; $i=0; };
								echo $img;
							}
							?>
							</tr>
						</table>
					</td>
					<td>
						<textarea style="width:100%;height:expression(this.scrollHeight+4+'px');min-height:120px" name="text" rows="35" wrap="virtual" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"><?php if ($new_tema==2) echo $text; ?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<?php 
		if ($new_tema!=3)
		{
			echo'
			<input type=checkbox name=closed value=yes ';if ($closed!=0) echo 'checked'; echo'>&nbsp;Разрешить просмотр <strong>только друзьям</strong><br>
			<input type=checkbox name=no_comment value=yes ';if ($no_comment!=0) echo 'checked'; echo'>&nbsp;<strong>Запретить</strong> комментарии<br><br>
			<input type=text name=nastroy size=80 value="'.$nastr.'">&nbsp;Настроение<br>
			<input type=text name=music size=80 value="'.$music.'">&nbsp;Слушаю музыку<br>
			';
		}
		?>
		</td>
	</tr>
</table>
<br /><br />
<input type="submit" value="<?php echo $nazv_knopki; ?>">
</form>
<?php
}

function convert_in_tags($text, $cut = '')
{
  $preg = array(
		  '/(?<!\\\\)\[color(?::\w+)?=(.*?)\](.*?)\[\/color(?::\w+)?\]/si'   => "<span style=\"color:\\1\">\\2</span>",
		  '/(?<!\\\\)\[size(?::\w+)?=(.*?)\](.*?)\[\/size(?::\w+)?\]/si'     => "<span style=\"font-size:\\1\">\\2</span>",
		  '/(?<!\\\\)\[font(?::\w+)?=(.*?)\](.*?)\[\/font(?::\w+)?\]/si'     => "<span style=\"font-family:\\1\">\\2</span>",
		  '/(?<!\\\\)\[align(?::\w+)?=(.*?)\](.*?)\[\/align(?::\w+)?\]/si'   => "<div style=\"text-align:\\1\">\\2</div>",
		  '/(?<!\\\\)\[b(?::\w+)?\](.*?)\[\/b(?::\w+)?\]/si'                 => "<span style=\"font-weight:bold\">\\1</span>",
          '/(?<!\\\\)\[s(?::\w+)?\](.*?)\[\/s(?::\w+)?\]/si'                 => "<span style=\"text-decoration:line-through\">\\1</span>",
		  '/(?<!\\\\)\[i(?::\w+)?\](.*?)\[\/i(?::\w+)?\]/si'                 => "<span style=\"font-style:italic\">\\1</span>",
		  '/(?<!\\\\)\[u(?::\w+)?\](.*?)\[\/u(?::\w+)?\]/si'                 => "<span style=\"text-decoration:underline\">\\1</span>",
		  '/(?<!\\\\)\[center(?::\w+)?\](.*?)\[\/center(?::\w+)?\]/si'       => "<div style=\"text-align:center\">\\1</div>",
          
		  // [code]
		  '/(?<!\\\\)\[code(?::\w+)?\](.*?)\[\/code(?::\w+)?\]/si'           => "<div class=\"bb-code\">\\1</div>",
		  // [email]
		  '/(?<!\\\\)\[email(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si'         => "<a href=\"mailto:\\1\" class=\"bb-email\">\\1</a>",
		  '/(?<!\\\\)\[email(?::\w+)?=(.*?)\](.*?)\[\/email(?::\w+)?\]/si'   => "<a href=\"mailto:\\1\" class=\"bb-email\">\\2</a>",
		  // [url]
		  '/(?<!\\\\)\[url(?::\w+)?\]www\.(.*?)\[\/url(?::\w+)?\]/si'        => "<a href=\"http://www.\\1\" target=\"_blank\" class=\"bb-url\">\\1</a>",
		  '/(?<!\\\\)\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'             => "<a href=\"\\1\" target=\"_blank\" class=\"bb-url\">\\1</a>",
		  '/(?<!\\\\)\[url(?::\w+)?=(.*?)?\](.*?)\[\/url(?::\w+)?\]/si'      => "<a href=\"\\1\" target=\"_blank\" class=\"bb-url\">\\2</a>",
		  // [img]
		  '/(?<!\\\\)\[img(?::\w+)?\](.*?)\[\/img(?::\w+)?\]/si'             => "<img src=\"\\1\" alt=\"\\1\" class=\"bb-image\" />",
		  '/(?<!\\\\)\[img(?::\w+)?=(.*?)x(.*?)\](.*?)\[\/img(?::\w+)?\]/si' => "<img width=\"\\1\" height=\"\\2\" src=\"\\3\" alt=\"\\3\" class=\"bb-image\" />",
		  // [quote]
		  '/(?<!\\\\)\[quote(?::\w+)?\](.*?)\[\/quote(?::\w+)?\]/si'         => "<table width=\"100%\" border=\"1\" bordercolor=\"444444\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#000000\"><tr><td valign=\"top\">\\1</td></tr></table>",
		  '/(?<!\\\\)\[quote(?::\w+)?=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\](.*?)\[\/quote\]/si'   => "<table width=\"100%\" border=\"1\" bordercolor=\"444444\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#000000\"><tr><td valign=\"top\">\\2</td></tr></table>",
		  // [list]
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\*(?::\w+)?\](.*?)(?=(?:\s*<br\s*\/?>\s*)?\[\*|(?:\s*<br\s*\/?>\s*)?\[\/?list)/si' => "\n<li class=\"bb-listitem\">\\1</li>",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list(:(?!u|o)\w+)?\](?:<br\s*\/?>)?/si'    => "\n</ul>",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:u(:\w+)?\](?:<br\s*\/?>)?/si'         => "\n</ul>",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:o(:\w+)?\](?:<br\s*\/?>)?/si'         => "\n</ol>",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(:(?!u|o)\w+)?\]\s*(?:<br\s*\/?>)?/si'   => "\n<ul class=\"bb-list-unordered\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:u(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "\n<ul class=\"bb-list-unordered\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:o(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "\n<ol class=\"bb-list-ordered\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=1\]\s*(?:<br\s*\/?>)?/si' => "\n<ol class=\"bb-list-ordered,bb-list-ordered-d\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=i\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-lr\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=I\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-ur\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=a\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-la\">",
		  '/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=A\]\s*(?:<br\s*\/?>)?/s'  => "\n<ol class=\"bb-list-ordered,bb-list-ordered-ua\">",
		  // escaped tags like \[b], \[color], \[url], ...
		  '/\\\\(\[\/?\w+(?::\w+)*\])/'                                      => "\\1"

  );

 // [cut]
 // TODO: Add cut with link name
  if ($cut == '')
  {
    $preg['/(?<!\\\\)\[cut(?::\w+)?\](.*?)\[\/cut(?::\w+)?\]/si'] = '\\1';
    $preg['/(?<!\\\\)\[cut(?::\w+)?=(.*?)\](.*?)\[\/cut(?::\w+)?\]/si']  = "\\2";
  }
  else
  {
    $preg['/(?<!\\\\)\[cut(?::\w+)?\](.*?)\[\/cut(?::\w+)?\]/si'] = "<a href=\"$cut\">Читать дальше...</a>";
    $preg['/(?<!\\\\)\[cut(?::\w+)?=(.*?)\](.*?)\[\/cut(?::\w+)?\]/si']  = "<a href=\"$cut\">\\1</a>";
  }
    
  
  $text = preg_replace(array_keys($preg), array_values($preg), $text);

	if (strpos($text,':')!==false)
	{
		$dh = opendir('smile/');
		while($file = readdir($dh))
		{
			if ($file=='.') continue;
			if ($file=='..') continue;
			$len=strlen($file)-4;
            if (substr($file,0,$len) == '') continue;
			$filesmile = ':'.substr($file,0,$len).':';
			$text = str_replace($filesmile,"<img src=smile/$file border=0>",$text);
		}
	}
  return $text;
}


echo'<script language="Javascript" src="bbcodes.js"></script>';
?>