<?PHP
require_once('inc/quest_define.inc.php');
include("inc/standart_func.lib.php");

$quest_user=mysql_fetch_array(myquery("SELECT * FROM quest_engine_users WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." "));

include("inc/standart_vars.inc.php");

echo '<TABLE bgcolor="#223344" align="center" width=100%><tr><td><center>&nbsp;';
if(!isset($quest_answer))
{	
	
    QuoteTable('open');
    echo '<TABLE bgcolor="#223344" align="center" width=100%><tr><Td bgcolor="#223344"><div align=left><font color=#BAFBB5 size=3><BR><center>Итак...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';	
    $text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=31 AND quest_type=".$quest_user['quest_type']."");
    //list($text)=mysql_fetch_array($text);
    if(mysql_num_rows($text)>0)
	{
		list($text)=mysql_fetch_array($text);
	}
	else $text = "echo 'Разговор в таверне, не хотите ли отдать посылку? В БД не найдена.';";
    eval($text);

    //	echo 'Вы вошли в таверну. В одну из тех таверен, где останавливаются моряки или усталые путники. Все помещение было наполнено едким дымом, в воздухе витал запах дешевого табака и домашнего пива. Одним словом, то еще местечко. Пытаясь убить время, ожидая нужного человека, вы взяли лежащую на стойке книгу. В аннотации было сказано: "Зубчанинов, В.Г. Разработка компьютерных ресурсосберегающих технологий по расчету и управлению процессами сложного пластического деформирования и нагружения конструкционных и строительных материалов и изделий и их автоматизированная обработка и отображение на испытательном комплексе СН-ЭВМ: отчет о НИР(заключ.). <b>В отчете представлены основные уравнения выпучивания и устойчивости пластин за пределом упргости в условиях ползучести при малых пргибах. Получена расчетная формула для критического времени, после которого пластинка не может находится в состоянии медленного квазистатического выпучивания с выполнением в каждый момент времени уравнений равновесия, и теряет устойчивость "хлопком". Рассматривается зависимось сходимости метода последовательных приближений от постановки упругопластической задачи.</b> " Внезапно из-за одного из столов встал человек по виду ничем не отличавшийся от остальных и подошел к Вам и поинтересовался, не вы ли принесли ему посылку.';	

    echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br><br>';

    echo '<font color=yellow size=4>Ответить:';	
    echo '<form action="" method="post"><input name="town_id" type="hidden" value="'.$town.'"><input name="quest_answer" type="hidden" value=1>
	<input name="answer" type="submit" value="Да, это я. *отдать пакет*" style="COLOR: #СССССС; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000"><br>
	</form></div></td></tr></table>';	
    QuoteTable('close');
}
elseif ($quest_answer==1)
{	
	$sending=myquery("SELECT id,item_uselife FROM game_items WHERE user_id='$user_id' AND item_id='$id_item_posylka' AND item_for_quest=".$quest_user['quest_owner_id']."");
	if(mysql_num_rows($sending)>0)
	{
	    if($quest_user['quest_finish_time']<time())
	    {
		    //значит, просоченo	
		    QuoteTable('open');
		    echo '<TABLE bgcolor="#223344" align="center" ><tr><Td bgcolor="#223344"><div align=left><font color=#FBB5B5 size=3><BR><center>Итак...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';
		    
		    $text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=33 AND quest_type=".$quest_user['quest_type']."");
	        //list($text)=mysql_fetch_array($text);
	        if(mysql_num_rows($text)>0)
			{
				list($text)=mysql_fetch_array($text);
			}
		else $text = "echo 'Разговор в таверне, задание просрочено. В БД не найдена.';";
	        eval($text);
		    //echo ' - <b>Вы опоздали, Мелькор вас укуси! Из-за вас я пропустил симпозиум, на котором обсуждались возможности квантовых систем передачи и преобразования информации, проиллюстрированые на примерах сверхплотного кодирования, квантовой телепортации и квантовых алгоритмов, рассматривались энтропийные и информационные характеристики квантовых систем, подробно обсуждались понятие квантового канала связи, его классическая и квантовая пропускные способности, а также передача классической информации с помощью сцепленного состояния!!!</b> - разгневанно прошипел получатель. <br><br> Но, тем не менее, он забрал у вас пакет и вручил письмо с потверждением, что он доставлен, хотя и не вовремя.';	
		    echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br>';
	        
		    myquery("UPDATE quest_engine_users SET done=2 WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." ");
	    }
	    else 
	    {
		    QuoteTable('open');
		    echo '<TABLE bgcolor="#223344" align="center" ><tr><Td bgcolor="#223344"><div align=left><font color=#BAFBB5 size=3><BR><center>Итак...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';
		    
		    $text=myquery("SELECT text FROM quest_engine_topics WHERE topic_id=".$quest_user['quest_topic_id']." AND owner_id=".$quest_user['quest_owner_id']." AND action_type=32 AND quest_type=".$quest_user['quest_type']."");
		    //list($text)=mysql_fetch_array($text);
	        if(mysql_num_rows($text)>0)
			{
				list($text)=mysql_fetch_array($text);
			}
			else $text = "echo 'Разговор в таверне, задание выполнено. В БД не найдена.';";
		    eval($text);
		    //echo ' - <b>Все отлично!</b> - сказал получатель. - <b>Это так же верно, как и то, что рибоза и дизоксирибоза никогда не встречаютсяв одном нуклеотиде, в одной молекуле нуклеиновой кислоты. Друг с другом связываются либо одни рибонуклеотиды, либо дезоксирибонуклиотиды.  Отсюда название нуклеиновых кислот – рибонуклеиновая (РНК)и дезоксирибонуклеиновая (ДНК).  Азотистые основания обладают слабо выраженными основными свойствами. В нуклеиновые кислоты входят два типа оснований. Одни из них относятся к группе пиримидинов, основу которого составляет шестичленное кольцо. Другие основания– это представители группы пуринов. У пуринов к пиримидиновому кольцу присоединеноеще пятичленное кольцо. Дезоксирибоза, соединяясь с пуриновым или пиримидиновым основанием посредством атома азота образует соединение нуклеотид. Нуклеотиды, соединяясь с одной молекулой фосфорной кислоты, образуют более сложные соединения – нуклеотиды. Таким образом, ДНК состоит из последовательно соединенных друг с другом друг дезоксирибонуклеотидов, каждый из которых содержит какое-то одно из четырех оснований – аденин,  цитозин, гуанин или тимин. Макромолекула ДНК состоит из двухцепей, протяженность которых, колеблется в широких пределах – от 77 до нескольких миллионов нуклеотидов. В ДНК входят два пурина – аденин (А) и гуанин(Г), два пиримидина и цитозин (Ц) и тимин (Т). И размеры А и Г несколько больше, чем Ц и Т!</b< <br><br> Он забрал у вас пакет и вручил письмо с потверждением, что он доставлен  вовремя.';	
		    echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br>';
	
	
		    /*myquery("DELETE FROM game_items WHERE user_id='$user_id' AND item_id=$id_posylka AND item_for_quest=".$quest_user['quest_owner_id']."");
	        $Item = new Item();
	        $Item->add_user($id_item_letter,$user_id,0,$quest_user['quest_owner_id']);*/
		    myquery("UPDATE quest_engine_users SET done=1 WHERE user_id='$user_id' AND quest_type=5 AND done=0 AND par1_value=".$char['map_name']." AND par2_value=".$town." AND par3_value=".$char['map_xpos']." AND par4_value=".$char['map_ypos']." ");
	
	    }
	    
	    //отберем посылку и дадим письмо
	    list($sending_id,$weight)=mysql_fetch_array($sending);
	    $Item = new Item();
	    $Item = new Item($sending_id);
		$Item->admindelete();
		//с весом еще
		//myquery("UPDATE game_users SET CW = CW-'$weight' WHERE user_id = ".$user_id."");
	       
	    $new_id = $Item->add_user($id_item_letter_complete,$user_id,0,$quest_user['quest_owner_id']);
	    //if($new_id[0]==1)
	    $new_id = $new_id[1];
	    //сгенерим вес
	    $weight = $weight=(mt_rand(10,30))/100;
	    myquery("UPDATE game_items SET item_uselife = '$weight' WHERE id = '$new_id'");
	    //хоп
	    myquery("UPDATE game_users SET CW = CW+'$weight' WHERE user_id = ".$char['user_id']."");
	        
	    echo '<br><br>';
	    echo '<form action="" method="post"><input name="town_id" type="hidden" value="'.$town.'"><BLOCKQUOTE><input name="quest_fin" type="submit" value="Вернуться к своим делам" style="COLOR: #СССССС; FONT-SIZE: 9pt; FONT-FAMILY: Verdana; BACKGROUND-COLOR: #000000"></BLOCKQUOTE></form></td></tr></table>';
	    QuoteTable('close');	
	}else 
	{
		QuoteTable('open');
		echo '<TABLE bgcolor="#223344" align="center" ><tr><Td bgcolor="#223344"><div align=left><font color=#FBB5B5 size=3><BR><center>Итак...</center><HR align="center" noshade size="2" width="80%"><BR><BR>';
		echo 'Но у тебя же нет посылки!';
		echo '</td></tr><tr><td bgcolor="#223344" align="center"><br><br><br><HR align="center" noshade size="2" width="80%"><br>';
	}
}
echo '</center></tr></td></table>';
 
?>