<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_Admin_Menu {

	private Custom_Store_Loader $loader;

	private array $hook_suffixes = [];

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		$this->loader->add_action( 'admin_menu', $this, 'add_menu_pages' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_assets' );
	}

	public function add_menu_pages(): void {
		$this->hook_suffixes[] = add_menu_page(
			esc_html__( 'Custom Store', 'custom-store-plugin' ),
			esc_html__( 'Custom Store', 'custom-store-plugin' ),
			'manage_options',
			'custom-store-plugin',
			[ $this, 'render_general_page' ],
			'dashicons-admin-generic',
			56
		);

		$this->hook_suffixes[] = add_submenu_page(
			'custom-store-plugin',
			esc_html__( 'General Settings', 'custom-store-plugin' ),
			esc_html__( 'General', 'custom-store-plugin' ),
			'manage_options',
			'custom-store-plugin',
			[ $this, 'render_general_page' ]
		);

		$this->hook_suffixes[] = add_submenu_page(
			'custom-store-plugin',
			esc_html__( 'Checkout Fields', 'custom-store-plugin' ),
			esc_html__( 'Checkout Fields', 'custom-store-plugin' ),
			'manage_options',
			'custom-store-checkout-fields',
			[ $this, 'render_checkout_fields_page' ]
		);
	}

	public function enqueue_assets( string $hook ): void {
		// Debug: uncomment the line below to see the actual hook value
		// error_log( 'CSP enqueue_assets hook: ' . $hook . ' | suffixes: ' . implode( ',', $this->hook_suffixes ) );

		if ( ! in_array( $hook, $this->hook_suffixes, true ) ) {
			return;
		}

		wp_enqueue_style(
			'csp-admin',
			CSP_PLUGIN_URL . 'assets/css/admin.css',
			[],
			CSP_VERSION
		);

		wp_enqueue_script(
			'csp-admin',
			CSP_PLUGIN_URL . 'assets/js/admin.js',
			[ 'jquery', 'jquery-ui-sortable' ],
			CSP_VERSION,
			true
		);

		wp_localize_script( 'csp-admin', 'cspAdmin', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'csp_admin_nonce' ),
			'i18n'    => [
				'saved'    => esc_html__( 'Settings saved.', 'custom-store-plugin' ),
				'error'    => esc_html__( 'Error saving settings.', 'custom-store-plugin' ),
				'confirm'  => esc_html__( 'Are you sure?', 'custom-store-plugin' ),
			],
		] );
	}

	public function render_general_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = get_option( 'csp_general_settings', [ 'enabled' => 'yes' ] );

		include CSP_PLUGIN_DIR . 'includes/admin/views/general-settings.php';
	}

	public function render_checkout_fields_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$fields = get_option( 'csp_checkout_fields', Custom_Store_Checkout_Fields::get_default_fields() );

		include CSP_PLUGIN_DIR . 'includes/admin/views/checkout-fields-settings.php';
	}
}
