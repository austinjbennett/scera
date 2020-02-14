/**
 * tabs.js
 *
 * Handles toggling the tabs
 */
jQuery(function() {
	jQuery('.tabber').click(function() {
		jQuery('.tab-block').removeClass('current');
		jQuery('.tabber').removeClass('current');
		jQuery(this).addClass('current');
		block = jQuery(this).data('target');
		jQuery(block).addClass('current');
	});
});