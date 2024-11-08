<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.borghydesing.it
 * @since      1.0.0
 *
 * @package    Smart_Insight_Lite
 * @subpackage Smart_Insight_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Smart_Insight_Lite
 * @subpackage Smart_Insight_Lite/admin
 * @author     Luca Borghese <lucaborghy@gmail.com>
 */
class Smart_Insight_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smart_Insight_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smart_Insight_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/smart-insight-lite-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Smart_Insight_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Smart_Insight_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smart-insight-lite-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'chartjs', plugin_dir_url( __FILE__ ) . 'js/chart.min.js', array( 'jquery' ), '4.4.6', false );
		wp_enqueue_script( 'show-visits-chart', plugin_dir_url( __FILE__ ) . 'js/show-visits-chart.min.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'show-visits-chart', 'visitsCharAnalytics', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('visits_char_analytics_nonce')
		));
	}

	function simple_analytics_add_admin_menu() {
		add_menu_page(
			'Simple Analytics Dashboard',
			'Analytics Dashboard',
			'manage_options',
			'simple-analytics-dashboard',
			array($this, 'simple_analytics_dashboard_page'),
			'dashicons-chart-bar',
			20
		);
	}

	function simple_analytics_dashboard_page() {
		global $wpdb;
		$cache_table_key = 'simple_analytics_table_data';
		$cache_table_ctrl = wp_cache_get($cache_table_key);
		
		$table_name = $wpdb->prefix . 'simple_analytics_data';

		// Recupera i dati delle ultime 10 visite per la tabella
		if ($cache_table_ctrl === false) {
			$query = $wpdb->prepare("SELECT * FROM {$table_name} ORDER BY visit_date DESC LIMIT %d", 50);
			$results = $wpdb->get_results($query);
			wp_cache_set($cache_table_key, $results, '', 3600); // Cache per 1 ora
		} else {
			$results = $cache_table_ctrl;
		}

		?>
		<div class="wrap">
			<h1>Elenco ultime visite</h1>
			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th>URL di pagina</th>
						<th>Lingua</th>
						<th>Durata visita (secondi)</th>
						<th>Data visita</th>
						<th>Eventi</th>
					</tr>
				</thead>
			<tbody>
		<?php

		if ($results) {
			foreach ($results as $row) {
				// Decodifica le azioni registrate in formato JSON
				$actions = json_decode(stripslashes($row->actions), true);
				$actions_display = [];

				// Mostra ogni azione con ID e classe
				if (is_array($actions)) {
					if (!empty($actions)) {
						foreach ($actions as $key => $action) {
							$action['id'] === "" ? $action['id'] = 'N/A' : $action['id'] = $action['id'];
							$action['class'] === "" ? $action['class'] = 'N/A' : $action['class'] = $action['class'];
							$actions_display[$key][] = 'Evento: ' . htmlspecialchars($action['event']);
							$actions_display[$key][] = 'Elemento: ' . htmlspecialchars($action['element']);
							$actions_display[$key][] = 'Id: ' . htmlspecialchars($action['id']);
							$actions_display[$key][] = 'Classi: ' . htmlspecialchars($action['class']);
							$actions_display[$key][] = 'Coord X: ' . htmlspecialchars($action['x']);
							$actions_display[$key][] = 'Coord Y: ' . htmlspecialchars($action['y']);
							$actions_display[$key][] = 'Data: ' . htmlspecialchars(gmdate('Y-m-d H:i:s', floor($action['timestamp'] / 1000)));
						}
					} 
				}

				?>
				<tr>
					<td><?php echo esc_html($row->page_url) ?></td>
					<td><?php echo esc_html($row->language) ?></td>
					<td><?php echo esc_html($row->time_spent) ?></td>
					<td><?php echo esc_html($row->visit_date) ?></td>
					<td><?php echo esc_html(count($actions) . ' click') ?></td>
				</tr>
				<?php
			}
		} else {
			echo '<tr><td colspan="5">Nessun dato disponibile</td></tr>';
		}
		?>
				</tbody>
			</table>
			<h1>Grafico ultime visite</h1>
			<canvas id="visitsChart" width="400" height="200"></canvas>
		</div>
		<?php
	}

	function get_visits_chart_data() {
		check_ajax_referer('visits_char_analytics_nonce', 'nonce');

		global $wpdb;
		$cache_graph_key = 'simple_analytics_graph_data';
		$cache_graph_ctrl = wp_cache_get($cache_graph_key);

		$table_name = $wpdb->prefix . 'simple_analytics_data';
	
		// Recupera e prepara i dati per il grafico
		if ($cache_graph_ctrl === false) {
			$query = $wpdb->prepare("SELECT DATE(visit_date) as visit_date, COUNT(*) as visits FROM $table_name GROUP BY DATE(visit_date) ORDER BY visit_date ASC LIMIT %d", 15);
			$results = $wpdb->get_results($query);
			wp_cache_set($cache_graph_key, $results, '', 3600); // Cache per 1 ora
		} else {
			$results = $cache_graph_ctrl;
		}
	
		$dates = [];
		$visit_counts = [];
		foreach ($results as $row) {
			$dates[] = $row->visit_date;
			$visit_counts[] = $row->visits;
		}
	
		wp_cache_delete($cache_graph_key);

		// Ritorna i dati in formato JSON
		wp_send_json_success([
			'dates' => $dates,
			'visit_counts' => $visit_counts
		]);
	}

}
