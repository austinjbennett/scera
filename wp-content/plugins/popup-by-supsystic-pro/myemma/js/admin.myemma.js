jQuery(document).ready(function(){
	_ppsUpdateSubMemLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubMemLists();
	});
	jQuery('#ppsPopupEditForm input[name="params[tpl][sub_mem_acc_id]"]').change(function(){
		_ppsUpdateSubMemLists();
	});
	jQuery('#ppsPopupEditForm input[name="params[tpl][sub_mem_pud_key]"]').change(function(){
		_ppsUpdateSubMemLists();
	});
	jQuery('#ppsPopupEditForm input[name="params[tpl][sub_mem_priv_key]"]').change(function(){
		_ppsUpdateSubMemLists();
	});
});
function _ppsUpdateSubMemLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'myemma') {
		jQuery('#ppsSubMemListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubMemMsg'
		,	data: {
				mod: 'myemma'
			,	action: 'getLists'
			,	sub_mem_acc_id: jQuery('#ppsPopupEditForm input[name="params[tpl][sub_mem_acc_id]"]').val()
			,	sub_mem_pud_key: jQuery('#ppsPopupEditForm input[name="params[tpl][sub_mem_pud_key]"]').val()
			,	sub_mem_priv_key: jQuery('#ppsPopupEditForm input[name="params[tpl][sub_mem_priv_key]"]').val()
			}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubMemLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_mem_lists ? ppsPopup.params.tpl.sub_mem_lists : [];
					for(var listId in res.data.lists) {
						var selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubMemLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ res.data.lists[ listId ]+ '</option>');
					}
					jQuery('#ppsSubMemListsShell').show();
					jQuery('#ppsSubMemLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}

