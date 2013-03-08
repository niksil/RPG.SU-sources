<?php

define('func4_boy',1);
define('func4_wait',2);
define('func4_lose',3);
define('func4_win',4);
define('func4_draw',5);
define('func4_ojid',6);
define('func4_podt',7);
define('func4_otkaz',8);
define('func4_net',9);


function arcomage_setFunc($user_id,$func_id)
{

    $sel_race = myquery("INSERT arcomage_user_func (user_id,func_id,func_sub_id,time_stamp) VALUES ('$user_id','".$func_id."','0','".time()."') ON DUPLICATE KEY UPDATE func_id='".$func_id."',time_stamp='".time()."' ");
    return 1;

}

function arcomage_getFunc($user_id)
{
    $sel_rid = myquery("SELECT func_id,time_stamp FROM arcomage_user_func WHERE user_id = '".$user_id."' ");
    if(mysql_num_rows($sel_rid)==0)
    {
        return 0; 
    }
    else
    {
        $arr_rid = mysql_fetch_array($sel_rid);
        return $arr_rid['func_id'];
    }
}


function arcomage_DelFunc($user_id)
{
    $sel_race = myquery("DELETE FROM arcomage_user_func WHERE user_id='$user_id'");
    return 1;
}

?>