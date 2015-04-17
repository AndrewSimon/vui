<?php
/* 
 * VUI - A PHP front-end UI written for Vagrant
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

$action=$_GET['action']; $s=$_GET['s'];  $r=$_GET['r']; 
$code=$_POST['code'];
$s = preg_replace('/\s+/', ' ', $s);
$s=current(explode(' ',$s));

//Browser cookie auth
// include_once 'js/auth.js';

## Uncomment to allow frame/iframe access
#header('Access-Control-Allow-Origin: *'); 

if($r == "") {$r="0";}
if($action == "save") {$s="0";}

ob_start();

include_once "code/config.php";
include_once "code/base.php";
include_once "code/body.php";
include_once "code/action.php";


if($r == "0") {
pageHeader($PAGE_TITLE);
} else {
echo "";
}

pageBody($action,$s,$r);

if($r == "0") {
pageFooter();
} else {
echo "";
}

ob_flush();

?>
