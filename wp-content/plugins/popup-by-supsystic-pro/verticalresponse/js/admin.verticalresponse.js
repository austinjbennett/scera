jQuery(document).ready(function(){
	_ppsUpdateSubVrLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubVrLists();
	});
});
function _ppsUpdateSubVrLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'verticalresponse') {
		jQuery('#ppsSubVrListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubVrMsg'
		,	data: {mod: 'verticalresponse', action: 'getLists'}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubVrLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_vr_lists ? ppsPopup.params.tpl.sub_vr_lists : [];
					for(var i in res.data.lists) {
						var listId = res.data.lists[ i ].id
						,	listName = res.data.lists[ i ].name
						,	selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubVrLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ listName+ '</option>');
					}
					jQuery('#ppsSubVrListsShell').show();
					jQuery('#ppsSubVrLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}