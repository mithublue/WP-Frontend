<template id="promo_contact_form">
	<div>
		<?php
		if( !class_exists( 'NeoForms_Init' ) ) {
			?>
			<el-row>
				<el-col :sm="24" class="mb20">
					<el-card :body-style="{ padding: '0px' }">
						<div style="" class="text_center pt30 pb30 pr30 pl20">
							<div class="text_center font_36">
								<i class="el-icon el-icon-info"></i>
							</div>
							<h5 class="mb20 mt20"><?php _e( 'To use contact form , you need add the extension, neoForms.'); ?></h5>
							<div class="bottom clearfix">
								<div class="mb20">
									<p><?php _e( 'neoForms is the fastest, best and easiest form building plugin ever in wordpress. This plugin is to let you build any type of form however you want in fastest and easiesy way. It lets you create form in less than a minute and more effectively.
It is designed to let you have the best and hassle free experience. '); ?></p>
								</div>
								<a class="el-button button-default" href="https://wordpress.org/plugins/neoforms/" target="_blank"><?php _e( 'You can learn more about neoForms from here'); ?></a>
								<a class="el-button button-primary" href="https://wordpress.org/plugins/neoforms/" target="_blank"><?php _e( 'Download', 'wpfront'); ?></a>
							</div>
						</div>
					</el-card>
				</el-col>
			</el-row>
			<?php
		}
		?>
	</div>
</template>
<script>
	var promo_contact_form = {
	    template: '#promo_contact_form'
	}
</script>