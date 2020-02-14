jQuery(document).ready(function(){
	_ppsUpdateSubGrLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubGrLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_gr_api_key]"]').change(function(){
		_ppsUpdateSubGrLists();
	});
});
function _ppsUpdateSubGrLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'get_response') {
		var apiKey = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_gr_api_key]"]').val());
		jQuery('#ppsSubGrListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubGrMsg'
		,	data: {mod: 'get_response', action: 'getLists', api_key: apiKey}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubGrLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_gr_lists ? ppsPopup.params.tpl.sub_gr_lists : [];
					for(var i in res.data.lists) {
						var listId = res.data.lists[ i ].id
						,	listName = res.data.lists[ i ].name
						,	selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubGrLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ listName+ '</option>');
					}
					jQuery('#ppsSubGrListsShell').show();
					jQuery('#ppsSubGrLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}