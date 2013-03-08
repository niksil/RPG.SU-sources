<?

if (function_exists("start_debug")) start_debug(); 

echo'<table border="0" cellpadding="2" width=100%>';
$sel=myquery("select * from game_zakon order by id");
while($zak=mysql_fetch_array($sel))
{
    echo'
    <tr>
    <td width="2%" height="9" valign="top" align=center><font color=#FF0000>'.$zak['id'].'</font></td>
    <td width="24%" valign="top">'.$zak['name'].'</td>
    <td width="70%"><p align=justify>'.$zak['text'].'</p></td>
    <td valign="top">';
    if ($zak['time']!='0') echo 'Время наказания: <font color=#FF0000>'.$zak['time'].'</font> минут';
    echo'</td>
    </tr>
    <tr><td>&nbsp;</td></tr>';
}
echo'
<tr><td colspan=4><font color=#FF0000>При рецидиве нарушения любого закона - время наказания автоматически увеличивается в 2(3,4,5....) раза</font></td></tr>
</table>';

if (function_exists("save_debug")) save_debug(); 

?>