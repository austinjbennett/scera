jQuery(document).ready(function(){
	_ppsUpdateSubCcLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubCcLists();
	});
});
function _ppsUpdateSubCcLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'constantcontact') {
		jQuery('#ppsSubCcListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubCcMsg'
		,	data: {mod: 'constantcontact', action: 'getLists'}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubCcLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_cc_lists ? ppsPopup.params.tpl.sub_cc_lists : [];
					for(var listId in res.data.lists) {
						var selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubCcLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ res.data.lists[ listId ]+ '</option>');
					}
					jQuery('#ppsSubCcListsShell').show();
					jQuery('#ppsSubCcLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}
