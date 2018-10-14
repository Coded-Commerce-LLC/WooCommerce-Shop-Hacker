<?php

// Plugin Builder Class
class woo_shophacker_builder {

	// Search Form
	static function init() {
		self::search();
	?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="woo_shophacker_query"><?php _e( 'Search Query', 'woo-shophacker' ); ?></label>
					</th>
					<td class="forminp forminp-number">
						<p>
							<input type="text" id="woo_shophacker_query" name="query" value="" />
							<input class="button-secondary" type="submit" value="Search Products" />
						</p>
					</td>
				</tr>
			</tbody>
		</table>

	<?php
	}

	// Search Processor
	static function search() {

		// Get Submitted Query
		$query = isset( $_POST['query'] )
			? sanitize_text_field( $_POST['query'] ) : '';

		// Run Query
		if( $query ) {
			$response = woo_shophacker_api::get_search_results( $query );
			$products = isset( $response->products ) ? $response->products : [];
			$meta = isset( $response->meta ) ? $response->meta : '';
			$total_pages = isset( $meta->total_pages ) ? intval( $meta->total_pages ) : 0;
			$total_records = isset( $meta->total_records ) ? intval( $meta->total_records ) : 0;

			// Output Results
			?>
			<div class="notice notice-info is-dismissible">

				<p>Total Pages: <?php echo $total_pages; ?></p>
				<p>Total Records: <?php echo $total_records; ?></p>

				<ol>
					<?php
					foreach( $products as $product ) {
						echo sprintf(
							"<li>%s</li>", trim( $product->name )
						);
					}
					?>
				</ol>

			</div>
			<?php
		}	
	}

// End Plugin Builder Class
}
