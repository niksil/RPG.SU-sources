<?php

if (function_exists("start_debug")) start_debug();

if ($char['hide']==0 OR $char['clan_id']==1)
{

    echo '<table><tr><td>';

    include('craft/buildings.inc.php');

    if (isset($_GET['use_svitok_hranitel']) AND is_numeric($_GET['use_svitok_hranitel']) AND $char['map_name']==691)
    {
        $sel_check = myquery("SELECT * FROM game_items WHERE id=".$_GET['use_svitok_hranitel']." AND item_id=".item_id_svitok_hranitel." AND user_id=$user_id AND priznak=0 AND used=0");
        if (mysql_num_rows($sel_check)>0)
        {
            $Item = new Item($_GET['use_svitok_hranitel']);
            $Item->admindelete();
            
            myquery("UPDATE game_map SET town=".id_portal_tuman.", to_map_name=".id_map_tuman.", to_map_xpos=0, to_map_ypos=0 WHERE xpos=19 and ypos=19 and name='".$char['map_name']."'");
            
            QuoteTable('open');
            echo 'Использовав Свиток Хранителя, ты активировал портал в Туманные Горы!';
            QuoteTable('close');
        }
    }
    
    //Покажем проходы и города на гексе (может быть только одно - или 1 город, или 1 проход)
    $sel=myquery("select * from game_map where town!=0 and xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
    if (mysql_num_rows($sel))
    {
       while ($gorod=mysql_fetch_array($sel))
       {
           $sel=myquery("select town,name,text,clan,user,race,time,gp,id,timestart,exit_lab from game_obj where id='".$gorod['town']."'");
           if(($gorod['to_map_name']!=0)AND(mysql_num_rows($sel)>0))
           {
                list($town,$name,$text,$clan,$user,$race,$time,$gp,$obj_id,$timestart,$exit_lab)=mysql_fetch_array($sel);
                if($clan==0 or $clan=='') $clan=$char['clan_id'];
                if($user=='') $user=$char['user_id'];
                if($race=='') $race=mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);

                $result_items = myquery("SELECT count(*) from game_wm WHERE user_id=$user_id AND type=5");
                if (@mysql_result($result_items,0,0) > 0) $race=mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0);

                $pass_time = true;
                
                if($gp=='') $gp='0';
                $a=explode(",",$clan);
                $b=explode(",",$user);

                $condition_text = '';
                if ($timestart!='')
                {
                    $d = explode(" ",$timestart);
                    $dat = explode(".",$d[0]);
                    $tim = explode(":",$d[1]);
                    $timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
                    if(time() < $timestamp_open)
                    {
                        $tme='no';
                    }
                    else
                    {
                        $tme='ok';
                    }
                }
                else
                {
                    $tme='ok';
                }

                if ($time!='' and $tme!='no')
                {
                    $d = explode(" ",$time);
                    $dat = explode(".",$d[0]);
                    $tim = explode(":",$d[1]);
                    $timestamp_open = mktime($tim[0],$tim[1],0,$dat[1],$dat[0],$dat[2]);
                    if(time() <= $timestamp_open)
                    {
                        $condition_text.='<div style="color:red;font-weight:800;">Проход закроется '.$time.'</div><br>';
                        $tme='ok';
                    }
                    else
                    {
                        $tme='no';
                        $pass_time = false;
                    }
                }
                elseif ($tme!='no')
                {
                    $tme='ok';
                }

                $item_id_need = 0;
                $sel_nom = myquery("SELECT DISTINCT nomer FROM game_obj_require WHERE obj_id=$obj_id");
                if (mysql_num_rows($sel_nom))
                {
                    $flag = 0;
                    $str = '<i>Для прохода выставлены условия: <br>';
                    $vsego_nom = mysql_num_rows($sel_nom);
                    $cur_nom = 0;
                    while (list($nom)=mysql_fetch_array($sel_nom))
                    {
                        $cur_nom++;
                        $sel_cond = myquery("SELECT * FROM game_obj_require WHERE nomer=$nom AND obj_id=$obj_id");
                        $vsego_cond = mysql_num_rows($sel_cond);
                        $cur_cond = 0;
                        $true_cond = 0;
                        while ($cond = mysql_fetch_array($sel_cond))
                        {
                            $cur_cond++;
                            switch ($cond['type'])
                            {
                                case 1:
                                    $str.='Уровень игрока ';
                                    $par = 'clevel';
                                break;
                                case 2:
                                    $str.='Количество наличных денег ';
                                    $par = 'GP';
                                break;
                                case 3:
                                    $str.='Наличие предмета ';
                                break;
                                case 34:
                                    $str.='Одетый предмет ';
                                break;
                                case 4:
                                    $par = 'vsadnik';
                                    $str.='Наличие коня ';
                                break;
                                case 5:
                                    $par = 'HP_MAX';
                                    $str.='Макс. здоровье ';
                                break;
                                case 6:
                                    $par = 'MP_MAX';
                                    $str.='Макс. мана ';
                                break;
                                case 7:
                                    $par = 'STM_MAX';
                                    $str.='Макс. энергия ';
                                break;
                                case 8:
                                    $par = 'STR';
                                    $str.='Сила игрока ';
                                break;
                                case 9:
                                    $par = 'NTL';
                                    $str.='Интеллект игрока ';
                                break;
                                case 10:
                                    $par = 'PIE';
                                    $str.='Ловкость игрока ';
                                break;
                                case 11:
                                    $par = 'SPD';
                                    $str.='Мудрость игрока ';
                                break;
                                case 12:
                                    $par = 'DEX';
                                    $str.='Выносливость игрока ';
                                break;
                                case 33:
                                    $par = 'VIT';
                                    $str.='Защита игрока ';
                                break;
                                case 13:
                                    $par = 'MS_ART';
                                    $str.='Специализация "владение артефактом" ';
                                break;
                                case 14:
                                    $par = 'MS_VOR';
                                    $str.='Специализация "вор" ';
                                break;
                                case 15:
                                    $par = '_MS_WEAPON';
                                    $str.='Специализация "владение оружием" ';
                                break;
                                case 16:
                                    $par = 'MS_KULAK';
                                    $str.='Специализация "кулачный бой" ';
                                break;
                                case 17:
                                    $par = 'MS_PARIR';
                                    $str.='Специализация "парирование" ';
                                break;
                                case 18:
                                    $par = 'MS_LEK';
                                    $str.='Специализация "лекарь" ';
                                break;
                                case 19:
                                    $par = 'win';
                                    $str.='Количество побед ';
                                break;
                                case 20:
                                    $par = 'lose';
                                    $str.='Количество поражений ';
                                break;
                                case 21:
                                    $par = 'arcomage_win';
                                    $str.='Выиграно в Две Башни ';
                                break;
                                case 22:
                                    $par = 'arcomage_lose';
                                    $str.='Проиграно в Две Башни ';
                                break;
                                case 23:
                                    $par = 'maze_win';
                                    $str.='Пройдено лабиринтов ';
                                break;
                                case 24:
                                    $par = 'skill_war';
                                    $str.='Магия "Воин" ';
                                break;
                                case 25:
                                    $par = 'skill_cook';
                                    $str.='Магия "Волшебник" ';
                                break;
                                case 26:
                                    $par = 'skill_art';
                                    $str.='Магия "Лучник" ';
                                break;
                                case 27:
                                    $par = 'skill_card';
                                    $str.='Магия "Вор" ';
                                break;
                                case 28:
                                    $par = 'skill_uknow';
                                    $str.='Магия "Разбойник" ';
                                break;
                                case 29:
                                    $par = 'skill_music';
                                    $str.='Магия "Бард" ';
                                break;
                                case 30:
                                    $par = 'skill_craft';
                                    $str.='Магия "Варвар" ';
                                break;
                                case 31:
                                    $par = 'skill_pet';
                                    $str.='Магия "Друид" ';
                                break;
                                case 32:
                                    $par = 'skill_explor';
                                    $str.='Магия "Паладин" ';
                                break;
                                case 101:
                                    $par = 'sklon';
                                    $str.='Склонность игрока ';
                                break;
                            }
                            if ($cond['type']==3)
                            {
                                list($id_item) = mysql_fetch_array(myquery("SELECT id FROM game_items_factsheet WHERE name='".$cond['value']."'"));
                                $item_id_need = $id_item;
                                $str.=' - '.$cond['value'];
                                $check_item = myquery("SELECT * FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id=$id_item");
                                if (mysql_num_rows($check_item)>0) $true_cond++;
                            }
                            elseif ($cond['type']==34)
                            {
                                list($name_item) = mysql_fetch_array(myquery("SELECT name FROM game_items_factsheet WHERE id=".$cond['value'].""));
                                $str.=' - '.$name_item;
                                $check_item = myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id AND priznak=0 AND item_id='".$cond['value']."' AND used>0");
                                if (mysql_num_rows($check_item)>0) $true_cond++;
                            }
                            elseif ($cond['type']==4)
                            {
                                list($name_horse) = mysql_fetch_array(myquery("SELECT nazv FROM game_vsadnik WHERE id=".$cond['value'].""));
                                $str.=' - '.$name_horse;
                                if ($char['vsadnik']==$cond['value']) $true_cond++;
                            }
                            elseif ($cond['type']==101)
                            {
                                if ($cond['value']==1)
                                {
                                    $str.=' - нейтральная';
                                }
                                if ($cond['value']==2)
                                {
                                    $str.=' - светлая';
                                }
                                if ($cond['value']==3)
                                {
                                    $str.=' - темная';
                                }
                                if ($char['sklon']==$cond['value']) $true_cond++;
                            }
                            elseif ($cond['type']==100)
                            {
                                if (isset($_REQUEST['keyword']))
                                {
                                    if (strtolower(trim($_REQUEST['keyword']))==strtolower(trim($cond['value'])))
                                    {
                                        $true_cond++;
                                        $pass = $cond['value'];
                                    }
                                    else
                                    {
                                        $str.= 'Ты '.echo_sex('указал','указала').' неправильное кодовое слово!';
                                    }
                                }
                                else
                                {
                                    $str.= '<br /><form autocomplete="off" action="" method="POST">Тебе надо ввести правильное кодовое слово:
                                    <br />Кодовое слово: <input type="text" size="25" maxsize="50" value="" name="keyword">&nbsp;&nbsp;<input type="submit" value="Ввести слово"></form>';
                                    $ask_pass = 1;
                                }
                            }
                            else
                            {
                                switch ($cond['condition'])
                                {
                                    case 1:
                                        $str.=' <=';
                                        if ($char[$par]<=$cond['value']) $true_cond++;
                                    break;
                                    case 2:
                                        $str.=' <';
                                        if ($char[$par]<$cond['value']) $true_cond++;
                                    break;
                                    case 3:
                                        $str.=' =';
                                        if ($char[$par]==$cond['value']) $true_cond++;
                                    break;
                                    case 4:
                                        $str.=' >=';
                                        if ($char[$par]>=$cond['value']) $true_cond++;
                                    break;
                                    case 5:
                                        $str.=' >';
                                        if ($char[$par]>$cond['value']) $true_cond++;
                                    break;
                                    case 6:
                                        $str.=' <>';
                                        if ($char[$par]!=$cond['value']) $true_cond++;
                                    break;
                                }
                                $str.=' '.$cond['value'];
                            }
                            if ($cur_cond<$vsego_cond) $str.=' <strong>И</strong> ';
                        }
                        if ($cur_nom<$vsego_nom) $str.='<br><strong>ИЛИ</strong><br>';
                        if ($true_cond==$vsego_cond OR ((isset($ask_pass)) AND ($true_cond+1==$vsego_cond))) $flag = 1;
                    }
                    $condition_text.='<p>'.$str.'</i></p><br />';
                    if ($flag==0) $tme='net';
                }

                while (list($val,$id)=each($a))
                {
                    if($char['clan_id']==$id and mysql_result(myquery("SELECT name FROM game_har WHERE id=".$char['race'].""),0,0)==$race)
                    {
                        while (list($val,$id)=each($b))
                        {
                            if($char['user_id']==$id)
                            {
                                if (isset($chage) and !isset($ask_pass) and $tme=='ok')
                                {
                                    $result_tonewmap = myquery("SELECT to_map_name, to_map_xpos, to_map_ypos FROM game_map WHERE name='".$char['map_name']."' AND xpos='".$char['map_xpos']."' AND ypos='".$char['map_ypos']."' AND to_map_name<>'0' LIMIT 1");
                                    if (mysql_num_rows($result_tonewmap))
                                    {
                                        $tonewmap = mysql_fetch_array($result_tonewmap);
                                        if ($char['GP'] >= $gp or $gp==0)
                                        {
										    if ($gp>0)
										    {
											    $result_usermap = myquery("UPDATE game_users SET GP=GP-$gp,CW=CW-'".($gp*money_weight)."' WHERE user_id='".$char['user_id']."' LIMIT 1");
                                                setGP($user_id,-$gp,18);
										    }
										    $result_usermap = myquery("UPDATE game_users_map SET map_name='".$tonewmap['to_map_name']."', map_xpos='".$tonewmap['to_map_xpos']."', map_ypos='".$tonewmap['to_map_ypos']."' WHERE user_id='".$char['user_id']."' LIMIT 1");
                                            //Закрываем портал в Туманные Горы после входа в него
                                            if ($gorod['town']==id_portal_tuman)
                                            {
                                                myquery("UPDATE game_map SET town=0, to_map_name=0, to_map_xpos=0, to_map_ypos=0 WHERE xpos='".$char['map_xpos']."' and ypos='".$char['map_ypos']."' and name='".$char['map_name']."'");
                                                //СПАВНИМ БОТОВ В ТУМАННЫХ ГОРАХ
                                                myquery("UPDATE game_npc SET time_kill=0 WHERE map_name=".id_map_tuman."");
                                                myquery("DELETE FROM game_npc WHERE npc_id=".id_npc_nepruha." AND map_name=".id_map_tuman."");
                                                //Ставим сундуки в конце коридора
                                                myquery("DELETE FROM game_items WHERE item_id=".item_id_sunduk." AND priznak=2 AND map_name=".id_map_tuman."");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,6)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,5)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,4)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,3)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,2)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,1)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",7,0)");
                                                myquery("INSERT INTO game_items (item_id,priznak,map_name,map_xpos,map_ypos) VALUES (".item_id_sunduk.",2,".id_map_tuman.",0,7)");
                                            }
                                            //проверка на использование черного ключа
                                            if ($item_id_need==id_black_key)
                                            {
                                                $seldel_id = myquery("SELECT id FROM game_items WHERE item_id=".id_black_key." AND user_id=$user_id AND priznak=0 AND used=0 LIMIT 1");
                                                if (mysql_num_rows($seldel_id))
                                                {
                                                    $del_id = mysqlresult($seldel_id,0,0);
                                                    $Item = new Item($del_id);
                                                    $Item->admindelete();
                                                }
                                            }
										    if ($char['map_name']!=$tonewmap['to_map_name'] AND $char['map_name']!=id_map_tuman AND $tonewmap['to_map_name']!=id_map_tuman)
										    {
											    list($maze_to)=mysql_fetch_array(myquery("SELECT maze FROM game_maps WHERE id=".$tonewmap['to_map_name'].""));
											    if ($maze_to==1)
											    {
                                                    $new_year_lab = array(809,810,811,812,813);
                                                    $not_boss = 0;
                                                    if (in_array($tonewmap['to_map_name'],$new_year_lab))
                                                    {
                                                        $not_boss = 1;
                                                    }
													fill_maze_by_npc_for_user($tonewmap['to_map_name'],$user_id,$not_boss);
											    }
											    list($maze_from,$exp_win,$gp_win,$maze_name)=mysql_fetch_array(myquery("SELECT maze,exp_maze,gp_maze,name FROM game_maps WHERE id=".$char['map_name'].""));
                                                $already = mysql_result(myquery("SELECT COUNT(*) FROM game_users_maze WHERE user_id=$user_id AND maze_id=".$char['map_name'].""),0,0);
											    if ($maze_from==1 AND $exit_lab==1 AND $already==0)
											    {
												    myquery("UPDATE game_users SET maze_win=maze_win+1,EXP=EXP+$exp_win,GP=GP+$gp_win WHERE user_id=$user_id");
                                                    $theme = 'Игроком пройден лабиринт: '.$maze_name;
                                                    $message = 'Игрок [b]'.$char['name'].'[/b] прошел лабиринт: [i]'.$maze_name.'[/i]. Время выхода из лабиринта: '.date("d.m.Y H:i:s",time()).'.';
                                                    myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, clan, time) VALUES ('1', '$user_id', '$theme', '$message', '0','0',".time().")");
                                                    myquery("INSERT INTO game_users_maze (user_id,maze_id) VALUES ($user_id,".$char['map_name'].")");
                                                    setGP($user_id,$gp_win,28);
                                                    setEXP($user_id,$exp_win,3);
											    }
										    }
										    setLocation("act.php");
                                        }
                                        else
                                        {
                                            echo'Плата за проход <font color=ff0000><b>'.$gp.'</b></font> золотых! У тебя их НЕТ!';
									    }
								    }
                                }
                                if ($pass_time)
                                {
                                    echo nl2br($text).'<br>';
                                    echo $condition_text;
                                }

                                if (($char['GP'] >= $gp or $gp==0) and !isset($ask_pass) and $tme=='ok')
                                {
                                    echo'<a href="act.php?chage=yes';
                                    if (isset($pass))
                                    {
                                        echo '&keyword='.$pass;
                                    }
                                    echo '">'.$name.'</a><br>';
                                    if ($gp!='0') echo'Плата за проход <font color=ff0000><b>'.$gp.'</b></font> золотых!';
                                }
                            }
                        }
                    }
                }
           }
           else
           {
                $sel=myquery("select * from game_gorod where town='".$gorod['town']."'");
                $gorod=mysql_fetch_array($sel);
                $clan = $gorod['clan'];
                $race = $gorod['race'];
                if($gorod['clan']==0 or $gorod['clan']=='') $clan=$char['clan_id'];
                if($gorod['race']==0) $race=$char['race'];
                $dostup=1;
                $race1 = myquery("SELECT race FROM game_har WHERE id=".$char['race']."");
                if (mysql_num_rows($race1))
                {
                    list($race1) = mysql_fetch_array($race1);
                    $race1 = 'enter_'.$race1;
                    if (!isset($gorod[$race1])) $dostup=1;
                    elseif ($gorod[$race1]!='1') $dostup=0;
                }
                if ($gorod['rustown']!='' AND $dostup!=1)
                {
                    echo '<font face="Tahoma" size="3">'.$gorod['rustown'].'</font><br><font color=#FF0000><b>Доступ в этот город для твоей расы закрыт!</b></font>';
                }

                if(($char['clan_id']==$clan or $clan==0) and $char['race']==$race)
                {
			echo'<font face="Tahoma" size="3">';
			if ($gorod['rustown']!='' AND $dostup==1)
			{
				$clan_nalog=0;
				if ($gorod['clan']>1)
				{
					$test_nalog = myquery("SELECT id, summa FROM game_clans_taxes WHERE clan_id=".$clan." Order By id DESC Limit 1");
					list($id,$clan_nalog)=mysql_fetch_array($test_nalog);
				}
				if ($clan_nalog==0)
					echo ('<a href="lib/town.php">ВОЙТИ в ГОРОД "'.$gorod['rustown'].'"</a>');
				else 
					echo ('Город <b><span style="color:yellow">'.$gorod['rustown'].'</span stylet></b> слишком долго ждал от вас благодарности за гостеприимство. В связи с неуплатой налогов он закрывает перед вашим кланом ворота. Вернуться сможете только после того как оплатите долги в <i>"Управление кланом"</i>.');
			}
			echo ('</font><br>');
                }
		echo ' '.stripslashes(nl2br($gorod['opis'])).'';
            }
       }
    }

    echo '</td></tr><tr><td>';

    //Проверим на квесты
    //Перерыв между неудачным прохождением квеста и новой попыткой - 5 минут
    $last_time = time()-10*60;
    $questsel = myquery("SELECT * FROM game_quest WHERE map_name=".$char['map_name']." AND map_xpos=".$char['map_xpos']." AND map_ypos=".$char['map_ypos']." AND min_clevel<=".$char['clevel']." AND max_clevel>=".$char['clevel']." LIMIT 1");
    if (mysql_num_rows($questsel))
    {
	    $quest = mysql_fetch_array($questsel);
	    $check = mysql_result(myquery("SELECT COUNT(*) FROM game_quest_users WHERE user_id=$user_id AND quest_id=".$quest['id']." AND (last_time>=$last_time OR finish>=1)"),0,0);
	    if($char['map_name']==666)
        {
    	    $check=1;
        }
        if ($check==0)
        {
            if (isset($_SESSION['quest1_step']) AND $quest['id']==1)
            {
                if (
                $_SESSION['quest1_step']==8 OR
                $_SESSION['quest1_step']==15 OR
                $_SESSION['quest1_step']==25 OR
                $_SESSION['quest1_step']==28 OR
                $_SESSION['quest1_step']==31 OR
                $_SESSION['quest1_step']==33 OR
                $_SESSION['quest1_step']==35 OR
                $_SESSION['quest1_step']==37 OR
                $_SESSION['quest1_step']==39 OR
                $_SESSION['quest1_step']==41 OR
                $_SESSION['quest1_step']==43 OR
                $_SESSION['quest1_step']==45 OR
                $_SESSION['quest1_step']==52
                )
                {
                   unset($_SESSION['quest1_lose']);
			       echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">Продолжить квест</a></div>';
                }
                elseif (
                $_SESSION['quest1_step']==7 OR
                $_SESSION['quest1_step']==14 OR
                $_SESSION['quest1_step']==24 OR
                $_SESSION['quest1_step']==27 OR
                $_SESSION['quest1_step']==30 OR
                $_SESSION['quest1_step']==32 OR
                $_SESSION['quest1_step']==34 OR
			    $_SESSION['quest1_step']==36 OR
                $_SESSION['quest1_step']==38 OR
                $_SESSION['quest1_step']==40 OR
                $_SESSION['quest1_step']==42 OR
                $_SESSION['quest1_step']==44 OR
                $_SESSION['quest1_step']==51
                )
                {
            	    $_SESSION['quest1_lose']=1;
				    echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">Продолжить квест</a></div>';
                }
                else
                {
                    //врубаем квест
                    echo $quest['begin'];
				    echo '<br><div align=right><a href="quest/'.$quest['filename'].'?begin" target="game">'.$quest['vhod'].'</a></div>';
                }
            }
            elseif (isset($_SESSION['quest2_step']) AND $quest['id']==21)
            {
                if
                (
                    $_SESSION['quest2_step']==202 OR
                    $_SESSION['quest2_step']==204 OR
                    $_SESSION['quest2_step']==206 OR
                    $_SESSION['quest2_step']==208 OR
                    $_SESSION['quest2_step']==210 OR
                    $_SESSION['quest2_step']==212
                )
                   echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">Продолжить квест</a></div>';
                elseif (
                $_SESSION['quest2_step']==201 OR
                $_SESSION['quest2_step']==203 OR
                $_SESSION['quest2_step']==205 OR
                $_SESSION['quest2_step']==207 OR
                $_SESSION['quest2_step']==209 OR
                $_SESSION['quest2_step']==211
                )
                {
            	    $_SESSION['quest2_lose']=1;
               	    echo '<br><div align=right><a href="quest/'.$quest['filename'].'?win_bot" target="game">Продолжить квест</a></div>';
                }
                else
                {
                    //врубаем квест
                    echo $quest['begin'];
			        echo '<br><div align=right><a href="quest/'.$quest['filename'].'?begin" target="game">'.$quest['vhod'].'</a></div>';
		        }
            }
            else
            {
                //врубаем квест
                echo $quest['begin'];
                echo '<br><div align=right><a href="quest/'.$quest['filename'].'?begin" target="game">'.$quest['vhod'].'</a></div>';
            }
        }
        else
        {
            if ($quest['id']!=1 AND $quest['id']!=21)
            {
     	        //врубаем квест
                echo $quest['begin'];
                //вывод тюрьмы
                if($char['map_name']==666)
                {
                    //echo '<div style="postion:absolute;">';
                    //$rand_x = mt_rand(0,300);
                    //$rand_y = mt_rand(0,300);
                    $checksum = md5(mt_rand(0,1000)+$user_id+time());
                    $_SESSION['katorga_checksum_href'] = $checksum;
                    $_SESSION['right_knopka']=mt_rand(1,4);
                    //if ($char['map_xpos']==1 AND $char['map_ypos']==1)
                    //    echo '<div style="position:relative;top:'.$rand_x.'px;left:'.$rand_y.'px;"><a href="quest/'.$quest['filename'].'?id='.$checksum.'" target="game">'.$quest['vhod'].'</a></div>';
                    //else
                    echo '<form action="quest/'.$quest['filename'].'?id='.$checksum.'" method="post">';
                    for ($ind=1;$ind<=4;$ind++)
                    {
                        if ($_SESSION['right_knopka']==$ind)
                        {
                            echo '<br /><br /><input type="submit" name="prison_button'.$ind.'" value="Крутить +1 оборот">';
                        }
                        else
                        {
                            echo '<br /><br /><input type="submit" name="prison_button'.$ind.'" value="Крутить -2 оборота">';
                        }
                    }
                    echo '</form>';
                    //echo '<a href="quest/'.$quest['filename'].'?id='.$checksum.'" target="game">'.$quest['vhod'].'</a>';
                    //echo '</div>';
                }
                else
                    echo '<br><div align=right><a href="quest/'.$quest['filename'].'" target="game">'.$quest['vhod'].'</a></div>';
            }
        }
    }

    echo '</td></tr><tr><td>';

    //Теперь проверим на движковые квесты
    include("quest/quest_engine_types/quest_engine_outside_print.php");

    //Покажем магазины на гексе
    $select=myquery("select * from game_shop where map='".$char['map_name']."' and pos_x='".$char['map_xpos']."' and pos_y='".$char['map_ypos']."' ");
    if (mysql_num_rows($select))
    {
        while ($shop=mysql_fetch_array($select))
        {
            echo "<b><font color=\"cccccc\">$shop[text]</font></b><br>";
            echo $shop['privet'];
		    echo '<br><div align=right><a href="shop/shop.php">'.$shop['vhod'].'</a></div>';
	    }
    }

    echo '</td></tr><tr><td>';

    //Покажем шахты на гексе
    $select=myquery("select * from game_mine where map='".$char['map_name']."' and pos_x='".$char['map_xpos']."' and pos_y='".$char['map_ypos']."' ");
    if (mysql_num_rows($select))
    {
        while ($shop=mysql_fetch_array($select))
        {
            echo "<b><font color=\"cccccc\">$shop[text]</font></b><br>";
            echo $shop['privet'];
            echo '<br><div align=right><a href="mine.php?option=vhod">ВОЙТИ в ШАХТУ "'.$shop['name'].'"</a></div>';
        }
    }

    echo '</td></tr><tr><td>';

    //Покажем обелиски на гексе
    $obelisk_query = "select * from game_obelisk where map_name=".$char['map_name']." and map_xpos=".$char['map_xpos']." and map_ypos=".$char['map_ypos']." AND user_id=0 AND time_begin<=".time()." AND time_begin>0 AND type NOT IN (SELECT harka FROM game_obelisk_users WHERE type=0 AND time_end>=".time().") LIMIT 1";
    if (isset($_GET['obelisk']))
    {
        $prov = myquery($obelisk_query);
        if (mysql_num_rows($prov))
        {
            $obelisk = mysql_fetch_array($prov);
            $har['STR']='твоя <b>Сила</b> увеличилась';
            $har['NTL']='твой <b>Интеллект</b> увеличился';
            $har['PIE']='твоя <b>Ловкость</b> увеличилась';
            $har['VIT']='твоя <b>Защита</b> увеличилась';
            $har['DEX']='твоя <b>Выносливость</b> увеличилась';
            $har['SPD']='твоя <b>Мудрость</b> увеличилась';
            if (isset($har[$obelisk['type']]))
            {
                $str = $obelisk['type'];
                $add = floor($char[$str]*0.1);
                $harka = $har[$str];
                echo 'Ты '.echo_sex('преклонил','преклонила').' колено у "'.$obelisk['name'].'"<br />
                И вдруг ты '.echo_sex('почувствовал','почувствовала').', что мир на короткий миг вокруг тебя неуловимо изменился<br />
                Пытаясь понять, что же сейчас произошло, ты вдруг '.echo_sex('обнаружил','обнаружила').' что '.$harka.' на '.$add.' единиц.<br />
                И тут ты '.echo_sex('услышал','услышала').' тихий голос, который шел из ниоткуда:<br />
                - Знай путник, что сила обелиска будет помогать тебе только в течение одного игрового месяца!<br />';
                myquery("UPDATE game_obelisk SET time_begin=0,time_end=0,user_id=$user_id,count_use=count_use+1 WHERE id=".$obelisk['id']."");
                myquery("INSERT INTO `game_obelisk_users` (`user_id` ,`harka` ,`time_end` ,`user_name` ,`value` ,`type` ) VALUES ($user_id,'$str',".(time()+24*60*60).",'".$char['name']."','$add',0)");
                myquery("UPDATE game_users SET $str=$str+$add WHERE user_id=$user_id");
            }
        }
    }
    $select=myquery($obelisk_query);
    if (mysql_num_rows($select))
    {
        while ($obelisk=mysql_fetch_array($select))
        {
            echo '<div>';
            switch ($obelisk['type'])
            {
                case 'STR':
                    $harka = 'Сила';
                    echo "<img src=\"http://".IMG_DOMAIN."/obelisk/obelisk_krasniy.gif\" align=\"left\">";
                break;
                case 'DEX':
                    $harka = 'Выносливость';
                    echo "<img src=\"http://".IMG_DOMAIN."/obelisk/obelisk_siniy.gif\" align=\"left\">";
                break;
                case 'SPD':
                    $harka = 'Мудрость';
                    echo "<img src=\"http://".IMG_DOMAIN."/obelisk/obelisk_fiolet.gif\" align=\"left\">";
                break;
                case 'NTL':
                    $harka = 'Интеллект';
                    echo "<img src=\"http://".IMG_DOMAIN."/obelisk/obelisk_dark.gif\" align=\"left\">";
                break;
                case 'PIE':
                    $harka = 'Ловкость';
                    echo "<img src=\"http://".IMG_DOMAIN."/obelisk/obelisk_jeltiy.gif\" align=\"left\">";
                break;
                case 'VIT':
                    $harka = 'Защита';
                    echo "<img src=\"http://".IMG_DOMAIN."/obelisk/obelisk_temniy.gif\" align=\"left\">";
                break;
            }
            echo "<b><font color=\"cccccc\">".$obelisk['name']."</font></b><br>";
            echo '<p align="justify">'.$obelisk['opis'].'</p>';
            echo '<br><div align=left><a href="?func=main&obelisk" target="game">Преклонить колено перед "'.$obelisk['name'].'"</a></div></div><br /><br />';
        }
    }

    echo '</td></tr></table>';
}
if (function_exists("save_debug")) save_debug();

?>