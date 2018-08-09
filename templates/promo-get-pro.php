<template id="wpfront_get_pro">
	<el-row :gutter="20">
		<el-col :sm="24" class="mb20">
			<el-card :body-style="{ padding: '0px' }">
				<div style="" class="text_center pt30 pb30 pr30 pl20">
					<div class="text_center font_36">
						<i class="el-icon el-icon-info"></i>
					</div>
					<h3 class="mb20 mt20"><?php _e( 'Upgrade to Pro Version to Break the Limit !', 'wpfront' ); ?></h3>
					<div class="bottom clearfix">
						<div class="mb20">
							<?php _e( 'Upgrading to pro version, you can have access to additional and advanced features that will make you enable to find the awesmeness !', 'wpfront'); ?>
						</div>
						<div class="mb20">
							<h4><?php _e( 'With Upgrading to Pro You will Have '); ?></h4>
							<ul>
								<li> Premium support.</li>
								<li> Automatic and regular update.</li>
								<li> Role based permission. You can define the roles that you want to have access for the form</li>
								<li> Multistep functionality.</li>
								<li> Different form presets</li>
								<li> 14 New and complex fields unlocked.</li>
								<li> Conditional fields. You can set field to be dependable on other fields when rendering.</li>
								<li> And more...</li>
							</ul>
						</div>
						<a href="https://cybercraftit.com/wp-frontend-pro/" target="_blank" class="el-button el-button--primary"><?php _e( 'Upgrade to Pro', 'wpfront' ); ?></a>
					</div>
				</div>
			</el-card>
		</el-col>
	</el-row>
</template>
<script>
    var wpfront_get_pro = {
        template: '#wpfront_get_pro'
    }
</script>