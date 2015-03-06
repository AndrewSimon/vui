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

include 'code/config.php';
if(get_magic_quotes_gpc()) {
$code=stripslashes(file_get_contents("$VAGRANT_FILE"));
} else {
$code=htmlspecialchars(file_get_contents("$VAGRANT_FILE"));
}

echo "
<P>For the Vagrantfile editor for $VAGRANT_HOST, click here to 
<span id=showLink".$cnt." class=btn-blue onClick=showEditor".$cnt."(); >Show Editor</span><span id=hideLink".$cnt." class=btn-blue onClick=hideEditor".$cnt."(); style=display:none;>Hide Editor</span></P>
<div style=display:none; id=Editor".$cnt.">
<P>Click \"Save\" to save, or click 'Refresh Server List' below to discard.</P>
<BR/> 
<blockquote>
<form action=$VAGRANT_HOST$VAGRANT_URI/index.php?action=save&r=$cnt method=post>
<input type=submit name=submit value=Save >
<BR>
<textarea id=styled name=code rows=40 cols=80>$code</textarea><BR>
<input type=submit name=submit value=Save >
</form>
</blockquote>

</div>

";

?>
