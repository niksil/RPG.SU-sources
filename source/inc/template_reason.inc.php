<?php

if (function_exists("start_debug")) start_debug(); 

error_reporting (E_ALL);

if (preg_match('/.inc.php/', $_SERVER['PHP_SELF']))
{
    setLocation('index.php');
}
else
{   
    if ($reason == 'stamina' AND $char['clevel']>0)
    {
        echo '<font color="red">�� ������� '.echo_sex('�����','������').':</font> �������!<br><br>';
    }
    elseif ($reason == 'delay' && $user_time < $char['delay'])
    {
        switch ($char['delay_reason'])
        {
            case 19:
                $delay_reason = '�������';
                break;
            case 20:
                $delay_reason = '�������';
                break;
            case 7:
                $delay_reason = '����� ������� ������� �������';
                break;
            case 8:
                $delay_reason = '�� ������� ������ ������! �������';
                break;
            default:
                $delay_reason = '������!';
        }
        if ($char['delay_reason']==8 AND $char['clevel']==0)
        {
		echo '<body onload="GGearsInit();">';
	}
        else
	    {
		    echo '<font color="#ff0000">���������:</font> ' . $delay_reason . '  ��� <span id="pendule"></span>&nbsp;<font color="#ff0000">'
		    .'<script language="JavaScript">
		    a='.abs($user_time - $char['delay']).'
		    text1="";
		    function clock_status()
		    {
			    if (a<=9) text="&nbsp;"+a;
			    if (a<=0) {text1="(������)";text="0";}
			    else text=a;
			    if (document.layers) {
				    document.layers.pendule.document.write(text);
				    document.layers.pendule.document.close();
				    document.layers.pend.document.write(text1);
				    document.layers.pend.document.close();
				    }
				    else
				    {
					    document.getElementById("pendule").innerHTML = text;
					    document.getElementById("pend").innerHTML = text1;
					    a=a-1;
					    window.setTimeout("clock_status()",1000); 
				    }
		    }
		    </script>
		    <body onLoad="clock_status(); GGearsInit();">'.' </font>���. <span id="pend"></span><br><br>';
	    }
    }
}

if (function_exists("save_debug")) save_debug(); 

?>