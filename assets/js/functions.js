var wpfront_hooks = {};

function wpfront_add_filter( filtername, anonymus_function ) {
    if( typeof wpfront_hooks[filtername] === 'undefined' ) {
        wpfront_hooks[filtername] = {};
    }

    wpfront_hooks[filtername][Object.keys(wpfront_hooks[filtername]).length] = anonymus_function;
}

function wpfront_apply_filters (filtername, data) {
    if( typeof wpfront_hooks[filtername] !== 'undefined' ) {
        for ( k in wpfront_hooks[filtername] ) {
            data = wpfront_hooks[filtername][k](data);
        }
    }
    return data;
}

if( typeof wpfront_stringify !== 'function' ) {
    var wpfront_stringify  = function (field) {
        var json = btoa(JSON.stringify(field, function(key, value) {
            if (typeof value === "function") {
                return "/Function(" + value.toString() + ")/";
            }
            return value;
        }));
        return json;
    };
}

if( typeof wpfront_parse !== 'function' ) {
    var wpfront_parse = function (json) {
        var field = JSON.parse(atob(json), function(key, value) {
            if (typeof value === "string" &&
                value.startsWith("/Function(") &&
                value.endsWith(")/")) {
                value = value.substring(10, value.length - 2);
                return eval("(" + value + ")");
            }
            return value;
        });
        return field;
    };
}

if( typeof wpfront_reset_fields !== 'function' ) {
    var wpfront_reset_fields = function (field) {

        // Convert to JSON using a replacer function to output
        // the string version of a function with /Function(
        // in front and )/ at the end.
        var json = JSON.stringify(field, function(key, value) {
            if (typeof value === "function") {
                return "/Function(" + value.toString() + ")/";
            }
            return value;
        });

        // Convert to an object using a reviver function that
        // recognizes the /Function(...)/ value and converts it
        // into a function via -shudder- `eval`.
        var field = JSON.parse(json, function(key, value) {
            if (typeof value === "string" &&
                value.startsWith("/Function(") &&
                value.endsWith(")/")) {
                value = value.substring(10, value.length - 2);
                return eval("(" + value + ")");
            }
            return value;
        });
        return field;
    };
}

function wpfront_make_sortable_row(sortObj,item,self,self_items) {
    ;(function ($) {
        $(sortObj).sortable({
            items: item,
            handle: '.wpfront_row_mover',
            update: function (event,ui) {

                self.render_field_list = false;

                var prev_index = jQuery(ui.item).attr('target_row');
                var current_index = jQuery(ui.item).index()-1;

                var temp_obj = wpfront_reset_fields( self_items[prev_index] );
                self_items.splice(prev_index,1);

                setTimeout(function () {
                    self_items.splice(current_index,0,temp_obj);
                    self.render_field_list = true;

                    setTimeout(function () {
                        wpfront_make_sortable_row(sortObj,item,self,self_items);
                        wpfront_make_sortable_field('.ui-wpfront_row','.ui-wpfront_col',self,self_items);
                    },1);
                },1);
            }
        });
    }(jQuery));
}

function wpfront_make_sortable_field(sortObj,item,self,self_items) {
    ;(function ($) {
        $(sortObj).sortable({
            items: item,
            handle: '.wpfront_col_mover',
            connectWith: sortObj,
            update: function (event,ui) {

                self.render_field_list = false;

                var prev_index = $(ui.item).attr('target_col');
                var prev_row_index = $(ui.item).attr('target_row');

                var current_index = $(ui.item).index() - 1;
                var current_row_index = $(ui.item).closest('.ui-wpfront_row').attr('target_row');

                var temp_obj = wpfront_reset_fields( self_items[prev_row_index].row_formdata[prev_index] );
                
                self_items[prev_row_index].row_formdata.splice(prev_index,1);
                setTimeout(function () {
                    self_items[current_row_index].row_formdata.splice(current_index,0,temp_obj);
                    self.render_field_list = true;
                    setTimeout(function () {
                        wpfront_make_sortable_row('#ui-wpfront_builder_ground','.ui-wpfront_row',self,self_items);
                        wpfront_make_sortable_field(sortObj,item,self,self_items);
                    },1);
                },1);
            }
        });
    }(jQuery));
}

function wpfront_reset_sortable( self, is_multistep ) {
    setTimeout(function () {
        if( !is_multistep ) {
            wpfront_make_sortable_row('#ui-wpfront_panel_ground','.ui-wpfront_row',self,self.formdata.field_data);

            wpfront_make_sortable_row('#ui-wpfront_builder_ground','.ui-wpfront_row',self,self.formdata.field_data);
            wpfront_make_sortable_field('.ui-wpfront_row','.ui-wpfront_col',self,self.formdata.field_data);
        } else {
            for( var s in self.field_data ) {
                wpfront_make_sortable_row('#ui-wpfront_panel_ground','.ui-wpfront_row',self,self.formdata.field_data[s].step_formdata);

                wpfront_make_sortable_row('#ui-wpfront_builder_ground','.ui-wpfront_row',self,self.formdata.field_data[s].step_formdata);
                wpfront_make_sortable_field('.ui-wpfront_row','.ui-wpfront_col',self,self.formdata.field_data[s].step_formdata);
            }
        }
    },1000);

}

function wpfront_reset_sortable_row( self, is_multistep ) {
    if( !is_multistep ) {
        setTimeout(function () {
            wpfront_make_sortable_row('#ui-wpfront_panel_ground','.ui-wpfront_row',self,self.formdata.field_data);

            wpfront_make_sortable_row('#ui-wpfront_builder_ground','.ui-wpfront_row',self,self.formdata.field_data);
        },1);
    } else {
        for( var s in self.field_data ) {
            wpfront_make_sortable_row('#ui-wpfront_panel_ground','.ui-wpfront_row',self,self.formdata.field_data[s].step_formdata);

            wpfront_make_sortable_row('#ui-wpfront_builder_ground','.ui-wpfront_row',self,self.formdata.field_data[s].step_formdata);
        }
    }
}

function wpfront_reset_sortable_field( self, is_multistep ) {
    if( !is_multistep ) {
        setTimeout(function () {
            wpfront_make_sortable_field('.ui-wpfront_row','.ui-wpfront_col',self,self.formdata.field_data);
        },1);
    } else {
        for( var s in self.field_data ) {
            wpfront_make_sortable_field('.ui-wpfront_row','.ui-wpfront_col',self,self.formdata.field_data[s].step_formdata);
        }
    }
}