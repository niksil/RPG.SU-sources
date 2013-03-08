<?php
require_once("../../inc/engine.inc.php");

if (file_exists(''.PATCH.'/cache/com.xml'))
{
    $xml = file_get_contents(''.PATCH.'/cache/com.xml');
    $arr = xml2array($xml);

    $gdate_mt = "";

    switch ($gdate_mod)
    {
    case 0:
      $gdate_mt = "Cегодня, ";
      $gdate = date("d.m.Y", time());
      break;

    case 1:
      $gdate_mt = "Завтра, ";
      $gdate = date("d.m.Y", time() + 86400);
      break;

    case -1:
      $gdate_mt = "Вчера, ";
      $gdate = date("d.m.Y", time() - 86400);
      break;

    default:
      $gdate = date("d.m.Y", time() + 7200);
    }

    switch ($gdate)
    {
    case $arr['horo']['_c']['date']['_a']['yesterday'];
      $gdayname = 'yesterday';
      break;
    case $arr['horo']['_c']['date']['_a']['today'];
      $gdayname = 'today';
      break;
    case $arr['horo']['_c']['date']['_a']['tomorrow'];
      $gdayname = 'tomorrow';
      break;
    case $arr['horo']['_c']['date']['_a']['tomorrow02'];
      $gdayname = 'tomorrow02';
      break;
    default:
      $gdayname = 'today';
      break;
    }

/*
    $ts = strtotime($arr['horo']['_c']['date']['_a'][$gdayname]);
    date ("l, jS F Y", $ts).
*/
    $nline = $gdate_mt.$arr['horo']['_c']['date']['_a'][$gdayname].
             "<br/><p>".
             iconv("UTF-8","Windows-1251",$arr['horo']['_c'][$gor]['_c'][$gdayname]['_v']).
             "</p>";
}
else
{
    $nline = "<br/><p>".
             iconv("UTF-8","Windows-1251",'Ошибка получения гороскопа').
             "</p>";
}
?>