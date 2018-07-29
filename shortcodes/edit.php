<?php

trait Wpfront_Edit {

	public function edit_init() {
		add_shortcode( 'wpfrontend_edit', array( $this, 'edit_definition' ));
	}

	/**
	 * @param $atts
	 * @param $content
	 * @param $tags
	 */
	public function edit_definition( $atts, $content, $tags ) {
		$atts = shortcode_atts( array(
			'id' => ''
		), $atts );

		if( isset( $_GET['pid'] ) && is_numeric( $_GET['pid'] ) ) {
			$pid = $_GET['pid'];
		} elseif ( isset( $atts['id'] ) && $atts['id'] ) {
			$pid = $atts['id'];
		} else {
			return;
		}


		$post_form = Wpfront_Functions::get_post_form( $pid );

		if( !$post_form ) {
			$post_form = Wpfront_Functions::get_fallback_form();
		}


		do_shortcode('[wpfrontend_form id="'.$post_form.'"]');
		$this->render_shortcode = true;
	}
}

///new Wpfront_Edit();