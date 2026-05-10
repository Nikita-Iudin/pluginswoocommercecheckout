<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_Checkout_Settings {

	private Custom_Store_Loader $loader;

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		$this->loader->add_action( 'wp_ajax_csp_save_checkout_fields', $this, 'ajax_save_fields' );
		$this->loader->add_action( 'wp_ajax_csp_reset_checkout_fields', $this, 'ajax_reset_fields' );
	}

	public function ajax_save_fields(): void {
		check_ajax_referer( 'csp_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Insufficient permissions.', 'custom-store-plugin' ) ] );
		}

		$raw_fields = isset( $_POST['fields'] ) ? wp_unslash( $_POST['fields'] ) : [];

		if ( ! is_array( $raw_fields ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid data format.', 'custom-store-plugin' ) ] );
		}

		$sanitized = $this->sanitize_fields( $raw_fields );

		update_option( 'csp_checkout_fields', $sanitized );

		wp_send_json_success( [ 'message' => esc_html__( 'Checkout fields saved.', 'custom-store-plugin' ) ] );
	}

	public function ajax_reset_fields(): void {
		check_ajax_referer( 'csp_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Insufficient permissions.', 'custom-store-plugin' ) ] );
		}

		$defaults = Custom_Store_Checkout_Fields::get_default_fields();
		update_option( 'csp_checkout_fields', $defaults );

		wp_send_json_success( [
			'message' => esc_html__( 'Checkout fields reset to defaults.', 'custom-store-plugin' ),
			'fields'  => $defaults,
		] );
	}

	private function sanitize_fields( array $raw_fields ): array {
		$clean = [];

		foreach ( $raw_fields as $key => $field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}

			$clean[ sanitize_text_field( $key ) ] = [
				'label'       => sanitize_text_field( $field['label'] ?? '' ),
				'placeholder' => sanitize_text_field( $field['placeholder'] ?? '' ),
				'priority'    => absint( $field['priority'] ?? 50 ),
				'enabled'     => filter_var( $field['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN ),
				'required'    => filter_var( $field['required'] ?? false, FILTER_VALIDATE_BOOLEAN ),
				'type'        => sanitize_text_field( $field['type'] ?? 'billing' ),
			];
		}

		return $clean;
	}
}
