<?php

if (function_exists("start_debug")) start_debug(); 

if ($adm['news'] >= 1)
{
    include_once('style/tinyMCE/tinyMCE_header.php');

    if (!isset($_POST['save']))
    {
        $result=myquery("select * from game_news where id='$id' LIMIT 1");
        $edit=mysql_fetch_array($result);
        echo'<form action="" method="post">
        <table width=100% border="0">
        <tr><td>Тема</td><td><input name="theme" type="text" value="'.$edit['theme'].'" size="50" maxlength="50"></td></tr>';

        echo '<tr><td valign=top>Текст:</td><td>';
        ?>
        <textarea id="elm1" name="elm1" rows="25" cols="80" style="width: 100%">
        <?
        echo $edit['text'];
        ?>
        </textarea>
        <?

        echo'<tr><td colspan="2" align="center"><input name="save" type="submit" value="Отредактировать"></td></tr>
        </table>
        </form>';
    }
    else
    {
        if ( isset( $_POST ) )
           $postArray = &$_POST ;            // 4.1.0 or later, use $_POST
        else
           $postArray = &$HTTP_POST_VARS ;    // prior to 4.1.0, use HTTP_POST_VARS

        $i=0;
        foreach ( $postArray as $sForm => $value )
        {
            $i++;
            if($i==2)
            {
                //$say = iconv("Windows-1251","UTF-8//IGNORE","Служебное: изменена новость <a href=\"http://".DOMAIN."/news.php?id=".$id."\" target=\"_blank\">".$theme."</a><br /><br />[pre]".$value."[/pre]");
                //myquery("INSERT INTO game_log (`message`,`date`,`fromm`) VALUES ('".$say."',".time().",-1)");
                $result=myquery("update game_news set theme='$theme',text='$value' where id='$id'");
                echo"<center>Новость изменена</center>";
                $da = getdate();
                $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year)
                 VALUES (
                 '".$char['name']."',
                 'Отредактировал новость: <b>".$theme."</b><br> новость - : ".$value."',
                 '".time()."',
                 '".$da['mday']."',
                 '".$da['mon']."',
                 '".$da['year']."')")
                     or die(mysql_error());
            }
        }
        echo '<meta http-equiv="refresh" content="1;url=?opt=main&option=news">';
    }
}

if (function_exists("save_debug")) save_debug(); 

?>