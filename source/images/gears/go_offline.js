// Copyright 2007, Google Inc.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are met:
//
//  1. Redistributions of source code must retain the above copyright notice, 
//     this list of conditions and the following disclaimer.
//  2. Redistributions in binary form must reproduce the above copyright notice,
//     this list of conditions and the following disclaimer in the documentation
//     and/or other materials provided with the distribution.
//  3. Neither the name of Google Inc. nor the names of its contributors may be
//     used to endorse or promote products derived from this software without
//     specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
// WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF 
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO
// EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
// PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
// OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
// WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR 
// OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF 
// ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

// Change this to set the name of the managed resource store to create.
// You use the name with the createManagedStore, and removeManagedStore,
// and openManagedStore APIs. It isn't visible to the user.
var STORE_NAME = "my_offline_docset";

// Change this to set the URL of tha manifest file, which describe which
// URLs to capture. It can be relative to the current page, or an
// absolute URL.

var localServer;
var store;

// Called onload to initialize local server and store variables
function GGearsInit() {
  if (window.google && google.gears)
  {
    localServer = google.gears.factory.create("beta.localserver");
    store = localServer.createManagedStore(STORE_NAME);
    textOut("Google Gears установленны - можно кэшировать!");
  }
  else
  {
//    textOut("Сначала нужно установить <a target=\"_blank\" href=\"http://gears.google.com/\">Google Gears</a>.");
  }
}

// Create the managed resource store
function GGearsCreateStore() {
  if (!window.google || !google.gears) {
    alert("Сначала нужно установить Google Gears.");
    return;
  }

  textOut("Кэшуруем... Это может занять несколько минут.");

  store.manifestUrl = MANIFEST_FILENAME;
  store.checkForUpdate();

  var timerId = window.setInterval(function() {
    // When the currentVersion property has a value, all of the resources
    // listed in the manifest file for that version are captured. There is
    // an open bug to surface this state change as an event.
    if (store.currentVersion) {
      window.clearInterval(timerId);
      textOut("Кэширование завершено. Версия: " + store.currentVersion);
    } else if (store.updateStatus == 3) {
      textOut("Ошибка: " + store.lastErrorMessage);
    }
  }, 500);  
}

// Remove the managed resource store.
function GGearsRemoveStore() {
  if (!window.google || !google.gears) {
    alert("You must install Gears first.");
    return;
  }

  textOut("Очищаем кэш...");

  localServer.removeManagedStore(STORE_NAME);
  textOut("Очищено!");
}

// Utility function to output some status text.
function textOut(s) {
 var elm = document.getElementById("textOut");
  while (elm.firstChild) {
    elm.removeChild(elm.firstChild);
  } 
  elm.appendChild(document.createTextNode(s));
}
