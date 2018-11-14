<?php

// Plugin API Class
class woo_shop_hacker_api {


	// Endpoint
	static $endpoint = 'https://api.shophacker.com/';


	// Makes Auth Header
	static function get_header( $args = [] ) {
		$apikey = get_option( 'woo_shop_hacker_apikey' );
		$apisecret = get_option( 'woo_shop_hacker_apisecret' );
		$credentials = sprintf( "%s:%s", $apikey, $apisecret );
		return array_merge( $args, [
			'headers' => [ 'Authorization' => 'Basic ' . base64_encode( $credentials ) ]
		] );
	}


	// Gets Single Product
	static function get_product( $id ) {
		$mid = get_option( 'woo_shop_hacker_merchantid' );
		$args = [ 'merchant_id' => $mid ];
		$url = woo_shop_hacker_api::$endpoint . 'products/' . intval( $id ) . '?' . http_build_query( $args );
		$header = woo_shop_hacker_api::get_header();
		$response = wp_remote_get( $url, $header );

		// Handle Bad Response
		if( empty( $response['body'] ) ) {
			print_r( $response );
			return false;
		}

		// Handle Good Response
		return json_decode( $response['body'] );
	}


	// Get All Products
	static function get_products() {
		$mid = get_option( 'woo_shop_hacker_merchantid' );
		$args = [ 'page' => 1, 'merchant_id' => $mid ];
		$url = woo_shop_hacker_api::$endpoint . 'products?' . http_build_query( $args );
		$header = woo_shop_hacker_api::get_header();
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
	static function get_search_results( $query = '', $page = 1 ) {
		$mid = get_option( 'woo_shop_hacker_merchantid' );
		$args = ['q' => $query, 'merchant_id' => $mid, 'page' => $page ];
		$url = sprintf(
			"%sproducts-search?%s",
			woo_shop_hacker_api::$endpoint,
			http_build_query( $args )
		);
		$header = woo_shop_hacker_api::get_header();
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
		$url = woo_shop_hacker_api::$endpoint . 'salesRequest';
		$args = woo_shop_hacker_api::get_header( [ 'Content-Type:' => 'application/json' ] );
		$mid = get_option( 'woo_shop_hacker_merchantid' );
		$body = [
			'sale' => [
				'customer_email' => $email,
				'customer_full_name' => $name,
				'merchant_id' => $mid,
				'shop_hacker_product_id' => $productID,
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
