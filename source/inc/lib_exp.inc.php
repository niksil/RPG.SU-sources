<?php
//функция возвращает количество опыта, необходимое чтобы прокачать уровень $current_level+1
function get_new_level($current_level)
{
    if ($current_level==0)
        $new_level=200;
    elseif ($current_level<5)
        $new_level=200*$current_level*($current_level+1);
    else    
        $new_level=200*$current_level*($current_level+1)+200*bcpow('1.45',(string)($current_level-5),0);
    
    return $new_level;    
}

//функция возвращает количество опыта, затраченное на прокачку $current_level уровня
function get_exp_from_level($current_level)
{
    $sum_exp = 0;
    for ($i=1;$i<=$current_level;$i++)
    {
        if ($i==1)
        {
            $sum_exp = 200;
        }
        elseif ($i<=5)
        {
            $sum_exp+=200*$i*($i-1);
        }
        else
        {
            $sum_exp+=200*$i*($i-1)+200*bcpow('1.45',(string)($i-6),0);
        }
    }   
    return $sum_exp;
}

//функция возвращает уровень, соответсвующий по сумме опыту $current_exp
function get_level_from_exp($current_exp)
{
    $clevel = 0;
    $l_exp = $current_exp;
    while ($l_exp>=0)
    {
        $minus_exp = get_new_level($clevel);
        $l_exp-=$minus_exp;
        if ($l_exp>=0) $clevel++;
    }
    return $clevel;
}

function do_obnul($user_id,$obnul=0)
{
    // Формула накопленного опыта
    $sel_char = myquery("SELECT * FROM game_users WHERE user_id=$user_id");
    if (!mysql_num_rows($sel_char)) $sel_char = myquery("SELECT * FROM game_users_archive WHERE user_id=$user_id");
    if (!mysql_num_rows($sel_char)) return;
    
    $char = mysql_fetch_array($sel_char);
    $gp = 0;
    $EXP_NEW=0;
    count_all_exp($EXP_NEW,$gp);
    if ($obnul==2)
    {
        if ($char['clevel']>=25)
        {
            $EXP_NEW-=floor($EXP_NEW*0.1);
        }
        elseif ($char['clevel']>=10)
        {
            $EXP_NEW-=floor($EXP_NEW*0.05);
        }
    }
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

    $upd=myquery("update game_users_archive set clevel='0', HP='$hp_maxn', HP_MAX='$hp_maxn', HP_MAXX='$hp_maxn', MP='$mp_maxn', MP_MAX='$mp_maxn',
    STM='$stm_maxn', STM_MAX='$stm_maxn', EXP='$EXP_NEW',GP=GP-$gp, STR='$strn', NTL='$ntln', PIE='$pien', VIT='$vitn', DEX='$dexn',
    SPD='$spdn', STR_MAX='$strn', NTL_MAX='$ntln', PIE_MAX='$pien', VIT_MAX='$vitn', DEX_MAX='$dexn',
    SPD_MAX='$spdn', CC=40, lucky=0, lucky_max=0 where user_id=$user_id limit 1");

    //обновление навыков и специализаций
    $gp=0;
    //удаляем коней
    $sel = myquery("SELECT SUM(game_vsadnik.cena) FROM game_vsadnik,game_users_horses WHERE game_vsadnik.id=game_users_horses.horse_id AND game_users_horses.user_id=".$char['user_id']." GROUP BY game_users_horses.user_id");
    $gp = mysqlresult($sel,0,0);
    myquery("DELETE FROM game_users_horses WHERE user_id=".$char['user_id']."");

    //if ($char['vsadnik']!=0) $gp= mysql_result(myquery("SELECT cena FROM game_vsadnik WHERE id='".$char['vsadnik']."'"),0,0);
    $upd=myquery("delete from game_spets_item where user_id='".$char['user_id']."'");
    $upd=myquery("update game_users set MS_ART=0, MS_KULAK=0, MS_LUK=0, MS_WEAPON=0, MS_VOR=0, MS_VSADNIK=0, MS_PARIR=0, MS_LEK=0, MS_KUZN=0, MS_SPEAR=0, MS_SWORD=0, MS_AXE=0, MS_THROW=0, skill_war=0, skill_music=0, skill_cook=0, skill_art=0, skill_explor=0, skill_craft=0,skill_card=0,skill_pet=0,skill_uknow=0,dvij=1, exam='0', bound='0',vsadnik=0,GP=GP+'$gp' where user_id='".$char['user_id']."'");
    $upd=myquery("update game_users_archive set MS_ART=0, MS_KULAK=0, MS_LUK=0, MS_WEAPON=0, MS_VOR=0, MS_VSADNIK=0, MS_PARIR=0, MS_LEK=0, MS_KUZN=0, MS_SPEAR=0, MS_SWORD=0, MS_AXE=0, MS_THROW=0, skill_war=0, skill_music=0, skill_cook=0, skill_art=0, skill_explor=0, skill_craft=0,skill_card=0,skill_pet=0,skill_uknow=0,dvij=1, exam='0', bound='0',vsadnik=0,GP=GP+'$gp' where user_id='".$char['user_id']."'");
    setGP($user_id,$gp,29);

    //Снятие всех предметов
    $upd=myquery("update game_items set used=0 where user_id='".$char['user_id']."' and priznak=0");
    if ($obnul==1) myquery("UPDATE game_users_data SET obnul=0 WHERE user_id=$user_id");
}

?>