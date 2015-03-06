<?php

/*
 * VUI: A PHP front-end UI written for Vagrant
 * Written by Andrew Simon, December 2014.
 * Updated by A.S. January 2015 for multiple vagrant host servers
 *
 * Copyright [2015] [Andrew Simon]
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

function pageHeader($title)
{
global $VAGRANT_HOME; $VAGRANT_HOST; global $VAGRANT_URI; global $VAGRANT_FILE;
  $ret = "";
  $ret .= "<!doctype html>
    <html>
    <head>
  <link href='styles/style.css' rel='stylesheet' type='text/css' />
  <link href='styles/progress.css' rel='stylesheet' type='text/css' />
  <script src='js/vui.js' type='text/javascript' ></script>
      <title>" . $title . "</title>";
  $ret .= "<header><div id=logo><h2>" . $title . "</h2></div></header>\n";

if($VAGRANT_HOME == "" && $REMOTE_HOSTS == "") {
echo "$ret";
} else {
    $ret .= "<div id=box><span id=warn>VUI SHOWS <b>VAGRANT WEB (e.g. apache user)</b> MANAGED GUESTS ONLY</span><br/>
Guest servers managed by other users on these VM HOSTS are <b>not</b> visible here</div>\n";
  echo $ret;
  echo "<p class=in><a class=btn-blue onClick=\"showProgress0()\" href=$VAGRANT_HOST$VAGRANT_URI/index.php >Refresh Server List</a></p>";
  echo "<span class=in style=width:300px;></span><form class=in name=df><input id=df type=text name=field value='' size=11></form>";
  if($VAGRANT_HOME !== "" && isset($VAGRANT_HOME)) {
  echo "<P><div id=box><span id=warn>Local Vagrant Host <b>$VAGRANT_HOST</b> listed below</div></span></P><BR/>";
	}
 }
}

function linkList($cnt) {
if($cnt < 999) {
global $VAGRANT_HOST; global $VAGRANT_URI;
$s=$_SERVER['HTTP_HOST'];
    $ret = "";
    $ret .= "<select onChange=\"ConfirmAction('$s',this.value);\">";
    $ret .= "<option style=text:bold>Select here for more options on $s</option>";
    $ret .= "<option target=$s value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=list_boxes&r=999>List Boxes</option>";
    $ret .= "<option target=$s value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=plugin_list&r=999>List Plugins</option>";
    $ret .= "<option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=kill&r=999>Kill Vagrant Tasks</option>";
// Build box list from glob of home dir
    $home = getenv('HOME');
    $ret .= "<optgroup label='Add box file (.box files copied to $home will appear here)'>";
    foreach (glob("$home/*.box") as $boxfile) {
    $ret .= "<option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=box_add&s=$boxfile>$boxfile</option>".PHP_EOL;
    }
    $ret .= "</optgroup>";

// Build plugin list from file
    $ret .= "<optgroup label='Install Vagrant Plugin (reinstalls or upgrades if already installed)'>";

$file_array = file("code/plugin_list");
foreach ($file_array as $plugin)
  $ret .= "<option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=plugin_reinstall&s=$plugin>$plugin plugin</option>".PHP_EOL;

    $ret .= "</optgroup>";
    $ret .= "</select></div>";

    echo $ret;
 } else {
  echo "<script>document.getElementById('progress999').style.display = 'none';</script>";
  echo "</div><BR/><span style=margin-left:40% id=link class=btn onClick=\"window.close();\" href=#>Close this pop-up</a></span><BR/><BR/>";
 } //End if $cnt < 999 
    $ret = "";
    $ret .= "<script>\n
    function hideProgress".$cnt."(){
	document.getElementById('progress".$cnt."').style.display = 'none';
   }
    function showProgress".$cnt."() {
        document.getElementById('progress".$cnt."').style.display = 'block';
   }
function goBack()
  {

  window.history.back()
  }

function showEditor".$cnt."()
  {
  document.getElementById('Editor".$cnt."').style.display = 'block';
  document.getElementById('showLink".$cnt."').style.display = 'none';
  document.getElementById('hideLink".$cnt."').style.display = 'inline';
 }

function hideEditor".$cnt."()
  {
  document.getElementById('Editor".$cnt."').style.display = 'none';
  document.getElementById('showLink".$cnt."').style.display = 'inline';
  document.getElementById('hideLink".$cnt."').style.display = 'none';
  }

   hideProgress".$cnt."();

    </script>\n
    ";
  echo $ret;
}

function pageBody($action,$s,$cnt)
{
global $VAGRANT_HOME; global $VAGRANT_HOST; 
global $VAGRANT_FILE; global $VAGRANT_URI; 
  $ret = "";
  $ret .= "<link href='styles/style.css' rel='stylesheet' type='text/css' />
           <link href='styles/progress.css' rel='stylesheet' type='text/css' />
           <script src='js/vui.js' type='text/javascript' ></script>
    </head>
    <body>\n";
if($VAGRANT_HOME !== "" && isset($VAGRANT_HOME) && file_exists($VAGRANT_FILE)) {
    $ret .= "<div id=box><table>\n";
    $ret .= "<tr id=table-header><th id=th1>Virtual Machine Name</th><th id=th2>Action</th><th id=th3> Status</th></tr>\n"; 
   echo $ret;
}
## THIS LOCAL VAGRANT SERVER
if($VAGRANT_HOME === "" || !isset($VAGRANT_HOME) || !file_exists($VAGRANT_FILE)) {
  echo "";
} else {
	action($action,$s,$cnt);
        include 'code/edit.php';
        include 'code/data.php';
	linkList($cnt);
}

## REMOTE VAGRANT SERVERS
global $REMOTE_HOSTS; 
## Action on the local server, if defined
## If $cnt > 0 - remote servers don't manage remotes
if($REMOTE_HOSTS == "" || $cnt > 0) {
	echo "";
} else {
  $servers=explode(",",$REMOTE_HOSTS);
  foreach ($servers as $h) {
  $cnt++;
  echo "</div><P><div id=box><span id=warn>Remote Vagrant Host ($cnt) <b>$h</b> listed below</div></span></P>
  <BR/><div id=rbox$cnt>Fetching remote data... please wait</div>";
  $h=$h . '?r='.$cnt;
// echo "<iframe src=$h style=width:100%;height:420px;border:none ></iframe>";
// Via AJAX
 echo "<script>
 function loadXMLDoc".$cnt."(url)
 {
 var x;
 if (window.XMLHttpRequest)
  {
  	x=new XMLHttpRequest();
  }
  x.onreadystatechange=function()
  {
  if (x.readyState < 4) {
    var p = '<div id=progress".$cnt." class=progress ><div> </div></div>';
    document.getElementById('rbox".$cnt."').innerHTML = 'Loading data, please wait<BR/>' + p;

  }
  if (x.readyState==4)
    {
    r=x.responseText;
    document.getElementById('rbox".$cnt."').innerHTML=r;
    document.getElementById('progress".$cnt."').style.display = 'none';
	/* alert(r); */
    }
  }
 x.open(\"GET\",url,true);
 x.send();
 }
function showEditor".$cnt."()
  {
  document.getElementById('Editor".$cnt."').style.display = 'block';
  document.getElementById('showLink".$cnt."').style.display = 'none';
  document.getElementById('hideLink".$cnt."').style.display = 'inline';
 }
function hideEditor".$cnt."()
  {
  document.getElementById('Editor".$cnt."').style.display = 'none';
  document.getElementById('showLink".$cnt."').style.display = 'inline';
  document.getElementById('hideLink".$cnt."').style.display = 'none';
  }

 loadXMLDoc".$cnt."('".$h."');
 </script>
 ";
  }
 }


### This runs a setup wizard because VAGRANT_HOME AND REMOTE_HOSTS are both empty
if ($VAGRANT_HOME != "" && !file_exists($VAGRANT_FILE)) {
        if($action=="initialize") {
		echo "<div id=box>";
		if(action($action,$s,$cnt)) {
			echo "";
		} else {
		echo "
		<div id=box>
<style> #progress0 { display: none; }</style>
<P>Fix the file path or permission problem above and try again.<P><span id=warn>If you just fixed it and/or the message shows it is resolved, click <a id=btn-blue href=$VAGRANT_HOST$VAGRANT_URI/index.php> NEXT </a></span>
";
			pageFooter();
			exit;
		}
	 }

  echo "<div id=box><span id=warn>You have defined a local VAGRANT_$VAGRANT_HOME, but the Vagrantfile does not exist in that path.</span>
<P>If you haven't installed Vagrant on this server but intend to, you will have to install it first before using VUI.</P> 
Once installed, or if Vagrant is already installed, VUI can initialize a new Vagrant environment for you. 
<P>If the VAGRANT_HOME path is correct, you MUST ensure that it is writeable for your web daemon id (e.g. apache, www-data or nginx) before proceeding.</P> 
<BR/><span id=warn>Click Initialize button to initialize $VAGRANT_HOME as your VAGRANT_HOME.</span>
 <p><a class=btn-blue href=$VAGRANT_HOST$VAGRANT_URI/index.php?action=initialize >Initialize Vagrant Environment</a></p>
</div>";
 }



###### END SETUP WIZARD
}

function pageFooter($file = null)
{
global $VAGRANT_HOST; global $VAGRANT_URI; global $VAGRANT_HOME; global $REMOTE_HOSTS; global $VAGRANT_FILE;
if(file_exists($VAGRANT_FILE) && $VAGRANT_HOME != "" || $REMOTE_HOSTS != "") {
echo "<p><a class=btn-blue onClick=\"showProgress0()\" href=$VAGRANT_HOST$VAGRANT_URI/index.php >Refresh Server List</a></p>";
echo "<script>window.onload = digiclock;</script>";
}
echo "<BR/><BR/>&copy; Copyright 2015. Written by Andrew Simon.";
echo "<BR/></body></html>";
}
