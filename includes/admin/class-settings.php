<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_Settings {

	private Custom_Store_Loader $loader;

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		$this->loader->add_action( 'wp_ajax_csp_save_general_settings', $this, 'ajax_save_settings' );
	}

	public function ajax_save_settings(): void {
		check_ajax_referer( 'csp_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Insufficient permissions.', 'custom-store-plugin' ) ] );
		}

		$enabled = isset( $_POST['enabled'] ) && 'yes' === sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) ? 'yes' : 'no';

		$settings = [
			'enabled' => $enabled,
		];

		update_option( 'csp_general_settings', $settings );

		wp_send_json_success( [ 'message' => esc_html__( 'Settings saved.', 'custom-store-plugin' ) ] );
	}
}
