<?
//ob_start('ob_gzhandler',9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');

if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '3');
//	define("MODULE_ID", '5');
}
else
{
	die();
}
require('../inc/lib_session.inc.php');

if (function_exists("start_debug")) start_debug(); 

$shoping=myquery("select * from game_shop where map='".$char['map_name']."' and pos_x='".$char['map_xpos']."' and pos_y='".$char['map_ypos']."' limit 1");
if(mysql_num_rows($shoping))
{
$shop=mysql_fetch_array($shoping);
?>
<HTML>
<HEAD>
<TITLE><?=$shop['name'];?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<meta name="Keywords" content="">
<style type="text/css">
<!--
body {
		background-color: #000000;
}
body,td,th {
		color: #CCCCCC;
}
-->
</style>
<style type="text/css">@import url("../style/shop.css");</style>
<style type="text/css">@import url("../style/global.css");</style>

</HEAD>
<script language="JavaScript">
function set(type){
location.href='?type='+type+''
}
</script>
<BODY LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<SCRIPT language=javascript src="../js/info.js"></SCRIPT><DIV id=hint  style="Z-INDEX: 100; LEFT: 0px; VISIBILITY: hidden; POSITION: absolute; TOP: 0px"></DIV>
<?
include("../lib/menu.php");
?>
<center>
<TABLE WIDTH=820 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_01.jpg" WIDTH=109 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_02.jpg" WIDTH=214 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_03.jpg" WIDTH=53 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_04.jpg" WIDTH=207 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_05.jpg" WIDTH=56 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_06.jpg" WIDTH=135 HEIGHT=33 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_07.jpg" WIDTH=46 HEIGHT=33 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_08.jpg" WIDTH=109 HEIGHT=57 ALT=""></TD>
				<TD>

<?
if ($shop['prod']==1)
{
	echo'<a href="?sell"><IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_09.jpg" ALT="" WIDTH=214 HEIGHT=57 border="0"></a>';
}
else
{
	echo'<IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_1_09.jpg" ALT="" WIDTH=214 HEIGHT=57 border="0">';
}
?>
</TD><TD><IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_10.jpg" WIDTH=53 HEIGHT=57 ALT=""></TD>
		  <td COLSPAN=3 ROWSPAN=3 background="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_11.jpg">

<?
if ($shop['dosp']==1)
{
	echo'<a href=shop.php?type=5><img src="http://'.IMG_DOMAIN.'/shops/swf/bron.gif" width="95" height="115" border=0></a>';
}
if ($shop['shit']==1)
{
	echo'<a href=shop.php?type=4><img src="http://'.IMG_DOMAIN.'/shops/swf/shit.gif" width="95" height="115" border=0></a>';
}
if ($shop['oruj']==1)
{
	echo'<a href=shop.php?type=1><img src="http://'.IMG_DOMAIN.'/shops/swf/weapon.gif" width="95" height="115" border=0></a>';
}
if ($shop['shlem']==1)
{
	echo'<a href=shop.php?type=6><img src="http://'.IMG_DOMAIN.'/shops/swf/shlem.gif" width="95" height="115" border=0></a>';
}
if ($shop['artef']==1)
{
	echo'<a href=shop.php?type=3><img src="http://'.IMG_DOMAIN.'/shops/swf/art.gif" width="95" height="115" border=0></a>';
}
if ($shop['ring']==1)
{
	echo'<a href=shop.php?type=2><img src="http://'.IMG_DOMAIN.'/shops/swf/ring.gif" width="95" height="115" border=0></a>';
}
if ($shop['mag']==1)
{
	echo'<a href=shop.php?type=7><img src="http://'.IMG_DOMAIN.'/shops/swf/mag.gif" width="95" height="115" border=0></a>';
}
if ($shop['svitki']==1)
{
	echo'<a href=shop.php?type=12><img src="http://'.IMG_DOMAIN.'/shops/swf/svitki.gif" width="95" height="115" border=0></a>';
}
if ($shop['pojas']==1)
{
	echo'<a href=shop.php?type=8><img src="http://'.IMG_DOMAIN.'/shops/swf/pojas.gif" width="95" height="115" border=0></a>';
}
if ($shop['amulet']==1)
{
	echo'<a href=shop.php?type=9><img src="http://'.IMG_DOMAIN.'/shops/swf/amulet.gif" width="95" height="115" border=0></a>';
}
if ($shop['perch']==1)
{
	echo'<a href=shop.php?type=10><img src="http://'.IMG_DOMAIN.'/shops/swf/perch.gif" width="95" height="115" border=0></a>';
}
if ($shop['boots']==1)
{
	echo'<a href=shop.php?type=11><img src="http://'.IMG_DOMAIN.'/shops/swf/boots.gif" width="95" height="115" border=0></a>';
}
if ($shop['eliksir']==1)
{
	echo'<a href=shop.php?type=13><img src="http://'.IMG_DOMAIN.'/shops/swf/eliksir.gif" width="95" height="115" border=0></a>';
}

if ($shop['shtan']==1)
{
	echo'<a href=shop.php?type=14><img src="http://'.IMG_DOMAIN.'/shops/swf/shtan.gif" width="95" height="115" border=0></a>';
}
if ($shop['naruchi']==1)
{
	echo'<a href=shop.php?type=15><img src="http://'.IMG_DOMAIN.'/shops/swf/naruch.gif" width="95" height="115" border=0></a>';
}
if ($shop['ukrash']==1)
{
	echo'<a href=shop.php?type=16><img src="http://'.IMG_DOMAIN.'/shops/swf/ukrash.gif" width="95" height="115" border=0></a>';
}
if ($shop['magic_books']==1)
{
	echo'<a href=shop.php?type=17><img src="http://'.IMG_DOMAIN.'/shops/swf/magic_books.gif" width="95" height="115" border=0></a>';
}
if ($shop['schema']==1)
{
	echo'<a href=shop.php?type=20><img src="http://'.IMG_DOMAIN.'/shops/swf/schema.gif" width="95" height="115" border=0></a>';
}
if ($shop['luk']==1)
{
	echo'<a href=shop.php?type=18><img src="http://'.IMG_DOMAIN.'/shops/swf/luk.gif" width="95" height="115" border=0></a>';
}
if ($shop['instrument']==1)
{
	echo'<a href=shop.php?type=24><img src="http://'.IMG_DOMAIN.'/shops/swf/instrument.gif" width="95" height="115" border=0></a>';
}
if ($shop['others']==1)
{
	echo'<a href=shop.php?type=97><img src="http://'.IMG_DOMAIN.'/shops/swf/other.gif" width="95" height="115" border=0></a>';
}
?>
</td>
<TD ROWSPAN=3><IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_12.jpg" WIDTH=46 HEIGHT=159 ALT=""></TD></TR>
<TR><TD><IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_13.jpg" WIDTH=109 HEIGHT=51 ALT=""></TD><TD>
<?
if ($shop['ident']==1)
{
	echo'<a href="?ident"><IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_14.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0"></a>';
}
elseif ($shop['kleymo']==1)
{
	echo'<a href="?kleymo"><IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_50.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0"></a>';
}
else
{
	echo'<IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_1_14.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0">';
}
?>
</TD><TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_15.jpg" WIDTH=53 HEIGHT=51 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_16.jpg" WIDTH=109 HEIGHT=51 ALT=""></TD>
				<TD>
<?
if ($shop['remont']==1)
{
	echo'<a href="?remont"><IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_17.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0"></a>';
}
else
{
	echo'<IMG SRC="http://'.IMG_DOMAIN.'/shops/shop/it_1_17.jpg" ALT="" WIDTH=214 HEIGHT=51 border="0">';
}
?>
</TD><TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_18.jpg" WIDTH=53 HEIGHT=51 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_19.jpg" WIDTH=109 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_20.jpg" WIDTH=214 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_21.jpg" WIDTH=53 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_22.jpg" WIDTH=207 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_23.jpg" WIDTH=56 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_24.jpg" WIDTH=135 HEIGHT=42 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_25.jpg" WIDTH=46 HEIGHT=42 ALT=""></TD>
	 </TR>
		 <TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_26.jpg" WIDTH=109 HEIGHT=115 ALT=""></TD>
						<TD COLSPAN=3 ROWSPAN=4 valign=top>

<?
if (isset($type))
{
	if (
		($type==1 and $shop['oruj']==1 and $shop['oruj_store_current']>0) or
		($type==5 and $shop['dosp']==1 and $shop['dosp_store_current']>0) or
		($type==4 and $shop['shit']==1 and $shop['shit_store_current']>0) or
		($type==2 and $shop['ring']==1 and $shop['ring_store_current']>0) or
		($type==8 and $shop['pojas']==1 and $shop['pojas_store_current']>0) or
		($type==6 and $shop['shlem']==1 and $shop['shlem_store_current']>0) or
		($type==7 and $shop['mag']==1 and $shop['mag_store_current']>0) or
		($type==3 and $shop['artef']==1 and $shop['artef_store_current']>0) or
		($type==9 and $shop['amulet']==1 and $shop['amulet_store_current']>0) or
		($type==10 and $shop['perch']==1 and $shop['perch_store_current']>0) or
		($type==11 and $shop['boots']==1 and $shop['boots_store_current']>0) or
		($type==13 and $shop['eliksir']==1 and $shop['eliksir_store_current']>0) or
		($type==14 and $shop['shtan']==1 and $shop['shtan_store_current']>0) or
		($type==15 and $shop['naruchi']==1 and $shop['naruchi_store_current']>0) or
		($type==16 and $shop['ukrash']==1 and $shop['ukrash_store_current']>0) or
		($type==12 and $shop['svitki']==1 and $shop['svitki_store_current']>0) or
		($type==17 and $shop['magic_books']==1 and $shop['magic_books_store_current']>0) or
		($type==18 and $shop['luk']==1 and $shop['luk_store_current']>0) or
		($type==20 and $shop['schema']==1 and $shop['schema_store_current']>0) or
		($type==24 and $shop['instrument']==1 and $shop['instrument_store_current']>0) or
		($type==97 and $shop['others']==1 and $shop['others_store_current']>0) 
   )
   {
		if (isset($buy))
		{
			$buy=(int)$buy;
			$result=@mysql_result(myquery("select count(*) from game_items_factsheet where id=$buy"),0,0);
			$result2=@mysql_result(myquery("select count(*) from game_shop_items where items_id=$buy and shop_id=".$shop['id'].""),0,0);
			if($result>0 and $result2>0)
			{
				$Item = new Item();
				$ar = $Item->buy($buy);
				if ($ar[0]>0)
				{
					if ($shop['view']==1)
					{
						$time=time();
						//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','$name','10','$cena','".$shop['id']."','$time')");
						save_stat($user_id,'','',10,$shop['id'],$ar[2],'',$ar[0],'','','','');
					}
					$ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('$user_id', '0', 'Покупка в магазине', 'Ты ".echo_sex('купил','купила')." предмет <".$ar[2]."> у торговца ".$shop['name']." за ".$ar[0].". монет','0','".time()."',5)");
					$char['GP']-=$ar[0];
					$char['CW']+=$ar[1];
					/*if ($type==1){ myquery("UPDATE game_shop SET oruj_store_current=oruj_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==5) { myquery("UPDATE game_shop SET dosp_store_current=dosp_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==4) { myquery("UPDATE game_shop SET shit_store_current=shit_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==2) { myquery("UPDATE game_shop SET ring_store_current=ring_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==8) { myquery("UPDATE game_shop SET pojas_store_current=pojas_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==6) { myquery("UPDATE game_shop SET shlem_store_current=shlem_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==7) { myquery("UPDATE game_shop SET mag_store_current=mag_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==3) { myquery("UPDATE game_shop SET artef_store_current=artef_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==9) { myquery("UPDATE game_shop SET amulet_store_current=amulet_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==10) { myquery("UPDATE game_shop SET perch_store_current=perch_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==11) { myquery("UPDATE game_shop SET boots_store_current=boots_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==12) { myquery("UPDATE game_shop SET svitki_store_current=svitki_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==13) { myquery("UPDATE game_shop SET eliksir_store_current=eliksir_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==14) { myquery("UPDATE game_shop SET shtan_store_current=shtan_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==15) { myquery("UPDATE game_shop SET naruchi_store_current=naruchi_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==16) { myquery("UPDATE game_shop SET ukrash_store_current=ukrash_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==17) { myquery("UPDATE game_shop SET magic_books_store_current=magic_books_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==18) { myquery("UPDATE game_shop SET luk_store_current=luk_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==20) { myquery("UPDATE game_shop SET schema_store_current=schema_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==24) { myquery("UPDATE game_shop SET instrument_store_current=instrument_store_current-1 WHERE id=".$shop['id']."");};
					if ($type==97) { myquery("UPDATE game_shop SET others_store_current=others_store_current-1 WHERE id=".$shop['id']."");};*/
                    
                    //Если делаем покупку в Черной Пещере - то сразу после покупки выкидываем игрока в Средиземье
                    if ($shop['map']==id_black_map)
                    {
                        myquery("UPDATE game_users_map SET map_name=18, map_xpos=".mt_rand(0,49).", map_ypos=".mt_rand(0,49)." WHERE user_id='".$char['user_id']."' LIMIT 1");
                    }
				}
		   }
	   }

	   echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';
	   echo'<font face=Verdana size=2><center>Нажмите на изображение чтобы посмотреть характеристики<br><tr bgcolor="#303A67"><td><font color=ffffff><b>Рисунок:</b></font></td><td><font color=ffffff><b>Название:</b></font></td><td><font color=ffffff><b>Цена:</b></font></td><td><font color=ffffff><b>купить:</b></font></td></tr>';

	   if (!isset($page)) $page=1;
	   $page=(int)$page;
	   $line=5;
	   $pg=myquery("SELECT COUNT(*) FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id where game_items_factsheet.type='$type' AND game_shop_items.shop_id='".$shop['id']."'");
	   $allpage=ceil(mysql_result($pg,0,0)/$line);
	   if ($page>$allpage) $page=$allpage;
	   if ($page<1) $page=1;

	   $sql = "SELECT game_items_factsheet.id AS id, game_items_factsheet.img AS img, game_items_factsheet.name AS name, game_items_factsheet.race AS race, game_items_factsheet.item_cost AS item_cost FROM game_items_factsheet JOIN game_shop_items ON game_items_factsheet.id=game_shop_items.items_id where game_items_factsheet.type='$type' AND game_shop_items.shop_id='".$shop['id']."' order by Binary game_items_factsheet.name ASC limit ".(($page-1)*$line).", $line";
	   $result=myquery($sql);
	   if ($result!=false AND mysql_num_rows($result)>0)
	   while($row=mysql_fetch_array($result))
	   {
			$Item = new Item();
			echo'<tr><td>';
			$Item->hint($row['id'],1,'<a href="http://'.DOMAIN.'/info/?item='.$row['id'].'" target="_blank" ',1); 
			echo '<img src="http://'.IMG_DOMAIN.'/item/'.$row["img"].'.gif" border="0" alt="Посмотреть характеристики"></a></td><td>'.$row["name"].'';
			if($row["race"] != 0)
			{
				echo' (Только для расы: <font color=ff0000><b>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$row['race'].""),0,0).'</b></font>)';
			}
			echo'</td><td>'.(round(($row["item_cost"]/100)*$shop["cena_prod"],2)).'</td><td><input type="button" value="Купить" onClick="location.href='."'shop.php?type=".$type."&buy=".$row["id"]."'".'"></td></tr>';
	   }
	   echo '<tr align=center><td colspan=4>';
	   $href = '?type='.$type.'&';
	   echo'<center>Страница: ';
	   show_page($page,$allpage,$href);
	   $all=mysql_result($pg,0,0);
	   echo'<br>(Всего предметов: '.$all.')</td></tr></table>';
   }
}

if (!isset($type) and !isset($buy) and !isset($sell) and !isset($ident) and !isset($kleymo) and !isset($remont))
{
	echo '<center>'.$shop['ind'].'</center>';
}

if (isset($sell))
{
    if (isset($sellitem) and is_numeric($sellitem))
    {
        if ($sellitem>0)
        {
	        $Item = new Item($sellitem);
	        $ar = $Item->sell();

	        if ($ar[0]>=0)
	        {
		        if ($shop['view']==1 AND $ar[0]>0)
		        {
			        $time=time();
			        //$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','".$items['ident']."','11','$cena','".$shop['id']."','$time')");
			        save_stat($user_id,'','',11,$shop['id'],$ar[2],'',$ar[0],'','','','');
		        }
		        myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('$user_id', '0', 'Продажа в магазине', 'Ты ".echo_sex('продал','продала')." предмет <".$ar[2]."> торговцу ".$shop['name']." за ".$ar[0]." монет','0','".time()."',5)");
		        $char['GP']+=$ar[0];
		        $char['CW']-=$ar[1];
	        }
        }
	
	    if (!isset($page)) $page=1;
	    $page=(int)$page;
	    $line=5;
		$pg=mysql_num_rows(myquery("SELECT * FROM game_items, game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 and game_items.ref_id=0 and game_items.priznak=0 AND game_items_factsheet.id=game_items.item_id AND game_items.kleymo=0"));
		$allpage=ceil($pg/$line);
	    if ($page>$allpage) $page=$allpage;
	    if ($page<1) $page=1;

	    echo'<table width="98%" cellpadding="2" cellspacing="2" border="0"><tr><td colspan=4>';
	    echo'<font size=2 face=verdana><center>Покупаю на <b>'.$shop['cena_pok'].'%</b> дешевле</font></td></tr>';
	    $result_items = myquery("SELECT game_items.id AS id, game_items.item_cost AS cena1, game_items_factsheet.oclevel, game_items_factsheet.name, game_items_factsheet.item_cost AS cena2, game_items_factsheet.img, game_items_factsheet.type, game_items_factsheet.item_uselife AS null_uselife, game_items.item_uselife,game_items.kleymo FROM game_items, game_items_factsheet WHERE game_items.user_id=$user_id AND game_items.used=0 and game_items.ref_id=0 and game_items.priznak=0 AND game_items_factsheet.id=game_items.item_id AND game_items.kleymo=0 ORDER BY binary game_items_factsheet.name limit ".(($page-1)*$line).", $line");
	    if ($result_items!=false AND mysql_num_rows($result_items)>0)
	    {
		    while($items = mysql_fetch_array($result_items))
		    {
                /*
			    $cena1=round($items['cena1']/100*$shop['cena_pok'],2);
			    $cena2=round($items['cena2']/100*$shop['cena_pok'],2);
			    $cena = min($cena1,$cena2);
                */
                $cena = round($items['cena2']/100*$shop['cena_pok'],2);
			    if ($items['kleymo']>0) $cena = 0;
				if ($items['item_uselife']<0) $items['item_uselife']=0;
			    if ($items['item_uselife']<($items['null_uselife']*0.9) AND $items['type']<90 AND $items['type']!=12 AND $items['type']!=13)
			    {
				    if ($items['null_uselife']>0)
				    {
					    $cena = round($cena*$items['item_uselife']/$items['null_uselife'],2);
				    }
				    /*
				    if ($items['item_uselife']>75) $cena=$cena*$items['oclevel']*0.5/2;
				    elseif ($items['item_uselife']>50) $cena=$cena*$items['oclevel']*0.25/2;
				    elseif ($items['item_uselife']>25) $cena=$cena*$items['oclevel']*0.2/2;
				    elseif ($items['item_uselife']>0) $cena=$cena*$items['oclevel']*0.15/2;
				    elseif ($items['item_uselife']<=0) $cena=$cena*$items['oclevel']*0.1/2;
				    */
			    }
			    echo '<tr><td>';
			    $Item = new Item($items['id']);
			    $Item->hint(0,1,'<span '); 
			    ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"middle","Продать","Продать");
			    echo '</td>
			    <td>'.$items['name'].'</td>
			    <td>'.$cena.'</td>
			    <td><input type="button" value="Продать" onClick=location.href='."'shop.php?sell&sellitem=".$items["id"]."'".'></td></tr>';
		    }
	    }
	    else
	    {
		    echo'<tr><td align=center><font size=2 face=verdana><b>В твоем инвентаре нет предметов.</td></tr>';
	    }
	    echo '<tr align=center><td colspan=4>';
	    $href = '?sell&sellitem=0&';
	    echo'<center>Страница: ';
	    show_page($page,$allpage,$href);
	    $all=$pg;
	    echo'<br>(Всего предметов в инвентаре: '.$all.')</td></tr></table>';
    }
    elseif (isset($sellres) and is_numeric($sellres))
    {
        if ($sellres>0)
        {
            //Продаем
            if (isset($sellcount) and is_numeric($sellcount))
            {
				$sellcount=(int)$sellcount;
				if ($sellcount>0)
				{
					$result_items = myquery("SELECT craft_resource.weight,craft_resource.incost cena,craft_resource.id AS res_id, craft_resource.name, craft_resource.img1, craft_resource_user.col FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id=$user_id AND craft_resource.id=craft_resource_user.res_id AND craft_resource.id=$sellres AND craft_resource_user.col>=$sellcount");
					if (mysql_num_rows($result_items))
					{
						$items = mysql_fetch_array($result_items);
						$summa = $sellcount * $items['cena'];
						if ($shop['view']==1 AND $sellcount>0)
						{
							$time=time();
							//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','".$items['ident']."','11','$cena','".$shop['id']."','$time')");
							save_stat($user_id,'','',11,$shop['id'],$items['name'],'',$summa,'','','','');
						}
						$res_weight = $sellcount*$items['weight'];
						myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time, folder) VALUES ('$user_id', '0', 'Продажа в магазине', 'Ты ".echo_sex('продал','продала')." ресурс <".$items['name']."> торговцу ".$shop['name']." в количестве ".$sellcount." за ".$summa." монет','0','".time()."',5)");
						echo'<center><b><font color=ff0000 face=verdana size=2>Продан ресурс: '.$items['name'].' '.$sellcount.' единиц за '.$summa.' монет</font></b></center>';
						if ($sellcount==$items['col'])
						{
							myquery("DELETE FROM craft_resource_user WHERE res_id=$sellres AND user_id=$user_id");
						}
						else
						{
							myquery("UPDATE craft_resource_user SET col=col-$sellcount WHERE res_id=$sellres AND user_id=$user_id");
						}
						myquery("update game_users set GP=GP+$summa,CW=CW-$res_weight where user_id=$user_id");
						setGP($user_id,$summa,8);
						$char['GP']+=$summa;
						$char['CW']-=$res_weight;
					}
				}
            }
            else
            {
                $result_items = myquery("SELECT craft_resource.id AS res_id, craft_resource.name, craft_resource.img1, craft_resource_user.col FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id=$user_id AND craft_resource.id=craft_resource_user.res_id AND craft_resource.id=$sellres");
                if (mysql_num_rows($result_items))
                {
                    $items = mysql_fetch_array($result_items);
                    echo '<center><br /><br />Цена за 1 единицу - 1 монета<br /><br /><br /><br /><form action="?sell&sellres='.$sellres.'" method="post">';
                    QuoteTable('open');
                    echo '<br />&nbsp;<br /><img align="middle" src="http://'.IMG_DOMAIN.'/item/resources/'.$items["img1"].'.gif">&nbsp;&nbsp;&nbsp;&nbsp;'.$items['name'].'&nbsp;&nbsp;&nbsp;&nbsp;Продать: <input type="text" name="sellcount" value="0" size="4" maxsize="4">&nbsp;&nbsp;из&nbsp;&nbsp;'.$items['col'].' ед. <br />&nbsp;<br />&nbsp;<br />';
                    QuoteTable('close');
                    echo '<br /><center><input type="submit" value="Продать"></form>';
                }   
            }
        }
        else
        {
            if (!isset($page)) $page=1;
            $page=(int)$page;
            $line=5;
            $pg=myquery("SELECT craft_resource.id AS res_id, craft_resource.name, craft_resource.img1, craft_resource_user.col FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id=$user_id AND craft_resource.id=craft_resource_user.res_id ORDER BY craft_resource.name");
            $allpage=ceil(mysql_num_rows($pg)/$line);
            if ($page>$allpage) $page=$allpage;
            if ($page<1) $page=1;

            echo'<table width="98%" cellpadding="2" cellspacing="2" border="0"><tr><td colspan=5>';
            $result_items = myquery("SELECT craft_resource.id AS res_id, craft_resource.name, craft_resource.incost cena, craft_resource.img1, craft_resource_user.col FROM craft_resource, craft_resource_user WHERE craft_resource_user.user_id=$user_id AND craft_resource.id=craft_resource_user.res_id ORDER BY binary craft_resource.name limit ".(($page-1)*$line).", $line");
            if ($result_items!=false AND mysql_num_rows($result_items)>0)
            {
                while($items = mysql_fetch_array($result_items))
                {
                    echo 
                    '<tr><td><br /><img src="http://'.IMG_DOMAIN.'/item/resources/'.$items["img1"].'.gif"></td>
                    <td>'.$items['name'].'</td>
                    <td>'.$items['col'].' ед.</td>
                    <td>За 1 единицу ресурса - '.$items['cena'].' монета</td>
                    <td><input type="button" value="Продать" onClick=location.href='."'shop.php?sell&sellres=".$items["res_id"]."'".'></td></tr>';
                }
            }
            else
            {
                echo'<tr><td align=center><font size=2 face=verdana><b>В твоем рюкзаке нет ресурсов.</td></tr>';
            }
            echo '<tr align=center><td colspan=5>';
            $href = '?sell&sellres=0&';
            echo'<center>Страница: ';
            show_page($page,$allpage,$href);
            $all=mysql_num_rows($pg);
            echo'<br>(Всего ресурсов: '.$all.')</td></tr></table>';
        }
    }
    else
    {
        echo '<br /><br /><br /><br /><br /><center>';
        QuoteTable('open');
        echo '<hr><br /><a href="?sell&sellitem=0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Продать предметы&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>';
        echo '<br /><br /><hr><br />';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?sell&sellres=0">Продать ресурсы</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /><br /><hr>';
        QuoteTable('close');
    }
}

if (isset($ident) AND $shop['ident']==1)
{
	$ident=(int)$ident;
	
	if ($ident>0)
	{
		$Item = new Item($ident);
		$ar = (int)$Item->identify();
		if ($ar[0]>0)
		{
			$char['GP']-=$ar[0];
			if ($shop['view']==1)
			{
				$time=time();
				//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','".$items['ident']."','12','$cena','".$shop['id']."','$time')");
				save_stat($char['user_id'],'','',12,$shop['id'],$ar[1],'',$ar[0],'','','','');
			}
		}
	}

	$result_items = myquery("SELECT game_items.id,game_items_factsheet.type,game_items_factsheet.img,game_items_factsheet.oclevel FROM game_items,game_items_factsheet WHERE game_items.user_id=".$char['user_id']." AND game_items.used=0 and game_items.ref_id=1 AND game_items.item_id=game_items_factsheet.id AND game_items.priznak=0 ORDER BY game_items_factsheet.type");
	echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';
	if(mysql_num_rows($result_items))
	{
		while($items = mysql_fetch_array($result_items))
		{
			switch ($items['type'])
			{
				case 1:
					$items['img']='unident/sword3';break;
				case 5:
					$items['img']='unident/armour3';break;
				case 3:
					$items['img']='unident/art3';break;
				case 8:
					$items['img']='unident/belt3';break;
				case 6:
					$items['img']='unident/helmet3';break;
				case 7:
					$items['img']='unident/magic3';break;
				case 2:
					$items['img']='unident/ring3';break;
				case 4:
					$items['img']='unident/shield3';break;
				case 9:
					$items['img']='unident/amulet3';break;
				case 10:
					$items['img']='unident/perch3';break;
				case 11:
					$items['img']='unident/boots3';break;
				case 13:
					$items['img']='unident/eliksir3';break;
				case 14:
					$items['img']='unident/shtan3';break;
				case 15:
					$items['img']='unident/naruchi3';break;
				case 17:
					$items['img']='unident/magic_books3';break;
				case 20:
					$items['img']='unident/schema3';break;
			}
			if ($items['oclevel']==0) $items['oclevel']=$char['clevel'];
			if ($items['oclevel']<=10) $cena = round($items['oclevel']*0.5,0);
			else $cena = $items['oclevel'];
			echo '<tr><td>';
			ImageItem($items['img'],0,0);
			echo '</td>
			<td>'.$items['type'].'</td>
			<td><input type="button" value="Идентифицировать за '.$cena.' монет" onClick=\'location.href="?ident='.$items["id"].'"\'></td></tr>';
		}
	}
	else
	{
		echo'<tr><td align=center><font size=2 face=verdana><b>У тебя нет не идентифицированных предметов</td></tr>';
	}
	echo'</table>';
}

if (isset($kleymo) AND $shop['kleymo']==1)
{
	$is_glava = false;
	if (mysql_num_rows(myquery("SELECT clan_id FROM game_clans WHERE glava=$user_id AND raz=0"))>0)
	{
		$is_glava = true;
	}
	
	$kleymo=(int)$kleymo;
	
	if ($kleymo>0 AND (isset($_GET['user']) OR isset($_GET['clan'])))
	{
		$type_kleymo = 0;
		$unset = 0;
		if (isset($_GET['del_kleymo'])) $unset = 1;
		if (isset($_GET['user']))
		{
			$type_kleymo = 2;//личное клеймение
		}
		elseif (isset($_GET['clan']) AND $is_glava)
		{
			$type_kleymo = 1;//клановое клеймение
		}
		if ($type_kleymo>0)
		{
			$Item = new Item($kleymo);
			$ar = $Item->kleymo($type_kleymo,$unset);
			if ($ar[0]>0)
			{
				$char['GP']-=$ar[0];
			}
		}
	}
	
	$result_items = myquery("SELECT game_items.id,game_items_factsheet.type,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.oclevel FROM game_items,game_items_factsheet WHERE game_items.user_id=".$char['user_id']." AND game_items.used=0 and game_items.ref_id=0 AND game_items.item_id=game_items_factsheet.id AND game_items.priznak=0 AND game_items_factsheet.personal=0 AND game_items.kleymo=0 AND game_items_factsheet.type NOT IN (12,13,19,21) ORDER BY game_items_factsheet.type, binary game_items_factsheet.name");
	$result_items_kleymo = myquery("SELECT game_items.id,game_items_factsheet.type,game_items_factsheet.name,game_items_factsheet.img,game_items_factsheet.oclevel,game_items.kleymo,game_items.kleymo_id FROM game_items,game_items_factsheet WHERE game_items.user_id=".$char['user_id']." AND game_items.used=0 and game_items.ref_id=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.personal=0 AND game_items.priznak=0 AND game_items.kleymo<>0 AND game_items_factsheet.type NOT IN (12,13,19,21) ORDER BY game_items_factsheet.type, binary game_items_factsheet.name");
	echo'<table width="98%" cellpadding="2" cellspacing="1" border="1">';
	if(mysql_num_rows($result_items)>0 OR mysql_num_rows($result_items_kleymo)>0)
	{
		$lev_us=myquery("(SELECT game_users.clevel FROM game_users WHERE user_id=$user_id) UNION (SELECT game_users_archive.clevel FROM game_users_archive WHERE user_id=$user_id) LIMIT 1");
		$lev_us=mysql_fetch_array($lev_us);
		while($items = mysql_fetch_array($result_items))
		{

			$cena = $lev_us['clevel'];
			echo '<tr><td>';
			ImageItem($items['img'],0,0);
			echo '</td>
			<td>'.$items['name'].'</td>
			<td><input type="button" style="width:270px;" value="Поставить личное клеймо за '.$cena.' монет" onClick=\'location.href="?user&kleymo='.$items["id"].'"\'>';
			if ($is_glava)
			{
				$cena = 20+$items['oclevel'];
				echo '<br /><br /><input style="width:270px;" type="button" value="Поставить клановое клеймо за '.$cena.' монет" onClick=\'location.href="?clan&kleymo='.$items["id"].'"\'>';
			}
			echo '</td></tr>';
		}
		while($items = mysql_fetch_array($result_items_kleymo))
		{
			if (($items['kleymo']==2)AND($items['kleymo_id']!=$user_id)) continue;
			if (($items['kleymo']==1)AND((!$is_glava) OR ($items['kleymo_id']!=$char['clan_id']))) continue;
			$cena = $lev_us['clevel']*2;
			echo '<tr><td>';
			ImageItem($items['img'],0,0);
			echo '</td>
			<td>'.$items['name'].'</td>
			<td>';
			if ($items['kleymo']==2) echo '<input type="button" style="width:270px;" value="Снять личное клеймо за '.$cena.' монет" onClick=\'location.href="?user&del_kleymo&kleymo='.$items["id"].'"\'>';
			if ($is_glava AND $items['kleymo']==1)
			{
				$cena = (20+$items['oclevel'])*2;
				echo '<br /><br /><input style="width:270px;" type="button" value="Снять клановое клеймо за '.$cena.' монет" onClick=\'location.href="?clan&del_kleymo&kleymo='.$items["id"].'"\'>';
			}
			echo '</td></tr>';
		}
	}
	else
	{
		echo'<tr><td align=center><font size=2 face=verdana><b>У тебя нет незаклейменных предметов</td></tr>';
	}
	echo'</table>';
}

if (isset($remont) AND $shop['remont']==1)
{
	$remont=(int)$remont;

	if (isset($remont1))
	{
		//выбрали предмет для ремонта
		if (isset($remont2))
		{
			$Item = new Item($remont);
			$ar = $Item->repair();
			if ($ar[0]>0)
			{
				if ($shop['view']==1)
				{
					$time=time();
					//$stat = myquery("INSERT DELAYED INTO game_stat (user_id,item_id,stat_id,gp,shop_id,time) VALUES ('$user_id','".$items['ident']."','15','$cena','".$shop['id']."','$time')");
					save_stat($user_id,'','',15,$shop['id'],$ar[1],'',$ar[0],'','','','');
				}
			}
		}
		else
		{
			//сначала получим подтверждение от игрока
			$it=myquery("select game_items_factsheet.name,game_items.item_uselife,game_items_factsheet.oclevel,game_items_factsheet.item_cost from game_items,game_items_factsheet where game_items.user_id='$user_id' and game_items_factsheet.type<90 and game_items_factsheet.type NOT IN (12,13,19,20,21) and game_items.item_uselife<100 and game_items.used=0 and game_items.id=$remont and game_items_factsheet.id=game_items.item_id and game_items.ref_id=0 and game_items.priznak=0");
			if (mysql_num_rows($it))
			{
				$items=mysql_fetch_array($it);

				$cena=100-$items['item_uselife'];
				if ($items['oclevel']<=0) $items['oclevel']=$char['clevel'];
				if ($items['item_uselife']>75) $cena=$cena*$items['oclevel']*0.1/2;
				elseif ($items['item_uselife']>50) $cena=$cena*$items['oclevel']*0.15/2;
				elseif ($items['item_uselife']>25) $cena=$cena*$items['oclevel']*0.2/2;
				elseif ($items['item_uselife']>0) $cena=$cena*$items['oclevel']*0.25/2;
				elseif ($items['item_uselife']<=0) $cena=$cena*$items['oclevel']*0.5/2;
				if ($cena>($items['item_cost']*0.75) and $items['item_cost']>0) $cena=$items['item_cost']*0.75;
				$cena = round($cena,2);
				if ($char['win']>$char['lose']*3)
				{
					$cena=round($cena*0.75,2);
				}
				elseif ($char['win']>$char['lose'])
				{
					$cena=round($cena*0.9,2);
				}
                if ($cena<1) $cena=1;
				$da = getdate();
				if ($da['mon']==7 AND $da['mday']==15)
				{
					$cena = 0;
				}
				if ($da['mon']==12 AND $da['mday']==31)
				{
					$cena = 0;
				}
				if ($da['mon']==1 AND $da['mday']>=1 AND $da['mday']<=7)
				{
					$cena = 0;
				}
				if ($char['clevel']<5)
				{
					$cena = 0;
				}

				echo "<center><br><font size = 2><b>Стоимость ремонта твоего предмета <br><font size = 2 color=#FF0000>&quot;$items[name]&quot;</font><br> составляет <font size = 2 color=#FF0000>$cena</font> монет</b></font>";

				if ($char['win']>$char['lose']*3)
				{
					echo "<center><br><font size = 2>Ты ВЕЛИКИЙ воин! Я даю тебе 25% скидку на ремонт, а ты всем расскажешь что я самый лучший торговец в мире!</font>";
				}
				elseif ($char['win']>$char['lose'])
				{
					echo "<center><br><font size = 2>Ты хороший воин! Я даю тебе 10% скидку на ремонт, а ты всем расскажешь что я самый лучший торговец в мире!</font>";
				}
				if ($items['item_uselife']>75)
				{
					echo '<br><br><center>Твоя вещь почти новая, точно чинить?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, так похожу</button>';
				}
				elseif ($items['item_uselife']>50)
				{
					echo '<br><br><center>Потрепано, но ничего - поправим, даешь или еще поносишь?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, буду носить пока</button>';
				}
				elseif ($items['item_uselife']>25)
				{
					echo '<br><br><center>Видно настоящего воина, тут латать и латать... недешево тебе обойдется. Так что чинить?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, я передумал</button>';
				}
				elseif ($items['item_uselife']>0)
				{
					echo '<br><br><center>Где ж это тебя так угораздило? На мою наковальню и то меньше ударов пришлось, тут работать и работать... ну что мне браться за дело?<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, не стоит</button>';
				}
				elseif ($items['item_uselife']<=0)
				{
					echo '<br><br><center>Точно хочешь чтобы я занялся этим металлоломом?<br>Рискуешь остаться без ничего!<br><br>';
					echo '<button onClick="location.href=\'?remont='.$remont.'&remont1&remont2\'">ДА, отремонтируйте</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					echo '<button onClick="location.href=\'?remont\'">НЕТ, я лучше его выкину</button>';
				}
			}
		}
	}
	else
	{
		echo'<table width="98%" cellpadding="2" cellspacing="2" border="0">';

		$it_all=myquery("select game_items.id,game_items_factsheet.img,game_items_factsheet.name,game_items.item_uselife,game_items_factsheet.oclevel,game_items_factsheet.item_cost from game_items,game_items_factsheet where game_items.user_id='$user_id' and game_items_factsheet.type NOT IN (12,13,19,20,21) and game_items_factsheet.type<90 and game_items.item_uselife<100 and game_items.used=0 and game_items_factsheet.id=game_items.item_id and game_items.priznak=0 and game_items.ref_id=0 Order by binary game_items_factsheet.name");
		if(mysql_num_rows($it_all))
		{
			while($items = mysql_fetch_array($it_all))
			{
				echo '<tr><td>';
				$Item = new Item($items['id']);
				$Item->hint(0,1,'<span '); 
				ImageItem($Item->fact['img'],0,$Item->item['kleymo'],"middle","Ремонтировать","Ремонтировать");
				echo '</td>
				<td ';
				if ($items['item_uselife']==0) echo ' bgcolor=#800000';
				echo'>'.$items['name'].' - Прочность '.$items['item_uselife'].'%';
				echo'</td>
				<td><input type="button" value="Ремонт" onClick=\'location.href="?remont1&remont='.$items["id"].'"\'></td></tr>';
			}
		}
		else
		{
			echo'<tr><td><center><font face=verdana size=2><b>У тебя нет сломаных предметов, или ты их не '.echo_sex('снял','сняла').' с себя</b></td></tr>';
		}
		echo'</table>';
	}
}
?>
</TD>
<TD><IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_28.jpg" WIDTH=56 HEIGHT=115 ALT=""></TD>

<TD valign="top"><div align="center"><img src="http://<?php echo IMG_DOMAIN; ?>/shop/<? echo $shop['name_img']; ?>.gif"><br><font face=verdana size=1><? echo $shop['name']; ?></font></div></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_30.jpg" WIDTH=46 HEIGHT=115 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_31.jpg" WIDTH=109 HEIGHT=51 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_32.jpg" WIDTH=56 HEIGHT=51 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_33.jpg" WIDTH=135 HEIGHT=51 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_34.jpg" WIDTH=46 HEIGHT=51 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_35.jpg" WIDTH=109 HEIGHT=68 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_36.jpg" WIDTH=56 HEIGHT=68 ALT=""></TD>
  <TD valign="middle"><div align="center">Вес: <b><? echo $char['CW'].' / '.$char['CC']; ?></b><br><b><img src="http://<?php echo IMG_DOMAIN; ?>/nav/gold.gif"><font color=ff0000>
				  <? echo $char['GP']; ?></b></font> золотых</div></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_38.jpg" WIDTH=46 HEIGHT=68 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_39.jpg" WIDTH=109 HEIGHT=145 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_40.jpg" WIDTH=56 HEIGHT=145 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_41.jpg" WIDTH=135 HEIGHT=145 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_42.jpg" WIDTH=46 HEIGHT=145 ALT=""></TD>
		</TR>
		<TR>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_43.jpg" WIDTH=109 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_44.jpg" WIDTH=214 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_45.jpg" WIDTH=53 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_46.jpg" WIDTH=207 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_47.jpg" WIDTH=56 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_48.jpg" WIDTH=135 HEIGHT=69 ALT=""></TD>
				<TD>
						<IMG SRC="http://<?php echo IMG_DOMAIN; ?>/shops/shop/it_49.jpg" WIDTH=46 HEIGHT=69 ALT=""></TD>
		</TR>
</TABLE>
<?
	set_delay_reason_id($user_id,3);
	ForceFunc($user_id,3);
}
else
{
	echo'На этой гексе нет торговцев';
}
if ($_SERVER['REMOTE_ADDR']==DEBUG_IP)
{
	show_debug();
}
?>
</BODY>
</HTML>
<?
mysql_close();

if (function_exists("save_debug")) save_debug(); 

?>