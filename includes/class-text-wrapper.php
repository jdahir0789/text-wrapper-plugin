<?php
/**
 * Main class file for Text Wrapper plugin.
 *
 * @package Text_Wrapper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Text_Wrapper
 *
 * Handles the core functionalities of the Text Wrapper plugin.
 */
class Text_Wrapper {

	/**
	 * Defaul Value for setting fields
	 *
	 * @var $defaul_value
	 */

	private $defaul_value = array(
		'tag'    => 'span',
		'class'  => 'wrap-text',
	);
	/**
	 * Constructor.
	 *
	 * Initializes the plugin by defining hooks and filters.
	 */
	public function __construct() {
		$this->define_hooks();
	}

	/**
	 * Define WordPress hooks and filters.
	 *
	 * Registers all actions and filters required for the plugin to work.
	 */
	private function define_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );

		add_action( 'init', array( $this, 'register_tinymce_plugin' ) );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Load only on relevant admin pages.
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php', 'settings_page_ctw-settings' ), true ) ) {
			return;
		}

		// Enqueue JavaScript for TinyMCE and settings.
		wp_enqueue_script(
			'ctw-admin-js',
			plugin_dir_url( __DIR__ ) . 'assets/js/admin.js',
			array( 'jquery', 'wp-tinymce' ),
			'1.0',
			true
		);

		// Enqueue CSS for the admin interface.
		wp_enqueue_style(
			'ctw-admin-css',
			plugin_dir_url( __DIR__ ) . 'assets/css/admin.css',
			array(),
			'1.0'
		);

		// Localize settings for JavaScript.
		wp_localize_script(
			'ctw-admin-js',
			'ctwSettings',
			array(
				'options'      => get_option( 'ctw_settings', $this->defaul_value ),
				'defaul_value' => $this->defaul_value,
				'_wpnonce'     => wp_create_nonce( 'ctw_save_settings' ),
			)
		);
	}

	/**
	 * Add a settings page to the WordPress admin menu.
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Text Wrapper Settings', 'text-wrapper' ),
			__( 'Text Wrapper', 'text-wrapper' ),
			'manage_options',
			'ctw-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Render the settings page for the Text Wrapper plugin.
	 */
	public function render_settings_page() {

		if ( isset( $_POST['ctw_settings'] ) ) {
			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( ( wp_unslash( $_POST['_wpnonce'] ) ) ), 'ctw_save_settings' ) ) {
				echo '<div class="error"><p>' . esc_html( 'Nonce verification failed. Settings were not saved.' ) . '</p></div>';
				return;
			}
			$options = array_map( 'sanitize_key', $_POST['ctw_settings'] );
			update_option( 'ctw_settings', $options );
			echo '<div class="updated"><p>' . esc_html( 'Settings saved successfully.' ) . '</p></div>';
		}

		$settings = get_option( 'ctw_settings', $this->defaul_value );

		include plugin_dir_path( __DIR__ ) . 'templates/settings-page.php';
	}

	/**
	 * Register the TinyMCE plugin and button.
	 */
	public function register_tinymce_plugin() {
		add_filter(
			'mce_external_plugins',
			function ( $plugins ) {
				$plugins['ctw_plugin'] = plugin_dir_url( __DIR__ ) . 'assets/js/admin.js';
				return $plugins;
			}
		);

		// Add the button to the toolbar.
		add_filter(
			'mce_buttons',
			function ( $buttons ) {
				array_push( $buttons, 'ctw_button' );
				return $buttons;
			}
		);
	}
}
