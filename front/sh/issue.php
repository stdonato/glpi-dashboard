<?php

//header('Content-Type: application/json; charset=UTF-8');

if(file_exists('/usr/bin/lsb_release')) {
	echo substr(shell_exec('/usr/bin/lsb_release -ds'),0,21); //ubuntu - debian
	}

elseif(file_exists('/etc/SuSE-release')) {
	echo substr(shell_exec('head -1 /etc/SuSE-release'),0,22);  //opensuse
  }
  
elseif(file_exists('/etc/redhat-release')) {
	echo substr(shell_exec('head -1 /etc/redhat-release'),0,21);  //redhat - centOS
  }

else {
	echo "Linux";
	}  
  
