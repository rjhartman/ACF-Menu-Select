<?php

/*
Plugin Name: Advanced Custom Fields: Menu Selector
Plugin URI: https://github.com/RHartman99/ACF-Menu-Select
Description: Adds a WP menu selector field type to ACF.
Version: 1.0.1
Author: Ryan Hartman
Author URI: https://github.com/RHartman99
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if (!defined('ABSPATH')) exit;


// check if class already exists
if (!class_exists('nextlevel_acf_plugin_menu')) :

	class nextlevel_acf_plugin_menu
	{

		// vars
		var $settings;


		/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/

		function __construct()
		{

			// settings
			// - these will be passed into the field class.
			$this->settings = array(
				'version'	=> '1.0.0',
				'url'		=> plugin_dir_url(__FILE__),
				'path'		=> plugin_dir_path(__FILE__)
			);


			// include field
			add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
			add_action('acf/register_fields', 		array($this, 'include_field')); // v4
		}


		/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	void
	*/

		function include_field($version = false)
		{

			// support empty $version
			if (!$version) $version = 4;


			// load acf-menu-select
			load_plugin_textdomain('acf-menu-select', false, plugin_basename(dirname(__FILE__)) . '/lang');


			// include
			include_once('fields/class-nextlevel-acf-field-menu-v' . $version . '.php');
		}
	}


	// initialize
	new nextlevel_acf_plugin_menu();


// class_exists check
endif;
