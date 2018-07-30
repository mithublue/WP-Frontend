<?php

trait Wpfront_Registration {

	public function registration_init() {
		add_filter( 'register_url', array( $this, 'custom_register_url' ), 10, 3 );
		add_shortcode( 'wpfrontend_registration', array( $this, 'registration_definition' ) );

		add_action( 'register_form', array( $this, 'custom_register_form' ) );
	}

	/**
	 * @param $reg_url
	 *
	 * @return false|string
	 */
	public function custom_register_url( $reg_url ) {
		if( Wpfront_Functions::get_settings('reg_page' ) ) {
			$reg_url = get_permalink( Wpfront_Functions::get_settings('reg_page' ) );
		}
		return $reg_url;
	}

	public function registration_definition() {
		require_once(ABSPATH . WPINC . '/registration.php');
		global $wpdb, $user_ID;
		//Check whether the user is already logged in
		if (!$user_ID) {

			if($_POST){

				$errors = array();

				//We shall SQL escape all inputs
				$username = $wpdb->prepare( '%s', $_REQUEST['username']);

				if(empty($username)) {
					$errors[] = "User name should not be empty.<br>";
				}

				$email = $wpdb->prepare( '%s', $_REQUEST['email']);

				if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
					$errors[] = "Please enter a valid email.<br>";
				}


				if( !empty( $errors ) ) {
					foreach ( $errors as $v => $error ) {
						echo $error.'<br>';
					}
				} else {
					$random_password = wp_generate_password( 12, false );
					$status = wp_create_user( $username, $random_password, $email );

					if ( is_wp_error($status) ) {
						foreach ( $status->errors as $k => $error_array ) {
							foreach ( $error_array as $v => $error ) {
								echo $error.'<br>';
							}
						}

					} else {
						$from = get_option('admin_email');
						$headers = 'From: '.$from . "\r\n";
						$subject = "Registration successful";
						$msg = "Registration successful.\nYour login details\nUsername: $username\nPassword: $random_password";
						wp_mail( $email, $subject, $msg, $headers );
						echo "Please check your email for login details.";
					}
				}
			}
			?>
			<!-- <script src="http://code.jquery.com/jquery-1.4.4.js"></script> -->
			<!-- Remove the comments if you are not using jQuery already in your theme -->
			<div id="container">
				<div id="content">
					<?php if(get_option('users_can_register')) {
						//Check whether user registration is enabled by the administrator ?>
						<div id="result"></div> <!-- To hold validation results -->
						<form action="" method="post">
							<label>Username</label>
							<input type="text" name="username" class="text" value="" /><br />
							<label>Email address</label>
							<input type="text" name="email" class="text" value="" /> <br/><br>
							<input type="submit" id="submitbtn" name="submit" value="SignUp" />
						</form>

						<script type="text/javascript">
                            //<![CDATA[

                            $("#submitbtn").click(function() {

                                $('#result').html('<img src="<?php bloginfo('template_url') ?>/images/loader.gif" class="loader" />').fadeIn();
                                var input_data = $('#wp_signup_form').serialize();
                                $.ajax({
                                    type: "POST",
                                    url:  "",
                                    data: input_data,
                                    success: function(msg){
                                        $('.loader').remove();
                                        $('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
                                    }
                                });
                                return false;

                            });
                            //]]>
						</script>

					<?php } else echo "Registration is currently disabled. Please try again later."; ?>
				</div>
			</div>
			<?php
		}
		else {
		    _e( 'You are already logged in.');
		}
	}

	function custom_register_form() {

		$first_name = ( ! empty( $_POST['first_name'] ) ) ? sanitize_text_field( $_POST['first_name'] ) : '';

		?>
		<p>
			<label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?><br />
				<input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(  $first_name  ); ?>" size="25" /></label>
		</p>
		<?php
	}

}