var formFields = [
    /*post*/
    /*ping_status,post_password,post_name(slug),post_parent*/
    //post_type,post_author, post_date,post_content, post_title, post_excerpt, post_status, comment_status, ,
    //post_type
    {
        type: 'input',
        inputType: 'select',
        preview: {
            'label': 'Post Type',
            name: 'post_type'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //post_author
    {
        type: 'input',
        inputType: 'text',
        preview: {
            'label': 'Post Author',
            name: 'post_author'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //post_date
    {
        type: 'input',
        inputType: 'text',
        preview: {
            'label': 'Post Date',
            name: 'post_date'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //post_content
    {
        type: 'input',
        inputType: 'post_content',
        preview: {
            'label': 'Post Content',
            name: 'post_content'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //post_title
    {
        type: 'input',
        inputType: 'post_title',
        preview: {
            'label': 'Post Title',
            name: 'post_title'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //post_excerpt
    {
        type: 'input',
        inputType: 'post_excerpt',
        preview: {
            'label': 'Post Excerpt',
            name: 'post_excerpt'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //post_status
    {
        type: 'input',
        inputType: 'post_status',
        preview: {
            'label': 'Post Status',
            name: 'post_status'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },

    //comment_status
    {
        type: 'input',
        inputType: 'comment_status',
        preview: {
            'label': 'Comment Status',
            name: 'comment_status'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    /*post ends*/
    //text
    {
        type: 'input',
        inputType: 'text',
        preview: {
            'label': 'Text',
            name: 'text'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //textarea
    {
        type: 'input',
        inputType: 'textarea',
        preview: {
            'label': 'Textarea',
            name: 'textarea'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //number
    {
        type: 'input',
        inputType: 'number',
        preview: {
            'label': 'Number',
            name: 'number'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //radio
    {
        type: 'input',
        inputType: 'radio',
        preview: {
            label: 'Radio',
            name: 'radio'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //radio_group
    {
        type: 'input',
        inputType: 'radio_group',
        preview: {
            label: 'Radio Group',
            name: 'radio_group'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //option_group
    /*{
        type: 'input',
        inputType: 'option_group',
        preview: {
            label: 'Option Group',
            name: 'option_group'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },*/
    //checkbox
    {
        type: 'input',
        inputType: 'checkbox',
        preview: {
            'label': 'Checkbox',
            name: 'checkbox'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //checkbox group
    {
        type: 'input',
        inputType: 'checkbox_group',
        preview: {
            'label': 'Checkbox Group',
            name: 'checkbox_group'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //select
    {
        type: 'input',
        inputType: 'select',
        preview: {
            'label': 'Select',
            name: 'select'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //website url
    {
        type: 'input',
        inputType: 'website_url',
        preview: {
            label: 'Website Url',
            name: 'website_url'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //email address
    {
        type: 'input',
        inputType: 'email_address',
        preview: {
            'label': 'Email Address',
            name: 'email_address'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //password
    {
        type: 'input',
        inputType: 'password',
        preview: {
            'label': 'Password',
            name: 'password'
        },
        settings:{
            atts: {
                span: 12
            }
        }
    },
    //hidden_field
    {
        type: 'input',
        inputType: 'hidden_field',
        preview: {
            'label': 'Hidden Field',
            name: 'hidden_field'
        },
        settings:{
            atts: {
                span: 24
            }
        }
    }
];

formFields = wpfront_apply_filters( 'wpfront_formFields', formFields );