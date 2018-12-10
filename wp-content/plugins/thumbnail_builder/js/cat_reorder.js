( function( $ ) {
    var parentSelector = (typeof(reorderCatBlock) != "undefined") ? reorderCatBlock+' ' : '';
    var thumbsSelector = (typeof(reorderBlock) != "undefined") ? reorderBlock+' ' : '';
    $(document).ready(function () {
        var currentPage = null;
        $( parentSelector+".lgts-categories-list" ).sortable({
            items: "li.lgts-categories-list-item",
            tolerance: "pointer",
            helper: 'clone',
            update: function(e, ui){
                var orderArray = ["0"];
                $( parentSelector+".lgts-categories-list li.lgts-categories-list-item" ).each(function(ind){
                    orderArray.push($(this).data('term_id'));
                });

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data:{
                        action: 'tb_reorder_cat',
                        orderArray: orderArray,
                        pageId: currentPage
                    },
                    dataType: "json",
                    success: function (response) {

                    },
                    complete: function(){

                    }
                });
            }
        });
        $( parentSelector+".lgts-categories-list" ).disableSelection();

        $( document ).on('click', parentSelector+'.load-cats', function(e){
            var catInp = $(parentSelector+'.pages-select');
            currentPage = ( catInp.length > 0 && catInp.val() != '0' ) ? catInp.val() : null;
            loadCategoriesForPage($(this))

        });

        function loadCategoriesForPage(btn) {
            btn.addClass('disabled');
            var btn_name = btn.html();
            btn.html('Please Wait...');
            $(parentSelector+".lgts-categories-list").empty();

            $.ajax({
                method: "POST",
                url: ajaxurl,
                data:{
                    pageId: currentPage,
                    action: 'tb_get_cat_by_page'
                },
                dataType: "json",
                success: function (response) {
                    if(response.type == 'success'){
                        $("#categoryTemplate" ).tmpl( response.categories ).appendTo(parentSelector+".lgts-categories-list" )
                    }
                },
                error:function(){

                },
                complete: function(){
                    btn.removeClass('disabled');
                    btn.html(btn_name);
                }
            });
        }

        $( document ).on('click', parentSelector+'.view-thumbnails-link', function(e){
            e.preventDefault();
            if(thumbsSelector == '') return false;

            var link = $(this);
            $(thumbsSelector+'.category-select').val(link.data('cat-id'));
            $(thumbsSelector+'.load-thumbs').click();
            $('a[href='+reorderBlock+']').click();

            return false;

        });

    });
})(jQuery);
