<?php

function plugin_init_dashboard() {		
  
   global $PLUGIN_HOOKS, $LANG ;
       
   Plugin::registerClass('PluginDashboardConfig', [
      'addtabon' => ['Entity']
   ]);

	 $PLUGIN_HOOKS['config_page']['dashboard'] = '../../front/config.form.php?forcetab=PluginDashboardConfig$1';
   
    $PLUGIN_HOOKS['csrf_compliant']['dashboard'] = true;   
    $PLUGIN_HOOKS["menu_toadd"]['dashboard'] = array('plugins'  => 'PluginDashboardConfig');
    $PLUGIN_HOOKS['config_page']['dashboard'] = 'front/index.php';
}


function plugin_version_dashboard(){
	global $DB, $LANG;

	return array('name'			=> __('Dashboard','dashboard'),
					'version' 			=> '0.8.3',
					'author'			   => '<a href="mailto:stevenesdonato@gmail.com"> Stevenes Donato </b> </a>',
					'license'		 	=> 'GPLv2+',
					'homepage'			=> 'https://forge.glpi-project.org/projects/dashboard',
					'minGlpiVersion'	=> '0.90'
					);
}

function plugin_dashboard_check_prerequisites(){
        if (GLPI_VERSION>=0.90){
                return true;
        } else {
                echo "GLPI version NOT compatible. Requires GLPI 0.90";
        }
}


function plugin_dashboard_check_config($verbose=false){
	if ($verbose) {
		echo 'Installed / not configured';
	}
	return true;
}


?>
