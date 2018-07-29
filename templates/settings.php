<template id="wpfront_settings">
    <div>
        <?php
        $roles = get_editable_roles();
        $all_roles = array();
        foreach ( $roles as $name => $role ) {
	        $all_roles[$name] = $role['name'];
        }
        $all_roles = base64_encode(json_encode($all_roles));

        $pages = get_pages();
        $all_pages = array( '' => 'Select Page');
        foreach ( $pages as $k => $each_page ) {
            $all_pages[$each_page->ID] = $each_page->post_title;
        };
        $all_pages = base64_encode(json_encode($all_pages));

        //users
        $users = get_users( array(
                'fields' => array( 'id', 'user_login')
        ));
        $all_users = array();
        foreach ( $users as $k => $each ) {
	        $all_users[$each->id] = $each->user_login;
        };
        $all_users = base64_encode(json_encode($all_users));

        $forms = get_posts( array(
	        'post_type' => 'wpfront_form',
            'post_status' => 'publish'
        ));
        $all_forms = array();
        foreach ( $forms as $k => $each ) {
	        $all_forms[$each->ID] = $each->post_title;
        };
        $all_forms = base64_encode(json_encode($all_forms));

        $post_statuses = get_post_statuses();
        $all_post_statuses = base64_encode(json_encode($post_statuses));
        ?>
        <el-tabs type="border-card" tab-position="left">
            <el-alert v-if="notice.msg"
                :title="notice.header"
                :type="notice.type"
                :description="notice.msg"
                      :closable="false"
                      show-icon>
            </el-alert>
            <template v-for="(section, key) in global_settings_sections">
                <el-tab-pane :label="section.label">
                    <el-form>
                        <vue_form_builder :model="global_settings" :schema="section.schema.fields"></vue_form_builder>
                        <p>{{ section.desc }}</p>
                    </el-form>
                </el-tab-pane>
            </template>
            <a href="javascript:" class="button button-primary" @click="save_global_settings"><?php _e( 'Save'); ?></a>
        </el-tabs>
    </div>
</template>
<script>
    var $all_roles = JSON.parse(atob('<?php echo $all_roles; ?>'));
    var $all_pages = JSON.parse(atob('<?php echo $all_pages; ?>'));
    var $all_users = JSON.parse(atob('<?php echo $all_users; ?>'));
    var $all_forms = JSON.parse(atob('<?php echo $all_forms; ?>'));
    var $all_post_statuses = JSON.parse(atob('<?php echo $all_post_statuses; ?>'));

    var global_settings_sections = wpfront_apply_filters( 'wpfront_global_settings_sections', {});
    var wpfrontend_settings = {
        template: '#wpfront_settings',
        data: function () {
            return {
                global_settings_sections: global_settings_sections
            }
        },
        methods: {
            save_global_settings: function () {
                this.$store.dispatch( 'save_global_settings' );
            },
        },
        computed: {
            global_settings: function () {
                return this.$store.getters.global_settings;
            },
            notice: function () {
                return this.$store.getters.notice;
            }
        },
        mounted: function () {
            this.$store.dispatch('get_global_settings');
        }
    }
</script>