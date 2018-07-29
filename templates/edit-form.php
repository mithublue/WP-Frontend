<template id="wpfront_edit_form">
    <div>

    </div>
</template>
<script>
    var wpfront_edit_form = {
        template: '#wpfront_edit_form',
        computed: {
            form: function () {
                return this.$store.getters.form;
            }
        },
        mounted: function () {

        }
    }
</script>