<?
if (function_exists("start_debug")) start_debug();
if ($adm['bank']>=1)
{
//готовый скрипт статистики , прикрученный к админке на равне с "деньги в банке".

//$dirclass="../class";
//include('../inc/xray.inc.php');
//include('../inc/lib.inc.php');
//DbConnect();
global $in,$reason,$r_name;


// навигация
//    ?opt=main&option=gp_stat&
function nav() {
// echo"<html><head>"
  //. "<title>Деньговая статистика.</title></head><body>"
  echo "<table border=\"1\" width=\"100%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\"><a href=\"?opt=main&option=gp_stat&\">Общая статистика.</a></td>"
  . "<td align=\"center\"><a href=\"?opt=main&option=gp_stat&in=list_r\">По всем Reason.</a></td>"
  . "<td align=\"center\"><a href=\"?opt=main&option=gp_stat&in=date_r\">Общая за период дат.</a></td>"
  . "<td align=\"center\"><a href=\"?opt=main&option=gp_stat&in=date_all\">По всем Reason за период дат.</a></td>"
  . "</tr></table><hr>";
}
function footer() {
//echo"<hr>©&nbsp;X-ray</body></html>";
}

// отображение приход-расход-баланс по умолчанию.
function main() {
global $reason,$r_name,$r_type;
$sql = myquery("select * from game_users_stat_gp order by user_id");

## начало функции вывода общей статистики, параметры выборки - всегда выше.
 // зануление выводящихся переменных
$gp=0;
$gp_in=0;
$gp_out=0;
$gp_game_in=0;
$gp_game_out=0;
$gp_user_in=0;
$gp_user_out=0;
$gp_nul=0;
$gp_adm_in=0;
$gp_adm_out=0;
$gp_zeml_in=0;
$gp_zeml_out=0;
nav();
while($row = mysql_fetch_array($sql))  // выборка из базы по указанным выше условиям.
{
$reason=$row['reason'];
r($reason);  // получение имен reason и типа действия.
switch($r_type) {   // обсчет каждого типа действия.
case "0": // 0- не указано.
$gp_nul=$gp_nul+$row['gp'];
break;
case "1": // 1- введено в игру (игроку).
//if ($row['gp']<=0){   // вот не знаю- бывает отрицательные данные на внесение игроку?
//$gp_in=$gp_in-$row['gp'];
//}else{
$gp_in=$gp_in+$row['gp'];
//}
  break;
case "2": // 2- выведено из игры (от игрока).
if ($row['gp']<=0){  // проверка на отрицательные суммы а то криво как то оно в базе записано все.
 $gp_out=$gp_out+$row['gp'];
}else{
$gp_out=$gp_out-$row['gp'];
}
break;
case "3": // 3-(6) передано внутри игры (от одного игрока другому)..
$gp_game_out=$gp_game_out+$row['gp'];
break;
case "4": // 4-(7) снято внутри игрока (в банк)
$gp_user_out=$gp_user_out+$row['gp'];
break;
case "5": // 5- Админские действия
// проверка отрицательности и разделение на дал-взял
if($row['gp']>=0){
$gp_adm_in=$gp_adm_in+$row['gp']; // Дали игроку
}else{
$gp_adm_out=$gp_adm_out+$row['gp'];   // отобрали у игрока.
}
break;
case "6": // 6-(3) получено  внутри игры (от одного игрока другому).
$gp_game_in=$gp_game_in+$row['gp'];
break;
case "7": // 7-(4) получено внутри игрока (из банка)
$gp_user_in=$gp_user_in+$row['gp'];

break;
break;
case "8":
// 8- Торговля землями между игроками.
$g_z= $row['gp'];
if ($g_z>=0){  // продана земля.
$gp_zeml_in=$gp_zeml_in+$row['gp'];
}else{ // куплена земля.
$gp_zeml_out=$gp_zeml_out+$row['gp'];
}
break;
default:
$gp_nul=$gp_nul+$row['gp'];
break;
}
 // echo "gp=$row[gp], reason=$row[reason]=$r_name r_type=$r_type<br>"; // тупо вывод сторки из базы.
}
echo "На данный момент:<br> ";
echo"<table border=\"1\" width=\"65%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">№</span></td>"
  . "<td align=\"center\" width=\"85%\">Тип Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">сумма</span></td>"
  . "</tr><tr>";
echo"<td align=\"center\" width=\"5%\">0</td>"
  . "<td align=\"center\" width=\"85%\">Не определенные действия</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_nul</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">1(+)</td>"
  . "<td align=\"center\" width=\"85%\">Введено в игру (игрокам)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">2(-)</td>"
  . "<td align=\"center\" width=\"85%\">Выведено из игры (от игроков)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">3(-)</td>"
  . "<td align=\"center\" width=\"85%\">Передано внутри игры (от одного игрока другому)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">6(+)</td>"
  . "<td align=\"center\" width=\"85%\">Получено  внутри игры (от одного игрока другому)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">4(-)</td>"
  . "<td align=\"center\" width=\"85%\">Снято внутри игрока (в банк)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">7(+)</td>"
  . "<td align=\"center\" width=\"85%\">Получено внутри игрока (из банка)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(+)</td>"
  . "<td align=\"center\" width=\"85%\">Продано земли</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(-)</td>"
  . "<td align=\"center\" width=\"85%\">Куплено земли</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(+)</td>"
  . "<td align=\"center\" width=\"85%\">Админы дали игрокам</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(-)</td>"
  . "<td align=\"center\" width=\"85%\">Админы отобрали у игроков</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_out</b></td></tr>";
echo"</tr></table>";
echo"<p>// Примечания к описанию типов Reason:<br>"
  . "// 0- не указано. или неопределено, значит или в базе есть reason с номером больше 62, которого скрипт еще не знает. <br>Или же в базу произошла запись неизвестного действия.</p>";

## конец функции вывода общей статистики, параметры выборки - всегда выше.
## вывод footer() включен ниже.

footer();
}

// статистика по reason
function reason(){
global $reason,$r_name,$gp_reason_min,$gp_reason_plus;
$sql = myquery("select * from game_users_stat_gp where reason=$reason");
$gp_reason_min  =0;
$gp_reason_plus =0;
while($row = mysql_fetch_array($sql))
{
if ($row['gp']<=0){
$gp_reason_min= $gp_reason_min+$row['gp'];
}else{
$gp_reason_plus = $gp_reason_plus+$row['gp'];
}
}
}


// Листинг имен reason для выбора статистики по ним.
function list_r(){
global $db,$reason,$r_name,$gp_reason_plus,$gp_reason_min;
$reason=1;
   nav();
$gp_r_plus= 0;
$gp_r_min= 0;
echo"<table border=\"1\" width=\"70%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">№</span></td>"
  . "<td align=\"left\" width=\"75%\">Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">сумма в плюс</span></td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">сумма в минус</span></td>"
  . "</tr>"
 ."";

while($reason!=62)
{
r($reason);
reason($reason);
echo"<tr><td align=\"center\" width=\"5%\">$reason</td>"
  . "<td align=\"left\" width=\"75%\">$r_name</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_plus</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_min</td>"
 ."</tr>";
$gp_r_plus =$gp_r_plus +$gp_reason_plus;
$gp_r_min =$gp_r_min +$gp_reason_min;
$reason=++$reason;
}
echo"<tr><td align=\"center\" width=\"5%\">==</td>"
  . "<td align=\"left\" width=\"75%\">Итого:</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_plus</b></td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_min</b></td>"
 ."</tr>";
echo"</table>";
footer();
}


## Запрос общей статистике по всем reason за периот даты.
 function date_all(){
 global $db,$reason,$r_name,$gp_reason_plus,$gp_reason_min,$r_type;
 ##########
$reason=1;
nav();
// функция запроса даты и выборки по дате,
// nav();
echo "<form action=\"\" method=\"post\" name=\"forma\"> Показать статистику за дату:<br>";
// ввод дата_b
echo "С&nbsp;<input type=\"text\" name=\"data_b\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_b'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\">";
// ввод дата_e
echo "&nbsp;по&nbsp;<input type=\"text\" name=\"data_e\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_e'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";

// ввод имени игрока
echo "Имя игрока&nbsp;<input type=\"text\" name=\"u_name\" value=\"";
if (isset($_REQUEST['u_name']))
{
echo $_REQUEST['u_name'];
}
else
{
echo 'Имя игрока';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";

echo "<input type=\"submit\" value=\"Показать статистику\" name=\"submit\"></form>";
echo "Введите дату в формате ГГГГ-ММ-ДД<br />Имя игрока не обязательно, но если выборка по игроку не нужна, то в этом поле- все стереть или оставить то что по умолчанию.<br>По дате данные берутся \"включительно\", если нужно за 1 день- то вводить период с (этот день) по (этот же день).<br> к примеру с 2007-11-15 по 2007-11-15<hr>";

if (isset($_REQUEST['data_b']) AND isset($_REQUEST['data_e'])) // AND isset($_REQUEST['u_name'])
{
$data_b=$_REQUEST['data_b'];
$data_e=$_REQUEST['data_e'];
$u_name=$_REQUEST['u_name'];
// форматируем дату для запроса.
$date_ex_b  = explode("-",$data_b);
$date_ex_e  = explode("-",$data_e);
// здесь
// $date_ex[0] = год
// $date_ex[1] = месяц
// $date_ex[2] = день
if ($data_b!="" AND $data_e!=""){
$ex_dat_b= mktime(0,0,0,$date_ex_b[1],$date_ex_b[2],$date_ex_b[0]);
$ex_dat_e= mktime(0,0,0,$date_ex_e[1],$date_ex_e[2],$date_ex_e[0])+86399; // + сутки
}else {
$ex_dat_b =0;            //  если дата начала или конца указаны как "" то начало и конец от 0 до максимума.
$ex_dat_e=99999999999999;
}

$gp_r_plus= 0;
$gp_r_min= 0;

echo"<table border=\"1\" width=\"70%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">№</span></td>"
  . "<td align=\"left\" width=\"75%\">Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">сумма в плюс</span></td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">сумма в минус</span></td>"
  . "</tr>"
 ."";

while($reason!=62)
{
r($reason);
//reason($reason);
// от сих выборка с проверками
//$sql = myquery("select * from game_users_stat_gp where AND reason=$reason");
// пошла проверка данных
if($u_name!='' OR $u_name!='Имя игрока'){  //имя введено
$user_s = myquery("SELECT user_id FROM game_users WHERE name='$u_name'");
if (!mysql_num_rows($user_s)) $user_s = myquery("SELECT user_id FROM game_users_archive WHERE name='$u_name'");   
$mesage_1= "1";
if(mysql_num_rows($user_s)){	//имя есть в базе
	 $user_row = mysql_fetch_array($user_s) ;
	 $user_id= $user_row['user_id'];
$mesage_1= "2";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){	//даты нет
//вывод имя
$mesage_1= "3";
$mesage= "Сделан запрос с именем <b>$u_name</b> ID=<b>$user_id</b>,<br>Период дат не указан, выбраны все.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE user_id='$user_id' AND reason='$reason'");
}else{
$mesage_1= "4";
//вывод имя + дата
$mesage= "Сделан запрос с именем <b>$u_name</b> ID=<b>$user_id</b>,<br> за период дат с <b>$data_b</b> по <b>$data_e</b>.";
//$sql = myquery("SELECT * FROM game_users_stat_gp WHERE DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')>='".$_REQUEST['data_b']."' AND DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')<='".$_REQUEST['data_e']."' AND user_id='$user_id'");
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND user_id='$user_id' AND reason='$reason'");
}
}else{
$mesage_1= "5";
//ничего
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //даты нет
//вывод все
 $mesage= "Имя и период дат не указаны, выведена вся статистика.";
$sql = myquery("SELECT * FROM game_users_stat_gp where reason='$reason'");
}else{
$mesage_1= "7";
//вывод дата
$mesage= "Сделан запрос за период дат с <b>$data_b</b> по <b>$data_e</b>.<br>Имя не указано, выбраны все.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND reason='$reason'");
}
}
}else{
$mesage_1= "6";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //даты нет
//вывод все
 $mesage= "3 Имя и период дат не указаны, выведена вся статистика.";
$sql = myquery("SELECT * FROM game_users_stat_gp whwre reason='$reason'");
}else{
$mesage_1= "7";
//вывод дата
$mesage= "4 Сделан запрос за период дат с <b>$data_b</b> по <b>$data_e</b>.<br>Имя не указано, выбраны все.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND reason='$reason'");
}
}
// кончилась проверка данных 0
// по сих выборка с проверками
$gp_reason_min  =0;
$gp_reason_plus =0;
while($row = mysql_fetch_array($sql))
{
if ($row['gp']<=0){
$gp_reason_min= $gp_reason_min+$row['gp'];
}else{
$gp_reason_plus = $gp_reason_plus+$row['gp'];
}
}
//reason($reason);
echo"<tr><td align=\"center\" width=\"5%\">$reason</td>"
  . "<td align=\"left\" width=\"75%\">$r_name</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_plus</td>"
  . "<td align=\"center\" width=\"10%\">$gp_reason_min</td>"
 ."</tr>";
$gp_r_plus =$gp_r_plus +$gp_reason_plus;
$gp_r_min =$gp_r_min +$gp_reason_min;
$reason=++$reason;
}
echo"<tr><td align=\"center\" width=\"5%\">==</td>"
  . "<td align=\"left\" width=\"75%\">Итого:</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_plus</b></td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_r_min</b></td>"
 ."</tr>";
echo"</table>";
echo "$mesage<br>";
footer();

 ##///////////////////////##
 
}
}

// НИЖЕ- готово
## Запрос общей статистике по типам reason за периот даты.
function date_r(){   //
 global $db,$reason,$r_name,$r_type;

 ##########
 // функция запроса даты и выборки по дате,
 nav();
echo "<form action=\"\" method=\"post\" name=\"forma\"> Показать статистику за дату:<br>";
// ввод дата_b
echo "С&nbsp;<input type=\"text\" name=\"data_b\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_b'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\">";
// ввод дата_e
echo "&nbsp;по&nbsp;<input type=\"text\" name=\"data_e\" value=\"";
if (isset($_REQUEST['data_e'])){
echo $_REQUEST['data_e'];
}else{
echo '0000-00-00';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";
// ввод имени игрока
echo "Имя игрока&nbsp;<input type=\"text\" name=\"u_name\" value=\"";
if (isset($_REQUEST['u_name']))
{
echo $_REQUEST['u_name'];
}
else
{
echo 'Имя игрока';
}
echo "\" size=\"15\" maxsize=\"10\"><br>";
echo "<input type=\"submit\" value=\"Показать статистику\" name=\"submit\"></form>";
echo "Введите дату в формате ГГГГ-ММ-ДД<br />Имя игрока не обязательно, но если выборка по игроку не нужна, то в этом поле- все стереть или оставить то что по умолчанию.<br>По дате данные берутся \"включительно\", если нужно за 1 день- то вводить период с (этот день) по (этот же день).<br> к примеру с 2007-11-15 по 2007-11-15<hr>";

if (isset($_REQUEST['data_b']) AND isset($_REQUEST['data_e'])) // AND isset($_REQUEST['u_name'])
{
$data_b=$_REQUEST['data_b'];
$data_e=$_REQUEST['data_e'];
$u_name=$_REQUEST['u_name'];
// форматируем дату для запроса.
$date_ex_b  = explode("-",$data_b);
$date_ex_e  = explode("-",$data_e);
// здесь
// $date_ex[0] = год
// $date_ex[1] = месяц
// $date_ex[2] = день
if ($data_b!="" AND $data_e!=""){
$ex_dat_b= mktime(0,0,0,$date_ex_b[1],$date_ex_b[2],$date_ex_b[0]);
$ex_dat_e= mktime(0,0,0,$date_ex_e[1],$date_ex_e[2],$date_ex_e[0])+86399; // + сутки
}else {
$ex_dat_b =0;            //  если дата начала или конца указаны как "" то начало и конец от 0 до максимума.
$ex_dat_e=99999999999999;
}


//echo "имя $u_name дата с $data_b по $data_e<hr>";
// пошла проверка данных
if($u_name!='' OR $u_name!='Имя игрока'){  //имя введено
$user_s = myquery("SELECT user_id FROM game_users WHERE name='$u_name'");
if (!mysql_num_rows($user_s)) $user_s = myquery("SELECT user_id FROM game_users_archive WHERE name='$u_name'");   
$mesage_1= "1";
if(mysql_num_rows($user_s)){	//имя есть в базе
	 $user_row = mysql_fetch_array($user_s) ;
	 $user_id= $user_row['user_id'];
$mesage_1= "2";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){	//даты нет
//вывод имя
$mesage_1= "3";
$mesage= "Сделан запрос с именем <b>$u_name</b> ID=<b>$user_id</b>,<br>Период дат не указан, выбраны все.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE user_id='$user_id'");
}else{
$mesage_1= "4";
//вывод имя + дата
$mesage= "Сделан запрос с именем <b>$u_name</b> ID=<b>$user_id</b>,<br> за период дат с <b>$data_b</b> по <b>$data_e</b>.";
//$sql = myquery("SELECT * FROM game_users_stat_gp WHERE DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')>='".$_REQUEST['data_b']."' AND DATE_FORMAT(FROM_UNIXTIME( `timestamp` ),'%Y-%m-%d')<='".$_REQUEST['data_e']."' AND user_id='$user_id'");
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e' AND user_id='$user_id'");
}
}else{
$mesage_1= "5";
//ничего
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //даты нет
//вывод все
 $mesage= "Имя и период дат не указаны, выведена вся статистика.";
$sql = myquery("SELECT * FROM game_users_stat_gp");
}else{
$mesage_1= "7";
//вывод дата
$mesage= "Сделан запрос за период дат с <b>$data_b</b> по <b>$data_e</b>.<br>Имя не указано, выбраны все.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e'");
}
}
}else{
$mesage_1= "6";
if($data_b=='0000-00-00' OR $data_e=='0000-00-00'  OR $data_b=='' OR $data_e==''){  //даты нет
//вывод все
 $mesage= "3 Имя и период дат не указаны, выведена вся статистика.";
$sql = myquery("SELECT * FROM game_users_stat_gp");
}else{
$mesage_1= "7";
//вывод дата
$mesage= "4 Сделан запрос за период дат с <b>$data_b</b> по <b>$data_e</b>.<br>Имя не указано, выбраны все.";
$sql = myquery("SELECT * FROM game_users_stat_gp WHERE timestamp >='$ex_dat_b' AND timestamp <='$ex_dat_e'");
}
}
// кончилась проверка данных 0

//echo "Вы выбрали период с <b>$data_b</b> по <b>$data_e</b><br>";
//echo "Стадия:$mesage_1<br>"; // вывод статдий для ошибок
echo "$mesage<br>";
## начало функции вывода общей статистики, параметры выборки - всегда выше.
 // зануление выводящихся переменных
$gp=0;
$gp_in=0;
$gp_out=0;
$gp_game_in=0;
$gp_game_out=0;
$gp_user_in=0;
$gp_user_out=0;
$gp_nul=0;
$gp_adm_in=0;
$gp_adm_out=0;
$gp_zeml_in=0;
$gp_zeml_out=0;
while($row = mysql_fetch_array($sql))  // выборка из базы по указанным выше условиям.
{
$reason=$row['reason'];
r($reason);  // получение имен reason и типа действия.
switch($r_type) {   // обсчет каждого типа действия.
case "0": // 0- не указано.
$gp_nul=$gp_nul+$row['gp'];
break;
case "1": // 1- введено в игру (игроку).
//if ($row['gp']<=0){   // вот не знаю- бывает отрицательные данные на внесение игроку?
//$gp_in=$gp_in-$row['gp'];
//}else{
$gp_in=$gp_in+$row['gp'];
//}
  break;
case "2": // 2- выведено из игры (от игрока).
if ($row['gp']<=0){  // проверка на отрицательные суммы а то криво как то оно в базе записано все.
 $gp_out=$gp_out+$row['gp'];
}else{
$gp_out=$gp_out-$row['gp'];
}
break;
case "3": // 3-(6) передано внутри игры (от одного игрока другому)..
$gp_game_out=$gp_game_out+$row['gp'];
break;
case "4": // 4-(7) снято внутри игрока (в банк)
$gp_user_out=$gp_user_out+$row['gp'];
break;
case "5": // 5- Админские действия
// проверка отрицательности и разделение на дал-взял
if($row['gp']>=0){
$gp_adm_in=$gp_adm_in+$row['gp']; // Дали игроку
}else{
$gp_adm_out=$gp_adm_out+$row['gp'];   // отобрали у игрока.
}
break;
case "6": // 6-(3) получено  внутри игры (от одного игрока другому).
$gp_game_in=$gp_game_in+$row['gp'];
break;
case "7": // 7-(4) получено внутри игрока (из банка)
$gp_user_in=$gp_user_in+$row['gp'];

break;
break;
case "8":
// 8- Торговля землями между игроками.
$g_z= $row['gp'];
if ($g_z>=0){  // продана земля.
$gp_zeml_in=$gp_zeml_in+$row['gp'];
}else{ // куплена земля.
$gp_zeml_out=$gp_zeml_out+$row['gp'];
}
break;
default:
$gp_nul=$gp_nul+$row['gp'];
break;
}
 // echo "gp=$row[gp], reason=$row[reason]=$r_name r_type=$r_type<br>"; // тупо вывод сторки из базы.
}
echo "За указанный период:<br> ";
echo"<table border=\"1\" width=\"65%\" id=\"table1\" cellpadding=\"0\" cellspacing=\"1\">"
  . "<tr><td align=\"center\" width=\"5%\"><span lang=\"ru\">№</span></td>"
  . "<td align=\"center\" width=\"85%\">Тип Reason</td>"
  . "<td align=\"center\" width=\"10%\"><span lang=\"ru\">сумма</span></td>"
  . "</tr><tr>";
echo"<td align=\"center\" width=\"5%\">0</td>"
  . "<td align=\"center\" width=\"85%\">Не определенные действия</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_nul</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">1(+)</td>"
  . "<td align=\"center\" width=\"85%\">Введено в игру (игрокам)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">2(-)</td>"
  . "<td align=\"center\" width=\"85%\">Выведено из игры (от игроков)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">3(-)</td>"
  . "<td align=\"center\" width=\"85%\">Передано внутри игры (от одного игрока другому)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">6(+)</td>"
  . "<td align=\"center\" width=\"85%\">Получено  внутри игры (от одного игрока другому)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_game_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">4(-)</td>"
  . "<td align=\"center\" width=\"85%\">Снято внутри игрока (в банк)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">7(+)</td>"
  . "<td align=\"center\" width=\"85%\">Получено внутри игрока (из банка)</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_user_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(+)</td>"
  . "<td align=\"center\" width=\"85%\">Продано земли</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">8(-)</td>"
  . "<td align=\"center\" width=\"85%\">Куплено земли</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_zeml_out</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(+)</td>"
  . "<td align=\"center\" width=\"85%\">Админы дали игрокам</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_in</b></td></tr>";
echo"<td align=\"center\" width=\"5%\">5(-)</td>"
  . "<td align=\"center\" width=\"85%\">Админы отобрали у игроков</td>"
  . "<td align=\"center\" width=\"10%\"><b>$gp_adm_out</b></td></tr>";
echo"</tr></table>";
echo"<p>// Примечания к описанию типов Reason:<br>"
  . "// 0- не указано. или неопределено, значит или в базе есть reason с номером больше 62, которого скрипт еще не знает. <br>Или же в базу произошла запись неизвестного действия.</p>";

## конец функции вывода общей статистики, параметры выборки - всегда выше.
## вывод footer() включен ниже.

////  Конец проверки на введенность данных
  }else{
echo "Вы чего-то не указали";
 }

footer();
}
## КОНЕЦ Запроса общей статистике по типам reason за периот даты.

####################################
#   Присвоение коду- имени reason  #
// Описание $r_type:
// 0- не указано. или неопределено.
// 1- введено в игру (игроку).
// 2- выведено из игры (от игрока).
// 3- передано внутри игры (от одного игрока другому).
// 4- снято внутри ирока (в банк)
// 5- Админские действия
// 6- получено  внутри игры (от одного игрока другому).
// 7- получено внутри ирока (из банка)
// 8- Торговля землями между игроками.
 function r(){
 global $db,$reason,$r_name, $r_type;
 switch($reason) {

	case "1":
	 $r_name="заработок на крафте ";
	 $r_type= 1;
   break;
	case "2":
	 $r_name="оплата владельца шахты работнику";
	 $r_type= 1;
   break;
	case "3":
	 $r_name="бонус старому игроку при регистрации нового игрока по ссылке Приглашение";
	 $r_type= 1;
   break;
	case "4":
	 $r_name="стартовое золото при регистрации";
	 $r_type= 1;
   break;
	case "5":
	 $r_name="найден сундук с золотом";
	 $r_type= 1;
   break;
	case "6":
	 $r_name="найдена ловушка сундука с золотом -золото уменьшается";
	 $r_type= 2;
   break;
	case "7":
	 $r_name="оплата за идентификацию предмета у торговца ";
	 $r_type= 2;
   break;
	case "8":
	 $r_name="продажа предмета торговцу";
	 $r_type= 1;
   break;
	case "9":
	 $r_name="покупка предмета у торговца";
	 $r_type= 2;
   break;
	case "10":
	 $r_name="зарядка артефакта у торговца";
	 $r_type= 2;
   break;
	case "11":
	 $r_name="ремонт предмета у торговца ";
	 $r_type= 2;
   break;
	case "12":
	 $r_name="покупка предмета на рынке";
	 $r_type= 3;
   break;
	case "13":
	 $r_name="продажа предмета на рынке";
	 $r_type= 6;
   break;
	case "14":
	 $r_name="налог за выставление предмета на рынок";
	 $r_type= 2;
   break;
	case "15":
	 $r_name="оплата за строительство здания";
	 $r_type= 2;
   break;
	case "16":
	 $r_name="покупка здания";
	 $r_type= 2;
   break;
	case "17":
	 $r_name="продажа здания ";
	 $r_type= 1;
   break;
	case "18":
	 $r_name="оплата за прохождение телепорта ";
	 $r_type= 2;
   break;
	case "19":
	 $r_name="возврат денег при удалении вещи из инвентаря игрока через админовку";
	 $r_type= 1;
   break;
	case "20":
	 $r_name="изменение золота через админский редактор персов";
	 $r_type= 5;
   break;
	case "21":
	 $r_name="золото за повышение уровня";
	 $r_type= 1;
   break;
	case "22":
	 $r_name="деньги за убийство бота";
	 $r_type= 1;
   break;
	case "23":
	 $r_name="проигрыш денег в Аркомаге";
	 $r_type= 3;
   break;
	case "24":
	 $r_name="выигрыш денег в Аркомаге";
	 $r_type= 6;
   break;
	case "25":
	 $r_name="деньги за убийство игрока ";
	 $r_type= 1;
   break;
	case "26":
	 $r_name="уменьшение денег при смерти игрока в бою (турниры)";
	 $r_type= 2;
   break;
	case "27":
	 $r_name="деньги за победу в бою (турниры)";
	 $r_type= 1;
   break;
	case "28":
	 $r_name="деньги за прохождение лабиринтов";
	 $r_type= 1;
   break;
	case "29":
	 $r_name="изменение денег при обнулении игрока";
	 $r_type= 2; //удаляются, потом только при прокачке добавлены.
   break;
	case "30":
	 $r_name="вклад денег на свой лицевой счет в банке из кошелька игрока";
	 $r_type= 4;
   break;
	case "31":
	 $r_name="снятие денег с лицевого счета в банке в кошелек игрока";
	 $r_type= 7;
   break;
	case "32":
	 $r_name="такса за открытие лицевого счета ";
	 $r_type= 2;
   break;
	case "33":
	 $r_name="оплата услуг храма бракосочетания ";
	 $r_type= 2;
   break;
	case "34":
	 $r_name="оплата при строительстве землевладения и зданий в нем и за покупку лицензии ";
	 $r_type= 2;
   break;
	case "35":
	 $r_name="покупка-продажа соток земли через вторичный рынок жилья";
	 $r_type= 8;
   break;
	case "36":
	 $r_name="покупка-продажа дома через вторичный рынок жилья";
	 $r_type= 8;
   break;
	case "37":
	 $r_name="комиссия при использовании вторичного рынка жилья ";
	 $r_type= 2;
   break;
	case "38":
	 $r_name="оплата налога на имущество (коммунальных платежей)";
	 $r_type= 2;
   break;
	case "39":
	 $r_name="плата за прием игрока в клан";
	 $r_type= 2;
   break;
	case "40":
	 $r_name="оплата налога на клан ";
	 $r_type= 2;
   break;
	case "41":
	 $r_name="оплата регистрации нового клана";
	 $r_type= 2;
   break;
	case "42":
	 $r_name="покупка лошади в конюшне ";
	 $r_type= 2;
   break;
	case "43":
	 $r_name="продажа лошади в конюшне";
	 $r_type= 1;
   break;
	case "44":
	 $r_name="оплата за лечение травмы у лекаря ";
	 $r_type= 2;
   break;
	case "45":
	 $r_name="возврат денег за отклонение открытки при модерации";
	 $r_type= 1;
   break;
	case "46":
	 $r_name="оплата отправки открытки ";
	 $r_type= 2;
   break;
	case "47":
	 $r_name="оплата услуг информационного отдела Гильдии Охотников за монстрами";
	 $r_type= 2;
   break;
	case "48":
	 $r_name="покупка ресурса на рынке ";
	 $r_type= 3;
   break;
	case "49":
	 $r_name="продажа ресурса на рынке";
	 $r_type= 6;
   break;
	case "50":
	 $r_name="оплата аренды места на рынке";
	 $r_type= 2;
   break;
	case "51":
	 $r_name="покупка билета в морском порту";
	 $r_type= 2;
   break;
	case "52":
	 $r_name="оплата за использование Алтаря Великой Силы";
	 $r_type= 2;
   break;
	case "53":
	 $r_name="возврат денег за коня при изменении навыка всадника на Алтаре Великой Силы";
	 $r_type= 1;
   break;
	case "54":
	 $r_name="плата за пополнение запасов таверны ";
	 $r_type= 2;
   break;
	case "55":
	 $r_name="покупка еды в таверне";
	 $r_type= 2;
   break;
	case "56":
	 $r_name="доходы от таверны ";
	 $r_type= 1;
   break;
	case "57":
	 $r_name="оплата услуг за пополнение своей таверны через другого тавернщика ";
	 $r_type= 2;
   break;
	case "58":
	 $r_name="подъемные при окончании Гильдии Новичков";
	 $r_type= 1;
   break;
	case "59":
	 $r_name="изменение денег за квест Спасение прекрасной незнакомки";
	 $r_type= 1;
   break;
	case "60":
	 $r_name="увеличение денег в обучающем квесте Гильдии Новичков";
	 $r_type= 1;
   break;
	case "61":
	 $r_name="автоматическая продажа лошади при выставлении на продажу дома с построенной конюшней ";
	 $r_type= 1;
   break;

default:
   $r_name="НЕ УКАЗАНО!!!";
   $r_type= 0;
   break;
}
}
####################################
#   Присвоение коду- имени reason  #






switch($in) {

case "date_all":
   date_all();
   break;

case "list_r":
   list_r();
   break;
   
 case "date_r":
   date_r();
   break;

default:
   main();
   break;
}




}

if (function_exists("save_debug")) save_debug();

?>

