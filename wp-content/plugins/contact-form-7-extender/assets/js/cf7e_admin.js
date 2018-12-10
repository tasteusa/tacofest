jQuery(document).on('click', 'a.title', function (e) {
	e.preventDefault();

	var collapse = jQuery(this).parent().find('.collapse');

	if ( !collapse.hasClass('open') ) {
		jQuery('.contact-form-7-wrapper .collapse').slideUp('slow').removeClass('open');
		collapse.slideDown('slow').addClass('open');
	} else {
		collapse.slideUp('slow').removeClass('open');
	}
})
jQuery(document).on('click', '.pagination a', function(e) {
	if( jQuery(this).parent().hasClass('disabled') ) {
		e.preventDefault();
		return false;
	}
});
jQuery(document).on('click', '#select-all', function (e) {
	jQuery('.contact-form-7-wrapper input[type="checkbox"]').each( function(i){
		jQuery(this).prop('checked', true);
	});
	jQuery('.contact-form-7-wrapper input[name="select-all"]').val(1);
});
jQuery(document).on('click', '#unselect-all', function (e) {
	jQuery('.contact-form-7-wrapper input[type="checkbox"]').each( function(i){
		jQuery(this).prop('checked', false);
	});
	jQuery('.contact-form-7-wrapper input[name="select-all"]').val(0);
});
jQuery(document).on('click', '.contact-form-7-wrapper input[type="checkbox"]', function (e) {
	jQuery('.contact-form-7-wrapper input[name="select-all"]').val(0);
});
jQuery(document).on('click', '#delete-selected', function (e) {
	
	var url = jQuery(this).attr('data-url');

	var deleteArr = [];
	var singleChecked = [];
	var counter = 0;
	jQuery('.contact-form-7-wrapper input[type="checkbox"]').each( function(i){
		if (jQuery(this).prop("checked")) {
			var parentSingle = jQuery(this).closest('.single');
			parentSingle.find('.loader').show();
			deleteArr[counter] = [jQuery(this).attr('name'),jQuery(this).val()];
			singleChecked[counter] = parentSingle.attr('data-id');
			counter = counter+1;
		}		
	});

	if (deleteArr.length > 0) {
		var lengthDel = deleteArr.length + ' number of records';
		if (jQuery('.contact-form-7-wrapper input[name="select-all"]').val() == '1') {
			lengthDel = 'all records'
		}
		if (confirm( 'You are about to delete ' + lengthDel +' - are you sure?')) {	
			jQuery.ajax({
				type: 'POST',
				url: url,
				data: {action : 'cf7e_delete_record', deleteArr: deleteArr, all: jQuery('.contact-form-7-wrapper input[name="select-all"]').val()},
				success: function(resp) {
					jQuery('.loader').hide();
					jQuery('.flash-message').html(resp).slideDown();
					singleChecked.forEach(function($item, index) {
						jQuery('.single[data-id="' + $item + '"').slideUp().remove();
					});
					setTimeout(hideFlashMessage, 5000);
				}
			});
		} else {
			jQuery('.loader').hide();
		}
	}	
});

function hideFlashMessage() {
	jQuery('.flash-message').slideUp();
}

jQuery(document).on('click', '#cf7e-filter a#export', function (e) {
	e.preventDefault();
	var url = jQuery(this).attr('href');
	var sort = jQuery(this).attr('data-sort');
	var site = jQuery(this).attr('data-site');
	jQuery('#cf7e-filter .loader').show();
	var limit = jQuery('#cf7e-filter select[name="message-limit"]').val();
	if (jQuery('.contact-form-7-wrapper input[name="select-all"]').val() == '1' ) {
		limit = 'all';
	}
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: {action : 'cf7e_export_csv', sort: sort, site_url: site, 'message-limit': limit},
		success: function(resp) {
			jQuery('.responce').html(resp).slideDown();
			jQuery('.loader').hide();
		}
	});
});

jQuery(document).on('click', '#cf7e-filter .responce a', function (e) {
	e.preventDefault();
	var file = jQuery(this).attr('data-href');
	window.open(file);
});
