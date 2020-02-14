jQuery(document).ready(function(){
	_ppsUpdateSubSgLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubSgLists();
	});
	jQuery('#ppsPopupEditForm input[name="sub_sg_api_name"]').change(function(){
		_ppsUpdateSubSgLists();
	});
	jQuery('#ppsPopupEditForm input[name="sub_sg_api_key"]').change(function(){
		_ppsUpdateSubSgLists();
	});
});
function _ppsUpdateSubSgLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'sendgrid') {
		jQuery('#ppsSubSgListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubSgMsg'
		,	data: {
				mod: 'sendgrid'
			,	action: 'getLists'
			,	sub_sg_api_name: jQuery('#ppsPopupEditForm input[name="sub_sg_api_name"]').val()
			,	sub_sg_api_key: jQuery('#ppsPopupEditForm input[name="sub_sg_api_key"]').val()
			}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubSgLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_sg_lists ? ppsPopup.params.tpl.sub_sg_lists : [];
					for(var listId in res.data.lists) {
						var selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubSgLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ res.data.lists[ listId ]+ '</option>');
					}
					jQuery('#ppsSubSgListsShell').show();
					jQuery('#ppsSubSgLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}

