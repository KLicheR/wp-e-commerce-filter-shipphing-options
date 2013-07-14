<?php
/*
Plugin Name: WP e-Commerce - Filter Shipping Options
Plugin URI: 
Description: Filter for "wp-e-commerce" shipping options.
Version: 1.0
Author: KLicheR
Author URI: http://profiles.wordpress.org/klicher
License: 
*/

/**
 * Filter the cart of "wp-e-commerce" to add/remove the
 * "fixed_rate_shipping_option" of the "wp-e-commerce-fixed-rate-shipping"
 * plugin based on user's *fixed_rate_shipping_option* capability.
 */
function wp_e_commerce_filter_shipphing_options_remove_shipping_option_based_on_roles($profile) {
	if (array_key_exists('cart', $profile)) {
		$cart = maybe_unserialize($profile['cart']);
		if (
			is_object($cart)
			&& !is_wp_error($cart)
			&& property_exists($cart, 'shipping_methods')
			&& is_array($cart->shipping_methods)
		) {
			$key = array_search('simple_shipping', $cart->shipping_methods);

			// If the user have access to "fixed_rate_shipping_option"...
			if (current_user_can('fixed_rate_shipping_option')) {
				// ...and doesn't have it...
				if (false === $key) {
					// ...replace the methods by what's set in the admin.
					$cart->shipping_methods = get_option('custom_shipping_options', array());
					$profile['cart'] = maybe_serialize($cart);
				}
			}
			// If the user doesn't have access to "fixed_rate_shipping_option"...
			else{
				// ...and have it...
				if (false !== $key) {
					// ...remove it.
					array_splice($cart->shipping_methods, $key, 1);
					$profile['cart'] = maybe_serialize($cart);
				}
			}
		}
	}

	return $profile;
}
add_filter('wpsc_get_all_customer_meta', 'wp_e_commerce_filter_shipphing_options_remove_shipping_option_based_on_roles');