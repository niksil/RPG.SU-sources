<?
//ob_start('ob_gzhandler',9);
include('inc/engine.inc.php');
require('inc/xray.inc.php');
include('inc/lib.inc.php');
include('inc/template.inc.php');
DbConnect();
include('inc/template_header.inc.php');

?>
<center><br><h3><b><font face="Tahoma"> бяе мнбнярх !</font></b></h3>
<table width="780" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="8" height="8"><img src="http://<?php echo IMG_DOMAIN; ?>/nav/2_01.jpg" width="8" height="8"></td>
		<td width="189" background="http://<?php echo IMG_DOMAIN; ?>/nav/2_02.jpg"></td>
		<td width="10"><img src="http://<?php echo IMG_DOMAIN; ?>/nav/2_04.jpg" width="8" height="8"></td>
	</tr>
	<tr>
		<td background="http://<?php echo IMG_DOMAIN; ?>/nav/2_05.jpg"></td>
		<td>
        <table style="width:100%">
<?
        if (!isset($_GET['id']))
        {
		    $news=myquery("SELECT * FROM game_news where status='0' ORDER BY id DESC");
        }
        else
        {
            $news=myquery("SELECT * FROM game_news where status='0' AND id=".((int)$_GET['id'])."");
        }
        if ($news!=false AND mysql_num_rows($news)>0)
        {
		    while($newsa=mysql_fetch_array($news))
		    {
			    echo'
                <tr>
                    <td>
                        <table width="100%" height="100%"  border="0" align="right" cellpadding="0" cellspacing="0">
			                <tr>
                                <td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_07.jpg" width="5" height="6"></td>
			                    <td width="757" background="http://'.IMG_DOMAIN.'/nav/1_09.jpg"></td>
                                <td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_10.jpg" width="7" height="6"></td>
			                </tr>
                            <tr>
                                <td width="5" background="http://'.IMG_DOMAIN.'/nav/1_17.jpg"></td>
                                <td height="100%" bgcolor="313131">
                                    <b>('.$newsa['created'].')</b><br>
                                    <span style="width:190; filter:glow(color=#333399, strength=5)">
                                    <font color="CCCCCC">'.$newsa['theme'].'</font></span><br>'.$newsa['text'].'
                                </td>
                                <td  width="5" background="http://'.IMG_DOMAIN.'/nav/1_15.jpg"></td>
                            </tr>
                            <tr>
                                <td width="5"><img src="http://'.IMG_DOMAIN.'/nav/1_19.jpg" width="5" height="8"></td>
			                    <td background="http://'.IMG_DOMAIN.'/nav/1_20.jpg"></td>
                                <td><img src="http://'.IMG_DOMAIN.'/nav/1_22.jpg" width="7" height="8"></td>
                            </tr>
                        </table>
                    </td>
                </tr>';
		    }
        }

?>		</table>
        </td>
		<td background="http://<?php echo IMG_DOMAIN; ?>/nav/2_07.jpg"></td>
	</tr>
	<tr>
		<td><img src="http://<?php echo IMG_DOMAIN; ?>/nav/2_10.jpg" width="8" height="8"></td>
		<td background="http://<?php echo IMG_DOMAIN; ?>/nav/2_11.jpg"></td>
		<td><img src="http://<?php echo IMG_DOMAIN; ?>/nav/2_13.jpg" width="8" height="8"></td>
	</tr>
</table>
</center>
</body>
</html>
<?php
mysql_close();
?>