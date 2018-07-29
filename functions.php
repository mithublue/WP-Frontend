<?php

if( !function_exists('wpfront_pri' ) ) {
    function wpfront_pri( $data ) {
        echo '<pre>'; print_r($data);echo '</pre>';
    }
}

class Wpfront_Functions {

    /**
     * Check if the plugin is pro
     * @return bool
     */
    static function is_pro() {
        if( is_file( dirname(__FILE__).'/pro/loader.php' ) ) {
            return true;
        }
        return false;
    }

    /**
     * get form capability
     * @return mixed|void
     */
    public static function form_capability() {
        return apply_filters( 'wpfront_form_capability', 'manage_options');
    }

    /**
     * Get form types
     */
    public static function get_form_types() {
    	$post_types = get_post_types( array(
    		'public' => true
	    ), 'ARRAY_A');

	    $form_types = array();

	    foreach ( $post_types as $post_type => $each ) {
    		$form_types[$post_type] = array(
    			'type' => 'post_type',
    			'label' => isset( $each->labels->singular_name ) ? $each->labels->singular_name : $each->label,
			    'desc' => __( 'Choose this if you want to create '. ( isset( $each->labels->singular_name ) ? $each->labels->singular_name : $each->label ).' form' )
		    );
	    }
        return apply_filters( 'wpfront_form_types', $form_types);
    }

    public static function get_form_post_status( $id ) {
        return get_post_status($id);
    }

    /**
     * Get formdata for
     * the given id
     * @param $id
     * @return mixed
     */
    public static function get_formdata( $id, $formatted = false ) {
        if( $formatted ) {
            return json_decode(base64_decode(get_post_meta( $id, 'wpfront_formdata', true )), true);
        }
        return $formdata = get_post_meta( $id, 'wpfront_formdata', true );
    }

    /**
     * Get form settings
     * for given id
     * @param $id
     * @return mixed
     */
    public static function get_form_settings( $id, $formatted = false ) {
        if( $formatted ) {
            return json_decode(base64_decode(get_post_meta( $id, 'wpfront_form_settings', true ) ), true);
        }
        return $form_settings = get_post_meta( $id, 'wpfront_form_settings', true );
    }

    /**
     * Get global settings
     * @param null $option_name
     * @return array|mixed|object|string|void
     */
    public static function get_settings($option_name = null) {
        global $wpfront_global_settings;
        if( !$wpfront_global_settings ) {
            $wpfront_global_settings = get_option( 'wpfront_global_settings' );
            $wpfront_global_settings = json_decode(base64_decode($wpfront_global_settings),true);
        }

        if( $option_name ) {
            return isset($wpfront_global_settings[$option_name]) ? $wpfront_global_settings[$option_name] : '';
        }

        return $wpfront_global_settings;
    }

    /**
     * Validate recaptcha
     */
    public static function recaptcha_validate($token) {
        $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify?secret='.Wpfront_Functions::get_settings('secrect_key').'&response='.$token );

        if( isset( $response['body'] ) ) {
            $body = json_decode($response['body'], true );
            if( $body['success'] ) {
                return true;
            }
        }
        return false;
    }

    public static function get_submission_occurance ( $submission_id ) {
	    $wpfront_submission_times = get_post_meta( $submission_id, 'wpfront_submission_times', true );
	    !$wpfront_submission_times ? $wpfront_submission_times = 0 : '';
	    return $wpfront_submission_times;
    }
    /**
     * Update submission times
     */
    public static function update_submission_occurance( $submission_id, $occurance = 1 ) {
	    $wpfront_submission_times = get_post_meta( $submission_id, 'wpfront_submission_times', true );
	    if( !$wpfront_submission_times ) $wpfront_submission_times = 0;
	    update_post_meta( $submission_id, 'wpfront_submission_times',$wpfront_submission_times + $occurance );
    }

    public static function get_post_form( $post_id ) {
    	return get_post_meta( $post_id, '_wpfrontend_submission_id', true );
    }

    public static function get_fallback_form() {
    	return Wpfront_Functions::get_settings('default_post_form' );
    }

    public static function get_installed_pages() {
    	$pages = get_option( 'wpfrontend_pages' );
    	!is_array( $pages ) ? $pages = array() : '';
    	return $pages;
    }

    public static function install_plugin_pages() {
    	$pages = array();

	    $edit_page_id = wp_insert_post(array(
		    'post_type' => 'page',
		    'post_status' => 'publish',
		    'post_title' => 'WP Frontend Edit',
		    'post_content' => '[wpfrontend_edit]'
	    ));
	    $dashboard_page_id = wp_insert_post(array(
		    'post_type' => 'page',
		    'post_status' => 'publish',
		    'post_title' => 'WP Frontend Dashboard',
		    'post_content' => '[wpfrontend_dashboard]'
	    ));
	    $login_page_id = wp_insert_post(array(
		    'post_type' => 'page',
		    'post_status' => 'publish',
		    'post_title' => 'WP Frontend Login',
		    'post_content' => '[wpfrontend_login]'
	    ));
	    $reg_page_id = wp_insert_post(array(
		    'post_type' => 'page',
		    'post_status' => 'publish',
		    'post_title' => 'WP Frontend Registration',
		    'post_content' => '[wpfrontend_registration]'
	    ));

	    $pages = array(
	    	'edit_page' => $edit_page_id,
		    'dashboard_page' => $dashboard_page_id,
		    'login_page' => $login_page_id,
		    'reg_page' => $reg_page_id
	    );

	    update_option( 'wpfrontend_pages', $pages );
    }

    public static function get_posts( $user_id, $post_types = array(), $post_statuses = array(), $paged = 0 ) {
    	$args = array(
    		'post_type' => $post_types,
		    'post_status' => $post_statuses,
		    'author' => $user_id,
		    'paged' => $paged
	    );
	    $the_query = new WP_Query( $args );

	    return $the_query;
    }

	/**
	 * Checkc users permission
	 * @param $action
	 * @param $scope
	 */
    public static function can_user( $action, $scope ) {
	    //if( current_user_can( 'administrator' ) ) return true;

    	switch ($scope) {
		    case 'post' :
			    switch ( $action ) {
				    case 'delete':
					    if( Wpfront_Functions::get_settings('user_can_delete_post') ) return true;
					    break;
			    }
			    break;

	    }
	    return false;
    }
}