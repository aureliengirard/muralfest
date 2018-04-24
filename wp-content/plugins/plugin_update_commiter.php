<?php
/*
Plugin Name: Plugin Update Commiter
Description: Do a git commit/push after any plugin has updated
Author: Codems
Version: 1.2
*/


/*
NOTE : Debug with 2>&1 at the end of the command to dump cmd error
EX:

echo shell_exec('git 2>&1');
die;
*/


add_action( 'upgrader_process_complete', 'PUC_upgrader_post_install_callback', 100, 2 );
add_action('deleted_plugin', 'commit_deleted_plugins', 10, 2);

function PUC_upgrader_post_install_callback( $upgrader_instance, $data ){ 
	
	$type = strtoupper( $data['type'] );
	$action = strtoupper( $data['action'].'ed' );

	if(isset($data["plugins"][0])){
		$plugin = $data["plugins"][0];
		if(strpos($plugin,"/")){
			$plugin = explode("/",$plugin);
			$plugin = $plugin[0];
		}
	}else{
		$plugin="";
	}

	commit_changes($type,$action,$plugin);
	
}

function commit_deleted_plugins($plugin, $deleted){

	if(strpos($plugin,"/")){
			$plugin = explode("/",$plugin);
			$plugin = $plugin[0];
	}else{
		$plugin="";
	}

	commit_changes('Plugin', 'DELETED',$plugin);
}


/**
 * @param $type
 * @param $action
 */
function commit_changes($type, $action,$plugin)
{

	if(is_localhost()){
		$gitPath = "git";
	}else{
		$gitPath = "/usr/local/cpanel/3rdparty/lib/path-bin/git";
	}

	
	shell_exec($gitPath.' config --local user.email "wp.site@codems.ca"');
	shell_exec($gitPath.' config --local user.name "Plugin Update Commiter"');
	
	shell_exec($gitPath.' add --all');
	shell_exec($gitPath.' commit --message="'.$type.' '.$action.' : '.$plugin.' --- (Auto-Commit)"');
	if(!is_localhost()){
		shell_exec($gitPath.' push -u origin master');
	} 
	shell_exec($gitPath.' config --local --unset user.email');
	shell_exec($gitPath.' config --local --unset user.name');


}

function is_localhost() {
	  $whitelist = array( '127.0.0.1', '::1' );
	    if( in_array( $_SERVER['REMOTE_ADDR'], $whitelist) )
	        return true;
}



?>