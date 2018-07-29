<?php

trait Wpfront_Login {
	protected $aria_describedby_error,$interim_login,$user_login,$rememberme,$redirect_to,$customize_login,$login_link_separator;

	public function login_init() {
		add_filter( 'login_url', array( $this, 'custom_login_url' ), 10, 2 );

		add_action( 'wp_login_failed', array( $this, 'custom_login_failed') );
		add_filter( 'authenticate', array( $this, 'custom_blank_username_password' ), 1, 3);

		add_filter( 'login_redirect', array( $this, 'custom_login_redirect' ), 10, 3 );
		add_filter( 'logout_redirect', array( $this, 'custom_login_redirect' ), 10, 3 );
		add_shortcode( 'wpfrontend_login', array( $this, 'login_definition' ) );

		add_filter( 'login_form_bottom', array( $this, 'login_bottom_text' ) );
		//add_action( 'wp_footer', array( $this, 'login_wp_footer' ) );
		add_action( 'init', array( $this, 'login_process_actions') );
	}

	/**
	 * redirection after successful login
	 * @param $redirect_to
	 * @param $request
	 * @param $user
	 *
	 * @return string|void
	 */
	function custom_login_redirect( $redirect_to, $request, $user ) {
			//is there a user to check?
		if( Wpfront_Functions::get_settings('login_redirect_page' ) ) {
			$redirect_to = get_permalink( Wpfront_Functions::get_settings('login_redirect_page' ) );
		} else {
			$redirect_to = admin_url();
		}

		return $redirect_to;
	}

	function custom_blank_username_password( $user, $username, $password ) {
		if( Wpfront_Functions::get_settings('login_page' ) ) {
			$login_url = get_permalink( Wpfront_Functions::get_settings('login_page' ) );
		};

		/*if( $username == "" || $password == "" ) {
			wp_redirect( add_query_arg( array( 'login' => 'blank' ), $login_url ) );
			exit;
		}*/
	}

	function custom_login_failed($username) {
		if( Wpfront_Functions::get_settings('login_page' ) ) {
			$login_url = get_permalink( Wpfront_Functions::get_settings('login_page' ) );
		};
		wp_redirect( add_query_arg( array( 'login' => 'failed' ), $login_url ) );
		exit;
	}

	public function custom_login_url( $login_url, $redirect ) {
		if( Wpfront_Functions::get_settings('login_page' ) ) {
			$login_url = get_permalink( Wpfront_Functions::get_settings('login_page' ) );
		}
		return $login_url;
	}

	public function login_process_actions() {

	}

	public function login_definition () {

		if( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) {
			_e( 'Invalid credentials', 'wpfront' );
		}

		$args = array(
			'echo'           => true,
			'remember'       => true,
			'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
			'form_id'        => 'loginform',
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'label_username' => __( 'Username or Email Address' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in'   => __( 'Log In' ),
			'value_username' => '',
			'value_remember' => true
		);
		wp_login_form();
	}

	public function login_bottom_text() {
		$login_link_separator = apply_filters( 'login_link_separator', ' | ' );
		?>
		<p id="nav">
			<?php if ( ! isset( $_GET['checkemail'] ) || ! in_array( $_GET['checkemail'], array( 'confirm', 'newpass' ) ) ) :
				if ( get_option( 'users_can_register' ) ) :
					$registration_url = sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) );

					/** This filter is documented in wp-includes/general-template.php */
					echo apply_filters( 'register', $registration_url );

					echo esc_html( $login_link_separator );
				endif;
				?>
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?' ); ?></a>
			<?php endif; ?>
		</p>
	<?php
	}
}



