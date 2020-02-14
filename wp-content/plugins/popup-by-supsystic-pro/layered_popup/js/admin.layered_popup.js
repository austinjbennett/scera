jQuery(document).ready(function(){
	// Selecting popup layered position
	jQuery('.ppsLayeredPosCell').click(function(){
		jQuery('.ppsLayeredPosCell').removeClass('active');
		jQuery(this).addClass('active')
		jQuery('#ppsPopupEditForm input[name="params[tpl][layered_pos]"]').val( jQuery(this).data('pos') );
	});
	
	var defaultPos = ppsPopup.params && ppsPopup.params.tpl.layered_pos ? ppsPopup.params.tpl.layered_pos : 'top';
	jQuery('.ppsLayeredPosCell[data-pos="'+ defaultPos+ '"]').trigger('click');
});
