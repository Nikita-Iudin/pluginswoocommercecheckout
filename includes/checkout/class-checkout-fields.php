<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Store_Checkout_Fields {

	private Custom_Store_Loader $loader;

	public function __construct( Custom_Store_Loader $loader ) {
		$this->loader = $loader;
	}

	public function register(): void {
		$this->loader->add_filter( 'woocommerce_checkout_fields', $this, 'customize_checkout_fields', 20, 1 );
		$this->loader->add_action( 'woocommerce_load_shipping_fields', $this, 'customize_shipping_fields', 20, 1 );
	}

	public function customize_checkout_fields( array $fields ): array {
		$general = get_option( 'csp_general_settings', [ 'enabled' => 'yes' ] );

		if ( empty( $general['enabled'] ) || 'yes' !== $general['enabled'] ) {
			return $fields;
		}

		$saved = get_option( 'csp_checkout_fields', self::get_default_fields() );

		if ( empty( $saved ) || ! is_array( $saved ) ) {
			return $fields;
		}

		foreach ( $saved as $key => $settings ) {
			$type = $settings['type'] ?? 'billing';

			if ( ! isset( $fields[ $type ] ) || ! is_array( $fields[ $type ] ) ) {
				continue;
			}

			if ( ! isset( $fields[ $type ][ $key ] ) ) {
				continue;
			}

			if ( empty( $settings['enabled'] ) ) {
				unset( $fields[ $type ][ $key ] );
				continue;
			}

			if ( ! empty( $settings['label'] ) ) {
				$fields[ $type ][ $key ]['label'] = $settings['label'];
			}

			if ( ! empty( $settings['placeholder'] ) ) {
				$fields[ $type ][ $key ]['placeholder'] = $settings['placeholder'];
			}

			if ( ! empty( $settings['priority'] ) ) {
				$fields[ $type ][ $key ]['priority'] = absint( $settings['priority'] );
			}

			$fields[ $type ][ $key ]['required'] = ! empty( $settings['required'] );
		}

		return $fields;
	}

	public function customize_shipping_fields( array $fields ): array {
		return $this->customize_checkout_fields( $fields );
	}

	public static function get_default_fields(): array {
		return [
			'billing_first_name' => [
				'label'       => __( 'First name', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 10,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_last_name'  => [
				'label'       => __( 'Last name', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 20,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_company'    => [
				'label'       => __( 'Company name', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 30,
				'enabled'     => true,
				'required'    => false,
				'type'        => 'billing',
			],
			'billing_country'    => [
				'label'       => __( 'Country / Region', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 40,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_address_1'  => [
				'label'       => __( 'Street address', 'custom-store-plugin' ),
				'placeholder' => __( 'House number and street name', 'custom-store-plugin' ),
				'priority'    => 50,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_address_2'  => [
				'label'       => __( 'Apartment, suite, unit, etc.', 'custom-store-plugin' ),
				'placeholder' => __( 'Apartment, suite, unit, etc. (optional)', 'custom-store-plugin' ),
				'priority'    => 60,
				'enabled'     => true,
				'required'    => false,
				'type'        => 'billing',
			],
			'billing_city'       => [
				'label'       => __( 'City', 'custom-store-plugin' ),
				'placeholder' => __( 'Enter city', 'custom-store-plugin' ),
				'priority'    => 70,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_state'      => [
				'label'       => __( 'Region / State', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 80,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_postcode'   => [
				'label'       => __( 'Postcode / ZIP', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 90,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_phone'      => [
				'label'       => __( 'Phone', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 100,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'billing_email'      => [
				'label'       => __( 'Email address', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 110,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'billing',
			],
			'shipping_first_name' => [
				'label'       => __( 'First name', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 10,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
			'shipping_last_name'  => [
				'label'       => __( 'Last name', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 20,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
			'shipping_company'   => [
				'label'       => __( 'Company name', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 30,
				'enabled'     => true,
				'required'    => false,
				'type'        => 'shipping',
			],
			'shipping_country'   => [
				'label'       => __( 'Country / Region', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 40,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
			'shipping_address_1' => [
				'label'       => __( 'Street address', 'custom-store-plugin' ),
				'placeholder' => __( 'House number and street name', 'custom-store-plugin' ),
				'priority'    => 50,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
			'shipping_address_2' => [
				'label'       => __( 'Apartment, suite, unit, etc.', 'custom-store-plugin' ),
				'placeholder' => __( 'Apartment, suite, unit, etc. (optional)', 'custom-store-plugin' ),
				'priority'    => 60,
				'enabled'     => true,
				'required'    => false,
				'type'        => 'shipping',
			],
			'shipping_city'      => [
				'label'       => __( 'City', 'custom-store-plugin' ),
				'placeholder' => __( 'Enter city', 'custom-store-plugin' ),
				'priority'    => 70,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
			'shipping_state'     => [
				'label'       => __( 'Region / State', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 80,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
			'shipping_postcode'  => [
				'label'       => __( 'Postcode / ZIP', 'custom-store-plugin' ),
				'placeholder' => '',
				'priority'    => 90,
				'enabled'     => true,
				'required'    => true,
				'type'        => 'shipping',
			],
		];
	}
}
