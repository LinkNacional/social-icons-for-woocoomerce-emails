=== Social Icons for WooCommerce Emails ===
Contributors: linknacional
Donate link: https://linknacional.com.br/
Requires at least: 6.0
Requires PHP: 8.2
Tested up to: 7.1
Stable tag: 2.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: woocommerce, email, social, links, footer

Add social icons to your WooCommerce emails.

== Description ==

Add social icons to your WooCommerce emails.

#### SETUP

Go to WooCommerce > Settings > Emails to configure the plugin.

#### USEFUL LINKS

Github: https://github.com/LinkNacional/social-icons-for-woocommerce-emails

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/social-icons-for-wc-emails` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Go to WooCommerce > Settings > Emails to configure the plugin.

== Frequently Asked Questions ==

= How I can add/remove social networks? =

Yes. Use the filter hook `siwce_social_links`.

Example:

`// add this to your functions.php
add_filter( 'siwce_social_links', '__add_vk_wc_emails' );
function __add_vk_wc_emails( $icons ) {
	$icons['vk'] = 'VK';
	return $icons;
}`

= How I can add/change an icon image? =

Use the filter hook `siwce_icon_image_uri`.

Example:

`// add this to your functions.php
add_filter( 'siwce_icon_image_uri', 'yourprefix_change_facebook_icon', 10, 2 );
function yourprefix_change_facebook_icon( $uri, $icon_id ) {
	if ( $icon_id == 'facebook' ) {
		$uri = get_stylesheet_directory_uri() . '/path/to/your/facebook_icon.png';
	}
	return $uri;
}`

== Screenshots ==

1. Default icons appearance

2. WooCommerce > Settings > Emails page

== Changelog ==

= 2.2.0 - 2026-08-17 =
* Fixed first-install rendering: icons without a configured URL are no longer output and icon size falls back to the default (48px) when the option is not yet saved
* Removed compiled translation files (.po/.mo), keeping only the .pot template
* Added WordPress.org directory banners (wp-assets)
* WordPress.org compliance adjustments in the plugin header and readme.txt

= 2.1.1 =
* Some fixes
* Bump Tested up to

= 2.1.0 =
* removed Google+ support, since it not exists anymore
* changed plugin license to GNU General Public License v3.0

= 2.0.4 =
* compatibility with woocommerce pre-update extension version check

= 2.0.3 =
* updated banner and icon

= 2.0 =
* feature: option to add a text before of the icons.
* feature: simplified the size of icons (now is just 1 option, instead of two)
* feature: translated to pt-BR
* fix: several values have been sanitized

= 1.0 =
* Initial release.

== Upgrade Notice ==
