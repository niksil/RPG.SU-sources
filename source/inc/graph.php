<?
/*
* graph_table(old_stage,new_stage,priority)
* graph_status(id,user_id,stage,time,priority)
*
* TODO1: Add more checks.
* TODO2: Test lock-stuff
*
*/

function getlast_graph($user_id)
{
	$sel1=myquery("SELECT stage FROM `graph_status` WHERE user_id='".$user_id."' ORDER BY id DESC LIMIT 1");
	if (mysql_num_rows($sel1))
	{
		return mysql_result($sel1,0,0);
	}
	else
	{
		/* No activity from user */
		return -1;
	}
}

function check_graph($user_id,$module_id)
{
	$old=getlast_graph($user_id);
	if($old==-1)
	{
		return -1;
	}
	return mysql_result(myquery("SELECT count(*) FROM `graph_table` WHERE old_stage='".$old."' AND new_stage='".$module_id."' "),0,0);
}

function addmod_graph($user_id,$module_id)
{
	mysql_query("SET AUTOCOMMIT=0");
	if(check_graph($user_id,$module_id))
	{
		myquery("INSERT INTO `graph_status` VALUES ('','".$user_id."','".$module_id."','UNIX_TIMESTAMP()','0')");
		/* New module activated */
		mysql_query("COMMIT");
		mysql_query("SET AUTOCOMMIT=1");
		return 1;
	}
	else
	{
		/* No good news */
		mysql_query("ROLLBACK");
		mysql_query("SET AUTOCOMMIT=1");
		return -1;
	}
}

function delmod_graph($user_id,$module_id)
{
	mysql_query("SET AUTOCOMMIT=0");
	$old=getlast_graph($user_id);
	if($module_id==$old)
	{
		myquery("INSERT INTO `graph_status` VALUES ('','".$user_id."','".$module_id."','UNIX_TIMESTAMP()','0')");
		/* New module activated */
		mysql_query("COMMIT");
		mysql_query("SET AUTOCOMMIT=1");
		return 1;
	}
	else
	{
		/* Function called from other module */
		mysql_query("ROLLBACK");
		mysql_query("SET AUTOCOMMIT=1");
		return -2;
	}
}
?>