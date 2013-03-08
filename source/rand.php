<?php
function make_seed()
{
	return hexdec(substr(md5(microtime()), -8)) & 0x7fffffff;
}

$ar = array();
$ar10 = 0;
$ar20 = 0;
$ar30 = 0;
$ar40 = 0;
$ar50 = 0;
$ar60 = 0;
$ar70 = 0;
$ar80 = 0;
$ar90 = 0;
$ar100 = 0;
for ($i=1;$i<=100000;$i++)
{
	mt_srand(make_seed());
	$r = mt_rand(1,100);
	$ar[] = $r;
	if     ($r<=10) $ar10++;
	elseif ($r<=20) $ar20++;
	elseif ($r<=30) $ar30++;
	elseif ($r<=40) $ar40++;
	elseif ($r<=50) $ar50++;
	elseif ($r<=60) $ar60++;
	elseif ($r<=70) $ar70++;
	elseif ($r<=80) $ar80++;
	elseif ($r<=90) $ar90++;
	elseif ($r<=100) $ar100++;
}

for ($i=0;$i<=100;$i++)
{
	echo '&nbsp;'.$ar[$i].'&nbsp;';
	if ($i%10==9)
	{
		echo '<br />';
	}
}
echo '<br /><br /><br />
От 0 до 10 = '.$ar10.' чисел<br />
От 10 до 20 = '.$ar20.' чисел<br />
От 20 до 30 = '.$ar30.' чисел<br />
От 30 до 40 = '.$ar40.' чисел<br />
От 40 до 50 = '.$ar50.' чисел<br />
От 50 до 60 = '.$ar60.' чисел<br />
От 60 до 70 = '.$ar70.' чисел<br />
От 70 до 80 = '.$ar80.' чисел<br />
От 80 до 90 = '.$ar90.' чисел<br />
От 90 до 100 = '.$ar100.' чисел<br />';
?>  