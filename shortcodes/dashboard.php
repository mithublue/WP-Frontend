<?php

trait Wpfront_Dashboard {

	public function dashboard_init() {
		add_shortcode( 'wpfrontend_dashboard', array( $this, 'dashboard_definition' ) );
		add_action( 'wp_footer', array( $this, 'dashboard_wp_footer' ) );

		add_action( 'init', array( $this, 'dashboard_process_actions') );
	}

	public function dashboard_process_actions() {

	    //if section posts
        if( $_GET['section'] == 'posts' ) {
	        /**
	         * Delete single item
	         */
            if( isset( $_GET['delete_id'] ) && is_numeric( $_GET['delete_id'] ) ) {
	            //check if user can delete post
	            if( Wpfront_Functions::can_user( 'delete', 'post' ) ) {
		            $current_post = get_post($_GET['delete_id']);
		            if( isset( $current_post->post_author ) && $current_post->post_author == get_current_user_id() ) {
			            //check if deletable post staus match the current post status
                        if( is_array( Wpfront_Functions::get_settings('deletable_post_status') ) && in_array( $current_post->post_status, Wpfront_Functions::get_settings('deletable_post_status') ) ) {

				                wp_update_post( array(
					            'ID' => $current_post->ID,
					            'post_status' => 'trash'
				            ));
			            }
		            }
	            }
	            wp_redirect( remove_query_arg( 'delete_id' ) );
	            exit;
            }

	        /**
	         * Delete multiple item(s)
	         */
	        if ( isset( $_POST['delete_ids'] ) && is_array( $_POST['delete_ids'] ) ) {

	            //check if user can delete post
                if( Wpfront_Functions::can_user( 'delete', 'post' ) ) {
                    foreach ( $_POST['delete_ids'] as $k => $id ) {
                        $current_post = get_post($id);
                        if( isset( $current_post->post_author ) && $current_post->post_author == get_current_user_id() ) {
	                        //check if deletable post staus match the current post status
                            if( is_array( Wpfront_Functions::get_settings('deletable_post_status') ) && in_array( get_post_status( $id ), Wpfront_Functions::get_settings('deletable_post_status') ) ) {
	                            wp_update_post( array(
	                                    'ID' => $id,
                                    'post_status' => 'trash'
                                ));
	                        }
                        }
                    }
                }
		        wp_redirect($_SERVER['REQUEST_URI']);
		        exit;
	        }

	        //die();
        }

    }

	public function dashboard_definition() {
		if( !is_user_logged_in() ) {
			echo 'You need to <a href="'.wp_login_url().'">login</a> to access this page';
			return;
		}
		?>
		<div id="wpfrontend-public-dashboard">
			<div class="oh">
				<div class="dashboard-nav fl p10">
					<ul>
						<li class="<?php echo Wpfront_Functions::dashboard_active_class('dashboard' ) ? 'active' : ''; ?>"><a href="<?php echo add_query_arg( array( 'section' => 'dashboard' ), parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) );?>"><?php _e( 'Dashboard', 'wpfront' ); ?></a></li>
						<li class="<?php echo Wpfront_Functions::dashboard_active_class('posts' ) ? 'active' : ''; ?>"><a href="<?php echo add_query_arg( array( 'section' => 'posts' ), parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) );?>"><?php _e( 'Posts', 'wpfront' ); ?></a></li>
					</ul>
				</div>
				<div class="dashboard-content fl p10">
                    <?php
                    if( isset( $_GET['section'] ) && method_exists( $this, 'section_'.$_GET['section'] ) ) {
                        $this->{'section_'.$_GET['section']}();
                    } else {
                        $this->section_dashboard();
                    }
                    ?>
				</div>
			</div>
		</div>
	<?php
	}

	/**
	 * Dashboard
	 */
	public function section_dashboard() {
	    ?>
        <h3><?php _e( 'Hello '.get_userdata(get_current_user_id())->display_name); ?></h3>
        <div class="oh">
            <div class="fl mr20">
                <img src="<?php echo get_avatar_url( get_current_user_id() ) ;?>" alt="">
            </div>
            <?php
            $userdata = get_userdata(get_current_user_id());
            ?>
            <div class="fl">
                <div>
		            <?php _e( 'Name','wpfront' ); ?> : <?php echo $userdata->user_nicename; ?>
                </div>
                <div>
		            <?php _e( 'Email','wpfront' ); ?> : <?php echo $userdata->user_email; ?>
                </div>
                <div>
		            <?php _e( 'Website URL','wpfront' ); ?> : <?php echo $userdata->user_url ? $userdata->user_url : 'N\A'; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function section_posts() {

	    global $paged;

	    if( isset( $_GET['pid'] ) && is_numeric( $_GET['pid'] ) ) {
	        echo do_shortcode( '[wpfrontend_edit]' );
		    return;
	    }


	    $rendering_post_types = array_keys(get_post_types( array(
		    'public' => true,
	    ), 'names') );

	    if( isset( $_GET['content_type'] ) && $_GET['content_type'] ) {
	        $rendering_post_types = array($_GET['content_type']);
        }

        $the_query = Wpfront_Functions::get_posts( get_current_user_id(), $rendering_post_types, array_keys( get_post_statuses() ), isset( $paged ) ? $paged : 0 );
	    ?>
        <div id="wpfrontend-section-posts" v-cloak>
	        <?php
            $post_types = get_post_types(array(
                    'public' => 'true'
            )); ?>
            <a href="<?php echo add_query_arg( array( 'content_type' => '' ), $_SERVER['REQUEST_URI'] ); ?>"><?php _e( 'All', 'wpfront' ); ?></a>
            <?php $i = 0;
            foreach ( $post_types as $k => $post_type ) {
	            echo $i < count($post_types) - 1 || $i > 0 ? '/' : ''; ?>
                <a href="<?php echo add_query_arg( array( 'content_type' => $post_type ), $_SERVER['REQUEST_URI'] ); ?>"><?php echo get_post_type_object( $post_type )->label; ?></a>
                <?php $i++;
            }

            if ( $the_query->have_posts() ) { ?>
                <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                    <div class="mb10">
                        <template>
                            <input type="submit" value="<?php _e( 'Delete Selected', 'wpfront' ); ?>" v-if="delete_ids.length">
                        </template>
                    </div>
                    <table>
                        <thead>
                        <tr>
                            <th></th>
                            <th><?php _e( 'Title', 'wpfront' ); ?></th>
                            <th><?php _e( 'Type', 'wpfront' ); ?></th>
                            <th><?php _e( 'Action', 'wpfront' ); ?></th>
                        </tr>
                        </thead>
                        <tbody>
		                <?php
		                while ( $the_query->have_posts() ) {
			                $the_query->the_post(); ?>
                            <tr>
                                <td>
                                    <?php if( Wpfront_Functions::get_settings('user_can_delete_post') ) {
	                                    if( is_array( Wpfront_Functions::get_settings('deletable_post_status') ) && in_array( get_post_status(), Wpfront_Functions::get_settings('deletable_post_status') ) ) { ?>
                                            <input type="checkbox" value="<?php echo get_the_ID(); ?>" name="delete_ids[]" v-model="delete_ids">
	                                    <?php }
                                    } ?>
                                </td>
                                <td><?php echo get_post_type(); ?></td>
                                <td><?php echo the_title(); ?></td>
                                <td>
					                <?php if( Wpfront_Functions::get_settings('user_can_edit_post') ) {
						                if( is_array( Wpfront_Functions::get_settings('editable_post_status') ) && in_array( get_post_status(), Wpfront_Functions::get_settings('editable_post_status') ) ) { ?>
                                            <a href="<?php echo add_query_arg( array('pid' => get_the_ID() ), $_SERVER['REQUEST_URI'] ); ?>"
                                               class="el-button el-button--default el-button--mini"
                                            ><?php _e( 'Edit', 'wpfront' ); ?></a>
						                <?php }
					                }
					                if( Wpfront_Functions::get_settings('user_can_delete_post') ) {
						                if( is_array( Wpfront_Functions::get_settings('deletable_post_status') ) && in_array( get_post_status(), Wpfront_Functions::get_settings('deletable_post_status') ) ) { ?>
                                            <a href="<?php echo add_query_arg( array('delete_id' => get_the_ID() ), $_SERVER['REQUEST_URI'] ); ?>"
                                                   class="el-button--mini el-button el-button--danger">
                                                <?php _e( 'Delete', 'wpfront' ); ?> </a>
						                <?php }
					                } ?>
                                </td>
                            </tr>
		                <?php } ?>
                        </tbody>
                    </table>
                </form>
                <div class="pagination">
			        <?php
			        echo paginate_links( array(
				        'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				        'total'        => $the_query->max_num_pages,
				        'current'      => max( 1, get_query_var( 'paged' ) ),
				        'format'       => '?paged=%#%',
				        'show_all'     => false,
				        'type'         => 'plain',
				        'end_size'     => 2,
				        'mid_size'     => 1,
				        'prev_next'    => true,
				        'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
				        'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
				        'add_args'     => false,
				        'add_fragment' => '',
			        ) );
			        ?>
                </div>
		        <?php
	        }
	        // Reset Post Data
	        wp_reset_postdata(); ?>
        </div>
        <?php
    }

	public function dashboard_wp_footer() {
		global $post;

		if( !has_shortcode( $post->post_content, 'wpfrontend_form' )
		    && !has_shortcode( $post->post_content, 'wpfrontend_edit' )
		    && !has_shortcode( $post->post_content, 'wpfrontend_dashboard' )
		    && !$this->render_shortcode
		) return;
		?>
        <script>
            ;document.addEventListener("DOMContentLoaded", function(event) {

                new Vue({
                    el: '#wpfrontend-section-posts',
                    data: {
                        delete_ids: []
                    }
                });
            });
        </script>
<?php
	}
}