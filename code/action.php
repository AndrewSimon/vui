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

if(isset($_GET['action'])) { $action=$_GET['action']; }
if(isset($_GET['code'])) { $code=$_POST['code']; }

function action($action,$target,$cnt) {
global $VAGRANT_HOME; global $VAGRANT_PROVIDER; 
global $VAGRANT_FILE; global $VAGRANT_HOST; global $code;
$target=strtok($target, " ");
$host=$_SERVER['HTTP_HOST'];

echo "<div id=progress$cnt class=progress ><div> </div></div>";

if($action=="save") {
	 echo "<style> #table-header { display:none; }</style>";
	if($code=="") {
		runCode("/bin/echo \"<h3>ERROR!  Cannot save empty Vagrantfile</h3>\"",$cnt);
	} else {
	file_put_contents($VAGRANT_FILE, $code);
	 	runCode("/bin/echo \"<h3>Saved to $VAGRANT_FILE on $VAGRANT_HOST</h3>\"",$cnt);
	}
}
if($action=="kill") {
	echo "<h3>Killing hung vagrant processes </h3><br/>Additional information below";
	runCode("ps -ef|grep vagrant |grep -v grep | xargs /bin/kill ",$cnt);
	}
if($action=="start") {
	echo "<h3>Starting vm named: $target... </h3><br/>Additional information below";
	runCode("/usr/bin/vagrant up $target --provider $VAGRANT_PROVIDER 2>&1",$cnt);
	}
if($action=="stop") {
	echo "<h3>Stopping $target... </h3>";
	runCode("/usr/bin/vagrant halt -f $target 2>&1",$cnt);
	}
if($action=="destroy") {
	echo "<h3>Destroying $target... </h3>";
	runCode("/usr/bin/vagrant destroy -f $target 2>&1",$cnt);
	}
if($action=="list_boxes") {
	runCode("/usr/bin/vagrant box list 2>&1",$cnt);
	}
if($action=="") {
/* 	Run this for DEBUG
 *	runCode("/usr/bin/vagrant --debug status 2>&1");
*/
 	runCode("/usr/bin/vagrant status 2>&1",$cnt);
	}
if($action=="status") {
	runCode("/usr/bin/vagrant status $target 2>&1",$cnt);
	}
if($action=="provision") {
	runCode("/usr/bin/vagrant provision $target 2>&1",$cnt);
	}
if($action=="push") {
	runCode("/usr/bin/vagrant push $target 2>&1",$cnt);
	}
if($action=="reload") {
	runCode("/usr/bin/vagrant reload $target 2>&1",$cnt);
	}
if($action=="package") {
	runCode("/usr/bin/vagrant package --base $target --output $target --output $target.box 2>&1",$cnt);
	}
if($action=="ssh-config") {
	runCode("echo -n 'Id ' ; echo `/bin/cat ./.vagrant/machines/$target/$VAGRANT_PROVIDER/id` ; /usr/bin/vagrant ssh-config $target 2>&1",$cnt);
	}
if($action=="plugin_list") {
	runCode("/usr/bin/vagrant plugin list 2>&1");
	}
if($action=="cleanup_virtualbox") {
	$path="VirtualBox\ VMs/";
	runCode("cd $path 2>&1; rm -rf $target 2>&1; echo 'cleanup of orphaned $target files completed, please run Destroy'");
	}
if($action=="initialize") {
	runCode("/usr/bin/vagrant init 2>&1");
	}
if($action=="box_add") {
		$boxname=pathinfo($target, PATHINFO_FILENAME);
		runCode("/usr/bin/vagrant box add $target --name $boxname --force 2>&1");
	}
if($action=="plugin_reinstall") {
	exec("/usr/bin/vagrant plugin list | grep $target >/dev/null",$output);
	if($output!="") {
		runCode("/usr/bin/vagrant plugin uninstall $target 2>&1");
	}
	runCode("sleep 3");
	runCode("/usr/bin/vagrant plugin install $target 2>&1");
	}
}

?>
