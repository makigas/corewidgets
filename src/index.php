<?php

/*
 * This file is part of the Makigas CoreWidgets library for WordPress.
 * Copyright (C) 2015 Dani Rodríguez <danirod@outlook.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @package makigas
 * @version 1.0.0
 */

/*
 * Plugin Name: makigas corewidgets
 * Plugin URI:  http://www.makigas.es
 * Description: Core widgets for usage within makigas themes and plugins.
 * Version:     1.0.0
 * Author:      Dani Rodríguez
 * Author URI:  http://www.danirod.es
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl.html
 * Domain Path: /languages
 * Text Domain: makigas-corewidgets
 */

defined('ABSPATH') or die('Please, do not execute this script directly.');

spl_autoload_register(function( $class_name ) {
    // Filter classes coming only from Makigas\CoreWidgets namespace.
    if (false === stripos($class_name, 'Makigas\CoreWidgets')) {
        return;
    }

    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    $root = plugin_dir_path(__FILE__) . 'lib' . DIRECTORY_SEPARATOR;
    $file = $root . $path;

    if (file_exists($file)) {
        require_once $file;
    } else {
        die('Error: class ' . $class_name . ' not found.');
    }
});

// Load translations.
add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'makigas-corewidgets', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
});

add_action('widgets_init', function() {
    // Register widgets.
    register_widget('Makigas\CoreWidgets\TextImageWidget');
});
