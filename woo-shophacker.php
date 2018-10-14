<?php
/**
 * Plugin Name: Woo Shop Hacker
 * Plugin URI: https://codedcommerce.com/product/woo-shophacker/
 * Description: Connects WooCommerce with Shop Hacker for syncing your products.
 * Version: 1.0
 * Author: Coded Commerce, LLC
 * Author URI: https://codedcommerce.com
 * Developer: Sean Conklin
 * Developer URI: https://seanconklin.wordpress.com
 * Text Domain: woo-shophacker
 * Domain Path: /languages
 *
 * WC requires at least: 3.0
 * WC tested up to: 3.4.5
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit If Accessed Directly
if( ! defined( 'ABSPATH' ) ) { exit; }

// Make Sure WooCommerce Is Activated
if(
	in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )
) {

	// Include Files
	require_once( 'class.api.php' );
	require_once( 'class.builder.php' );
	require_once( 'class.settings.php' );

	// Plugin Hooks
	add_action( 'woo_shophacker_settings_after', [ 'woo_shophacker_builder', 'init' ] );
}
