<?php

class Wpfront_Submission_Process {

    /**
     * @var Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;
    protected $postdata;
    protected $form_settings;
    protected $formdata;
    protected $returned_data = array();
	/**
	 * Grab all fields
	 * @var
	 */
    protected $form_fields = array();
    protected $errors = array();

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

    public function __construct( $postdata ) {

        /**
         * Check if the form exists
         * and published
         */
        if( Wpfront_Functions::get_form_post_status( $postdata['wpfront_submission_id'] ) !== 'publish' ) {
            $this->set_error( 'Form is not submittable !' );
            return false;
        }

        $this->postdata = $postdata;
        $this->form_settings = Wpfront_Functions::get_form_settings($this->postdata['wpfront_submission_id'], true );
        $this->formdata = Wpfront_Functions::get_formdata($this->postdata['wpfront_submission_id'], true );

        if( empty( $this->get_errors() ) ) {
            if( $this->process_form() ) {
	            /**
	             * Save number of submission
	             * in form
	             */
	            Wpfront_Functions::update_submission_occurance( $this->postdata['wpfront_submission_id'] );
	            return true;
            }
        }

        if( empty( $this->get_errors() ) )
            $this->set_error( 'Something went wrong !' );
        return false;
    }


    /**
     * Process form
     * @return bool|mixed|void
     */
    public function process_form () {

        /**
         * Data validation to check if
         * all data is okay
         */
        if( !$this->validate_data( $this->postdata, $this->formdata ) ) {
            return false;
        };

        if( !$this->common_validation() ) {
            return false;
        }



        if( 1 /*method_exists( $this, 'process_'.$this->form_settings['form_settings']['s']['form_type'] )*/ ) {
	        //$process_method = 'process_'.$this->form_settings['form_settings']['s']['form_type'];
	        $process_method = 'process_post_type';
	        if( $this->{$process_method}() ) {
                return true;
            };

            return false;
        } else {
            return apply_filters( 'wpfront_process_form_'.$this->form_settings['form_settings']['s']['form_type'], false, $this->postdata, $this->form_settings, $this );
        }
    }

    /**
     * Form specific process
     * Send mail
     * @return bool
     */
    public function process_post_type() {
        $form_settings = $this->form_settings;

        $arg = array(
        	'post_title' => 'Post Title',
        	'post_content' => 'Post Content',
        	//post_author
	        //post_date
	        //post_excerpt
	        ///post_name
	        ///
        	'post_type' => $this->form_settings['form_settings']['s']['post_type'],
	        'post_status' => $this->form_settings['form_settings']['s']['post_status'],
	        'comment_status' => $this->form_settings['form_settings']['s']['comment_status']
        );

        $non_meta = array();

        if( isset( $this->postdata['save_type'] ) && $this->postdata['save_type'] == 'draft' ) {
        	$arg['post_status'] = $this->postdata['save_type'];
        }

	    /**
	     * From settings
	     */
	    //post_author
	    if( Wpfront_Functions::get_settings('default_post_author' ) ) {
        	$arg['post_author'] = Wpfront_Functions::get_settings('default_post_author' );
	    }
	    if( is_user_logged_in() ) {
		    $arg['post_author'] = get_current_user_id();
	    }


	    /**
	     * From form
	     */
	    //set post_type
	    if( isset( $this->postdata['post_type'] )
	        && !empty( $this->postdata['post_type'] )
	        && in_array( 'post_type', $this->form_fields )
	        && post_type_exists( $this->postdata['post_type'] )
	    ) {
		    $arg['post_type'] = $this->postdata['post_type'];
		    $non_meta[] =  'post_type';
	    }

        //post_title
	    if( isset( $this->postdata['post_title'] )
	        && in_array( 'post_title', $this->form_fields )
	    ) {
		    $arg['post_title'] = $this->postdata['post_title'];
		    $non_meta[] =  'post_title';
	    }
	    //post_content
	    if( isset( $this->postdata['post_content'] )
	        && in_array( 'post_content', $this->form_fields )
	    ) {
		    $arg['post_content'] = $this->postdata['post_content'];
		    $non_meta[] =  'post_content';
	    }

	    //post_author
	    if( isset( $this->postdata['post_author'] )
	        && !empty( $this->postdata['post_author'] )
	        && in_array( 'post_author', $this->form_fields )
	    ) {
		    $arg['post_author'] = $this->postdata['post_author'];
		    $non_meta[] =  'post_author';
	    }
	    //post_date
	    if( isset( $this->postdata['post_date'] )
	        && !empty( $this->postdata['post_date'] )
	        && in_array( 'post_date', $this->form_fields )
	    ) {
		    $arg['post_date'] = $this->postdata['post_date'];
		    $non_meta[] =  'post_date';
	    }
	    //post_excerpt
	    if( isset( $this->postdata['post_excerpt'] )
	        && !empty( $this->postdata['post_excerpt'] )
	        && in_array( 'post_excerpt', $this->form_fields )
	    ) {
		    $arg['post_excerpt'] = $this->postdata['post_excerpt'];
		    $non_meta[] =  'post_excerpt';
	    }

	    //set post_status
	    if( isset( $this->postdata['post_status'] )
	        && !empty( $this->postdata['post_status'] )
	        && in_array( 'post_status', $this->form_fields )
	    ) {
		    $arg['post_status'] = $this->postdata['post_status'];
		    $non_meta[] =  'post_status';
	    }

	    //set comment_status
	    if( isset( $this->postdata['comment_status'] )
	        && !empty( $this->postdata['comment_status'] )
	        && in_array( 'comment_status', $this->form_fields )
	    ) {
		    $arg['comment_status'] = $this->postdata['comment_status'];
		    $non_meta[] =  'comment_status';
	    }

	    //set meta
	    $arg['meta_input'] = array();
	    foreach ( $this->postdata as $name => $value ) {
	    	if( !in_array( $name, $non_meta ) ) {
	    		$arg['meta_input'][$name] = $value;
		    }
	    }
	    /**
	     * Excluside meta
	     */
	    $arg['meta_input']['_wpfrontend_submission_id'] = $this->postdata['wpfront_submission_id'];

	    /**
	     * have id if edit
	     * otherwise not
	     */
	    if( isset( $this->postdata['post_id'] ) && $this->postdata['post_id'] && is_numeric( $this->postdata['post_id'] ) ) {
		    $arg['ID'] = $this->postdata['post_id'];
		    $post_id = wp_update_post($arg);
	    } else {
	    	//create new
		    $post_id = wp_insert_post($arg);
	    }



	    if( $post_id ) {
		    //set post_format
		    if( isset( $this->form_settings['form_settings']['s']['post_format'] )
		        && !empty( $this->form_settings['form_settings']['s']['post_format'] )
		    ) {
			    set_post_format( $post_id, $this->form_settings['form_settings']['s']['post_format'] );
		    }
		    //set category
		    if( isset( $this->form_settings['form_settings']['s']['default_tax_category'] )
		        && !empty( $this->form_settings['form_settings']['s']['default_tax_category'] )
		    ) {
			    wp_set_post_categories( $post_id, $this->form_settings['form_settings']['s']['default_tax_category'] );
		    }

		    $this->set_returned_data( array(
			    'post_id' => $post_id,
			    'arg' => $arg
		    ) );
		    return true;
	    }

        return false;
    }

	/**
	 * Common validation
	 * E.g: security check
	 * @return bool
	 */
    public function common_validation() {
        /**
         * Recaptcha validation
         */
        if( isset( $this->postdata['g-recaptcha-response'] ) ) {
            $token = $this->postdata['g-recaptcha-response'];
            if( Wpfront_Functions::recaptcha_validate($token) ) {
                return true;
            } else {
                $this->set_error( 'Recaptcha is not valid' );
            }
            return false;
        }

        return true;
    }

    /**
     * Data validation after submission
     */
    public function validate_data() {
        $postdata = $this->postdata;
        $formdata = $this->formdata;

	    if( !$this->form_settings['form_settings']['s']['is_multistep'] ) {
		    foreach ( $formdata['field_data'] as $k => $data ) {
			    if( $data['type'] == 'row' ) {
				    $this->row_validation($data);
			    }
		    }
	    } else {
		    do_action( 'wpfront_form_validate_data', $postdata, $formdata, $this );
	    }


        if( empty( $this->get_errors() ) )
            return true;
        return false;
    }

	public function row_validation( $data ) {
		$postdata = $this->postdata;

		foreach ( $data['row_formdata'] as $k => $col_data ) {
			/**
			 * Validation : Required
			 */
			if( isset( $col_data['s']['required'] ) && $col_data['s']['required'] == true ) {
				/**
				 * if data type if
				 * file
				 */
				//neoforms_pri($_FILES);
				if( $col_data['preview']['name'] == 'upload' ) {
					if( $_FILES[$col_data['s']['name']]['error'][0] ) {
						$this->set_error( ( isset( $col_data['s']['label'] ) ? $col_data['s']['label'] : $col_data['s']['name'] ) .' is Required ' );
					}
				} else {
					if( !isset( $postdata[$col_data['s']['name']] ) || empty( $postdata[$col_data['s']['name']] ) ) {
						$this->set_error( ( isset( $col_data['s']['label'] ) ? $col_data['s']['label'] : $col_data['s']['name'] ) .' is Required ' );
					}
				}
			}

			$this->form_fields[] = $col_data['s']['name'];
		}
	}

    /**
     * Set Errors
     * @param $msg
     */
    public function set_error( $msg ) {
        $this->errors[] = $msg;
    }

    /**
     * Get Errors
     * @return array
     */
    public function get_errors() {
        return $this->errors;
    }

	/**
	 * @param $key
	 * @param $value
	 */
    public function set_returned_data( $key = '', $value = '' ) {
    	if ( is_array( $key ) ) {
		    $this->returned_data = array_merge( $this->returned_data, $key );
	    } else {
		    $this->returned_data[$key] = $value;
	    }
    }

    public function get_returned_data() {
    	return $this->returned_data;
    }

}
