<?php

// Plugin API Class
class woo_shophacker_api {

	// Endpoint
	static $endpoint = 'https://api.shophacker.com/';

	// Makes Auth Header
	static function get_header( $args = [] ) {
		$apikey = get_option( 'woo_shophacker_apikey' );
		$apisecret = get_option( 'woo_shophacker_apisecret' );
		$credentials = sprintf( "%s:%s", $apikey, $apisecret );
		return array_merge( $args, [
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( $credentials ),
			)
		] );
	}

	// Get All Products
	static function get_products() {
		$mid = get_option( 'woo_shophacker_merchantid' );
		$args = ['page' => 1, 'merchant_id' => $mid ];
		$url = woo_shophacker_api::$endpoint . 'products?' . http_build_query( $args );
		$header = woo_shophacker_api::get_header();
		$response = wp_remote_get( $url, $header );

		// Handle Bad Response
		if( empty( $response['body'] ) ) {
			print_r( $response );
			return false;
		}

		// Handle Good Response
		return json_decode( $response['body'] );
	}

	// Search Products
	static function get_search_results( $query = '' ) {
		$mid = get_option( 'woo_shophacker_merchantid' );
		$args = ['q' => $query, 'merchant_id' => $mid ];
		$url = woo_shophacker_api::$endpoint . 'products-search?' . http_build_query( $args );
		$header = woo_shophacker_api::get_header();
		$response = wp_remote_get( $url, $header );

		// Handle Bad Response
		if( empty( $response['body'] ) ) {
			print_r( $response );
			return false;
		}

		// Handle Good Response
		return json_decode( $response['body'] );
	}

	// Save Sale
	static function save_sale( $productID, $name, $email ) {

		// Verify Data
		if( ! $productID || ! $name || ! $email ) {
			return false;
		}

		// Transmit Order
		$url = woo_shophacker_api::$endpoint . 'salesRequest';
		$args = woo_shophacker_api::get_header( [ 'Content-Type:' => 'application/json' ] );
		$mid = get_option( 'woo_shophacker_merchantid' );
		$body = [
			'sale' => [
				'shop_hacker_product_id' => $productID,
				'customer_full_name' => $name,
				'customer_email' => $email,
				'merchant_id' => $mid,
			]
		];
		$args['body'] = json_encode( $body );
		$response = wp_remote_post( $url, $args );

		// Handle Bad Response
		if( empty( $response['body'] ) ) {
			print_r( $response );
			return false;
		}

		// Handle Good Response
		$response = json_decode( $response['body'] );
		return isset( $response->sale_builder_id ) ? intval( $response->sale_builder_id ) : false;
	}

// End Plugin API Class
}
