<?php
/*---------------------------------------------------*/
#                                                     #
#          Основной конфигурационный файл             #
#                                                     #
/*---------------------------------------------------*/

/* Данные для временной зоны по умолчанию */
date_default_timezone_set('Europe/Moscow');

/* Данные для сессий */

define('PHPRPG_SESSION_EXPIRY', '300'); //Время жизни сессии
define('PHPRPG_EPOCH', '994737600');

/* Данные для соединения с БД */

define('PHPRPG_DB_HOST', '[#DB_HOST#]'); //Сервер БД mySQL
define('PHPRPG_DB_NAME', '[#DB_BASENAME#]'); //Имя БД mySQL
define('PHPRPG_DB_USER', '[#DB_USERNAME#]'); //Имя пользователя БД
define('PHPRPG_DB_PASS', '[#DB_PASSWORD#]'); //Пароль пользователя БД

/* Данные для игры */

define('GAME_NAME','[#ADM_GAME_NAME#]'); //Название игры
define('PATCH', '[#ADM_PATCH#]'); //Полный путь до корневой директории игры
define('DEBUG_IP','127.0.0.1'); //IP адрес для режима отладки
define('DEBUG_RUN',0); //Вкл./Выкл. режима отладки 0-Выкл. и 1-Вкл.
define('DOMAIN','[#ADM_DOMEN#]'); //Домен на котором установлена игра
define('TEST_SERVER','localhost'); //Тестовый сервер
define('IMG_DOMAIN',DOMAIN.'/images'); //Домен изображений (по умолчанию каталог images)

/* Задаем имя скрипта */
ereg("([^\\/]*)$", $_SERVER['PHP_SELF'], $php_self);
define('PHP_SELF', $php_self[1]);

/* Функция соединения с БД */
DbConnect();
function DbConnect()
{
	$mysqli = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());
}

/* Файл создан программой X-ray Installer v.1.0 */

?>