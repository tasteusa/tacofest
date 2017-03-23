( function( $ ) {
    $(document).ready(function () {

        $( ".lgts-categories-list" ).sortable({
            items: "li.lgts-categories-list-item",
            tolerance: "pointer",
            helper: 'clone',
            update: function(e, ui){
                var orderArray = ["0"];
                $( ".lgts-categories-list li.lgts-categories-list-item" ).each(function(ind){
                    orderArray.push($(this).data('term_id'));
                });

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data:{
                        action: 'tb_reorder_cat',
                        orderArray: orderArray
                    },
                    dataType: "json",
                    success: function (response) {

                    },
                    complete: function(){

                    }
                });
            }
        });
        $( ".lgts-categories-list" ).disableSelection();

    });
})(jQuery);
