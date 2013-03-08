<?php

if (function_exists("start_debug")) start_debug(); 

//Проверка ходили ли все
mt_srand((double)microtime()*1000000);
$select=mysql_result(myquery("SELECT count(*) FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."' AND func=2"),0,0);
if ($select==2)
{
    $curtime = time();
    $zap = myquery("UPDATE arcomage_users SET func=1,hod='$curtime' WHERE arcomage_id='".$charboy['arcomage_id']."'");

    $hod = $arcomage['hod'];
    $sel_init = myquery("SELECT * FROM arcomage_users WHERE arcomage_id='".$charboy['arcomage_id']."'");
    while($userboy=mysql_fetch_array($sel_init))
    {
        $q = $userboy['user_id'];
        $users[$q]['tower']         =$userboy['tower'];
        $users[$q]['wall']          =$userboy['wall'];
        $users[$q]['bricks']        =$userboy['bricks'];
        $users[$q]['bricks_add']    =$userboy['bricks_add'];
        $users[$q]['gems']          =$userboy['gems'];
        $users[$q]['gems_add']      =$userboy['gems_add'];
        $users[$q]['monsters']      =$userboy['monsters'];
        $users[$q]['monsters_add']  =$userboy['monsters_add'];
        $users[$q]['win']           =0;
		$users[$q]['lose']          =0;
		$users[$q]['hod']           =0;
    }


	$sel_hod = myquery("SELECT * FROM arcomage_history WHERE arcomage_id='".$arcomage['id']."' AND hod='$hod'");

    $cards2end = array();
    $cards2end[$arcomage['user1']] = 1;
    $cards2end[$arcomage['user2']] = 1;
    $card = 0;
    
	while ($user = mysql_fetch_array($sel_hod))
	{
		$kto = $user['user_id'];
        $card = $user['card_id'];
        
        if ($arcomage['user1'] == $kto)
          $kogo = $arcomage['user2'];
        else
          $kogo = $arcomage['user1'];
        
        if ($user['fall'] == 1)
          $cards2end[$kto] -= 1;
        else
        {
          $cards2end[$kto] -= 1;
          $cards2end[$kto] += extra_hod($card);
          
          if ($cards2end[$kto] >= 0)
          {
            $users[0]['tower']        = $users[$kto]['tower'];
            $users[0]['wall']         = $users[$kto]['wall'];
            $users[0]['bricks']       = $users[$kto]['bricks'];
            $users[0]['bricks_add']   = $users[$kto]['bricks_add'];
            $users[0]['gems']         = $users[$kto]['gems'];
            $users[0]['gems_add']     = $users[$kto]['gems_add'];
            $users[0]['monsters']     = $users[$kto]['monsters'];
            $users[0]['monsters_add'] = $users[$kto]['monsters_add'];
            $users[0]['win']          = $users[$kto]['win'];
            $users[0]['lose']         = $users[$kto]['lose'];
            $users[0]['hod']          = $users[$kto]['hod'];
            make_action_card($users,0,$kogo,$card);
            $users[$kto]['tower']        = $users[0]['tower'];
            $users[$kto]['wall']         = $users[0]['wall'];
            $users[$kto]['bricks_add']   = $users[0]['bricks_add'];
            $users[$kto]['gems_add']     = $users[0]['gems_add'];
            $users[$kto]['monsters_add'] = $users[0]['monsters_add'];
            $users[$kto]['win']          = $users[0]['win'];
            $users[$kto]['lose']         = $users[0]['lose'];
            $users[$kto]['hod']          = $users[0]['hod'];
          }
        }
	}
    
 	$users[$arcomage['user1']]['bricks']+=$users[$arcomage['user1']]['bricks_add'];
 	$users[$arcomage['user1']]['gems']+=$users[$arcomage['user1']]['gems_add'];
 	$users[$arcomage['user1']]['monsters']+=$users[$arcomage['user1']]['monsters_add'];
 	
    $users[$arcomage['user2']]['bricks']+=$users[$arcomage['user2']]['bricks_add'];
 	$users[$arcomage['user2']]['gems']+=$users[$arcomage['user2']]['gems_add'];
 	$users[$arcomage['user2']]['monsters']+=$users[$arcomage['user2']]['monsters_add'];

	if ($users[$arcomage['user1']]['tower']>=$arcomage['tower_win']) 		$users[$arcomage['user1']]['win']=1;
	if ($users[$arcomage['user1']]['bricks']>=$arcomage['resource_win']) 	$users[$arcomage['user1']]['win']=1;
	if ($users[$arcomage['user1']]['gems']>=$arcomage['resource_win']) 		$users[$arcomage['user1']]['win']=1;
	if ($users[$arcomage['user1']]['monsters']>=$arcomage['resource_win']) 	$users[$arcomage['user1']]['win']=1;
	if ($users[$arcomage['user1']]['tower']<=0) 							$users[$arcomage['user1']]['lose']=1;
    
    if ($users[$arcomage['user2']]['tower']>=$arcomage['tower_win'])        $users[$arcomage['user2']]['win']=1;
    if ($users[$arcomage['user2']]['bricks']>=$arcomage['resource_win'])    $users[$arcomage['user2']]['win']=1;
    if ($users[$arcomage['user2']]['gems']>=$arcomage['resource_win'])      $users[$arcomage['user2']]['win']=1;
    if ($users[$arcomage['user2']]['monsters']>=$arcomage['resource_win'])  $users[$arcomage['user2']]['win']=1;
    if ($users[$arcomage['user2']]['tower']<=0)                             $users[$arcomage['user2']]['lose']=1;

    $user1 = $arcomage['user1'];
    $user2 = $arcomage['user2'];
    $func1 = 1;
    $func2 = 1;

    // Draw?
     if (($users[$user1]['win'] == 1 AND $users[$user2]['win'] == 1) OR
         ($users[$user1]['lose'] == 1 AND $users[$user2]['lose'] == 1) OR
         ($users[$user1]['lose'] == 1 AND $users[$user1]['win'] == 1) OR
         ($users[$user2]['win'] == 1 AND $users[$user2]['lose'] == 1))
    {
        $func1 = 5;
        $func2 = 5;
    }
    // Player 1 wins?
    elseif (($users[$user1]['win'] == 1) OR ($users[$user2]['lose'] == 1))
    {
        $func1 = 4;
        $func2 = 3;
    }
    // Player 2 wins?
    elseif (($users[$user2]['win'] == 1) OR ($users[$user1]['lose'] == 1))
    {
        $func1 = 3;
        $func2 = 4;
    }
     
    myquery("UPDATE arcomage_users SET func=$func1,tower='".$users[$user1]['tower']."',wall='".$users[$user1]['wall']."',bricks='".$users[$user1]['bricks']."',bricks_add='".$users[$user1]['bricks_add']."',gems='".$users[$user1]['gems']."',gems_add='".$users[$user1]['gems_add']."',monsters='".$users[$user1]['monsters']."',monsters_add='".$users[$user1]['monsters_add']."' WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id='$user1'");
 	myquery("UPDATE arcomage_users SET func=$func2,tower='".$users[$user2]['tower']."',wall='".$users[$user2]['wall']."',bricks='".$users[$user2]['bricks']."',bricks_add='".$users[$user2]['bricks_add']."',gems='".$users[$user2]['gems']."',gems_add='".$users[$user2]['gems_add']."',monsters='".$users[$user2]['monsters']."',monsters_add='".$users[$user2]['monsters_add']."' WHERE arcomage_id='".$charboy['arcomage_id']."' AND user_id='$user2'");

	myquery("UPDATE arcomage SET timehod=$curtime,hod=hod+1 WHERE id='".$charboy['arcomage_id']."'");
    if (function_exists("save_debug")) save_debug(); 
    setLocation("arcomage.php");
}
?>