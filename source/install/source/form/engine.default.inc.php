<?php
/*---------------------------------------------------*/
#                                                     #
#          �������� ���������������� ����             #
#                                                     #
/*---------------------------------------------------*/

/* ������ ��� ��������� ���� �� ��������� */
date_default_timezone_set('Europe/Moscow');

/* ������ ��� ������ */

define('PHPRPG_SESSION_EXPIRY', '300'); //����� ����� ������
define('PHPRPG_EPOCH', '994737600');

/* ������ ��� ���������� � �� */

define('PHPRPG_DB_HOST', '[#DB_HOST#]'); //������ �� mySQL
define('PHPRPG_DB_NAME', '[#DB_BASENAME#]'); //��� �� mySQL
define('PHPRPG_DB_USER', '[#DB_USERNAME#]'); //��� ������������ ��
define('PHPRPG_DB_PASS', '[#DB_PASSWORD#]'); //������ ������������ ��

/* ������ ��� ���� */

define('GAME_NAME','[#ADM_GAME_NAME#]'); //�������� ����
define('PATCH', '[#ADM_PATCH#]'); //������ ���� �� �������� ���������� ����
define('DEBUG_IP','127.0.0.1'); //IP ����� ��� ������ �������
define('DEBUG_RUN',0); //���./����. ������ ������� 0-����. � 1-���.
define('DOMAIN','[#ADM_DOMEN#]'); //����� �� ������� ����������� ����
define('TEST_SERVER','localhost'); //�������� ������
define('IMG_DOMAIN',DOMAIN.'/images'); //����� ����������� (�� ��������� ������� images)

/* ������ ��� ������� */
ereg("([^\\/]*)$", $_SERVER['PHP_SELF'], $php_self);
define('PHP_SELF', $php_self[1]);

/* ������� ���������� � �� */
DbConnect();
function DbConnect()
{
	$mysqli = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
	mysql_select_db(PHPRPG_DB_NAME) or die(mysql_error());
}

/* ���� ������ ���������� X-ray Installer v.1.0 */

?>