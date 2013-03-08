<?php
$select2=myquery("select * from craft_build_rab where user_id=$user_id");
if (mysql_num_rows($select2)==0)
{
	$hod=0;
	$timeout=0;
}
else
{
	$rab=mysql_fetch_array($select2);
	$timeout=$rab['dlit'];
	$hod=$rab['date_rab'];
	$build_id=$rab['build_id'];    
}
include(getenv("DOCUMENT_ROOT").'/inc/craft/craft.inc.php');
include(getenv("DOCUMENT_ROOT").'/craft/inc/meating.inc.php');
?>