<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_API {

	private Custom_Store_Loader $loader;

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		$this->loader->add_action( 'rest_api_init', $this, 'register_routes' );
	}

	public function register_routes(): void {
		// Future: REST API endpoints for checkout field management.
		// Example:
		// register_rest_route( 'custom-store/v1', '/checkout-fields', [
		//     'methods'             => 'GET',
		//     'callback'            => [ $this, 'get_fields' ],
		//     'permission_callback' => function() {
		//         return current_user_can( 'manage_options' );
		//     },
		// ]);
	}
}
