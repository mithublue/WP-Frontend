var wpfront_post_types = {};

for( k in wpfront_obj.post_types ) {
    wpfront_post_types[k] = wpfront_obj.post_types[k];
}

var wpfront_form_settings = {
    form_settings: {
        for: '',
        label: 'Post Settings',
        s: {
            post_type: '',
            post_status: '',
            enable_draft_save: true,
            post_format: '',
            default_tax_category: '',
            redirect_to: 'same',
            submit_btn_text: 'Submit',
            enable_draft_btn: true,
            page_id: '',
            url: '',
            comment_status: 'open',
            success_msg: 'Form submission successful',
            failure_msg: 'Form submission failed'
        },
        schema: {
            fields: [
                {
                    model: 'post_type',
                    type: 'select',
                    label: 'Post Type',
                    desc: 'Post of this post type will be created by this form',
                    options: wpfront_obj.post_types
                },
                {
                    model: 'post_status',
                    type: 'select',
                    label: 'Post Status',
                    desc: 'Default post status for post created',
                    options: wpfront_obj.post_statuses
                },
                {
                    model: 'post_format',
                    type: 'select',
                    label: 'Post Format',
                    desc: 'Default post format for post created',
                    options: wpfront_obj.post_formats
                },
                {
                    model: 'enable_draft_save',
                    type: 'input',
                    inputType: 'checkbox',
                    desc: 'Check this if you want to let users save the created post as draft.',
                    options: {
                        true: 'Enable saving as draft'
                    }
                },
                {
                    model: 'default_tax_category',
                    type: 'select',
                    label: 'Default Post Category',
                    desc: 'Default post category for post created (if supported)',
                    options: wpfront_obj.default_tax_category,
                    multiple: true
                },
                {
                    model: 'redirect_to',
                    type: 'select',
                    label: 'Redirect to',
                    desc: 'Where the page should be redirect to, after successful form sumission',
                    options: {
                        'same' : 'Same Page',
                        'to_page' : 'To a Page',
                        'to_url' : 'To a URL'
                    }
                },
                {
                    model: 'page_id',
                    type: 'select',
                    label: 'Select Page',
                    desc: 'Select the page, where the page should be redirected after successful form submission',
                    options: $neoform_pages,
                    visible: function (model) {
                        return model.redirect_to === 'to_page';
                    }
                },
                {
                    model: 'url',
                    type: 'input',
                    inputType: 'text',
                    label: 'Redirect URL',
                    desc: 'Place the url where the user should be redirected after successful form submission',
                    visible: function (model) {
                        return model.redirect_to === 'to_url';
                    }
                },
                /*{
                    model: 'success_msg',
                    type: 'textarea',
                    label: 'Success Message',
                    desc: 'Message that will be shown after successful form submission',
                    visible: function (model) {
                        return model.redirect_to === 'same';
                    }
                },*/
                {
                    model: 'comment_status',
                    type: 'select',
                    label: 'Comment Status',
                    desc: 'Default comment status',
                    options: wpfront_obj.post_comment_statuses
                },
                {
                    model: 'submit_btn_text',
                    type: 'input',
                    inputType: 'text',
                    label: 'Submit Button Text',
                    desc: 'Submi button text'
                },
                {
                    model: 'success_msg',
                    type: 'textarea',
                    label: 'Message on Success',
                    desc: 'This message will be displayed after successful form submissio',
                },
                {
                    model: 'failure_msg',
                    type: 'textarea',
                    label: 'Message on Failure',
                    desc: 'This message will be displayed if form submission fails',
                }
            ]
        }
    },
    edit_settings: {
        for: '',
        label: 'Edit Settings',
        s: {
            post_status: '',
            redirect_to: 'same',
            page_id: '',
            url: '',
            submit_btn_text: 'Update',
            success_msg: 'Form submission successful',
            failure_msg: 'Form submission failed'
        },
        schema: {
            fields: [
                {
                    model: 'post_status',
                    type: 'select',
                    label: 'Post Status',
                    desc: 'Default post status for post created',
                    options: wpfront_obj.post_statuses
                },
                {
                    model: 'redirect_to',
                    type: 'select',
                    label: 'Redirect to',
                    desc: 'Where the page should be redirect to, after successful form sumission',
                    options: {
                        'same' : 'Same Page',
                        'to_page' : 'To a Page',
                        'to_url' : 'To a URL'
                    }
                },
                {
                    model: 'page_id',
                    type: 'select',
                    label: 'Select Page',
                    desc: 'Select the page, where the page should be redirected after successful form submission',
                    options: $neoform_pages,
                    visible: function (model) {
                        return model.redirect_to === 'to_page';
                    }
                },
                {
                    model: 'url',
                    type: 'input',
                    inputType: 'text',
                    label: 'Redirect URL',
                    desc: 'Place the url where the user should be redirected after successful form submission',
                    visible: function (model) {
                        return model.redirect_to === 'to_url';
                    }
                },
                {
                    model: 'submit_btn_text',
                    type: 'input',
                    inputType: 'text',
                    label: 'Submit Button Text',
                    desc: 'Submi button text'
                },
                {
                    model: 'success_msg',
                    type: 'textarea',
                    label: 'Message on Success',
                    desc: 'This message will be displayed after successful form submissio',
                },
                {
                    model: 'failure_msg',
                    type: 'textarea',
                    label: 'Message on Failure',
                    desc: 'This message will be displayed if form submission fails',
                }
            ]
        }
    },
    form_restriction: {
        for: '',
        label: 'Restriction Settings',
        s: {
            is_scheduled: false,
            schedule_from: '',
            schedule_to: '',
            msg_before_schedule: '',
            limit_submission: false,
            number_of_submission: 0,
            limit_break_msg: '',
            guest_post: true,
            guest_post_fields: ['email'],
            require_login_msg: ''
        },
        schema: {
            fields: [
                {
                    model: 'is_scheduled',
                    type: 'input',
                    inputType: 'checkbox',
                    desc: 'Check this if you want the users enabled to submit form or a time period.',
                    options: {
                        true: 'Schedule form for a time period'
                    }
                },
                {
                    model: 'schedule_from',
                    type: 'datetimepicker',
                    label: 'Schedule Start Date',
                    desc: 'The date when the form will be accessible from',
                    visible: function (model) {
                        return model.is_scheduled;
                    }
                },
                {
                    model: 'schedule_to',
                    type: 'datetimepicker',
                    label: 'Schedule End Date',
                    desc: 'The date after when the form will not be accessible and submission will not be valid',
                    visible: function (model) {
                        return model.is_scheduled;
                    }
                },
                {
                    model: 'msg_before_schedule',
                    type: 'textarea',
                    label: 'Message before/after Schedule',
                    desc: 'Text that will be displayed if user visits the form page before schedule starts or after schedule ends.',
                    visible: function (model) {
                        return model.is_scheduled;
                    }
                },
                {
                    model: 'limit_submission',
                    type: 'input',
                    inputType: 'checkbox',
                    desc: 'Limit form submission',
                    options:{
                        true: 'Limit Form Submission'
                    }
                },
                {
                    model: 'number_of_submission',
                    type: 'input',
                    inputType: 'number',
                    label: 'Number of Submission Allowed',
                    desc: 'Number of form submission allowed',
                    visible: function (model) {
                        return model.limit_submission;
                    }
                },
                {
                    model: 'limit_break_msg',
                    type: 'textarea',
                    label: 'Message after Submission Limit Reached',
                    desc:'Message that will be displayed to user if the limit for submission reached',
                    visible: function (model) {
                        return model.limit_submission;
                    }
                },
                {
                    model: 'guest_post',
                    type: 'input',
                    inputType: 'checkbox',
                    desc: 'Check this if you want user to create post as guest (Creating post will not require login)',
                    options: {
                        true: 'Enable guest posting'
                    }
                },
                {
                    model: 'require_login_msg',
                    type: 'textarea',
                    label: 'Login Message',
                    desc: 'Message to show non loggedin users',
                    visible: function (model) {
                        return !model.guest_post;
                    }
                }
            ]
        }
    },
    appearance_settings: {
        for: '',
        label: 'Appearance Settings',
        s: {
            layout_type: 'rounded'
        },
        schema: {
            fields:[
                {
                    model: 'layout_type',
                    type: 'select',
                    label: 'Layout Type',
                    desc: 'Select layout type',
                    options: {
                        rounded: 'Rounded',
                        cornered: 'Cornered'
                    }
                }
            ]
        }
    }
};

wpfront_form_settings = wpfront_apply_filters( 'wpfront_form_settings', wpfront_form_settings );