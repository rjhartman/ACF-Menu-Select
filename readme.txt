=== Advanced Custom Fields: Menu Field ===
Contributors: Ryan Hartman
Tags: acf, custom fields,
Requires at least: 3.6.0
Tested up to: 4.9.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a WP menu selector custom field type to ACF.

== Description ==

Adds a WP menu selector custom field type to ACF. Will list all available WP menus and return the slug of the WP menu object.

= Compatibility =

This ACF field type is compatible with:
* ACF 5
* ACF 4

== Installation ==

1. Copy the `acf-menu-select` folder into your `wp-content/plugins` folder
2. Activate the menu plugin via the plugins admin page
3. Create a new field via ACF and select the 'Menu Selector' type under the 'Choice' category
4. When using in the front end, be sure to get the menu object, the field will simply return the menu identifier string.

== Changelog ==

= 2.0.0 =
* Use caching to fix default values changing.

= 1.1.0 =
* Fixed default value bug where the default value could not be changed.

= 1.0.1 =
* Added default value functionality

= 1.0.0 =
* Initial Release.