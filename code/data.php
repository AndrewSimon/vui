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

include_once './code/config.php';
$filename = "$EXTRA_DATA_FILE";

echo "<div id=box>";
if (file_exists($filename)) {
$data=htmlspecialchars(file_get_contents($filename));
echo "
<p>Extra data found for ".$_SERVER['SERVER_NAME'].", click here to
<span id=showData".$cnt." class=btn onClick='showData".$cnt."()'; >Show Data</span><span id=hideData".$cnt." class=btn onClick='hideData".$cnt."()'; style=display:none;>Hide Data</span></p>
<div style=display:none; id=data".$cnt.">
<BR/>
<blockquote>
<textarea id=styled name=data".$cnt." rows=10 cols=60>$data</textarea><BR>
</blockquote>

</div>

<script>
function showData".$cnt."() {
document.getElementById('data".$cnt."').style.display='inline-block';
document.getElementById('hideData".$cnt."').style.display='inline-block';
document.getElementById('showData".$cnt."').style.display='none';
}
function hideData".$cnt."() {
document.getElementById('data".$cnt."').style.display='none';
document.getElementById('hideData".$cnt."').style.display='none';
document.getElementById('showData".$cnt."').style.display='inline-block';
}
</script>
";

}
 
?>
