<?
					{
						$ssylka='���������� �����';
					}
				}
				if (($items["item_id"]==shamp_item_id) OR ($items["item_id"]==hlop_item_id) OR ($items["item_id"]==berez_item_id) OR ($items["item_id"]==ell_item_id) OR ($items["item_id"]==beer_s_item_id) OR ($items["item_id"]==beer_t_item_id) OR ($items["item_id"]==beer_td_item_id))
				{
					$ssylka='<a href=::link::>������������</a>';
					$link='"http://'.DOMAIN.'/item.php?inv_option=use&id='.$items['id'].'"';
				}

				$Item = new Item($items['id']);
				$check_up = $Item->check_up();
				
				echo '<tr><td>';
				if ($Item->item['ref_id']==1 AND (!$Item->counted_item() OR $Item->fact['type']==22) AND $Item->fact['type']!=20)
				{
					$Item->ImageUnidentItem();
				}
				else
				{
					$Item->hint(0,1,'<span '); 
					ImageItem($Item->fact['img'],0,$Item->item['kleymo']);
					echo '</span>';
				}

				if ($from_house AND $link!='')
				{
					$link='"'.str_replace('"','',$link).'&house&option='.$option.'"';    
				}
				if ($from_house AND $link1!='')
				{
					$link1='"'.str_replace('"','',$link1).'&house&option='.$option.'"';    
				}
				if ($from_house AND $link2!='')
				{
					$link2='"'.str_replace('"','',$link2).'&house&option='.$option.'"';    
				}

				if ($link!='')
				{
					$ssylka = str_replace('::link::',$link,$ssylka);
				}
				if ($link1!='')
				{
					$ssylka = str_replace('::link1::',$link1,$ssylka);
				}
				if ($link2!='')
				{
					$ssylka = str_replace('::link2::',$link2,$ssylka);
				}

				if ($check_up == 1)
					$dotsym = '<span style="color:chartreuse">&#149;</span> ';
				else
					$dotsym = '<span style="color:red">&#149;</span> ';

//				$dotsym = '<span style="color:gray">&#149;</span> ';

				$ssylka = str_replace('::DOT::',$dotsym,$ssylka);

				echo '</td><td>'.$ssylka.'</td><td>';


				if ($Item->personal==0 AND !$from_house)
				{                                                                                           
					echo '| <a href="item.php?inv_option=drop&id='.$items['id'].'">��������</a></td>';
				}
				/*if ($from_house)
				{
					$ss = 'inv.php?option='.$_GET['option'].'&house&identify_id='.$items['id'].'&func=inv';
				}
				else
				{
					$ss = 'act.php?identify_id='.$items['id'].'&func=inv';
				}
				$Item->hint(0,1,'<a href="'.$ss.'" ');
				echo '����������</a>';*/
                if ($Item->getFact('type')==3 and $items['ref_id']!=1 AND !$from_house)
				{
					$zar = $Item->getFact('item_uselife')-$Item->getItem('count_item');
					if ($zar>0)
					{
						if ($char['STM_MAX']>$char['MP_MAX'])
						{
							$mp = 10;
							$stm = 5;
						}
						else
						{
							$mp = 5;
							$stm = 10;
						}
						
						$kol_mp = floor($char['MP']/$mp);
						$kol_stm = floor($char['STM']/$stm);
						$kol = min($kol_mp,$kol_stm);
						$zar = min($zar,$kol);
						if ($zar>0)
						{
							$sel_last_event = myquery("SELECT timestamp FROM game_users_event WHERE user_id=$user_id AND event=1");
							if ($sel_last_event!=false AND mysql_num_rows($sel_last_event)>0)
							{
								list($last_event) = mysql_fetch_array($sel_last_event);
							}
							else
							{
								$last_event = 0;
							}
							if (($last_event+$Item->getFact('cooldown'))<time() AND !$from_house)
							{
								echo '</td><td>&nbsp;|&nbsp;<a href="?func=inv&option=charge&id='.$items['id'].'">�������� �� '.$zar.' '.pluralForm($zar,'�����','������','�������').'</a><td>';
							}
							else
							{
								$razn = ($last_event+$Item->getFact('cooldown'))-time();
								$min = floor($razn/60);
								$sec = $razn-$min*60;
								echo '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;�� ��� '.echo_sex('�������','��������').' �������� '.date("d.m.Y H:i:s",$last_event).'. <br>&nbsp;&nbsp;|&nbsp;���� ���� ��������� ���.<br>&nbsp;&nbsp;&nbsp;&nbsp;�������� ����� '.$min.' ���. '.$sec.' ���.';
							}
						}                        
					}
			   }
				$nom++;
				echo'</td></tr>';
			}
			echo '</table>';
		}
	}
	
	if ($col_14>=7)
	{
		QuoteTable('open','500px');
		echo '<center>�� '.echo_sex('������','�������').' '.$col_14.' '.pluralForm($col_14,'����','����','���').' �������. <br />��� ������ ������� ���������� ����� 7 ���. �� ������ <br /><br /><a href="act.php?func=inv&make_amulet">������� ������ �������</a>. <br /><br />�������������� ������ �������� ����� �������� �� ����, �� ����� ������ �� ������!';
		QuoteTable('close');
	}
    if ($col_part_svitok_hranitel>=5)
    {
        QuoteTable('open','500px');
        echo '<center>�� '.echo_sex('������','�������').' '.$col_part_svitok_hranitel.' '.pluralForm($col_part_svitok_hranitel,'�����','�����','������').' ������ ���������. <br />��� ��������� ������ ��������� ���������� ����� 5 ������. �� ������ <br /><br /><a href="act.php?func=inv&make_svitok">������� ������ ��������� �� ������</a>.';
        QuoteTable('close');
    }
    if ($col_part_svitok_ice>=10)
    {
        QuoteTable('open','500px');
        echo '<center>�� '.echo_sex('������','�������').' '.$col_part_svitok_ice.' '.pluralForm($col_part_svitok_ice,'�����','�����','������').' ������ �������� �������. <br />��� ��������� ������ �������� ������� ���������� ����� 10 ������. <br />�� ������ <br /><br /><a href="act.php?func=inv&make_svitok_ice">������� ������ �������� ������� �� ������</a>.';
        QuoteTable('close');
    }
	echo '</span>';
}

if (!isset($map)) 
{
	if (isset($item))
	{
		$map=$item;
	}
	else
	{
		$map = 0;
	}
}
?>
<script>
function ge( a )
{
	if( document.all ) return document.all[a];
	else return document.getElementById( a );
}
parent.ge( '<? echo'd'.$map.''; ?>' ).innerHTML=ge( 'qwe' ).innerHTML;
</script>   
<?
if (function_exists("save_debug")) save_debug(); 
?>