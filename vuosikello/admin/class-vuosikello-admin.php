<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       utu.fi
 * @since      1.0.0
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/admin
 * @author     Sami Jokela <sami.jokela@utu.fi>
 */
class Vuosikello_Admin {

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
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'vuosikello_settings';

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
		 * defined in Vuosikello_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vuosikello_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vuosikello-admin.css', array(), $this->version, 'all' );
		wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui' );

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
		 * defined in Vuosikello_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vuosikello_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vuosikello-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_register_script( 'd3', plugins_url( 'js/d3.min.js', __FILE__ ) );
		wp_enqueue_script( 'd3' );

		wp_register_script( 'd3_vuosikello', plugins_url( '/js/vuosikello.js', __FILE__ ), array('d3') );
		wp_enqueue_script( 'd3_vuosikello' );
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/vuosikello-admin-display.php';
	}

	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Vuosikello settings', 'vuosikello'),
			__( 'Vuosikello', 'vuosikello-settings' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		// Add a General section
	add_settings_section(
		$this->option_name . '_general',
		__( 'General', 'vuosikello' ),
		array( $this, $this->option_name . '_general_cb' ),
		$this->plugin_name
	);

	/*add_settings_field(
		$this->option_name . '_position',
		__( 'Vuosikello example option', 'vuosikello' ),
		array( $this, $this->option_name . '_position_cb' ),
		$this->plugin_name,
		$this->option_name . '_general',
		array( 'label_for' => $this->option_name . '_position' )
	);*/
	}

	public function vuosikello_settings_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', 'vuosikello' ) . '</p>';
	}

	public function vuosikello_settings_position_cb() {
		?>
			<fieldset>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_position' ?>" id="<?php echo $this->option_name . '_position' ?>" value="before">
					<?php _e( 'Option 1', 'outdated-notice' ); ?>
				</label>
				<br>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_position' ?>" value="after">
					<?php _e( 'Option 2', 'outdated-notice' ); ?>
				</label>
			</fieldset>
		<?php
	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_vuosikello_visualizations() {
		$daycare_permalink = Vuosikello_Utils::get_vuosikello_permalink();
		echo "<h3>Vuosikelloon: <a href=\"$daycare_permalink\">$daycare_permalink</a><br></h3>";
		//include_once dirname( dirname(__FILE__) ).'/includes/partials/vuosikello-visualizations.php';
	}

	public function add_vuosikello_visualizations_page() {
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Minun vuosikelloni', 'vuosikello-visualizations'),
			__( 'Minun vuosikelloni', 'vuosikello-visualizations' ),
			'read',
			'vuosikello-visualizations',
			array( $this, 'display_vuosikello_visualizations' )
		);
	}

	/**
	 * Create custom post type for vuosikello events
	 */
	function vuosikello_events_custom_post_type_init() {
	    $labels = array(
	        'name'                  => __( 'Vuosikello events', 'vuosikello' ),
	        'singular_name'         => __( 'Vuosikello event', 'vuosikello' ),
	        'menu_name'             => __( 'Vuosikello events', 'vuosikello' ),
	        'name_admin_bar'        => __( 'Vuosikello event', 'vuosikello' ),
	        'add_new'               => __( 'Add new', 'vuosikello' ),
	        'add_new_item'          => __( 'Add new vuosikello event', 'vuosikello' ),
	        'new_item'              => __( 'New vuosikello event', 'vuosikello' ),
	        'edit_item'             => __( 'Edit vuosikello event', 'vuosikello' ),
	        'view_item'             => __( 'View vuosikello event', 'vuosikello' ),
	        'all_items'             => __( 'All vuosikello events', 'vuosikello' ),
	        'search_items'          => __( 'Find vuosikello events', 'vuosikello' ),
	        'parent_item_colon'     => __( 'Parent Vuosikello Events:', 'vuosikello' ),
	        'not_found'             => __( 'Vuosikello evens not found.', 'vuosikello' ),
	        'not_found_in_trash'    => __( 'Vuosikello events not found from trash.', 'vuosikello' ),
	        'archives'              => _x( 'Vuosikello events archive', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'vuosikello' ),
	        'insert_into_item'      => _x( 'Add into vuosikello event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'vuosikello' ),
	        'uploaded_to_this_item' => _x( 'Uploaded to this Vuosikello Event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'vuosikello' ),
	        'filter_items_list'     => _x( 'Filter Vuosikello Events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'vuosikello' ),
	        'items_list_navigation' => _x( 'Vuosikello Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'vuosikello' ),
	        'items_list'            => _x( 'Vuosikello Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'vuosikello' ),
	    );

			$l_capabilities = array(
			  'edit_post'          => 'edit_vk_event',
			  'read_post'          => 'read_vk_event',
			  'delete_post'        => 'delete_vk_events',
			  'edit_posts'         => 'edit_vk_events',
			  'edit_others_posts'  => 'edit_others_vk_events',
			  'publish_posts'      => 'publish_vk_events',
			  'read_private_posts' => 'read_private_vk_events',
			  'create_posts'       => 'create_vk_events',
			);

	    $args = array(
	        'labels'             => $labels,
	        'public'             => true,
	        'publicly_queryable' => true,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
	        'query_var'          => true,
	        'rewrite'            => array( 'slug' => 'vuosikello-event' ),
	        'capabilities'    	 => $l_capabilities,
	        'has_archive'        => true,
	        'hierarchical'       => true,
	        'menu_position'      => null,
	        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'page-attributes' ),
					'menu_icon'					 => 'dashicons-calendar',
					'taxonomies'				 => array('vuosikello_event_category')
	    );

	    register_post_type( 'vuosikello_event', $args );
	}

	/**
	 *	Register taxonomy for vuosikello events
	 */
	 function create_vuosikello_event_taxonomy() {

			$labels = array(
				'name' 												=> __('Categories', 'vuosikello'),
				'singular_name' 							=> __('Category', 'vuosikello'),
				'search_items' 								=> __('Find categories', 'vuosikello'),
				'popular_items' 							=> __('Popular categories', 'vuosikello'),
				'all_items' 									=> __('All categories', 'vuosikello'),
				'parent_item' 								=> null,
				'parent_item_colon' 					=> null,
				'edit_item' 									=> __('Edit category', 'vuosikello'),
				'update_item' 								=> __('Update category', 'vuosikello'),
				'add_new_item' 								=> __('Add new category', 'vuosikello'),
				'new_item_name' 							=> __('Add category name'),
				'separate_items_with_commas' 	=> __('Separate categories with comma'),
				'add_or_remove_items'					=> __('Add or remove categories'),
				'choose_from_most_used'				=> __('Choose from most used categories'),
			);

			register_taxonomy('vuosikello_event_category', 'vuosikello_event', array(
				'label'					=> __('Event category'),
				'labels'				=> $labels,
				'hierarchical' 	=> true,
				'show_ui'				=> true,
				'query_var'			=> true,
				'rewrite'				=> array('slug' => 'vuosikello-event-category'),
			));

	 }

	 function vuosikello_events_edit_columns($columns) {

		 $columns = array(
			 "cb" => "<input type=\"checkbox\" />",
			 "title" => "Tapahtuma",
			 "vk_col_ev_cat" => "Kategoria",
			 "vk_col_ev_date" => "Päivämäärät",
			 /*"vk_vol_ev_desc" => "Description",*/
		 );
		 return $columns;

	 }

	 function vuosikello_events_custom_columns($column) {
		 global $post;
		 $custom = get_post_custom();
		 switch($column) {
			 case "vk_col_ev_cat":
			 	$eventcats = get_the_terms($post->ID, "vuosikello_event_category");
				$eventcats_html = array();
				if($eventcats) {
					foreach($eventcats as $eventcat)
					array_push($eventcats_html, $eventcat->name);
					echo implode($eventcats_html, ", ");
				} else {
					_e('Ei kategoriaa', 'themeforce');
				}
				break;
				case "vk_col_ev_date":
				    // - show dates -
				    $startd = $custom["vk_events_startdate"][0];
				    $endd = $custom["vk_events_enddate"][0];
				    $startdate = date_i18n("F j, Y", $startd);
				    $enddate = date_i18n("F j, Y", $endd);
				    echo $startdate . '<br /><em>' . $enddate . '</em>';
				break;
		 }
	 }

	 function vuosikello_events_sortable_columns($columns) {
		 return array_merge($columns, array("vk_col_ev_cat" => "vk_col_ev_cat", "vk_col_ev_date" => "vk_col_ev_date"));
	 }

	 function vuosikello_events_column_orderby($query) {
		 if(!is_admin()) {
			 return;
		 }
		 $custom = get_post_custom();
		 $orderby = $query->get('orderby');
		 if($orderby == 'vk_col_ev_date') {
			 $query->set('orderby', 'meta_value');
			 $query->set('meta_key', 'vk_events_startdate');
		 }
	 }

	 function vuosikello_events_create() {
		 add_meta_box('vk_events_meta', 'Event dates', array($this, 'vk_events_meta'), 'vuosikello_event');
	 }

	 function vk_events_meta($post_id) {

		 global $post;
		 $custom = get_post_custom($post->ID);
		 $meta_sd = null;
		 $meta_ed = null;
		 if(isset($custom["vk_events_startdate"][0]) && isset($custom["vk_events_enddate"][0])) {
			 $meta_sd = $custom["vk_events_startdate"][0];
			 $meta_ed = $custom["vk_events_enddate"][0];
	 		}

		 $date_format = get_option('date_format');

		 if($meta_sd == null) {
			 $meta_sd = time();
			 $meta_ed = $meta_sd;
		 }

		 $clean_sd = date("d.m.Y", $meta_sd);
		 $clean_ed = date("d.m.Y", $meta_ed);

		 echo '<input type="hidden" name="vk-events-nonce" id="vk-events-nonce" value="' . wp_create_nonce('vk-events-nonce') . '" />';

		 ?>
		 <div class="vk-meta">
			 <ul>
			 	<li><label>Start date</label><input name="vk_events_startdate" class="vkdate" value="<?php echo $clean_sd; ?>" /></li>
				<li><label>End date</label><input name="vk_events_enddate" class="vkdate" value="<?php echo $clean_ed; ?>" /></li>
			 </ul>
		 </div>
		 <script>
    jQuery(document).ready(function($) {
        $(".vkdate").datepicker({ dateFormat: 'dd.mm.yy' });
    });
</script>
		 <?php
	 }

	 function save_vk_events($post_id) {
		 global $post;

		 if(!isset($_POST['vk-events-nonce']) || !wp_verify_nonce( $_POST['vk-events-nonce'], 'vk-events-nonce')) {
			 return $post_id;
		 }

		 if( !current_user_can( 'edit_post', $post_id))
		 	return $post_id;


			if(!isset($_POST["vk_events_startdate"])):
				return $post_id;
			endif;

			$updatestartd = strtotime ($_POST["vk_events_startdate"]);
			update_post_meta($post_id, "vk_events_startdate", $updatestartd);

			if(!isset($_POST["vk_events_enddate"])):
				return $post_id;
			endif;
			$ignored_groups = array('Registered', 'admin');
			$groups_user = new Groups_User(get_current_user_id());
			$groups = $groups_user->groups;
			foreach($groups as $group) {
				if(!in_array($group->name, $ignored_groups)) {
					$group_set = $group->name;
					$res = Groups_Post_Access::create(array('group_id' => $group->group_id, 'post_id' => $post_id));
					echo "<script>console.log('Post $post_id set to group $group_set: $res');</script>";
				}
			}
			$updateendd = strtotime ( $_POST[vk_events_enddate]);
			update_post_meta($post_id, "vk_events_enddate", $updateendd);
	 }

	 public function add_vuosikello_shortcode() {
		 	add_shortcode( 'vuosikello', array( $this , 'vuosikello_shortcode_func' ) );
	 }

	  public function vuosikello_shortcode_func() {
		 ob_start();
		 include_once dirname( dirname(__FILE__) ).'/includes/partials/vuosikello-visualizations.php';
		 return ob_get_clean();
	 }

	 function vuosikello_load_text_domain() {
		 	load_plugin_textdomain( 'vuosikello', null, 'vuosikello/languages/' );
	 }
}
