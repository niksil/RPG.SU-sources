function insertAtCaret (textObj, textFieldValue) 
{
    if(document.all)
    {
        if (textObj.createTextRange && textObj.caretPos && !window.opera) 
        {
            var caretPos = textObj.caretPos;
            caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ?textFieldValue + ' ' : textFieldValue;
        }
        else
        {
            textObj.value += textFieldValue;
        }
    }
    else
    {
        if(textObj.selectionStart)
        {
            var rangeStart = textObj.selectionStart;
            var rangeEnd = textObj.selectionEnd;
            var tempStr1 = textObj.value.substring(0,rangeStart);
            var tempStr2 = textObj.value.substring(rangeEnd, textObj.value.length);
            textObj.value = tempStr1 + textFieldValue + tempStr2;
            textObj.selectionStart=textObj.selectionEnd=rangeStart+textFieldValue.length;
        }
        else
        {
            textObj.value+=textFieldValue;
        }
    }
}
function priv(name)
{
    var oMessage = top.window.frames.chat.document.getElementById('chat_mess');
    var oToMessage = top.window.frames.chat.document.getElementById('too');
    oMessage.focus();
    if (name=='') name='Всем';
    if (name.length>10)
        oToMessage.size=name.length;
    else
        oToMessage.size=10;
    oToMessage.value=name;
}
function cha(name,par)
{
    var oMessage = top.window.frames.chat.document.getElementById('chat_mess');
    var oToMessage = top.window.frames.chat.document.getElementById('too');
    oMessage.focus();
    if (par==1)
    {
        oMessage.value=name;
    }
    else
    {
        insertAtCaret(oMessage,' '+name+', ');
    }
    oToMessage.value='Всем';
    oToMessage.size=10;
}
function showContextMenu(evt) {
    hideContextMenus();
    evt = (evt) ? evt : ((event) ? event : null);
    if (evt) {
        var elem = (evt.target) ? evt.target : evt.srcElement;
        if (elem.nodeType == 3) {
            elem = elem.parentNode;
        }
        if (elem.className == "contextEntry") {
            var menu = document.getElementById("contextMenu"+elem.id);
            if (menu.setCapture) {
                menu.setCapture();
            }
            var left, top;
            if (evt.pageX) {
                left = evt.pageX;
                top = evt.pageY;
            } else if (evt.offsetX || evt.offsetY) {
                left = evt.offsetX;
                top = evt.offsetY;
            } else if (evt.clientX) {
                left = evt.clientX;
                top = evt.clientY;
            }
            menu.style.left = left + "px";
            menu.style.top = top + "px";
            menu.style.display = "block";
            if (evt.preventDefault) {
                evt.preventDefault();
            }
            evt.returnValue = false;
        }
    }
}
function getHref(tdElem) {
    var div = tdElem.parentNode.parentNode.parentNode.parentNode;
    var index = tdElem.parentNode.rowIndex;
    for (var i in cMenu) {
        if (cMenu[i].menuID = div.id) {
            return cMenu[i].hrefs[index];
        }
    }
    return "";
}
function execMenu(evt) {
    evt = (evt) ? evt : ((event) ? event : null);
    if (evt) {
        var elem = (evt.target) ? evt.target : evt.srcElement;
        if (elem.nodeType==3) {
            elem = elem.parentNode;
        }
        if (elem.className == "menuItemOn") {
            //location.href = getHref(elem);
        }
        hideContextMenus();
    }
}
function hideContextMenus() {
    if (document.releaseCapture) {
        document.releaseCapture();
    }
    var divmenu = document.body.getElementsByTagName("div");
    for (var i = 0; i < divmenu.length; i++) {
        if (divmenu[i].className.indexOf("contextMenu") == 0) {
            divmenu[i].style.display = "none";
        }
    }
}
function toggleHighlight(evt) {
    evt = (evt) ? evt : ((event) ? event : null);
    if (evt) {
        var elem = (evt.target) ? evt.target : evt.srcElement;
        if (elem.nodeType==3) {
            elem = elem.parentNode;
        }
        if (elem.className.indexOf("menuitem") != -1) {
            elem.className = (evt.type == "mouseover") ? "menuItemOn" : "menuItem";
        }
    }
}
function setContextTitles() {
    var cMenuReady = (document.body.addEventListener || typeof document.oncontextmenu != "undefined")
    var spans = document.body.getElementsByTagName("span");
    for (var i = 0; i < spans.length; i++) {
        if (spans[i].className == "contextEntry") {
            if (cMenuReady && !window.opera) {
                var menuAction = (navigator.userAgent.indexOf("Mac") != -1) ? "Удерживайте кнопку мыши нажатой, " : "Щелкните правой кнопкой мыши, ";
                spans[i].title = menuAction + "чтобы вызвать меню."
            } else {
                spans[i].title = "Контекстные меню доступны в других" + " браузерах (IE5+/Windows, Mozilla1.0+).";
                spans[i].style.cursor = "default";
            }
        }
    }
}
function initContextMenus() {
    if (document.body.addEventListener) {
        document.body.addEventListener("contextmenu", showContextMenu, true);
        document.body.addEventListener("click", hideContextMenus, true);
    } else {
        document.body.oncontextmenu = showContextMenu;
    }
    setContextTitles();
}