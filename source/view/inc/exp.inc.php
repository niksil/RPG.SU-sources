<?

if (function_exists("start_debug")) start_debug(); 


echo'<table width=100% border=0 cellpadding="1" cellspacing="2" bordercolor=c0c0c0><tr><th>Уровень</th><th>Опыт до уровня</th><th>Золото</th><th>Навыки</th><th>Харки</th></tr>';
for ($clevel=1;$clevel<=40;$clevel++)
{	
    $new_clevel = get_new_level($clevel-1);
    if ($clevel >= 0 and $clevel < 10) { $gp=50; $nav=1; $xar=2;}
    elseif ($clevel == 10) {$gp=300; $nav=3; $xar=3;}
    elseif ($clevel > 10 and $clevel < 20) {$gp=100; $nav=1; $xar=2;}
    elseif ($clevel == 20) {$gp=500; $nav=3; $xar=3;}
    elseif ($clevel > 20 and $clevel < 30){ $gp=200; $nav=1; $xar=2;}
    elseif ($clevel == 30) {$gp=1000; $nav=3; $xar=3;}
    elseif ($clevel > 30 and $clevel < 40){ $gp=300; $nav=1; $xar=2;}
    elseif ($clevel == 40) {$gp=1500; $nav=3; $xar=3;}
    $bgcolor = "#585858";
    if ($clevel%2==0) $bgcolor = "#333333";
    if ($clevel%10==0)
    {
        echo '<tr style="text-align:center;background-color:'.$bgcolor.';font-size:16px;font-weight:900;font-color:white"><td>'.$clevel.'</td><td>'.$new_clevel.'</td><td>'.$gp.'</td><td>+'.$nav.' </td><td>+'.$xar.'</td></tr>';
        
    }
    else
    {
	    echo '<tr style="text-align:center;background-color:'.$bgcolor.';font-size:14px;font-weight:400;"><td>'.$clevel.'</td><td>'.$new_clevel.'</td><td>'.$gp.'</td><td>+'.$nav.' </td><td>+'.$xar.'</td></tr>';
    }
}
echo'</table>';

if (function_exists("save_debug")) save_debug(); 

?>