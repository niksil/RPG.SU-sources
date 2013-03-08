<?php
if (isset($set_dvij))
{
	$up = myquery("UPDATE game_users SET dvij='$set_dvij' WHERE user_id='".$char['user_id']."'");
}
?>