<?
$dirclass = "../class";
require_once('../inc/engine.inc.php');
require('../inc/xray.inc.php');
include('../inc/lib.inc.php');
include('../inc/template.inc.php');

$db_game = mysql_connect(PHPRPG_DB_HOST, PHPRPG_DB_USER, PHPRPG_DB_PASS) or die(mysql_error());
mysql_select_db(PHPRPG_DB_NAME,$db_game) or die(mysql_error());  

$sel = mysql_query("SELECT * FROM game_items_old WHERE town=3 AND type='Оружие'",$db_game);
while ($it = mysql_fetch_array($sel))
{
    list($item_id) = mysql_fetch_array(mysql_query("SELECT id FROM game_items_factsheet WHERE name='".$it['ident']."'",$db_game));
    echo '<br>Предмет - '.$it['ident'].', продавец - '.$it['name'].', item_id='.$item_id.'';
}

echo 'The End';
?>