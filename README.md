# ACF Menu Field

Adds a WP menu selector custom field type to [ACF](https://www.advancedcustomfields.com/). Will list all available WP menus and return the selected menu's slug.

## Installation

1. Copy the `acf-menu-select` folder into your `wp-content/plugins` folder
2. Activate the menu plugin via the plugins admin page
3. Create a new field via ACF and select the 'Menu Selector' type under the 'Choice' category
4. When using in the front end, be sure to get the menu object, the field will simply return the menu identifier string.

Front-end example:

Wordpress

```php
  wp_get_nav_menu_object(get_field('custom_menu'));
```

Timber (timber.php, page.php, etc..)

where
