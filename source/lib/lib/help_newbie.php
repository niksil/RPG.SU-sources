<?

if (function_exists("start_debug")) start_debug(); 

if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

include('inc/template.inc.php');
require('inc/template_header.inc.php');

echo'<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">';
OpenTable('title');

?>'

<head>
<title>Инструкция для ленивых.</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="Keywords" content="">
<style type="text/css">@import url("style/global.css");</style>

</head>

<body>
<br /><br />
<center><b><font face="verdana" size=4 color="#FF0000">Инструкция для ленивых.</font></b></center>
<br />
<hr>
<table width="100%"><tr><td width="15"></td><td><p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

 В игру вы попадаете на закрытую территорию «Гильдия новичков» на квадрат с координатами <font face="verdana" size=3 color="#FF0000">8;1</font>, на этом же квадрате находится единственный в тех местах <font face="verdana" size=3 color="#FF0000">город</font>. В этом городе в разделе <font face="verdana" size=3 color="#FF0000">«Таверна»</font> вы можете <font face="verdana" size=3 color="#FF0000">пополнить здоровье, ману и энергию</font> выбрав соответствующее блюдо, а в разделе <font face="verdana" size=3 color="#FF0000">«Тренер»</font> вы можете <font face="verdana" size=3 color="#FF0000">прокачать</font> своего героя.

</p>
<p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

Для того, что бы прокачать своего персонажа и <font face="verdana" size=3 color="#FF0000">выйти из «Гильдии новичков»</font> вам <font face="verdana" size=3 color="#FF0000">необходимо бить ботов</font>. Вы сможете найти их, нажав в правом нижнем углу раздел <font face="verdana" size=3 color="#FF0000">«Карта местности»</font>. Там написаны координаты всех ботов, их сила удара и жизнь. Идите на указанную клетку с ботом. Рядом с ботом вы увидите маленький меч, нажмите на него и начнётся бой. Также можно начать бой просто кликнув мышкой по рисунку бота. В бою вы можете бить соперника кулаком (в дальнейшем при прокачке оружием или магией). <font face="verdana" size=3 color="#FF0000">За победу вы получаете очки</font> (exp), при накоплении необходимой суммы очков вы можете <font face="verdana" size=3 color="#FF0000">прокачать персонаж (в городе в разделе «Тренер»)</font>. Вы пришли на указанный квадрат, но <font face="verdana" size=3 color="#FF0000">там нет бота?</font> Обновите окно «Карта местности» если цифры не изменились на карте, то просто подождите и бот появится, если изменились, то бота до вас убил другой игрок, идите к другому боту.

</p>
<p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

Для того, что бы ваш персонаж был сильнее, <font face="verdana" size=3 color="#FF0000">купите ему оружие, доспех, шлем и щит</font>, сделать это можно у <font face="verdana" size=3 color="#FF0000">торговца на клетке 13,16</font>. Нажмите на картинку с предметом, и откроется энциклопедия, где вы сможете прочитать информацию о данной вещи. <font face="verdana" size=3 color="#FF0000"><b>Внимание</b>: не все предметы вы можете одеть!</font> Прежде чем купить внимательно посмотрите на то, какой уровень необходим для той вещи, что вы выбрали и какие характеристики требуются.

</p>
<p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

Вы купили предметы, теперь <font face="verdana" size=3 color="#FF0000">нужно их одеть</font>. Нажмите вверху кнопку <font face="verdana" size=3 color="#FF0000">«Инвентарь»</font> (вверху 2-я по счёту) и вы увидите те предметы, что у вас есть. Направьте стрелочку мыши на картинку с предметом, появится маленькое окно и если вы правильно выбрали вещь, то будет написано «вы можете надеть этот предмет», нажмите на картинку с вещью, и она переместиться на вашего персонажа. Если вещь не подходит её можно продать обратно торговцу (там есть раздел «Продажа»). Одеть можно 1-ну вещь в один квадратик, например 1 меч или 1 кольцо, для каждой вещи предназначено своё место!

</p>
<p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

Вы можете драться с <font face="verdana" size=3 color="#FF0000">другими игроками</font>, для этого подойдите к игроку и нажмите на меч <font face="verdana" size=3 color="#FF0000">рядом с картинкой</font> соперника. <font face="verdana" size=3 color="#FF0000">Игроков 0 уровня никто не может бить</font>, поэтому пока вы 0 уровень и на вас не могут напасть, а вы можете. Посмотреть, где в данный момент находятся игроки, можно нажав кнопку <font face="verdana" size=3 color="#FF0000">«Онлайн»</font> (вверху 5-я по счёту). Вернуться в игру обратно, можно нажав кнопку <font face="verdana" size=3 color="#FF0000">«Игра»</font> (вверху 1-я).

</p>
<p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

Для того чтоб <font face="verdana" size=3 color="#FF0000">выйти из «Гильдии новичков»</font>, набирайте очки (exp). Для 1-го уровня нужно 200 очков, для 2-го 400 , для 3-го 1200, для 4-го 2400, для 5-го 4000. В Гильдии Новичков <font face="verdana" size=3 color="#FF0000">уровни вам будут повышаться автоматически</font> при наборе нужного для уровня количества опыта. Когда вы <font face="verdana" size=3 color="#FF0000">достигнете 5-го уровня</font> - Вам предложат выйти из Гильдии Новичков в Средиземье и выдадут некоторую сумму в качестве "подъемных". И уже <font face="verdana" size=3 color="#FF0000">в Средиземье вы сможете распределить накопленные и нераспределенные вами очки навыков и характеристик!</font>

</p>
<p style="word-spacing: 1; margin-left: 1; margin-right: 1; margin-top: 10; margin-bottom: 10" align="justify"><font face="Verdana" size="3" color="#FFFF80">

<font face="verdana" size=4 color="#FF0000"><b>Мультоводство запрещено</b></font>! Мультоводство – это создание, ещё хотя бы 1-го персонажа, помимо вашего основного. Если вы хотите играть другим персонажем, обратитесь к администрации, что б удалили 1-го. Для <font face="verdana" size=3 color="#FF0000">этого есть специальный раздел</font> на форуме. Иначе будут удалены все.

</font></p></td><td width="15"></td></tr></table>
<hr>

Составлено игроком Nit_z &copy;

<?

OpenTable('close');

echo '</td><td width="172" valign="top">';
include('inc/template_stats.inc.php');
echo '</td></tr></table>';

if (function_exists("save_debug")) save_debug(); 

?>