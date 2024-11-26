<?php
/**
 * Plugin Name: Text Wrapper
 * Description: Adds a custom button to WordPress and ACF WYSIWYG editors to wrap selected text with a customizable tag and class.
 * Version: 1.0
 * Author: JD Ahir
 * Text Domain: text-wrapper
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Text_Wrapper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	function ( $class ) {
		if ( strpos( $class, 'Text_Wrapper' ) === 0 ) {
				$filename = plugin_dir_path( __FILE__ ) . 'includes/class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';
			if ( file_exists( $filename ) ) {
				include $filename;
			}
		}
	}
);

add_action(
	'plugins_loaded',
	function () {
		if ( class_exists( 'Text_Wrapper' ) ) {
			new Text_Wrapper();
		}
	}
);
