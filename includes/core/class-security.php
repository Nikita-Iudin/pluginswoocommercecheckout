<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_Security {

	private Custom_Store_Loader $loader;

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		$this->loader->add_action( 'admin_init', $this, 'check_woocommerce_active' );
		$this->loader->add_action( 'wp_ajax_csp_save_checkout_fields', $this, 'ajax_verify_nonce' );
		$this->loader->add_action( 'wp_ajax_csp_save_general_settings', $this, 'ajax_verify_nonce' );
	}

	public function check_woocommerce_active(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', [ $this, 'woocommerce_missing_notice' ] );
		}
	}

	public function woocommerce_missing_notice(): void {
		printf(
			'<div class="error"><p>%s</p></div>',
			esc_html__( 'Custom Store Plugin requires WooCommerce to be installed and active.', 'custom-store-plugin' )
		);
	}

	public function ajax_verify_nonce(): void {
		if ( ! check_ajax_referer( 'csp_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Nonce verification failed.', 'custom-store-plugin' ) ] );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Insufficient permissions.', 'custom-store-plugin' ) ] );
		}
	}

	public static function verify_admin_referer( string $action ): bool {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), $action ) ) {
			return false;
		}

		return true;
	}

	public static function sanitize_field( string $type, mixed $value ): mixed {
		return match ( $type ) {
			'text'   => sanitize_text_field( $value ),
			'email'  => sanitize_email( $value ),
			'url'    => esc_url_raw( $value ),
			'int'    => absint( $value ),
			'bool'   => filter_var( $value, FILTER_VALIDATE_BOOLEAN ),
			default  => sanitize_text_field( $value ),
		};
	}
}
