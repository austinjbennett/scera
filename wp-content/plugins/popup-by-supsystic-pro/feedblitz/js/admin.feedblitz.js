jQuery(document).ready(function(){
	_ppsUpdateSubFeedbLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubFeedbLists();
	});
	jQuery('#ppsPopupEditForm input[name="params[tpl][sub_feedb_key]"]').change(function(){
		_ppsUpdateSubFeedbLists();
	});
});
function _ppsUpdateSubFeedbLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'feedblitz') {
		jQuery('#ppsSubFeedbListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubFeedbMsg'
		,	data: {
				mod: 'feedblitz'
			,	action: 'getLists'
			,	sub_feedb_api_name: jQuery('#ppsPopupEditForm input[name="sub_feedb_api_name"]').val()
			,	sub_feedb_api_key: jQuery('#ppsPopupEditForm input[name="sub_feedb_api_key"]').val()
			}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubFeedbLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_feedb_lists ? ppsPopup.params.tpl.sub_feedb_lists : [];
					for(var listId in res.data.lists) {
						var selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubFeedbLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ res.data.lists[ listId ]+ '</option>');
					}
					jQuery('#ppsSubFeedbListsShell').show();
					jQuery('#ppsSubFeedbLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}

