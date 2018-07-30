<?php
/*
 * Plugin Name: WP Frontend
 * Description: Frontend Dashboard, Profile and Posting Manager for Wordpress.
 * Plugin URI:
 * Author URI: https://cybercraftit.com/
 * Author: CyberCraft
 * Text Domain: wpfront
 * Domain Path: /languages
 * Version: 1.0
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPFRONT_VERSION', '1.0' );
define( 'WPFRONT_ROOT', dirname(__FILE__) );
define( 'WPFRONT_ASSET_PATH', plugins_url('assets',__FILE__) );
define( 'WPFRONT_BASE_FILE', __FILE__ );
define( 'WPFRONT_PRODUCTION', true );

Class Wpfront_Init {
	/**
	 * @var Singleton The reference the *Singleton* instance of this class
	 */
	private static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'on_activate' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'action_links' ) );
		add_action( 'admin_head', array( $this, 'admin_head_includes' ) );
		$this->includes();
	}

	public function on_activate() {
		if( empty( Wpfront_Functions::get_installed_pages() ) ) {
			Wpfront_Functions::install_plugin_pages();
		}
	}

	public function action_links($links) {
		$links[] = '<a href="https://cybercraftit.com/contact/" target="_blank">'.__( 'Ask for Modification', 'wpfront' ).'</a>';
		if( ! Wpfront_Functions::is_pro() ) {
			$links[] = '<a href="https://cybercraftit.com/wp-frontend-pro/" style="color: #fa0000;" target="_blank">'.__( 'Upgrade to Pro', 'wpfront' ).'</a>';
		}
		return $links;
	}

	public function includes() {
		include_once 'form-type-data.php';
		include_once 'ajax-actions.php';
		include_once 'form-builder-admin.php';
		include_once 'functions.php';

		include_once 'shortcodes/edit.php';
		include_once 'shortcodes/dashboard.php';
		include_once 'shortcodes/form.php';
		include_once 'shortcodes/login.php';
		include_once 'shortcodes/registration.php';
		include_once 'shortcodes/shortcode.php';

		include_once 'submission-process.php';
		include_once 'wpfrontend-applications.php';

		require_once dirname(__FILE__).'/news.php';

		if( Wpfront_Functions::is_pro() ) {
			include_once 'pro/loader.php';
		} else {
			include_once 'pro-data.php';
		}
	}

	public function admin_head_includes() {
		include_once 'data.php';
	}


	public function register_post_type() {
		$capability = Wpfront_Functions::form_capability();

		$labels = array(
			'name'                  => _x('Form', 'post type general name', 'wpfront'),
			'singular_name'         => _x('Form', 'post type singular name','wpfront'),
			'menu_name'             => _x( 'Form', 'admin menu', 'wpfront'),
			'name_admin_bar'        => _x( 'Form', 'add new on admin bar', 'wpfront'),
			'add_new'               => _x('Add New Form', 'Form' , 'wpfront' ),
			'add_new_item'          => __('Add New Form', 'wpfront'),
			'edit_item'             => __('Edit Form', 'wpfront'),
			'new_item'              => __('New Form' , 'wpfront' ),
			'view_item'             => __('View Form', 'wpfront' ),
			'all_items'             => __( 'All Form', 'wpfront' ),
			'search_items'          => __('Search Form', 'wpfront' ),
			'not_found'             =>  __('Nothing found', 'wpfront' ),
			'not_found_in_trash'    => __('Nothing found in Trash', 'wpfront' ),
			'parent_item_colon'     => '',

		);

		register_post_type( 'wpfront_form', array(
			'label'           => __( 'Forms', 'wpfront' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => false,
			'capability_type' => 'post',
			'hierarchical'    => false,
			'query_var'       => false,
			'supports'        => array('title'),
			'capabilities' => array(
				'publish_posts'       => $capability,
				'edit_posts'          => $capability,
				'edit_others_posts'   => $capability,
				'delete_posts'        => $capability,
				'delete_others_posts' => $capability,
				'read_private_posts'  => $capability,
				'edit_post'           => $capability,
				'delete_post'         => $capability,
				'read_post'           => $capability,
			),
			'labels' => $labels,
		) );

		/**
		 * Entry post type
		 */
		$labels = array(
			'name'                  => _x('Entry', 'post type general name', 'wpfront'),
			'singular_name'         => _x('Entry', 'post type singular name','wpfront'),
			'menu_name'             => _x( 'Entry', 'admin menu', 'wpfront'),
			'name_admin_bar'        => _x( 'Entry', 'add new on admin bar', 'wpfront'),
			'add_new'               => _x('Add New Entry', 'Form' , 'wpfront' ),
			'add_new_item'          => __('Add New Entry', 'wpfront'),
			'edit_item'             => __('Edit Entry', 'wpfront'),
			'new_item'              => __('New Entry' , 'wpfront' ),
			'view_item'             => __('View Entry', 'wpfront' ),
			'all_items'             => __( 'All Entry', 'wpfront' ),
			'search_items'          => __('Search Entry', 'wpfront' ),
			'not_found'             =>  __('Nothing found', 'wpfront' ),
			'not_found_in_trash'    => __('Nothing found in Trash', 'wpfront' ),
			'parent_item_colon'     => '',

		);

		register_post_type( 'wpfront_entry', array(
			'label'           => __( 'Entries', 'wpfront' ),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => false,
			'capability_type' => 'post',
			'hierarchical'    => false,
			'query_var'       => false,
			'supports'        => array('title'),
			'capabilities' => array(
				'publish_posts'       => $capability,
				'edit_posts'          => $capability,
				'edit_others_posts'   => $capability,
				'delete_posts'        => $capability,
				'delete_others_posts' => $capability,
				'read_private_posts'  => $capability,
				'edit_post'           => $capability,
				'delete_post'         => $capability,
				'read_post'           => $capability,
			),
			'labels' => $labels,
		) );
	}
}

Wpfront_Init::get_instance();

