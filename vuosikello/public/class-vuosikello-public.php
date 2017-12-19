<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       utu.fi
 * @since      1.0.0
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/public
 * @author     Sami Jokela <sami.jokela@utu.fi>
 */
class Vuosikello_Public {

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
		 * defined in Vuosikello_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vuosikello_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vuosikello-public.css', array(), $this->version, 'all' );
		wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );

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
		 * defined in Vuosikello_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vuosikello_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vuosikello-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_register_script( 'd3', plugins_url( '/js/d3.min.js', __FILE__ ) );
		wp_enqueue_script( 'd3' );

		wp_register_script( 'vuosikello', plugins_url( '/js/vuosikello.js', __FILE__ ), array('d3') );
		wp_enqueue_script( 'vuosikello' );
	}

	public function custom_public_vuosikello_post() {
		$labels = array(
				'name'                  => __( 'Daycare center', 'päiväkoti' ),
				'singular_name'         => __( 'Daycare center', 'päiväkoti' ),
				'menu_name'             => __( 'Daycare center', 'päiväkoti' ),
				'name_admin_bar'        => __( 'Daycare center', 'päiväkoti' ),
				'add_new'               => __( 'Add new', 'päiväkoti' ),
				'add_new_item'          => __( 'Add new daycare', 'päiväkoti' ),
				'new_item'              => __( 'New daycare', 'päiväkoti' ),
				'edit_item'             => __( 'Edit daycare', 'päiväkoti' ),
				'view_item'             => __( 'View daycare', 'päiväkoti' ),
				'all_items'             => __( 'All daycares', 'päiväkoti' ),
				'search_items'          => __( 'Find daycares', 'päiväkoti' ),
				'parent_item_colon'     => __( 'Parent Daycare:', 'päiväkoti' ),
				'not_found'             => __( 'Daycare not found.', 'päiväkoti' ),
				'not_found_in_trash'    => __( 'Daycare not found from trash.', 'päiväkoti' ),
				'archives'              => _x( 'Daycare archive', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'päiväkoti' ),
				'insert_into_item'      => _x( 'Add into daycare', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'päiväkoti' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this daycare', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'päiväkoti' ),
				'filter_items_list'     => _x( 'Filter daycare list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'päiväkoti' ),
				'items_list_navigation' => _x( 'Daycare list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'päiväkoti' ),
				'items_list'            => _x( 'Daycare list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'päiväkoti' ),
		);

		$l_capabilities = array(
		  'edit_post'          => 'edit_daycare',
		  'read_post'          => 'read_daycare',
		  'delete_post'        => 'delete_daycare',
		  'edit_posts'         => 'edit_daycares',
		  'edit_others_posts'  => 'edit_others_daycares',
		  'publish_posts'      => 'publish_daycares',
		  'read_private_posts' => 'read_private_daycares',
		  'create_posts'       => 'create_daycares',
		);

		$args = array(
				'labels'             => $labels,
				/*'label' => 'Päiväkoti',*/
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'vuosikello-post' ),
				'capabilities'       => $l_capabilities,
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', /*'excerpt',*/ 'comments', 'page-attributes' ),
				'menu_icon'					 => 'dashicons-admin-multisite',
				/*'taxonomies'				 => array('vuosikello_post_category')*/
		);
		register_post_type('vuosikello_post', $args);
	}

	public function save_vuosikello_post($data, $postarr) {
		if('vuosikello_post' != $data['post_type']) {
			return $data;
		}
		$header = '<h3>'.$data['post_title'].':'.' Vuosikello.<br></h3>';
		$shortcode = '[vuosikello]';
		$data['post_content'] = $header.$shortcode;
		return $data;
	}

	public function add_vuosikello_shortcode() {
		 add_shortcode( 'vuosikello', array( $this , 'vuosikello_shortcode_func' ) );
	}

	 public function vuosikello_shortcode_func() {
		ob_start();
		include_once dirname( dirname(__FILE__) ).'/includes/partials/vuosikello-visualizations.php';
		return ob_get_clean();
	}

	public function insert_return_to_vuosikello($content) {
		global $post;
		if($post->post_type == 'vuosikello_event' && Groups_Post_Access::user_can_read_post($post->ID)) {
			$content .= '<br><a href='.Vuosikello_Utils::get_vuosikello_permalink().'>Takaisin vuosikelloon</a>';
		}
		return $content;
	}

}
