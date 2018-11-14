<?php

// Plugin Builder Class
class woo_shop_hacker_builder {


	// Class Properties
	static $total_pages = 0;
	static $total_records = 0;


	// Test Plugin Settings
	static function is_configured() {
		$mid = get_option( 'woo_shop_hacker_merchantid' );
		$apikey = get_option( 'woo_shop_hacker_apikey' );
		$apisecret = get_option( 'woo_shop_hacker_apisecret' );
		return ( $mid && $apikey && $apisecret );
	}


	// Section Contents
	static function woo_shop_hacker_settings_before() {

		// Ensure Plugin Is Configured
		if( ! self::is_configured() ) { return []; }

		// Get Search Results
		$query = isset( $_REQUEST['query'] ) ? sanitize_text_field( $_REQUEST['query'] ) : '';
		$page = isset( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : 1;
		$products = $query ? self::run_search( $query, $page ) : [];

		// Get Pagination
		$pagination = [ sprintf( 'Page: %d of %d', $page, self::$total_pages ) ];
		if( $page > 2 ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				1, $query, '&laquo; ' . __( 'First Page', 'woo-shop-hacker' )
			);
		}
		if( $page > 1 ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				$page - 1, $query, '&laquo; ' . __( 'Previous Page', 'woo-shop-hacker' )
			);
		}
		if( $page < self::$total_pages ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				$page + 1, $query, __( 'Next Page', 'woo-shop-hacker' ) . '  &raquo;'
			);
		}
		if( $page < self::$total_pages - 1 ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				self::$total_pages, $query, __( 'Last Page', 'woo-shop-hacker' ) .  ' &raquo;'
			);
		}

		// Search Form
		include( 'view.inventory.html.php' );

		// Return Settings
		return [];	
	}


	// Product HTML
	static function print_product( $product ) {

		// Get Description
		$description = '';
		foreach( $product->product_line_items as $val ) {
			$description = $val->description;
		}

		// Output
		echo sprintf(
			'
				<dt>
					<label>
						<input type="checkbox" name="add_product[]" value="%d" /> <strong>%s</strong>
						<a href="#" onclick="jQuery( \'#pop%d\' ).toggle( \'slow\' ); return false;" class="dashicons dashicons-sort"></a><br />
					</label>
				</dt>
				<dd>
					<small>%s</small>
					<div id="pop%d" class="shop_hacker_product_details">%s</div>
				</dd>
			',
			$product->id,
			trim( $product->name ),
			$product->id,
			$product->bundle_headline,
			$product->id,
			$description
		);
	}


	// Search Processor
	static function run_search( $query, $page ) {

		// Run Query
		$response = woo_shop_hacker_api::get_search_results( $query, $page );
		$products = isset( $response->products ) ? $response->products : [];

		// Get Meta Data
		$meta = isset( $response->meta ) ? $response->meta : '';
		self::$total_pages = isset( $meta->total_pages ) ? intval( $meta->total_pages ) : 0;
		self::$total_records = isset( $meta->total_records ) ? intval( $meta->total_records ) : 0;

		// Return Products
		return $products;
	}


// End Plugin Builder Class
}
