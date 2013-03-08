<?php
/*
//������ � �����
//CC - ������������ ����������� ���������
//CW - ������� ����������� ���������

������ �������:
1) ������� ������� � ����� - take();
2) ��������� ������� �� ����� - drop();
3) ���������� ���������� �� ���� �������� � ��������� ������ - info();
4) ���������� ���������� �� ���� �������� � ���� ����������� �������� - hint();
5) ���������������� ������� � �������� - identify();
6) ������� ������� �������� - sell();
7) ������ ������� � �������� - buy();
8) �������� ������� � �������� - repair();
9) ������������ ������� - use_item();
10) ��������� ������� �� ����� - sell_market();
11) ������ ������� �� ����� - buy_market();
12) ����� ������� - up();
13) ����� ������� - down();
14) ������� ������� - delete();



�������� used: - � ����� ����� ��������� ��������� �������
0 - �����
1 - ����
2 - ������ 1
3 - ��������
4 - ��� ��� ���� 2
5 - ������
6 - ���� 
7 - ����� 
8 - ���� 
9 - ��������
10 - ��������
11 - �����
12 - ������� ��� ������ 1
13 - ������� ��� ������ 2
14 - ������� ��� ������ 3
15 - ��������� 1
16 - ��������� 2
17 - ������
18 - ������
19 - ������ 2
20 - ������ 3
21 - ����������
22 - ���� ��� _����_ ����������


type - ��� ��������
1 - ������
2 - ������
3 - ��������
4 - ���
5 - ������
6 - ����
7 - �����
8 - ����
9 - ��������
10 - ��������
11 - �����
12 - ������
13 - ��������
14 - ������
15 - ������
16 - ���������
17 - ���.�����
18 - ����
19 - ���.������
20 - ����� ������������ ����
21 - ������
22 - ���� �������
23 - ���������
24 - ����������

95 = qengine_item_type - ������� ��� ������ �������
97 - ������ (�������, ������� � �.�. �� �����)
98 - ����� ������� ������� (����� ������� ��������� �� ��������)
99 - wm

type_weapon
0 -  ��� ���� (MS_WEAPON)
1 -  �������� (MS_KULAK)
2 -  ���������� (MS_LUK)
3 -  ������� (MS_SWORD)
4 -  �������� (MS_AXE)
5 -  ������� (MS_SPEAR)

def_type (����� ���� � 
�������[15](������ �����), 
�������[5](������ ����), 
�����[6](������ ������), 
������[14]](������ ����), 
�������[11]](������ ���))
0 -  ������
1 -  ��������
2 -  ���������� (��������)
3 -  ���� (������������)

wm
1 - ������ ����������� �������
2 - ������ ���������
3 - ������ ������� �������������
4 - ������ ������ �������������
5 - ������ ����������


��� ���� 20 - ����� ������������ ��������:
oclevel = ������� ����� (�� 1 �� 5)
indx = id ���������������� ��������

��� ���� 21 - ������
quantity - id ���� � ������� ������ ����� ��������������

game_items.count_item = ���-�� �������/���������/�����/���.���������
*/


class Item
{
	public $item;
	public $fact;
	private $char;
	public $message;
	private $tax_market = 0.08;//����� �� �����
	
	private function init_fact($id)
	{
		if ((int)$id<=0) exit;
		$this->fact = mysql_fetch_assoc(myquery("SELECT * FROM game_items_factsheet WHERE id=$id"));
		$this->fact['weight'] = (double)$this->fact['weight'];
	}
	
	private function init_item($id)
	{
		if ((int)$id<=0) exit;
		$this->item = mysql_fetch_assoc(myquery("SELECT * FROM game_items WHERE id=$id"));
		$this->init_fact($this->item['item_id']);
	}

	public function setChar($char_id = -1)
	{
		if ($char_id == -1)
		{
			global $char;
			$this->char = $char;
		}
		else
		{
			$result = myquery("SELECT view_active_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos  FROM view_active_users,game_users_map WHERE game_users_map.user_id=view_active_users.user_id AND view_active_users.user_id=$char_id");
			if($result==false OR mysql_num_rows($result)==0)
			{
				$result = myquery("SELECT game_users.*, game_users_map.map_name, game_users_map.map_xpos,game_users_map.map_ypos,game_users_active_delay.delay,game_users_active_delay.delay_reason  FROM game_users,game_users_map,game_users_active_delay WHERE game_users.user_id=game_users_active_delay.user_id AND game_users_map.user_id=game_users.user_id AND game_users.user_id=$char_id");
			}
			$this->char = mysql_fetch_assoc($result);
			$this->char['user_id']=$char_id;
			$this->char['func_id']=getFunc($char_id);
		}
	}

	public function find_item($item_id,$not_used=0)
	{
		if ($not_used==1)
		{
			$find = myquery("SELECT id FROM game_items WHERE item_id=$item_id AND user_id=".$this->char['user_id']." AND priznak=0 AND used=0 LIMIT 1");
		}
		else
		{
			$find = myquery("SELECT id FROM game_items WHERE item_id=$item_id AND user_id=".$this->char['user_id']." AND priznak=0 LIMIT 1");
		}
		if ($find==false OR mysql_num_rows($find)==0)
		{
			return 0;
		}
		$this->init_item(mysql_result($find,0,0));
		return 1;
	}
	
	public function __get($index)
	{
		if (isset($this->fact[$index]))
		{
			return $this->fact[$index];
		}
		elseif (isset($this->item[$index]))
		{
			return $this->item[$index];
		}
		return $this->$index;
	}
	
	public function getItem($index)
	{
		if (isset($this->item[$index]))
		{
			return $this->item[$index];
		}
		return $this->$index;
	}
	
	public function getFact($index)
	{
		if (isset($this->fact[$index]))
		{
			return $this->fact[$index];
		}
		return $this->$index;
	}
	
	public function getOpis()
	{
		$opis = '';
		
		$sel = myquery("SELECT opis FROM game_items_opis WHERE item_id=".$this->item['id']."");
		if ($sel!=false AND mysql_num_rows($sel)>0)
		{
			list($opis) = mysql_fetch_array($sel);
		}
		
		return $opis;
	}

	public function delOpis($item_id = 0)
	{
		if ($item_id == 0)
			$item_id = $this->item['id'];
		myquery("DELETE FROM game_items_opis WHERE item_id=".$this->item['id'].";");
	}
	
	public function setOpis($opis,$item_id = 0)
	{
		if ($item_id == 0)
			$item_id = $this->item['id'];
		myquery("INSERT INTO game_items_opis (item_id,opis) VALUE ('$item_id','$opis') ON DUPLICATE KEY UPDATE opis='$opis'");
	}
	
	public function __toString()
	{
		return $this->fact['name'];
	}
	
	public function __destruct()
	{
	}
	
	public function __construct($id=0)
	{
		global $char;
		$this->char = $char;
		$this->message = '';
		if ($id>0)
		{
			$this->init_item($id);
		}
	}

	public function counted_item()
	{                       
		if ($this->fact['type']==13 OR $this->fact['type']==12 OR $this->fact['type']==21 OR $this->fact['type']==19 OR $this->fact['type']==22 OR $this->fact['type']==97) return true;
		return false;
	}  

	public function max_count()
	{
		if (!$this->counted_item())
			return 1;
		if ($this->fact['weight']!=0)	
		return min(50, 50.0 / $this->fact['weight']);
		return 50;
	}
	  
	public function take($id=0)
	{
		global $_SESSION;
		global $user_time;
		//1. �������� ��������� �� ������ ������� �� ����� ����� � ������� � ����� � ���� priznak=2
		//2. ������ �������� user_id, ������������� priznak=0,map_name=0,map_xpos=0,map_ypos=0
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['map_name']==$this->char['map_name'] AND $this->item['map_xpos']==$this->char['map_xpos'] AND $this->item['map_ypos']==$this->char['map_ypos'] AND $this->item['priznak']==2)
		{
			if ($this->fact['name']=='����� ������� ����������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$rand = mt_rand(10,20);
				$sel = myquery("SELECT game_items.id,game_items.item_id,game_items.count_item,game_items_factsheet.item_uselife FROM game_items,game_items_factsheet WHERE game_items.user_id=".$this->char['user_id']." AND game_items.used=3 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id");
				if ($sel!=false)
				{
					list($id_art,$ident_art,$cur_zar,$max_zar) = mysql_fetch_array($sel);
					$new_zar = min($max_zar,$cur_zar+$rand);
					myquery("UPDATE game_items SET count_item=$new_zar WHERE id=$id_art");
					$rand = $new_zar-$cur_zar;
				}
				else
				{
					$rand = 0;
				}
				$_SESSION['getsunduk']='�� '.echo_sex('�������','��������').' �������� �� '.$rand.' �������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������ � �����������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				myquery("UPDATE game_users SET GP=GP + ".$this->item['item_cost']." WHERE user_id=".$this->char['user_id']."");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','�����').' ������ � '.$this->item['item_cost'].' ��������';
				setGP($this->char['user_id'],$this->item['item_cost'],5);
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='����� ����')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$stm = mt_rand(20,90);
				if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				myquery("UPDATE game_users SET STM=STM + $stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ���� � ���� ������� ���������� �� '.$stm.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='����� ����')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$stm = mt_rand(20,90);
				if (($stm+$this->char['HP'])>$this->char['HP_MAX']) $stm = $this->char['HP_MAX'] - $this->char['HP'];
				myquery("UPDATE game_users SET HP=HP + $stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('����','�����').' ���� � ���� ����� ���������� �� '.$stm.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='���������� �������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$stm = mt_rand(10,70);
				if (($stm+$this->char['MP'])>$this->char['MP_MAX']) $stm = $this->char['MP_MAX'] - $this->char['MP'];
				myquery("UPDATE game_users SET MP=MP + $stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ���������� ������� � ���� ���� ���������� �� '.$stm.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������� � ������� ������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(10,50);
				$mp = mt_rand(20,60);
				$stm = mt_rand(30,70);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ������� � ������� ������ � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� ����� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������� � �������� ������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(10,40);
				$mp = mt_rand(20,40);
				$stm = mt_rand(10,40);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ������� � �������� ������ � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� ����� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='����������� ������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(50,90);
				$mp = mt_rand(50,90);
				$stm = mt_rand(50,90);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ����������� ������ � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� ����� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������ � ���������')
			{
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$hp = mt_rand(40,50);
				$mp = mt_rand(40,50);
				$stm = mt_rand(40,50);

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$hp=-$hp;
					if (($this->char['HP']+$hp)<0) $hp = -$this->char['HP'];
				}
				else
				{
					if (($hp+$this->char['HP'])>$this->char['HP_MAX']) $hp = $this->char['HP_MAX'] - $this->char['HP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$mp=-$mp;
					if (($this->char['MP']+$mp)<0) $mp = -$this->char['MP'];
				}
				else
				{
					if (($mp+$this->char['MP'])>$this->char['MP_MAX']) $mp = $this->char['MP_MAX'] - $this->char['MP'];
				}

				$r = mt_rand(1,4);
				if ($r==1)
				{
					$stm=-$stm;
					if (($this->char['STM']+$stm)<0) $stm = -$this->char['STM'];
				}
				else
				{
					if (($stm+$this->char['STM'])>$this->char['STM_MAX']) $stm = $this->char['STM_MAX'] - $this->char['STM'];
				}

				myquery("UPDATE game_users SET HP=HP+$hp,MP=MP+$mp,STM=STM+$stm WHERE user_id=".$this->char['user_id']." LIMIT 1");
				$_SESSION['getsunduk']='�� '.echo_sex('�����','������').' ������ � ��������� � ���� ���� ���������� �� '.$mp.' ������, ���� ������� ���������� �� '.$stm.' ������, ���� �������� ���������� �� '.$hp.' ������';
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
			elseif ($this->fact['name']=='������ ��������')
			{
				//myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				//��������� ������� � ��������� ����� �� �������
				$check = myquery("SELECT id FROM game_items WHERE item_id=".item_id_key_constructor." AND user_id=".$this->char['user_id']." AND priznak=0 LIMIT 1");
				if (!mysql_num_rows($check))
				{
					$_SESSION['getsunduk'] = '��� ����, ����� ������� <b>������ ��������</b>, ���� ����� � ��������� <b>���� �� ������� ��������</b>!';  
				}
				else
				{
					//������� ����
					list($id_key) = mysql_fetch_array($check);
					$ItemDel = new Item($id_key);
					$ItemDel->admindelete();
					
					//���������� ������ �� ����� �����
					$sel = myquery("SELECT xpos,ypos FROM game_maze WHERE map_name=".$this->item['map_name']." AND (move_up+move_right+move_down+move_left>=2)");
					jump_random_query($sel);
					$pos = mysql_fetch_assoc($sel);
					myquery("UPDATE game_items SET map_xpos=".$pos['xpos'].",map_ypos=".$pos['ypos']." WHERE id=".$this->item['id']."");
					
					$item_name = "������";
					if ($this->char['map_name']==691 OR $this->char['map_name']==692 OR $this->char['map_name']==804)
					{
						if ($this->char['map_name']==691)
						{
							$drop = array(
							569,//����� ������� 1
							570,//����� ������� 2
							571,//����� ������� 3
							572,//����� ������� 4
							573,//����� ������� 5
							574,//����� ������� 6
							575,//����� ������� 7
							320,//����� ������� �������������� ������� (+200 STM)
							322,//����� ������� �������������� �������� (+200 HP)
							321//����� ������� �������������� ���� (+200 MP)
							);
						}
						if ($this->char['map_name']==692)
						{
							$drop = array(
							576,//����� ������� 8
							577,//����� ������� 9
							578,//����� ������� 10
							579,//����� ������� 11
							580,//����� ������� 12
							581,//����� ������� 13
							582,//����� ������� 14
							321,//������� ������� �������������� ���� (+200 MP)
							358,//������� ������� �������������� ������� (+800 STM)
							322//������� ������� �������������� �������� (+200 HP)
							);
						}
						if ($this->char['map_name']==804)
						{
							$drop = array(
							576,//����� ������� 8
							577,//����� ������� 9
							578,//����� ������� 10
							579,//����� ������� 11
							580,//����� ������� 12
							581,//����� ������� 13
							582,//����� ������� 14
							321,//������� ������� �������������� ���� (+200 MP)
							358,//������� ������� �������������� ������� (+800 STM)
							322//������� ������� �������������� �������� (+200 HP)
							);
						}
						mt_srand(make_seed());
						$ran = mt_rand(0,9);
						$item_id = $drop[$ran];
						$ar = $this->add_user($item_id,$this->char['user_id'],1);
						if ($ar[0]==0)
						{
							$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ ��������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
						}
						else
						{
							$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=$item_id"),0,0);
							$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ �������� � '.echo_sex('�����','�����').' � ��� <b>'.$item_name.'</b>';
						}
					}
				}
				setLocation("act.php?getsunduk");
				{if (function_exists("save_debug")) save_debug(); exit;}
			}
            elseif ($this->fact['id']==item_id_sunduk) //������ � �������� �����
            {
                //myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
                //��������� ������� � ��������� ����� �� �������
                $check = myquery("SELECT id FROM game_items WHERE item_id=".item_id_old_key." AND user_id=".$this->char['user_id']." AND priznak=0 LIMIT 1");
                if (!mysql_num_rows($check))
                {
                    $_SESSION['getsunduk'] = '��� ����, ����� ������� <b>������</b>, ���� ����� � ��������� <b><a href=\'info/?item='.item_id_old_key.'\' target=_blank>������ ����</a></b>!';  
                }
                else
                {
                    //������� ����
                    list($id_key) = mysql_fetch_array($check);
                    $ItemDel = new Item($id_key);
                    $ItemDel->admindelete();
                    
                    //������� ������ 
                    myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
                    
                    $item_name = "������";
                    if ($this->char['map_name']==id_map_tuman)
                    {
                        $chance = mt_rand(0,100);
                        $item_id = 0;
                        $id_svitok = 0;
                        $char = $this->char;
                        if ($char['map_xpos']==7 AND $char['map_ypos']==0)
                        {
                            $id_svitok = item_id_svitok_light_usil;
                            $item_name = "������ ������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==1)
                        {
                            $id_svitok = item_id_svitok_light_usil;
                            $item_name = "������ ������� ��������";
                        }
                        elseif ($char['map_xpos']==7 AND $char['map_ypos']==2)
                        {
                            $id_svitok = item_id_svitok_medium_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==3)
                        {
                            $id_svitok = item_id_svitok_medium_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==7 AND $char['map_ypos']==4)
                        {
                            $id_svitok = item_id_svitok_hard_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==5)
                        {
                            $id_svitok = item_id_svitok_hard_usil;
                            $item_name = "������ �������� ��������";
                        }
                        elseif ($char['map_xpos']==7 AND $char['map_ypos']==6)
                        {
                            $id_svitok = item_id_svitok_absolut_usil;
                            $item_name = "������ ����������� ��������";
                        }
                        elseif ($char['map_xpos']==0 AND $char['map_ypos']==7)
                        {
                            $id_svitok = item_id_svitok_absolut_usil;
                            $item_name = "������ ����������� ��������";
                        }
                        
                        if ($chance<=50)
                        {
                            $item_id = $id_svitok;
                        }
                        elseif ($chance<=75)
                        {
                            $user_id = $char['user_id'];
                            $ress = mysql_fetch_array(myquery("SELECT * FROM craft_resource WHERE id=".id_resource_saphire.""));
                            $kol = 1;
                            $weight = $kol*$ress['weight'];
                            $prov = mysqlresult(myquery("select count(*) from game_wm where user_id=$user_id AND type=1"),0,0);
                            if ($char['CW']+$weight<=$char['CC'] or $prov>0)
                            {
                                $delay = $user_time + $weight;
                                $update_users = myquery("UPDATE game_users SET CW=(CW + $weight) WHERE user_id=$user_id LIMIT 1");
                                set_delay_info($user_id,$delay,7);
                                myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ($user_id,".id_resource_saphire.",1) ON DUPLICATE KEY UPDATE col=col+1");
                                $item_name = "������";
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ � '.echo_sex('�����','�����').' � ��� <b>'.$item_name.'</b>';
                            }
                            else
                            {
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
                            }
                        }
                        else
                        {
							$user_id = $char['user_id'];
							list($amber, $wei) = mysql_fetch_array(myquery("Select id, weight From craft_resource Where name='������'"));
							if (isset($amber) and $char['CW']+$wei>$char['CC'])
							{
								$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
							}
							elseif (isset($amber))
							{
								myquery("INSERT INTO craft_resource_user (user_id,res_id,col) VALUES ($user_id,$amber,1) ON DUPLICATE KEY UPDATE col=col+1");
								$_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ � '.echo_sex('�����','�����').' � ��� <b>������</b>';
								myquery("Update game_users Set CW=CW+$wei Where user_id=$user_id");
							}
                            /*$id_svitok = item_id_svitok_light_usil;
                            $item_name = "������ ������� ��������";*/
                        }
                        if ($item_id>0) 
                        {
                            $ar = $this->add_user($item_id,$this->char['user_id'],1);
                            if ($ar[0]==0)
                            {
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������, �� � ����� ��������� �� ������� ����� ��� �������, � ������ ��������!';
                            }
                            else
                            {
                                $item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=$item_id"),0,0);
                                $_SESSION['getsunduk']='�� '.echo_sex('������','�������').' ������ � '.echo_sex('�����','�����').' � ��� <b>'.$item_name.'</b>';
                            }
                        }
                        if ($char['map_xpos']==0 AND $char['map_ypos']==7)
                        {
                            $npc_templ = mysql_fetch_array(myquery("SELECT * FROM game_npc_template WHERE npc_id=".id_npc_nepruha.""));
                            myquery("INSERT INTO game_npc SET stay=1,npc_id='".$npc_templ['npc_id']."',map_name='".$char['map_name']."',xpos=0,ypos=7,view=0,dropable=0,HP=".$npc_templ['npc_max_hp'].",MP=".$npc_templ['npc_max_mp'].",npc_flag=0");
                            attack_npc($char,mysql_insert_id());
                        }
                    }
                }
                setLocation("act.php?getsunduk");
                {if (function_exists("save_debug")) save_debug(); exit;}
            }
			else
			{
				if ($this->fact['clan_id']>0 AND $this->fact['clan_id']!=$this->char['clan_id'])
				{
					setLocation("act.php?errror=wrong_clan");
					{if (function_exists("save_debug")) save_debug(); exit;}
				}
				else
				{
					$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$this->char['user_id']." AND type=1"),0,0);
					if ($this->char['CW']+$this->fact['weight']<=$this->char['CC'] or $prov>0)
					{
						$kol_inv = @mysql_result(@myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
						if (($this->fact['kol_per_user'] == 0) || ($kol_inv < $this->fact['kol_per_user']))
						{
							$ref_id = 1;
							if (($this->fact['type']==95 OR $this->counted_item() OR $this->fact['type']==16) and $this->fact['type']!=22) $ref_id = 0;
							if ($ref_id==0 AND $this->counted_item())
							{
								$check = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND priznak=0 AND used=0 AND item_id=".$this->fact['id']."");
								if (mysql_num_rows($check)==0)
								{
									$update_items = myquery("UPDATE game_items SET user_id=".$this->char['user_id'].", ref_id=0, map_name=0, map_xpos=0, map_ypos=0,priznak=0,count_item=1 WHERE id=".$this->item['id']."");
								}
								else
								{
									$update_items = myquery("UPDATE game_items SET count_item=count_item+1 WHERE user_id=".$this->char['user_id']." AND priznak=0 AND item_id=".$this->fact['id']." AND used=0");
									myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
								}
							}
							else
							{
								$update_items = myquery("UPDATE game_items SET user_id=".$this->char['user_id'].", ref_id='$ref_id', map_name=0, map_xpos=0, map_ypos=0,priznak=0 WHERE id=".$this->item['id']."");
							}
							//$delay = $user_time + $this->fact['weight']; //������� �������� ��������
							$update_users = myquery("UPDATE game_users SET CW=CW + ".$this->fact['weight']." WHERE user_id=".$this->char['user_id']." LIMIT 1");
							//set_delay_id($this->char['user_id'],$delay);
						}
						else
						{
							setLocation("act.php?errror=max_inv");
							{if (function_exists("save_debug")) save_debug(); exit;}
						}

					}
					else
					{
						setLocation("act.php?errror=full_inv");
						{if (function_exists("save_debug")) save_debug(); exit;}
					}
				}
			}
		}
	}  
	
	public function drop($id=0)
	{
		//1. �������� ��������� �� ������ ������� � ������ � ����� � ���� priznak=0 � item_for_quest=0
		//2. ������ �������� user_id, ������������� priznak=2,map_name,map_xpos,map_ypos,ref_id=1 (��� ������� ������ ������������� �� �����)
		//3. ���� ��� ������, �������, ���� ��� ������ "������" - �� � count_item �������� �� ����� ����������. ���� ��� ������� ����� � ��������� count_item ��� ���������� ������ ��������� ������� ��������� ��� count_item=1
		
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->fact['personal']==0 AND $this->item['used']==0 AND $this->item['item_for_quest']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->fact['type']!=95)
		{
			$weight = min($this->char['CW'],$this->fact['weight']);
			myquery("UPDATE game_users SET CW=CW-".$weight." WHERE user_id=".$this->item['user_id']." LIMIT 1");
			myquery("UPDATE game_users_archive SET CW=CW-".$weight." WHERE user_id=".$this->item['user_id']." LIMIT 1");
			if ($this->counted_item())
			{
				if ($this->fact['type']==22)
				{
				$ref_id=1;
				}
				else
				{
				$ref_id=0;
				}
				if ($this->item['count_item']==1)
				{
					myquery("UPDATE game_items SET user_id=0, map_name='".$this->char['map_name']."', map_xpos=".$this->char['map_xpos'].", map_ypos=".$this->char['map_ypos'].",priznak=2,ref_id=".$ref_id.",item_cost=0 WHERE id=".$this->item['id']." LIMIT 1");
				}
				else
				{
					myquery("INSERT INTO game_items SET user_id=0, map_name='".$this->char['map_name']."', map_xpos=".$this->char['map_xpos'].", map_ypos=".$this->char['map_ypos'].",priznak=2,ref_id=".$ref_id.",item_cost=0,count_item=1,item_id=".$this->item['item_id']."");
					myquery("UPDATE game_items SET count_item=count_item-1 WHERE id=".$this->item['id']."");
				}
			}
			else
			{
				myquery("UPDATE game_items SET user_id=0, map_name='".$this->char['map_name']."', map_xpos=".$this->char['map_xpos'].", map_ypos=".$this->char['map_ypos'].",priznak=2,ref_id=1,item_cost=0 WHERE id=".$this->item['id']." LIMIT 1");
			}
		}
	}
	
	public function info($id=0,$check_up=0,$from_town=0,$quote_width=0)
	{
		//������� ��������� ����������, ������ ���� ������� ������������ � ������������ ��� ���������� ������������� ����� ��� �������� ��������
		if ($id>0)
		{
			$this->init_item($id);
		}
		if (($this->item['user_id']==$this->char['user_id'] AND $this->item['priznak']==0) OR ($from_town>0))
		{
			if ($quote_width==0)
				QuoteTable('open');
			else
				QuoteTable('open',$quote_width);
			if (($this->item['ref_id']!=1) OR ($from_town>0))
			{
				if ($from_town==0)
				{
					echo '<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center">';
					ImageItem($this->fact['img'],0,$this->item['kleymo']);
					echo'<br><font color="#ffff00">'.$this->name.'</font>';
					if ($this->fact['race']<>0) echo'<br>������ ��� ����: <font color="#ff0000"><b>'.mysql_result(myquery("SELECT name FROM game_har WHERE id=".$this->fact['race'].""),0,0).'</b></font>';

					echo'</div></td><td valign="top">';
					echo'<div align="left"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>';
				}
				if ($this->fact['view']=='1' OR $this->item['user_id']==$this->char['user_id'])
				{
					if ($this->fact['type']!=99) echo'���: '.type_str($this->fact['type']).'<br>';
					if ($this->fact['in_two_hands']==1) echo '���������<br />';
					if ($this->fact["type"]==1 OR $this->fact["type"]==18)
					{
						switch ($this->fact["type_weapon"])
						{
							case 0:{echo '����� ������: ��� ������<br />';}break;
							case 1:{echo '����� ������: ��������<br />';}break;
							case 2:{echo '����� ������: ����������<br />';}break;
							case 3:{echo '����� ������: �������<br />';}break;
							case 4:{echo '����� ������: ��������<br />';}break;
							case 5:{echo '����� ������: �������<br />';}break;
							case 6:{echo '����� ������: �����������<br />';}break;
						}
					}
					if ($this->fact['curse']!='')
					{
						$str     = $this->fact['curse'];
						$order   = array("\r\n", "\n", "\r");
						$replace = '<br />';
						$newstr = str_replace($order, $replace, $str);
						echo $newstr.'<br>';
					}
					if ($this->fact['indx']<>0)
						if ($this->fact['type'] == 1 or $this->fact['type'] == 3 or $this->fact['type'] == 19 or $this->fact['type'] == 21) echo '����: ' . $this->fact['indx'] . '&nbsp;&plusmn;&nbsp;' . $this->fact['deviation'] . '<br>';
					if ($this->fact['type'] == 4) echo '������: ' . $this->fact['indx'] . '<br>';
					if ($this->fact['type'] == 3) echo '���-�� �������: '.$this->item['count_item'].'<br>';
					if (!$this->counted_item() AND $this->fact['type'] != 95 AND $this->fact['type'] != 20)
					{
						$use=$this->item['item_uselife'];
						echo '���������: '.$use.'%';
					}
					if (!$this->counted_item() AND $this->fact['breakdown']==1)
					{
						echo '<br />�������������: '.$this->item['item_uselife_max'].'/'.$this->fact['item_uselife_max'];
					}
					
					echo '<br />';

					if ($this->fact['weight']>0) 
					{
						if ($from_town==1 AND $this->counted_item())
						{
							echo'���: ' . $this->fact['weight']*$this->item['count_item'] . '<br><br>';
						}
						else
						{
							echo'���: ' . $this->fact['weight'] . '<br><br>';
						}
					}

					if (!$this->counted_item() AND $this->fact['type']!=20)
					{
						echo '������� ��������:<br>';
						if ($this->fact['dstr']<>'0') echo '���� ��: '.$this->fact['dstr'].'<br>';
						if ($this->fact['dntl']<>'0') echo '��������� ��: '.$this->fact['dntl'].'<br>';
						if ($this->fact['dpie']<>'0') echo '�������� ��: '.$this->fact['dpie'].'<br>';
						if ($this->fact['dvit']<>'0') echo '������ ��: '.$this->fact['dvit'].'<br>';
						if ($this->fact['ddex']<>'0') echo '������������ ��: '.$this->fact['ddex'].'<br>';
						if ($this->fact['dspd']<>'0') echo '�������� ��: '.$this->fact['dspd'].'<br>';
						if ($this->fact['dlucky']<>'0') echo '����� ��: '.$this->fact['dlucky'].'<br>';
						if ($this->fact['hp_p']<>'0') echo '����� ��: '.$this->fact['hp_p'].'<br>';
						if ($this->fact['mp_p']<>'0') echo '���� ��: '.$this->fact['mp_p'].'<br>';
						if ($this->fact['stm_p']<>'0') echo '������� ��: '.$this->fact['stm_p'].'<br>';
						if ($this->fact['cc_p']<>'0') echo '������� ��������� ��: '.$this->fact['cc_p'].'<br>';
					}
					else
					{
						if ($this->fact['type']==20)
						{
							$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['indx'].""),0,0);
							echo '����� ��� ������������ ��������: <b><font color=red>'.$item_name.'</font></b><br /><br />����������:<br />';
							switch ($this->fact['oclevel'])
							{
								case 1:
								{
									echo '������� ������ = 8<br />������� "����������" = 0<br />����� = 120 ���.<br />';
								}
								break;
								case 2:
								{
									echo '������� ������ = 12<br />������� "����������" = 55<br />����� = 180 ���.<br />';
								}
								break;
								case 3:
								{
									echo '������� ������ = 16<br />������� "����������" = 85<br />����� = 240 ���.<br />';
								}
								break;
								case 4:
								{
									echo '������� ������ = 20<br />������� "����������" = 115<br />����� = 300 ���.<br />';
								}
								break;
								case 5:
								{
									echo '������� ������ = 24<br />������� "����������" = 145<br />����� = 420 ���.<br />';
								}
								break;
							}
							$sel_schema = myquery("SELECT game_items_schema.*,craft_resource.name FROM game_items_schema,craft_resource WHERE game_items_schema.item_id=".$this->fact['id']." AND game_items_schema.res_id=craft_resource.id");
							while ($schema = mysql_fetch_array($sel_schema))
							{
								echo '<br />������: <b>'.$schema['name'].'</b>, ����������: <b>'.$schema['col'].'</b>';	
							}                          
						}
						elseif ($this->fact['type']==21)
						{
							$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['quantity'].""),0,0);
							echo '<br />������������ � ���������: <b><font color=red>'.$item_name.'</font></b><br /><br />';
						}
											}
					if ($this->fact['can_up']==0)
					{
						echo '<br /><br />������ ������� ����� ������.';
					}
					if ($this->fact['clan_id']>0)
					{
						$nazv = mysqlresult(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->fact['clan_id'].""),0,0);
						echo'<br>������ ��� �����: <b>'.$nazv.'</b><br>';
					}
					if ($this->fact['id']==license_item_id)
					{
						list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$this->item['for_town'].""));
						echo '<br /><br />�������� �� ������������� ������������ � ������ <b>'.$rustown.'</b>';
					}
					if ($this->item['kleymo']==1)
					{
						echo '<br /><i><u>������� ��������� ������ <img src="http://'.IMG_DOMAIN.'/clan/'.$this->item['kleymo_id'].'.gif"><br />������ ������� ����� ���� ��������� � ��������� ����� ����� � ����� �����</u></i><br />';
					}
					if ($this->item['kleymo']==2)
					{
						$name_user = get_user("name",$this->item['kleymo_id']);
						echo '<br /><i><u>������� ��������� ������� <b>'.$name_user.'</b><br />������ ������� ����� ���� ��������� � ��������� ��������� ������ � ����� �����</u></i><br />';
					}
				}
				if ($from_town==0)
				{
					echo'</div></td></tr></table>';
				}
			}
			else
			{
				echo '<span class="ERROR">�� ����������������</span>';
			}
			QuoteTable('close');
		}
	}

	public function hint($id=0,$check_up=0,$str_begin="<span ",$from_template=0)
	{                                    
		//������� ���������-����������, ������ ���� ������� ������������ � ������������ ��� ���������� ������������� ����� ��� �������� ��������
		if ($id>0)
		{
			if ($from_template==0)
			{
				$this->init_item($id);
			}
			else
			{
				$this->init_fact($id);    
			}
		}
		if ($check_up!=0)
		{
			$can_up = $this->check_up();
		}
		echo $str_begin;
		//��� ����
		?> onmousemove=movehint(event) onmouseover="showhint('<?php
		;
		if ($this->item['ref_id']==1 AND $this->item['priznak']==0 AND $this->fact["type"]!=95 AND !$this->counted_item() and ($this->item['user_id']==$this->item['kleymo_id'] or $this->item['kleymo']==0))			
		{
			echo '<b><font color=#FF0000>�� ����������������</font></b>';
		}
		else
		{
			echo '<center><font color=#0000FF>'.htmlspecialchars($this->fact['name']).'</font>';
			if ($this->fact['curse']!='')
			{
				$str     = htmlspecialchars($this->fact['curse']);
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				$newstr = str_replace($order, $replace, $str);
				echo '<hr><font color=#000000>'.$newstr.'</font>';
			}
		}
		?>','<?php echo '<font color=000000>';
		if ($this->item['ref_id']==1 AND $this->item['priznak']==0 AND $this->fact["type"]!=95 AND !$this->counted_item() and ($this->item['user_id']==$this->item['kleymo_id'] or $this->item['kleymo']==0))
		{
			echo '<b><font color=#FF0000>�� ����������������</font></b>';
		}
		else
		{
			if ($from_template==0) 
			{
				if ($this->item['kleymo']==1)
				{
					echo '<i><u>������� ��������� ������ <img src=http://'.IMG_DOMAIN.'/clan/'.$this->item['kleymo_id'].'.gif><br />������ ������� ����� ���� ��������� � ��������� ����� ����� � ����� �����</u></i><br /><br />';
				}
				if ($this->item['kleymo']==2)
				{
					$name_user = get_user("name",$this->item['kleymo_id']);
					echo '<i><u>������� ��������� ������� <b>'.$name_user.'</b><br />������ ������� ����� ���� ��������� � ��������� ��������� ������ � ����� �����</u></i><br /><br />';
				}
			}
			if ($this->fact['view']==1 OR $this->item['user_id']==$this->char['user_id'] or $this->item['kleymo_id']==$this->char['user_id'])
			{
				if ($this->fact['race']<>0) echo'<br />������ ��� ����: <font color=ff0000><b>'.mysqlresult(myquery("SELECT name FROM game_har WHERE id=".$this->fact['race'].""),0,0).'</b></font>';
				if ($this->fact["type"]!=99 AND $this->fact["type"]!=95 AND $this->fact["type"]!=98) echo '<br />���: '.type_str($this->fact["type"]).'';
				if ($this->fact['in_two_hands']==1) echo '<br />���������';
				if ($this->fact["type"]==1 OR $this->fact["type"]==18)
				{
					switch ($this->fact["type_weapon"])
					{
						case 1:{echo '<br />����� ������: ��������';}break;
						case 2:{echo '<br />����� ������: ����������';}break;
						case 3:{echo '<br />����� ������: �������';}break;
						case 4:{echo '<br />����� ������: ��������';}break;
						case 5:{echo '<br />����� ������: �������';}break;
						case 6:{echo '<br />����� ������: �����������';}break;
					}
				}
				if ($this->fact['indx']<>0)
					if ($this->fact['type'] == 1 or $this->fact['type'] == 3 or $this->fact['type'] == 19 or $this->fact['type'] == 21) echo '<br />����: ' . $this->fact['indx'] . '&nbsp;&plusmn;&nbsp;' . $this->fact['deviation'] . '';
				if ($this->fact['type'] == 4) echo '<br />������: ' . $this->fact['indx'] . '';
				if ($from_template==0) 
				{
					if ($this->fact['type'] == 3) echo '<br />���-�� �������: '.$this->item['count_item'].'';
					if (!$this->counted_item() AND $this->fact['type'] != 95 AND $this->fact['type'] != 20)
					{
						$use=$this->item['item_uselife'];
						echo '<br />���������: '.$use.'%';
					}
					if (!$this->counted_item() AND $this->fact['breakdown']==1)
					{
						echo '<br />�������������: '.$this->item['item_uselife_max'].'/'.$this->fact['item_uselife_max'];
					}
				}

				if ($this->fact['weight'] >0 ) 
				{
					if ($from_template==0 AND $this->counted_item())
					{
						echo'<br /><br />���: '.$this->fact['weight']*$this->item['count_item'].'';
					}
					else
					{
						echo'<br /><br />���: '.$this->fact['weight'].'';
					}
				}
					   
				if ($this->fact['can_up']==0)
				{
					echo '<br /><br />������ ������� ����� ������.';
				}
				if ($this->fact['clan_id']>0)
				{
					$nazv = mysqlresult(myquery("SELECT nazv FROM game_clans WHERE clan_id=".$this->fact['clan_id'].""),0,0);
					echo'<br>������ ��� �����: <b>'.$nazv.'</b><br>';
				}
				if ($this->fact['id']==license_item_id AND $from_template==0)
				{
					list($rustown) = mysql_fetch_array(myquery("SELECT rustown FROM game_gorod WHERE town=".$this->item['for_town'].""));
					echo '<br />�������� �� ������������� ������������ � ������ <b>'.$rustown.'</b><br />';
				}
				if (!$this->counted_item() AND $this->fact['type']!=20)
				{           
					echo'<br /><b>������� ��������:</b>'; 
					if ($this->fact['dstr']<>'0')  echo '<br />���� ��: '.$this->fact['dstr'].'';
					if ($this->fact['dntl']<>'0')  echo '<br />��������� ��: '.$this->fact['dntl'].'';
					if ($this->fact['dpie']<>'0')  echo '<br />�������� ��: '.$this->fact['dpie'].'';
					if ($this->fact['dvit']<>'0')  echo '<br />������ ��: '.$this->fact['dvit'].'';
					if ($this->fact['ddex']<>'0')  echo '<br />������������ ��: '.$this->fact['ddex'].'';
					if ($this->fact['dspd']<>'0')  echo '<br />�������� ��: '.$this->fact['dspd'].'';
					if ($this->fact['dlucky']<>'0')  echo '<br />����� ��: '.$this->fact['dlucky'].'';
					if ($this->fact['hp_p']<>'0')  echo '<br />����� ��: '.$this->fact['hp_p'].'';
					if ($this->fact['mp_p']<>'0')  echo '<br />���� ��: '.$this->fact['mp_p'].'';
					if ($this->fact['stm_p']<>'0') echo '<br />������� ��: '.$this->fact['stm_p'].'';
					if ($this->fact['cc_p']<>'0')  echo '<br />������� ��������� ��: '.$this->fact['cc_p'].'';

					echo '<br /><br /><font color=ff0000>������� �������:';
					if ($this->fact['oclevel']>'0')  echo '<br />�������: '.$this->fact['oclevel'].'';
					if ($this->fact['ostr']>'0')  echo '<br />����: '.$this->fact['ostr'].'';
					if ($this->fact['ontl']>'0')  echo '<br />���������: '.$this->fact['ontl'].'';
					if ($this->fact['opie']>'0')  echo '<br />��������: '.$this->fact['opie'].'';
					if ($this->fact['ovit']>'0')  echo '<br />������: '.$this->fact['ovit'].'';
					if ($this->fact['odex']>'0')  echo '<br />������������: '.$this->fact['odex'].'';
					if ($this->fact['ospd']>'0')  echo '<br />��������: '.$this->fact['ospd'].'';
					if ($this->fact['olucky']>'0')  echo '<br />�����: '.$this->fact['olucky'].'';
					echo '</font>';
					if ($check_up>0)
					{
						echo '<hr>';
						if ($can_up == 1)
						{
							echo '<br />�� ������ ����� ���� �������';
						}
						else
						{
							echo $can_up.'<br /><b><font color=red>�� �� ������� ����� ���� �������</font></b>';
						}
					}
				}
				else
				{
					if ($this->fact['type']==20)
					{
						$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['indx'].""),0,0);
						echo '<br />����� ��� ������������ ��������: <b><font color=red>'.$item_name.'</font></b><br /><br />����������:<br />';
						switch ($this->fact['oclevel'])
						{
							case 1:
							{
								echo '������� ������ = 8<br />������� \\\'����������\\\' = 0<br />����� = 120 ���.<br />';
							}
							break;
							case 2:
							{
								echo '������� ������ = 12<br />������� \\\'����������\\\' = 55<br />����� = 180 ���.<br />';
							}
							break;
							case 3:
							{
								echo '������� ������ = 16<br />������� \\\'����������\\\' = 85<br />����� = 240 ���.<br />';
							}
							break;
							case 4:
							{
								echo '������� ������ = 20<br />������� \\\'����������\\\' = 115<br />����� = 300 ���.<br />';
							}
							break;
							case 5:
							{
								echo '������� ������ = 24<br />������� \\\'����������\\\' = 145<br />����� = 420 ���.<br />';
							}
							break;
						}                          
						$sel_schema = myquery("SELECT game_items_schema.*,craft_resource.name FROM game_items_schema,craft_resource WHERE game_items_schema.item_id=".$this->fact['id']." AND game_items_schema.res_id=craft_resource.id");
						while ($schema = mysql_fetch_array($sel_schema))
						{
							echo '<br />������: <b>'.$schema['name'].'</b>, ����������: <b>'.$schema['col'].'</b>';    
						}                          
					}
					elseif ($this->fact['type']==21)
					{
						$item_name = mysqlresult(myquery("SELECT name FROM game_items_factsheet WHERE id=".$this->fact['quantity'].""),0,0);
						echo '<br />������������ � ���������: <b><font color=red>'.$item_name.'</font></b><br /><br />';
					}
					elseif ($this->fact['type']==13)
					{
						echo '<br><br>';
						list($dlit)=mysql_fetch_array(myquery("Select dlit from game_eliksir_dlit where elik_id=".$this->fact['id'].""));
						$dlit=$dlit/60;
						if ($this->fact['id']==eliksir_mogushestva_item_id)
						{
							echo '<i>���������� '.$this->fact['stm_p'].' ������ � ������������� ������ ������� �� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
						}
						elseif ($this->fact['id']==eliksir_bodrosti_item_id)
						{
							echo "<i>��� ��������� �������� �� �� ������ �������� (������� \'���������, �� ������\' �� �������� ���� ��� ���������). ��������� ".$dlit." ".pluralForm($dlit,'������','������','�����').".</i>";
						}
						elseif ($this->fact['id']==eliksir_zorkosti_item_id)
						{
							echo '<i>��� ������������� �������� �� ������� ������������ �������� �������� ����������� ������ ������� (������ ������ ���� ��������� �������). ��������� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
						}
						elseif ($this->fact['id']==eliksir_nevidimka_item_id)
						{
							echo '<i>��� ��������� �������� �� ������� ��� ������ �������. ��������� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
						}
						else
						{
							if ($dlit==0)
							{
								if ($this->hp_p>0)
								{
									echo '<i>�������� �������� �� '.$this->hp_p.' '.pluralForm($this->hp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->mp_p>0)
								{
									echo '<i>�������� ���� �� '.$this->mp_p.' '.pluralForm($this->mp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->stm_p>0)
								{
									echo '<i>�������� ������� �� '.$this->stm_p.' '.pluralForm($this->stm_p,'�������','�������','������').'.</i><br>';
								}
							}
							else
							{
								if ($this->hp_p>0)
								{
									echo '<i>�������� ������������ ������� �������� �� '.$this->hp_p.' '.pluralForm($this->hp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->mp_p>0)
								{
									echo '<i>�������� ������������ ������� ���� �� '.$this->mp_p.' '.pluralForm($this->mp_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->stm_p>0)
								{
									echo '<i>�������� ������������ ������� ������� �� '.$this->stm_p.' '.pluralForm($this->stm_p,'�������','�������','������').'.</i><br>';
								}
								if ($this->dstr>0)
								{
									echo '<i>�������� ���� �� '.$this->dstr.' '.pluralForm($this->dstr,'�������','�������','������').'.</i><br>';
								}
								if ($this->dntl>0)
								{
									echo '<i>�������� ��������� �� '.$this->dntl.' '.pluralForm($this->dntl,'�������','�������','������').'.</i><br>';
								}
								if ($this->dpie>0)
								{
									echo '<i>�������� �������� �� '.$this->dpie.' '.pluralForm($this->dpie,'�������','�������','������').'.</i><br>';
								}
								if ($this->dspd>0)
								{
									echo '<i>�������� �������� �� '.$this->dspd.' '.pluralForm($this->dspd,'�������','�������','������').'.</i><br>';
								}
								if ($this->dvit>0)
								{
									echo '<i>�������� ������ �� '.$this->dvit.' '.pluralForm($this->dvit,'�������','�������','������').'.</i><br>';
								}
								if ($this->ddex>0)
								{
									echo '<i>�������� ������������ �� '.$this->ddex.' '.pluralForm($this->ddex,'�������','�������','������').'.</i><br>';
								}
								if ($this->dlucky>0)
								{
									echo '<i>�������� ����� �� '.$this->dlucky.' '.pluralForm($this->dlucky,'�������','�������','������').'.</i><br>';
								}
								if ($this->cc_p>0)
								{
									echo '<i>�������� ������������ ��� �� '.$this->cc_p.' '.pluralForm($this->cc_p,'�������','�������','������').'.</i><br>';
								}
								echo '<i>��������� '.$dlit.' '.pluralForm($dlit,'������','������','�����').'.</i>';
							}
						}
					}
				}
			}
		}
		?>',0,1,event)" onmouseout="showhint('','',0,0,event)"
		<?php
		echo '>';
	}
	
	public function identify($id=0)
	{
		//������� ������ ���� � ������ � priznak=0 � used=0 � ref_id=1 � ����� ������ ���� �� ����������� �������� � �������� ������ ���������� ��������������
		$ar = array(0,'');
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->item['ref_id']==1 AND $this->item['priznak']==0)
		{
			if ($this->fact['oclevel']==0) $this->fact['oclevel']=$this->char['clevel'];
			if ($this->fact['oclevel']<=10) $cena = round($this->fact['oclevel']*0.5,0);
			else $cena = $this->fact['oclevel'];
			if ($this->char['GP']>=$cena)
			{
				if ($this->fact['type']==22)
				{
					$check1=myquery("select * from game_items where item_id=".$this->item['item_id']." and user_id=".$this->char['user_id']." and ref_id=0 and priznak=0");
					if (mysql_num_rows($check1)>0) $check2=1;
				}
				if (isset($check2))
				{
				$upd=myquery("update game_items set count_item=count_item+1 where item_id=".$this->item['item_id']." and user_id=".$this->char['user_id']." and ref_id=0 and priznak=0 Limit 1");
				$del=myquery("delete from game_items where id=".$this->item['id']."");
				}
				else
				{
					$upd=myquery("update game_items set ref_id='0' where id=".$this->item['id']."");
				}
				$user=myquery("update game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' where user_id=".$this->char['user_id']."");
				setGP($this->char['user_id'],-$cena,7);
				$ar[0]=$cena;
				$ar[1]=$this->fact['name'];
			}
			else echo '��������� �����';
		}
		else 
		{
			//echo '������ ����������������';
		}
		return $ar;
	}

	public function kleymo_return($id)
	{
		$this->init_item($id);
		$error = 1;
		if ($this->item['kleymo']==1)
		{
			//������� ��������� ����������� ��������
			if (mysql_num_rows(myquery("SELECT clan_id FROM game_clans WHERE glava=".$this->char['user_id']." AND raz=0"))>0)
			{
				if ($this->item['kleymo_id']==$this->char['clan_id'])
				{
					if (($this->char['CC']-$this->char['CW'])>=$this->fact['weight'])
					{
						$error = 0;
					}
					else
					{
						echo '�� ������� ���������� ����� � ���������!<br />';
					}
				}
				else
				{
					echo '������� ��������� �� ����� ������!<br />';
				}    
			}
			else
			{
				echo '�� �� ����� �����!<br />';
			}
		}
		else
		{
			//������� ������� ����������� ��������
			if ($this->item['kleymo_id']==$this->char['user_id'])
			{
				if (($this->char['CC']-$this->char['CW'])>=$this->fact['weight'])
				{
					$error = 0;
				}
				else
				{
					echo '�� ������� ���������� ����� � ���������!<br />';
				}
			}
			else
			{
				echo '������� ��������� �� �����!<br />';
			}    
		}
		if ($error == 0)
		{
			myquery("UPDATE game_items SET user_id=".$this->char['user_id'].",priznak=0,map_name=0,map_xpos=0,map_ypos=0,town=0,sell_time=0,post_to=0,post_var=0 WHERE id=".$this->item['id']."");
			myquery("UPDATE game_users SET CW=CW+".$this->fact['weight']." WHERE user_id=".$this->char['user_id']."");
			if ($this->item['priznak']==0)
			{
				if ($this->item['used']>0)
				{
					$this->item_down();
				}    
				myquery("UPDATE game_users SET CW=CW-".$this->fact['weight']." WHERE user_id=".$this->item['user_id']."");
				myquery("INSERT INTO game_pm (komu, otkogo, theme, post, view, time) VALUES ('".$this->item['user_id']."', '0', '������� ����������� ��������', '�������� ������ ".$this->char['name']." �������� ".$this->fact['name']." ������ ���� �������, ������������ � ����','0','".time()."')");
			}
		}
	}
	
	public function kleymo($type_kleymo,$unset)
	{
		//������� ������ ���� � ������ � priznak=0 � used=0 � ref_id=1 � ����� ������ ���� �� ����������� �������� � �������� ������ ���������� ��������������
		$ar = array(0,'');
		if ($unset==0)
		{
			if ($this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['kleymo']==0 AND !$this->counted_item() AND !$this->fact['personal']==1)
			{
				if ($type_kleymo==2) //������ ���������
				{
					$cena = $this->char['clevel'];
				}
				if ($type_kleymo==1) //�������� ���������
				{
					$cena = 20+$this->fact['oclevel'];
				}
				if ($this->char['GP']>=$cena)
				{
					if ($type_kleymo==1)
					{
						$kleymo_id = $this->char['clan_id'];
					}
					else
					{
						$kleymo_id = $this->char['user_id'];
					}
					$sel_kleymo_nomer = myquery("SELECT kleymo_nomer FROM game_items WHERE kleymo=$type_kleymo AND kleymo_id=$kleymo_id ORDER BY kleymo_nomer DESC LIMIT 1");
					if (mysql_num_rows($sel_kleymo_nomer)>0)
					{
						list($kleymo_nomer)= mysql_fetch_array($sel_kleymo_nomer);
						$kleymo_nomer++;
					}
					else
					{
						$kleymo_nomer = 1;
					}
					$upd=myquery("update game_items set kleymo='$type_kleymo',kleymo_nomer=$kleymo_nomer,kleymo_id=$kleymo_id where id=".$this->item['id']."");
					$user=myquery("update game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' where user_id=".$this->char['user_id']."");
					setGP($this->char['user_id'],-$cena,82);
					$ar[0]=$cena;
					$ar[1]=$this->fact['name'];
				}
				else echo '��������� �����';
			}
		}
		else
		{
			if ($this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['kleymo']!=0 AND !$this->counted_item())
			{
				$flag = 0;
				if ($this->item['kleymo']==1)
				{
					if ($this->item['kleymo_id']==$this->char['clan_id'])
					{
						$flag = 1;
					}
				}
				if ($this->item['kleymo']==2)
				{
					if ($this->item['kleymo_id']==$this->char['user_id'])
					{
						$flag = 1;
					}
				}
				if ($this->item['kleymo']==2) //������ ������ ������� ��������
				{
					$cena = $this->char['clevel']*2;
				}
				if ($this->item['kleymo']==1) //������ ������ ��������� �������� ��������
				{
					$cena = (20+$this->fact['oclevel'])*2;
				}
				if ($this->char['GP']>=$cena AND $flag==1)
				{
					$upd=myquery("update game_items set kleymo=0,kleymo_nomer=0,kleymo_id=0 where id=".$this->item['id']."");
					$user=myquery("update game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' where user_id=".$this->char['user_id']."");
					setGP($this->char['user_id'],-$cena,83);
					$ar[0]=$cena;
					$ar[1]=$this->fact['name'];
				}
				else echo '��������� �����';
			}
		}
		return $ar;
	}

	public function sell($id=0)
	{
		global $shop;
		$ar = array(-1,0,'');
		//������� ������ ���� � ������ � priznak=0 � used=0 � ref_id=0 � ����� ������ ���� �� ����������� �������� � �������� ������ �������� ��� ����
		if ($id>0)
		{
			$this->init_item($id);
		}
		$user_id = $this->char['user_id'];
		if ($this->item['user_id']==$user_id AND $this->item['used']==0 AND $this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['kleymo']==0)
		{
            /*
			$cena1=round($this->fact['item_cost']/100*$shop['cena_pok'],2);
			$cena2=round($this->item['item_cost']/100*$shop['cena_pok'],2);
			$cena = min($cena1,$cena2);
			*/
            $cena = round($this->fact['item_cost']/100*$shop['cena_pok'],2);
			if ($this->fact['type']==3) $this->fact['item_uselife']=100;
			if ($this->item['item_uselife']<0) $this->item['item_uselife']=0;
			if ($this->item['item_uselife']<($this->fact['item_uselife']*0.9) AND $this->fact['type']<90 AND $this->fact['type']!=12 AND $this->fact['type']!=13)
			{
				if ($this->fact['item_uselife']>0)
				{
					$cena = round($cena*$this->item['item_uselife']/$this->fact['item_uselife'],2);
				}
			}
            $del = $this->fact['item_uselife_max'];
            if ($del==0) $del=1;
            $koef_prochnost = min(1,max(0,$this->item['item_uselife_max']/$del));
            $cena = round($cena*$koef_prochnost,2);
			if ($this->item['kleymo']>0)
			{
				$cena = 0;
			}

			echo'<center><b><font color=ff0000 face=verdana size=2>������ �������: '.$this->fact['name'].' �� '.$cena.' �������</font></b></center>';
			$this->delete_item();
			myquery("update game_users set GP=GP+$cena where user_id=".$this->char['user_id']."");
			setGP($this->char['user_id'],$cena,8);
			$ar[0]=$cena;
			$ar[1]=$this->fact['weight'];
			$ar[2]=$this->fact['name'];
		}
		else
		{
			//echo'<center><b><font color=ff0000 face=verdana size=2>������ �������</font></b></center>';
		}
		return $ar;
	}
	
	public function buy($id=0)
	{
		global $shop;
		
		$ar = array(0,0,'');
		//����� ������ ���� �� ����������� �������� � �������� ������ ��������� ��� ���� � ��� ���� ���� �� ������� � � ������ ���������� ���������� ����
		$need_add = 1;

		if ($id>0)
		{
			$this->init_fact($id);
		}
		else
		{
			return $ar;
		}
		$cena=round($this->fact['item_cost']/100*$shop["cena_prod"],2);
		$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$this->char['user_id']." AND type=1"),0,0);

		if ($need_add == 1 and $this->char['GP']>=$cena and ($this->char['CW']<=($this->char['CC']-$this->fact['weight']) OR $prov>0))
		{
			$error = '';
			$need_add = 1;

			$kol_inv = mysql_result(myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);

			if (($this->fact['kol_per_user'] != 0) && ($kol_inv >= $this->fact['kol_per_user']))
			{
				$error = '������ ������ ����� '.$this->fact['kol_per_user'].' ���������.';
				$need_add = 0;
			}

			if ($this->counted_item())
			{
				if ($kol_inv > 0 && $error == '')
				{
					myquery("UPDATE game_items SET count_item=count_item+1 WHERE priznak = 0 AND item_id=".$this->fact['id']." AND user_id=".$this->char['user_id']." LIMIT 1;");
					$need_add = 0;
				}
				else
				{
					$need_add = 1;
				}
				$item_uselife = 100;
			}
			else
			{
				$item_uselife = $this->fact['item_uselife'];
			}

			if ($this->fact['clan_id']>0 AND $this->fact['clan_id']!=$this->char['clan_id'])
			{
				$error = '���� ������� �� ��� ������ �����!';
			}
			if ($error=='')
			{
				$ar[0] = $cena;
				$ar[1] = $this->fact['weight'];
				$ar[2] = $this->fact['name'];
				$result=myquery("update game_users set gp=gp-$cena, CW=CW + ".$this->fact['weight']." where user_id=".$this->char['user_id']."");
				setGP($this->char['user_id'],-$cena,9);
				if ($need_add==1)
				{
					$count_item = 1;
					if ($this->fact['type']==3)
					{
						$count_item = $item_uselife;
						$item_uselife = 100;
					}
					myquery("insert into game_items (user_id, item_id, item_uselife, item_uselife_max, item_cost, ref_id, shop_from, count_item) values (".$this->char['user_id'].", ".$this->fact['id'].", $item_uselife, ".$this->fact['item_uselife_max'].", ".$this->fact['item_cost'].",0, ".$shop['id'].",$count_item)");
				}
				if ($this->char['clevel']<5)
				{
					if ($this->fact['type']==1)
					{   
						//���� ���������� ������ - ��� ����� ���� �����, ���������� ��������� �����
						 $step = mysqlresult(myquery("SELECT step FROM game_users_intro WHERE user_id=".$this->char['user_id'].""),0,0);
						 if ($step==6)
						 {
							 myquery("UPDATE game_users_intro SET step=7 WHERE user_id=".$this->char['user_id']."");
						 }
					}
					if ($this->fact['type']==4 OR $this->fact['type']==5)
					{   
						//���� ���������� ������ ��� ��� - ��� ����� ���� �����, ���������� ��������� �����
						 $step = mysqlresult(myquery("SELECT step FROM game_users_intro WHERE user_id=".$this->char['user_id'].""),0,0);
						 if ($step==8)
						 {
							 myquery("UPDATE game_users_intro SET step=9 WHERE user_id=".$this->char['user_id']."");
						 }
					}
				}
				echo '<b><center><font color=ff0000><br>������� '.$this->fact['name'].' ������ �� '.$cena.' �����!</font></center></b>';
			}
			else
			{
				echo '<b><center><font color=ff0000><br> '.$error.'</font></center></b>';
			}
		}
		else echo '<b><center><font color=ff0000><br>��������� ����� ��� ���� ��������� ����������!</font></center></b>';
        return $ar;
	}

	public function repair($id=0)
	{
		$ar = array(0,'');
		//����� ������ ���� �� ����������� �������� � �������� ������ ������������� ��� ����
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->fact['type']!=20 AND $this->fact['type']<90  AND !$this->counted_item() AND $this->item['item_uselife']<100)
		{
			if ($this->fact['type']==3)
			{
				$this->fact['item_uselife'] = 100;
			}
			$cena=$this->fact['item_uselife']-$this->item['item_uselife'];
			if ($this->fact['oclevel']<=0) $this->fact['oclevel']=$this->char['clevel'];
			if ($this->item['item_uselife']>0.75*$this->fact['item_uselife']) $cena=$cena*$this->fact['oclevel']*0.1/2;
			elseif ($this->item['item_uselife']>0.50*$this->fact['item_uselife']) $cena=$cena*$this->fact['oclevel']*0.15/2;
			elseif ($this->item['item_uselife']>0.25*$this->fact['item_uselife']) $cena=$cena*$this->fact['oclevel']*0.2/2;
			elseif ($this->item['item_uselife']>0) $cena=$cena*$this->fact['oclevel']*0.25/2;
			elseif ($this->item['item_uselife']<=0) $cena=$cena*$this->fact['oclevel']*0.5/2;

			if ($cena<1) $cena=1;
			if ($cena>($this->fact['item_cost']*0.75) and $this->fact['item_cost']>0) $cena=$this->fact['item_cost']*0.75;
			$cena = round($cena,2);
			if ($this->char['win']>$this->char['lose']*3)
			{
				$cena=round($cena*0.75,2);
			}
			elseif ($this->char['win']>$this->char['lose'])
			{
				$cena=round($cena*0.9,2);
			}
			$da = getdate();
			if ($da['mon']==7 AND $da['mday']==15)
			{
				$cena = 0;
			}
			if ($da['mon']==12 AND $da['mday']==31)
			{
				$cena = 0;
			}
			if ($da['mon']==1 AND $da['mday']>=1 AND $da['mday']<=7)
			{
				$cena = 0;
			}
			if ($this->char['clevel']<5)
			{
				$cena = 0;
			}

			if ($this->char['GP']>=$cena)
			{
				$ar[0] = $cena;
				$ar[1] = $this->name;
				$breakdown = 0;
				if ($this->fact['breakdown']==1)
				{
					//$chance = (100-$this->item['item_uselife'])/4;
					//$r = mt_rand(1,100);
					//if ($chance<$r)
					//{
						//��������� "������" ��������
						$breakdown = 1;
					//} 
				}   
				$upd=myquery("update game_items set item_uselife=".$this->fact['item_uselife'].",item_uselife_max=item_uselife_max-$breakdown where id=".$this->item['id']."");
				$user=myquery("update game_users set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' where user_id=".$this->item['user_id']."");
				$user=myquery("update game_users_archive set GP=GP-$cena,CW=CW-'".($cena*money_weight)."' where user_id=".$this->item['user_id']."");
				setGP($this->item['user_id'],-$cena,11);
				if ($this->item['item_uselife']<=0)
				{
					$crack = mt_rand(1,100);
					if ($crack<=3)
					{
						$this->admindelete();
						echo'<center><br><br><BR><BR><BR><h3>��, ��� ����� ����... ���� �� ���� ��� ������� - ��� <br>*������ ����������� ���� ��������� ����������� ����*';
					}
					else 
					{
						echo'<center><br><br><BR><BR><BR><h3>���������������';
					}
				}
				else
				{
					if ($this->item['item_uselife_max']-$breakdown<=0 AND $this->fact['breakdown']==1)
					{
						$this->admindelete();
						echo'<center><br><br><BR><BR><BR><h3>�� ������...���� ������� ��� ������ ��������<br>*������ ����������� ���� ��������� ����������� ����*';
					}
					else 
					{
						echo'<center><br><br><BR><BR><BR><h3>���������������';
					}
				}
			}
			else echo'<center><br><br><BR><BR><BR><h3>�� ������� �����';
		}
		return $ar;
	}
	
	public function use_item($id=0)
	{
		global $char;
		if ($id>0)
		{
			$this->init_item($id);
		}
		//����� ������������ ������ ��������
		if ($this->fact['type']==13)
		{
			if ($this->item['user_id']==$this->char['user_id'])
			{
				if ($this->item['priznak']==0)
				{
					list($dlit)=mysql_fetch_array(myquery("Select dlit From game_eliksir_dlit Where elik_id=".$this->fact['id'].""));
					//�������� ���������������� ��������
					if ($dlit>0)
					{
						if ($this->fact['id']==eliksir_bodrosti_item_id)
						{
							//����� ��������
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=3");
							myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+$dlit).",3)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['id']==eliksir_zorkosti_item_id)
						{
							//����� ��������
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=4");
							myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+$dlit).",4)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['id']==eliksir_nevidimka_item_id)
						{
							//����� �����������
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=5");
							myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+$dlit).",5)");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						elseif ($this->fact['id']==eliksir_mogushestva_item_id)
						{
							//����� ����������
							//�������� �� ������� ������������ ��������
							$check = myquery("SELECT * FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=2 AND harka='STM' AND time_end>".time()."");
							if (mysql_num_rows($check)>0)
							{
								myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=2 AND harka='STM'");
							}
							else
							{
								myquery("UPDATE game_users SET STM_MAX=STM_MAX+".$this->fact['stm_p']." WHERE user_id=".$this->char['user_id']."");
							}
							myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STM',2,".(time()+$dlit).",".$this->fact['stm_p'].")");
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						else
						{
							// ��������� �����
							$count = myquery("SELECT Distinct time_end FROM game_obelisk_users WHERE type=2 AND time_end>".time()." AND user_id=".$this->item['user_id']." AND harka!='STM'");
							if (mysql_num_rows($count)<3)
							{
								if ($this->fact['hp_p']>0)
								{
									myquery("UPDATE game_users SET HP_MAX=HP_MAX+".$this->fact['hp_p']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'HP_MAX',2,".(time()+$dlit).",".$this->fact['hp_p'].")");
								}
								if ($this->fact['mp_p']>0)
								{
									myquery("UPDATE game_users SET MP_MAX=MP_MAX+".$this->fact['mp_p']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'MP_MAX',2,".(time()+$dlit).",".$this->fact['mp_p'].")");
								}
								if ($this->fact['stm_p']>0)
								{
									myquery("UPDATE game_users SET STM_MAX=STM_MAX+".$this->fact['stm_p']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STM_MAX',2,".(time()+$dlit).",".$this->fact['stm_p'].")");
								}
								if ($this->fact['dstr']>0)
								{
									myquery("UPDATE game_users SET STR=STR+".$this->fact['dstr']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STR',2,".(time()+$dlit).",".$this->fact['dstr'].")");
								} 
								if ($this->fact['dspd']>0)
								{
									myquery("UPDATE game_users SET SPD=SPD+".$this->fact['dspd']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'SPD',2,".(time()+$dlit).",".$this->fact['dspd'].")");
								} 
								if ($this->fact['dntl']>0)
								{
									myquery("UPDATE game_users SET NTL=NTL+".$this->fact['dntl']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'NTL',2,".(time()+$dlit).",".$this->fact['dntl'].")");
								} 
								if ($this->fact['dpie']>0)
								{
									myquery("UPDATE game_users SET PIE=PIE+".$this->fact['dpie']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'PIE',2,".(time()+$dlit).",".$this->fact['dpie'].")");
								} 
								if ($this->fact['dvit']>0)
								{
									myquery("UPDATE game_users SET VIT=VIT+".$this->fact['dvit']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'VIT',2,".(time()+$dlit).",".$this->fact['dvit'].")");
								} 
								if ($this->fact['ddex']>0)
								{
									myquery("UPDATE game_users SET DEX=DEX+".$this->fact['ddex']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'DEX',2,".(time()+$dlit).",".$this->fact['ddex'].")");
								} 
								if ($this->fact['cc_p']>0)
								{
									myquery("UPDATE game_users SET CC=CC+".$this->fact['cc_p']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'CC',2,".(time()+$dlit).",".$this->fact['cc_p'].")");
								} 
								if ($this->fact['dlucky']>0)
								{
									myquery("UPDATE game_users SET LUCKY=LUCKY+".$this->fact['dlucky']." WHERE user_id=".$this->char['user_id']."");
									myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'LUCKY',2,".(time()+$dlit).",".$this->fact['dlucky'].")");
								} 
								$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
							}
							else
							{
								$this->message = '�� '.echo_sex('�����','������').' �����. �� �� '.echo_sex('������������','�������������').', ��� ���� ����� ������ ����. ������ ������ ���� ����� ������� ����� �����.<br>';         
							}   
						}
					}
					elseif ($dlit==0)
					{
						if ($this->fact['hp_p']>0 OR $this->fact['mp_p']>0 OR $this->fact['stm_p']>0)
						{
							$new_HP = min($this->char['HP_MAX'],$this->char['HP']+$this->fact['hp_p']);
							$new_MP = min($this->char['MP_MAX'],$this->char['MP']+$this->fact['mp_p']);
							$new_STM = min($this->char['STM_MAX'],$this->char['STM']+$this->fact['stm_p']);
							myquery("UPDATE game_users SET HP=$new_HP,MP=$new_MP,STM=$new_STM WHERE user_id=".$this->char['user_id']."");
							$char['HP']=$new_HP;
							$char['MP']=$new_MP;
							$char['STM']=$new_STM;
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
					}
					/*
					if ($this->fact['id']==zelye_glubin_item_id)
					{
						//����� ������
						myquery("UPDATE game_users SET HP_MAX=HP_MAXX+".$this->fact['hp_p']." WHERE user_id=".$this->char['user_id']."");
						myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=1");
						myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+24*60*60).",1)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['id']==zelye_glubin_medium_item_id)
					{
						//������� ����� ������
						myquery("UPDATE game_users SET HP_MAX=HP_MAXX+".$this->fact['hp_p']." WHERE user_id=".$this->char['user_id']."");
						myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=1");
						myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+48*60*60).",1)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['id']==zelye_glubin_big_item_id)
					{
						//������� ����� ������
						myquery("UPDATE game_users SET HP_MAX=HP_MAXX+".$this->fact['hp_p']." WHERE user_id=".$this->char['user_id']."");
						myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=1");
						myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+72*60*60).",1)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['id']==eliksir_mogushestva_item_id)
					{
						//����� ����������
						//�������� �� ������� ������������ ��������
						$check = myquery("SELECT * FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=2 AND harka='STM' AND time_end>".time()."");
						if (mysql_num_rows($check)>0)
						{
							myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=2 AND harka='STM'");
						}
						else
						{
							myquery("UPDATE game_users SET STM_MAX=STM_MAX+50 WHERE user_id=".$this->char['user_id']."");
						}
						myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STM',2,".(time()+24*60*60).",50)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['id']==eliksir_bodrosti_item_id)
					{
						//����� ��������
						myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=3");
						myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+24*60*60).",3)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['id']==eliksir_zorkosti_item_id)
					{
						//����� ��������
						myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=4");
						myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+24*60*60).",4)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['id']==eliksir_nevidimka_item_id)
					{
						//����� �����������
						myquery("DELETE FROM game_obelisk_users WHERE user_id=".$this->char['user_id']." AND type=5");
						myquery("INSERT INTO game_obelisk_users (user_id,time_end,type) VALUES (".$this->char['user_id'].",".(time()+24*60*60).",5)");
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['hp_p']>0 OR $this->fact['mp_p']>0 OR $this->fact['stm_p']>0)
					{
						$new_HP = min($this->char['HP_MAX'],$this->char['HP']+$this->fact['hp_p']);
						$new_MP = min($this->char['MP_MAX'],$this->char['MP']+$this->fact['mp_p']);
						$new_STM = min($this->char['STM_MAX'],$this->char['STM']+$this->fact['stm_p']);
						myquery("UPDATE game_users SET HP=$new_HP,MP=$new_MP,STM=$new_STM WHERE user_id=".$this->char['user_id']."");
						$char['HP']=$new_HP;
						$char['MP']=$new_MP;
						$char['STM']=$new_STM;
						$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
					}
					elseif ($this->fact['dstr']>0 OR $this->fact['dspd']>0 OR $this->fact['dpie']>0 OR $this->fact['dvit']>0 OR $this->fact['ddex']>0 OR $this->fact['dntl']>0)
					{

						$count = mysql_result(myquery("SELECT COUNT(*) FROM game_obelisk_users WHERE type=2 AND time_end>".time()." AND user_id=".$this->item['user_id']." AND harka!='STM'"),0,0);
						if ($count<3)
						{
							if ($this->fact['dstr']>0)
							{
								myquery("UPDATE game_users SET STR=STR+".$this->fact['dstr']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'STR',2,".(time()+$dlit).",".$this->fact['dstr'].")");
							} 
							if ($this->fact['dspd']>0)
							{
								myquery("UPDATE game_users SET SPD=SPD+".$this->fact['dspd']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'SPD',2,".(time()+$dlit).",".$this->fact['dspd'].")");
							} 
							if ($this->fact['dntl']>0)
							{
								myquery("UPDATE game_users SET NTL=NTL+".$this->fact['dntl']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'NTL',2,".(time()+$dlit).",".$this->fact['dntl'].")");
							} 
							if ($this->fact['dpie']>0)
							{
								myquery("UPDATE game_users SET PIE=PIE+".$this->fact['dpie']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'PIE',2,".(time()+$dlit).",".$this->fact['dpie'].")");
							} 
							if ($this->fact['dvit']>0)
							{
								myquery("UPDATE game_users SET VIT=VIT+".$this->fact['dvit']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'VIT',2,".(time()+$dlit).",".$this->fact['dvit'].")");
							} 
							if ($this->fact['ddex']>0)
							{
								myquery("UPDATE game_users SET DEX=DEX+".$this->fact['ddex']." WHERE user_id=".$this->char['user_id']."");
								myquery("INSERT INTO game_obelisk_users (user_id,harka,type,time_end,value) VALUES (".$this->char['user_id'].",'DEX',2,".(time()+$dlit).",".$this->fact['ddex'].")");
							} 
							$this->message = $this->message.'<b><font color=red>�� '.echo_sex('�����','������').' ����� "'.$this->fact['name'].'"</font></b><br>'; 
						}
						else
						{
							$this->message = '�� '.echo_sex('�����','������').' �����. �� �� '.echo_sex('������������','�������������').', ��� ���� ����� ������ ����. ������ ������ ���� ����� ������� ����� �����.<br>';         
						}                      
					}*/ 
				}
			}
		}
		//��� ����� ������������ �������� � ������� ����������� � ���� ������ ������ :-)
		elseif (($this->fact['id']==shamp_item_id)OR($this->fact['id']==hlop_item_id)OR($this->fact['id']==beer_td_item_id)OR($this->fact['id']==beer_t_item_id)OR($this->fact['id']==beer_s_item_id)OR($this->fact['id']==ell_item_id)OR($this->fact['id']==berez_item_id))
		{
			if ($this->item['user_id']==$this->char['user_id'])
			{
				if ($this->item['priznak']==0)
				{
					if ($this->fact['id']==shamp_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/shamp.gif border=0>&nbsp;[b]".echo_sex('������','�������')." ������� �����������[/b]")."','".time()."','yellow')");
					}
					elseif ($this->fact['id']==hlop_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/hlop.gif border=0>&nbsp;[b]������ ".echo_sex('���������','����������')." ���������[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==beer_td_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_temn_beer_dwarf.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ ������� ������������ ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==beer_t_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_temn_beer.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ ������� ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==beer_s_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_svet_beer.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ �������� ����[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==ell_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_ell.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ���[/b]")."','".time()."','#47F8EF')");
					}
					elseif ($this->fact['id']==berez_item_id)
					{
						myquery("INSERT INTO game_log (fromm,message,date,color) VALUES ('".$this->char['user_id']."','".iconv("Windows-1251","UTF-8//IGNORE","<img src=mag/p_berez_sok.jpg border=0>&nbsp;[b]  ".echo_sex('�����','������')." ������ ������ ���������� ����[/b]")."','".time()."','#47F8EF')");
					}
				}    
			}
		}
		$this->delete_item();
	}
	
	public function del_market($komu)
	{
		$this->move_item_to_user(0,$komu);    
	}
	
	public function sell_market($town_id,$price,$from_post=0,$post_to=0,$post_var=0,$kol_item=1)
	{
		$ar = array(0,$this->item['id']);
		if ($this->item['ref_id']==0 AND $this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $this->fact['type']<=97 AND $this->item['item_for_quest']==0)
		{
			$kol = min($kol_item, $this->max_count());
			if (/*$this->fact['type']==19 OR $this->fact['type']==21*/ $this->counted_item())
			{
				if ($this->item['count_item'] < $kol)
				{
					$kol = $this->item['count_item'];
				}
			}
			else
			{
				$kol = 1;
			}

			$tax = round($price*$this->tax_market,2);
			if ($this->char['GP']<$tax AND $from_post==0)
			{
				echo '� ���� ������������ ����� ��� ������ ������';
			}    
			elseif ($this->char['GP']<$from_post AND $from_post!=0)
			{
				echo '� ���� ������������ ����� ��� ������ �������� �������';
			}    
			elseif ($price<1 AND $from_post==0)
			{
				echo '����������� ���� - 1 ������';
			}
			elseif ($price>10000 AND $from_post==0)
			{
				echo '������������ ���� - 10 000 �����';
			} 
			elseif ($this->fact['personal']>0)
			{
				echo '������ ������� ��������� ���������';
			}
			elseif ($kol_item <= 0)
			{
				echo '�������� ���������� ��� �������';
			}
			else
			{
				$this->item['town']=$town_id;
				$this->item['item_cost']=(double)$price;
				if ($from_post==0)
				{
					$item_id = $this->move_item_to_market($tax,0,0,1,$kol);
					echo'<b><font color=#FFFF00 size=3>���� ���������� �� �������. �� '.echo_sex('��������','���������').' �� ������ ��������� ����� '.$tax.' '.pluralForm($tax,'������','������','�����').'</font></b>';
				}
				else
				{
					$item_id = $this->move_item_to_market($from_post,$post_to,$post_var,3,$kol);
					echo'<b><font color=#FFFF00 size=3>������� ������� � ��������. �� '.echo_sex('��������','���������').' �� �������� '.$from_post.' '.pluralForm($from_post,'������','������','�����').'</font></b>';
				}
				$ar[0]=$price;
				$ar[1]=$item_id;
			}
		}
		return $ar;
	}

	public function confirm_market($with_desc = 0)
	{
		if ($this->item['user_id']==$this->char['user_id'] AND $this->item['ref_id']==0 AND $this->item['used']==0 AND $this->fact['type']<=97 and $this->item['priznak']==0 AND $this->fact['personal']==0)
		{   
			echo('<form action="" method="POST">');
			echo('<table border="0" cellpadding="1"><tr><td></td></tr></table><table border="1" cellpadding="0" style="border-collapse: collapse" width="98%" bordercolor="777777" bgcolor="223344" align=center><tr><td>');
			echo('<table cellpadding="0" cellspacing="4" border="0"><tr><td valign="left"><div align="center">');
			ImageItem($this->fact['img'],0,$this->item['kleymo']);
			echo'<br><font color="#ffff00">' . $this->fact['name'] . '</font></div></td><td valign="top"><div align="left"><img src="http://'.IMG_DOMAIN.'/nav/x.gif" width="0" height="0" hspace="40" border="0"><br>';
			if ($this->fact['type']!=99) echo'���: '.type_str($this->fact['type']).'<br>';
			if ($this->fact['indx']<>0)
				if ($this->fact['type'] == 1 or $this->fact['type'] == 3 or $this->fact['type'] == 19 or $this->fact['type'] == 21) echo '����: ' . $this->fact['indx'] . '&nbsp;&plusmn;&nbsp;' . $this->fact['deviation'] . '<br>';
			if ($this->fact['type'] == 4) echo '������: ' . $this->fact['indx'] . '<br>';
			if ($this->fact['type'] == 3) echo '���-�� �������: '.$this->item['count_item'].'<br>';
            
			if (!$this->counted_item() AND $this->fact['type'] != 95 AND $this->fact['type'] != 20)
			{
				$use=$this->item['item_uselife'];
				echo '���������: '.$use.'%';
			}
            
			if (!$this->counted_item() AND $this->fact['breakdown']==1)
			{
				echo '<br />�������������: '.$this->item['item_uselife_max'].'/'.$this->fact['item_uselife_max'];
			}
			
			echo '<br />';

			if ($this->fact['weight']>0) echo'���: ' . $this->fact['weight'] . '<br><br>';

			if (!$this->counted_item() AND $this->fact['type']!=20)
			{
				echo '������� ��������:<br>';
				if ($this->fact['dstr']<>'0')  echo '���� ��: '.$this->fact['dstr'].'<br>';
				if ($this->fact['dntl']<>'0')  echo '��������� ��: '.$this->fact['dntl'].'<br>';
				if ($this->fact['dpie']<>'0')  echo '�������� ��: '.$this->fact['dpie'].'<br>';
				if ($this->fact['dvit']<>'0')  echo '������ ��: '.$this->fact['dvit'].'<br>';
				if ($this->fact['ddex']<>'0')  echo '������������ ��: '.$this->fact['ddex'].'<br>';
				if ($this->fact['dspd']<>'0')  echo '�������� ��: '.$this->fact['dspd'].'<br>';

				if ($this->fact['hp_p']<>'0')  echo '����� ��: '.$this->fact['hp_p'].'<br>';
				if ($this->fact['stm_p']<>'0')  echo '������� ��: '.$this->fact['stm_p'].'<br>';
				if ($this->fact['mp_p']<>'0')  echo '���� ��: '.$this->fact['mp_p'].'<br>';
				if ($this->fact['cc_p']<>'0')  echo '������� ����� ��: '.$this->fact['cc_p'].'<br>';
			}
			if ($this->counted_item())
			{
				echo'<br>�� ���� ����� ����� �������� �� <b>1</b> �� <b>'.($this->max_count()).'</b> ���������.<br/>';
				echo'<br>���-�� ���������:<input name="colitems" type="text" size="4" value="1"> (� ������� - '.$this->item['count_item'].' ��.)<br><br>';			
			}
			echo'<br>����:<input name="cena" type="text" size="6" value="1.00"> �������<br><br>';
			echo '<font color="#ffff00"><b><u>�� ������ ��������� ����� �� ����� �� '.echo_sex('������','������').' ������ ��������� 8% �� ���� ��������!</u></b></font><br><br>';
			if ($with_desc)
				echo '��������:<br><textarea name="opis" cols="15" rows="3"></textarea><br>';

			echo '<input name="" type="submit" value=���������>';
			echo'</div></td></tr></table>';
			echo'</td></tr><input name="see" type="hidden" value=""></table></form>';
		}
	}
	
	public function buy_market($id=0,$from_post=0)
	{
		$ar = array(0, '', '');
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->fact['kol_per_user']>0 AND $from_post==0)
		{
			$kol_inv = @mysql_result(@myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
			if ($kol_inv + $this->item['count_item'] > $this->fact['kol_per_user'])
			{
				echo '������ ������ ����� '.$this->fact['kol_per_user'].' ���������.';
				return $ar;
			}
		}
		if ($this->fact['clan_id']>0 AND $this->fact['clan_id']!=$this->char['clan_id'])
		{
			echo '���� ������� �� ��� ������ �����!';
			return $ar;
		}
		if ($this->item['priznak']==1 OR ($this->item['priznak']==3 AND $from_post>0))
		{
			if ($this->char['GP']<$this->item['item_cost'])
			{
				echo '� ���� �� ������ �����';
			}
			else
			{
				$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$this->char['user_id']." AND type=1"),0,0);
				$item_weight = $this->fact['weight'];
				if ($this->counted_item())
				{
					$item_weight = $item_weight * $this->item['count_item'];
				}
				if (($this->char['CW']+$item_weight)>$this->char['CC'] AND $prov==0)
				{
					echo '������������ ���������� ����� � ���������';
				}
				else
				{
					$this->move_item_to_user($this->item['item_cost'],$this->char['user_id']);
					$ar[0] = $this->item['item_cost'];
					if ($from_post>0)
					{
						$ar[0] = 1;
					}
					$ar[1] = $this->fact['name'];
					$ar[2] = $this->getOpis();    
				}
			}
		}
		return $ar;
	}
	
	public function take_post()
	{
		$ar = array(0,'','');
		if ($this->item['priznak']==3)
		{
			if ($this->char['user_id']!=$this->item['post_to'])
			{
				echo '�� ������� ����� ������ �������. �� ��!';
			}
			else
			{
				$prov=mysql_result(myquery("select count(*) from game_wm where user_id=".$this->char['user_id']." AND type=1"),0,0);
				if (($this->char['CW']+$this->fact['weight'])>$this->char['CC'] AND $prov==0)
				{
					echo '������������ ���������� ����� � ���������';
				}
				else
				{
					$this->move_item_to_user(0,$this->char['user_id']);
					$ar[0] = 1;
					$ar[1] = $this->fact['name'];
					$ar[2] = $this->getOpis();    
				}
			}
		}
		return $ar;
	}
	
	public function ImageUnidentItem()
	{
		switch ($this->fact['type'])
		{
			case 1:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/sword3.gif">';};break;
			case 2:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/ring3.gif">';};break;
			case 3:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/art3.gif">';};break;
			case 4:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/shield3.gif">';};break;
			case 5:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/armour3.gif">';};break;
			case 6:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/hemlet3.gif">';};break;
			case 7:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/magic3.gif">';};break;
			case 8:{echo '<img src="http://'.IMG_DOMAIN.'/item/unident/belt3.gif">';};break;
		}
	}

	public function up($id=0,$used)
	{
		global $user_time;
		global $_SESSION;
		$_SESSION['error_inv']='';
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($used==0)
		{
			switch ($this->fact['type'])
			{
				case 1: $used=1; break;
				case 2: 
				{
					$used=2; 
					if (isset($_GET['slot']) AND $_GET['slot']==2 AND $this->char['clevel']>=3)
					{
						$used = 19;
					}
					if (isset($_GET['slot']) AND $_GET['slot']==3 AND $this->char['clevel']>=7)
					{
						$used = 20;
					}
				}
				break;
				case 3: $used=3; break;
				case 4: $used=4; break;
				case 5: $used=5; break;
				case 6: $used=6; break;
				case 7: $used=7; break;
				case 8: $used=8; break;
				case 9: $used=9; break;
				case 10: $used=10; break;
				case 11: $used=11; break;
				case 12:
				{                     
					$used=12; 
					if (isset($_GET['slot']) AND $_GET['slot']==2)
					{
						$used = 13;
					}
					if (isset($_GET['slot']) AND $_GET['slot']==3)
					{
						$used = 14;
					}
				}
				break;
				case 13:
				{                     
					$used=12; 
					if (isset($_GET['slot']) AND $_GET['slot']==2)
					{
						$used = 13;
					}
					if (isset($_GET['slot']) AND $_GET['slot']==3)
					{
						$used = 14;
					}
				}
				break;
				case 14: $used=17; break;
				case 15: $used=18; break;
				case 16: 
				{
					$used=15; 
					if (isset($_GET['slot']) AND $_GET['slot']==2)
					{
						$used = 16;
					}
				}
				break;
				case 18: $used=4; break;
				case 18: $used=4; break;
				case 23: $used=22; break;
				case 24: $used=21; break;
			}
		}
		if ($this->fact['can_up']==1 AND $this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']==0 AND $used>0 AND (!$this->counted_item() OR $this->fact['type']==12 OR $this->fact['type']==13))
		{
			if (getFunc($this->item['user_id'])==2 and $this->fact['type']==24 and mysql_num_rows(myquery("Select * From game_items Where user_id=".$this->item['user_id']." and priznak=0 and used=21 and item_id=".$this->item['item_id'].""))==0)
			{
				return;
			}
			else
			{
				if ($this->item['ref_id']!=0)
				{
					$_SESSION['error_inv']='error_ident';
					setLocation("act.php?func=inv&error_ident");
					return;
				}
				if ($this->char['delay']>$user_time)
				{
					$_SESSION['error_inv']='reason=delay';
					setLocation("act.php?func=inv&reason=delay");
					return;
				}
				if ($this->item['item_uselife']==0 AND $this->fact['type']!=12 AND $this->fact['type']!=13)
				{
					$_SESSION['error_inv']='error_broken';
					setLocation("act.php?func=inv&error_broken&reason=delay");
					return;
				}
				$check = $this->check_up();
				if ($check!=1)
				{
					$_SESSION['error_inv']='error_stat';
					$_SESSION['error_stat']=$check;
					setLocation("act.php?func=inv&error_stat");
					return;
				}
				$check_used = $this->check_used($used);
				if ($check_used!=1)
				{
					$_SESSION['error_inv']='error_broken';
					setLocation("act.php?func=inv&error_broken&reason=delay");
					return;
				}
				$check = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND priznak=0 AND used=$used");
				if (mysql_num_rows($check))
				{
					list($used_item) = mysql_fetch_array($check);
					$ItemUsed = new Item($used_item);
					$ItemUsed->item_down();
				}
				$this->item_up($used);
			}
		}
        else
        {
            /*
            if ($this->item['priznak']!=0)
            {
                echo '<script>alert("� ���� ��� ����� ��������!")</script>';
            }
            if ($this->item['user_id']!=$this->char['user_id'])
            {
                echo '<script>alert("��� �� ���� �������!")</script>';
            }
            if ($this->item['used']!=0)
            {
                echo '<script>alert("������� ������ ���� ����!")</script>';
            }
            if ($used<=0)
            {
                echo '<script>alert("���������� ���� �������!")</script>';
            }
            if ($this->counted_item() AND $this->fact['type']<>12 AND $this->fact['type']<>13)
            {
                echo '<script>alert("�������������� �������!")</script>';
            }
            */
        }
	}
	
	public function down($id=0)
	{
		if ($id>0)
		{
			$this->init_item($id);
		}
		if ($this->item['priznak']==0 AND $this->item['user_id']==$this->char['user_id'] AND $this->item['used']!=0 and (getFunc($this->item['user_id'])!=2 or $this->fact['type']!=24 or $this->item['item_uselife']<=0))
		{
			$this->item_down();
		}
	}
	
	public function check_up($id=0)
	{
		$ret_str = '';
		if ($id>0)
		{
			$this->init_item($id);
		}

		if ($this->item['item_uselife']<=0 && $this->fact['type'] != 13)
		{
			$ret_str.='<br />������� ������.'; 
		}

		if ($this->fact['can_up']==0 OR ($this->counted_item() AND $this->fact['type']!=12 AND $this->fact['type']!=13))
		{
			$ret_str.='<br />������� ������ �����';
		}
		if ($this->fact['oclevel']!=0)
		{
			if ($this->char['clevel']<$this->fact['oclevel'])
			{
				$ret_str.='<br />���� ������� ������: '.$this->fact['oclevel'];
			}
		}
		if ($this->fact['race']!=0)
		{
			if ($this->char['race']!=$this->fact['race'])
			{
				$ret_str.='<br />���� ������� �� ��� ����� ����';
			}
		}
		if ($this->fact['ostr']!=0)
		{
			if ($this->char['STR']<$this->fact['ostr'])
			{
				$ret_str.='<br />���� ���� ������: '.$this->fact['ostr'];
			}
		}
		if ($this->fact['ontl']!=0)
		{
			if ($this->char['NTL']<$this->fact['ontl'])
			{
				$ret_str.='<br />���� ��������� ������: '.$this->fact['ontl'];
			}
		}
		if ($this->fact['opie']!=0)
		{
			if ($this->char['PIE']<$this->fact['opie'])
			{
				$ret_str.='<br />���� �������� ������: '.$this->fact['opie'];
			}
		}
		if ($this->fact['ovit']!=0)
		{
			if ($this->char['VIT']<$this->fact['ovit'])
			{
				$ret_str.='<br />���� ������ ������: '.$this->fact['ovit'];
			}
		}
		if ($this->fact['odex']!=0)
		{
			if ($this->char['DEX']<$this->fact['odex'])
			{
				$ret_str.='<br />���� ������������ ������: '.$this->fact['odex'];
			}
		}
		if ($this->fact['ospd']!=0)
		{
			if ($this->char['SPD']<$this->fact['ospd'])
			{
				$ret_str.='<br />���� �������� ������: '.$this->fact['ospd'];
			}
		}
		if ($this->fact['olucky']!=0)
		{
			if ($this->char['lucky']<$this->fact['olucky'])
			{
				$ret_str.='<br />���� ����� ������: '.$this->fact['olucky'];
			}
		}
		if ($this->fact['clan_id']!=0)
		{
			if ($this->char['clan_id']!=$this->fact['clan_id'])
			{
				$ret_str.='<br />���� ������� �� ��� ������ �����';
			}
		}
		if ($this->fact['type']==1 OR $this->fact['type']==18)
		{
			if ($this->fact['type_weapon_need']>0)
			{
				switch ($this->fact['type_weapon'])
				{
					case 0:
					{
						if ($this->char['MS_WEAPON']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � �������� �������� ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
					case 1:
					{
						if ($this->char['MS_KULAK']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � ������� ��������� ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
					case 2:
					{
						if ($this->char['MS_LUK']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � ������� ����������� ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
					case 3:
					{
						if ($this->char['MS_SWORD']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � ������� �������� ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
					case 4:
					{
						if ($this->char['MS_AXE']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � ������� ��������� ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
					case 5:
					{
						if ($this->char['MS_SPEAR']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � ������� �������� ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
					case 6:
					{
						if ($this->char['MS_THROW']<$this->fact['type_weapon_need'])
						{
							$ret_str.='<br />���� ������������� � ������� ������������ ������ ������: '.$this->fact['type_weapon_need'];        
						}
					}
					break;
				}
			}
			if ($this->fact['in_two_hands']==1)
			{
				$used_shield = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=".$this->char['user_id']." AND used=4 AND priznak=0"),0,0);
				if ($used_shield>0)
				{
					$ret_str.='<br />������ ����� ��������� ������. �������� ������ ����.';
				}
			}
		}
		if ($this->fact['type']==4)
		{
			$used_weapon = myquery("SELECT game_items_factsheet.in_two_hands FROM game_items,game_items_factsheet WHERE game_items.user_id=".$this->char['user_id']." AND game_items.used=1 AND game_items.priznak=0 AND game_items.item_id=game_items_factsheet.id AND game_items_factsheet.in_two_hands=1");
			if (mysql_num_rows($used_weapon)>0)
			{
				$ret_str.='<br />������ ����� ���. ����� ��������� ������.';
			}
		}
		if ($ret_str=='')
		{
			return 1;
		}
		else
		{
			return $ret_str;
		}
	}

	private function item_up($used)
	{
		global $user_time;
		$add_str = $add_pie = $add_ntl = $add_vit = $add_spd = $add_dex = $add_lucky = $add_cc = $add_hp = $add_mp = $add_stm = 0;
		if ($this->fact['type'] != 23)
		{
			//�������� ��������� ���������
			$sel_compl = myquery("SELECT complect_id FROM game_items_complect WHERE item_id=".$this->fact['id']."");
			while(list($complect_id) = mysql_fetch_array($sel_compl))
			{
				$est_complect = 0;
				$kol_item_in_complect = 0;
				$kol_item_complect_used = 0;
				$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");
				while (list($item_id) = mysql_fetch_array($sel_check))
				{
					$kol_item_in_complect++;
					if ($item_id != $this->fact['id'])
					{
						$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
						if (!mysql_num_rows($check_used))
						{
							$est_complect = 2;
							break;
						}
						else
							$kol_item_complect_used++;
					}
					else
					{
						$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
						if (mysql_num_rows($check_used))
								$kol_item_complect_used++;
					}
				}
				if (($kol_item_in_complect-$kol_item_complect_used)==1 AND $est_complect!=2)
					$est_complect = 1;

				$is_on_user = mysql_fetch_array(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=$complect_id AND user_id=".$this->char['user_id'].";"));

				if ($est_complect == 1 && !$is_on_user[0])
				{
					myquery("INSERT INTO `game_items` (`user_id`, `item_id`, `ref_id`, `item_uselife`, `item_cost`, `map_name`, `map_xpos`, `map_ypos`, `item_for_quest`, `town`, `sell_time`, `priznak`, `post_to`, `post_var`, `used`, `item_uselife_max`, `for_town`, `shop_from`, `kleymo`, `kleymo_nomer`, `kleymo_id`, `count_item`) 
					VALUES (".$this->char['user_id'].", '$complect_id', '0', '100', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '22', '100', '0', '0', '0', '0', '0', '1');");
					
					$compl = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=$complect_id"));
					$add_str += $compl['dstr'];
					$add_pie += $compl['dpie'];
					$add_ntl += $compl['dntl'];
					$add_vit += $compl['dvit'];
					$add_dex += $compl['ddex'];
					$add_spd += $compl['dspd'];
					$add_lucky += $compl['dlucky'];
					$add_cc += $compl['cc_p'];
					$add_hp += $compl['hp_p'];
					$add_mp += $compl['mp_p'];
					$add_stm += $compl['stm_p'];
				}
			}
		}
		else
		{
			// ����� �������� ��� ����?
			$is_on_user = mysql_fetch_array(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=".$this->fact['id']." AND user_id=".$this->char['user_id'].";"));
			if ($is_on_user[0])
				return;
			//���������� � ����� ��������� ����������?
			$complect_id = $this->fact['id'];
			$est_complect = 0;
			$kol_item_in_complect = 0;
			$kol_item_complect_used = 0;
			$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");
			while (list($item_id) = mysql_fetch_array($sel_check))
			{
				$kol_item_in_complect++;
				if ($item_id != $this->fact['id'])
				{
					$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
					if (!mysql_num_rows($check_used))
					{
						$est_complect = 2;
						break;
					}
					else
						$kol_item_complect_used++;
				}
				else
				{
					$check_used = myquery("SELECT id FROM game_items WHERE priznak=0 AND used>0 AND user_id=".$this->char['user_id']." AND item_id=$item_id");
					if (mysql_num_rows($check_used))
							$kol_item_complect_used++;
				}
			}
			if (($kol_item_in_complect-$kol_item_complect_used)==1 AND $est_complect!=2)
				$est_complect = 1;

			if ($est_complect != 1)
				return;
		}

		// �� ����� � �� ������
		if ($this->fact['type']!=12 AND $this->fact['type']!=13)
		{
			myquery("UPDATE game_users SET
			STR=STR+".$this->fact['dstr']."+".$add_str.", 
			PIE=PIE+".$this->fact['dpie']."+".$add_pie.", 
			NTL=NTL+".$this->fact['dntl']."+".$add_ntl.", 
			VIT=VIT+".$this->fact['dvit']."+".$add_vit.", 
			DEX=DEX+".$this->fact['ddex']."+".$add_dex.", 
			SPD=SPD+".$this->fact['dspd']."+".$add_spd.", 
			STR_MAX=STR_MAX+".$this->fact['dstr']."+".$add_str.", 
			PIE_MAX=PIE_MAX+".$this->fact['dpie']."+".$add_pie.", 
			NTL_MAX=NTL_MAX+".$this->fact['dntl']."+".$add_ntl.", 
			VIT_MAX=VIT_MAX+".$this->fact['dvit']."+".$add_vit.", 
			DEX_MAX=DEX_MAX+".$this->fact['ddex']."+".$add_dex.", 
			SPD_MAX=SPD_MAX+".$this->fact['dspd']."+".$add_spd.",
			lucky=lucky+".$this->fact['dlucky']."+".$add_lucky.",
			lucky_max=lucky_max+".$this->fact['dlucky']."+".$add_lucky.", 
			CC=CC+".$this->fact['cc_p']."+".$add_cc.", 
			HP_MAX=HP_MAX+".$this->fact['hp_p']."+".$add_hp.",
			HP_MAXX=HP_MAXX+".$this->fact['hp_p']."+".$add_hp.", 
			MP_MAX=MP_MAX+".$this->fact['mp_p']."+".$add_mp.", 
			STM_MAX=STM_MAX+".$this->fact['stm_p']."+".$add_stm.",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=".$this->item['user_id']."");

			myquery("
			UPDATE game_users_archive SET 
			STR=STR+".$this->fact['dstr']."+".$add_str.", 
			PIE=PIE+".$this->fact['dpie']."+".$add_pie.", 
			NTL=NTL+".$this->fact['dntl']."+".$add_ntl.", 
			VIT=VIT+".$this->fact['dvit']."+".$add_vit.", 
			DEX=DEX+".$this->fact['ddex']."+".$add_dex.", 
			SPD=SPD+".$this->fact['dspd']."+".$add_spd.", 
			STR_MAX=STR_MAX+".$this->fact['dstr']."+".$add_str.", 
			PIE_MAX=PIE_MAX+".$this->fact['dpie']."+".$add_pie.", 
			NTL_MAX=NTL_MAX+".$this->fact['dntl']."+".$add_ntl.", 
			VIT_MAX=VIT_MAX+".$this->fact['dvit']."+".$add_vit.", 
			DEX_MAX=DEX_MAX+".$this->fact['ddex']."+".$add_dex.", 
			SPD_MAX=SPD_MAX+".$this->fact['dspd']."+".$add_spd.",
			lucky=lucky+".$this->fact['dlucky']."+".$add_lucky.",
			lucky_max=lucky_max+".$this->fact['dlucky']."+".$add_lucky.", 
			CC=CC+".$this->fact['cc_p']."+".$add_cc.", 
			HP_MAX=HP_MAX+".$this->fact['hp_p']."+".$add_hp.",
			HP_MAXX=HP_MAXX+".$this->fact['hp_p']."+".$add_hp.", 
			MP_MAX=MP_MAX+".$this->fact['mp_p']."+".$add_mp.", 
			STM_MAX=STM_MAX+".$this->fact['stm_p']."+".$add_stm.",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=".$this->item['user_id']."");
		}
		//set_delay_id($this->item['user_id'], $user_time + $this->fact['weight']); //������� �������� ��������

		//��� ��������� � ������� ���� ���������. ���� ���������� �� > 1 ����� ���� ������ ���������� �������
		if (($this->fact['type']==12 OR $this->fact['type']==13) AND ($this->item['count_item']>1))
		{
			myquery("UPDATE game_items SET count_item=count_item-1 WHERE id=".$this->item['id']."");
			$up = myquery("INSERT INTO game_items SET
                            user_id = ".$this->item['user_id'].",
                            ref_id='0',
                            item_id=".$this->fact['id'].",
                            item_uselife=".$this->item['item_uselife'].",
                            item_uselife_max=".$this->item['item_uselife_max'].",
                            item_cost=".$this->item['item_cost'].",
                            priznak=0,
                            used=$used,
                            item_for_quest=0,
                            count_item=1");
			$insert_id = mysql_insert_id();
		}
		else
		{
			myquery("UPDATE game_items SET used=$used WHERE id=".$this->item['id']."");
		}
	}
	
	private function item_down()
	{
		global $user_time;
		//�������� ������ ���� �� ���������
		$add_str = $add_pie = $add_ntl = $add_vit = $add_spd = $add_dex = $add_lucky = $add_cc = $add_hp = $add_mp = $add_stm = 0;
		if ($this->fact['type'] != 23)
		{
			$sel_compl = myquery("SELECT complect_id FROM game_items_complect WHERE item_id=".$this->fact['id']."");
			while(list($complect_id) = mysql_fetch_array($sel_compl))
			{
				$complect_down = 0;
				$sel_check = myquery("SELECT item_id FROM game_items_complect WHERE complect_id=$complect_id");
				$kol_in_complect = 0;
				$kol_used_complect = 0;
				while (list($item_id) = mysql_fetch_array($sel_check))
				{
					$kol_in_complect++;
					if ($this->fact['id']==$item_id)
					{
						$checkused = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND used>0 AND item_id=$item_id AND priznak=0 AND id<>".$this->item['id']."");
						if (mysql_num_rows($checkused))
						{
							$kol_used_complect++;
						}                    
					}
						else
					{
						$checkused = myquery("SELECT id FROM game_items WHERE user_id=".$this->char['user_id']." AND used>0 AND item_id=$item_id AND priznak=0");
						if (mysql_num_rows($checkused))
							$kol_used_complect++;
					}
				}
				$is_on_user = mysql_fetch_array(myquery("SELECT COUNT(*) FROM game_items WHERE item_id=$complect_id AND user_id=".$this->char['user_id'].";"));

				if ($kol_in_complect-$kol_used_complect==1 && $is_on_user[0])
				{
					myquery("DELETE FROM game_items WHERE item_id=$complect_id AND user_id=".$this->char['user_id']." LIMIT 1;");
					$compl = mysql_fetch_array(myquery("SELECT * FROM game_items_factsheet WHERE id=$complect_id"));
					$add_str += $compl['dstr'];
					$add_pie += $compl['dpie'];
					$add_ntl += $compl['dntl'];
					$add_vit += $compl['dvit'];
					$add_dex += $compl['ddex'];
					$add_spd += $compl['dspd'];
					$add_lucky += $compl['dlucky'];
					$add_cc += $compl['cc_p'];
					$add_hp += $compl['hp_p'];
					$add_mp += $compl['mp_p'];
					$add_stm += $compl['stm_p'];
				}
			}
		}
		else
			myquery("DELETE FROM game_items WHERE item_id=".$this->fact['id']." AND user_id=".$this->char['user_id']." LIMIT 1;");

		//set_delay_id($this->item['user_id'],$user_time + $this->fact['weight']); //������� �������� ��������

		if ($this->fact['type']!=12 AND $this->fact['type']!=13)
		{
			myquery("
			UPDATE game_users SET 
			STR=STR-".$this->fact['dstr']."-'$add_str', 
			PIE=PIE-".$this->fact['dpie']."-'$add_pie', 
			NTL=NTL-".$this->fact['dntl']."-'$add_ntl', 
			VIT=VIT-".$this->fact['dvit']."-'$add_vit', 
			DEX=DEX-".$this->fact['ddex']."-'$add_dex', 
			SPD=SPD-".$this->fact['dspd']."-'$add_spd', 
			STR_MAX=STR_MAX-".$this->fact['dstr']."-'$add_str', 
			PIE_MAX=PIE_MAX-".$this->fact['dpie']."-'$add_pie', 
			NTL_MAX=NTL_MAX-".$this->fact['dntl']."-'$add_ntl', 
			VIT_MAX=VIT_MAX-".$this->fact['dvit']."-'$add_vit', 
			DEX_MAX=DEX_MAX-".$this->fact['ddex']."-'$add_dex', 
			SPD_MAX=SPD_MAX-".$this->fact['dspd']."-'$add_spd', 
			lucky=lucky-".$this->fact['dlucky']."-'$add_lucky',
			lucky_max=lucky_max-".$this->fact['dlucky']."-'$add_lucky',
			CC=CC-".$this->fact['cc_p']."-'$add_cc', 
			HP_MAX=HP_MAX-".$this->fact['hp_p']."-'$add_hp', 
			HP_MAXX=HP_MAXX-".$this->fact['hp_p']."-'$add_hp', 
			MP_MAX=MP_MAX-".$this->fact['mp_p']."-'$add_mp', 
			STM_MAX=STM_MAX-".$this->fact['stm_p']."-'$add_stm',
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=".$this->item['user_id']."");

			myquery("
			UPDATE combat_users SET 
			STR=STR-".$this->fact['dstr']."-'$add_str', 
			PIE=PIE-".$this->fact['dpie']."-'$add_pie', 
			NTL=NTL-".$this->fact['dntl']."-'$add_ntl', 
			VIT=VIT-".$this->fact['dvit']."-'$add_vit', 
			DEX=DEX-".$this->fact['ddex']."-'$add_dex', 
			SPD=SPD-".$this->fact['dspd']."-'$add_spd', 
			lucky=lucky-".$this->fact['dlucky']."-'$add_lucky',
			HP_MAX=HP_MAX-".$this->fact['hp_p'].", 
			MP_MAX=MP_MAX-".$this->fact['mp_p'].", 
			STM_MAX=STM_MAX-".$this->fact['stm_p'].",
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=".$this->item['user_id']."");

			myquery("
			UPDATE game_users_archive SET 
			STR=STR-".$this->fact['dstr']."-'$add_str', 
			PIE=PIE-".$this->fact['dpie']."-'$add_pie', 
			NTL=NTL-".$this->fact['dntl']."-'$add_ntl', 
			VIT=VIT-".$this->fact['dvit']."-'$add_vit', 
			DEX=DEX-".$this->fact['ddex']."-'$add_dex', 
			SPD=SPD-".$this->fact['dspd']."-'$add_spd', 
			STR_MAX=STR_MAX-".$this->fact['dstr']."-'$add_str', 
			PIE_MAX=PIE_MAX-".$this->fact['dpie']."-'$add_pie', 
			NTL_MAX=NTL_MAX-".$this->fact['dntl']."-'$add_ntl', 
			VIT_MAX=VIT_MAX-".$this->fact['dvit']."-'$add_vit', 
			DEX_MAX=DEX_MAX-".$this->fact['ddex']."-'$add_dex', 
			SPD_MAX=SPD_MAX-".$this->fact['dspd']."-'$add_spd', 
			lucky=lucky-".$this->fact['dlucky']."-'$add_lucky',
			lucky_max=lucky_max-".$this->fact['dlucky']."-'$add_lucky',
			CC=CC-".$this->fact['cc_p']."-'$add_cc', 
			HP_MAX=HP_MAX-".$this->fact['hp_p']."-'$add_hp', 
			HP_MAXX=HP_MAXX-".$this->fact['hp_p']."-'$add_hp', 
			MP_MAX=MP_MAX-".$this->fact['mp_p']."-'$add_mp', 
			STM_MAX=STM_MAX-".$this->fact['stm_p']."-'$add_stm',
			STM=LEAST(STM,STM_MAX), MP=LEAST(MP,MP_MAX), HP=LEAST(HP,HP_MAX)
			WHERE user_id=".$this->item['user_id']."");
		}
		//��� ������� � ��������� �������� ���� ������ ���������� �������
		if ($this->fact['type']==12 OR $this->fact['type']==13)
		{
			$sel_having = myquery("SELECT id FROM game_items WHERE user_id=".$this->item['user_id']." AND used=0 AND priznak=0 AND item_id=".$this->item['item_id']."");
			if ($sel_having!=false AND mysql_num_rows($sel_having))
			{
				list($item_having_id) = mysql_fetch_array($sel_having);
				myquery("UPDATE game_items SET count_item=count_item+".$this->item['count_item']." WHERE id=$item_having_id");
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");    
			}
			else
			{
				myquery("UPDATE game_items SET used=0 WHERE id=".$this->item['id']."");  
			}
		}
		else
		{
			myquery("UPDATE game_items SET used=0 WHERE id=".$this->item['id']."");
		}
	}
	
	private function check_used($used)
	{
		switch ($this->fact['type'])
		{
			case 1:    //������
				if ($used!=1 AND $used!=4) return 0;
			break;
			case 2:    //������
				if ($used!=2 AND $used!=19 AND $used!=20) return 0;
			break;
			case 3:    //��������
				if ($used!=3 AND $used!=17) return 0;
			break;
			case 4:    //���
				if ($used!=4) return 0;
			break;
			case 5:    //������
				if ($used!=5) return 0;
			break;
			case 6:    //����
				if ($used!=6) return 0;
			break;
			case 7:    //�����
				if ($used!=7) return 0;
			break;
			case 8:    //����
				if ($used!=8) return 0;
			break;
			case 9:    //��������
				if ($used!=9) return 0;
			break;
			case 10:   //��������
				if ($used!=10) return 0;
			break;
			case 11:   //�����
				if ($used!=11) return 0;
			break;
			case 12:   //������
				if ($used!=12 AND $used!=13 AND $used!=14) return 0;
			break;
			case 13:   //�������� 
				if ($used!=12 AND $used!=13 AND $used!=14) return 0;
			break;
			case 14:   //������ 
				if ($used!=17) return 0;
			break;
			case 15:   //������ 
				if ($used!=18) return 0;
			break;
			case 16:   //��������� 
				if ($used!=15 AND $used!=16) return 0;
			break;
			case 17:   //��������� 
				if ($used!=4) return 0;
			break;
			case 18:   //���� 
				if ($used!=4) return 0;
			break;
			case 19:   //�����.������ 
				return 0;
			break;
			case 20:   //����� ��������� 
				return 0;
			break;
			case 21:   //������ 
				return 0;
			break;
			case 24:   //����������
				if ($used!=21) return 0;
			break;
			default:
				return 0;
			break;
		}
		return 1;
	}
	
	public function move_item_to_user($cost = 0, $komu, $kol_item = 1)
	{
		if (!$this->counted_item())
		{
			myquery("UPDATE game_items SET town=0,map_xpos=0,map_ypos=0,map_name=0,post_to=0,post_var=0,sell_time=0,used=0,item_cost=0,priznak=0,user_id=".$komu." WHERE id=".$this->item['id']."");
		}
		else
		{
			$check = myquery("SELECT id FROM game_items WHERE user_id=".$komu." AND item_id=".$this->fact['id']." AND priznak=0 AND used=0 and ref_id=0");
			if (mysql_num_rows($check)>0)
			{
				if ($this->getItem('priznak')==4 and $this->getItem('type')==13)
				{
					myquery("UPDATE game_items SET count_item=count_item+$kol_item WHERE user_id=".$komu." AND item_id=".$this->fact['id']." AND priznak=0 AND used=0");
				}
				else
				{
					myquery("UPDATE game_items SET count_item=count_item+".$this->item['count_item']." WHERE user_id=".$komu." AND item_id=".$this->fact['id']." AND priznak=0 AND used=0");
				}
				if ($this->getItem('priznak')!=4 or $this->item['count_item']==$kol_item)
				{
					myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				}
				else
				{
					myquery("Update game_items Set count_item=count_item-$kol_item WHERE id=".$this->item['id']."");
				}
			}
			else
			{
				if ($this->getItem('priznak')!=4 or $this->item['count_item']==$kol_item)
				{
					myquery("UPDATE game_items SET town=0,map_xpos=0,map_ypos=0,map_name=0,post_to=0,post_var=0,sell_time=0,used=0,item_cost=0,priznak=0,user_id=".$komu." WHERE id=".$this->item['id']."");
				}
				else
				{
					myquery("Insert Into game_items (user_id, item_id, count_item) Values ($komu, ".$this->item['item_id'].", $kol_item)");
					myquery("Update game_items Set count_item=count_item-$kol_item WHERE id=".$this->item['id']."");
				}
			}
		}
		$weight_item = $this->fact['weight'];
		if ($this->counted_item())
		{
			if ($this->getItem('priznak')!=4) $kol_item=$this->item['count_item'];
			$weight_item = $weight_item*$kol_item;
		}
		if ($cost>0)
		{
			myquery("UPDATE game_users SET GP=GP-$cost,CW=CW-".($this->item['item_cost']*money_weight)."+".$weight_item." WHERE user_id=".$komu."");
			myquery("UPDATE game_users_archive SET GP=GP-$cost,CW=CW-".($this->item['item_cost']*money_weight)."+".$weight_item." WHERE user_id=".$komu."");
			set_delay_plus_id($komu,$weight_item);
			setGP($komu,-$cost,12);
			myquery("UPDATE game_users SET GP=GP+$cost,CW=CW+".($this->item['item_cost']*money_weight)." WHERE user_id=".$this->item['user_id']."");
			myquery("UPDATE game_users_archive SET GP=GP+$cost,CW=CW+".($this->item['item_cost']*money_weight)." WHERE user_id=".$this->item['user_id']."");
			setGP($this->item['user_id'],$cost,13);
		}
		else
		{
			myquery("UPDATE game_users SET CW=CW+".$weight_item." WHERE user_id=".$komu."");
			myquery("UPDATE game_users_archive SET CW=CW+".$weight_item." WHERE user_id=".$komu."");
			set_delay_plus_id($komu,$weight_item);
		}
	}
	
	public function move_item_to_market($tax = 0, $post_to = 0,$post_var = 0,$priznak = 1, $kol_item = 1)
	{
		$item_id = $this->item['id'];
		if ($this->counted_item() AND $this->item['count_item']>$kol_item)
		{
			$item_id=0;
			if ($priznak==4 and $this->fact['type']==13)
			{
				$check=myquery("Select * From game_items Where user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and priznak=4");
				if (mysql_num_rows($check)>0) list($item_id)=mysql_fetch_array($check);
			}
			if ($item_id==0)
			{
				myquery("INSERT INTO game_items SET town=".$this->item['town'].",map_xpos=0,map_ypos=0,map_name=0,post_to=$post_to,post_var=$post_var,sell_time=".time().",used=0,item_cost=".$this->item['item_cost'].",priznak=$priznak,user_id=".$this->char['user_id'].",count_item=$kol_item,item_id=".$this->fact['id']."");
				$item_id = mysql_insert_id();
			}
			else
			{
				myquery("Update game_items Set count_item=count_item+$kol_item Where user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and priznak=4");	
			}
			myquery("UPDATE game_items SET count_item=count_item-$kol_item WHERE id=".$this->item['id']."");
		}
		else
		{
			if ($priznak==4 and $this->fact['type']==13 and mysql_num_rows(myquery("Select * From game_items Where user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and priznak=4"))>0)
			{
				myquery("Delete From game_items WHERE id=".$this->item['id']."");
				myquery("Update game_items Set count_item=count_item+$kol_item Where user_id=".$this->item['user_id']." and item_id=".$this->item['item_id']." and priznak=4");	
			}
			else
			{
				myquery("UPDATE game_items SET town=".$this->item['town'].",map_xpos=0,map_ypos=0,map_name=0,post_to=$post_to,post_var=$post_var,sell_time=".time().",used=0,item_cost=".$this->item['item_cost'].",priznak=$priznak,user_id=".$this->char['user_id']." WHERE id=".$this->item['id']."");
			}
		}
		$weight_item = $this->fact['weight'];
		if ($this->counted_item())
		{
			$weight_item = $weight_item*$kol_item;
		}
		myquery("UPDATE game_users SET GP=GP-$tax,CW=CW-".$weight_item."-".($tax*money_weight)." WHERE user_id=".$this->char['user_id']."");
		set_delay_plus_id($this->char['user_id'],$weight_item);
		setGP($this->char['user_id'],-$tax,14);
		return $item_id;
	}
	
	private function delete_item($all=0)
	{
		switch ($this->item['priznak'])
		{
			case 0:
			{
				if ($this->item['used']>0 AND $this->item['used']!=12 AND $this->item['used']!=13 AND $this->item['used']!=14)
				{
					$this->item_down();
				}    
				if ($this->counted_item() AND $this->item['count_item']>1 AND $all==0)
				{
					myquery("UPDATE game_items SET count_item=count_item-1 WHERE id=".$this->item['id']."");
				}
				else
				{
					myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
					$check=myquery("Select * FROM quest_constructor WHERE item_id=".$this->item['id']."");
					if (mysql_num_rows($check)>0)
					{
						myquery("DELETE FROM quest_constructor WHERE item_id=".$this->item['id']."");
						myquery("DELETE FROM game_items_factsheet WHERE id=".$this->item['item_id']."");
					}
				}
				if($this->fact['type']!=95)
				{
					$weight = $this->fact['weight'];
					if ($all!=0 AND $this->counted_item() AND $this->item['count_item']>1) $weight = $weight*$this->item['count_item'];
					myquery("UPDATE game_users SET CW=CW-".$this->fact['weight']." WHERE user_id=".$this->item['user_id']."");
					myquery("UPDATE game_users_archive SET CW=CW-".$this->fact['weight']." WHERE user_id=".$this->item['user_id']."");
				}
				else
				{
					myquery("UPDATE game_users SET CW=CW-".$this->item['item_uselife']." WHERE user_id=".$this->item['user_id']."");
					myquery("UPDATE game_users_archive SET CW=CW-".$this->item['item_uselife']." WHERE user_id=".$this->item['user_id']."");
				}
			}
			break;
			default:
				myquery("DELETE FROM game_items WHERE id=".$this->item['id']."");
				$check=myquery("Select * FROM quest_constructor WHERE item_id=".$this->item['id']."");
				if (mysql_num_rows($check)>0)
				{
					myquery("DELETE FROM quest_constructor WHERE item_id=".$this->item['id']."");
					myquery("DELETE FROM game_items_factsheet WHERE id=".$this->item['item_id']."");
				}
			break;
		}
	}
	
	public function admindelete($all=0)
	{
		$this->delete_item($all);
	}
	
	public function add_user($id,$user_id_add,$check_weight=0,$item_for_quest=0,$check_count=0)
	{
		$ar = array(0);
		if ($id>0)
		{
			$this->init_fact($id);
			if ($check_weight!=0)
			{
				$sel = myquery("SELECT CW,CC FROM game_users WHERE user_id=$user_id_add");
				if (!mysql_num_rows($sel)) $sel = myquery("SELECT CW,CC FROM game_users_archive WHERE user_id=$user_id_add");
				list($user_CW,$user_CC) = mysql_fetch_array($sel); 
				$prov = mysql_result(myquery("SELECT COUNT(*) FROM game_wm WHERE user_id=$user_id_add AND type=1"),0,0);
				if ($prov==0 AND ($user_CC-$user_CW)<$this->fact['weight'])
				{
					echo '������������ ���������� ����� � ��������� ��� ���������� ��������: '.$this->fact['name'].'!';
					return $ar;
				}
			}

			if ($check_count!=0)
			{
				$kol_inv = mysql_result(myquery("SELECT SUM(`count_item`) FROM game_items WHERE user_id=".$this->char['user_id']." AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
				if (($this->fact['kol_per_user'] != 0) && ($kol_inv >= $this->fact['kol_per_user']))
				{
					echo '������ ������ � ����� ����� '.$this->fact['kol_per_user'].' ���������: '.$this->fact['name'].'!';
					return $ar;
				}
/*				$sel = myquery("SELECT CW,CC FROM game_users WHERE user_id=$user_id_add");
				if (!mysql_num_rows($sel)) $sel = myquery("SELECT CW,CC FROM game_users_archive WHERE user_id=$user_id_add");
				list($user_CW,$user_CC) = mysql_fetch_array($sel); 
				$prov = mysql_result(myquery("SELECT COUNT(*) FROM game_wm WHERE user_id=$user_id_add AND type=1"),0,0);
				if ($prov==0 AND ($user_CC-$user_CW)<$this->fact['weight'])
				{
					echo '������������ ���������� ����� � ��������� ��� ���������� ��������: '.$this->fact['name'].'!';
					return $ar;
				}
*/
			}
			$up = myquery("UPDATE game_users SET CW=CW+".$this->fact['weight']." WHERE user_id=$user_id_add");
			$up = myquery("UPDATE game_users_archive SET CW=CW+".$this->fact['weight']." WHERE user_id=$user_id_add");
			$need_add = 1;
			$insert_id = 0;
			if ($this->counted_item())
			{
				$chek = mysql_result(myquery("SELECT COUNT(*) FROM game_items WHERE user_id=$user_id_add AND item_id=".$this->fact['id']." AND priznak=0"),0,0);
				if ($chek>0) $need_add=0;
			}
			if ($need_add==1)
			{
				$count_item = 1;
				if ($this->fact['type']==3)
				{
					$count_item = $this->fact['item_uselife'];
					$this->fact['item_uselife']=100;
				}
				$up = myquery("INSERT INTO game_items SET
								user_id = $user_id_add,
								ref_id='0',
								item_id=".$this->fact['id'].",
								item_uselife=".$this->fact['item_uselife'].",
								item_uselife_max=".$this->fact['item_uselife_max'].",
								item_cost=".$this->fact['item_cost'].",
								priznak=0,
								item_for_quest=$item_for_quest,
								count_item=$count_item,
								used=0");
				$insert_id = mysql_insert_id();
			}
			else
			{
				myquery("UPDATE game_items SET count_item=count_item+1 WHERE user_id='$user_id_add' AND item_id='".$this->fact['id']."' AND priznak=0");
			}
			$ar[0]=1;
			$ar[1]=$insert_id;
		}
		return $ar;
	}
}
?>