<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://avinash.wisdmlabs.net/
 * @since             1.0.0
 * @package           Newsletter
 *
 * @wordpress-plugin
 * Plugin Name:       Newsletter
 * Plugin URI:        https://https://avinash.wisdmlabs.net/
 * Description:       This plugin is mainly used for the subscription purpose. The admin can send the email to their subscribers.
 * Version:           1.0.0
 * Author:            Avinash Jha
 * Author URI:        https://https://avinash.wisdmlabs.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       newsletter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEWSLETTER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-newsletter-activator.php
 */
function activate_newsletter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-newsletter-activator.php';
	Newsletter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-newsletter-deactivator.php
 */
function deactivate_newsletter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-newsletter-deactivator.php';
	Newsletter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_newsletter' );
register_deactivation_hook( __FILE__, 'deactivate_newsletter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-newsletter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_newsletter() {

	$plugin = new Newsletter();
	$plugin->run();

}
run_newsletter();
