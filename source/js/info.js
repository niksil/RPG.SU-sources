function properties(obj,obj_name)
{
	var result="";
	for (var i in obj) {
		result += "" + obj_name + "." + i + " = " + obj[i] + "<br>";
	}
	result += "<br>";
	return result;
}

function movehint(event,toleft)
{
	var n = (document.layers) ? 1 : 0;
	
	if (n)
	{
	}
	else if(event.clientX)
	{
		hor = document.body.scrollWidth - document.getElementById('hint').offsetWidth;
		ver = document.body.scrollHeight - document.getElementById('hint').offsetHeight;
		
		posHor = document.body.scrollLeft + event.clientX + 10;
		posVer = document.body.scrollTop + event.clientY + 10;

		posHor2 = document.body.scrollLeft + event.clientX - document.getElementById('hint').offsetWidth - 5;
		posVer2 = document.body.scrollTop + event.clientY - document.getElementById('hint').offsetHeight - 5;

		if (event.clientX + 350 > document.body.scrollWidth)
			toleft = 1;

		if (toleft==1)
		{
			posHor = posHor - 265;
			posHor2 = posHor - 265;
		}
		
		if (posHor<hor)
		{
			if ((posHor + document.getElementById('hint').offsetWidth) > (document.body.scrollWidth - 5))
			{
				document.getElementById('hint').style.left = document.body.scrollLeft + document.body.scrollWidth - document.getElementById('hint').offsetWidth - 5;
			}
			else
			{
				document.getElementById('hint').style.left = posHor;
			}
		}
		else
		{
			if (posHor2 < (document.body.scrollLeft + 5))
			{
				document.getElementById('hint').style.left = document.body.scrollLeft + 5;
			}
			else
			{
				document.getElementById('hint').style.left = posHor2;
			}
		}
		if (posVer<ver)
		{
			if ((posVer + document.getElementById('hint').offsetHeight) > (document.body.scrollTop + document.body.scrollHeight - 5))
			{
				document.getElementById('hint').style.top = document.body.scrollTop + document.body.scrollHeight - document.getElementById('hint').offsetHeight - 5;
			}
			else
			{
				document.getElementById('hint').style.top = posVer;
			}
		}
		else
		{
			if (posVer2 < (document.body.scrollTop + 5))
			{
				document.getElementById('hint').style.top = document.body.scrollTop + 5
			}
			else
			{
				document.getElementById('hint').style.top = posVer2;
			}
		}
	}
}


function showhint(x,y,w,z,event,toleft)
{
	var n = (document.layers) ? 1 : 0;
	textNN = y; 
	sizeNN = w; 
	//if (x == '') x = "Описание";
	headerNN = x; 
	temp = "<TABLE WIDTH=250 BORDER=0 CELLSPACING=0 CELLPADDING=2 style=\"z-index:999;opacity:0.75;filter:alpha(opacity=75);margin-bottom:5px; margin-right:5px;border: groove maroon 2px; background-color:#fffff0;;font-size:11px;font-family:verdana;\"><TR><TD bgcolor=#e4d4b1 style=\"border-bottom: solid 1px maroon;\" class=s><B>" + x + "</B></FONT></TD></TR><TR><TD class=s style=\"padding: 7px; text-align:left;\">" + y + "</TD></TR></TABLE>";
	if (n)
	{
		document.captureEvents(Event.MOUSEOVER);
		document.captureEvents(Event.MOUSEOUT);
		document.captureEvents(Event.MOUSEMOVE);
		//document.onmouseover = showNN;
		//document.onmousemove = moveNN;
		//document.onmouseout = hideNN;
		if (parseInt(navigator.appVersion)>= 5)
		{
			document.getElementById('hint').innerHTML = temp;
			document.getElementById('hint').style.width = w;
			if (z == 1)
				document.getElementById('hint').style.visibility = "visible";
			else
				document.getElementById('hint').style.visibility = "hidden";
		}
		else
		{
			document.layers['hint'].width = w;
			document.layers['hint'].document.write(temp);
			document.layers['hint'].document.close();
			document.layers['hint'].visibility = "show";
		}
	}
	else
	{
		if (z == 1)
		{
			document.getElementById('hint').innerHTML = temp;
			document.getElementById('hint').style.width = w;
			document.getElementById('hint').style.visibility = "visible";
			movehint(event,toleft);
		}
		else
		{
			document.getElementById('hint').style.visibility = "hidden";
			document.getElementById('hint').style.posTop = 0;
			document.getElementById('hint').style.posLeft = 0;
		}
	}
}