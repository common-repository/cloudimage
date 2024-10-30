(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$('#cloudimage-domain').keyup(
		$.debounce(500, function(e) {
		    $(".v7-check-processing").show();
			$(".v7-check-notices").hide();

	        var data = {
	            action: 'cloudimage_check_v7',
	            token: $(e.target).val()
	        };

	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            data: data
	        }).done(function (res, textStatus, jqXHR) {
	            res = JSON.parse(res);

	            if (res.v7_check_successful == 1)
	            {
	            	$("#cloudimage-removes_v7").prop("checked", res.remove_v7);
	            }
	            else
	            {
	            	$(".v7-check-notices").show();
	            	$(".v7-check-notices").text(res.err_msg);
	            	$("#cloudimage-removes_v7").prop("checked", 1);
	            }

	            $(".v7-check-processing").hide();
	        }).fail(function(jqXHR, textStatus, errorThrown) {
	        	console.error('V7 Check Error');
	        	$(".v7-check-processing").hide();
	        });
		})
	);

})( jQuery );
