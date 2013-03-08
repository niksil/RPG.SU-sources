<?
error_reporting('E_ALL');
require_once('inc/engine.inc.php');
include('inc/xray.inc.php');
include('inc/lib.inc.php');
include('inc/functions.php');
include('inc/lib_session.inc.php');

$selobnul = myquery("SELECT obnul_free FROM game_users_data WHERE user_id=$user_id");
if (!mysql_num_rows($selobnul)) header("Location:act.php");
list($kol_obnul) = mysql_fetch_array($selobnul);
if ($kol_obnul!=1) header("Location:act.php");

if (isset($_POST['make_obnul']))
{
    do_obnul_old();
}

?>
<title><?php echo GAME_NAME;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="description" content="">
<meta name="Keywords" content="">
<script language="JavaScript" type="text/javascript" src="js/cookies.js"></script>
<style type="text/css">@import url("style/global.css");</style>

<?

function count_all_exp_old(&$EXP_NEW,&$gp)
{
    GLOBAL $char, $user_id;
    // Формула накопленного опыта
    $level = $char['clevel'];
    $i = 1;
    $gp = 0;
    $EXP_NEW=$char['EXP'];
    for($i=1;$i<=$level;$i++)
    {
        if (($i-1) == 0)
        {
            $exp = 200;
        }
        else
        {
            $exp = $i*($i-1)*200;
        }
        $EXP_NEW=$EXP_NEW+$exp;
    }
    $new_clevel = get_level_from_exp($EXP_NEW);
    
    for($i=1;$i<=$new_clevel;$i++)
    {
        if ($i == 10)
        {
            $gp+=300;
        }
        elseif ($i == 20)
        {
            $gp+=500;
        }
        elseif ($i == 30)
        {
            $gp+=1000;
        }
        elseif ($i == 40)
        {
            $gp+=1500;
        }
        else
        {
            if ($i<10)
            {
                $gp+=50;
            }
            else
            {
                $gp+=floor(($i-1)/10)*2*50;
            }
        }
    }
    return '';
}

function do_obnul_old()
{
    GLOBAL $char, $user_id;
    // Формула накопленного опыта
    $gp = 0;
    $EXP_NEW=0;
    count_all_exp_old($EXP_NEW,$gp);
    if ($char['clevel']>=40)
    {
        //$EXP_NEW-=floor($EXP_NEW*0.1);
    }
    elseif ($char['clevel']>8)
    {
        //$EXP_NEW-=floor($EXP_NEW*0.05);
    }
    
    myquery("SET AUTOCOMMIT=0");
    myquery("START TRANSACTION");

    $result=myquery("select * from game_har where id='".$char['race']."'");
    $row=mysql_fetch_array($result);

    $hp_maxn=$row["hp_max"];
    $mp_maxn=$row["mp_max"];
    $stm_maxn=$row["stm_max"];
    $strn=$row["str"];
    $ntln=$row["ntl"];
    $pien=$row["pie"];
    $vitn=$row["vit"];
    $dexn=$row["dex"];
    $spdn=$row["spd"];

    $upd=myquery("update game_users set clevel='0', HP='$hp_maxn', HP_MAX='$hp_maxn', HP_MAXX='$hp_maxn', MP='$mp_maxn', MP_MAX='$mp_maxn',
    STM='$stm_maxn', STM_MAX='$stm_maxn', EXP='$EXP_NEW',GP=GP-$gp, STR='$strn', NTL='$ntln', PIE='$pien', VIT='$vitn', DEX='$dexn',
    SPD='$spdn', STR_MAX='$strn', NTL_MAX='$ntln', PIE_MAX='$pien', VIT_MAX='$vitn', DEX_MAX='$dexn',
    SPD_MAX='$spdn', CC=40, lucky=0, lucky_max=0 where user_id=$user_id limit 1");

    //обновление навыков и специализаций
    $gp=0;
    //удаляем коней
    $sel = myquery("SELECT SUM(game_vsadnik.cena) FROM game_vsadnik,game_users_horses WHERE game_vsadnik.id=game_users_horses.horse_id AND game_users_horses.user_id=".$char['user_id']." GROUP BY game_users_horses.user_id");
    if (mysql_num_rows($sel)!=0) $gp = $gp + mysqlresult($sel,0,0);
    myquery("DELETE FROM game_users_horses WHERE user_id=".$char['user_id']."");
    
    $sel = myquery("SELECT COUNT(*) FROM game_users_crafts WHERE profile=1 AND user_id=$user_id");
    if (mysql_num_rows($sel)!=0) $gp = $gp + 80*mysqlresult($sel,0,0);
    myquery("UPDATE game_users_crafts SET profile=0 WHERE profile=1 AND user_id=$user_id");

    //if ($char['vsadnik']!=0) $gp= mysql_result(myquery("SELECT cena FROM game_vsadnik WHERE id='".$char['vsadnik']."'"),0,0);
    $upd=myquery("delete from game_spets_item where user_id='".$char['user_id']."'");
    $upd=myquery("update game_users set MS_ART=0, MS_KULAK=0, MS_LUK=0, MS_WEAPON=0, MS_VOR=0, MS_VSADNIK=0, MS_PARIR=0, MS_LEK=0, MS_KUZN=0, MS_SPEAR=0, MS_SWORD=0, MS_AXE=0, MS_THROW=0, skill_war=0, skill_music=0, skill_cook=0, skill_art=0, skill_explor=0, skill_craft=0,skill_card=0,skill_pet=0,skill_uknow=0,dvij=1, exam='0', bound='0',vsadnik=0,GP=GP+'$gp' where user_id='".$char['user_id']."'");
    setGP($user_id,$gp,29);

    //Снятие всех предметов
    $upd=myquery("update game_items set used=0 where user_id='".$char['user_id']."' and priznak=0");

    myquery("UPDATE game_users_data SET obnul_free=0 WHERE user_id=$user_id");
    
    myquery("COMMIT");
    
    myquery("SET AUTOCOMMIT=1");
    
    Header("Location:main.php");    
}


$img='http://'.IMG_DOMAIN.'/race_table/human/table';
echo'
<table width=100% border="0" cellspacing="0" cellpadding="0"><tr><td width="1" height="1"><img src="'.$img.'_lt.gif"></td><td background="'.$img.'_mt.gif"></td><td width="1" height="1"><img src="'.$img.'_rt.gif"></td></tr>
<tr><td background="'.$img.'_lm.gif"></td><td background="'.$img.'_mm.gif" align="center" valign="top" width="100%" height="100%">';



QuoteTable('open');
echo '<div style="padding:40px;font-weight:900;color:#C0FFC0;font-family:Georgia,Verdana,Tahoma,Arial,sans-serif;font-size:15px;">
<br /><br /><br /><br /><br /><br />
Здравствуй, уважаемый игрок. В связи с кардинальными изменениями таблицы опыта, мы вынуждены выполнить "обнуление" твоего персонажа. После "обнуления" твой уровень и характеристики будут сброшены до начального уровня (0 уровня). Все предметы будут с тебя сняты. Если у тебя есть конь, то тебе вернут половину его стоимости, а самого коня удалят. У тебя будет вычтено то количество золотых монет, которое ты получишь при прокачивании уровней.<br /><br /><br />
Если ты согласен с "обнулением" своего персонажа, нажми кнопку ниже текста. Если не согласен, то ты можешь просто закрыть сайт игры.
<br /><br /><br />
С уважением, администрация игры '.GAME_NAME.'.<br /><br /><br /><br /><br /><br />
<center>
<form action="" method="post" name="form_obnul" class="button"><input style="font-size:18px;font-color:white;font-weight:900;font-family:Verdana;padding:20px;" type="submit" name="make_obnul" value="ДА, Я согласен, обнулите мой персонаж."></form>
<br /><br /><br /><br /><br /><br />
</div>';
QuoteTable('close');

echo'</td><td background="'.$img.'_rm.gif"></td></tr><tr><td width="1" height="1"><img src="'.$img.'_lb.gif"></td><td background="'.$img.'_mb.gif"></td><td width="1" height="1"><img src="'.$img.'_rb.gif"></td></tr></table>';



mysql_close();
?>