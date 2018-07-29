var wpfront_form_type_data = {
    blank_form: {
        label: 'Blank Form',
        form_type: '',
        field_data: []
    },
    contact: {
        label: 'Contact Form',
        form_type: 'contact',
        field_data: [
            {
                type: 'row',
                preview: {
                    label: 'Row'
                },
                row_formdata: [
                    {
                        type: 'input',
                        inputType: 'text',
                        preview: {
                            'label': 'Text',
                            name: 'text'
                        },
                        s:{
                            required: false,
                            name: 'name',
                            label: 'Name',
                            id: '',
                            class: '',
                            placeholder: '',
                            maxlength: '',
                            value: '',
                            has_relation: false,
                            relation: [{
                                field: '',
                                value : '',
                                relation_type: 'and'
                            }],
                        },
                        settings:{
                            atts: {
                                span: 12
                            }
                        }
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        preview: {
                            'label': 'Text',
                            name: 'text'
                        },
                        s:{
                            required: true,
                            name: 'email',
                            label: 'Email',
                            id: '',
                            class: '',
                            placeholder: '',
                            maxlength: '',
                            value: '',
                            has_relation: false,
                            relation: [{
                                field: '',
                                value : '',
                                relation_type: 'and'
                            }],
                        },
                        settings:{
                            atts: {
                                span: 12
                            }
                        }
                    },
                    {
                        type: 'input',
                        inputType: 'text',
                        preview: {
                            'label': 'Text',
                            name: 'text'
                        },
                        s:{
                            required: true,
                            name: 'subject',
                            label: 'Subject',
                            id: '',
                            class: '',
                            placeholder: '',
                            maxlength: '',
                            value: '',
                            has_relation: false,
                            relation: [{
                                field: '',
                                value : '',
                                relation_type: 'and'
                            }],
                        },
                        settings:{
                            atts: {
                                span: 24
                            }
                        }
                    },
                    {
                        type: 'input',
                        inputType: 'textarea',
                        preview: {
                            'label': 'Textarea',
                            name: 'textarea'
                        },
                        settings:{
                            atts: {
                                span: 24
                            }
                        },
                        s:{
                            required: true,
                            name: 'message',
                            label: 'Message',
                            id: '',
                            class: '',
                            placeholder: '',
                            maxlength: '',
                            value: '',
                            has_relation: false,
                            relation: [{
                                field: '',
                                value : '',
                                relation_type: 'and'
                            }],
                        }
                    }
                ]
            },
        ]
    }
};

wpfront_form_type_data = wpfront_apply_filters( 'wpfront_form_type_data', wpfront_form_type_data );