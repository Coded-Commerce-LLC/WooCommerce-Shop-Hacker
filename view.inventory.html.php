
<style>
	.shop_hacker_product_details {
		background-color: #ffffff;
		display: none;
		margin: 1em 0;
		padding: 1em;
	}
</style>

<h2><?php _e( 'Shop Hacker Inventory', 'woo-shop-hacker' ); ?></h2>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woo_shop_hacker_query"><?php _e( 'Search Products', 'woo-shop-hacker' ); ?></label>
			</th>
			<td class="forminp forminp-number">
				<p>
					<input type="text" id="woo_shop_hacker_query" name="query" value="<?php echo $query; ?>" />
					<input class="button-secondary" type="submit" value="<?php _e( 'Search Products', 'woo-shop-hacker' ); ?>" />
				</p>

				<?php if( empty( $products ) && $query ) { ?>
				<p><strong><?php _e( 'No results', 'woo-shop-hacker' ); ?></strong></p>
				<?php } ?>

			</td>
		</tr>

		<?php if( ! empty( $products ) ) { ?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woo_shop_hacker_query"><?php _e( 'Add to Store', 'woo-shop-hacker' ); ?></label>
			</th>
			<td class="forminp forminp-number">

				<dl>
					<?php
					foreach( $products as $product ) {
						woo_shop_hacker_builder::print_product( $product );
					}
					?>
				</dl>

				<p>
					<input class="button-secondary" type="submit" value="<?php _e( 'Add to Store', 'woo-shop-hacker' ); ?>" /> &nbsp;
					<?php echo implode( ' &nbsp; ', $pagination ); ?>
				</p>
			</td>
		</tr>
		<?php } ?>

	</tbody>
</table>
<hr />
