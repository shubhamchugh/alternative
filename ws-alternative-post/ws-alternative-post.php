<?php
if(!defined('ABSPATH')){die("404 Not Found");} 
/*
Plugin Name:  WS Alternative Post
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  WS Alternative Post Plugin is very useful to add Alternative Post Features in any existing site, It is very lightweight.
Version:      1
Author:       Nanchhu Yadav
Author URI:   https://developer.wordpress.org/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  alternative
Domain Path:  /languages
*/

define('WSALTERNATIVEPATH',plugin_dir_path( __FILE__ ));

define('WSALTERNATIVEURL',plugin_dir_url(__FILE__));

define('WS_ALTERNATIVE_TEXT_DOMAIN', 'alternative');
load_plugin_textdomain( 'alternative', FALSE, plugin_dir_path( dirname( __FILE__ ) ) . '/languages/' );

/*
* WS Alternative Includes 
*/ 

require_once(WSALTERNATIVEPATH.'/include/functions.php'); 