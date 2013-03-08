var mainTableWidth = 750;
var browserName = navigator.appName;

if (browserName == "Netscape") {
  v  = ".top";
  l  = ".left";
  dS = "document.";
  sD = "";
  y  = "window.pageYOffset";
  iW = "window.innerWidth";
  iH = "window.innerHeight";
  oW = -8;
} else {
  v  = ".pixelTop";
  l  = ".pixelLeft";
  dS = "";
  sD = ".style";
  y  = "document.body.scrollTop";
  iW = "document.body.clientWidth";
  iH = "document.body.clientHeight";
  oW = 0;
}

var dhtml         = null; // Our layer
var isOver        = true;
var myTimer       = setTimeout("HideDHTML()",300);
var intCurrent    = -1;
var intNew        = -2;

var ns4 = (document.layers)? true:false
var ie4 = (document.all)? true:false
/*
if ((ns4) || (ie4)) {
} else {
        PopUp = no_PopUp;
}

function no_PopUp() {
        return true;
}
*/

function PopUp(intNew,event) {
     clearTimeout(myTimer);
	 //intNew = arguments[0];
	 //event = arguments[1];

     if ( intCurrent != intNew )
     {
          intCurrent = intNew;

          // Load defaults to runtime.
          var m_frame     = self;
		  var m_border    = "1";
          var m_fgcolor   = "BGCOLOR=\"#000080\"";
          var m_bgcolor   = "BGCOLOR=\"#FFFFB3\"";
          var m_text      = DHTML_texts[intNew];
          var m_textfont  = "arial,helvetica,verdana";
          var m_textsize  = "1";
          var m_textcolor = "#FF0000";
		  var divTable = document.getElementById('DHTLMenu');

          var layerhtml = "<TABLE id=divTable "
                              + " BORDER=0 CELLPADDING="
                              + m_border
                              + " CELLSPACING=0 "
                              + m_bgcolor
                              + " "
                              + "><TR><TD>"
                              + "<TABLE WIDTH=100% BORDER=0 CELLPADDING=2 CELLSPACING=0 "
                              + m_fgcolor
                              + " "
                              + "><TR><TD VALIGN=TOP><FONT FACE=\"" + m_textfont + "\""
                              + " COLOR=\"" + m_textcolor + "\" SIZE=\"" + m_textsize + "\">"
                              + m_text + "</FONT></TD></TR></TABLE></TD></TR></TABLE>";

          //if (ns4) { dhtml = m_frame.document.DHTLMenu; }
          //if (ie4) { dhtml = m_frame.DHTLMenu.style; }
          dhtml = m_frame.document.getElementById("DHTLMenu").style;
          
          dhtml.left = event.clientX + 10;
	      dhtml.top = event.clientY + document.body.scrollTop;
          //dhtml.top = event.clientY;
          dhtml.onmouseover = OverDHTML;
          dhtml.onmouseout = OutDHTML;
          OverDHTML();
          // if (ns4)
          // {
          //      var lyr = dhtml.document;
          //      lyr.write(layerhtml);
          //      lyr.close();
          //      dhtml.visibility = "show";
          // }
          // else if (ie4)
          // {
                //m_frame.document.all["DHTLMenu"].innerHTML = layerhtml;
                divTable.innerHTML = layerhtml;
                dhtml.visibility = "visible";
          // }
		  dhtml.top = event.clientY + document.body.scrollTop;
          dhtml.left = event.clientX - divTable.scrollWidth - 2;
    }
}


function HideDHTML() {
    if (!isOver) {
        intCurrent = -2;
        if (dhtml != null) {
            //if (ns4) { dhtml.visibility = "hide" }
            //else if (ie4) { dhtml.visibility = "hidden" }
            dhtml.visibility = "hidden";
        }
    }
}

function OverDHTML() {
    clearTimeout(myTimer);
    isOver = true;
}

function OutDHTML() {
    clearTimeout(myTimer);
    isOver = false;
    myTimer = setTimeout("HideDHTML()",300);
}