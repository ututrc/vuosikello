<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       utu.fi
 * @since      1.0.0
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Vuosikello
 * @subpackage Vuosikello/includes
 * @author     Sami Jokela <sami.jokela@utu.fi>
 */
class Vuosikello {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Vuosikello_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'vuosikello';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Vuosikello_Loader. Orchestrates the hooks of the plugin.
	 * - Vuosikello_i18n. Defines internationalization functionality.
	 * - Vuosikello_Admin. Defines all hooks for the admin area.
	 * - Vuosikello_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vuosikello-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vuosikello-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vuosikello-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-vuosikello-public.php';

		/**
		 * Vuosikello utilities. Namely to do with group creation and finding
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vuosikello-utils.php';

		$this->loader = new Vuosikello_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Vuosikello_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Vuosikello_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Vuosikello_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_vuosikello_visualizations_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_vuosikello_event_taxonomy' );
		$this->loader->add_action( 'init', $plugin_admin, 'vuosikello_events_custom_post_type_init' );

		$this->loader->add_filter( 'manage_edit-vuosikello_event_columns', $plugin_admin, 'vuosikello_events_edit_columns' );
		$this->loader->add_action( 'manage_pages_custom_column', $plugin_admin, 'vuosikello_events_custom_columns' );
		$this->loader->add_filter( 'manage_edit-vuosikello_event_sortable_columns', $plugin_admin, 'vuosikello_events_sortable_columns');
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'vuosikello_events_column_orderby');
		$this->loader->add_action( 'admin_init', $plugin_admin, 'vuosikello_events_create' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_vk_events' );
		$this->loader->add_action( 'init', $plugin_admin, 'add_vuosikello_shortcode');
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'vuosikello_load_text_domain');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Vuosikello_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'custom_public_vuosikello_post');
		$this->loader->add_action( 'init', $plugin_public, 'add_vuosikello_shortcode');
		$this->loader->add_filter( 'wp_insert_post_data', $plugin_public, 'save_vuosikello_post', '10', 2);
		$this->loader->add_filter( 'the_content', $plugin_public, 'insert_return_to_vuosikello');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Vuosikello_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
