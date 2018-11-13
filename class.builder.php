<?php

// Plugin Builder Class
class woo_shop_hacker_builder {


	// Class Properties
	static $total_pages = 0;
	static $total_records = 0;


	// Section Contents
	static function woo_shop_hacker_settings_after() {

		// Ensure Plugin Is Configured
		$mid = get_option( 'woo_shop_hacker_merchantid' );
		$apikey = get_option( 'woo_shop_hacker_apikey' );
		$apisecret = get_option( 'woo_shop_hacker_apisecret' );
		if( ! $mid || ! $apikey || ! $apisecret ) { return []; }

		// Get Search Results
		$query = isset( $_REQUEST['query'] ) ? sanitize_text_field( $_REQUEST['query'] ) : '';
		$page = isset( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : 1;
		$products = $query ? self::run_search( $query, $page ) : [];

		// Get Pagination
		$pagination = [ sprintf( 'Page: %d of %d', $page, self::$total_pages ) ];
		if( $page > 2 ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				1, $query, __( '&laquo; First Page', 'woo-shop-hacker' )
			);
		}
		if( $page > 1 ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				$page - 1, $query, __( '&laquo; Prev Page', 'woo-shop-hacker' )
			);
		}
		if( $page < self::$total_pages ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				$page + 1, $query, __( 'Next Page &raquo;', 'woo-shop-hacker' )
			);
		}
		if( $page < self::$total_pages - 1 ) {
			$pagination[] = sprintf(
				'<a href="admin.php?page=wc-settings&tab=settings_tab_shop_hacker&paginate=%d&query=%s">%s</a>',
				self::$total_pages, $query, __( 'Last Page &raquo;', 'woo-shop-hacker' )
			);
		}

		// Search Form
		?>
		<h2>Shop Hacker Inventory</h2>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="woo_shop_hacker_query"><?php _e( 'Search Products', 'woo-shop-hacker' ); ?></label>
					</th>
					<td class="forminp forminp-number">
						<p>
							<input type="text" id="woo_shop_hacker_query" name="query" value="" />
							<input class="button-secondary" type="submit" value="Search Products" />
						</p>
					</td>
				</tr>

				<?php if( $products ) { ?>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="woo_shop_hacker_query"><?php _e( 'Add to Store', 'woo-shop-hacker' ); ?></label>
					</th>
					<td class="forminp forminp-number">

						<?php
						foreach( $products as $product ) {

							// Get Description
							$description = '';
							foreach( $product->product_line_items as $val ) {
								$description .= str_replace( '"', '\\"', preg_replace( "/[\n\r]/", "", $val->description ) );
							}

							// Output
							echo sprintf(
								'
									<div>
										<p>
											<label title="%s"><input type="checkbox" name="product[]" value="%d" />
												<a href="#" onclick="pop%d(); return false;" class="dashicons dashicons-external"></a> %s
											</label>
											<script>
												function pop%d() {
													var pop%d = window.open( "", "Product-Window", "width=800,height=500" );
													pop%d.document.write( "%s" );
												}
											</script>
										</p>
									</div>
								',
								$product->bundle_headline,
								$product->id,
								$product->id,
								trim( $product->name ),
								$product->id,
								$product->id,
								$product->id,
								sprintf( "<html><head><title>%s</title></head><body>%s</body></html>", trim( $product->name ), $description )
							);
						}
						?>

						<br />
						<p>
							<input class="button-secondary" type="submit" value="Add to Store" /> &nbsp;
							<?php echo implode( ' &nbsp; ', $pagination ); ?>
						</p>
					</td>
				</tr>
				<?php } ?>

			</tbody>
		</table>
		<?php

		// Return Settings
		return [];	
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

		// Return Results
		return $products;
	}


// End Plugin Builder Class
}
