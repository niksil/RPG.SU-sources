<?
$dirclass = "../class";
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');
DbConnect();

error_reporting("E_ALL");


function PrintError($param,$it,$encik)
{
        if ($it[$param]!=$encik[$param])
        {
                list($name) = mysql_fetch_array(myquery("(SELECT name FROM game_users WHERE user_id=".$it['user_id'].") UNION (SELECT name FROM game_users_archive WHERE user_id=".$it['user_id'].")"));
                echo'
                <tr><td>'.$it['type'].'</td><td>'.$it['ident'].'</td><td>'.$name.'</td><td>'.$param.'</td><td>'.$encik[$param].'</td><td>'.$it[$param].'</td><td>'.($encik[$param]-$it[$param]).'</td></tr>';
        }
}



$sel = myquery("SELECT * FROM `blog_post` WHERE post_time=0 ORDER BY `blog_post`.`post_id` DESC");
$old_month=1;
$year=2008;
while ($it=mysql_fetch_array($sel))
{
        list($day,$month) =split("-", $it['time']);
        list($hour,$minute) =split("-", $it['dat']);
        $day=(int)$day;
        $month=(int)$month;
        $hour=(int)$hour;
        $minute=(int)$minute;
        if($old_month<$month)
        {
                print "=============================<br><br>";
                $year--;
        }
                $old_month=$month;

        $untime=strtotime("$day-$month-$year $hour:$minute:00");
        myquery("UPDATE blog_post SET post_time=".$untime." WHERE post_id=".$it['post_id']." ");
        print "time: $day | dat: $month -- ".date("Y-n-j h:i:s",$untime)."<br>";
}

$sel = myquery("SELECT * FROM `blog_comm` WHERE comm_time=0 ORDER BY `blog_comm`.`comm_id` DESC");
$old_month=1;
$year=2008;
while ($it=mysql_fetch_array($sel))
{
        list($day,$month) =split("-", $it['tim']);
        list($hour,$minute) =split("-", $it['dat']);
        $day=(int)$day;
        $month=(int)$month;
        $hour=(int)$hour;
        $minute=(int)$minute;
        if($old_month<$month)
        {
                print "=============================<br><br>";
                $year--;
        }
                $old_month=$month;

        $untime=strtotime("$day-$month-$year $hour:$minute:00");
        myquery("UPDATE blog_comm SET comm_time=".$untime." WHERE comm_id=".$it['comm_id']." ");
        print "time: $day | dat: $month -- ".date("Y-n-j h:i:s",$untime)."<br>";
}

?>