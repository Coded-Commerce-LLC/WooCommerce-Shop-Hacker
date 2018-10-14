<?php

class woo_shophacker_settings {

	/**
	 * Bootstraps the class and hooks required actions & filters.
	 *
	 */
	public static function init() {
		add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
		add_action( 'woocommerce_settings_tabs_settings_tab_shophacker', __CLASS__ . '::settings_tab' );
		add_action( 'woocommerce_update_options_settings_tab_shophacker', __CLASS__ . '::update_settings' );
	}

	/**
	 * Add a new settings tab to the WooCommerce settings tabs array.
	 *
	 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
	 * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
	 */
	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['settings_tab_shophacker'] = __( 'Shop Hacker', 'woo-shophacker' );
		return $settings_tabs;
	}

	/**
	 * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
	 *
	 * @uses woocommerce_admin_fields()
	 * @uses self::get_settings()
	 */
	public static function settings_tab() {
		do_action( 'woo_shophacker_settings_before' );
		woocommerce_admin_fields( self::get_settings() );
		do_action( 'woo_shophacker_settings_after' );
	}

	/**
	 * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
	 *
	 * @uses woocommerce_update_options()
	 * @uses self::get_settings()
	 */
	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}

	/**
	 * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
	 *
	 * @return array Array of settings for @see woocommerce_admin_fields() function.
	 */
	public static function get_settings() {
		$settings = array(
			'section_title' => array(
				'name' => __( 'Shop Hacker API Credentials', 'woo-shophacker' ),
				'type' => 'title',
				'id' => 'woo_shophacker_api'
			),
			'merchantid' => array(
				'name' => __( 'Merchant ID', 'woo-shophacker' ),
				'type' => 'number',
				'id' => 'woo_shophacker_merchantid'
			),
			'apikey' => array(
				'name' => __( 'API Key', 'woo-shophacker' ),
				'type' => 'text',
				'id' => 'woo_shophacker_apikey'
			),
			'apisecret' => array(
				'name' => __( 'API Secret', 'woo-shophacker' ),
				'type' => 'password',
				'id' => 'woo_shophacker_apisecret'
			),
			'section_end' => array(
				 'type' => 'sectionend',
				 'id' => 'woo_shophacker_end'
			)
		);
		return apply_filters( 'wc_settings_tab_shophacker_settings', $settings );
	}
}

woo_shophacker_settings::init();
