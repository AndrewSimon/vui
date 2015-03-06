//
//* vui.js - a javascript backend for VUI 
//* Written by Andrew Simon, December 2014.
// * Updated by A.S. February 2015
// *
// * Copyright [2015] [Andrew Simon]
// *
// * Licensed under the Apache License, Version 2.0 (the "License");
// * you may not use this file except in compliance with the License.
// * You may obtain a copy of the License at
// *
// *   http://www.apache.org/licenses/LICENSE-2.0
// *
// * Unless required by applicable law or agreed to in writing, software
// * distributed under the License is distributed on an "AS IS" BASIS,
// * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// * See the License for the specific language governing permissions and
// * limitations under the License.
//*/

function getQueryVar(v,url) {
    var queryStr = url + '&';
    var regex = new RegExp('.*?[&\\?]' + v + '=(.*?)&.*');
    val = queryStr.replace(regex, "$1");
    // If the string is the same, we didn't find a match - return false
    return val == queryStr ? false : val;
}

function ConfirmAction(s,url) {
        var action = getQueryVar('action',url)
        var target  = getQueryVar('s',url)
        var msg = action;
        var h = new URL(url);
        var host = h.hostname;
        if(msg=='plugin_list') msg='a plugin listing';
        if(msg=='list_boxes') msg='a box listing';
        if(msg=='box_add') msg='add '+target+' box file';
        if(msg=='kill') msg='kill hung vagrant tasks';
        if(msg=='provision') msg='provision '+target ;
        if(msg=='start') msg='start '+target ;
        if(msg=='ssh-config') msg='ssh-config to get details about '+target ;
        if(msg=='status') msg='status to get details about '+target;
        if(msg=='plugin_reinstall') msg='install, re-install and/or upgrade '+target+' plugin, it can take 5 minutes or more';
        if(msg=='install_vagrant') msg='install vagrant using '+target;
        if(msg=='cleanup_vb') msg='delete orphaned VirtualBox VM files for ';

/* Start here with those action you don't wish to prompt */
if(action=='list_boxes' || action=='plugin_list' || action=='ssh-config' || action=='status' ) {
newwindow=window.open('', s, 'toolbar=yes, scrollbars=yes, resizable=yes, top=120, left=200, width=800, height=860');
newdocument=newwindow.document;
newdocument.write('<link href=\'styles/progress.css\' rel=\'stylesheet\' type=\'text/css\' /> <link href=\'styles/progress.css\' rel=\'stylesheet\' type=\'text/css\' /><span id=warn>Running '+msg+' on '+host+', please wait...<div id=progress$cnt class=progress ><div> </div></div>');
window.open(url, s, 'toolbar=yes, scrollbars=yes, resizable=yes, top=120, left=200, width=800, height=860');

} else if(action == false) {
                ;
        } else {
        var r = confirm('Are you sure you want to '+msg+' on '+host+'?\n\nClick OK to continue, or Cancel to abort');
        if (r == true) {
newwindow=window.open('', s, 'toolbar=yes, scrollbars=yes, resizable=yes, top=120, left=200, width=800, height=860');
newdocument=newwindow.document;
newdocument.write('<link href=\'styles/progress.css\' rel=\'stylesheet\' type=\'text/css\' /> <link href=\'styles/progress.css\' rel=\'stylesheet\' type=\'text/css\' /><span id=warn>Running '+msg+' on '+host+', please wait...<div id=progress$cnt class=progress ><div> </div></div>');
window.open(url, s, 'toolbar=yes, scrollbars=yes, resizable=yes, top=120, left=200, width=800, height=860');
                } else {
                alert(action+' on '+s+' canceled!');
                }
        }
}

function digiclock() {
  n=new Date();
  hour=n.getHours();
  min=n.getMinutes();
  sec=n.getSeconds();

if (min<=9) { min='0'+min; }
if (sec<=9) { sec='0'+sec; }
if (hour>12) { hour=hour-12; add='pm'; }
else { hour=hour; add='am'; }
if (hour==12) { add='pm'; }

document.df.field.value = ((hour<=9) ? '0'+hour : hour) + ':' + min + ':' + sec + ' ' + add;

setTimeout('digiclock()', 1000);
}

function pickOS() {
	document.getElementById('pickOS').style.display = "block";
}
