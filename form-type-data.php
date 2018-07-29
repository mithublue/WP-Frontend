<?php
$post_types = get_post_types( array(
	'public' => true
), 'ARRAY_A');

$form_types = array();

$wpfront_form_type_data = array();
$wpfront_form_type_data = apply_filters( 'wpfront_form_type_data', $wpfront_form_type_data);

class Wpfront_Form_Type_Data {

	protected $type,$form_type, $form_type_data, $form_settings;

	public function __construct( $type, $form_type ) {

		$this->type = $type;
		$this->form_type = $form_type;
		$data = '';

		if( method_exists( $this, 'generate_'.$type.'_data' ) ) {
			$method = 'generate_'.$type.'_data';
			$data = $this->{$method}();
		}

		$this->set_form_settings();
	}

	public function set_form_settings() {
		$data = array();
		$data['form_settings'] = array(
			'for' => '',
			'label' => 'Post Settings',
			's' => array(
				'post_type'=> $this->form_type,
				'post_status'=> 'publish',
				'enable_draft_save'=> true,
				'post_format'=> '',
				'default_tax_category'=> '',
				'redirect_to'=> 'same',
				'submit_btn_text'=> 'Submit',
				'enable_draft_btn'=> true,
				'page_id'=> '',
				'url'=> '',
				'comment_status'=> 'open',
				'success_msg'=> 'Form submission successful',
				'failure_msg' => 'Form submission failed'
			)
		);

		$this->form_settings = $data;
	}


	public function generate_post_type_data() {
		$data = array(
			'label' => get_post_type_object($this->form_type)->labels->singular_name,
			'form_type' => $this->form_type,
			'field_data' => array(

				//
			)
		);

		if( post_type_supports( $this->form_type, 'title') ) {
			$data['field_data'][] = array(
				'type' => 'row',
				'preview' => array(
					'label' => 'Row'
				),
				'row_formdata' => array(
					array(
						'type' => 'input',
						'inputType' => 'text',
						'preview' => array(
							'label' =>  'Text',
							'name' => 'text'
						),
						's' => array(
							'required' => false,
							'name' => 'post_title',
							'label' => 'Title',
							'id' => '',
							'class' => '',
							'placeholder' => '',
							'maxlength' => '',
							'value' => '',
							'has_relation' => false,
							'relation' => array(
								array(
									'field' => '',
									'value' => '',
									'relation_type' => 'and'
								)
							)
						),
						'settings' => array(
							'atts' => array(
								'span' => 12
							)
						)
					)
				)
			);
		}

		//editor
		if( post_type_supports( $this->form_type, 'editor') ) {
			$data['field_data'][] = array(
				'type' => 'row',
				'preview' => array(
					'label' => 'Row'
				),
				'row_formdata' => array(
					array(
						'type' => 'input',
						'inputType' => 'textarea',
						'preview' => array(
							'label' =>  'Textarea',
							'name' => 'textarea'
						),
						'settings' => array(
							'atts' => array(
								'span' => 24
							)
						),
						's' => array(
							'required'=> false,
                            'name'=> 'post_content',
                            'label'=> 'Content',
                            'id'=> '',
                            'class'=> '',
                            'placeholder'=> '',
                            'maxlength'=> '',
                            'value'=> '',
                            'has_relation'=> false,
                            'relation'=> array(
                            	array(
		                            'field'=> '',
		                            'value' => '',
		                            'relation_type'=> 'and'
	                            )
                            ),
						),
					)
				)
			);
		}

		//more...

		$this->form_type_data = $data;
	}

	public function get_form_type_data() {
		return $this->form_type_data;
	}

	public function get_form_settings() {
		return $this->form_settings;
	}
}