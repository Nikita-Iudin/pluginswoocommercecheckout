<?php
/**
 * Plugin Name: Custom Store Plugin
 * Plugin URI:  https://example.com/custom-store-plugin
 * Description: Customize WooCommerce checkout fields from the admin panel — labels, placeholders, order, visibility, and required status.
 * Version:     1.0.0
 * Author:      Custom Store
 * Author URI:  https://example.com
 * License:     GPL-2.0+
 * Text Domain: custom-store-plugin
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 *
 * WC requires at least: 8.0
 * WC tested up to:      9.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CSP_VERSION', '1.0.0' );
define( 'CSP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CSP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CSP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once CSP_PLUGIN_DIR . 'includes/core/class-loader.php';
require_once CSP_PLUGIN_DIR . 'includes/core/class-security.php';
require_once CSP_PLUGIN_DIR . 'includes/admin/class-admin-menu.php';
require_once CSP_PLUGIN_DIR . 'includes/admin/class-settings.php';
require_once CSP_PLUGIN_DIR . 'includes/admin/class-checkout-settings.php';
require_once CSP_PLUGIN_DIR . 'includes/checkout/class-checkout-fields.php';
require_once CSP_PLUGIN_DIR . 'includes/integrations/class-shipping.php';
require_once CSP_PLUGIN_DIR . 'includes/integrations/class-payments.php';
require_once CSP_PLUGIN_DIR . 'includes/integrations/class-api.php';

final class Custom_Store_Plugin {

	private static ?self $instance = null;

	private Custom_Store_Loader $loader;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->loader = new Custom_Store_Loader();
		$this->define_hooks();
	}

	private function define_hooks(): void {
		$security = new Custom_Store_Security( $this->loader );
		$security->register();

		$admin_menu = new Custom_Store_Admin_Menu( $this->loader );
		$admin_menu->register();

		$settings = new Custom_Store_Settings( $this->loader );
		$settings->register();

		$checkout_settings = new Custom_Store_Checkout_Settings( $this->loader );
		$checkout_settings->register();

		$checkout_fields = new Custom_Store_Checkout_Fields( $this->loader );
		$checkout_fields->register();

		$shipping = new Custom_Store_Shipping( $this->loader );
		$shipping->register();

		$payments = new Custom_Store_Payments( $this->loader );
		$payments->register();

		$api = new Custom_Store_API( $this->loader );
		$api->register();
	}

	public function run(): void {
		$this->loader->run();
	}
}

function custom_store_plugin(): Custom_Store_Plugin {
	return Custom_Store_Plugin::get_instance();
}

add_action( 'plugins_loaded', 'custom_store_plugin_run' );
function custom_store_plugin_run(): void {
	$plugin = custom_store_plugin();
	$plugin->run();
}

register_activation_hook( __FILE__, 'custom_store_plugin_activate' );
function custom_store_plugin_activate(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		deactivate_plugins( CSP_PLUGIN_BASENAME );
		wp_die( esc_html__( 'Custom Store Plugin requires WooCommerce to be installed and active.', 'custom-store-plugin' ) );
	}

	$defaults = Custom_Store_Checkout_Fields::get_default_fields();
	if ( false === get_option( 'csp_checkout_fields', false ) ) {
		add_option( 'csp_checkout_fields', $defaults );
	}

	if ( false === get_option( 'csp_general_settings', false ) ) {
		add_option( 'csp_general_settings', [ 'enabled' => 'yes' ] );
	}

	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'custom_store_plugin_deactivate' );
function custom_store_plugin_deactivate(): void {
	flush_rewrite_rules();
}
