jQuery(document).ready(function(){
	_ppsUpdateSubBeLists();
	jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').change(function(){
		_ppsUpdateSubBeLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="sub_be_login"]').change(function(){
		_ppsUpdateSubBeLists();
	});
	jQuery('#ppsPopupEditForm').find('[name="sub_be_pass"]').change(function(){
		_ppsUpdateSubBeLists();
	});
	// This don't work, need to use jquery.on() here, but it is too much processor time for this small feature
	/*jQuery('.ppsBeHelpApiKeyLink').click(function(){
		var $dialog = jQuery('<div />').html('<img src="'+ jQuery(this).attr('href')+ '" />').dialog({
			modal:    true
		,	width: 800
		,	height: 333
		,	buttons: {
				OK: function() {
					$dialog.dialog('close');
				}
			}
		,	close: function() {
				$dialog.remove();
			}
		});
		return false;
	});*/
});
function _ppsUpdateSubBeLists() {
	if(jQuery('#ppsPopupEditForm').find('[name="params[tpl][sub_dest]"]').val() == 'benchmarkemail') {
		var login = jQuery.trim(jQuery('#ppsPopupEditForm input[name="sub_be_login"]').val())
		,	pass = jQuery.trim(jQuery('#ppsPopupEditForm input[name="sub_be_pass"]').val());
		jQuery('#ppsSubBeListsShell').hide();
		jQuery.sendFormPps({
			msgElID: 'ppsSubBeMsg'
		,	data: {mod: 'benchmarkemail', action: 'getLists', sub_be_login: login, sub_be_pass: pass}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#ppsSubBeLists').html('');
					var selectedListsIds = ppsPopup && ppsPopup.params.tpl && ppsPopup.params.tpl.sub_be_lists ? ppsPopup.params.tpl.sub_be_lists : [];
					for(var i in res.data.lists) {
						var listId = res.data.lists[ i ].id
						,	listName = res.data.lists[ i ].name
						,	selected = toeInArrayPps(listId, selectedListsIds) ? 'selected="selected"' : '';
						jQuery('#ppsSubBeLists').append('<option '+ selected+ ' value="'+ listId+ '">'+ listName+ '</option>');
					}
					jQuery('#ppsSubBeListsShell').show();
					jQuery('#ppsSubBeLists').chosen().trigger('chosen:updated');
				}
			}
		});
	}
}