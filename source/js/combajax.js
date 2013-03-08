//top.window.frames.chat.location.replace("combat/chat.php?type=chat");

//******AJAX - составление списка новых игроков и определение ничьи
var updateInterval=10000;//10 sec
var xmlHttpGetMessages = createXmlHttpRequestObject();
var xmlHttpGetPass = createXmlHttpRequestObject();
var params="";
var timeout_id="";
var timeout_id_pass="";

function call_clan()
{
    params='call_clan';
    requestNewUsers();    
}

function requestNewUsers()
{
    if(xmlHttpGetMessages)
    {
        try
        {
            if (xmlHttpGetMessages.readyState == 4 || xmlHttpGetMessages.readyState == 0)
            {
                var now = new Date();
                url = ScriptUrl+"?"+now.getTime();
                if (params!="")
                {
                    url = url+"&"+params;    
                }   
                xmlHttpGetMessages.open("GET", url, true);
                xmlHttpGetMessages.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                xmlHttpGetMessages.onreadystatechange = handleReceivingNewUser;
                xmlHttpGetMessages.send(null);
                params="";
            }
            else
            {
                clearTimeout(timeout_id);
                timeout_id = setTimeout("requestNewUsers();", updateInterval);
            }
        }
        catch(e)
        {
        }
    }
    else
    {
        xmlHttpGetMessages = createXmlHttpRequestObject();
        requestNewUsers();
    }
} 

function requestPassUsers()
{
    if(xmlHttpGetPass)
    {
        try
        {
            if (xmlHttpGetPass.readyState == 4 || xmlHttpGetPass.readyState == 0)
            {
                var now = new Date(); 
                url = ScriptUrl+"?call_pass&"+now.getTime();    
                xmlHttpGetPass.open("GET", url, true);
                xmlHttpGetPass.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
                xmlHttpGetPass.onreadystatechange = handleReceivingPassUser;
                xmlHttpGetPass.send(null);
            }
            else
            {
                clearTimeout(timeout_id_pass);
                timeout_id_pass = setTimeout("requestPassUsers();", updateInterval);
            }
        }
        catch(e)
        {
        }
    }
    else
    {
        xmlHttpGetPass = createXmlHttpRequestObject();
        requestPassUsers();
    }
} 

function handleReceivingNewUser()
{
    try
    {
        if (xmlHttpGetMessages.readyState == 4)
        {
            if (xmlHttpGetMessages.status == 200)
            {
                try
                {
                    if (xmlHttpGetMessages.responseText=="pass")
                    {
                        location.replace("war.php");
                    }
                    else
                    {
                        if (xmlHttpGetMessages.responseText=="call_clan")
                        {
                            bt = document.getElementById("button_call_clan");
                            bt.style.display="none";
                        }
                        else
                        {
                            if (xmlHttpGetMessages.responseText!="nobody")
                            {
                                var div_up = document.getElementById("div_newusers"); 
                                var sp_up = document.getElementById("span_newusers");
                                div_up.style.display="block";
                                sp_up.innerHTML = xmlHttpGetMessages.responseText;
                            }
                        }
                    }
                }
                catch(e)
                {
                }
            }
            else
            {
            }
        }
    }
    catch(e)
    {
        xmlHttpGetMessages = createXmlHttpRequestObject();
    }
    clearTimeout(timeout_id);
    timeout_id = setTimeout("requestNewUsers();", updateInterval); 
}

function handleReceivingPassUser()
{
    try
    {
        if (xmlHttpGetPass.readyState == 4)
        {
            if (xmlHttpGetPass.status == 200)
            {
                try
                {
                    if (xmlHttpGetPass.responseText!="nopass")
                    {
                        var div_up = document.getElementById("div_passusers"); 
                        var sp_up = document.getElementById("span_passusers");
                        div_up.style.display="block";
                        sp_up.innerHTML = xmlHttpGetPass.responseText;
                    }
                    else
                    {
                        var div_up = document.getElementById("div_passusers"); 
                        var sp_up = document.getElementById("span_passusers");
                        div_up.style.display="none";
                        sp_up.innerHTML = "";
                    }
                }
                catch(e)
                {
                }
            }
            else
            {
            }
        }
    }
    catch(e)
    {
        xmlHttpGetPass = createXmlHttpRequestObject();
    }
    clearTimeout(timeout_id_pass);
    timeout_id_pass = setTimeout("requestPassUsers();", updateInterval); 
}
function init()
{
    requestNewUsers();
    requestPassUsers();
}