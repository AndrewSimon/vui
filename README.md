# Vui

PHP based user interface for Hashicorp's Vagrant API system

## VUI Install Instructions

1. Change directory to the location you want vui installed.

2. Using the git command-line:

```
git clone https://github.com/AndrewSimon/vui
```

## Vagrant Install Instructions:

1. Install vagrant and plugin dependencies
- centos/rhl: yum install  ruby-devel.x86_64 
  -OR- ubuntu/debian: apt-get install ruby1.9.1-dev
2. Install Vagrant
-Download the package for you OS from: 
	https://www.vagrantup.com/downloads
- Install the .rpm or .deb, as appropriate

## Configuring Vagrant UI (VUI)

### Apache Instructions:

- You can create a VirtualHost with a new host name and DocRoot
- OR 
- You can create an alias under an existing VirtualHost and Docroot

Below are (example) instructions for an alias in Apache


1) Add an alias for /var/www/vui under an existing VirtualHost definition

- Edit httpd.conf
```
####### Example #############
Alias /vui/ "/var/www/vui/"

<Directory "/var/www/vui/">
    DirectoryIndex index.php
    Options Indexes FollowSymLinks
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>
####### End Example #########
```

2) Configure apache, nginx or www-data user write access to it's user home directory
- For example, if your web daemon id is 'apache' and it's home as specfied in /etc/passwd file is /var/www, as root run ```chown root:apache /var/www && chmod 775 /var/www```

3) Enable write access to the VAGRANT_HOME and ALL of the installed files
- For example, if your web id is 'apache' and VAGRANT_HOME is /var/www/vui, as root, run ```chown -R apache:apache /var/www/vui && chmod -R 700 /var/www/vui```

4) Increase the PHP max execution time (to maybe 10 minutes)
 - Edit /etc/php.ini and set max_execution_time = 600

5) Increase the apache or nginx connection timeout to 600, as well.

6) For MULTIPLE VAGRANT HOSTS (NOT GUESTS) ONLY
- Install on each vagrant 'remote host', as steps 1-5 above
- Configure the $REMOTE_HOSTS field on THIS server with each URL, comma delimited

7) Secure vui using Basic Auth, if desired. Default .htaccess and .htpasswd provided.

### VUI Configuration Instructions:

#### Edit the vui/code/config.php configuration file
- Follow the instructions in the configuration file.
- Vagrant will be run from wherever you define the VAGRANT_HOME directory to be.
- Typically, you set the VAGRANT_HOME to what is defined for the web account id home as specified in /etc/passwd but it can be any path with sufficient space and appropriate directory permission. 

#### Modify Directory Permission on the vui install path AND the VAGRANT_HOME path
- Set permission on VAGRANT_HOME directory and the VAGRANT_URI actual path (not the alias) for the web account to own them, with read, write and execute access. 
- Allocate lots of space to the VAGRANT_HOME directory! Box files and drive images can gobble up quite a huge amount of disk space.

#### Initialize your VUI environment and install any plugins (if needed).
- In your web browser, go to the VUI URL you defined
- VUI will guide you through initializing the environment
- Click 'Initialize Environment'.  If VUI reports errors, correct them.  Most will be config.php or permission related
- If you just modified a directory permission or edited config.php, refresh the browser instead of clicking 'Next'
- If prompted, correct new problems or click 'Next; otherwise initializing the environment is done.
- Once done, you do not need to have boxes added, just use a HashiCorp URL for a box
- When performing any Vagrant tasks on command-line, ONLY USE THE WEB DAEMON ACCOUNT ID
- The option to add boxes locally via VUI is in progress
- Vagrant ships with virtualbox, docker and hyperv providers. Many other providers are available via plugin download.  Download of (most) plugins and installation is handled through VUI
- VMWare and other providers are not free, and will require a gem or plugin install, a license, and possible other steps. Follow the 'Trouble-shooting' steps below on how to do run command-line steps outside of Vagrant UI (VUI).
- Similarly, box files must be added by hand (for now), see 'Trouble-Shooting' guide on how to run commands as the Web Daemon user.

### Running command-line Vagrant options and Trouble-shooting as the Web Daemon account id

- Temporarily, change the web daemon account id shell to /bin/bash
- To add a box file, download the box file, change permission on it, as root where ```<id>``` is the web daemon id,  run ```su - <id> -c "cd /path/to/vagrant/home && vagrant box add /path/to/boxfile --name name_of_box_file"```
- For other vagrant commands as the web daemon, run ```su - <id> -c "cd /path/to/vagrant/home && vagrant <command> <opts>```
- For basic environment trouble-shooting, run: ```su - <id> -c "cd /path/to/vagrant/home && vagrant"```
- The output may describe missing dependencies or files, or may prompt for actions
- If prompted, it may want to 'rebuild' the vagrant environment. Answer "Yes" to do so
- Once your environment is OKAY, vagrant may still issue messages which appear on command line regarding Vagrantfile, provider, provisioner, box file, etc.  Warnings and errors of this type will also appear in VUI. 
- Many/most non-environment errors can be remedied in changes to the Vagrantfile (e.g. specifying a different provider).
- Run ```vagrant help``` as any id to see commands related to box files, providers, plugins, etc.
- Once completed, change web daemon account id back to the original shell (e.g. /sbin/nologin)

## Maintainers

AndrewSimon

### Copyright and license

Copyright 2015, Andrew Simon (asimon@asimon.net)

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
