( function( $ ) {
    $(document).ready(function () {
        var parentSelector = (typeof(generatorBlock) != "undefined") ? generatorBlock+' ' : '';
        var reorderSelector = (typeof(reorderBlock) != "undefined") ? reorderBlock+' ' : '';
        var mediaFrame;
        var alert = $('<div class="alert thumbs-alert"></div>');
        var alertTimeout = null;

        function checkSubmitBtn(){
            var thumbs = $(parentSelector+'.single-linked-thumb');
            var submBtn = $(parentSelector+'.submit-thumb-btn');

            if(thumbs.length <= 0) return submBtn.addClass('hidden');
            return submBtn.removeClass('hidden');
        }

        function showAlert(message, role, timeout, cb){
            var buttons = $(parentSelector+'.buttons-container');
            if($(parentSelector+'.thumbs-alert').length > 0) $(parentSelector+'.thumbs-alert').remove();
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

            var thumbs = $(parentSelector+'.single-linked-thumb');
            var error = false;
            thumbs.each(function(i, thumb){
                var tmp = {
                    title: $.trim($(thumb).find('.title-field').val()),
                    attach_id: $(thumb).find('.hiddden-img-id').val(),
                    tax: $(thumb).find('.category-field').val(),
                    url:$.trim($(thumb).find('.url-field').val()),
                    text:$.trim($(thumb).find('.text-field').val())
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
            if($(parentSelector+'.thumbs-alert').length > 0) $(parentSelector+'.thumbs-alert').remove();
        }

        $(parentSelector+'.submit-thumb-btn').on('click', function(){
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
                    'action': 'tb_create_thumbs',
                    'thumbs': thumbsData
                },
                dataType: "json",
                success: function () {
                    $(parentSelector+'.single-linked-thumb').remove();
                    $(reorderSelector+'.load-thumbs').click();
                    checkSubmitBtn();
                    showAlert('Thumbnails Created','success', 3000, false);
                },
                error:function(){
                    checkSubmitBtn();
                    showAlert('Server Error','danger', 3000, false);
                }
            });
        });

        $(document).on('click', parentSelector+'.single-linked-thumb .delete-thumb',function(){
            var thumb = $(this).closest('.single-linked-thumb');
            thumb.remove();
            checkSubmitBtn();
            $(parentSelector+'.thumbs-container').sortable('refresh');
        });

        $(parentSelector+".thumbs-container" ).sortable({
            items: "div.single-linked-thumb",
            tolerance: "pointer"
        });

        $(parentSelector+".thumbs-container" ).disableSelection();

        $(parentSelector+'.select-img-btn').on('click', function(){
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

                    var thumbHtml = $("#thumbTemplate" ).tmpl( tmplData );
                    $(parentSelector+".row.thumbs-container" ).append(thumbHtml);
                });
                checkSubmitBtn();
                $(parentSelector+'.thumbs-container').sortable('refresh');
            });

            mediaFrame.open();

        });

        $(document).on('click', '.winner-select [name="winner"]', function () {
            var $this = $(this);
            if($this.prop('checked')){
                $this.closest('.single-linked-thumb').find('.winner-place-select').removeClass('hidden');
                $this.closest('.single-linked-thumb').find('.set-winner-tip').fadeIn();
            } else {
                $this.closest('.single-linked-thumb').find('.winner-place-select').addClass('hidden');
            }
            setTimeout(function () {
                $this.closest('.single-linked-thumb').find('.set-winner-tip').fadeOut()
            }, 5000);
        });

        $(document).on('change', '.place-select', function () {
            if($(this).val() != '0'){
                $(this).removeClass('danger');
                $(this).siblings('.require-error').empty();
            }
        });

    });
})(jQuery);
