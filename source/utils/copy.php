<?
error_reporting('E_ALL');
require_once("../inc/engine.inc.php");
$url = 'http://img.ignio.com/r/export/win/xml/daily/com.xml';
$file = ''.PATCH.'/cache/com.xml';

if (file_exists($file))
unlink($file);

$ch = curl_init();
if($ch)
{
    $fp = fopen($file, "w");
    if($fp)
    {
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FILE, $fp);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_exec($ch);
      curl_close($ch);
      fclose($fp);
    }
}

?>