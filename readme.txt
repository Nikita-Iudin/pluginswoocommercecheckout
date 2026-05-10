=== Custom Store Plugin ===
Contributors: customstore
Tags: woocommerce, checkout, fields, customizer
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Customize WooCommerce checkout fields from the WordPress admin panel — labels, placeholders, order, visibility, and required status.

== Description ==

Custom Store Plugin lets store managers fully control WooCommerce checkout fields without writing code.

**Features:**

* Change field labels and placeholders
* Reorder fields via drag-and-drop
* Hide or show individual fields
* Toggle required / optional per field
* Support for billing and shipping fields
* AJAX-powered settings with nonce verification
* Native WordPress admin UI
* OOP modular architecture ready for integrations

== Installation ==

1. Upload the `custom-store-plugin` folder to `/wp-content/plugins/`
2. Activate through the Plugins menu
3. Navigate to **Custom Store → Checkout Fields** to configure

== Frequently Asked Questions ==

= Does this work with WooCommerce Blocks checkout? =

The plugin currently targets the classic shortcode checkout (`[woocommerce_checkout]`). Block checkout support is on the roadmap.

= Can I add custom fields? =

The current version manages existing WooCommerce billing/shipping fields. Custom field creation is planned for a future release.

== Changelog ==

= 1.0.0 =
* Initial release
* Checkout field customization (label, placeholder, priority, enabled, required)
* Drag-and-drop reordering
* AJAX save with nonce verification
* General settings page
* Modular OOP architecture
