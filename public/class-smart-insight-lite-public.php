<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.borghydesing.it
 * @since      1.0.0
 *
 * @package    Smart_Insight_Lite
 * @subpackage Smart_Insight_Lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Smart_Insight_Lite
 * @subpackage Smart_Insight_Lite/public
 * @author     Luca Borghese <lucaborghy@gmail.com>
 */
class Smart_Insight_Lite_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/smart-insight-lite-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/smart-insight-lite-public.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'simple-analytics-tracker', plugin_dir_url( __FILE__ ) . 'js/tracker.min.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'simple-analytics-tracker', 'simpleAnalytics', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('simple_analytics_nonce')
		));
	}

	function simple_analytics_track_visit() {
		check_ajax_referer('simple_analytics_nonce', 'nonce');
	
		global $wpdb;
		$table_name = $wpdb->prefix . 'simple_analytics_data';
		$cache_key = 'simple_analytics_data';
	
		if (isset($_POST['page_url'])) {
			$page_url = sanitize_text_field(wp_unslash($_POST['page_url']));
		} else {
			$page_url = "";
		}
		if (isset($_POST['time_spent'])) {
			$time_spent = intval($_POST['time_spent']);
		} else {
			$time_spent = 0;
		}
		if (isset($_POST['visit_date'])) {
			$visit_date = sanitize_text_field(wp_unslash($_POST['visit_date']));
		} else {
			$visit_date = "";
		}
		if (isset($_POST['actions'])) {
			$actions = sanitize_text_field(wp_unslash($_POST['actions'])); // Azioni dell'utente in formato JSON
		} else {
			$actions = "";
		}
		if (isset($_POST['language'])) {
			$language = sanitize_text_field(wp_unslash($_POST['language']));
		} else {
			$language = "";
		}
		
		$data = [
			'page_url'   => $page_url,
			'language'   => $language,
			'time_spent' => $time_spent,
			'visit_date' => $visit_date,
			'actions'    => $actions,
		];
		
		$formati = ['%s', '%s', '%d', '%s', '%s'];
		
		$wpdb->insert($table_name, $data, $formati);

		wp_cache_delete($cache_key);
	
		wp_send_json_success();
	}

}
