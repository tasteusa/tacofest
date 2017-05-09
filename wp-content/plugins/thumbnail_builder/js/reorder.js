( function( $ ) {
    var mediaFrame = false;
    var loadedCategory = null;
    $(document).ready(function () {
        var alertRolesAliases ={
            'error':'danger'
        };
        var alert = $('<div class="alert thumbs-alert"></div>');
        var alertTimeout = null;

        function showAlert(message, role, timeout, cb){
            if(typeof(alertRolesAliases[role]) == "string") role = alertRolesAliases[role];

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
            loadThumbsContainer(cat,'custom',btn);
            $('.sort-thumbs-a-z').removeClass('disabled');
        });

        $('.sort-thumbs-a-z').on('click',function(){
            var cat = $('.category-select').val();
            var btn = $(this);
            loadThumbsContainer(cat,'a-z',btn);
        });

        function loadThumbsContainer(cat,order,btn) {
            btn.addClass('disabled');
            var btn_name = btn.html();
            btn.html('Please Wait...');
            $( ".row.thumbs-container" ).empty();
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:{
                    'action': 'tb_get_thumbs_in_cat',
                    'order': order,
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
                    loadedCategory = cat;
                },
                error:function(){
                    showAlert('Server Error','danger', 3000, false);
                },
                complete: function(){
                    btn.removeClass('disabled');
                    btn.html(btn_name);
                }
            });
        }
        
        function makeChanged(thumbContainer){
            thumbContainer.addClass('changed');
            thumbContainer.find('.save-thumb').removeClass('disabled');
        }

        $( ".thumbs-container" ).on('input', 'input, select', function(){
            var thumbContainer = $(this).closest('.single-linked-thumb');
            makeChanged(thumbContainer);
        });

        $( ".thumbs-container" ).on('click','.single-linked-thumb .thumb-img', function(e){
            var img = $(this);
            var thumbContainer = img.closest('.single-linked-thumb');
            var imgField = thumbContainer.find('.hiddden-img-id');

            selectImg(function (data) {
                imgField.val(data.imgId);
                img.attr('src',data.imgUrl);
                makeChanged(thumbContainer);
            });
        });

        $( ".thumbs-container" ).on('click','.single-linked-thumb .save-thumb', function(e){
            var btn = $(this);
            var thumb = btn.closest('.single-linked-thumb');

            if(btn.hasClass('disabled') || !thumb.hasClass('changed')){
                e.preventDefault();
                return false;
            }

            $( ".thumbs-container" ).sortable( "disable" );
            var data = {
                action: 'tb_edit_thumb',
                thumbId: thumb.find('.hiddden-thumb-id').val(),
                thumbTitle: $.trim(thumb.find('.title-field').val()),
                thumbImg: (thumb.find('.hiddden-img-id').val() != '')?thumb.find('.hiddden-img-id').val():null,
                thumbCat: (thumb.find('.category-field').val() != '')?thumb.find('.category-field').val():0,
                thumbUrl:$.trim(thumb.find('.url-field').val())
            };
            showAlert('Please wait...','info');

            btn.addClass('disabled');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:data,
                dataType: "json",
                success: function (response) {
                    showAlert(response.message, response.type, 3000, false);

                    if(response.type == 'success'){
                        thumb.removeClass('changed');
                        if(loadedCategory != data.thumbCat) thumb.remove();
                    }else{
                        btn.removeClass('disabled');
                    }

                },
                error:function(){
                    showAlert('Server Error','danger', 3000, false);
                },
                complete: function(){
                    $( ".thumbs-container" ).sortable( "enable" );
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

        var imgCb = null;
        function selectImg (cb){
            imgCb = cb;
            if ( mediaFrame ) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media.frames.tgm_media_frame = wp.media({
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            mediaFrame.on('select', function(){
                var selection = mediaFrame.state().get('selection');
                selection.map( function( attachment ) {
                    attachment = attachment.toJSON();
                    var Data = {
                        'imgUrl': attachment.url,
                        'imgId': attachment.id,
                        'imgtitle': attachment.title
                    };
                    if(typeof(imgCb) == "function") imgCb(Data);
                    imgCb = null;
                });
            });

            mediaFrame.open();
        }

        $( ".thumbs-container" ).sortable({
            items: "div.single-linked-thumb",
            tolerance: "pointer",
            helper: 'clone',
            update: function(e, ui){

                var order = [];
                $('.single-linked-thumb .hiddden-thumb-id').each(function () {
                    var id = $(this).val();
                    order.push(id);
                });
                var cat = $('.category-select').val();
                var data = {
                    action: 'tb_reorder_thumbs',
                    items: order,
                    category: cat,
                };

                $( ".thumbs-container" ).sortable( "disable" ).addClass('sorting-process');
                showAlert('Sorting is in progress, please wait...','info', false, false);

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data:data,
                    dataType: "json",
                    success: function (response) {

                    },
                    complete: function(){
                        hideAlert();
                        $( ".thumbs-container" ).sortable( "enable" ).removeClass('sorting-process');
                    }
                });
            }
        });
        $( ".thumbs-container" ).disableSelection();

    });
})(jQuery);
