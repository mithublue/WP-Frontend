<?php

class Wpfront_Shortcode_Handler {

    protected $formdata = array();
    protected $form_settings = array();
    public $current_post;

    protected $render_shortcode;

    use Wpfront_Edit, Wpfront_Form, Wpfront_Dashboard, Wpfront_Login, Wpfront_Registration;

    public function __construct() {

        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts_styles'));
        add_action( 'wp_head', array( $this, 'wp_head' ) );

        $this->edit_init();
        $this->form_init();
        $this->dashboard_init();
        $this->login_init();
        $this->registration_init();
    }

	/**
     * Populate data from post to form fields
	 * @param $post_id
	 * @param $formdata
	 */
    public function populate_post_formdata( $post_id, $formdata, $form_settings ) {
        $formdata = json_decode(base64_decode( $formdata ), true );

        if( !$form_settings['form_settings']['s']['is_multistep'] ) {
	        foreach ( $formdata['field_data'] as $v => $data ) {
		        if( $data['type'] == 'row' ) {
			        foreach ( $data['row_formdata'] as $k => $col_data ) {
				        if( isset( $this->current_post->{$col_data['s']['name']} ) ) {
					        $formdata['field_data'][$v]['row_formdata'][$k]['s']['value'] = $this->current_post->{$col_data['s']['name']};
				        };
			        }
		        }
	        }
        } else {
	        $formdata = apply_filters( 'populate_post_form_data_multistep', $formdata, $form_settings, $this );
        }

	    return $formdata = base64_encode( json_encode( $formdata ) );
    }

    /**
     * Enqueue scripts
     */
    public function wp_enqueue_scripts_styles() {
        global $post;
        if( !isset( $post->post_content) ) return;

        if( !has_shortcode( $post->post_content, 'wpfrontend_form' )
            && !has_shortcode( $post->post_content, 'wpfrontend_edit' )
            && !has_shortcode( $post->post_content, 'wpfrontend_dashboard' )
            && !$this->render_shortcode
        ) return;

        wp_enqueue_style('wpfront-framework-css', WPFRONT_ASSET_PATH.'/css/framework.css' );
        wp_enqueue_style('wpfront-style-css', WPFRONT_ASSET_PATH.'/css/style.css' );
        wp_enqueue_style('wpfront-element-css', WPFRONT_ASSET_PATH.'/css/element.css' );

        wp_enqueue_script('wpfront-vue', WPFRONT_ASSET_PATH.'/js/vue.js', array(), false, true );

        wp_enqueue_script('wpfront-element-js', WPFRONT_ASSET_PATH.'/js/element.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-element-en-js', WPFRONT_ASSET_PATH.'/js/element-en.js', array( 'wpfront-vue' ), false, true );

        /*form data*/
        wp_enqueue_script('wpfront-functions-js', WPFRONT_ASSET_PATH.'/js/functions.js', array( 'wpfront-vue' ), false, true );
        wp_enqueue_script('wpfront-comp-public-form-js', WPFRONT_ASSET_PATH.'/js/templates/form-public.js', array( 'wpfront-vue' ), false, true );
        wp_localize_script( 'wpfront-functions-js', 'wpfront_object', array(
                'ajaxurl' => admin_url('admin-ajax.php')
        ));

        do_action('wpfront_public_load_scripts_styles' );
    }


    public function wp_head() {
        global $post;
        if( !has_shortcode( $post->post_content, 'wpfrontend_form' )
            && !has_shortcode( $post->post_content, 'wpfrontend_edit' )
            && !has_shortcode( $post->post_content, 'wpfrontend_dashboard' )
            && !$this->render_shortcode
        ) return;
            ?>
            <script src='https://www.google.com/recaptcha/api.js'></script>
            <?php

    }


}

new Wpfront_Shortcode_Handler();

//test
add_action( 'init', function () { return;
    //wpfront_pri(get_post_meta(183));die();
if( isset( $_POST['wpfront_submit'] ) ) {
        $submission = new Wpfront_Submission_Process($_POST);
        if( $submission->get_errors() )
            wpfront_pri($submission->get_errors());

        die();
    }
});
