<template id="cc_news">
	<div>
		<template v-if="response.status == 'ok'">
			<template v-if="response.posts">
				<div class="cc_news_container">
					<h1>
						<?php _e('Latest News','cc'); ?>
					</h1>
					<template v-for="(content,k) in response.posts">
						<div class="each_container">

							<div class="cc_news_notice_container">
								<div class="thumbnail">
									<img :src="content.thumbnail" alt="" width="200"
									     v-if="content.thumbnail"
									>
								</div>
								<div class="news_content">
									<h3>
										<h3><a :href="content.url" target="_blank" style="text-decoration:none;color:#444444;" v-html="content.title"></a></h3>
										<div v-html="content.excerpt"></div>
										<a :href="content.url" target="_blank"><?php _e( 'Read More', 'cc' ); ?></a>
								</div>
							</div>
						</div>
					</template>
					<a class="read_more_news" href="http://blog.cybercraftit.com/category/news/" target="_blank"><?php _e('Read More', 'cc'); ?></a>
				</div>
			</template>
		</template>
	</div>
</template>
<script>
	var cc_news = {
	    template: '#cc_news',
		data: function () {
			return {
			    response: ''
			}
        },
		mounted: function () {
	        var _this = this;
            (function ($) {
                $.post(
                    ajaxurl,
	                {
	                    action: 'cc_get_news'
	                },
	                function (data) {

		                if( data.success ) {
                            _this.response = data.data.response
		                }
                    }
                )
            }(jQuery));
        }
	}
</script>