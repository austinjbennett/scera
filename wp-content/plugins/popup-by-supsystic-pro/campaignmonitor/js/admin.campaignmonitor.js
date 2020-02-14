jQuery(document).ready(function(){
	_ppsUpdateSubCmLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubCmLists();
	});
});
function _ppsUpdateSubCmLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'campaignmonitor') {
		jQuery('#ppsSubCmListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubCmMsg'
		,	data: {mod: 'campaignmonitor', action: 'getLists'}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubCmLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_cm_lists ? ppsPopup.params.tpl.sub_cm_lists : [];
					for(var listId in res.data.lists) {
						var selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubCmLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ res.data.lists[ listId ]+ '</option>');
					}
					jQuery('#ppsSubCmListsShell').show();
					jQuery('#ppsSubCmLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}

