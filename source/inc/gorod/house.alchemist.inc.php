<?
if (isset($mes))
{ 
	echo '<iframe src="../quest/alchemist.php?house&mes='.$mes.'" width="100%" height="700"></iframe>';
}
else
{
	echo '<iframe src="../quest/alchemist.php?house" width="100%" height="700"></iframe>';
}
?>