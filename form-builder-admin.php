<?php

class Wpfront_Admin {
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
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
        add_action( 'admin_footer', array( $this, 'admin_footer' ) );
        add_action( 'wpfront_prepend_scripts_styles', array( $this, 'form_type_data' ) );
        add_action( 'wpfront_prepend_scripts_styles', array( $this, 'prepend_scripts_styles' ) );
    }

    public function register_admin_menu() {
        global $submenu;

        $capability = Wpfront_Functions::form_capability();
        $hook = add_menu_page( __( 'WP Frontend - The Best and Fastest Form Builder Ever', 'wpfront' ), 'WP Frontend', $capability, 'wpfront', array( $this, 'wpfront_page') );

        if ( current_user_can( $capability ) ) {
            $submenu['wpfront'][] = array( __( 'All Forms', 'wpfront' ), $capability, 'admin.php?page=wpfront#/' );
            $submenu['wpfront'][] = array( __( 'Add Form', 'wpfront' ), $capability, 'admin.php?page=wpfront#/forms/form-types' );

	        if( !class_exists( 'NeoForms_Init' ) ) {
		        $submenu['wpfront'][] = array( __( 'Contact Form', 'wpfront' ), $capability, 'admin.php?page=wpfront#/forms/promo-contact-form' );
	        }
	        if( !class_exists( 'NeoForms_Pro_Init' ) ) {
		        $submenu['wpfront'][] = array( __( 'Registration Form', 'wpfront' ), $capability, 'admin.php?page=wpfront#/forms/promo-registration-form' );
	        }

            $submenu = apply_filters( 'wpfront_admin_menu', $submenu, $hook, $capability );
            do_action( 'wpfront_admin_menu', $submenu, $hook, $capability );
        }

        // only admins should see the settings page
        if ( current_user_can( 'manage_options' ) ) {
            $submenu['wpfront'][] = array( __( 'Settings', 'wpfront' ), 'manage_options', 'admin.php?page=wpfront#/settings' );
        }

	    $submenu['wpfront'][] = array( __( 'Help', 'wpfront' ), 'manage_options', 'admin.php?page=wpfront#/help' );

        add_action( 'load-'. $hook, array( $this, 'load_scripts' ) );
    }


    public function wpfront_page() {
        include_once WPFRONT_ROOT.'/templates/main.php';
    }

    public function load_scripts() {
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_style('wpfront-framework-css', WPFRONT_ASSET_PATH.'/css/framework.css' );
        wp_enqueue_style('wpfront-style-css', WPFRONT_ASSET_PATH.'/css/style.css' );
        wp_enqueue_style('wpfront-element-css', WPFRONT_ASSET_PATH.'/css/element.css' );

        wp_enqueue_script('wpfront-vue', WPFRONT_ASSET_PATH.'/js/vue.js', array(), false, true );
        wp_enqueue_script('wpfront-vue-router', WPFRONT_ASSET_PATH.'/js/vue-router.min.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-vuex', WPFRONT_ASSET_PATH.'/js/vuex.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-functions', WPFRONT_ASSET_PATH.'/js/functions.js' );
        wp_enqueue_script('wpfront-formbuilder-js', WPFRONT_ASSET_PATH.'/js/templates/core/form-builder.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-element-js', WPFRONT_ASSET_PATH.'/js/element.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-element-en-js', WPFRONT_ASSET_PATH.'/js/element-en.js', array( 'wpfront-vue' ), false, true );

        /*form data*/
        do_action('wpfront_prepend_scripts_styles' );

        wp_enqueue_script('wpfront-form-type-data-js', WPFRONT_ASSET_PATH.'/js/form-type-data.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-form-fields-js', WPFRONT_ASSET_PATH.'/js/form-fields.js', array( 'wpfront-vue' ), false, true );
        
        wp_enqueue_script('wpfront-field-attributes-js', WPFRONT_ASSET_PATH.'/js/field-attributes.js', array( 'wpfront-vue' ), false, true );
        wp_localize_script( 'wpfront-field-attributes-js', 'wpfront_obj', array(
        	'post_types' => get_post_types(array(
        		'public' => true
	        )),
	        'post_statuses' => get_post_statuses(),
	        'post_comment_statuses' => array( 'open' => __( 'Open', 'wpfront' ), 'close' => __( 'Close', 'wpfront' ) )
        ));

        wp_enqueue_script('wpfront-form-settings-js', WPFRONT_ASSET_PATH.'/js/form-settings.js', array( 'wpfront-vue' ), false, true );

	    $formats = get_theme_support( 'post-formats' );
	    $all_formats = array();
	    foreach ( $formats as $k => $format_data ) {
		    $all_formats = array_merge( $all_formats, $format_data );
	    }
        wp_localize_script( 'wpfront-form-settings-js', 'wpfront_obj', array(
		    'post_types' => get_post_types(array(
			    'public' => true
		    )),
		    'post_statuses' => get_post_statuses(),
		    'post_comment_statuses' => array( 'open' => __( 'Open', 'wpfront' ), 'close' => __( 'Close', 'wpfront' ) ),
	        'post_formats' => $all_formats,
	        'default_tax_category' => array_column(get_terms( array(
		        'taxonomy' => 'category',
		        'hide_empty' => false,
	        ) ), 'name', 'term_id' )
	    ));



        wp_enqueue_script('wpfront-store-js', WPFRONT_ASSET_PATH.'/js/store.js', array( 'wpfront-vue' ), false, true );

        wp_enqueue_script('wpfront-form-js', WPFRONT_ASSET_PATH.'/js/templates/form.js', array( 'wpfront-vue' ), false, true );

        do_action('wpfront_load_scripts_styles' );

        wp_enqueue_script('wpfront-script-js', WPFRONT_ASSET_PATH.'/js/script.js', array( 'wpfront-vue', 'jquery' ), false, true );
    }

    public function form_type_data() {
    	//include_once ''
    }

    public function prepend_scripts_styles() {
        if( !Wpfront_Functions::is_pro() ) {
            wp_enqueue_script('wpfront-pro-data-js', WPFRONT_ASSET_PATH.'/js/pro-data.js'/*, array( 'wpfront-vue' ), false, true */);
        }
    }

    public function admin_footer() {
        include_once 'templates/core/form-builder.php';
    }
}

Wpfront_Admin::get_instance();