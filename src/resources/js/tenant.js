/**
 * Created by t on 9/9/2017.
 */
rg_tenants = function() {

    var init = function() {
        var jQ_body = $('body');

        jQ_body.on('click', '.rg-ajax-tenant-destroy', function (ev) {

            var _this = $(this);
            var callback = _this.data('callback');

            ev.stopPropagation();
            ev.preventDefault();

            rutatiina.transaction_delete({
                datatable: null,
                url: _this.attr('href'),
                method: 'POST',
                data: {_method: 'DELETE'},

                onSuccessCallback: function() {
                    if(callback) {
                        window.location.replace(callback);
                    }
                },
                onFailureCallback: function() {
                    //do nothing
                },

                title: "Are you sure?",
                text: "You will not be able to recover all the data!",
                type: "warning",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel pls!"
            });

        });
    }

    return {
        // public functions
        init: function() {

            init();

            try {
                datatable_sidebar();
            } catch (e) {
                console.log(e);
            }

            try {
                datatable_txns();
            } catch (e) {
                console.log(e);
            }

        }
    };
}();

jQuery(document).ready(function() {

    rg_tenants.init();

});