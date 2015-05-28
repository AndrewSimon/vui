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

function runCode($vcmd,$cnt)
{
  global $VAGRANT_HOME;
  global $VAGRANT_URI;
  global $VAGRANT_HOST;
  $ret = "";
/* ALL ACTIONS GET SIMPLE BODY IN SEPARATE WINDOW */
  $cnt=999;
/* COMMENT ABOVE LINE AND LINES IN ConfirmAction TO CHANGE BEHAVIOR */
  putenv($VAGRANT_HOME);
  exec($vcmd, $data);
  foreach($data as $line) {
        if (trim($line) === "Current machine states:") { echo ""; }
        if (stripos($line,"o usable default provider")) {

        echo "<style> #table-header { display:none; }</style>
	<span id=warn>No usable default provider could be found for your system.
	<P>If not using Virtualbox, please select 
	from the long list of provider plugins at the bottom of this page.
	<P>For example, if the provider is Amazon EC2, install the 'vagrant-aws' plugin

</span><BR/> ";
	
	} elseif (stripos($line,"VERR_ALREADY_EXISTS")) {
        echo "<p><span id=warn><b><font=+1>Select 'Cleanup', 
		then select 'Destroy' to fix the error below</font></b></span></p>";
        } elseif (strpos($line,"not created") && !strpos($line," is not created")) {
	// Removes extra spaces
	$s = preg_replace('/\s+/', ' ', $line);
	$s=current(explode(' ',$s));
	preg_match('#\((.*?)\)#', $line, $provider);
	$provider=$provider[1];
//	echo "<script>alert('".$provider."')</script>";
        echo "<tr><td style='border:1px solid #DDD';>
	<img src=images/serverdown.png alt=not_created height=42 width=42>&nbsp;&nbsp;" . $line . "</td>
	<td style='border:1px solid #DDD';> 
	<a class=btn onClick=\"ConfirmAction('$s','$VAGRANT_HOST$VAGRANT_URI/index.php?action=start&provider=$provider&s=$s&r=$cnt');\"> Start </a></td>
	<td style='border:1px solid #DDD';> <a class=btn-blue onClick=\"ConfirmAction('$s','$VAGRANT_HOST$VAGRANT_URI/index.php?action=status&provider=$provider&s=$s&r=999');\">  Details</a></td></tr>\n";
        } elseif (strpos($line,"To stop this")) {
        echo "<tr><td>The instance is running. To stop and destroy this machine, click `Action` button and select the `Destroy` option. </td><td></td><td></td></tr>\n";
        } elseif (empty($line)) {
        echo "";
        } elseif (strpos($line,"Running provisioner")) {
        echo "<style> #th1 { display:none; }</style>";
        echo "<tr><th>Running provisioner, results below:</th></tr>\n";
        } elseif (strpos($line,"SSH Configuration")) {
        echo "<style> #th1 { display:none; }</style>";
        echo "<tr><th>SSH configuration below:</th></tr>\n";
        } elseif (strpos($line,"vagrant halt")) {
        echo "<tr><td colspan=3>To halt or stop, click `Action` button and select the `Suspend` option.</td></tr>\n";
        } elseif (strpos($line,"un `vagrant up`")) {
	$line=str_ireplace("created.","created,",$line);
	$line=str_ireplace("run `vagrant up`","select `Start`",$line);
 echo "<tr><td colspan=3>$line </td></tr>\n";
        } elseif (strpos($line,"vagrant status NAME")) {
        echo "<tr><td colspan=3>VM, click the `Details` link.</td></tr>\n";
  	} elseif (strpos($line,"poweroff (") || strpos($line,"running (") || strpos($line,"stopp") || strpos($line,"inaccessible (")) {
		$s = preg_replace('/\s+/', ' ', $line);
		$s=current(explode(' ',$s));
		if (strpos($line,"stopp")||strpos($line,"poweroff"))  {
		preg_match('#\((.*?)\)#', $line, $provider);
		$provider=$provider[1];
               echo "<tr><td style='border:1px solid #DDD';>
		<img src=images/serverdown.png alt=not_created height=42 width=42>&nbsp;&nbsp;" . $line . "</td>";
	       echo "
               <td style='border:1px solid #DDD';> 
                <select onChange=\"ConfirmAction('$s',this.value);\">
                <option>Action</option>";
                echo "<option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=start&provider=$provider&s=$s&r=$cnt >Start</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=reload&s=$s&r=$cnt >Reload</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=destroy&s=$s&r=$cnt >Destroy</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=cleanup_$provider&s=$s&r=$cnt >Cleanup</option>";
		} elseif (strpos($line,"inaccessible")) {
		preg_match('#\((.*?)\)#', $line, $provider);
		$provider=$provider[1];
                echo "<tr><td style='border:1px solid #DDD';>
		<img src=images/serverdown.png alt=not_created height=42 width=42>&nbsp;&nbsp;" . $line . "</td>";
	        echo "
                <td style='border:1px solid #DDD';> 
                <select onChange=\"ConfirmAction('$s',this.value);\">
                <option >Action</option>";
                echo "<option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=cleanup_$provider&s=$s&r=$cnt >Cleanup</option>";
        	} elseif (stripos($line,"take a few minutes")) { echo "$line<BR/>"; 
		} else {
		preg_match('#\((.*?)\)#', $line, $provider);
               echo "<tr><td style='border:1px solid #DDD';>
		<img src=images/serverup.png alt=not_created height=42 width=42>&nbsp;&nbsp;" . $line . "</td>";
	       echo "
               <td style='border:1px solid #DDD';> 
                <select onChange=\"ConfirmAction('$s',this.value);\">
                <option >Action</option>";
                echo "<option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=destroy&s=$s&r=$cnt >Destroy</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=stop&s=$s&r=$cnt >Suspend</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=provision&s=$s&r=$cnt >Provision</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=push&s=$s&r=$cnt >Push</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=reload&s=$s&r=$cnt >Reload</option>
                <option value=$VAGRANT_HOST$VAGRANT_URI/index.php?action=package&s=$s&r=$cnt >Package</option>
                </select>";
		}
	echo "
        </td>
        <td style='border:1px solid #DDD';>  
	        <a class=btn-blue onClick=\"ConfirmAction('$s','$VAGRANT_HOST$VAGRANT_URI/index.php?action=ssh-config&s=$s&r=999');\">  Details</a>
        </td></tr>\n"; } else {
        echo "<tr><td colspan=3>" . $line . "</td></tr>\n";
        }
  }
  $ret .= "</table></div>";
  echo $ret;

}
?>
