<?php
require_once("../inc/engine.inc.php");
flush();
// нужно заполнить поля
$from=GAME_NAME;
$fromemail='no_reply@'.DOMAIN.'';
$subject='Тест';
$to='7786077'; // <-- номер. (Уверен, что кто-нибудь не догадается прописать :)
$body='Тестовое сообшение из PHP скрипта';

$submit='Send Message'; // НЕ РЕДАКТИРОВАТЬ
$ref="http://wwp.icq.com/$to"; // НЕ РЕДАКТИРОВАТЬ

// формирование заголовка
$PostData= 
"from=".urlencode($from)."&". 
"fromemail=".urlencode($frommail)."&". 
"subject=".urlencode($subject)."&". 
"body=".urlencode($body)."&". 
"to=".urlencode($to)."&". 
"submit=".urlencode($submit); 

$len=strlen($PostData); 


$nn="
"; 
$zapros= 
"POST /scripts/WWPMsg.dll HTTP/1.0".$nn. 
"Referer: $ref".$nn. 
"Content-Type: application/x-www-form-urlencoded".$nn. 
"Content-Length: $len".$nn. 
"Host: wwp.icq.com".$nn. 
"Accept: */*".$nn. 
"Accept-Encoding: gzip, deflate".$nn. 
"Connection: Keep-Alive".$nn. 
"User-Agent: Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)".$nn. 
"".$nn. 
"$PostData";

echo $zapros." ------------- "; 
flush(); 

// открываем сокет и шлем заголовок 
$fp = fsockopen("wwp.icq.com", 80, &$errno, &$errstr, 30); 
if(!$fp) { print "$errstr ($errno)<br> "; exit; } 

// для наглядности выводим заголовок ответа и страницу на экран 
fputs($fp,$zapros); 
print fgets($fp,20048); 
fclose($fp); 
?>

