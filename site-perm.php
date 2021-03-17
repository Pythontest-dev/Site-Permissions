<?php
/**
 * Plugin Name: Site Permission
 * Description: Pugin to manage page permissions
 * Version: 1.3
 * Author: Pythontest
 * License: GPLv3
 * Requires PHP: 7.2
 */
require_once 'owt-table-list.php';
require_once 'function.php';
require_once 'vievs-fun.php';
    add_action('wp_body_open', 'sp_block');
    
     /** Krok 2. */
add_action( 'admin_menu', 'sp_my_plugin_menu' );
/** Krok 1. */
function sp_my_plugin_menu()
{
    add_menu_page( 'site-Perm', 'Site permission', 'manage_options', 'sp_site-perm','sp_my_plugin_options');
    add_submenu_page('sp_site-perm','Sites','Sites','manage_options','sp_site-perm');
    add_submenu_page('sp_site-perm', 'Settings', 'Settings', 'manage_options', 'sp_seet-site-perm','sp_seet_site_perm');
    if(sp_IsKPanel())
    {
        if(sp_TestKPanel())
        {
            add_menu_page('Sites', 'Sites', 'read', 'site-perm-site','sp_Kpanelset','dashicons-admin-page');
        }
        //add_submenu_page('site-perm', 'Analytics', 'Analytics', 'manage_options', 'anal-site-perm','analytics');
    }
}
/** Krok 3. */
function sp_DBP_tb_create()
{
    global $wpdb;
    $DPB_tb_name=$wpdb->prefix."perm_site_seetings";
    $DBP_query="CREATE TABLE $DPB_tb_name ( `key_id` TEXT NOT NULL , `value` TEXT NOT NULL,UNIQUE (`key_id`, `value`) ) ENGINE = InnoDB;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($DBP_query);
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_".$plugin, 'sp_addPluginSettingsLinkk' );
add_filter('network_admin_plugin_action_links_'.$plugin, 'sp_addPluginSettingsLinkk');


 register_activation_hook(__FILE__,"sp_DBP_tb_create");

?>