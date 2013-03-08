function createXmlHttpRequestObject()
{
    var xmlHttp;
    try
    {
        xmlHttp = new XMLHttpRequest();
    }
    catch(e)
    {
        var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
        "MSXML2.XMLHTTP.5.0",
        "MSXML2.XMLHTTP.4.0",
        "MSXML2.XMLHTTP.3.0",
        "MSXML2.XMLHTTP",
        "Microsoft.XMLHTTP");
        for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
        {
            try
            {
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            }
            catch (e) {}
        }
    }
    if (!xmlHttp)
        return null;
    else
        return xmlHttp;
}

var ForumAjaxPoll = createXmlHttpRequestObject();
//var ForumAjaxReply = createXmlHttpRequestObject();

function request_poll()
{
    if (ForumAjaxPoll)
    {
        frmPoll = document.forms.read_poll;
        params = "actionpoll&ajax&vote";
        numradios = frmPoll.vote_id.length;
        for (var i = 0; i < numradios; i++)
        {
            if (frmPoll.vote_id[i].checked)
            {
                params=params+'&vote_id='+frmPoll.vote_id[i].value;
            }
        }
        params=params+'&poll_id='+frmPoll.poll_id.value;
        try
        {
            if (ForumAjaxPoll.readyState == 4 || ForumAjaxPoll.readyState == 0)
            {
                ForumAjaxPoll.open("GET", "index.php?"+params, true);
                ForumAjaxPoll.onreadystatechange = handle_poll;
                ForumAjaxPoll.send(null);
            }
        }
        catch(e)
        {
            //alert(e.toString());
        }
        return false;
    }
    return true;
}

function handle_poll()
{
    try
    {
        if (ForumAjaxPoll.readyState == 4)
        {
            if (ForumAjaxPoll.status == 200)
            {
                try
                {
                    //
                    if (document.all)
                        span = document.ajax_read_poll;
                    else
                        span = document.getElementById('ajax_read_poll');
                    if (span)
                    {
                        span.innerHTML = ForumAjaxPoll.responseText;
                    }
                }
                catch(e)
                {
                    //alert(e.toString());
                }
            }
        }
    }
    catch(e)
    {
        //alert(e.toString());
    }
}