<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.borghydesing.it
 * @since      1.0.0
 *
 * @package    Smart_Insight_Lite
 * @subpackage Smart_Insight_Lite/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Smart_Insight_Lite
 * @subpackage Smart_Insight_Lite/includes
 * @author     Luca Borghese <lucaborghy@gmail.com>
 */
class Smart_Insight_Lite_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$instance = new self();
		$instance->simple_analytics_create_tables();

	}

	public function simple_analytics_create_tables() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'simple_analytics_data';
		$charset_collate = $wpdb->get_charset_collate();

		// Controlla se la tabella esiste già
		$query = "SHOW TABLES LIKE {$table_name}";
		if ($wpdb->get_var($query) != $table_name) {
			$sql = "CREATE TABLE {$table_name} (
				id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				page_url VARCHAR(255) NOT NULL,
				visit_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
				language VARCHAR(50) DEFAULT '' NOT NULL,
				time_spent INT DEFAULT 0,
				actions TEXT,
				PRIMARY KEY  (id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		} else {
			//error_log('La tabella esiste già.');
		}
	}

}
