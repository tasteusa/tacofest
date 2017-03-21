( function( $ ) {
    $(document).ready(function () {

        var mediaFrame;
        var alert = $('<div class="alert thumbs-alert"></div>');
        var alertTimeout = null;

        function checkSubmitBtn(){
            var thumbs = $('.single-linked-thumb');
            var submBtn = $('.submit-thumb-btn');

            if(thumbs.length <= 0) return submBtn.addClass('hidden');
            return submBtn.removeClass('hidden');
        }

        function showAlert(message, role, timeout, cb){
            var buttons = $('.buttons-container');
            buttons.addClass('hidden');
            if($('.thumbs-alert').length > 0) $('.thumbs-alert').remove();
            var tmpAlert = alert.clone(true).addClass('alert-'+role).html(message);
            buttons.after(tmpAlert);

            if(typeof(timeout) !="undefined" && timeout){
                if(alertTimeout != null) clearTimeout(alertTimeout);
                setTimeout(function(){
                    hideAlert();
                    alertTimeout == null;
                    if(typeof (cb) == "function")cb();
                },timeout)
            }
        }

        function gatherData(){
            var data = [];

            var thumbs = $('.single-linked-thumb');
            var error = false;
            thumbs.each(function(i, thumb){
                var tmp = {
                    title: $.trim($(thumb).find('.title-field').val()),
                    attach_id: $(thumb).find('.hiddden-img-id').val(),
                    tax: $(thumb).find('.category-field').data('value'),
                    url:$.trim($(thumb).find('.url-field').val())
                };

                if($.trim(tmp.title) == ''){
                    error = true;
                }else{
                    data.push(tmp);
                }
            });

            if(error) return false;

            return data;
        }

        function hideAlert(){
            $('.buttons-container').removeClass('hidden');
            if($('.thumbs-alert').length > 0) $('.thumbs-alert').remove();
        }

        $('.submit-thumb-btn').on('click', function(){
            var thumbsData = gatherData();
            if(!thumbsData){
                showAlert('One or more thumbnails have no title','danger', 3000,false);
                return false;
            }
            showAlert('Please Wait','info', false, false);
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:{
                    'action': 'create_thumbs',
                    'thumbs': thumbsData
                },
                dataType: "json",
                success: function () {
                    location.href=RedirectUrl;
                    showAlert('Thumbnails Created','success', false, false);
                },
                error:function(){
                    showAlert('Server Error','danger', 3000, false);
                }
            });
        });

        $(document).on('click', '.single-linked-thumb .delete-thumb',function(){
            var thumb = $(this).closest('.single-linked-thumb');
            if(thumb.find('.category-field').data('ui-autocomplete') != undefined) thumb.find('.category-field').autocomplete( "destroy" );
            thumb.remove();
            checkSubmitBtn();
            $('.thumbs-container').sortable('refresh');
        });

        $( ".thumbs-container" ).sortable({
            items: "div.single-linked-thumb",
            tolerance: "pointer"
        });
        $( ".thumbs-container" ).disableSelection();

        $('.select-img-btn').on('click', function(){
            checkSubmitBtn();
            if ( mediaFrame ) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media.frames.tgm_media_frame = wp.media({
                multiple: true,
                library: {
                    type: 'image'
                }
            });

            mediaFrame.on('select', function(){
                var selection = mediaFrame.state().get('selection');
                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();
                    var tmplData = {
                        'img_url': attachment.url,
                        'img_id': attachment.id,
                        'title': attachment.title
                    };

                    var thumbHtml = $( "#thumbTemplate" ).tmpl( tmplData );
                    $( ".row.thumbs-container" ).append(thumbHtml);
                    thumbHtml.find('.category-field').first().autocomplete({
                        source: ThumbCategories,
                        minLength: 1,
                        select: function ( event, ui ) {
                            event.preventDefault();
                            $(this).val(ui.item.label).data('value',ui.item.value);
                            return ui.item.label;
                        }
                    });
                });
                checkSubmitBtn();
                $('.thumbs-container').sortable('refresh');
            });

            mediaFrame.open();

        });

    });
})(jQuery);
