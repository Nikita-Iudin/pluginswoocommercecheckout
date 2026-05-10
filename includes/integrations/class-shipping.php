<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_Shipping {

	private Custom_Store_Loader $loader;

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		// Future: shipping method integrations.
	}
}
