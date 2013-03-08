<?PHP
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require_once('../inc/xray.inc.php');
require_once('../inc/lib.inc.php');
require_once('../inc/lib_session.inc.php');
require_once('quest_engine_types/inc/quest_define.inc.php');

if(isset($exit))
{
	echo '<script>top.window.frames.game.location.replace("../act.php?func=main")</script>';
	exit();
}
OpenTable('title');

if($user_id==1)
{
	$Item = new Item();
	$Item->add_user($id_item_ring_of_fire,$user_id,0);

	$q=myquery("SELECT * FROM quest_engine_users where user_id=".$user_id." AND quest_type=804");
	if(mysql_num_rows($q)>0)
	{
		$q1=mysql_fetch_array($q);
		echo '<font color=green>Граница: '.$q1["par2_value"].', ИД предмета '.$q1["par1_value"].'<br>';
		list($n)=mysql_fetch_array(myquery("SELECT ident FROM game_items WHERE id=89"));
		echo '<font color=green>А сломали мы кому-то: '.$n.' %)<br>';
		$up=myquery("UPDATE game_items SET item_uselife=(".$q1["par2_value"]."-2) WHERE user_id=".$user_id." AND item_id=".$q1["par1_value"]." LIMIT 1");
		echo '<font color=green>По идее оружие поломано, проверь!<br>';
	}
	else
		echo 'No quest';

	$q=myquery("SELECT * FROM quest_engine_owners WHERE 1");
	while ($q1=mysql_fetch_array($q))
	{
		echo '<font color=green> ID = '.$q1['id'].'	, NAME = '.$q1['name'].'<BR>';
	}
	$up=myquery("UPDATE game_npc SET npc_xpos=9,npc_ypos=9 WHERE npc_name='Ящер Смерти'");  //ЧТО ЭТО?
	echo '<br><a href="?magaz"> *Show your -=HALIAVA PARTY members ticket=- to a guard to enter the department store:)* </a><br>';
	if(isset($magaz))
	{
		echo '<br><font color=green>Our range of goods: <BR>';
		$items=(myquery("SELECT * FROM game_items_factsheet WHERE 1 ORDER BY id ASC"));
		while ($item=mysql_fetch_array($items))
		{
			 echo '<font color=yellow> id = '.$item['id'].'. '.$item['name'].'. in encyc = '.$item['view'].' <BR>';
		}
		?>
		<HR align="center" noshade size="2" width="80%">
		<form method="POST" action="?magaz">
		<INPUT type="text" maxlength="10" name="buy_id" value="enter id here">
		<INPUT type="submit" name="ok" value="FIND IT BY ID">
		</FORM>]
		<HR align="center" noshade size="2" width="80%">
		<FORM method="POST" action="?magaz&buy&name">
		<INPUT type="text" maxlength="50" name="buy_name" value="enter name here">
		<INPUT type="submit" name="ok1" value="FIND IT NAME">
		</FORM>
		<HR align="center" noshade size="2" width="80%">
		<?PHP
		if(isset($buy_id) or isset($buy_name))
		{
			if(isset($buy_id))
				$item=myquery("SELECT * FROM game_items_factsheet WHERE id=".$buy_id."");
			if(isset($buy_name))
				$item=myquery("SELECT * FROM game_items_factsheet WHERE name='".$buy_name."'");
			if(mysql_num_rows($item)==0) echo '<font color=red> There is no item like that you want.';
			else
			{
				$item=mysql_fetch_array($item);
				echo '<br><font color=green>Do you want to buy item '.$item['name'].', ID = '.$item['id'].', encyclopedy view = '.$item['view'].'<br>';
				echo '
				<form method="POST" action="?magaz&buy">
				<INPUT type="hidden" name="to_buy_id" value='.$item['id'].'>
				<INPUT type="submit" name="ok" value="BUY IT">
				</FORM>
				';
			}
		}
		if(isset($to_buy_id))
		{
			$Item = new Item();
			$Item->add_user($to_buy_id,$user_id,0);
			echo '<font color=green size=5><br>Thanks for your purchase! Come to us again!<br>';
		}
	}
	else
	{
		 $q=myquery("SELECT * FROM quest_engine_users where user_id=".$user_id." AND quest_type>800");
		 $q1=mysql_fetch_array($q);
		 echo '<font color=green>До '.strftime("%e-%m-%Y%t%T",$q1["quest_finish_time"]).'';
	}

	$q=myquery("SELECT * FROM quest_engine_users where user_id=".$user_id."");
	if(mysql_num_rows($q)>0) echo '<font color=green>You have quests';
	else echo '<font color=green>You have no quests';
	echo '<br>';
	while ($q1=mysql_fetch_array($q))
	{
		list($name)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$q1['quest_owner_id'].""));
		echo '<font color=green>Тип '.$q1["quest_type"].', НПЦ '.$name.', Пар1 '.$q1["par1_value"].', Пар2 '.$q1["par2_value"].', Пар3 '.$q1["par3_value"].', Пар4 '.$q1["par4_value"].'<br>';
	}
	echo '<br><br><br>';

	$q=myquery("SELECT * FROM quest_engine_users where user_id=".$user_id." AND quest_type>800");
	$q1=mysql_fetch_array($q);

	$items=myquery("SELECT * FROM game_shop WHERE pos_x=8 AND pos_y=24");
	$it=mysql_fetch_array($items);
	echo '<font color=orange>Торговец '.$it["name"].' - продажа: '.$it["prod"].',кольца: '.$it["ring"];

	$items=myquery("SELECT * FROM game_shop_items");
	QuoteTable('open');
	while ($it=mysql_fetch_array($items))
	{
		list($name)=mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet where id=".$it['items_id']." ORDER BY name ASC"));
		list($n,$x,$y)=mysql_fetch_array(myquery("SELECT name,pos_x,pos_y FROM game_shop where id=".$it['shop_id'].""));
		echo '<font color=orange>Предмет '.$name.' у '.$n.': '.$x.', '.$y.'.<br>';
	}
}
echo '<BR><BR><BR>';

QuoteTable('open');
echo '<font color=#F0F0F0><a href ="?exit=1"> ВЫход';
QuoteTable('close');
OpenTable('close');
?>
