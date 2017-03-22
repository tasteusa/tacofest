( function( $ ) {
    $(document).ready(function () {
        console.log('init');
        $('.vc_color_picker_param_field').colorPicker({
            renderCallback: function($elm, toggled) {
                //$elm.val('#' + this.color.colors.HEX);
            }
        });
        $('.vc_color_picker_param_field').on('change',function(){
            $(this).css('background-color',$(this).val());
        })
    });
})(jQuery);