<?php

if (function_exists("start_debug")) start_debug(); 

function print_spellbook($magic)
//параметром передается ассоциативный массив. Ключи - имена школ магии. Значение: 0 - не показывать, 1 - показывать OFF, 2 - показывать ON
{
    $current_bookmark = 1;
	echo '
	<div style="position:relative;">
	<img src="http://'.IMG_DOMAIN.'/spellbook/kniga.jpg" border=0 alt="Книга заклинаний" title="">';
    foreach ($magic as $key => $value) {
        switch ($key)
        {
            case 'fire':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=fire"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/ogon_off.gif" alt="Магия Огня" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/ogon_on.gif" alt="Магия Огня" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;   
            
            case 'water':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=water"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/voda_off.gif" alt="Магия Воды" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/voda_on.gif" alt="Магия Воды" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'earth':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=earth"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/zemlya_off.gif" alt="Магия Земли" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/zemlya_on.gif" alt="Магия Земли" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'air':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=air"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/vozduh_off.gif" alt="Магия Воздуха" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/vozduh_on.gif" alt="Магия Воздуха" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'death':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=death"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/smert_off.gif" alt="Магия Смерти" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/smert_on.gif" alt="Магия Смерти" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'life':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=life"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/jizn_off.gif" alt="Магия Жизни" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/jizn_on.gif" alt="Магия Жизни" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'dark':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=dark"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/tma_off.gif" alt="Магия Тьмы" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/tma_on.gif" alt="Магия Тьмы" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'light':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=light"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/svet_off.gif" alt="Магия Света" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/svet_on.gif" alt="Магия Света" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
            case 'astral':
            if ($value==1)
            {
		        echo '
		        <a href="?func=spell_book&magic=astral"><img border=0 src="http://'.IMG_DOMAIN.'/spellbook/astral_off.gif" alt="Магия Астрала" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; "></a>';
                $current_bookmark++;
            }
            elseif ($value==2)
            {
		        echo '
		        <img border=0 src="http://'.IMG_DOMAIN.'/spellbook/astral_on.gif" alt="Магия Астрала" style="position:absolute; left:817px; top:'.(79*$current_bookmark).'px; ">';
                $current_bookmark++;
            }
            break;
               
        }                                                                                                                                      
    }
}


$magic = Array();
$magic['fire']=1;
$magic['water']=1;
$magic['earth']=1;
$magic['air']=1;
$magic['astral']=1;
$magic['death']=0;
$magic['life']=0;
$magic['light']=0;
$magic['dark']=0;
if (isset($_GET['magic']))
{
    $magic[$_GET['magic']]=2;
}
else
{
    $magic['fire']=2;
}
print_spellbook($magic);

if (function_exists("save_debug")) save_debug(); 

?>