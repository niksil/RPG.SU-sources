<?

if (function_exists("start_debug")) start_debug(); 

if ($adm['users'] > 1)
{
    if (!isset($_POST['user_name']))
    {
        echo '<div id="content" onclick="hideSuggestions();">
        <form action="" method="post">
        <font size="1" face="Verdana" color="#ffffff">Зайти в игру под игроком: 
        <input name="name_v" type="text" size="25" id="keyword" onkeyup="handleKeyUp(event)"><div style="display:none;" id="scroll"><div id="suggest"></div></div>
        <input name="user_name" type="submit" value="Зайти в игру"></form></div><script>init();</script>';
    }
    else
    {
        $user_name = $_POST['name_v'];
        $sel = myquery("SELECT * FROM game_users WHERE name='$user_name' LIMIT 1");
        if (!mysql_num_rows($sel))
        {
             $sel = myquery("SELECT * FROM game_users_archive WHERE user_name='$user_name' LIMIT 1");
             if (mysql_num_rows($sel))
             {
                 $up = myquery("INSERT INTO game_users SELECT * FROM game_users_archive WHERE user_name='$user_name' LIMIT 1");
                 $up = myquery("DELETE FROM game_users_archive WHERE user_name='$user_name'");
             }
        }
        $sel = myquery("SELECT * FROM game_users WHERE name='$user_name'");
        if (!mysql_num_rows($sel))
        {
             echo 'Игрок не найден!';
        }
        else
        {
            $user=mysql_fetch_array($sel);
            if ($user['clan_id']==1) 
            {
                echo 'Нельзя заходить за Создателей';
            }
            else
            {
                $user_id = $user['user_id'];
	            $_SESSION['user_id'] = $user['user_id'];
	            $user_host = HostIdentify();
                $user_host_more = HostIdentifyMore();
	            //$_SESSION['user_host_ip'] = $user_host;
                $user_time = time();
                mysql_query("INSERT INTO game_users_active SET last_active='$user_time', host='$user_host', user_id='$user_id' ON DUPLICATE KEY UPDATE last_active='$user_time',host='$user_host'"); 
                mysql_query("INSERT INTO game_users_active_host SET host_more='$user_host_more', user_id='$user_id' ON DUPLICATE KEY UPDATE host_more='$user_host_more'");
                mysql_query("INSERT INTO game_users_active_delay SET delay_reason='0', user_id='$user_id' ON DUPLICATE KEY UPDATE delay_reason='0'");
                mysql_query("INSERT IGNORE INTO game_users_func (`user_id`,`func_id`) VALUES ('".$user_id."','5')");
                mysql_query("INSERT IGNORE INTO game_users_data SET user_id='$user_id'");
                /*
                ?>
                <script type="text/javascript" language="JavaScript">
                setCookie("rpgsu_login", "<?=$user['user_name'];?>", , "/");
                setCookie("rpgsu_pass", "<?=md5($user['user_pass']);?>", , "/");
                setCookie("rpgsu_sess", "<?=$_COOKIE['rpgsu_sess'];?>", , "/");
                </script>
                Выполнено!
                <?
                */
                echo 'Выполнено!';
			    $da = getdate();
			    $log=myquery("INSERT INTO game_log_adm (adm,dei,cur_time,day,month,year) VALUES (
			    '".$char['name']."',
			    'Зашел под игроком: ".$user_name."',
			    '".time()."',
			    '".$da['mday']."',
			    '".$da['mon']."',
			    '".$da['year']."')
			    ");
                //echo('<pre>'.print_r($_COOKIE,true).'</pre>');  
            }
        }
    }
}

if (function_exists("save_debug")) save_debug(); 

?>