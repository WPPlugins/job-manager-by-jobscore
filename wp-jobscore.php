<?php

/*
Plugin Name: Job Manager by JobScore
Plugin URI: http://jobscore.com/plugins/wp-jobscore
Description: This plugin allows the user to modify the options of the JobScore jobs page iframe.
Version: 1.0
Author: Andrei Rosseti
Author URI: http://andrei.rosseti.eti.br
License: GPL2
*/

define( 'WPJS_OPT_CONFIG', 'wpjs_config_');
define( 'WPJS_OPT_WIDGET', 'wpjs_widget_');

class WP_JobScore 
{	
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ));
	}
	
	public function install() 
	{
		add_option(WPJS_OPT_WIDGET . "show_logo", 'remove');
	}
	
	public function deactivate() 
	{
		global $wpdb;
		
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '".WPJS_OPT_CONFIG."%'");
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '".WPJS_OPT_WIDGET."%'");
	}
	
	public function constants() {
		define( 'WPJS_URL',	 plugins_url( '', __FILE__ ));
		define( 'WPJS_PATH', dirname( __FILE__ ) );
		define( 'WPJS_EXT',  '.php');
	}
	
	public function includes() 
	{
		if (is_admin()) {
			include (WPJS_PATH . '/admin' . WPJS_EXT);
		}
	}
	
	public function init() 
	{
		$this->constants();
		$this->includes();
	}
	
	public function handle_the_content($content) 
	{	
		global $wpdb;
		
		if (is_page()) 
		{
			$account_name = get_option(WPJS_OPT_CONFIG . 'account_name');
			$related_page = get_option(WPJS_OPT_CONFIG . 'page_id');

			if (get_the_ID() == $related_page && $account_name) 
			{
				ob_start();
				include (WPJS_PATH . '/views/widget-src' . WPJS_EXT);
				$widget_src_tags = ob_get_contents();
				ob_end_clean();

				return $content . $widget_src_tags;
			}
		}

		return $content;
	}
	
	public function plugin_settings_link($links) 
	{ 
		$settings_link = '<a href="options-general.php?page=wpjs-config">Settings</a>'; 
	
		array_unshift($links, $settings_link); 
		
		return $links; 
	}
}

$wpjs = new WP_JobScore();

register_activation_hook(	__FILE__, array( $wpjs, 'install' ) );
register_deactivation_hook( __FILE__, array( $wpjs, 'deactivate' ) );

 
$plugin = plugin_basename(__FILE__); 

add_filter("plugin_action_links_$plugin", array($wpjs, 'plugin_settings_link') );

add_action('the_content', array($wpjs, 'handle_the_content'));

