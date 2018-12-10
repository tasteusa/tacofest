( function( $ ) {
    $(document).ready(function () {
        var trigger = document.createElement("div");
        trigger.setAttribute('id','eventbrite-widget-modal-trigger-'+buttonInfo.id);
        document.body.appendChild(trigger);
        $('.t-buy-tickets-btn').find('a').on('click', function(e){
            e.preventDefault();
            $('#eventbrite-widget-modal-trigger-'+buttonInfo.id).trigger('click');
        });
    });
})(jQuery);
