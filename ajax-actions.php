<?php
class Wpfront_Ajax_actions {

    public static function init() {
        add_action( 'wp_ajax_wpfront_update_form', array( __CLASS__, 'wpfront_update_form' ) );
        add_action( 'wp_ajax_wpfront_update_entry', array( __CLASS__, 'update_entry' ) );
        add_action( 'wp_ajax_wpfront_get_forms', array( __CLASS__, 'wpfront_get_forms' ) );
        add_action( 'wp_ajax_wpfront_get_form', array( __CLASS__, 'wpfront_get_form' ) );
        add_action( 'wp_ajax_wpfront_get_entry', array( __CLASS__, 'get_entry' ) );
        add_action( 'wp_ajax_wpfront_delete_form', array( __CLASS__, 'wpfront_delete_form' ) );
        add_action( 'wp_ajax_wpfront_delete_entry', array( __CLASS__, 'wpfront_delete_entry' ) );
        add_action( 'wp_ajax_wpfront_buck_delete', array( __CLASS__, 'buck_delete' ) );
        add_action( 'wp_ajax_wpfront_save_global_settings', array( __CLASS__, 'save_global_settings' ) );
        add_action( 'wp_ajax_wpfront_get_global_settings', array( __CLASS__, 'get_global_settings' ) );
        add_action( 'wp_ajax_wpfront_recaptcha_validate', array( __CLASS__, 'recaptcha_validate' ) );
        add_action( 'wp_ajax_wpfront_submit_form', array( __CLASS__, 'submit_form' ) );
        add_action( 'wp_ajax_nopriv_wpfront_submit_form', array( __CLASS__, 'submit_form' ) );
        add_action( 'wp_ajax_wpfront_get_entries', array( __CLASS__, 'get_entries' ) );
        //
	    add_action( 'wp_ajax_wpfront_populate_form_type_data', array( __CLASS__, 'populate_form_type_data' ) );
	    add_action( 'wp_ajax_wpfront_get_tax_terms', array( __CLASS__, 'get_tax_terms' ) );
    }

    public static function wpfront_update_form() {
        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        !isset( $_POST['status'] ) || !$_POST['status'] ? $_POST['status'] = 'draft' : '';
        !isset( $_POST['title'] ) || !$_POST['title'] ? $_POST['title'] = 'Form' : '';

        $title = sanitize_text_field( $_POST['title'] );
        $status = sanitize_text_field( $_POST['status'] );
        $formdata = wp_filter_post_kses($_POST['formdata']);
        $form_settings = wp_filter_post_kses($_POST['form_settings']);
	    $form_settings_decoded = json_decode( base64_decode($form_settings), true );

        if( isset( $formdata ) && $formdata ) {
            $arg = array(
                'post_title' => $title,
                'post_type' => 'wpfront_form',
                'post_status' => $status,
                'meta_input' => array(
                    'wpfront_formdata' => $formdata,
                    'wpfront_form_settings' => $form_settings,
	                '_wpfront_form_post_type' => isset( $form_settings_decoded['form_settings']['s']['post_type'] ) ? $form_settings_decoded['form_settings']['s']['post_type'] : ''
                )
            );

            //edit form
            if ( is_numeric( $_POST['form_id'] ) && $_POST['form_id'] ) {
                $arg['ID'] = $_POST['form_id'];
            }

            $result = wp_insert_post($arg);

            if( $result ) {
                wp_send_json_success(array(
                    'id' => $result
                ));
            }

            wp_send_json_error();
        }
        exit;
    }

	/**
	 * Entry update
	 */
    public static function update_entry() {
        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        !isset( $_POST['status'] ) || !$_POST['status'] ? $_POST['status'] = 'draft' : '';
        $status = sanitize_text_field( $_POST['status'] );

	    $arg = array(
		    'post_type' => 'wpfront_entry',
		    'post_status' => $status,
	    );

	    //edit form
	    if ( is_numeric( $_POST['entry_id'] ) && $_POST['entry_id'] ) {
		    $arg['ID'] = $_POST['entry_id'];

		    $result = wp_update_post($arg);
	    } else {
		    $result = wp_insert_post($arg);
        }

	    if( $result ) {
		    wp_send_json_success(array(
			    'id' => $result
		    ));
	    }

	    wp_send_json_error();
        exit;
    }

    /**
     * Get forms
     */
    public static function wpfront_get_forms() {

        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        if( !isset( $_POST['page'] ) || !$_POST['page'] || !is_numeric( $_POST['page'] ) ) {
            $_POST['page'] = 1;
        }


        if ( !isset( $_POST['status'] ) || !$_POST['status'] ) {
            $_POST['status'] = 'publish';
        }

        $page = $_POST['page'];
        $status = sanitize_text_field($_POST['status']);

        $forms = get_posts(array(
            'post_type' => 'wpfront_form',
            'post_status' => $status,
            'posts_per_page' => 10,
            'offset' => ($page - 1)*10,
            'order' => 'DESC',
            'orderby' => 'ID'
        ));

        wp_send_json_success(array(
            'forms' => $forms,
            'counts' => wp_count_posts()->publish
        ));
    }

    /**
     * Get entries
     */
    public static function get_entries() {
        if( !isset( $_POST['form_type'] ) || !$_POST['form_type'] ) wp_send_json_error(array(
            'msg' => 'Form type is not set !'
        ));

        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        if( !isset( $_POST['page'] ) || !$_POST['page'] || !is_numeric( $_POST['page'] ) ) {
            $_POST['page'] = 1;
        }

        if ( !isset( $_POST['status'] ) || !$_POST['status'] ) {
            $_POST['status'] = 'publish';
        }



        $page = $_POST['page'];
        $status = sanitize_text_field($_POST['status']);
        $form_type = sanitize_text_field( $_POST['form_type'] );

        $entries = get_posts(array(
            'post_type' => 'wpfront_entry',
            'post_status' => $status,
            'posts_per_page' => 10,
            'offset' => ($page - 1)*10,
            'order' => 'DESC',
            'orderby' => 'ID',
            'meta_key' => 'wpfront_form_type',
            'meta_value' => $form_type
        ));

        wp_send_json_success(array(
            'entries' => $entries,
            'counts' => wp_count_posts()->publish
        ));
    }

    /**
     * Get single form
     */
    public static function wpfront_get_form() {

        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        if( !isset($_POST['id']) || !$_POST['id'] || !is_numeric($_POST['id']) ) wp_send_json_error();

        $id = $_POST['id'];
        $form = get_post($id);

        if ( $form ) {
            wp_send_json_success(array(
                'form' => $form,
                'formdata' => Wpfront_Functions::get_formdata($id),
                'form_settings' => Wpfront_Functions::get_form_settings($id)
            ));
        }
        wp_send_json_error();
    }

    /**
     * Get entry
     */
    public static function get_entry() {

        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        if( !isset($_POST['id']) || !$_POST['id'] || !is_numeric($_POST['id']) ) wp_send_json_error();

        $id = $_POST['id'];
        $entry = get_post($id);
        $entry_metas = get_post_meta($id);
        $data_fields = array();

        /**
         * Get form id
         * used to create this entry
         */
        $wpfront_form_id = get_post_meta( $id, 'wpfront_form_id', true );
        if( !$wpfront_form_id ) wp_send_json_error(array(
            'msg' => 'No entry found !'
        ));


        $formdata = json_decode( base64_decode( Wpfront_Functions::get_formdata($wpfront_form_id) ), true );
        $form_settings = json_decode( base64_decode( Wpfront_Functions::get_form_settings($wpfront_form_id) ), true ) ;


	    if ( !isset( $form_settings['form_settings']['s']['is_multistep'] ) || !$form_settings['form_settings']['s']['is_multistep'] ) {
            foreach ( $formdata['field_data'] as $row => $row_data ) {
                foreach ( $row_data['row_formdata'] as $col => $col_data ) {
	                /**
	                 * If field is present
                     * in preset fields
	                 */
                    if( isset( $col_data['s']['name']) ) {
		                $data_fields[ $col_data['s']['name'] ] = array(
			                'label' => isset( $col_data['s']['label'] ) ? $col_data['s']['label'] : '',
			                'value' => isset( $entry_metas[$col_data['s']['name']][0] ) ? $entry_metas[$col_data['s']['name']][0] : ''
		                );
	                }
                }
            }
        } else {
	        $data_fields = apply_filters( 'wpfront_multistep_entry_meta_data', $data_fields, $formdata, $entry_metas );
        }
	    $data_fields = apply_filters( 'wpfront_multistep_entry_data', $data_fields, $formdata, $entry_metas );


        if ( $entry ) {
            wp_send_json_success(array(
                'entry' => array(
                    'main' => $entry,
                    'data_fields' => $data_fields,
                    'form_settings' => $form_settings
                )
            ));
        }
        wp_send_json_error(array(
            'msg' => __( 'Something went wrong !', 'wpfront' )
        ));
    }

    /**
     * Delete form
     */
    public static function wpfront_delete_form() {
        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        if( !isset($_POST['form_id']) || !$_POST['form_id'] || !is_numeric($_POST['form_id']) ) wp_send_json_error();
        $form_id = $_POST['form_id'];
        $result = '';

        if( isset( $_POST['trashDelete'] ) ) {
            if( $_POST['trashDelete'] ) {
                $result = wp_trash_post( $form_id );
            } else {
                $result = wp_delete_post( $form_id, true );
            }
        } else {
            $result = wp_delete_post( $form_id, true );
        }


        if( $result ) {
            wp_send_json_success(array(
                'msg' => 'Form Deleted !'
            ));
        }

        wp_send_json_error(array(
            'msg' => 'Form could not be deleted !'
        ));
    }

    /**
     * Delete entry
     */
    public static function wpfront_delete_entry() {
        if( !current_user_can(Wpfront_Functions::form_capability()) ) wp_send_json_error(array(
            'msg' => 'You are not allowed to apply this operation !'
        ));

        if( !isset($_POST['id']) || !$_POST['id'] || !is_numeric($_POST['id']) ) wp_send_json_error();
        $id = $_POST['id'];
        $result = '';

        if( isset( $_POST['trashDelete'] ) ) {
            if( $_POST['trashDelete'] ) {
                $result = wp_trash_post( $id );
            } else {
                $result = wp_delete_post( $id, true );
            }
        } else {
            $result = wp_delete_post( $id, true );
        }


        if( $result ) {
            wp_send_json_success(array(
                'msg' => 'Entry Deleted !'
            ));
        }

        wp_send_json_error(array(
            'msg' => 'Entry could not be deleted !'
        ));
    }

    public function buck_delete() {
    	if( !isset( $_POST['ids'] ) ) wp_send_json_error();
    	global $wpdb;
    	$ids = json_decode( $_POST['ids'], true );
    	if( !is_array( $ids ) ) wp_send_json_error();
    	$ids = implode(',',$ids);

    	if( $ids ) {

    		if( !$_POST['soft'] ) {
			    $query = "DELETE FROM $wpdb->posts WHERE ID IN ($ids);";
		    } else {
    			$query = "UPDATE $wpdb->posts
SET post_status = 'trash'
WHERE ID IN ($ids);";
		    }

		    if( $wpdb->query($query) ) {
			    wp_send_json_success();
		    }
	    }
	    wp_send_json_error();
    }

    /**
     * Save global settings
     */
    public static function save_global_settings () {
        $global_settings = wp_filter_post_kses($_POST['global_settings']);
        if( update_option( 'wpfront_global_settings', $global_settings ) )
            wp_send_json_success(
                array(
                    'msg' => __( 'Data saved successfully', 'wpfront' )
                )
            );
        wp_send_json_error(
            array(
                'msg' => __( 'Data could not be saved !', 'wpfront' )
            )
        );
    }

    /**
     * Get global settings
     */
    public static function get_global_settings () {
        $global_settings =  get_option( 'wpfront_global_settings' );
        if( $global_settings )
            wp_send_json_success(array(
                'global_settings' => $global_settings
            ));
        wp_send_json_error();
    }

    /**
     * Submitting the form
     */
    public static function submit_form() {

        if( !isset( $_POST['formData'] ) ) wp_send_json_error();
            parse_str( $_POST['formData'], $formData );

	    /**
	     * Adding save type as well
	     * to the $formData
	     */
	    $formData['save_type'] = $_POST['save_type'];

        if( !isset( $formData['wpfront_submission_id'] ) ||!is_numeric( $formData['wpfront_submission_id'] ) )
            wp_send_json_error(array(
                'msg' => 'This operation is not valid !'
            ));

        $submission = new Wpfront_Submission_Process($formData);

        if( $submission->get_errors() ) {
            wp_send_json_error(array(
                'msg' => 'Something went wrong !',
                'errors' => $submission->get_errors()
            ));
            exit;
        }

        $return = $submission->get_returned_data();

        if( isset( $return['post_id'] ) ) {

        }

        wp_send_json_success(array(
            'msg' => 'Success !'
        ));
        exit;
    }

	/**
	 * Populate form type data
	 * in form
	 */
    public static function populate_form_type_data() {
    	if( !isset( $_POST['type'] ) || !isset( $_POST['form_type'] ) ) wp_send_json_error();

    	$type = sanitize_text_field( $_POST['type'] );
    	$form_type = sanitize_text_field( $_POST['form_type'] );
	    $form_type_data_object = new Wpfront_Form_Type_Data( $type, $form_type );
	    $form_type_data = $form_type_data_object->get_form_type_data();
	    $form_settings = $form_type_data_object->get_form_settings();

	    if( $form_type_data ) {
	    	wp_send_json_success(array(
	    		'form_type_data' => $form_type_data,
			    'form_settings' => $form_settings
		    ));
	    }

	    wp_send_json_error();
    }

    /**
     * Recaptcha Validation
     */
    public function recaptcha_validate() {
        if( !isset( $_POST['token'] ) ) wp_send_json_error();
        $token = $_POST['token'];
	    Wpfront_Functions::recaptcha_validate( $token );
    }

	/**
	 * get all tax and terms of all post types
	 */
    public static function get_tax_terms() {
	    $post_types = get_post_types();

	    foreach ( $post_types as $post_type ) {
		    $taxonomy_names = get_object_taxonomies( $post_type );

		    $terms = get_terms( $taxonomy_names, array( 'hide_empty' => false ));
		    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
			    echo '<h5>'.$post_type.'</h5>';
			    echo '<ul>';
			    foreach ( $terms as $term ) {
				    echo '<li>' . $term->name . '</li>';

			    }
			    echo '</ul>';
		    }
	    }
    }
}

Wpfront_Ajax_actions::init();