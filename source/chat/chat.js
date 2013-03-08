var rusBig = new Array( "Э", "Ч", "Ш", "Ё", "Ё", "Ж", "Ю", "Ю", "\Я", "\Я", "А", "Б", "В", "Г", "Д", "Е", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Щ", "Ъ", "Ы", "Ь");
var rusSmall = new Array("э", "ч", "ш", "ё", "ё","ж", "ю", "ю", "я", "я", "а", "б", "в", "г", "д", "е", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "щ", "ъ", "ы", "ь" );
var engBig = new Array("E\'", "CH", "SH", "YO", "JO", "ZH", "YU", "JU", "YA", "JA", "A","B","V","G","D","E", "Z","I","J","K","L","M","N","O","P","R","S","T","U","F","H","C", "W","~","Y", "\'");
var engSmall = new Array("e\'", "ch", "sh", "yo", "jo", "zh", "yu", "ju", "ya", "ja", "a", "b", "v", "g", "d", "e", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s",  "t", "u", "f", "h", "c", "w", "~", "y", "\'");
var rusRegBig = new Array( /Э/g, /Ч/g, /Ш/g, /Ё/g, /Ё/g, /Ж/g, /Ю/g, /Ю/g, /Я/g, /Я/g, /А/g, /Б/g, /В/g, /Г/g, /Д/g, /Е/g, /З/g, /И/g, /Й/g, /К/g, /Л/g, /М/g, /Н/g, /О/g, /П/g, /Р/g, /С/g, /Т/g, /У/g, /Ф/g, /Х/g, /Ц/g, /Щ/g, /Ъ/g, /Ы/g, /Ь/g);
var rusRegSmall = new Array( /э/g, /ч/g, /ш/g, /ё/g, /ё/g, /ж/g, /ю/g, /ю/g, /я/g, /я/g, /а/g, /б/g, /в/g, /г/g, /д/g, /е/g, /з/g, /и/g, /й/g, /к/g, /л/g, /м/g, /н/g, /о/g, /п/g, /р/g, /с/g, /т/g, /у/g, /ф/g, /х/g, /ц/g, /щ/g, /ъ/g, /ы/g, /ь/g);
var engRegBig = new Array( /E'/g, /CH/g, /SH/g, /YO/g, /JO/g, /ZH/g, /YU/g, /JU/g, /YA/g, /JA/g, /A/g, /B/g, /V/g, /G/g, /D/g, /E/g, /Z/g, /I/g, /J/g, /K/g, /L/g, /M/g, /N/g, /O/g, /P/g, /R/g, /S/g, /T/g, /U/g, /F/g, /H/g, /C/g, /W/g, /~/g, /Y/g, /'/g);
var engRegSmall = new Array(/e'/g, /ch/g, /sh/g, /yo/g, /jo/g, /zh/g, /yu/g, /ju/g, /ya/g, /ja/g, /a/g, /b/g, /v/g, /g/g, /d/g, /e/g, /z/g, /i/g, /j/g, /k/g, /l/g, /m/g, /n/g, /o/g, /p/g, /r/g, /s/g, /t/g, /u/g, /f/g, /h/g, /c/g, /w/g, /~/g, /y/g, /'/g);


function rusLang() 
{
	var oMessage = top.window.frames.chat.document.getElementById('chat_mess');
	var textar = oMessage.value;
	if (textar) 
	{
		for (i=0; i<engRegSmall.length; i++) 
		{
			textar = textar.replace(engRegSmall[i], rusSmall[i]); 
		}
		for (var i=0; i<engRegBig.length; i++) 
		{
			textar = textar.replace(engRegBig[i], rusBig[i]);  
		} 
		oMessage.value = textar;
	}
}

function sm(name)
{
	var oMessage = SetMessageInputFocus();
	oMessage.value=oMessage.value+" %sm"+name;
}
function pe(name,add)
{
	var oMessage = SetMessageInputFocus();
	var oToMessage = top.window.frames.chat.document.getElementById('too');
	if (add==1)
	{
		oMessage.value=oMessage.value + name;
	}
	else
	{
		oMessage.value=name;
	}
	oToMessage.value="Всем";
}
function priv(name)
{
	var oMessage = SetMessageInputFocus();
	var oToMessage = top.window.frames.chat.document.getElementById('too');
	if (name=='') name='Всем';
	if (name.length>10)
		oToMessage.size=name.length+5;
	else
		oToMessage.size=10;
	oToMessage.value=name;
}

var chatWindow;
/* chatURL - URL for updating chat messages */
var chatURL = "chat_ajax.php";
// when set to true, display detailed error messages
/* create XMLHttpRequest objects for updating the chat messages and
getting the selected color */
var xmlHttpGetMessages = createXmlHttpRequestObject();
var debugMode = true;
/* initialize the messages cache */
var cache = new Array();
/* lastMessageID - the ID of the most recent chat message */
var lastMessageID = -1;
/* creates an XMLHttpRequest instance */
function createXmlHttpRequestObject()
{
	// will store the reference to the XMLHttpRequest object
	var xmlHttp;
	// this should work for all browsers except IE6 and older
	try
	{
		// try to create XMLHttpRequest object
		xmlHttp = new XMLHttpRequest();
	}
	catch(e)
	{
		// assume IE6 or older
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
		"MSXML2.XMLHTTP.5.0",
		"MSXML2.XMLHTTP.4.0",
		"MSXML2.XMLHTTP.3.0",
		"MSXML2.XMLHTTP",
		"Microsoft.XMLHTTP");
		// try every prog id until one works
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++)
		{
			try
			{
				// try to create XMLHttpRequest object
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}
			catch (e) {}
		}
	}
	// return the created object or display an error message
	if (!xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else
		return xmlHttp;
}

function clear_chat()
{
	var tbody = top.window.frames.chat.chat_f.document.getElementById('chat_text_table');
	try
	{
		tbody.innerHTML = "";
	}
	catch(e)
	{
		while (tbody.rows.length > 0)
		{
			tbody.deleteRow(0);
		}
	}
}

function refresh_chat()
{
	clear_chat();
	lastMessageID = -1;
	requestNewMessages();
}

function SetMessageInputFocus()
{
	var oChat = top.window.frames.chat;
	if (document.all)
	{
		var oCurrentMessage = oChat.chat_mess;
	}
	else
	{
		var oCurrentMessage = oChat.document.getElementById('chat_mess');
	}
	oCurrentMessage.focus(); 
	return oCurrentMessage;
}

/* this function initiates the chat; it executes when the chat page loads
*/
function init()
{
	// get a reference to the text box where the user writes new messages
	var oMessageBox = SetMessageInputFocus();
	// prevents the autofill function from starting
	oMessageBox.setAttribute("autocomplete", "off");
	// initiates updating the chat window
	requestNewMessages();
}

/* function called when the Send button is pressed */
function sendMessage(message)
{
	var flag = 0;
	var oCurrentMessage = SetMessageInputFocus();
	var oToMessage = top.window.frames.chat.document.getElementById('too');
	if (message==null)
	{
		// save the message to a local variable and clear the text box
		message = trim(oCurrentMessage.value);
		if (message=='') return;
		tomessage = trim(oToMessage.value); 
		flag = 1;
		color = oCurrentMessage.style.color;
		//if (message.substr(0,4)=="[me]" || message.substr(0,1)=="#")
		//{}
		//else
		//    message = "[color="+color+"]"+message+"[/color]";
	}
	else
	{
		tomessage = "blazevic";
	}
	// don't send void messages
	if (trim(message) != "")
	{
		// if we need to send and retrieve messages
		params = "mode=SendAndRetrieveNew" +
		"&id=" + encodeURIComponent(lastMessageID) +
		"&message=" + encodeURIComponent(message);
		if (tomessage != "" &&  tomessage!='Всем')
		{
			params += "&to=" + encodeURIComponent(tomessage);     
		}
		params += "&chat=" + sel_chat;
		// add the message to the queue
		cache.push(params);
		// clear the text box
		oCurrentMessage.value = "";
		//alert(params);
		if (flag==0)
		{
			requestNewMessages();
		}
	}
}
/* makes asynchronous request to retrieve new messages, post new messages, delete messages */
function requestNewMessages()
{
	// only continue if xmlHttpGetMessages isn't void
	if(xmlHttpGetMessages)
	{
		try
		{
			// don't start another server operation if such an operation
			// is already in progress
			if (xmlHttpGetMessages.readyState == 4 ||
			xmlHttpGetMessages.readyState == 0)
			{
				// we will store the parameters used to make the server request
				var params = "";
				// if there are requests stored in queue, take the oldest one
				if (cache.length>0)
					params = cache.shift();
					// if the cache is empty, just retrieve new messages
				else
					params = "mode=RetrieveNew" +
					"&id=" +lastMessageID;
				// call the server page to execute the server-side operation
				xmlHttpGetMessages.open("POST", chatURL, true);
				xmlHttpGetMessages.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				xmlHttpGetMessages.onreadystatechange = handleReceivingMessages;
				xmlHttpGetMessages.send(params);
			}
			else
			{
				// we will check again for new messages
				setTimeout("requestNewMessages();", updateInterval);
			}
		}
		catch(e)
		{
			displayError(e.toString());
		}
	}
	else
	{
		xmlHttpGetMessages = createXmlHttpRequestObject();
	}
}
/* function that handles the http response when updating messages */
function handleReceivingMessages()
{
	try
	{
		// continue if the process is completed
		if (xmlHttpGetMessages.readyState == 4)
		{
			// continue only if HTTP status is "OK"
			if (xmlHttpGetMessages.status == 200)
			{
				try
				{
					 // process the server's response
					 readMessages();
				}
				catch(e)
				{
					 // display the error message
					 displayError(e.toString());
				}
			}
			else
			{
				// display the error message
				displayError(xmlHttpGetMessages.statusText);
			}
		}
	}
	catch(e)
	{
		displayError(e.toString());
	}
}
/* function that processes the server's response when updating messages */
function readMessages()
{
	// retrieve the server's response
	var response = xmlHttpGetMessages.responseText;
	// server error?
	if (response.indexOf("ERRNO") >= 0 || response.indexOf("error:") >= 0 || response.length == 0)
		throw(response.length == 0 ? "Void server response." : response);

	// retrieve the JSON object correspondig to the responseText element
	responseJSON = xmlHttpGetMessages.responseText.parseJSON();
	// initialize the arrays
	idArray = new Array();
	colorArray = new Array();
	nameArray = new Array();
	timeArray = new Array();
	messageArray = new Array();
	toArray = new Array();
	pmArray = new Array();
	channelArray = new Array();
	ptypeArray = new Array();
	// retrieve the arrays
	for(i=0;i<responseJSON[0].results.length;i++)
	{
		// retrieve the arrays from the server's response
		idArray[i] = responseJSON[0].results[i].id;
		colorArray[i] = responseJSON[0].results[i].color;
		nameArray[i] = responseJSON[0].results[i].name;
		timeArray[i] = responseJSON[0].results[i].time;
		messageArray[i] = responseJSON[0].results[i].message;
		toArray[i] = responseJSON[0].results[i].to;
		pmArray[i] = responseJSON[0].results[i].pm_id;
		channelArray[i] = responseJSON[0].results[i].channel;
		ptypeArray[i] = responseJSON[0].results[i].ptype;
	}
	// add the new messages to the chat window
	displayMessages(idArray, colorArray, nameArray, timeArray, messageArray, toArray, pmArray, channelArray, ptypeArray);
	// the ID of the last received message is stored locally
	if(idArray.length>0)
	lastMessageID = idArray[idArray.length-1]; 
	// restart sequence
	setTimeout("requestNewMessages();", updateInterval);
}
/* handles keydown to detect when enter is pressed */
function handleKey(e)
{
	// get the event
	e = (!e) ? window.event : e;
	// get the code of the character that has been pressed
	code = (e.charCode) ? e.charCode :
	((e.keyCode) ? e.keyCode :
	((e.which) ? e.which : 0));
	// handle the keydown event
	if (e.type == "keydown")
	{
		// if enter (code 13) is pressed
		if(code == 13)
		{
			// send the current message
			sendMessage();
		}
	}
}
/* removes leading and trailing spaces from the string */
function trim(s)
{
	return s.replace(/(^\s+)|(\s+$)/g, "")
}
function open_chat()
{
	if (!chatWindow || chatWindow.closed)
	{
		chatWindow = window.open("","chatWindow","status,scrollbars,toolbar,resizable=1,width="+screen.availWidth+",height="+screen.availHeight);
		content = "<html><head><title>Чат</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\"><meta name=\"Keywords\" content=\"Описание чата\"><style type=\"text/css\">@import url(\"../style/global.css\");</style></head><frameset rows=\"0,*\" frameborder=\"0\" border=\"0\" ><frame src=\"\" name=\"menu\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" NORESIZE><frame src=\"chat.php?full\" name=\"chat\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"yes\"></frameset><noframes><body></body></noframes></html>";
		chatWindow.document.write(content);
		chatWindow.document.close();
	}
	else 
	{
		if (chatWindow.focus) {
			chatWindow.focus();
		}
	}
}
// function that displays an error message
function displayError(message)
{
	try
	{
		t1 = xmlHttpGetMessages.readyState;
		//alert(message+'|||readyState='+t1+'|||responseText='+xmlHttpGetMessages.responseText+'|||status='+xmlHttpGetMessages.status+'|||statusText='+xmlHttpGetMessages.statusText);
	}
	catch(e)
	{
		xmlHttpGetMessages = createXmlHttpRequestObject(); 
	}
	// display error message, with more technical details if debugMode is true
	refresh_chat();
}
function refresh_smile()
{
	elem = top.window.frames.chat.document.getElementById("sel_smile");
	if (elem.style.visibility=="hidden")
	{
		hide_all();
		var elem = top.window.frames.chat.document.getElementById("sel_smile");
		var bar = top.window.frames.chat.document.getElementById("menu_smile");
		elem.style.visibility="visible";
		elem.style.left = bar.offsetLeft + 45 + "px";
		elem.style.top =  bar.offsetTop + 20 + "px";
		var elem = top.window.frames.chat.document.getElementById("table_smile1");
		if (elem) 
		{
			elem.style.display="block";
		}
	}
	else
	{
		elem.style.visibility="hidden";
   }
}

function refresh_setup()
{
	elem = top.window.frames.chat.document.getElementById("setup");
	if (elem.style.visibility=="hidden")
	{
		hide_all();
		var elem = top.window.frames.chat.document.getElementById("setup");
		var bar = top.window.frames.chat.document.getElementById("menu_setup");
		elem.style.visibility="visible";
		elem.style.left = bar.offsetLeft + 100 + "px";
		elem.style.top =  bar.offsetTop + 20 + "px";
	}
	else
	{
		elem.style.visibility="hidden";
   }
}

function refresh_extra()
{
	elem = top.window.frames.chat.document.getElementById("extra");
	if (elem.style.visibility=="hidden")
	{
		hide_all();
		var elem = top.window.frames.chat.document.getElementById("extra");
		var bar = top.window.frames.chat.document.getElementById("menu_extra");
		elem.style.visibility="visible";
		elem.style.left = bar.offsetLeft + 60 + "px";
		elem.style.top =  bar.offsetTop + 20 + "px";
	}
	else
	{
		elem.style.visibility="hidden";
   }
}

function hide_all()
{
	elem = top.window.frames.chat.document.getElementById("sel_smile");
	elem.style.visibility="hidden";
	elem = top.window.frames.chat.document.getElementById("setup");
	elem.style.visibility="hidden";
	elem = top.window.frames.chat.document.getElementById("extra");
	elem.style.visibility="hidden";      
	elem = top.window.frames.chat.document.getElementById("bbcode");
	elem.style.visibility="hidden";
	elem = top.window.frames.chat.document.getElementById("privat_mode");
	elem.style.visibility="hidden";
	table1 = top.window.frames.chat.document.getElementById("table_smile1");
	if (table1) {table1.style.display="none";}  
	table2 = top.window.frames.chat.document.getElementById("table_smile2");
	if (table2) {table2.style.display="none";}
	table3 = top.window.frames.chat.document.getElementById("table_smile3");
	if (table3) {table3.style.display="none";}
	table4 = top.window.frames.chat.document.getElementById("table_smile4");
	if (table4) {table4.style.display="none";}
	table5 = top.window.frames.chat.document.getElementById("table_smile5");
	if (table5) {table5.style.display="none";}
}
function show_table_smile(number)
{         
	table1 = top.window.frames.chat.document.getElementById("table_smile1");
	if (table1) {table1.style.display="none";}
	table2 = top.window.frames.chat.document.getElementById("table_smile2");
	if (table2) {table2.style.display="none";}
	table3 = top.window.frames.chat.document.getElementById("table_smile3");
	if (table3) {table3.style.display="none";}
	table4 = top.window.frames.chat.document.getElementById("table_smile4");
	if (table4) {table4.style.display="none";}
	table5 = top.window.frames.chat.document.getElementById("table_smile5");
	if (table5) {table5.style.display="none";}
	table = top.window.frames.chat.document.getElementById("table_smile"+number);
	if (table) {table.style.display="block";}
}
function color_words(color)
{
	elem = top.window.frames.chat.document.getElementById("chat_mess");
	elem.style.color=color;
}
function swap_sound()
{
	if (show_sound=="on")
	{
		show_sound="off";
	}
	else
	{
		show_sound="on";
	} 
	setCookie("chat_sound",show_sound);
	refresh_sound();
}
function refresh_sound()
{
	if (show_sound=="on")
	{
		img = top.window.frames.chat.document.getElementById("img_sound");
		img.src="img/sound-off.gif";
	}
	else
	{
		img = top.window.frames.chat.document.getElementById("img_sound");
		img.src="img/sound-on.gif";
	} 
}
function refresh_bbcode()
{
	elem = top.window.frames.chat.document.getElementById("bbcode");
	if (elem.style.visibility=="hidden")
	{
		hide_all();
		var elem = top.window.frames.chat.document.getElementById("bbcode");
		var bar = top.window.frames.chat.document.getElementById("menu_bbcode");
		elem.style.visibility="visible";
		elem.style.left = bar.offsetLeft + 96 + "px";
		elem.style.top =  bar.offsetTop + 20 + "px";
	}
	else
	{
		elem.style.visibility="hidden";
   }
}
function refresh_privat()
{
	elem = top.window.frames.chat.document.getElementById("privat_mode");
	if (elem.style.visibility=="hidden")
	{
		hide_all();
		var elem = top.window.frames.chat.document.getElementById("privat_mode");
		var bar = top.window.frames.chat.document.getElementById("menu_privat");
		elem.style.visibility="visible";
		elem.style.left = bar.offsetLeft + 96 + "px";
		elem.style.top =  bar.offsetTop + 20 + "px";
	}
	else
	{
		elem.style.visibility="hidden";
   }
}
function swap_minmax()
{
	if (minmax=="on")
	{
		minmax="off";
	}
	else
	{
		minmax="on";
	} 
	setCookie("chat_minmax",minmax);
	refresh_minmax();
}
function clear_chat_for_memory()
{
	var tbody = top.window.frames.chat.chat_f.document.getElementById('chat_text_table'); 
	for (var i=tbody.rows.length-1;i>=max_rows_onscreen;i--)
	{
		try
		{ tbody.deleteRow(i) }
		catch(e)
		{ tbody.rows[i].innerHTML=""; }
	}
}
function parseMessage(msg)
{
  var rx = null;
  
  rx = new RegExp('  ','g');
  msg = msg.replace(rx, '&nbsp;&nbsp;');

  // try to parse bbcode
  rx = new RegExp('\\[b\\](.+?)\\[\/b\\]','ig');
  msg = msg.replace(rx, '<span style="font-weight: bold">$1</span>');
  rx = new RegExp('\\[i\\](.+?)\\[\/i\\]','ig');
  msg = msg.replace(rx, '<span style="font-style: italic">$1</span>');
  rx = new RegExp('\\[u\\](.+?)\\[\/u\\]','ig');
  msg = msg.replace(rx, '<span style="text-decoration: underline">$1</span>');
  rx = new RegExp('\\[s\\](.+?)\\[\/s\\]','ig');
  msg = msg.replace(rx, '<span style="text-decoration: line-through">$1</span>');
  rx = new RegExp('\\[pre\\](.+?)\\[\/pre\\]','ig');
  msg = msg.replace(rx, '<pre>$1</pre>');
  rx = new RegExp('\\[email\\]([A-z0-9][\\w.-]*@[A-z0-9][\\w\\-\\.]+\\.[A-z0-9]{2,6})\\[\/email\\]','ig');
  msg = msg.replace(rx, '<a href="mailto: $1">$1</a>');
  rx = new RegExp('\\[email=([A-z0-9][\\w.-]*@[A-z0-9][\\w\\-\\.]+\\.[A-z0-9]{2,6})\\](.+?)\\[\/email\\]','ig');
  msg = msg.replace(rx, '<a href="mailto: $1">$2</a>');
  rx = new RegExp('\\[color=([a-zA-Z]+|\\#?[0-9a-fA-F]{6}|\\#?[0-9a-fA-F]{3})](.+?)\\[\/color\\]','ig');
  msg = msg.replace(rx, '<span style="color: $1">$2</span>');
  
  return msg;
}
// displays a message
function displayMessage(message,channel)
{
	// get the scroll object
	var tbody = top.window.frames.chat.chat_f.document.getElementById('chat_text_table');
	if (channel==1)
	{
		var tbody = top.window.frames.chat.chat_f.document.getElementById('combat_text_table');
	}
	if (channel==2)
	{
		var tbody = top.window.frames.chat.chat_f.document.getElementById('arcomage_text_table');
	}
	if (message=="#obn:")
	{
		try
		{
			tbody.innerHTML = "";
		}
		catch(e)
		{
			while (tbody.rows.length > 0)
			{
				tbody.deleteRow(0);
			}
		}
		tr = tbody.insertRow(0);
		td = tr.insertCell(0);
		td.innerHTML = "<img src=\"mag/obn.gif\" border=\"0\"><font color=red><b>Наложена печать обновления<b></font>";
	}
	else
	if (message.substr(0,8)=="#delete:")
	{
		idMessage = message.substr(8,message.length-8);
		try
		{
			var divElem = top.window.frames.chat.chat_f.document.getElementById("message"+idMessage);
			divElem.innerHTML = "";
		}
		catch(e)
		{
			//alert(e);
		}
	}
	else 
	if (message == "CLEAR")
	{
		try
		{
			tbody.innerHTML = "";
		}
		catch(e)
		{
			while (tbody.rows.length > 0)
			{
				tbody.deleteRow(0);
			}
		}
		tr = tbody.insertRow(0);
		td = tr.insertCell(0);
		td.innerHTML = "<img src=\"mag/slep.gif\" border=\"0\"><font color=red><b>На тебя наложена печать слепоты<b></font>";
	}
	else
	{
		// display the message
		tr = tbody.insertRow(0);
		td = tr.insertCell(0);
		td.innerHTML = message;
	}
}