=== Plugin Name ===
Contributors: KLicheR
Donate link: 
Tags: 
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.1
License: 
License URI: 

Filter for "wp-e-commerce" shipping options.

== Description ==

Filter the checkout shipping options of **wp-e-commerce** to remove the *simple_shipping* of the **wp-e-commerce-fixed-rate-shipping** plugin based on user's *fixed_rate_shipping_option* capability.

== Installation ==

1. Upload `wp-e-commerce-filter-shipphing-options` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a capability (permission) named "fixed_rate_shipping_option" through your favorite *user roles manager* plugin

== Changelogs ==

=== 1.1 ===

* The filter was no more effective in the checkout after using the "Calculate" shipping button.