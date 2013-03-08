<?php

class install
{
    function start()
    {
        //���������� ������� ��� ���������
        $step = 'step-1';
        if ((isset($_GET['step'])) && ((intval($_GET['step'])) >= 0))
            $step = 'step'.intval($_GET['step']);

        if ((isset($_GET['savestep'])) && ((intval($_GET['savestep'])) > 0))
            $step = 'savestep'.intval($_GET['savestep']);


        $html_content = file_get_contents("source/template/default/main.html");
        //$html_content = "";
        switch ($step)
        {
            //����� ������ ���, ����� ������� �������� �������, �� ���������
            //����� ������������ ��� � ��
            case 'step0':
                $html_content = file_get_contents("source/template/default/step0.html");
                //��������� ������ PHP
                $result = version_compare("5.0.0", phpversion());
                $err = '';
                $errors_found = false;
                if (intval($result) > 0)
                {
                    $err .= "<p>&nbsp;</p><p><b>������ PHP ������ ���� �� ���� 5.0.0</b></p>";
                    $errors_found = true;
                }
                //��������� ���������� ����������
                    $register_globals = (ini_get('register_globals') == 1) ? intval(1) : intval(0);
                    if (intval($register_globals) <= 0)
                {
                    $err .= "<p>&nbsp;</p><p><b>���������� �������� ���������� ���������� (register_globals)</b></p>";
                    $errors_found = true;
                }
                //��������� ���������� �������
                   $magic_quotes_gpc = (ini_get('magic_quotes_gpc') == 1) ? intval(1) : intval(0);
                   if (intval($magic_quotes_gpc) <= 0)
                {
                    $err .= "<p>&nbsp;</p><p><b>���������� �������� ���������� ������� (magic_quotes_gpc)</b></p>";
                    $errors_found = true;
                }
                //�������� ������������� �������
                $exts = get_loaded_extensions();
                $need_exts = array("calendar", "session", "date", "iconv", "json", "pcre", "mbstring", "gd", "mysql");
                foreach ($need_exts as $need_ext)
                {
                    if (!in_array($need_ext, $exts))
                    {
                        $err .= "<p>&nbsp;</p><p><b>�� ���������� ������ ".$need_ext." � PHP</b></p>";
                        $errors_found = true;
                    }
                }
                
                if ($errors_found)
                $html_content .= "<script>btnNext.setDisabled(true);</script>";
                $html_content = str_replace("[#errore_php#]", $err, $html_content);
                break;
                //========================================================================
                //���������� ����� ������� ����
                ////////////////////////////////////////////////
                                case 'step1':
                    $html_content = file_get_contents("source/template/default/step1.html");
                   
                    $gamename = '����������';
                    if ((isset($_SESSION['adm_inst']['gamename'])) && (!empty($_SESSION['adm_inst']['gamename'])))
                        $host = trim($_SESSION['adm_inst']['gamename']);
                    
                    $domen = $_SERVER['HTTP_HOST'];
                    if ((isset($_SESSION['adm_inst']['domen'])) && (!empty($_SESSION['adm_inst']['domen'])))
                        $prefix = trim($_SESSION['adm_inst']['domen']);

                    $path = $_SERVER['DOCUMENT_ROOT'];
                    if ((isset($_SESSION['adm_inst']['path'])) && (!empty($_SESSION['adm_inst']['path'])))
                        $prefix = trim($_SESSION['adm_inst']['path']);

                    $login = 'admin';
                    if ((isset($_SESSION['adm_inst']['login'])) && (!empty($_SESSION['adm_inst']['login'])))
                        $login = trim($_SESSION['adm_inst']['login']);

                    $password = 'password';
                    if ((isset($_SESSION['adm_inst']['password'])) && (!empty($_SESSION['adm_inst']['password'])))
                        $pass = trim($_SESSION['adm_inst']['password']);
                    
                    $email = 'admin@mygame.ru';
                    if ((isset($_SESSION['adm_inst']['email'])) && (!empty($_SESSION['adm_inst']['email'])))
                        $pass = trim($_SESSION['adm_inst']['email']);


                    $html_content = str_replace("[#value_gamename#]", $gamename, $html_content);
                    $html_content = str_replace("[#value_domen#]", $domen, $html_content);
                    $html_content = str_replace("[#value_path#]", $path, $html_content);
                    $html_content = str_replace("[#value_logins#]", $login, $html_content);
                    $html_content = str_replace("[#value_password#]", $password, $html_content);
                    $html_content = str_replace("[#value_email#]", $email, $html_content);
                break;

                //���������
                case 'savestep1':
                    $gamename = addslashes(iconv("utf-8", "windows-1251", trim($_POST['gamename'])));
                    $domen    = addslashes(iconv("utf-8", "windows-1251", trim($_POST['domen'])));
                    $path     = addslashes(iconv("utf-8", "windows-1251", trim($_POST['path'])));
                    $login    = addslashes(iconv("utf-8", "windows-1251", trim($_POST['login'])));
                    $password = addslashes(iconv("utf-8", "windows-1251", trim($_POST['password'])));
                    $email    = addslashes(iconv("utf-8", "windows-1251", trim($_POST['email'])));

                    $_SESSION['adm_inst']['gamename'] = $gamename;
                    $_SESSION['adm_inst']['domen']    = $domen;
                    $_SESSION['adm_inst']['path']     = $path;
                    $_SESSION['adm_inst']['login']    = $login;
                    $_SESSION['adm_inst']['password'] = $password;
                    $_SESSION['adm_inst']['email']    = $email;
                    
                     return '{success: true, info: ""}';
                break;
                
                //////////////////////////////////////
                
                case 'step2':
                    
                    $html_content = file_get_contents("source/template/default/step2.html");
                    
                    $host = 'localhost';
                    if ((isset($_SESSION['sqll_inst']['host'])) && (!empty($_SESSION['sqll_inst']['host'])))
                        $host = trim($_SESSION['sqll_inst']['host']);

                    $myslogin = '';
                    if ((isset($_SESSION['sqll_inst']['myslogin'])) && (!empty($_SESSION['sqll_inst']['myslogin'])))
                        $myslogin = trim($_SESSION['sqll_inst']['myslogin']);

                    $myspassword = '';
                    if ((isset($_SESSION['sqll_inst']['myspassword'])) && (!empty($_SESSION['sqll_inst']['myspassword'])))
                        $myspass = trim($_SESSION['sqll_inst']['myspassword']);

                    $namebase = '';
                    if ((isset($_SESSION['sqll_inst']['namebase'])) && (!empty($_SESSION['sqll_inst']['namebase'])))
                        $namebase = trim($_SESSION['sqll_inst']['namebase']);

                    $html_content = str_replace("[#value_hostadress#]", $host, $html_content);
                    $html_content = str_replace("[#value_myslogin#]", $myslogin, $html_content);
                    $html_content = str_replace("[#value_myspassword#]", $myspass, $html_content);
                    $html_content = str_replace("[#value_namebase#]", $namebase, $html_content);
                break;

                //��������� ����������� ����������� � ���� ������ mysqll_inst
                case 'savestep2':
                    
                    $host     = addslashes(iconv("utf-8", "windows-1251", trim($_POST['hostadress'])));
                    $myslogin = addslashes(iconv("utf-8", "windows-1251", trim($_POST['myslogin'])));
                    $myspass  = addslashes(iconv("utf-8", "windows-1251", trim($_POST['myspassword'])));
                    $namebase = addslashes(iconv("utf-8", "windows-1251", trim($_POST['namebase'])));

                    $_SESSION['sqll_inst']['host']     = $host;
                    $_SESSION['sqll_inst']['myslogin']    = $myslogin;
                    $_SESSION['sqll_inst']['myspassword'] = $myspass;
                    $_SESSION['sqll_inst']['namebase'] = $namebase;

                    $errore = '';
                    if ((empty($host)) || (empty($myslogin)) /*|| (empty($pass))*/ || (empty($namebase)))
                            $errore = "����� ��������� �� ���������.";
                    else
                    {
                        //������ ���������� �������� �� ���������� � mySql
                        $resurs = @mysql_connect($host, $myslogin, $myspass);
                        if (!$resurs)
                            $errore = "�� ���� ����������� � ����� ������. ��������� ����� ������� MySql, � ����� ����� � ������ ��� ������� � ����.";
                        else
                        {
                            if (!mysql_select_db ($namebase, $resurs))
                                $errore = '�� ���������� ���� ������ <i>'.$namebase.'</i>.';
                            else
                            {
                                $res = mysql_query("SELECT VERSION() AS v", $resurs);
                                $rec = mysql_fetch_assoc($res);
                                $v = explode(".",$rec['v']);
                                $v = intval($v[0]);
                                if ($v<4)
                                    $errore = "������ MySQL ������ ���� �� ���� 4";
                            }
                        }
                    }
                    if (empty($errore))
                        return '{success: true, info: ""}';
                    else
                        return '{success: false, info: "'.addslashes($errore).'"}';
                break;

                //========================================================================
                case 'step3':
                    $html_content = file_get_contents("source/template/default/step3.html");
                   
                    $host        = $_SESSION['sqll_inst']['host'];
                    $myslogin    = $_SESSION['sqll_inst']['myslogin'];
                    $myspass     = $_SESSION['sqll_inst']['myspassword'];
                    $namebase    = $_SESSION['sqll_inst']['namebase'];
                    $gamename    = $_SESSION['adm_inst']['gamename'];
                    $domen       = $_SESSION['adm_inst']['domen'];
                    $path        = $_SESSION['adm_inst']['path'];
                    $login       = $_SESSION['adm_inst']['login'];
                    $password    = $_SESSION['adm_inst']['password'];
                    $email       = $_SESSION['adm_inst']['email'];
                    
                    $html_content = str_replace("[#value_hostadress#]", $host,     $html_content);
                    $html_content = str_replace("[#value_myslogin#]",      $myslogin,    $html_content);
                    $html_content = str_replace("[#value_myspassword#]",   $myspass,     $html_content);
                    $html_content = str_replace("[#value_namebase#]",   $namebase, $html_content);
                    $html_content = str_replace("[#value_gamename#]", $gamename, $html_content);
                    $html_content = str_replace("[#value_domen#]", $domen, $html_content);
                    $html_content = str_replace("[#value_logins#]", $login, $html_content);
                    $html_content = str_replace("[#value_email#]", $email, $html_content);
                    $html_content = str_replace("[#value_password#]", $password, $html_content);
                    $html_content = str_replace("[#value_path#]", $path, $html_content);
                break;

                case 'savestep3':
                    return '{success: true, info: ""}';
                break;
    //========================================================================
                //��������� �����������
               case 'step4':
               $url_admin = 'http://'.$_SERVER['HTTP_HOST'].'/index.php';
                    //��������������� ������� ������ �����
                   $html = '';
                   $html .= '<script type="text/javascript">';
                   $html .= '    btnNext.setDisabled(true)';
                   $html .= '</script>';
                   clearstatcache();
                   if (!file_exists('source/form/engine.default.inc.php'))
                       return $html.'<p align="center">�� ���� ����� ���� �������� engine.default.inc.php.</p>';
                     
                   $html_config = file_get_contents('source/form/engine.default.inc.php');
                   $html_config = str_replace('[#DB_HOST#]'      ,$_SESSION['sqll_inst']['host'],     $html_config);
                   $html_config = str_replace('[#DB_BASENAME#]'  ,$_SESSION['sqll_inst']['namebase'], $html_config);
                   $html_config = str_replace('[#DB_USERNAME#]'  ,$_SESSION['sqll_inst']['myslogin'],    $html_config);
                   $html_config = str_replace('[#DB_PASSWORD#]'  ,$_SESSION['sqll_inst']['myspassword'], $html_config);
                   $html_config = str_replace('[#ADM_GAME_NAME#]'  ,$_SESSION['adm_inst']['gamename'], $html_config);
                   $html_config = str_replace('[#ADM_DOMEN#]'  ,$_SESSION['adm_inst']['domen'], $html_config);
                   $html_config = str_replace('[#ADM_PATCH#]'  ,$_SESSION['adm_inst']['path'], $html_config);
                   
                   $fp = fopen("../inc/engine.inc.php","w");
                   fwrite($fp,"$html_config");
                   fclose($fp);
                   
                    //������ ��������� ��
                     	 mysql_connect($_SESSION['sqll_inst']['host'], $_SESSION['sqll_inst']['myslogin'], $_SESSION['sqll_inst']['myspassword']);
                         mysql_select_db ($_SESSION['sqll_inst']['namebase']);
                         $fname = './source/sql/base_file.txt'; //��������� ���� � ����� � ������
                         $xray = file($fname);

                         $count = count($xray); //������������ ����� ��������� �������
                           for ($i=0; $i<=$count; $i++) {
                         $xray[$i] = rtrim($xray[$i], " \t\r\n\0\x0B"); //������� ��������� ����� (������ ��������, ���������, ������� �������, ���� ����, �������)

                          if (preg_match ("/;\n/i", $xray[$i]."\n")) //����������� ��������� + ���������� ������� ��������
                               {
                                    $sql .= $xray[$i]."\n";
                                    $sql = str_replace('[#ADM_GAME_NAME#]'  , $_SESSION['adm_inst']['gamename'], $sql);
                                    $sql = str_replace('[#ADM_PASSWORD#]'   , md5($_SESSION['adm_inst']['password']), $sql);
                                    $sql = str_replace('[#ADM_LOGIN#]'      , $_SESSION['adm_inst']['login'],    $sql);
                                    $sql = str_replace('[#ADM_EMAIL#]'      , $_SESSION['adm_inst']['email'],    $sql);
                                    mysql_query($sql); //������
                                    $sql = null; //������� ����������
                               }
                         else
                               {
                                    $sql .= $xray[$i]."\n"; //��������� ������
                               }
                             }
                     	 //////////////////////////////
                       $html .= '<br><p align="center">���� �������� ������� ������!</p><br>';
                       $html .= '<script type="text/javascript">';
                       $html .= '    redirect_admin("'.$url_admin.'");';
                       $html .= '</script>';
                       $html .= '<p align="center">��� ����� � ���� ������� ������ "������". <br>�� �������� ����������� ������� ����� install � ������ �����.</p><br>';
                       
                       $html .= '<p align="center">��� ����������� � ���� � ���������������� ���������� �����������: <br> ����� "'.$_SESSION['adm_inst']['login'].'" � ������ "'.$_SESSION['adm_inst']['password'].'"</p>';
                       
                   return $html;
                break;
        }
        return $html_content;
    }

}
?>