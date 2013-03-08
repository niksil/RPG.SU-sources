<?
include("xml.php");

function addm($priv)
{
    global $mes;
    global $bot;
    global $char;
    global $name;
    setlocale (LC_ALL, "ru_RU.CP1251");

    if (!isset($char['sex']) || $char['sex'] == "male")
      $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\1", $mes);
    else
      $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\2", $mes);

    $mes=$name.', <span style="color:#FF2828;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE",$mes).'</style>';
    $message = $mes;
    $message = mysql_real_escape_string($message);


    $sel = myquery("SELECT `count` FROM `game_bot_chat_resp` WHERE `id` = '$name';");
    $n = mysql_fetch_array($sel);
    if ($n['count'] <= 4)
    {
      myquery("INSERT INTO `game_bot_chat_resp` (`id`,`count`) VALUES ('$name','1') ON DUPLICATE KEY UPDATE `count` = `count` + 1;");

      if ($priv == 1)
        $update_chat=myquery("insert into game_log (town,fromm,too,message,date,ptype) values (0,'-1','".$char['user_id']."','".$message."','".time()."',1)");
      else
        $update_chat=myquery("insert into game_log (town,fromm,too,message,date,ptype) values (0,'-1','0','".$message."','".time()."',0)");
    }
    else
    {
      $sel = myquery("SELECT `text` FROM `game_bot_chat_annoy` ORDER BY RAND() ASC LIMIT 1");
      $mes = mysql_fetch_array($sel);

      if ($char['sex'] == "male")
        $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\1", $mes);
      else
        $mes = preg_replace("/\\{([\\d\\w\\s]*?)\\|([\\d\\w\\s]*?)\\}/i", "\\2", $mes);

      $message = mysql_real_escape_string($name.', <span style="color:#FF2828;font-size:12px;">'.iconv("Windows-1251","UTF-8//IGNORE",$mes['text']).'</style>');
      $update_chat=myquery("insert into game_log (town,fromm,too,message,date) values (0,'-1','0','".$message."','".time()."')");
    }
}
/*
if ($char['user_id'] == 15109 && preg_match ("/ debug/i", "$message"))
{
      ob_start();

        echo("<pre>\n");

        $query = "SELECT UNIX_TIMESTAMP(`reg_time`) AS `reg`, UNIX_TIMESTAMP(`unreg_time`) AS `unreg` FROM `game_clans` WHERE clan_id = ".(1).";";
        echo("\$query: \"".$query."\"\n");
        $live_res=myquery($query);

        print_r($live_res);
        echo("\n");

        $live_res = mysql_fetch_array($live_res);

        print_r($live_res);
        echo("\n\$reg:".$live_res['reg']);

//        print_r( $char );
//        echo("\$message: \"".$message."\"");
//
//        $live_reg = date("j.m.Y", $live_res['reg']);

      echo("</pre>");
      $tmp = ob_get_clean();

    $tmp = iconv("Windows-1251","UTF-8//IGNORE","OK." . $tmp);

    myquery("insert into game_log (town,fromm,too,message,date) values (0,'-1','".$char['user_id']."','".$tmp."','".time()."')");
}
*/
if (!isset($message)) {if (function_exists("save_debug")) save_debug(); exit;}

// thanks

if (preg_match ("/ (спасибо|благодар|признат|мерси|здорово|пожалуйст|прости|молодец|аригато|%sm1_04_thank_you)/i", $message))
{
  global $name;

  myquery("UPDATE `game_bot_chat_resp` SET `count` = 0 WHERE `id` = '$name';");
}

// date modificator

$gdate_mod = -2;

if (preg_match ("/ сегодня/i", $message))
 $gdate_mod = 0;
elseif (preg_match ("/ вчера/i", $message))
 $gdate_mod = -1;
elseif (preg_match ("/ завтра/i", $message))
 $gdate_mod = 1;


if (preg_match ("/овен/i", "$message"))
{
    $gor='aries';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/телец/i", "$message"))
{
    $gor='taurus';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}    
elseif (preg_match ("/близнец/i", "$message"))
{
    $gor='gemini';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/рак/i", "$message"))
{
    $gor='cancer';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/лев/i", "$message"))
{
    $gor='leo';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/дева/i", "$message"))
{
    $gor='virgo';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/весы/i", "$message"))
{
    $gor= "libra";
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/скорпион/i", "$message"))
{
    $gor='scorpio';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/стрелец/i", "$message"))
{
    $gor= 'sagittarius';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/козерог/i", "$message"))
{
    $gor= 'capricorn';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/водоле/i", "$message"))
{
    $gor= 'aquarius';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/рыб/i", "$message"))
{
    $gor= 'pisces';
    include('bot/goroskop.php');
    $mes=$nline;
    addm(1);
}
elseif (preg_match ("/ кубик/i", "$message"))
{
    $r = mt_rand(1, 6);
    $mes='вот: <img src="/chat/dice/'.$r.'.gif" align="middle" alt="'.$r.'"/>';
    addm(1);
}
/*
elseif (preg_match("/ вики ([\\w\\s]+)/i" , $message, $matches))
{
    $xml = file_get_contents('http://ru.wikipedia.org/w/api.php?action=opensearch&search=".rawurlencode($matches[1])."&format=xml&limit=1');
    $arr = xml2array($xml);
    $found = $arr['SearchSuggestion']['_c']['Section']['_c']['Item']['_c']['Text']['_v'];

    $mes='В википедии найдена статья "'.$found.'": <br/>';
    addm(1);
}
*/


/*
elseif (preg_match ("/ анекдот/i", "$message"))
{
    $typ="j";
    include('bot/anekdot.php');
    addm(1);
}
elseif (preg_match ("/ истори/i", "$message"))
{
    $typ="o";
    include('bot/anekdot.php');
    addm(1);
}
elseif (preg_match ("/ афоризм/i", "$message"))
{
    $typ="a";
    include('bot/anekdot.php');
    addm(0);
}
elseif (preg_match ("/афаризм/i", "$message"))
{
    $typ="a";
    include('bot/anekdot.php');
    addm(0);
}

elseif (preg_match ("/ стих/i", "$message"))
{
    $typ="c";
    include('bot/anekdot.php');
    addm(1);
}
elseif (preg_match ("/ стиш/i", "$message"))
{
    $typ="c";
    include('bot/anekdot.php');
    addm(1);
}
*/
else
{
	$mes_chat = array();
	$sel=myquery("SELECT text, type FROM game_bot_chat ORDER BY LENGTH(type) DESC, RAND() ASC;");
	while($txt=mysql_fetch_array($sel))
	{	
		$txtt=$txt['type'];
		if (preg_match("/$txtt/i", "$message")>0)
		{
			$mes_chat[]=$txt['text'];
		}
 	}
	if (count($mes_chat) > 0)
	{
//		shuffle($mes_chat);
                $mes = $mes_chat[0];
                addm(0);
	}
 }
?>