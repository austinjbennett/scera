jQuery(document).ready(function(){
	_ppsUpdateSubMrLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubMrLists();
	});
	jQuery('#ppsPopupEditForm input[name="sub_mr_api_host"]').change(function(){
		_ppsUpdateSubMrLists();
	});
	jQuery('#ppsPopupEditForm input[name="sub_mr_api_key"]').change(function(){
		_ppsUpdateSubMrLists();
	});
});
function _ppsUpdateSubMrLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'mailrelay') {
		jQuery('#ppsSubMrListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubMrMsg'
		,	data: {
				mod: 'mailrelay'
			,	action: 'getLists'
			,	sub_mr_api_host: jQuery('#ppsPopupEditForm input[name="sub_mr_api_host"]').val()
			,	sub_mr_api_key: jQuery('#ppsPopupEditForm input[name="sub_mr_api_key"]').val()
			}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubMrLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_mr_lists ? ppsPopup.params.tpl.sub_mr_lists : [];
					for(var listId in res.data.lists) {
						var selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubMrLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ res.data.lists[ listId ]+ '</option>');
					}
					jQuery('#ppsSubMrListsShell').show();
					jQuery('#ppsSubMrLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}

