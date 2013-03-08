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
            divmenu[i].style.visibility = "hidden";
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
            if (cMenuReady) {
                var menuAction = (navigator.userAgent.indexOf("Mac") != -1) ? "Удерживайте кнопку мыши нажатой, " : "Щелкните правой кнопкой мыши, ";
                spans[i].title = menuAction + "чтобы вызвать меню."
            } else {
                spans[i].title = "Контекстные меню доступны в других" + "браузерах (IE5+/Windows, Netscape6+).";
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

