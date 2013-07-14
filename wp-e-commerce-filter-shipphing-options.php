<?php
/*
Plugin Name: WP e-Commerce - Filter Shipping Options
Plugin URI: 
Description: Filter for "wp-e-commerce" shipping options.
Version: 1.1
Author: KLicheR
Author URI: http://profiles.wordpress.org/klicher
License: 
*/

// Determine if a user currently logged in and have the capability "fixed_rate_shipping_option".
$wp_e_commerce_filter_shipphing_options_user_login_has_capability = false;

/**
 * Filter the option "custom_shipping_options" and remove the shipping option
 * "simple_shipping" if the current user or the user that try to logged in
 * doesn't have the capability "fixed_rate_shipping_option".
 */
function wp_e_commerce_filter_shipphing_options_filter_option_custom_shipping_options($option) {
	global $wp_e_commerce_filter_shipphing_options_user_login_has_capability;

	$key = array_search('simple_shipping', $option);
	// If the shipping option "simple_shipping" exists...
	// ...and the current user doesn't have the capability "fixed_rate_shipping_option"...
	// ...and no user are currently trying to logged in and have the capability "fixed_rate_shipping_option".
	if (
		false !== $key
		&& !current_user_can('fixed_rate_shipping_option')
		&& false === $wp_e_commerce_filter_shipphing_options_user_login_has_capability
	) {
		// ...remove it.
		array_splice($option, $key, 1);
	}

	return $option;
}
add_filter('option_custom_shipping_options', 'wp_e_commerce_filter_shipphing_options_filter_option_custom_shipping_options');

/**
 * Update the shipping methods of the current cart when a user logged in and
 * have the capability "fixed_rate_shipping_option".
 */
function wp_e_commerce_filter_shipphing_options_login($user_login, $user) {
	global $wpsc_cart, $wp_e_commerce_filter_shipphing_options_user_login_has_capability;

	// If the user that logged in have the capability "fixed_rate_shipping_option".
	if (
		is_object($user)
		&& property_exists($user, 'allcaps')
		&& is_array($user->allcaps)
		&& isset($user->allcaps['fixed_rate_shipping_option'])
		&& $user->allcaps['fixed_rate_shipping_option'] === true
	) {
		// Let the "wp_e_commerce_filter_shipphing_options_filter_option_custom_shipping_options"
		// filter do his job cause the "current_user_can" function doesn't work
		// at this point.
		$wp_e_commerce_filter_shipphing_options_user_login_has_capability = true;
		// Update the shipping methods.
		$wpsc_cart->get_shipping_method();

		$wp_e_commerce_filter_shipphing_options_user_login_has_capability = false;
	}
}
add_action('wp_login', 'wp_e_commerce_filter_shipphing_options_login', 10, 2);