<div class="wrap">
    <div id="wpfront-app">
        <div class="mt20">
            <router-view></router-view>
        </div>
    </div>
</div>
<?php
include_once 'form-types.php';
include_once 'forms.php';
include_once 'form.php';
include_once 'edit-form.php';
include_once 'settings.php';
include_once 'entries.php';
include_once 'entry.php';
include_once 'help.php';
include_once 'promo-contact-form.php';
include_once 'promo-registration-form.php';
do_action('wpfront_admin_templates' );
