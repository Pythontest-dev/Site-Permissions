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
    add_action('wp_body_open', 'siteperm_block');
    
     /** Krok 2. */
add_action( 'admin_menu', 'siteperm_my_plugin_menu' );
/** Krok 1. */
function siteperm_my_plugin_menu()
{
    add_menu_page( 'site-Perm', 'Site permission', 'manage_options', 'siteperm_site-perm','siteperm_my_plugin_options');
    add_submenu_page('siteperm_site-perm','Sites','Sites','manage_options','siteperm_site-perm');
    add_submenu_page('siteperm_site-perm', 'Settings', 'Settings', 'manage_options', 'siteperm_seet-site-perm','siteperm_seet_site_perm');
    if(siteperm_IsKPanel())
    {
        if(siteperm_TestKPanel())
        {
            add_menu_page('Sites', 'Sites', 'read', 'site-perm-site','siteperm_Kpanelset','dashicons-admin-page');
        }
        //add_submenu_page('site-perm', 'Analytics', 'Analytics', 'manage_options', 'anal-site-perm','analytics');
    }
}
/** Krok 3. */
function siteperm_DBP_tb_create()
{
    global $wpdb;
    $DPB_tb_name=$wpdb->prefix."perm_site_seetings";
    $DBP_query="CREATE TABLE $DPB_tb_name ( `key_id` TEXT NOT NULL , `value` TEXT NOT NULL,UNIQUE (`key_id`, `value`) ) ENGINE = InnoDB;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($DBP_query);
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_".$plugin, 'siteperm_addPluginSettingsLinkk' );
add_filter('network_admin_plugin_action_links_'.$plugin, 'siteperm_addPluginSettingsLinkk');


 register_activation_hook(__FILE__,"siteperm_DBP_tb_create");

?>