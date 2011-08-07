/**
 * Plugin to add a confirm dialog to a link
 */
(function($){  
	$.fn.confirm = function(options, callback) {   
		var defaults = {  
            message : "Confirm Action",
            title   : "Confirm Action"
		};  
		var options = $.extend(defaults, options);
		
		var container = $(this);
        
        container.live('click', function() {
            var confirm = $('<div id="delete-message">'
                + '<p>' + options.message + '</p>'
                + '<button id="confirm-button">Confirm</button>'
                + '<button id="cancel-button">Cancel</button>'
                + '</div>');
                confirm.dialog({ title: options.title });

            $('#cancel-button').live('click', function() {
                confirm.dialog('close');
            });

            $('#confirm-button').live('click', function() { 
                $.post(container.attr('href'), {});

                confirm.dialog('close');
                callback();
            });
            return false;

    });

	};  
})(jQuery); 