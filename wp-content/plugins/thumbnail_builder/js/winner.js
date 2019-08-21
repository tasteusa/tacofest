( function( $ ) {

    $.fn.getStyleObject = function(){
        var dom = this.get(0);
        var style;
        var returns = {};
        if(window.getComputedStyle){
            var camelize = function(a,b){
                return b.toUpperCase();
            };
            style = window.getComputedStyle(dom, null);
            for(var i=0;i<style.length;i++){
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/g, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            }
            return returns;
        }
        if(dom.currentStyle){
            style = dom.currentStyle;
            for(var prop in style){
                returns[prop] = style[prop];
            }
            return returns;
        }
        return this.css();
    };

    $(document).ready(function () {
        var mediaFrame = false;
        var loadedCategory = null;
        var parentSelector = (typeof(winnerBlock) != "undefined") ? winnerBlock+' ' : '';
        var alert = $('<div class="alert thumbs-alert"></div>');
        var alertTimeout = null;
        var alertRolesAliases ={
            'error':'danger'
        };
        var cText = ['', '1st Place Winner', '2nd Place Winner', '3rd Place Winner'];

        function showAlert(message, role, timeout, cb){
            if(typeof(alertRolesAliases[role]) == "string") role = alertRolesAliases[role];

            if($(parentSelector+'.thumbs-alert').length > 0) $(parentSelector+'.thumbs-alert').remove();
            var tmpAlert = alert.clone(true).addClass('alert-'+role).html(message);
            $(parentSelector+'.form-container').before(tmpAlert);

            if(typeof(timeout) !="undefined" && timeout){
                if(alertTimeout != null) clearTimeout(alertTimeout);
                setTimeout(function(){
                    hideAlert();
                    alertTimeout == null;
                    if(typeof (cb) == "function")cb();
                },timeout)
            }
        }
        function hideAlert(){
            if($(parentSelector+'.thumbs-alert').length > 0) $(parentSelector+'.thumbs-alert').remove();
        }

        $(parentSelector+'.load-thumbs').on('click',function(){
            var cat = $(parentSelector+'.category-select').val();
            var btn = $(this);
            var filter = $(parentSelector+'.city-select').val();
            loadWinnerThumbsContainer(cat, btn, filter);
            $(parentSelector+'.sort-thumbs-by-city').removeClass('disabled');
        });

        $(parentSelector+'.sort-thumbs-by-city').on('click',function(){
            var cat = $(parentSelector+'.category-select').val();
            var btn = $(this);
            var filter = $(parentSelector+'.city-select').val();
            loadWinnerThumbsContainer(cat, btn, filter);
        });

        function loadWinnerThumbsContainer(cat, btn, filter) {
            btn.addClass('disabled');
            var btn_name = btn.html();
            btn.html('Please Wait...');
            $(parentSelector+".row.thumbs-container" ).empty();
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:{
                    'action': 'tb_get_winners_thumbs',
                    'filter': filter,
                    'tax': cat
                },
                dataType: "json",
                success: function (response) {

                    if(typeof(response.thumbs) != 'undefined' && response.thumbs.length > 0){
                        var thumbHtml = $("#thumbWinnerTemplate" ).tmpl( response.thumbs ).appendTo(parentSelector+".row.thumbs-container" );
                        $(parentSelector+".row.thumbs-container" ).append(thumbHtml);
                    }else{
                        showAlert('No Thumbnails Found','info', 3000, false);
                    }
                    loadedCategory = cat;

                    $(document).find('.winner-text').draggable({
                        cursor: "move",
                        start: function (event, ui) {
                            $(this).css("border", "1px dashed red");
                        },
                        stop: function (event, ui) {
                            $(this).css("border", "");
                            $(this).closest('.thumbnail').find('.congrats-hiddden-text-position').val($(this).attr('style'));
                        }
                    }).resizable({
                        start: function (event, ui) {
                            $(this).css("border", "1px dashed red");
                        },
                        stop: function (event, ui) {
                            $(this).css("border", "");
                            $(this).closest('.thumbnail').find('.congrats-hiddden-text-position').val($(this).attr('style'));
                        }
                    });

                    $(document).find('.winner-image')
                        .draggable({
                            cursor: "move",
                            start: function (event, ui) {
                                $(this).css("border", "1px dashed red");
                            },
                            stop: function (event, ui) {
                                $(this).css("border", "");
                                $(this).closest('.thumbnail').find('.congrats-hiddden-img-position').val($(this).attr('style'));
                            }
                        })
                        .resizable({
                            start: function (event, ui) {
                                $(this).css("border", "1px dashed red");
                            },
                            stop: function (event, ui) {
                                $(this).css("border", "");
                                $(this).closest('.thumbnail').find('.congrats-hiddden-img-position').val($(this).attr('style'));
                            }
                        });


                    $(document).find('.single-winner-thumb').each(function () {
                        var $this = $(this);
                        if(!$this.find('.winner-text p').text().trim().length){
                            $this.find('.winner-text').addClass('hidden');
                        }
                        if($this.find('.winner-image').css('background-image').trim() == 'none'){
                            $this.find('.winner-image').addClass('hidden');
                        }

                        var handle = $this.find( ".custom-handle" );
                        $this.find( ".slider" ).slider({
                            range: "max",
                            min: 1,
                            max: 72,
                            value: 22,
                            create: function() {
                                handle.text( $( this ).slider( "value" ) );
                            },
                            slide: function( event, ui ) {
                                handle.text( ui.value );
                                $this.find('.winner-text').css('font-size', ui.value+'px');

                            }
                        });
                        if($this.find('.congrats-text-field').val() == ''){
                            var winnerTextDefaultStyles = 'position: absolute !important;'+
                                'top: auto;'+
                                'bottom: 0;'+
                                'left: 0;'+
                                'border: 0;'+
                                'text-align: center;'+
                                'width: 100%;'+
                                'font-size:22px;'+
                                'background-color: #e11f59;';

                            $this.find('.congrats-text-field').val(cText[$this.find('.place-select').val()]);
                            $this.find('.winner-text').removeClass('hidden');
                            $this.find('.winner-text p').html($this.find('.congrats-text-field').val());
                            $this.find('.winner-text').attr('style',winnerTextDefaultStyles);
                            $this.find('.congrats-hiddden-text-position').val(winnerTextDefaultStyles);

                        }





                    });





                },
                error:function(){
                    loadedCategory = null;
                    showAlert('Server Error','danger', 3000, false);
                },
                complete: function(){
                    btn.removeClass('disabled');
                    btn.html(btn_name);
                }
            });
        }

        $(document).on('keyup', '[name="congrats-text"]', function(){
            var el = $(this).closest('.thumbnail');
            var winner_text = el.find('.winner-text p');
            var text_str = $(this).val();

            if(text_str.length){
                el.find('.winner-text').removeClass('hidden');
                el.find('.congrats-hiddden-text-position').val(el.find('.winner-text').attr('style'));
            } else {
                el.find('.winner-text').addClass('hidden');
                el.find('.winner-text').removeAttr('style');
                el.find('.congrats-hiddden-text-position').val('');
            }
            winner_text.html(text_str);

        });


        $(document).on('change', '.congrats-text-color-field', function () {
            var color = $(this).val();
            var el = $(this).closest('.thumbnail');
            el.find('.winner-text').css('color',color);
            el.find('.congrats-hiddden-text-position').val(el.find('.winner-text').attr('style'));
        });

        $(document).on('click', '.congrats-font-weight-field', function () {
            var el = $(this).closest('.thumbnail');
            if($(this).prop('checked')){
                el.find('.winner-text').css('font-weight',700);
            } else {
                el.find('.winner-text').css('font-weight',400);
            }
            el.find('.congrats-hiddden-text-position').val(el.find('.winner-text').attr('style'));
        });

        $(document).on('change', '.congrats-text-align-field', function () {
            var align = $(this).val();
            var el = $(this).closest('.thumbnail');
            el.find('.winner-text').css('text-align',align);
            el.find('.congrats-hiddden-text-position').val(el.find('.winner-text').attr('style'));
        });

        $(document).on('change', '.congrats-text-background-color-field', function () {
            var color = $(this).val();
            var el = $(this).closest('.thumbnail');
            el.find('.winner-text').css('background-color',color);
            el.find('.congrats-hiddden-text-position').val(el.find('.winner-text').attr('style'));


        });

        $(document).on('click', '.use-transparent-bg', function () {
            var el = $(this).closest('.thumbnail');
            if($(this).prop('checked')){
                el.find('.background-color-wrapper').addClass('hidden');
                el.find('.winner-text').css('background-color', 'transparent');
            } else {
                el.find('.winner-text').css('background-color', el.find('.congrats-text-background-color-field').val());
                el.find('.background-color-wrapper').removeClass('hidden');
            }
            el.find('.congrats-hiddden-text-position').val(el.find('.winner-text').attr('style'));
        });


        $(parentSelector+".thumbs-container" ).on('click','.single-winner-thumb .save-thumb', function(){
            var btn = $(this);
            var thumb = btn.closest('.single-winner-thumb');

            //$(parentSelector+".thumbs-container" ).sortable( "disable" );
            var isWinner = thumb.find('.is-winner').prop('checked');
            var winnerPlace = thumb.find('.place-select');
            var data = {
                action: 'tb_winner_edit_thumb',
                thumbId: thumb.find('.hiddden-thumb-id').val(),
                thumbWinner: isWinner,
                thumbWinnerPlace: winnerPlace.val(),
                thumbCongratsText: thumb.find('.congrats-text-field').val(),
                thumbCongratsTextAlign: thumb.find('[name="congrats-text-align"]:checked').val(),
                thumbCongratsTextColor: thumb.find('.congrats-text-color-field').val(),
                thumbCongratsFontWeight: thumb.find('.congrats-font-weight-field').prop('checked'),
                thumbCongratsUseTransparentBg: thumb.find('.use-transparent-bg').prop('checked'),
                thumbCongratsTextBackgroundColor: thumb.find('.congrats-text-background-color-field').val(),
                thumbCongratsTextPosition: thumb.find('.congrats-hiddden-text-position').val(),
                thumbCongratsImageId: thumb.find('.congrats-hiddden-img-id').val(),
                thumbCongratsImagePosition: thumb.find('.congrats-hiddden-img-position').val(),
                thumbImageId: thumb.find('.hiddden-thumb-id').val(),
            };
            if(isWinner){
                if(winnerPlace.val() == '0'){
                    winnerPlace.addClass('danger');
                    thumb.find('.require-error').text('Choose Place is required for winner' );
                    return;
                } else {
                    winnerPlace.removeClass('danger');
                    thumb.find('.require-error').empty();
                }
            }
            showAlert('Please wait...','info');

            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:data,
                dataType: "json",
                success: function (response) {
                    showAlert(response.message, response.type, 3000, false);

                    if(response.type == 'success'){
                        //thumb.removeClass('changed');
                    }else{
                        btn.removeClass('disabled');
                    }
                },
                error:function(){
                    showAlert('Server Error','danger', 3000, false);
                },
                complete: function(){

                }
            });
        });

        $(document).on('click', '.select-img-btn', function(){
            var this_winner = $(this).closest('.single-winner-thumb');

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
                    console.log(this_winner.find('.image-wrapper-block').attr('style'));
                    this_winner.find('.congrats-hiddden-img-id').val(attachment.id);

                    this_winner.find('.winner-image').attr('style', 'background-image:url('+attachment.url+');' +
                        'border: 1px dashed red;');
                    this_winner.find('.winner-image').removeClass('hidden');

                    this_winner.find('.congrats-hiddden-img-position').val('background-image:url('+attachment.url+');');


                });
            });
            mediaFrame.open();
        });


        $(document).on('click', '.clear', function () {
           var el = $(this).closest('.single-winner-thumb');
            el.find('.winner-image').removeAttr('style').addClass('hidden');
            el.find('.congrats-hiddden-img-position').val('');
        });

        $(document).on('click', '.delete-winner-thumb', function () {
           var thumb = $(this).closest('.single-winner-thumb');
            var data = {
                action: 'tb_delete_winner_thumb',
                thumbId: thumb.find('.hiddden-thumb-id').val(),
            };
            if(confirm("Do you really want to remove this winner?")){
                showAlert('Please wait...','info');

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data:data,
                    dataType: "json",
                    success: function (response) {
                        showAlert(response.message, response.type, 3000, false);

                        if(response.type == 'success'){
                            thumb.remove();
                        }
                    },
                    error:function(){
                        showAlert('Server Error','danger', 3000, false);
                    },
                    complete: function(){

                    }
                });
            }



        });



    });

})(jQuery);