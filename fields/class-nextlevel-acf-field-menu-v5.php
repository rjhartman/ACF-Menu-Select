<?php

// exit if accessed directly
if (!defined('ABSPATH')) exit;


// check if class already exists
if (!class_exists('nextlevel_acf_field_menu')) :

	class nextlevel_acf_field_menu extends acf_field
	{
		public static $test = 'pls work';

		/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

		function __construct($settings)
		{

			/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

			$this->name = 'menu';


			/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

			$this->label = __('Menu Selector', 'acf-menu-select');


			/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

			$this->category = 'choice';


			/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

			$this->defaults = array(
				'default_menu'	=> 1
			);


			/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('FIELD_NAME', 'error');
		*/

			$this->l10n = array(
				'error'	=> __('Error! Please enter a higher value', 'acf-menu-select'),
			);


			/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

			$this->settings = $settings;

			$this->cache = $settings['path'] . "nextlevel_json.json";

			if(!file_exists($this->cache)) {
				fclose(fwrite(fopen($this->cache, "w"), "{}"));
			}


			// do not delete!
			parent::__construct();
		}
		/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

		function render_field_settings($field)
		{

			/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
			if (!function_exists("nextlevel_get_nav_menus")) {
				function nextlevel_get_nav_menus()
				{
					$arr = [];
					foreach (wp_get_nav_menus() as $_ => $obj) {
						$arr[$obj->slug] = $obj->name;
					}
					return $arr;
				}
			}

			acf_render_field_setting($field, array(
				'label'			=> __('Default Menu', 'acf-menu-select'),
				'instructions'	=> __('Set a menu to default to, instead of just the first one registered (almost always main-menu).', 'acf-menu-select'),
				'type'			=> 'select',
				'choices' => nextlevel_get_nav_menus(),
				'name'			=> 'default_menu',
			));
		}



		/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

		static function parse_json_file($filename) {
			try {
				$fp = fopen($filename, 'r');
				$raw_json = fread($fp, filesize($filename));
				$json = json_decode($raw_json, true);
				fclose($fp);
				return $json;
			} catch(Exception $_) {
				return array();
			}
		}

		function get_cached_value($key)
		{
			$json = $this->parse_json_file($this->cache);
			return $json[$key];
		}

		function save_to_cache($key, $value) {
			try {
				$json = $this->parse_json_file($this->cache);
				$json[$key] = $value;
				$fp = fopen($this->cache, 'w');
				fwrite($fp, json_encode($json));
				fclose($fp);
				return true;
			} catch(Exception $_) {
				return false;
			}
		}

		function remove_from_cache($key) {
			try {
				$json = $this->parse_json_file($this->cache);
				unset($json[$key]);
				$fp = fopen($this->cache, 'w');
				fwrite($fp, json_encode($json));
				fclose($fp);
				return true;
			} catch(Exception $_) {
				return false;
			}
		}

		function render_field($field)
		{


			/*
		*  Review the data of $field.
		*  This will show what data is available
		*/
			echo '<pre>';
				// print_r($field);
				// print_r($this->cache);
			echo '</pre>';

			// If the field's value is empty, then we need to fill it with the default value.
			// Save to the cache the default value that we are using.
			if(empty($field['value'])) {
				$value = $field["default_menu"];
				$this->save_to_cache($field['id'], $value);
			}
			// If the field's value is already set and matches the cached value,
			// then we were using the default value. Thus, use the default menu in case it has changed,
			// and update the cache.
			else if($field['value'] === $this->get_cached_value($field["id"]) && $this->get_cached_value($field["id"]) === true) {
				$value = $field['default_menu'];
				$this->save_to_cache($field['id'], $value);
			}
			// Otherwise, we were not using the default value, and it should remain unchanged.
			else {
				$value = $field['value'];
				$this->remove_from_cache($field['id']);
			}

?>
			<select name="<?= esc_attr($field['name']) ?>" value="<?= $value ?>">
				<?php
				if (empty($field['value']))
					echo '<option disabled selected value=' . esc_attr($field['default_menu']) . ' style="display:none;"> Default Menu (' . esc_html(ucwords(str_replace(["_", "-"], " ", $field['default_menu']))) . ')</option>';
				else 
					echo '<option value="">Default Menu (' . esc_html(ucwords(str_replace(["_", "-"], " ", $field['default_menu']))) . ')</option>';

				foreach (wp_get_nav_menus() as $_ => $obj)
					if ($field['value'] === $obj->slug)
						echo '<option selected value=' . esc_attr($obj->slug) . '>' . esc_html($obj->name) . '</option>';
					else
						echo '<option value=' . esc_attr($obj->slug) . '>' . esc_html($obj->name) . '</option>';
				?>
				
			</select>

<?php
		}
	}


	// initialize
	new nextlevel_acf_field_menu($this->settings);


// class_exists check
endif;

?>