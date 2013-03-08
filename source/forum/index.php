<?php
//ob_start("ob_gzhandler",9);
$dirclass="../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');


if (!defined("MODULE_ID"))
{
	define("MODULE_ID", '6');
}
else
{
	die();
}

if (function_exists("start_debug")) start_debug();

$admin = false;
$forum_admin = false;
$guest = true;
if (isset($_COOKIE['rpgsu_sess']) AND isset($_COOKIE['rpgsu_login']) AND isset($_COOKIE['rpgsu_pass']))
{
	require('../inc/lib_session.inc.php');
	$sel = mysql_result(myquery("SELECT COUNT(*) FROM game_prison WHERE user_id=$user_id"),0,0);
	if ($sel>0) die('Ты на каторге!');
	
	set_delay_reason_id($user_id,5);

	$seladmin = myquery("select * from game_admins where user_id=$user_id");
	if ($seladmin!=false AND mysql_num_rows($seladmin)>0)
	{
		$admin = true;
		$ad = mysql_fetch_array($seladmin);
		if ($ad['forum']>=1)
		{
			$forum_admin = true;
		}
	}
	$guest = false;
	if ($char['clan_id']==1)
	{
		$admin = true;
		$forum_admin = true;
	}
}
else
{
	$char = array();
	$char['name'] = 'Гость';
	$char['user_id'] = 0;
	$char['clan_id'] = 0;
	$char['clevel'] = 0;
	if (DOMAIN == TEST_SERVER) die('closed');
}

include("forum.class.php");

if (isset($_GET['act']))
{
	$act = $_GET['act'];
}
else
{
	$act = 'main';
}

$sel_setup = myquery("SELECT * FROM forum_setup WHERE user_id=".$char['user_id']."");
if ($sel_setup!=false AND mysql_num_rows($sel_setup)>0)
{
	$setup = mysql_fetch_array($sel_setup);
	if ($setup['reply']==0) $setup['reply']=15;
}
else
{
	$setup = array();
	$setup['reply']=15;
	$setup['podpis']='';
	$setup['show_avatar']=1;
	$setup['show_podpis']=0;            
}

$forum = new Forum();
$forum->admin = $admin;
$forum->forum_admin = $forum_admin;
$forum->char = $char;
$forum->guest = $guest;
$forum->barier_id = 72;
$forum->detectRights();
$forum->setup = $setup;

if (isset($_GET['last_unread']))
{
	$inf = $forum->GoToFirstUnread($_GET['id']);
	if (isset($inf['list']))
	{
		if ($inf['f_unread'] == "0")
		{
			header("Location:?act=topic&id=".$_GET['id']."&page=1");
		}
		else
		{
			header("Location:?act=topic&id=".$_GET['id']."&page=".$inf['list']."#otvet".$inf['f_unread']."");
		};
	};
};

if (isset($_GET['markreadall']))
{
	$forum->MarkReadAll();
}
elseif (isset($_GET['mark_unread']))
{
	$id = (int)$_GET['id'];
	$forum->MarkUnread($id);
}
elseif (isset($_GET['closetopic']))
{
	$id = (int)$_GET['id'];
	$forum->OpenCloseTopic($id);
}
elseif (isset($_GET['markattention']))
{
	$id = (int)$_GET['id'];
	$forum->MarkAttention($id);
}
elseif (isset($_GET['movetopic']))
{
	$id = (int)$_GET['id'];
	$kat = (int)$_GET['kat'];
	$forum->MoveTopic($id,$kat);
}
elseif (isset($_GET['delete']))
{
	$id = (int)$_GET['id'];
	$forum->Del($id,1);
}
elseif (isset($_GET['fulldelete_reply']))
{
	$id = (int)$_GET['id'];
	$forum->Del($id,2);
}
elseif (isset($_GET['delete_reply']))
{
	$id = (int)$_GET['id'];
	$forum->Del($id,0);
}
elseif (isset($_GET['restore_reply']))
{
	$id = (int)$_GET['id'];
	$forum->Del($id,0);
}
elseif (isset($_GET['moder']))
{
	if (isset($_GET['ar']))
	{
		$forum->Moder($_GET['moder'],$_GET['ar']);
	}
}
elseif (isset($_GET['saythanks_post']))
{
	$id = (int)$_GET['saythanks_post'];
	$forum->ThanksPost($id);
}
elseif (isset($_GET['saythanks_topic']))
{
	$id = (int)$_GET['saythanks_topic'];
	$forum->ThanksTopic($id);
}
elseif (isset($_GET['delthanks_post']))
{
	$id = (int)$_GET['delthanks_post'];
	$forum->DelThanksPost($id,$user_id);
}
elseif (isset($_GET['delthanks_topic']))
{
	$id = (int)$_GET['delthanks_topic'];
	$forum->DelThanksTopic($id,$user_id);
}
if (isset($_POST['AddReply']))
{
	$forum->AddReply(); 
}
if (isset($_POST['AddTopic']))
{
	$forum->AddTopic();
}
if (isset($_POST['EditReply']))
{
	$forum->EditReply();
}
if (isset($_POST['EditTopic']))
{
	$forum->EditTopic();
}
if (isset($_POST['setup']))
{
	$forum->SaveSetup();
}
if (isset($_POST['actionpoll']))
{
	$forum->ActionPoll($_POST);
}
if (isset($_GET['actionpoll']))
{
	$forum->ActionPoll($_GET);
	if (isset($_GET['ajax']) AND isset($_GET['poll_id']))
	{
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: text/plain; charset=windows-1251');
		list($topic_id) = mysql_fetch_array(myquery("SELECT topic_id FROM forum_poll WHERE poll_id=".$_GET['poll_id'].""));
		echo $forum->PrintPoll($topic_id,'read',1);
		die();
	}
}
$forum->action($act);

//ob_end_flush();
mysql_close();

if (function_exists("save_debug")) save_debug(); 
exit;
 
?>
