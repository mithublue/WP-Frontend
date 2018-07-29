<?php
class Wpfrontend_Application {

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
		add_action( 'init', array( $this, 'actions_in_init' ) );
	}

	public function actions_in_init() {
		if( current_user_can('administrator' ) ) return;

		if( is_user_logged_in() ) {
			//admin_bar_roles
			if( empty( array_intersect( wp_get_current_user()->roles, is_array(Wpfront_Functions::get_settings('admin_bar_roles')) ? Wpfront_Functions::get_settings('admin_bar_roles') : array()  ) ) ) {
				add_filter('show_admin_bar', '__return_false');
			}

			//allow_admin_access_roles;
			if( is_admin() && empty( array_intersect( wp_get_current_user()->roles, is_array(Wpfront_Functions::get_settings('allow_admin_access_roles')) ? Wpfront_Functions::get_settings('allow_admin_access_roles') : array()  ) ) ) {
				if ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
					wp_redirect( home_url() );
					exit;
				}
			}
		}


	}
}

Wpfrontend_Application::get_instance();