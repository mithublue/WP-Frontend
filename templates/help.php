<template id="wpfront_help">
	<div>
		<el-row :gutter="20">
			<el-col :sm="8" class="mb20">
				<el-card :body-style="{ padding: '0px' }">
					<div style="" class="text_center pt30 pb30 pr30 pl20">
						<div class="text_center font_36">
							<i class="el-icon el-icon-document"></i>
						</div>
						<h5 class="mb20 mt20"><?php _e( 'Need Good Understanding ?', 'wpfront' ); ?></h5>
						<div class="bottom clearfix">
							<div class="mb20">
								<?php _e( 'Our official detailed documentation will help you get a good ground on the thing you are looking for', 'wpfront'); ?>
							</div>
							<a href="http://docs.cybercraftit.com/docs/wpfront-user-documentation/" target="_blank" class="el-button"><?php _e( 'Go to Documentation', 'wpfront' ); ?></a>
						</div>
					</div>
				</el-card>
			</el-col>
			<el-col :sm="8" class="mb20">
				<el-card :body-style="{ padding: '0px' }">
					<div style="" class="text_center pt30 pb30 pr30 pl20">
						<div class="text_center font_36">
							<i class="el-icon el-icon-info"></i>
						</div>
						<h5 class="mb20 mt20"><?php _e( 'Need Assistance ?', 'wpfront' ); ?></h5>
						<div class="bottom clearfix">
							<div class="mb20">
								<?php _e( 'Our official support is raedy to help you with any query or something.', 'wpfront'); ?>
							</div>
							<a href="http://supports.cybercraftit.com/" target="_blank" class="el-button"><?php _e( 'Go to Support', 'wpfront' ); ?></a>
						</div>
					</div>
				</el-card>
			</el-col>
			<el-col :sm="8" class="mb20">
				<el-card :body-style="{ padding: '0px' }">
					<div style="" class="text_center pt30 pb30 pr30 pl20">
						<div class="text_center font_36">
							<i class="el-icon el-icon-warning"></i>
						</div>
						<h5 class="mb20 mt20"><?php _e( 'Found any Issue ?', 'wpfront' ); ?></h5>
						<div class="bottom clearfix">
							<div class="mb20">
								<?php _e( 'Report us if any bug or issue is found to help us make this product better.', 'wpfront'); ?>
							</div>
							<a href="https://github.com/mithublue/wpfront/issues" target="_blank" class="el-button"><?php _e( 'Report to Github', 'wpfront' ); ?></a>
						</div>
					</div>
				</el-card>
			</el-col>
		</el-row>
		<el-row :gutter="20">
			<el-col :sm="8" class="mb20">
				<el-card :body-style="{ padding: '0px' }">
					<div style="" class="text_center pt30 pb30 pr30 pl20">
						<div class="text_center font_36">
							<i class="el-icon el-icon-setting"></i>
						</div>
						<h5 class="mb20 mt20"><?php _e( 'Need customization ?', 'wpfront' ); ?></h5>
						<div class="bottom clearfix">
							<div class="mb20">
								<?php _e( 'We welcome new ideas and customizations and integration are welcome. You are welcome to contact with us for these', 'wpfront'); ?>
							</div>
							<a href="https://cybercraftit.com/contact/" target="_blank" class="el-button"><?php _e( 'Contact Us', 'wpfront' ); ?></a>
						</div>
					</div>
				</el-card>
			</el-col>
			<el-col :sm="8" class="mb20">
				<el-card :body-style="{ padding: '0px' }">
					<div style="" class="text_center pt30 pb30 pr30 pl20">
						<div class="text_center font_36">
							<i class="el-icon el-icon-success"></i>
						</div>
						<h5 class="mb20 mt20"><?php _e( 'Like Our Plugin ?', 'wpfront' ); ?></h5>
						<div class="bottom clearfix">
							<div class="mb20">
								<?php _e( 'Your valuable feedback and review encourage us to make more awesomeness. :) ', 'wpfront'); ?>
							</div>
							<a href="https://wordpress.org/support/plugin/wpfront/reviews/?rate=5#new-post" target="_blank" class="el-button"><?php _e( 'Rate WP Frontend', 'wpfront' ); ?></a>
						</div>
					</div>
				</el-card>
			</el-col>
		</el-row>
	</div>
</template>
<script>
	var wpfrontend_help = {
	    template: '#wpfront_help'
	}
</script>