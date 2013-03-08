<?PHP

$quests=myquery("SELECT quest_type,quest_topic_id,quest_owner_id FROM quest_engine_users WHERE user_id='$user_id' AND quest_type>600 AND quest_type<700 AND par1_value=".$char['map_name']." AND par2_value=".$char['map_xpos']." AND  par3_value=".$char['map_ypos']." AND done!=1 AND done!=2");
if(mysql_num_rows($quests))
{
	QuoteTable('open');
	$quest_user=mysql_fetch_array($quests);
	//list($owner)=mysql_fetch_array(myquery("SELECT name FROM quest_engine_owners WHERE id=".$cquest['quest_owner_id'].""));
	//echo '<font color=#F0F0F0>!';
	$text=myquery("SELECT text FROM quest_engine_topics WHERE quest_type=".$quest_user['quest_type']." AND topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=34");
	list($text)=mysql_fetch_array($text);
	//echo $text;
	eval($text);
	/*switch ($quest_user['quest_type'])
	{
		case 601:
			$mode='sudoku';
			break;
	}*/
	$mode=$quest_user['quest_type'];
	echo '<br><div align=right><a href="quest/quests_engine_chek.php?mode='.$mode.'" target="game"><font size=4>Приступить</a></font></div>';
	QuoteTable('close');
}
	// Тестовый код!
    //Покажем Квестовиков на гексе
 /*$select=myquery("select * from quest_engine_owners where map_name='".$char['map_name']."' and map_xpos='".$char['map_xpos']."' and map_ypos='".$char['map_ypos']."' ");
    if (mysql_num_rows($select)>0)
    {
        while ($shop=mysql_fetch_array($select))
        {
            echo "<br><b><font color=#cccccc>".$shop['about']."</font></b><br>";
//            echo $shop['privet'];
		    echo '<br><div align=right><a href="quest/quests_engine_chek.php">'.$shop["enter"].'</a></div>';
	    }
    }*/

	// Тестовый код!
	
?>