var wpfront_routes = [
    {path: '/', redirect: '/forms'},
    {path: '/forms', component: wpfrontend_forms},
    {path: '/forms/:status/page/:page', component: wpfrontend_forms},
    {path: '/forms/form-types', component: wpfrontend_form_types},
    {path: '/forms/new-form/:form_type', component: wpfrontend_new_form},

    {path: '/forms/new-form/type/:type/:form_type', component: wpfrontend_new_form},

    {path: '/forms/form/:form_id/edit', component: wpfrontend_new_form},
    {path: '/forms/new-form/update', component: wpfrontend_new_form},
    {path: '/settings', component: wpfrontend_settings},
    {path: '/forms/entries/:form_type', component: wpfrontend_entries},
    {path: '/forms/entries/:form_type/:status/page/:page', component: wpfrontend_entries},
    {path: '/forms/entries/:form_type/view/:id', component: wpfrontend_entry},
    {path: '/help', component: wpfrontend_help},
    {path: '/forms/promo-contact-form', component: promo_contact_form},
    {path: '/forms/promo-registration-form', component: promo_registration_form},
    {path: '/cc-news', component: cc_news},
];

wpfront_routes = wpfront_apply_filters( 'wpfront_routes', wpfront_routes );

const router = new VueRouter({
    routes : wpfront_routes,
    'linkActiveClass': 'active'
});

var row_data =  {
    type: 'row',
    preview: {
        label: 'Row'
    },
    row_formdata: []
};

var app = new Vue({
    store: store,
    router: router,
    el: '#wpfront-app',
    data: {}
});