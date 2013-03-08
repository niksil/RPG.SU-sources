<?
//ob_start('ob_gzhandler',9);
include('inc/template.inc.php');
include('inc/template_header.inc.php');
echo '
<CENTER>
Ты можешь просмотреть карту Средиземья в двух вариантах:

<ul>
  <li>1. <a href = "/images/sz.html">Карта Средиземья со всплывающими подсказками по координатам (загружается ооооочень долго)</a></li>
  <li>2. <a href = "/images/map/Middleearth.jpg">Карта Средиземья в виде одного графического файла (448617 байт)</a></li>
</ul>
<br><br><br>

Ты можешь просмотреть карту Белерианда в двух вариантах:

<ul>
  <li>1. <a href = "/images/bel.html">Карта Белерианда со всплывающими подсказками по координатам (загружается ооооочень долго)</a></li>
  <li>2. <a href = "/images/map/beleriand.jpg">Карта Белерианда в виде одного графического файла (490088 байт)</a></li>
</ul>';

//OpenTable('close');