<?php
if ($_SERVER['PHP_SELF']!="/act.php")
{
	die();
}

if (function_exists("start_debug")) start_debug(); 

if (!empty($option))
{
	if ($char['hide']==1) {if (function_exists("save_debug")) save_debug(); exit;}
	switch ($option)
	{
		case 'npc':
		{
			if (isset($id) AND is_numeric($id) AND $id>0)
			{
				$current_time = time();
				$online_range = $current_time;
				$Npc = new Npc($id);
				if ($Npc->can_attack($char))
				{
					if ($Npc->npc_for_level($char))
					{
						//������ �������. �������� �� ����������� �� ���������� ���� ������ ������� �� ����� ������
						if($Npc->npc['npc_quest_engine_id']>0)
						{
							list($fin_time)=mysql_fetch_array(myquery("SELECT quest_finish_time FROM quest_engine_users WHERE quest_type=1 AND par1_value='".$id."'"));
							if($Npc->npc['npc_quest_engine_id']!=$user_id AND $fin_time>time()) 
							{
								if (function_exists("save_debug")) save_debug();
								setLocation("act.php?func=main");
								exit;
							}
						}
						attack_npc($char,$id,0);
					}
					else
					{
						echo '<center><b>��� �� �������� ��� ������ ������!</b></center>';
					}
				}
				else
				{
					echo '<center><b>�� �� ������ ������� �� ����!</b></center>';
				}
			}
		}
		break;
	}
}
setLocation("act.php");

if (function_exists("save_debug")) save_debug(); 

?>