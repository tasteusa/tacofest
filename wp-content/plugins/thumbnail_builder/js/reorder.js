( function( $ ) {
    $(document).ready(function () {

        var alert = $('<div class="alert thumbs-alert"></div>');
        var alertTimeout = null;

        function showAlert(message, role, timeout, cb){
            if($('.thumbs-alert').length > 0) $('.thumbs-alert').remove();
            var tmpAlert = alert.clone(true).addClass('alert-'+role).html(message);
            $('.form-container').before(tmpAlert);

            if(typeof(timeout) !="undefined" && timeout){
                if(alertTimeout != null) clearTimeout(alertTimeout);
                setTimeout(function(){
                    hideAlert();
                    alertTimeout == null;
                    if(typeof (cb) == "function")cb();
                },timeout)
            }
        }

        $('.load-thumbs').on('click',function(){
            var cat = $('.category-select').val();
            var btn = $(this);
            btn.addClass('disabled');
            btn.html('Please Wait...');
            $( ".row.thumbs-container" ).empty();
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:{
                    'action': 'get_thumbs_in_cat',
                    'tax': cat
                },
                dataType: "json",
                success: function (response) {

                    if(typeof(response.thumbs) != 'undefined' && response.thumbs.length > 0){
                        var thumbHtml = $( "#thumbTemplate" ).tmpl( response.thumbs ).appendTo( ".row.thumbs-container" );
                        $( ".row.thumbs-container" ).append(thumbHtml);
                    }else{
                        showAlert('No Thumbnails Found','info', 3000, false);
                    }
                },
                error:function(){
                    showAlert('Server Error','danger', 3000, false);
                },
                complete: function(){
                    btn.removeClass('disabled');
                    btn.html('load thumbnails');
                }
            });
        });

        function gatherData(){
            var data = [];

            return data;
        }

        function hideAlert(){
            if($('.thumbs-alert').length > 0) $('.thumbs-alert').remove();
        }

        $( ".thumbs-container" ).sortable({
            items: "div.single-linked-thumb",
            tolerance: "pointer",
            update: function(e, ui){
                var supEl =ui.item.prev();
                var put = 'after';

                if(supEl.length <= 0){
                    supEl = ui.item.next();
                    put = 'before';
                }

                var data = {
                    action: 'reorder_thumbs',
                    targetPostId: ui.item.find('.hiddden-thumb-id').val(),
                    supportPostId: supEl.find('.hiddden-thumb-id').val(),
                    put: put
                };

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data:data,
                    dataType: "json",
                    success: function (response) {

                    }
                });
            }
        });
        $( ".thumbs-container" ).disableSelection();

    });
})(jQuery);
