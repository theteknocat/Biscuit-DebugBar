var DebugBar = {
	is_fullscreen: false,
	is_expanded: false,
	content_height_diffs: {},
	init: function() {
		jQuery('#debug-content').hide();

		// Cache the difference in height between the content containers and the debug bar main container
		jQuery('.debug-content').each(function() {
			var curr_content_container_height = parseInt(jQuery(this).children('.debug-content-container:first').css('height'));
			var curr_content_height_diff = 325-curr_content_container_height;
			DebugBar.content_height_diffs[this.id] = curr_content_height_diff;
		});

		// Hide all but first tab contents
		jQuery('.debug-content:not(:first)').hide();
		// Select the first tab
		jQuery('.debug-button:first').addClass('selected');
		jQuery('#debug-console .debug-content-container:not(:first)').hide();
		jQuery('#debug-error .debug-content-container:not(:first)').hide();
		jQuery('#debug-db-queries .debug-content-container:not(:first)').hide();
		jQuery('#debug-events .debug-content-container:not(:first)').hide();
		jQuery('#debug-var-dump .debug-content-container:not(:first)').hide();

		jQuery('#debug-console .debug-header:not(:first)').hide();
		jQuery('#debug-error .debug-header:not(:first)').hide();
		jQuery('#debug-db-queries .debug-header:not(:first)').hide();
		jQuery('#debug-events .debug-header:not(:first)').hide();
		jQuery('#debug-var-dump .debug-header:not(:first)').hide();

		jQuery('#debug-expand-collapse-button').click(function() {
			DebugBar.toggle_size('expanded');
			return false;
		});

		jQuery('#debug-toggle-fullscreen-button').click(function() {
			DebugBar.toggle_size('fullscreen');
			return false;
		});

		jQuery('.debug-button').click(function() {
			jQuery('.debug-button').removeClass('selected');
			jQuery(this).addClass('selected');
			jQuery('.debug-content').hide();
			var section_name = this.id.substr(7);	// Everything after 'button-'
			jQuery('#'+section_name).show();
			return false;
		});

		jQuery('.debug-flip-button').click(function() {
			var my_id = this.id.substr(18);		// Everything after "debug-flip-button-"
			var id_bits = my_id.match(/([a-z\-]+)-([0-9]+)/);
			var log_type = id_bits[1];
			var page_num = id_bits[2];
			var header_id = '#debug-'+log_type+'-header-'+page_num;
			jQuery(this).parent().parent().hide().next().hide();
			jQuery(header_id).show().next().show();
			return false;
		});
		// Wrap all body content except the debug bar in a container and fix it's height:
		jQuery("body > div[id!='debug-bar']").wrapAll('<div id="debug-page-container"></div>');
		jQuery('div#debug-page-container').css({height: (jQuery(document).height()-23)+'px'});
		// Resize the page container when the window is resized
		jQuery(window).resize(function() {
			if (DebugBar.is_fullscreen) {
				var new_content_height = (jQuery(document).height()-23);
				setTimeout('DebugBar.adjust_content_container_heights('+new_content_height+');',100);
				new_content_height += 'px';
				jQuery('#debug-content').css({'height': new_content_height});
			} else {
				if (jQuery('#debug-content').css('display') == "block") {
					var page_container_height = (jQuery(document).height()-349);
				} else {
					var page_container_height = (jQuery(document).height()-23);
				}
				jQuery('div#debug-page-container').css({'height': page_container_height+'px'});
			}
		});
	},
	toggle_size: function(mode) {
		if (!this.is_expanded && !this.is_fullscreen) {
			jQuery('#debug-content').css({'height': '0'});
		}
		if ((mode == 'fullscreen' && this.is_fullscreen) || (mode == 'expanded' && (this.is_expanded || this.is_fullscreen))) {
			if (this.is_fullscreen) {
				jQuery('#debug-page-container').show();
			}
			if (this.is_fullscreen && this.is_expanded && mode == 'fullscreen') {
				jQuery('#debug-content').animate({'height': '326px'}, 'fast', 'linear', function() {
					jQuery('#debug-page-container').css({'height': (jQuery(document).height()-349)+'px'});
					setTimeout('DebugBar.adjust_content_container_heights(326);',100);
				});
			} else {
				jQuery('#debug-content').slideUp('fast', function() {
					jQuery('#debug-content').css({'height': '0'});
					jQuery('#debug-page-container').css({'height': (jQuery(document).height()-23)+'px'});
				});
				this.is_expanded = false;
			}
			this.is_fullscreen = false;
			if (!this.is_expanded) {
				jQuery('#debug-expand-collapse-button').removeClass('expanded');
			}
		} else {
			if (mode == 'fullscreen') {
				var new_height = (jQuery(document).height()-23);
				this.is_fullscreen = true;
			} else {
				var new_height = 326;
				this.is_expanded = true;
			}
			var new_content_adjust_height = new_height;
			new_height += 'px';
			jQuery('#debug-content').animate({'height': new_height}, 'fast', 'linear', function() {
				jQuery('#debug-expand-collapse-button').addClass('expanded');
				setTimeout('DebugBar.adjust_content_container_heights('+new_content_adjust_height+');',100);
				if (DebugBar.is_fullscreen) {
					jQuery('#debug-page-container').hide();
				} else if (DebugBar.is_expanded) {
					jQuery('#debug-page-container').css({'height': (jQuery(document).height()-349)+'px'});
				}
			});
		}
	},
	adjust_content_container_heights: function(bar_height) {
		jQuery('.debug-content').each(function() {
			var new_container_height = (bar_height-DebugBar.content_height_diffs[this.id])+'px';
			jQuery(this).children('.debug-content-container').css({'height': new_container_height});
		});
	}
}

jQuery(document).ready(function() {
	DebugBar.init();
});