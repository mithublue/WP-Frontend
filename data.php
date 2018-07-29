<script>
    /*global settings*/
    wpfront_add_filter( 'wpfront_global_settings', function( global_settings ) {
        var settings = {
            'admin_bar_roles' : [],
            'allow_admin_access_roles' : [],
            //post submission
            'edit_page' : '',
            'default_post_author' : 'admin',
            'default_post_form' : '',
            //dashboard
            'dashboard_page' : '',
            'user_can_edit_post' : true,
            'user_can_delete_post' : true,
            'editable_post_status' : ['pending'],
            'deletable_post_status' : ['pending'],
            'dashboard_post_per_page' : 10,
            'show_user_bio' : false,
            'show_post_count' : false,
            //'show_featured_img' : false,
            'unauth_msg' : 'You need to login to access this page',
            //login/reg
            'login_page' : '',
            'reg_page' : '',
            'auto_login_after_reg' : false,
            'login_redirect_page' : '',

        };
        global_settings = Object.assign({}, global_settings, settings );
        return global_settings;
    });
    wpfront_add_filter( 'wpfront_global_settings_sections', function (global_settings_sections) {

        var settings_sections = {
            general: {
                label: 'General',
                desc: 'General settings',
                schema: {
                    fields: [
                        {
                            model: 'admin_bar_roles',
                            label: 'Show admin bar for these roles',
                            desc: 'WP Frontend removes admin bar from frontend for the all roles EXCEPT the roles selected here.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: $all_roles
                        },
                        {
                            model: 'allow_admin_access_roles',
                            label: 'Allow access to wp admin panel for these roles',
                            desc: 'WP Frontend will allow users with these roles to access the wordpress admin panel.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: $all_roles
                        }
                    ]
                }
            },
            'post_submission' : {
                label: 'Post Submission',
                desc: 'Post Submission Settings',
                schema: {
                    fields: [
                        {
                            model: 'edit_page',
                            label: 'Choose page which will be treated as edit page.',
                            desc: 'Edit page is the page where the user will edit post. Generally, this is the page containing the [wpfrontend_edit] shortcode',
                            type: 'select',
                            options: $all_pages
                        },
                        {
                            model: 'default_post_author',
                            label: 'Default author for a post created',
                            desc: 'Default post author for a post created. (If the post author is not found)',
                            type: 'select',
                            options: $all_users
                        },
                        {
                            model: 'default_post_form',
                            label: 'Default Fallback Form',
                            desc: 'Choose Form Which will be Used by Default if no associated form for a post it not found.',
                            type: 'select',
                            options: $all_forms
                        }
                    ]
                }
            },
            dashboard: {
                label: 'Dashboard',
                desc: 'Dashboard settings',
                schema : {
                    fields: [
                        {
                            model: 'dashboard_page',
                            label: 'Choose page which will be treated as Dashboard/My Account page for user.',
                            desc: 'Dashboard/My Account page is the page which will be the dashboard for logged in users.',
                            type: 'select',
                            options: $all_pages
                        },
                        {
                            model: 'user_can_edit_post',
                            desc: 'Check this, if you want the post author to be able to edit post.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: {
                                true: 'Can User Edit Post ?'
                            }
                        },
                        {
                            model: 'editable_post_status',
                            label: 'Editable Post Status',
                            desc: 'Post will be editable by the post author for the selected post status.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: $all_post_statuses,
                            visible: function (model) {
                                return model.user_can_edit_post;
                            }
                        },
                        {
                            model: 'user_can_delete_post',
                            desc: 'Check this, if you want the post author to be able to delete post.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: {
                                true: 'Can User Delete Post ?'
                            }
                        },
                        {
                            model: 'deletable_post_status',
                            label: 'Deletable Post Status',
                            desc: 'Post will be deletable by the post author for the selected post status.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: $all_post_statuses,
                            visible: function (model) {
                                return model.user_can_delete_post;
                            }
                        },
                        {
                            model: 'show_user_bio',
                            desc: 'Check this, if you want the user bio in dashboard.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: {
                                true: 'Show User Bio ?'
                            }
                        },
                        {
                            model: 'show_post_count',
                            desc: 'Check this, if you want to show post count in dashboard.',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: {
                                true: 'Show Post Count ?'
                            }
                        },
                        {
                            model: 'dashboard_post_per_page',
                            label: 'Post Per Page',
                            desc: 'Number of posts per page in dashboard.',
                            type: 'input',
                            inputType: 'number',
                        },
                        {
                            model: 'unauth_msg',
                            label: 'Message for Non Logged in User',
                            type: 'textarea',
                            desc: 'Message to show in dashboard, if user is not logged in.'
                        }
                    ]
                }
            },
            login_reg: {
                label: 'Login and Registration Settings',
                schema: {
                    fields: [
                        {
                            model: 'login_page',
                            label: 'Choose page which will be treated as login page for user.',
                            desc: 'Login page is the page which will be used for login instead of default wordpress login page. Leave it blank to use default login page',
                            type: 'select',
                            options: $all_pages
                        },
                        {
                            model: 'reg_page',
                            label: 'Choose page which will be treated as registration page for user.',
                            desc: 'Registration page is the page which will be used for login instead of default wordpress registration page. Leave it blank to use default registration page',
                            type: 'select',
                            options: $all_pages
                        },
                        {
                            model: 'auto_login_after_reg',
                            desc: 'Check this, if you want user to be automatically logged in after registration',
                            type: 'input',
                            inputType: 'checkbox',
                            vertical: true,
                            options: {
                                true: 'Login automatically after registration'
                            }
                        },
                        {
                            model: 'login_redirect_page',
                            label: 'Select Page Where User will be Redirected After Login.',
                            desc: 'User will be redirected to the selected page after login.',
                            type: 'select',
                            options: $all_pages
                        }
                    ]
                }
            }
        };

        global_settings_sections = Object.assign({},global_settings_sections, settings_sections);
        return global_settings_sections;
    });
    /**/
</script>