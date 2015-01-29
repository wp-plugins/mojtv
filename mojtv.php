<?php
/*
Plugin Name: MojTv
Plugin URI: http://mojtv.net/
Description: Moj TV feed plugin
Author: WebMasterJ
Version: 0.1
*/

/*
 * Copyright 2014 WebMasterJ
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

define( 'MOJTV_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'MOJTV_PLUGIN_CACHE_DIR', MOJTV_PLUGIN_DIR . '/cache' );

require_once( MOJTV_PLUGIN_DIR . '/widget.php' );

if(!class_exists('mojtv'))
{
    class mojtv extends WP_Widget {
        public function __construct()
        {
        	add_action('admin_init', array(&$this, 'admin_init'));
					add_action('admin_menu', array(&$this, 'add_menu'));
					parent::WP_Widget(false, $name = __('Moj TV', 'wp_widget_plugin') );
        }

        public static function activate()
        {
					if(is_writable(MOJTV_PLUGIN_CACHE_DIR)) {
            $ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, 'http://mojtv.net/xmltv/channels.ashx ');
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$xml = curl_exec ($ch);
						curl_close ($ch);
						if (@simplexml_load_string($xml)) {
								$fp = fopen(MOJTV_PLUGIN_CACHE_DIR.'/channels.xml', 'w');
								fwrite($fp, $xml);
								fclose($fp);
						}
					}
        }

        public static function deactivate()
        {
            unlink(MOJTV_PLUGIN_CACHE_DIR.'/channels.xml');
        }
				
				public function admin_init()
				{
						$this->init_settings();
				}
				
				public function init_settings()
				{
					register_setting('mojtv-group', 'mojtv_setting_kanali');
					register_setting('mojtv-group', 'mojtv_setting_refresh_ch');					
					register_setting('mojtv-group', 'mojtv_setting_nrres');
					register_setting('mojtv-group', 'mojtv_setting_type');
					register_setting('mojtv-group', 'mojtv_setting_next');
					register_setting('mojtv-group', 'mojtv_setting_height');
				}
				
				public function add_menu()
				{
						$hook = add_options_page('MojTv Settings', 'MojTv Settings', 'administrator', 'mojtv', array(&$this, 'plugin_settings_page'));

						add_action('load-'.$hook,'do_on_my_plugin_settings_save');
				}
				
				public function plugin_settings_page()
				{
						if(!current_user_can('manage_options'))
						{
								wp_die(__('You do not have sufficient permissions to access this page.'));
						}
				
						include(sprintf("%s/settings.php", dirname(__FILE__)));
				}
				
				
				function form($instance) { ?>
					<p>This widget does not have settings.</p><p>Settings are general and set on plugin settings page.</p>
				<? }
		 
				
				function update($new_instance, $old_instance) {
				}
		 
				
				function widget($args, $instance) {
						extract( $args );
						 echo $before_widget;
						 if(get_option('mojtv_setting_height') != '') {
							 echo '<ul class="movielist" style="height: '. intval(get_option('mojtv_setting_height')) .'px;">';
						 } else {
							 echo '<ul class="movielist">';
						 }
						 echo moj_widget();
						 echo '</ul>';
						 echo $after_widget;
				}
    }
}

if(class_exists('mojtv'))
{
	
		add_action( 'wp_enqueue_scripts', 'register_plugin_styles_moj' );

		function register_plugin_styles_moj() {
			wp_register_style( 'mojtv.css', plugins_url('/css/mojtv.css', __FILE__ ), array());
			wp_enqueue_style( 'mojtv.css');
		}
	
		add_action( 'admin_notices', 'moj_admin_notice' );
		
		function moj_admin_notice() {
			if(!is_writable(MOJTV_PLUGIN_CACHE_DIR)) {
				moj_admin_notice_show("Can't write in cache",0);
			} else {
				//moj_admin_notice_show("All is cool",1);
			}
		}
		
		function moj_admin_notice_show($message,$br) {
			if($br==0) {
				echo "<div class='error'><p>". $message ."</p></div>";
			} else {
				echo "<div class='updated'><p>". $message ."</p></div>";
			}
		}
   
		add_action('widgets_init', create_function('', 'return register_widget("mojtv");'));
		register_activation_hook(__FILE__, array('mojtv', 'activate'));
    register_deactivation_hook(__FILE__, array('mojtv', 'deactivate'));
		
		function get_channel_xml() {
			if(is_writable(MOJTV_PLUGIN_CACHE_DIR)) {
				global $schedulesT;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://mojtv.net/xmltv/channels.ashx');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$xml = curl_exec ($ch);
				curl_close ($ch);
				if (@simplexml_load_string($xml)) {
						$fp = fopen(MOJTV_PLUGIN_CACHE_DIR.'/channels.xml', 'w');
						fwrite($fp, $xml);
						fclose($fp);
				}
			}
		}

		function get_movies_xml() {
			if(is_writable(MOJTV_PLUGIN_CACHE_DIR)) {
				global $schedulesT;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://mojtv.net/xmltv/exportMovies.ashx?channel_ids='. implode(',',get_option('mojtv_setting_kanali')) .'&program_start='. date('Y/n/j') .'&program_stop='. date('Y/n/j', strtotime(' +'.get_option('mojtv_setting_next').' day')) .'&film_tip=0');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$xml = curl_exec ($ch);
				curl_close ($ch);
				if (@simplexml_load_string($xml)) {
						$fp = fopen(MOJTV_PLUGIN_CACHE_DIR.'/movies.xml', 'w');
						fwrite($fp, $xml);
						fclose($fp);
				}
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://mojtv.net/xmltv/exportMovies.ashx?channel_ids='. implode(',',get_option('mojtv_setting_kanali')) .'&program_start='. date('Y/n/j') .'&program_stop='. date('Y/n/j', strtotime(' +'.get_option('mojtv_setting_next').' day')) .'&film_tip=1');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$xml = curl_exec ($ch);
				curl_close ($ch);
				if (@simplexml_load_string($xml)) {
						$fp = fopen(MOJTV_PLUGIN_CACHE_DIR.'/series.xml', 'w');
						fwrite($fp, $xml);
						fclose($fp);
				}
			}
		}
		
		if (time()-filemtime(MOJTV_PLUGIN_CACHE_DIR.'/channels.xml') > get_option('mojtv_setting_refresh_ch') * 3600 * 24) {
			get_channel_xml();
		}
		
		if (time()-filemtime(MOJTV_PLUGIN_CACHE_DIR.'/movies.xml') > 2 * 3600) {
			get_movies_xml();
		}
		
		function do_on_my_plugin_settings_save()
		{
			if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
			 {
					get_channel_xml();
					get_movies_xml();
			 }
		}

		
		function mojtv_widg() {
			if(get_option('mojtv_setting_height') != '') {
				return '<ul class="movielist" style="height: '. intval(get_option('mojtv_setting_height')) .'px;">'.moj_widget().'</ul>';
			} else {
				return '<ul class="movielist">'.moj_widget().'</ul>';
			}
		}
		
		add_shortcode('mojtv', 'mojtv_widg');
}

?>
