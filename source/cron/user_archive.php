<?
define('money_weight',0);

require_once("../inc/engine.inc.php");
require_once("../inc/xray.inc.php");

    myquery ("set character_set_client='cp1251'");
    myquery ("set character_set_results='cp1251'");
    myquery ("set collation_connection='cp1251_general_ci'");

$db = mysql_pconnect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());

$cur_time=time()-172800;
echo 'Переброс игроков в архив<br>';
$sel = myquery("INSERT INTO game_users_archive SELECT * FROM game_users WHERE game_users.delay<'".$cur_time."'");
$del = myquery("DELETE FROM game_users WHERE game_users.delay<'".$cur_time."'");
$opt = myquery("OPTIMIZE TABLE game_users");

//Очистка статистики
//$cur_time=time()-60*60*24*45;
//$del = myquery("DELETE FROM game_stat WHERE time<'".$cur_time."'");
//$opt = myquery("OPTIMIZE TABLE game_stat");

//Очистка активности
//$cur_time=time()-60*60*24*45;
//$del = myquery("DELETE FROM game_activity WHERE time<'".$cur_time."'");
//$opt = myquery("OPTIMIZE TABLE game_activity");


//Очистка просроченной регистрации игроков
echo 'Очистка просроченной регистрации игроков<br>';
$cur_time=time()-60*60*24*2;
$del = myquery("DELETE FROM game_users_reg WHERE rego_time<'".$cur_time."'");
$opt = myquery("OPTIMIZE TABLE game_users_reg");

//Очистка логов боев
echo 'Очистка логов боев<br>';
$cur_time=time()-60*60*24*30;
$del = myquery("DELETE FROM game_combats_log WHERE time<'".$cur_time."'");
$opt = myquery("OPTIMIZE TABLE game_combats_log");

//Перемещение проданных лотов с аукциона в инвентарь и денег за них продавцу
echo 'Обработка аукциона<br>';
$time_for_check = time()-604800;
$select=myquery("SELECT * FROM game_items_old where priznak='1' and type!='' and (sell_time<'$time_for_check' or last_price=max_price) ORDER BY sell_time DESC");
while ($item=mysql_fetch_array($select))
{
        $check = myquery("SELECT user_id FROM game_users WHERE name='".$item['name']."'");
        if (!mysql_num_rows($check)) $check = myquery("SELECT user_id FROM game_users_archive WHERE name='".$item['name']."'");
        list($user_id_sell)=mysql_fetch_array($check);
        if ($user_id_sell!=$item['last_user'])
        {
            //Предмет был куплен кем-то
            $userid =  $item['last_user'];
            $it = $item['id'];
            $sct=myquery("select name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p from game_items_old where priznak='1' and id='$it'");
            list($name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p)=mysql_fetch_array($sct);

            $result=myquery("insert into game_items(user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('$userid','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
            $result=myquery("update game_users set CW=CW+$weight where user_id='$userid'");

            $result=myquery("update game_users set GP=GP+'".$item['last_price']."',CW=CW+'".($item['last_price']*money_weight)."' where name='$name'");
            $result=myquery("update game_users_archive set GP=GP+'".$item['last_price']."',CW=CW+'".($item['last_price']*money_weight)."' where name='$name'");

            $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
            list($rustown)=mysql_fetch_array($town_select);

            $check = myquery("SELECT name FROM game_users WHERE user_id='".$item['last_user']."'");
            if (!mysql_num_rows($check)) $check = myquery("SELECT name FROM game_users_archive WHERE user_id='".$item['last_user']."'");
            list($name_buy)=mysql_fetch_array($check);

            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name_buy', 'Автосообщение', 'Аукцион', 'Ты купил лот ".$ident." на аукционе в ".$rustown."! Он перемещен в твой инвентарь','0','".time()."')");
            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name', 'Автосообщение', 'Аукцион', 'Твой лот ".$ident." был куплен на аукционе в ".$rustown."! В твой кошелек переведена сумма покупки предмета: ".$item['last_price']."','0','".time()."')");

            $delete=myquery("delete from game_items_old where priznak='1' and id='$it'");

            echo 'Предмет '.$ident.' куплен игроком '.$name_buy.' за '.$item['last_price'].'. Владелец '.$name.'<br>';
        }
        else
        {
            //Предмет не покупали. Просто возвращаем его владельцу
            $userid =  $user_id_sell;
            $it = $item['id'];
            $sct=myquery("select name,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p from game_items_old where priznak='1' and id='$it'");
            list($name,$ident,$indx,$deviation,$mode,$weight,$curse,$img,$item_cost,$type,$ostr,$ontl,$opie,$ovit,$odex,$ospd,$oclevel,$dstr,$dntl,$dpie,$dvit,$ddex,$dspd,$sv,$race,$hp_p,$mp_p,$stm_p,$cc_p)=mysql_fetch_array($sct);

            $result=myquery("insert into game_items(user_id,ref_id,ident,indx,deviation,mode,weight,curse,img,item_cost,type,ostr,ontl,opie,ovit,odex,ospd,oclevel,dstr,dntl,dpie,dvit,ddex,dspd,sv,race,hp_p,mp_p,stm_p,cc_p) values ('$userid','0','$ident','$indx','$deviation','$mode','$weight','$curse','$img','0','$type','$ostr','$ontl','$opie','$ovit','$odex','$ospd','$oclevel','$dstr','$dntl','$dpie','$dvit','$ddex','$dspd','$sv','$race','$hp_p','$mp_p','$stm_p','$cc_p')");
            $result=myquery("update game_users set CW=CW+$weight where user_id='$userid'");
            $town_select = myquery("select rustown from game_gorod where town='".$item['town']."'");
            list($rustown)=mysql_fetch_array($town_select);
            $ma=myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('$name', 'Автосообщение', 'Аукцион', 'Твой лот ".$ident." снят с продажи на аукционе в ".$rustown." так как он не участвовал в торгах в течении недели (на твой лот так и не нашлось покупателя!) ! он возвращен в твой инвентарь','0','".time()."')");
            $delete=myquery("delete from game_items_old where priznak='1' and id='$it'");
            echo 'Предмет '.$ident.' никем не куплен. Владелец '.$name.'<br>';
        }
}


/*
//Очистка почты
$sel = myquery("SELECT name FROM game_users");
while (list($name)=mysql_fetch_array($sel))
{
    //прочитанные письма
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //архивные письма
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //отправленные письма
    $sel_pm = myquery("SELECT * FROM game_pm WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }

    //удаленные прочитанные письма
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //удаленные архивные письма
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //удаленные отправленные письма
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }
}
$sel = myquery("SELECT name FROM game_users_archive");
while (list($name)=mysql_fetch_array($sel))
{
    //прочитанные письма
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //архивные письма
    $sel_pm = myquery("SELECT * FROM game_pm WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //отправленные письма
    $sel_pm = myquery("SELECT * FROM game_pm WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }

    //удаленные прочитанные письма
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 1");
    $kol = mysql_num_rows($sel_pm);
    if($kol>300)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=1 LIMIT 0,'".($kol-300)."'");
    }
    //удаленные архивные письма
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE komu='".$name."' AND view = 2");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE kome='".$name."' AND view=2 LIMIT 0,'".($kol-100)."'");
    }
    //удаленные отправленные письма
    $sel_pm = myquery("SELECT * FROM game_pm_deleted WHERE otkogo='".$name."' AND view = 3");
    $kol = mysql_num_rows($sel_pm);
    if($kol>100)
    {
        $del = myquery("DELETE FROM game_pm_deleted WHERE otkogo='".$name."' AND view=3 LIMIT 0,'".($kol-100)."'");
    }
}
$opt = myquery("OPTIMIZE TABLE game_pm");
$opt = myquery("OPTIMIZE TABLE game_pm_deleted");
*/

?>