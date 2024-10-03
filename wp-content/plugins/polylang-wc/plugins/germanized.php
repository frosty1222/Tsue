<?php
/**
 * @package Polylang-WC
 */

/**
 * Manages the compatibility with:
 *
 * @see https://wordpress.org/plugins/woocommerce-germanized/ WooCommerce Germanized, version tested: 3.8.2.
 *
 * This plugin already includes a compatibility with Polylang, not specifically PLLWC.
 * This class adds a quick fix for emails.
 *
 * @since 1.6.3
 */
class PLLWC_Germanized {

	/**
	 * Constructor.
	 *
	 * @since 1.6.3
	 */
	public function __construct() {
		// Prevents the new order email to admin to be sent twice.
		add_filter( 'woocommerce_germanized_order_email_admin_confirmation_sent', '__return_true' );
	}
}
