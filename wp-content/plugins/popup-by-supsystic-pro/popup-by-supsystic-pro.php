<?php
/**
 * Plugin Name: PopUp by Supsystic PRO
 * Description: PopUp by Supsystic PRO version.
 * Plugin URI: https://supsystic.com/
 * Author: supsystic.com
 * Author URI: https://supsystic.com/
 * Version: 1.7.7
 **/
	require_once(dirname(__FILE__). DIRECTORY_SEPARATOR. 'wpUpdater.php');
	
	register_activation_hook(__FILE__, 'popupBySupsysticProActivateCallback');
    register_deactivation_hook(__FILE__, array('modInstallerPps', 'deactivate'));
    register_uninstall_hook(__FILE__, array('modInstallerPps', 'uninstall'));
	
	add_filter('pre_set_site_transient_update_plugins', 'checkForPluginUpdatepopupBySupsysticPro');
    add_filter('plugins_api', 'myPluginApiCallpopupBySupsysticPro', 10, 3);
    
	if(!function_exists('getProPlugCodePps')) {
		function getProPlugCodePps() {
			return 'popup_by_supsystic_pro';
		}
	}
	if(!function_exists('getProPlugDirPps')) {
		function getProPlugDirPps() {
			return basename(dirname(__FILE__));
		}
	}
	if(!function_exists('getProPlugFilePps')) {
		function getProPlugFilePps() {
			return basename(__FILE__);
		}
	}
	if(!defined('S_YOUR_SECRET_HASH_'. getProPlugCodePps()))
		define('S_YOUR_SECRET_HASH_'. getProPlugCodePps(), 'sdfeFIOFJ3idj#*R#ER3487#$#$(@#203@#KLDKW@)w32EOEdjkwdD@#@');
	
    if(!function_exists('checkForPluginUpdatepopupBySupsysticPro')) {
        function checkForPluginUpdatepopupBySupsysticPro($checkedData) {
            if(class_exists('wpUpdaterPps')) {
                return wpUpdaterPps::getInstance( getProPlugDirPps(), getProPlugFilePps(), getProPlugCodePps() )->checkForPluginUpdate($checkedData);
            }
			return $checkedData;
        }
    }
    if(!function_exists('myPluginApiCallpopupBySupsysticPro')) {
        function myPluginApiCallpopupBySupsysticPro($def, $action, $args) {
            if(class_exists('wpUpdaterPps')) {
                return wpUpdaterPps::getInstance( getProPlugDirPps(), getProPlugFilePps(), getProPlugCodePps() )->myPluginApiCall($def, $action, $args);
            }
			return $def;
        }
    }
	/**
	 * Check if there are base (free) version installed
	 */
	if(!function_exists('popupBySupsysticProActivateCallback')) {
		function popupBySupsysticProActivateCallback() {
			if(class_exists('framePps')) {
				$arguments = func_get_args();
				call_user_func_array(array('modInstallerPps', 'check'), $arguments);
			}
		}
	}
	add_action('admin_notices', 'popupBySupsysticProInstallBaseMsg');
	if(!function_exists('popupBySupsysticProInstallBaseMsg')) {
		function popupBySupsysticProInstallBaseMsg() {
			if(!get_option('pps_full_installed') || !class_exists('framePps')) {
				$plugName = 'PopUp by Supsystic';
				$plugWpUrl = 'https://wordpress.org/plugins/popup-by-supsystic';
				$html = '<div class="error"><p><strong style="font-size: 15px;">
					Please install Free (Base) version of '. $plugName. ' plugin, you can get it <a target="_blank" href="'. $plugWpUrl. '">here</a> or use Wordpress plugins search functionality, 
					activate it, then deactivate and activate again PRO version of '. $plugName. '. 
					In this way you will have full and upgraded PRO version of '. $plugName. '.</strong></p></div>';
				echo $html;
			}
		}
	}