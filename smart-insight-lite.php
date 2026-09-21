<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://lucaborghese.it
 * @since             1.0.0
 * @package           Smart_Insight_Lite
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Insight Lite
 * Plugin URI:        https://sil.lucaborghese.it
 * Description:       Aggiunge al tuo sito web una dashboard semplice e intuitiva con le metriche essenziali relative al traffico e alle conversioni.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Author:            Luca Borghese
 * Author URI:        https://lucaborghese.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       smart-insight-lite
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
define( 'SMART_INSIGHT_LITE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smart-insight-lite-activator.php
 */
function smart_insight_lite_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-insight-lite-activator.php';
	Smart_Insight_Lite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smart-insight-lite-deactivator.php
 */
function smart_insight_lite_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smart-insight-lite-deactivator.php';
	Smart_Insight_Lite_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'smart_insight_lite_activate' );
register_deactivation_hook( __FILE__, 'smart_insight_lite_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smart-insight-lite.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function smart_insight_lite_run() {

	$plugin = new Smart_Insight_Lite();
	$plugin->run();

}
smart_insight_lite_run();
