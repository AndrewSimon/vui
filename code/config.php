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

## Needed to get rid of useless 'undefined index' warning
if(isset($_SERVER['SERVER_NAME'])) { 
$servername=$_SERVER['SERVER_NAME'];
} else { $servername='my server'; }

/***********************************************
 * This configuration file is in 3 parts:
 * 1) The Vagrant configuration on THIS server
 *  This is optional as vagrant-ui can
 *  be run as a UI for remote hosts only
 * 2) The Web environment on THIS server:
 *  This is REQUIRED. Vagrant installs to
 *  be accessed via THIS vui also require 
 *  vui code to be installed there, as well.
 * 3) Remote URL's (comma delimited):
 *  This is optional in that you are not
 *  required to manage mutliple Vagrant
 *  Hosts through one web interface. 
 * 4) Optional local host specific configuration
 ***********************************************
*/  


### VAGRANT ENVIRONMENT ON THIS SERVER (IF ANY)

## NOTE: VAGRANT_HOME variable MUST start with HOME=
## NOTE: For NO Vagrant on this host, use blank $VAGRANT_HOME=""
## Can be any path that 1) exists, 2) owned by your web daemon, and
## 3) enough space for, or links to, web daemon readable box files
## $VAGRANT_HOME="";
$VAGRANT_HOME="HOME=/var/www";


## Path to the vagrant file, it will end up being in same 
## directory as vui index.php if you let vui initialize your environment
$VAGRANT_FILE="/var/www/vui/Vagrantfile";

### WEB ENVIRONMENT ON THIS SERVER (REQUIRED)

## The fully qualified host name or IP of THIS server. 
## Must include http:// or https://
## DO NOT INCLUDE THE VAGRANT_URI ALIAS OR PATH SPECIFIED BELOW
$VAGRANT_HOST="https://www.example.com";

## The alias or URI, if any, defined in apache/nginx. Leave off trailing slashes
$VAGRANT_URI="/vui";

## REMOTE VAGRANT URL'S FOR REMOTE SERVERS (IF ANY)

## Define the COMMA DELIMITED FULL URL ON EACH "REMOTE" HOST, AS EACH MAY DIFFER
## Include trailing slash or file name index.php if required by remote server(s).
## $REMOTE_HOSTS="https://s1.example.com/vagrant-ui/index.php,http://s2.example.com/v/"



## OPTIONAL CONFIGURABLE DATA FOR EACH LOCAL SERVER

## The page header and title. Not displayed in any 'REMOTE_HOST' block
$PAGE_TITLE="VUI - Vagrant UI on ".$servername." ";

## If present, this is visible in each REMOTE_HOST block, as well.
## This is a strictly optional file that when present, gives vui
## an extra read-only textarea showing the file's contents.
## Extra data is usually snapshot or similar data generated via cron
## to assist in configuring the Vagrantfile with recent info.

$EXTRA_DATA_FILE="/tmp/image.report";

?>
