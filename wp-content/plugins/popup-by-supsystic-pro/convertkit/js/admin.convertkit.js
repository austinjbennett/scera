jQuery(document).ready(function(){
	_ppsUpdateSubCkLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubCkLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_ck_api_key]"]').change(function(){
		_ppsUpdateSubCkLists();
	});
});
function _ppsUpdateSubCkLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'convertkit') {
		var apiKey = jQuery.trim(jQuery('#ppsPopupEditForm input[name="params[tpl][sub_ck_api_key]"]').val());
		jQuery('#ppsSubCkListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubCkMsg'
		,	data: {mod: 'convertkit', action: 'getLists', api_key: apiKey}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubCkLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_ic_lists ? ppsPopup.params.tpl.sub_ic_lists : [];
					for(var i in res.data.lists) {
						var listId = res.data.lists[ i ].id
						,	listName = res.data.lists[ i ].name
						,	selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubCkLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ listName+ '</option>');
					}
					jQuery('#ppsSubCkListsShell').show();
					jQuery('#ppsSubCkLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}