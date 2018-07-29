<template id="wpfront_public_form">
    <!--if -->
</template>
<script>
    ;document.addEventListener("DOMContentLoaded", function(event) {
        Vue.component('wpfront_public_form',{
            props: ['formdata','relations','form_settings'],
            template: '#wpfront_public_form'
        });
    });
</script>
<template id="wpfront_row">
    <div>
        <el-row>
            <template v-for="(field_data,k) in form_data">
                <wpfront_input_template :relations="relations" v-if="field_data.type == 'input' || field_data.inputType == 'text'" :field_data="field_data" :target_row="row_number" :target_col="k"></wpfront_input_template>
            </template>
        </el-row>
    </div>
</template>
<script>
    ;document.addEventListener("DOMContentLoaded", function(event) {
        Vue.component('wpfront_row',{
            template: '#wpfront_row',
            props: ['row_number','form_data','relations']
        });
    });
</script>
<template id="wpfront_input_template">
    <el-col :md="field_data.settings.atts.span">
        <div v-if="relations[field_data.s.name]">
            <el-form-item :label="field_data.s.label" :class="{'is-required': field_data.s.required}">
                <?php do_action('wpfront_form_item_top' )?>
                <?php include_once 'form-items.php';?>
                <?php do_action('wpfront_form_item_bottom' )?>
            </el-form-item>
        </div>
    </el-col>
</template>
<script>
    ;document.addEventListener("DOMContentLoaded", function(event) {
        Vue.component('wpfront_input_template',{
            props: ['field_data','target_row','target_col','relations'],
            template: '#wpfront_input_template'
        });
    });
</script>
<?php do_action('components_form_items' ); ?>